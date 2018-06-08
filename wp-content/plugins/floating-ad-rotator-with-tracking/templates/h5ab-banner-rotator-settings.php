<?php

	if ( ! defined( 'ABSPATH' ) ) exit;
	
	$reload= H5AB_Banner::update_advert_settings();
	
    if ($reload) {    

?>
        <script>location.reload();</script>
<?php

    }


    //Return h5ab banner settings
	$h5ab_banner_settings = get_option(H5AB_Banner::_bannerSettings);
    //Return h5ab session settings
	$h5ab_session_settings = get_option(H5AB_Banner::_sessionSettings);
 
	//HTML classes
	$show_field = "h5ab_show_field";
	$hide_field = "h5ab_hide_field";
	
	//If there is nothing in h5ab_banner_advert_type field, set it to the default value, otherwise use whatever is stored
    if(!get_option(H5AB_Banner::_advertType)) update_option(H5AB_Banner::_advertType, H5AB_Banner::_default);
	
	//Return h5ab_banner_advert_type
	$h5ab_banner_advert_type = get_option(H5AB_Banner::_advertType);
	
?>

<h1><?php _e( 'Ad Rotator Settings', 'floating-ad-rotator-with-tracking' ); ?></h1>

<p style="max-width: 500px;"><?php _e( 'Statistics don&#39;t count logged in admin views to improve accuracy.', 'floating-ad-rotator-with-tracking' ); ?></p>

    <p style="max-width: 500px;">
    <?php _e( 'Instructions for using this plugin can be found at:', 'floating-ad-rotator-with-tracking' ); ?>
     <a href="https://www.html5andbeyond.com/floating-banner-ad-rotator-tracking-wordpress-plugin/" target="_blank">HTML5andBeyond</a>
    </p>

