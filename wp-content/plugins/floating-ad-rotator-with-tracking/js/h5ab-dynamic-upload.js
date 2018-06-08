(function($) {
    
	$(document).ready(function() {
	
		//Globals
		var imageContainer = $('.h5ab-image-cont'),
			  form = $('#h5ab-featured-upload'),
			  imageUploadContainer = $('#h5ab-featured-upload #h5ab_banner_array'),
			  updatedAdminNotice = $('.updated'),
			  errorAdminNotice = $('.error'),
			  i = 1;

            $('body').on('click', '#h5ab-add-image',  function(e) {
				e.preventDefault();
				i++;
				imageUploadContainer.append('<div class="row">' +
																		   '<label>Upload Banner: </label><input type="text" name="h5ab_banner_upload_file[]" id="image_' +
																			i + '" placeholder="Upload / Select Banner Image" /><a href="#" class="h5ab-upload-banner-input-btn" id="h5ab_upload_image_' + i + '">Upload Banner</a>' +
																			'<label style="padding-left: 3px;">Link URL: </label><input type="text" name="h5ab_banner_upload_url[]" placeholder="Link URL" maxlength="350" />' +
																			'<div>');
				 //console.log(i);
				 
			});

            $('body').on('click', '.h5ab-upload-banner-input-btn',  function(){

                var file_frame,
                    attachmentImg;

                    $(this).each(function(){

                    var getElem = $(this).attr('id');

                    //console.log(getElem)

                    if ( file_frame ) {
                        file_frame.open();
                        return;
                    }

                        file_frame = wp.media.frames.file_frame = wp.media({
                            title: jQuery( this ).data( 'Login_Styler_Logo' ),
                            id: 'h5ab-login-styler-upload',
                            button: {
                                text: jQuery( this ).data( 'uploader_button_text' ),
                            },
                            multiple: false,
                            editing: false,
                            type: 'image'
                        });

                        file_frame.on( 'select', function() {

                            var selection = file_frame.state().get('selection');
                                selection.map( function( attachment ) {
                                    attachmentImg = attachment.toJSON();
                                });

                            window.parent.focus();
                            $('#' + getElem, window.parent.document).prev('input').val(attachmentImg['url'])

                        });

                    file_frame.open();

                    }); //each function

        });

		
	   //Determines form upload type and content output
		$('body').on('click', '.h5ab-advert-type', function() {

				var elem = this,
				    h5ab_banner_advert_type = $(elem).val();
			
				var data = {
					'action': 'set_type',  //Action must have same value as name of php handler function
					'type': h5ab_banner_advert_type
				};
				
			$.post(ajax_object.ajax_url, data, function(response) {
				
					var response = $.parseJSON(response),
							content;
							
					 //Remove '.show field' from all form items
					 form.find(".h5ab_show_field").removeClass("h5ab_show_field").addClass("h5ab_hide_field");
					 
					 //Re-attach '.show field' to specific items
					$.each([$("#" + h5ab_banner_advert_type ), $(":submit")] , function(i, item){
								$(item).addClass("h5ab_show_field");
					});
					 
					 //Clear the image container and admin notices
					 imageContainer.empty();
					 updatedAdminNotice.hide();
					 errorAdminNotice.hide();
					 
					if(!$.isEmptyObject(response)) {
							
						content = response.content;	
						//console.log(content);
						
						//Append the html to the image container div
						if(content) { imageContainer.append(content); }
					}
			});
		
		});
		
		
		$('body').on('click', '.h5ab-remove-image', function() {
				var elem = this,
						imgID = $(elem).attr('data-h5ab-banner-index');
		
				var data = {
					'action': 'remove_image', 
					'index': imgID
				};
				
				$.post(ajax_object.ajax_url, data, function(response) {
					var response = $.parseJSON(response);
					//console.log(response);
					if(!$.isEmptyObject(response)) {
						 if(response.remove_all) {
								imageContainer.empty(); //Remove all images
						 } else {
							 $("#h5ab-image_" + response.index).remove(); 
						 }
					}
					
				});
		});

	}); //Doc ready end
	
})(jQuery);

