(function($) {

$('head').append('<style>.h5ab_hide_field {display:none;}.h5ab_show_field {display:block;} /style>');

$('html').addClass('h5ab-banner-active');

//Don't display banner by default (display none set as inline style, but just to make sure)
 $('.h5ab-banner-hide').css('display', 'none');

var sessionOnce =  session_data.session_value,
       sessionAdmin =  session_data.admin_session,
	   showToVisitors = session_data.show_to_visitors,
	   delay =  session_data.delay;
	   if(delay > 0) delay = delay * 1000;
	   
function showSessionAdvert() {

    var inAdminView = $('body').hasClass('admin-bar'),
	        showAdvert = false;
	
	 //Determine whether to show the advert based on visitor/admin visibility settings
     if(inAdminView) {
	      if(sessionAdmin == 'true') showAdvert = true;
     }  else {
         if(showToVisitors == 'true') showAdvert = true;
     }
	
	 if(showAdvert) {
	
		$('.h5ab-banner-hide').css('display', 'block');

		var data = {
				'action': 'view_click',
				'index': $('#h5ab-advert-cont #h5ab-advert-cont-inner a').attr('data-h5ab-banner-index'),
				'key_index':  $('#h5ab-advert-cont #h5ab-advert-cont-inner a').attr('data-h5ab-key-index'),
				'type': 'h5ab_banner_views'
			}
			
			if(typeof data.index != 'undefined') {
				jQuery.post(ajax_object.ajax_url, data, function(response) {
					//console.log('View Registered');
				});
			
			}
			
			//Advert has been shown once
			sessionStorage.setItem('h5abBannerOnce', 'true');
	} 
		
	$('#h5ab-advert-cont #h5ab-advert-cont-inner a').on('click',function(){

    var linkURL = $(this).attr('href');

	var data = {
		'action': 'view_click',
        'index': $(this).attr('data-h5ab-banner-index'),
		'type': 'h5ab_banner_clicks'
	};

	jQuery.post(ajax_object.ajax_url, data, function(response) { 
		//console.log('Click Registered');
	});

	
    if ($('#h5ab-advert-cont #h5ab-advert-cont-inner a').attr('data-h5ab-banner-window') == 'true') {
        setTimeout(function(){
          window.open(linkURL);
        },750);
    } else {
        setTimeout(function(){
          window.location.replace(linkURL);
        },750);
    }


    return false;

});

$('#h5ab-advert-cont, #h5ab-advert-close').on('click',function(){

	$('#h5ab-advert-cont').fadeOut(500);
	
});
			
		
}

//Show advert or not based on value of 'Session Once'

//Don't show advert if: Only show advert once AND advert has already been shown
if (sessionOnce == 'true' && sessionStorage.getItem('h5abBannerOnce') == 'true') {

    $('.h5ab-banner-hide').css('display', 'none');

//Show advert if: Only show advert once AND session storage item not set
} else if (sessionOnce == 'true' && sessionStorage.getItem('h5abBannerOnce') == null || sessionOnce == 'true' && sessionStorage.getItem('h5abBannerOnce') == undefined) {

		setTimeout(function(){ showSessionAdvert(); }, delay);
		
//Show advert if: Setting to show advert repeatedly regardless of session storage item value
} else {

		setTimeout(function(){ showSessionAdvert(); }, delay);
		
}




}(jQuery));
