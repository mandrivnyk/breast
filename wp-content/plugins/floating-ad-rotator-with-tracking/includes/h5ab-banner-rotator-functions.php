<?php

if ( ! defined( 'ABSPATH' ) ) exit;

//Remove banner details from the db field and tell javascript which banner to remove from the DOM
function h5ab_banner_remove_image_callback() {
				
			if(isset($_POST['index'])) {
			
					$index = intval($_POST['index']);
					$ret = new stdclass();
					$ret->index = $index;
					$ret->remove_all = false;
	
					$currentData = json_decode(get_option(H5AB_Banner::_default), true);
					
					if(is_array($currentData)) {

						if($index == -1) { 
								$ret->remove_all = true;
						} else {
						
								unset($currentData[$index]);
							   
								$escaped_data = json_encode(esc_sql($currentData));
								$ret->success = update_option(H5AB_Banner::_default, $escaped_data);   

								$updated_data = json_decode(get_option(H5AB_Banner::_default), true);
								
								$banners_remaining = (empty($updated_data))? false : true;
								if ($banners_remaining == false) $ret->remove_all = true;
								
						}
						
						 if($ret->remove_all == true) {
								$ret->success = delete_option(H5AB_Banner::_default); 
								//If the 'randomize' setting is not enabled, set the rotation index to -1 
								$settings = get_option(H5AB_Banner::_bannerSettings);
								if($settings['h5ab_randomize']['active'] == 'false') { 
										$settings['h5ab_randomize']['index'] = -1;
										update_option(H5AB_Banner::_bannerSettings, $settings);
								}	
						 }
							
						echo(json_encode($ret));
					}
			}
					
			die();
}


//Set advert type and send existing content to JS to be outputted
function h5ab_banner_set_type_callback() {
		
		$type = (isset($_POST['type'])) ? $_POST['type'] : false;
		
		if(!empty($type)) {

			$ret = new stdclass();
			//Update radio button state in database
			update_option(H5AB_Banner::_advertType, $type); 
			$ret->h5ab_banner_advert_type = get_option(H5AB_Banner::_advertType); 
			$ret->content = apply_filters('h5ab_output_banner_html', '', $type);
			
			//Send HTML to JS
			echo (json_encode($ret));
		}	
		
		die();
}

function h5ab_banner_view_click_callback() {

	if (get_option(H5AB_Banner::_advertType) == H5AB_Banner::_default) {
			
		if(isset($_POST['type'])) {
		
			$type = $_POST['type'];
			
			if(isset($_POST['index'])) {
			
			    $index = $_POST['index'];
				$getAdvertArray =  json_decode(get_option(H5AB_Banner::_default), TRUE);
				$current_views =	 $getAdvertArray[$index]['views'];
				$current_clicks =  $getAdvertArray[$index]['clicks'];
				
				switch($type) {
				case 'h5ab_banner_views':
						$getAdvertArray[$index]['views'] = $current_views + 1;
						$views = $getAdvertArray[$index]['views'];
						$getAdvertArray[$index]['clickthrough_rate'] = round($current_clicks / $views * 100, 2);
						
						$settings = get_option(H5AB_Banner::_bannerSettings);
						if($settings['h5ab_randomize']['active'] == 'false') { 
							/*
							*The index and key index is sent here via Ajax when the banner is viewed
							*The key_index is saved to keep track of which banner was last shown in sequential mode
							*/
							if(isset($_POST['key_index'])) $key_index = $_POST['key_index'];
							$settings['h5ab_randomize']['index'] = $key_index;
						    update_option(H5AB_Banner::_bannerSettings, $settings);
					    }
					
				break;
				case 'h5ab_banner_clicks':
						$getAdvertArray[$index]['clicks'] = $current_clicks + 1;
						$clicks = $getAdvertArray[$index]['clicks'];
						$getAdvertArray[$index]['clickthrough_rate'] = round($clicks / $current_views * 100, 2);
				break;
				}				 
				update_option(H5AB_Banner::_default, json_encode(esc_sql($getAdvertArray)));
			}
		}
	}
	
	die();

}

//Return an array of variables from posted data
function getPostData($postData) {
	 $data = array();
	 
	 $data['h5ab_banner_customhtml'] = ( isset ( $_POST['h5ab_banner_customhtml'] ) ) ? $postData['h5ab_banner_customhtml'] :  false;
	 
	 $data['urls']  =  ( isset ( $postData['h5ab_banner_upload_url'] ) )  ? $postData['h5ab_banner_upload_url'] : false;
     $data['files'] =  ( isset($postData['h5ab_banner_upload_file'] ) )   ?  $postData['h5ab_banner_upload_file']: false;
	 
	 //Settings
     $data['newwindow']  =  (isset ( $postData['h5ab_banner_new_window'] ) ) ? $postData['h5ab_banner_new_window']: 'false';
	 $data['showBannerToAdmin'] = ( isset ( $postData['h5ab_admin_preview'] ) ) ? $postData['h5ab_admin_preview'] : 'false';
	 $data['showBannerToVisitors'] = ( isset ( $postData['h5ab_visitor_show'] ) ) ? $postData['h5ab_visitor_show'] : 'false';
	 $data['bannerSession'] = ( isset ( $postData['h5ab_ad_session'] ) ) ? $postData['h5ab_ad_session'] : 'false';
     $data['bannerText'] = ( isset ( $postData['h5ab_banner_text'] ) ) ? $postData['h5ab_banner_text'] : 'false';
	 $data['randomize'] = ( isset ( $postData['h5ab_randomize'] ) ) ? $postData['h5ab_randomize'] : 'false';
	 $data['delay'] = ( isset ( $postData['h5ab_advert_delay'] ) ) ? $postData['h5ab_advert_delay'] : 'false';
	
	 return $data; 
}

