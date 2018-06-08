<?php
	
	/**
 * Plugin Name: Floating Banner Ad Rotator with Tracking
 * Plugin URI: https://wordpress.org/plugins/floating-ad-rotator-with-tracking/
 * Text Domain: floating-ad-rotator-with-tracking
 * Description: Display a Floating Banner Rotator Box at the Bottom of Your Site. Track Banner Views, Clicks and Clickthroughs or add Custom Banner Tracking HTML from External Services.
 * Version: 2.0
 * Author: HTML5andBeyond
 * Author URI: http://www.html5andbeyond.com/
 * License: GPL2 or Later
 */

 //Define constant vars

	if ( ! defined( 'ABSPATH' ) ) exit;

	define( 'H5AB_BANNER_PLUGIN_DIR', plugin_dir_path( __FILE__ ));
	define('H5AB_BANNER_PLUGIN_URL', plugin_dir_url( __FILE__ ));
	include_once( H5AB_BANNER_PLUGIN_DIR . 'includes/h5ab-banner-rotator-functions.php');
    include( H5AB_BANNER_PLUGIN_DIR . 'includes/htmLawed.php' );
	
	//Needed for file upload purposes
	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	require_once( ABSPATH . 'wp-admin/includes/media.php' );


	if(!class_exists('H5AB_Banner')) { 
			
			class H5AB_Banner {
			
			    const _default = 'h5ab_banner_array';
				const _custom = 'h5ab_banner_customhtml';
				const _advertType = 'h5ab_banner_advert_type';
				const _bannerSettings = 'h5ab_banner_settings';
				const _sessionSettings = 'h5ab_banner_session';
				
				private $formResponse = 0;
				private $advertArrayKeyIndex  = 0;
				
				public static $htmlawedconfig = array(
										'style_pass'=>1,
										'unique_ids'=>0,
										'css_expression'=>1,
										'safe'=>1,
										'keep_bad'=>2
										);
				 
				public function __construct() {
					
					add_action( 'init', array('H5AB_Banner', 'update_advert_settings'), 1);
                    add_action( 'init', array($this, 'load_scripts'), 2);
					add_action( 'init', array($this, 'validate_form_callback'), 3);

                    add_filter( 'h5ab_output_banner_html', array($this, 'construct_banner_html'), 1, 2);
					
					//Sets up the plugin settings page
					add_action('admin_menu', array($this, 'add_menu')); 
					
                    //Admin only Scripting and CSS files
                    add_action( 'admin_enqueue_scripts', array($this, 'admin_init'));
					
					//Ajax for admins only
					add_action('wp_ajax_remove_image', 'h5ab_banner_remove_image_callback');
					add_action('wp_ajax_set_type', 'h5ab_banner_set_type_callback');
					
					//Ajax for admins and non logged-in users
					add_action('wp_ajax_nopriv_view_click', 'h5ab_banner_view_click_callback');
					
					//Footer
                    add_action('wp_footer', array($this, 'load_advert_box'), 10 );

                    //Translations
                    add_action('plugins_loaded', array($this, 'h5ab_fbar_lang'));
					
				}
				
				public function admin_init() {
				
                    wp_register_style('h5ab-banner-rotator-admin-css', H5AB_BANNER_PLUGIN_URL . 'css/h5ab-banner-rotator-admin.css');
					wp_enqueue_style( 'h5ab-banner-rotator-admin-css' );

                    wp_register_script('dynamic-upload-script', H5AB_BANNER_PLUGIN_URL . 'js/h5ab-dynamic-upload.js', array('jquery'), '', true);
					wp_enqueue_script('dynamic-upload-script');
					
					wp_localize_script( 'dynamic-upload-script', 'ajax_object',
					array( 'ajax_url' => admin_url( 'admin-ajax.php' ) /*, 'we_value' => 1234*/ ) );
				

                    wp_enqueue_media();

				}
				
				public function add_menu() {
				
					/*$page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position*/
					add_menu_page('Banner Rotator', __('Banner Rotator', 'floating-ad-rotator-with-tracking'), 'administrator', 'banner-rotator-settings',
					array($this, 'plugin_settings_page'), H5AB_BANNER_PLUGIN_URL . 'images/icon.png');
					
				}
				
				public function plugin_settings_page() {
				
					if(!current_user_can('administrator')) {
						  wp_die('You do not have sufficient permissions to access this page.');
					}
				
					include_once(sprintf("%s/templates/h5ab-banner-rotator-settings.php", H5AB_BANNER_PLUGIN_DIR));
				
				}
				
				public function load_scripts() {
				
				    $h5ab_banner_settings = get_option(self::_bannerSettings);
				  
					wp_register_style('h5ab-banner-rotator-css', H5AB_BANNER_PLUGIN_URL . 'css/h5ab-banner-rotator.css');
					wp_enqueue_style( 'h5ab-banner-rotator-css' );

                    wp_register_script('h5ab-banner-rotator-script', H5AB_BANNER_PLUGIN_URL . 'js/h5ab-banner-rotator.js', array('jquery'), '', true);

                    $h5ab_banner_settings = get_option(H5AB_Banner::_bannerSettings);
				
                    $h5ab_session_settings = get_option(H5AB_Banner::_sessionSettings);

                    $translation_array = array(
                        'session_value' => $h5ab_session_settings['h5ab_banner_session'],
                        'admin_session' => $h5ab_banner_settings['h5ab_admin_preview'],
						'show_to_visitors' => $h5ab_banner_settings['h5ab_visitor_show'],
						'delay' => $h5ab_banner_settings['h5ab_advert_delay']
                    );

                    wp_localize_script( 'h5ab-banner-rotator-script', 'session_data', $translation_array );

                   wp_localize_script( 'h5ab-banner-rotator-script', 'ajax_object',
				   array( 'ajax_url' => admin_url( 'admin-ajax.php' )));

                    wp_enqueue_script('h5ab-banner-rotator-script');
				
				}

				public function setFormResponse($response) {
					$class = ($response->success) ? 'updated' : 'error';
				    $this->formResponse =  '<div = class="' . $class . '"><p>' . $response->message . '</p></div>';
				}

				public function getFormResponse() {
				    $fr = $this->formResponse;
				    echo $fr;
				}
				
				public function validate_form_callback() {
					
					if (isset($_POST['h5ab_banner_upload_nonce'])) {
				
							if(wp_verify_nonce( $_POST['h5ab_banner_upload_nonce'], 'h5ab_banner_upload' )) {
								   
										$response = h5ab_banner_upload();

									    $this->setFormResponse($response);

								       add_action('admin_notices',  array($this, 'getFormResponse'));
									
							} else {
								wp_die("You do not have access to this page");
							}
									
					} 
						
				}
				
				
				private function findValidAdvertIndex($index, $bannerArray) {
							
							$bannerArrayKeys = array_keys($bannerArray);
							
							$key = false;
							
						    if(array_key_exists($index, $bannerArrayKeys)){
								$key = $bannerArrayKeys[$index];	
							}
				            
							 //If the banner exists and is linked to a valid image
							 if ($key !== false) {
									$this->advertArrayKeyIndex = $index; 	
									return $index;
									
							 } else {
							
								$array_keys_copy = $bannerArrayKeys;
								end($array_keys_copy);
								
								//If the index is greater than the length of array_keys
								if($index > key($array_keys_copy)) { 
									$index = -1; 
								}
								
								$next = $index + 1;  
								$this->findValidAdvertIndex($next, $bannerArray);
							}
			  }
			  

				/* Show Banners/Adverts to users */
               public function load_advert_box() {
					
					$h5ab_banner_settings = get_option(self::_bannerSettings);
				
						if (get_option(self::_advertType) == self::_default) { 

							$bannerArray =  json_decode(get_option(self::_default), true);

							if(!empty($bannerArray)) {
							
								if($h5ab_banner_settings['h5ab_randomize']['active'] == 'false')
							   {	
									 $bannerArrayKeys = array_keys($bannerArray);
									 $lastIndex = $h5ab_banner_settings['h5ab_randomize']['index'];
									 $nextIndex = ($lastIndex) + 1; 

									/*  
									*  Recursive function will keep incrementing number by one until it finds a valid array key index
									*  which is stored in the class variable $this->advertArrayKeyIndex, the reason I have done this is 
									*  because the return value of the recursive function can't be assigned to a variable.
									*/
									
									$this->findValidAdvertIndex($nextIndex, $bannerArray); 
									
									/*
									*  Note: The array key index will be saved in the database only if the banner is viewed
									* This occurs in: h5ab-banner-rotator-functions.php/h5ab_banner_view_click_callback function
									*/
									
									$key_index = $this->advertArrayKeyIndex;
									//Selected banner index
									$index = $bannerArrayKeys[$key_index];

								} else {

										
										//$key_index isn't required in randomize mode so set to empty string
										$key_index = '';
										//Select a random advert
										$index = array_rand($bannerArray);
									
								}
										 
										    $linkURL = esc_url($bannerArray[$index]['text']);
											$bannerURL = esc_url($bannerArray[$index]['url']);  
											$h5ab_session_settings = get_option(H5AB_Banner::_sessionSettings);
											$aboveAdvertText = esc_attr($h5ab_session_settings['h5ab_banner_text']);
										   
											//Construct the string to output
											?>
												<div id="h5ab-advert-cont" class="h5ab-banner-hide" style="display:none;">
												<div id="h5ab-advert-close"></div>
												<div id="h5ab-advert-cont-inner">
												<?php if (!empty($aboveAdvertText)) { echo '<div id="h5ab-advert-text">' . $aboveAdvertText . '</div>'; } ?>
												<!-- Attribute 'data-h5ab-key-index' has been added to keep track of banners shown in sequential mode -->
												<a href="<?php echo $linkURL; ?>" data-h5ab-banner-index="<?php echo $index; ?>" data-h5ab-key-index = "<?php echo $key_index; ?>" data-h5ab-banner-window="<?php if (! empty($h5ab_banner_settings)) { if ($h5ab_banner_settings['h5ab_banner_new_window'] == 'true') { echo 'true'; } } ?>" rel="nofollow"><img src="<?php echo $bannerURL; ?>" alt="advert" /></a>
												</div></div>
											<?php
							 }
							
								
						} else if (get_option(self::_advertType) == self::_custom) {

							$getAdvertArray =  get_option(self::_custom);
							
							if(!empty($getAdvertArray)) {

								$getAdvertArray = htmLawed(stripslashes($getAdvertArray), self::$htmlawedconfig);
										
								?>

								<div id="h5ab-advert-cont" class="h5ab-banner-hide">
								<div id="h5ab-advert-close"></div>
								<div id="h5ab-advert-cont-inner"><?php echo $getAdvertArray; ?></div>
								</div>
														
								<?php
							}
					   }
               }
			   
				/* Show the banners/adverts in Admin View **/
				public function construct_banner_html($html, $type) {
					$html = NULL;
					$content = get_option($type);
					
					if(!empty($content)) {	

						if($type == H5AB_Banner::_custom) {

								$content = htmLawed(stripslashes($content), self::$htmlawedconfig);
								
								$html = '<div id="h5ab-html-advert" style="display:inline-block;">' . $content . '</div>';
						}
						
						if($type == H5AB_Banner::_default) { 
							
							$banners= json_decode($content, true);
							$number = count($banners);
						
							if($number > 0){
								$html = '<a class="h5ab-remove-image h5ab-remove-all" href="#" data-h5ab-banner-index="-1">Click to remove all images</a>';
								
								foreach ($banners as $index=>$banner) {
								
									$imageIndex = esc_attr('h5ab-image_' . $index);
									$bannerURL = esc_url($banner['url']);
									$linkURL = esc_url($banner['text']);
									$views = esc_html($banner['views']);
									$clicks = esc_html($banner['clicks']);
									$clickthrough = esc_html($banner['clickthrough_rate']);
								
									$html .= '<div class="h5ab-advert-image" id="'. $imageIndex . '" >
													<a href="'. $linkURL . '"><img src="' . $bannerURL . '"></a><br/>
													<a class="h5ab-remove-image" href="#" data-h5ab-banner-index="' . $index .'">Click to remove</a>
													<span class="h5ab-display-views" >Views:&nbsp;' . $views . '</span>
													<span class="h5ab-display-clicks" >Clicks:&nbsp;' . $clicks . '</span>
													<span class="h5ab-display-clickthrough" >Clickthrough rate:&nbsp; ' . $clickthrough .'%</span>
												</div>';
								}	
							}
						}
					}
				
					return $html;
				}
				
				/*** Utility functions ***/
				
				public static function get_allowed_html() {
				    return self::$htmlawedconfig;
				}
				
				public static function update_advert_settings() {
					   $h5ab_banner_settings =  get_option(H5AB_Banner::_bannerSettings);
					   $h5ab_session_settings = get_option(H5AB_Banner::_sessionSettings);
					   $update = false;
					   $updated = false;
					 
				      if ($h5ab_session_settings == false){
					        add_option('h5ab_banner_session', array('h5ab_banner_session' => 'true', 'h5ab_banner_text' => ''));
							$updated = true;
					  }
					      
					 if ($h5ab_banner_settings == false) { 
							self::activate(); 
							$updated = true;
					 } else {
					 
					        //Check whether individual settings are in place
							if(!isset($h5ab_banner_settings['h5ab_randomize'])) { 
									$update = true;  
									$h5ab_banner_settings['h5ab_randomize'] = array('active' => 'true', 'index' => -1); 
							}
							
							if(!isset($h5ab_banner_settings['h5ab_advert_delay'])) { 
									$update = true;  
									$h5ab_banner_settings['h5ab_advert_delay'] = 0; 
							}
							
			                if($update) { 
							    update_option(H5AB_Banner::_bannerSettings, $h5ab_banner_settings); 
								$updated = true;
						   }
					  }
					  
					  return $updated;
				}
				
                public static function activate() {
	
					add_option(H5AB_Banner::_bannerSettings, array('h5ab_banner_new_window' => 'false', 
																												'h5ab_admin_preview' => 'false',
																												'h5ab_visitor_show' => 'true',
																												'h5ab_advert_delay' => 0,
																												'h5ab_randomize' => array('active' => 'true', 'index' => -1))
									);
									
	
                   add_option('h5ab_banner_session', array('h5ab_banner_session' => 'true', 'h5ab_banner_text' => ''));
     

																								  
				}

                public function h5ab_fbar_lang() {
                    load_plugin_textdomain( 'floating-ad-rotator-with-tracking', FALSE, basename( dirname( __FILE__ ) ) . '/lang/' );
                }
			
			}

	}

	if(class_exists('H5AB_Banner')) {

        register_activation_hook( __FILE__, array('H5AB_Banner' , 'activate'));
		$H5AB_Banner = new H5AB_Banner(); 
	
	}
	

?>