<div class="h5ab-ad-container">

    <h2 style="font-weight: bold;">1. <?php _e( 'Choose Banner Type', 'floating-ad-rotator-with-tracking' ); ?></h2>

    <!--Select advert type -->
    <input type="radio" name="h5ab_banner_advert_type" class="h5ab-advert-type" id="h5ab-banner-array" style="display: none;" value="h5ab_banner_array" <?php if($h5ab_banner_advert_type == H5AB_Banner::_default) echo "checked"; ?> ><label for="h5ab-banner-array" ><?php _e( 'Banners', 'floating-ad-rotator-with-tracking' ); ?></label>
    <input type="radio" name="h5ab_banner_advert_type" class="h5ab-advert-type" id="h5ab-html-rotator" style="display: none;" value="h5ab_banner_customhtml" <?php if($h5ab_banner_advert_type == H5AB_Banner::_custom) echo "checked"; ?> ><label for="h5ab-html-rotator"><?php _e( 'Custom HTML', 'floating-ad-rotator-with-tracking' ); ?></label>

    <hr/>
	
	 <!-- On refresh, output only certain fields depending on the value of h5ab_banner_advert_type -->
	<form id="h5ab-featured-upload" method="post" enctype="multipart/form-data">

		<div id="table">
		
			<div id="h5ab_banner_customhtml" class="<?php $class = ($h5ab_banner_advert_type == H5AB_Banner::_custom)? $show_field : $hide_field;  echo esc_attr($class); ?>" >
                <h2 style="font-weight: bold;">2. <?php _e( 'Custom HTML', 'floating-ad-rotator-with-tracking' ); ?></h2>
				<?php $html = get_option("h5ab_banner_customhtml"); ?>
				<textarea type="text" name="h5ab_banner_customhtml" id="h5ab_banner_customhtml"><?php echo htmLawed(stripslashes($html), H5AB_Banner::get_allowed_html()); ?></textarea>
                <p><?php _e( 'JavaScript is disabled within custom HTML for security reasons', 'floating-ad-rotator-with-tracking' ); ?></p>
                <p><?php _e( 'Works Great with', 'floating-ad-rotator-with-tracking' ); ?> <a href="http://trck.me/?ref=mg147">Trck.me</a> - <?php _e( 'Affiliate Link', 'floating-ad-rotator-with-tracking' ); ?></p>
			</div>

		
			<div id="h5ab_banner_array" class="<?php $class = ($h5ab_banner_advert_type == H5AB_Banner::_default)? $show_field : $hide_field; echo esc_attr($class); ?>">
			<h2 style="font-weight: bold;">2. <?php _e( 'Upload Banners', 'floating-ad-rotator-with-tracking' ); ?></h2>
			<div id="h5ab-add-image" style="cursor: pointer; text-decoration: underline;"><?php _e( 'Add New', 'floating-ad-rotator-with-tracking' ); ?></div>
				<div class="row">
				    <label><?php _e( 'Upload Banner:', 'floating-ad-rotator-with-tracking' ); ?> </label>
                    <input type="text" name="h5ab_banner_upload_file[]" class="h5ab-upload-banner-input" id="image_1" placeholder="Upload / Select Banner Image" /><a href="#" class="h5ab-upload-banner-input-btn" id="h5ab_upload_image_1"><?php _e( 'Upload Banner', 'floating-ad-rotator-with-tracking' ); ?></a>
                    <label><?php _e( 'Link URL:', 'floating-ad-rotator-with-tracking' ); ?> </label>
					<input type="text" name="h5ab_banner_upload_url[]" placeholder="Link URL" maxlength="350" />
				</div>
			</div>
			
			<hr/>

            <h2 style="font-weight: bold;">3. <?php _e( 'Modify the Settings', 'floating-ad-rotator-with-tracking' ); ?></h2>
			
            <div class="row h5ab-session-settings ">
                <label><?php _e( 'Above Advertisement Text (Banners Mode Only)', 'floating-ad-rotator-with-tracking' ); ?></label><br/>
				<input type="text" name="h5ab_banner_text" id="h5ab-banner-text" value="<?php if (! empty($h5ab_session_settings)) { echo esc_attr($h5ab_session_settings['h5ab_banner_text']); } ?>" placeholder="Above Advert Text"  />
            </div>

            <div class="row h5ab-randomize-setting ">
				<input type="checkbox" name="h5ab_randomize" id="h5ab-banner-randomize" value="true" <?php if (! empty($h5ab_banner_settings)) { if ($h5ab_banner_settings['h5ab_randomize']['active'] == 'true') { echo 'checked';} } ?> />
                <label for="h5ab-banner-randomize"><?php _e( 'Randomize (Banners Only)', 'floating-ad-rotator-with-tracking' ); ?></label>
            </div>

			<div class="row h5ab-new-window-setting" >
				<input type="checkbox" name="h5ab_banner_new_window" id="h5ab-banner-link-new" value="true" <?php if (! empty($h5ab_banner_settings)) { if ($h5ab_banner_settings['h5ab_banner_new_window'] == 'true') {echo 'checked';} } ?> />
                <label for="h5ab-banner-link-new"><?php _e( 'Open Links in a New Window (Banners Only)', 'floating-ad-rotator-with-tracking' ); ?></label>
            </div>
			<br/>
		   <div class="row h5ab-session-settings ">
                <label><?php _e( 'Advertisement Delay (in seconds)', 'floating-ad-rotator-with-tracking' ); ?> </label><br/>
				<input type="number" name="h5ab_advert_delay" id="h5ab-advert-delay" value="<?php if (! empty($h5ab_session_settings)) { echo esc_attr($h5ab_banner_settings['h5ab_advert_delay']); } ?>" placeholder="0"  />
            </div>
			
			<div class="row h5ab-admin-show-setting">
				<input type="checkbox" name="h5ab_admin_preview" id="h5ab-banner-admin-show" value="true" <?php if (! empty($h5ab_banner_settings)) { if ($h5ab_banner_settings['h5ab_admin_preview'] == 'true') {echo 'checked';} } ?> />
                <label for="h5ab-banner-admin-show"><?php _e( 'Show Adverts in Admin View', 'floating-ad-rotator-with-tracking' ); ?></label>
            </div>
			
			<div class="row h5ab-visitor-show-setting ">
				<input type="checkbox" name="h5ab_visitor_show" id="h5ab-banner-visitor-show" value="true" <?php if (! empty($h5ab_banner_settings)) { if ($h5ab_banner_settings['h5ab_visitor_show'] == 'true') {echo 'checked';} } ?> />
                <label for="h5ab-banner-visitor-show"><?php _e( 'Show Adverts to Site Visitors', 'floating-ad-rotator-with-tracking' ); ?></label>
            </div>

            <div class="row h5ab-session-settings ">
				<input type="checkbox" name="h5ab_ad_session" id="h5ab-banner-session" value="true" <?php if (! empty($h5ab_session_settings)) { if ($h5ab_session_settings['h5ab_banner_session'] == 'true') {echo 'checked';} } ?> />
                <label for="h5ab-banner-session"><?php _e( 'Show Advert Only Once Per Session', 'floating-ad-rotator-with-tracking' ); ?></label>
            </div>
			
		</div>


		<?php
			wp_nonce_field( 'h5ab_banner_upload', 'h5ab_banner_upload_nonce' ); 
			if ( ! is_admin() ) {
			echo 'Only Admin Users Can Update These Options';
			} else { ?>
			
			<input type="submit"  class="button button-primary show_field" id="h5ab_upload_banner_submit" name="h5ab_upload_banner_submit" value="<?php _e( 'Upload Banners/Save HTML', 'floating-ad-rotator-with-tracking' );?>" />
			
		<?php
		
			}
        
		?>

	</form>
	

    </div>

    <div class="h5ab-ad-container">

    <h2 style="font-weight: bold;"><?php _e( 'Current Banners and Advertising', 'floating-ad-rotator-with-tracking' ); ?></h2>

	
	<div class='h5ab-image-cont'>
		
	<?php
		
		//Output default or custom content
		$h5ab_banner_advert_type = get_option(H5AB_Banner::_advertType); //Get option again
		$html = apply_filters('h5ab_output_banner_html', '', $h5ab_banner_advert_type);
		if(!is_null($html)) echo $html;
		
	?>
		
	</div>

	<div id="h5ab-affiliate-advert" style="width: 170px; top: 5px; right: 5px; background-color: #fff; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; padding: 5px;">
                    <p style="margin: 0;"><?php _e( 'Advertisements', 'floating-ad-rotator-with-tracking' ); ?>:</p>
                    <a href="http://12b3cmwsdxbl5kcafucdflwka3.hop.clickbank.net/" target="_blank"><img src="<?php echo esc_url(plugins_url( '../images/lts.png', __FILE__ )) ?>" border="0" style="max-width: 100%; height: auto;" /></a><br>
                    <a href="http://si123.seopressor.hop.clickbank.net" target="_blank"><img src="<?php echo esc_url(plugins_url( '../images/seop.jpg', __FILE__ )) ?>" border="0" style="max-width: 100%; height: auto;" /></a>
                    <p style="margin: 0;"><?php _e( '*Affiliate Links', 'floating-ad-rotator-with-tracking' ); ?></p>

    </div>

</div>

<hr/>

<p style="max-width: 500px;">
Do you like this plugin? Help us promote it:
    <a href="https://twitter.com/share?url=https://wordpress.org/plugins/floating-ad-rotator-with-tracking&text=Floating Banner Ad Rotator with Tracking WordPress Plugin - &via=html5andbeyond&hashtags=internetmarkeing" target="_blank">Share on Twitter</a> or <a href="http://www.facebook.com/sharer.php?u=https://wordpress.org/plugins/floating-ad-rotator-with-tracking/" target="_blank">Share on Facebook</a>
</p>

<div style="background-color: #ddd; width: 98%; padding: 0 5px;">

<p><?php _e( 'Session Storage is used to display advert only once per session - this session storage doesn&#39;t include any personal information it simple stores a value of &#39;true&#39;.', 'floating-ad-rotator-with-tracking' ); ?></p>

<p><?php _e( 'Statistics are disabled on custom HTML and won&#39;t update if visitors browser has JavaScript disabled.', 'floating-ad-rotator-with-tracking' ); ?></p>

<p><?php _e( '*Affiliate Link - We (Plugin Authors) earn commission on sales generated through this link.', 'floating-ad-rotator-with-tracking' ); ?></p>
</div>