//Update form settings and return settings and session feedback
function updateSettings($data, $currentData) {

	 $advertArrayKeyIndex = $currentData['h5ab_randomize']['index'];
	 
	 $settingsUpdated = update_option(H5AB_Banner::_bannerSettings, array('h5ab_banner_new_window' => esc_attr($data['newwindow']), 
																																		  'h5ab_admin_preview' => esc_attr($data['showBannerToAdmin']), 
																																		  'h5ab_visitor_show' => esc_attr($data['showBannerToVisitors']),
																																		  'h5ab_advert_delay' => esc_attr($data['delay']),
																																		  'h5ab_randomize' => array('active' => esc_attr($data['randomize']), 'index' => esc_attr($advertArrayKeyIndex))
																																		));
																																		
    $sessionUpdated = update_option(H5AB_Banner::_sessionSettings, array('h5ab_banner_session' => esc_attr($data['bannerSession']), 
																																		   'h5ab_banner_text' => esc_attr($data['bannerText'])));
																																		   
   return array('settingsUpdated' => $settingsUpdated, 'sessionUpdated' => $sessionUpdated);
	
}

function upload_h5ab_banner_files() {

    $response = new stdClass();
	$response->success = false;
	$response->message =  __( 'No files submitted for upload', 'floating-ad-rotator-with-tracking');
	
	$current_settings = get_option(H5AB_Banner::_bannerSettings);
	$data = getPostData($_POST); 
	
     // Remove Extra Whitespace
     $urls = str_replace(' ', '', $data['urls']);
     $files = str_replace(' ', '', $data['files']);
	
	 $files = array_filter($files); 
	 	 
	 $feedback = updateSettings($data, $current_settings);
	 $settingsUpdated = $feedback['settingsUpdated'];
	 $sessionUpdated = $feedback['sessionUpdated'];
	 
	
	if(!empty($files) && !empty($urls)) {
		   
			if(count($files) > 0) {
				//Access already present advert images
				$currentData = json_decode(get_option(H5AB_Banner::_default), TRUE);
				if(!isset($currentData)) $currentData = array();

					 foreach($files as $key => $value) {
					
									array_push($currentData, array('url' => esc_url($files[$key]), 'text' => esc_url($urls[$key]),
									'views' => 0, 'clicks' => 0,  'clickthrough_rate' => 0));
					}
					

				$encodedArr = json_encode(esc_sql($currentData));

				if($encodedArr) $success = update_option(H5AB_Banner::_default, $encodedArr);

				if($success) {
					$response->success = true;
					$response->message = __( 'Images were uploaded successfully', 'floating-ad-rotator-with-tracking');
					return $response;
				} else {
					//Requires language support
					$response->message = "File upload could not be completed";
					return $response;
				}
				
			} 

	 }
	 
	 if($settingsUpdated || $sessionUpdated) {
				$response->success = true;
				$response->message = __( 'Banner Settings Updated', 'floating-ad-rotator-with-tracking');	
	 } else {
				 $response->message =  __( 'No files submitted for upload', 'floating-ad-rotator-with-tracking');	
			  }
	 
	 return $response;
	  
}


function upload_h5ab_banner_customhtml() {

			$response = new stdClass();	
			$response->success = false;
			$response->message = __( 'Custom HTML could not be saved', 'floating-ad-rotator-with-tracking');
			
			$current_settings = get_option(H5AB_Banner::_bannerSettings);
			$data = getPostData($_POST);
		
			
			$feedback = updateSettings($data, $current_settings);
			$settingsUpdated = $feedback['settingsUpdated'];
			$sessionUpdated = $feedback['sessionUpdated'];
			
			if(!empty($data['h5ab_banner_customhtml'])) {
			
					$h5ab_banner_customhtml = $data['h5ab_banner_customhtml'];
			  
                    $h5ab_banner_customhtml = str_replace("'",'"', $h5ab_banner_customhtml);
					$html_saved = update_option(H5AB_Banner::_custom, htmLawed(stripslashes($h5ab_banner_customhtml), H5AB_Banner::get_allowed_html()));

					if($html_saved) {
						$response->success = true;
						$response->message = __( 'Custom HTML successfully saved', 'floating-ad-rotator-with-tracking');
					} else {
							 if($settingsUpdated || $sessionUpdated) {
								$response->success = true;
								$response->message = __( 'Banner Settings Updated', 'floating-ad-rotator-with-tracking');			
							}
					}
			}
			
			return $response;
}



function h5ab_banner_upload() {
		
		$h5ab_banner_advert_type = get_option('h5ab_banner_advert_type');
		  
		//Determine which data type to save
		if(!empty($h5ab_banner_advert_type)) {
				switch($h5ab_banner_advert_type) {
				
				 case H5AB_Banner::_default:
					return upload_h5ab_banner_files();
				 break;
				  
				 case H5AB_Banner::_custom:
					return upload_h5ab_banner_customhtml();
				 break;
			
				}
		} 			 
}
			
			
?>
