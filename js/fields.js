jQuery(document).ready(function($){
    
    /*** Date Field ***/
	(function() {
		jQuery('.mtbxr-date-hidden').each(function(){
			jQuery(this).datepicker({
				showOtherMonths: true,
				selectOtherMonths: true,
				changeYear: true,
				dateFormat: '@',
				showOn: "button",
				onSelect: function(dateText, inst) {
					var date = new Date(parseInt(dateText));
					jQuery(this).prev().prev().prev().val(date.getMonth()+1);
					jQuery(this).prev().prev().val(date.getDate());
					jQuery(this).prev().val(date.getFullYear());
				}
			});
			var date = parseInt(jQuery(this).val());
			if(date!=null){
				jQuery(this).datepicker( "setDate", new Date( date*1000 ));/*** Assign to calendar. Remember js timestamp is in milliseconds while php is in seconds ***/
			}
		});
		
	})();
    
    /*** Sortable Images ***/
    (function() {
        jQuery('.mtbxr-sortable-images').sortable({
			placeholder: "mtbxr-placeholder-image",
			forcePlaceholderSize:true
		});
		
		jQuery('.mtbxr-sortable-images').on('click', '.mtbxr-image-delete', function(e) {
			e.preventDefault();
			
			var box = jQuery(this).parents('.mtbxr-sortable-image');
			box.fadeOut('slow', function(){
				box.remove();
			});
		});
        
    })();
    
    /*** Sortable Images - Gallery Code - WP 3.5+ ***/
    (function() {
        if(!mtbxr.new_media_gallery){
            return; // Exit. Use old gallery code for older wp version
        }
        
        /*** ADD IMAGE ***/
		jQuery('.mtbxr-field').on('wpinsertmedia', '.mtbxr-add-image', function(e, image_url, attachment_id) {
			var field, sortable, new_box_html;
            field = jQuery(this).parents('.mtbxr-field');
			sortable = field.find('.mtbxr-sortable-images');
			new_box_html = field.find('.mtbxr-sortable-image-skeleton').html().replace(/_dummy/g,'');/*** Remove _dummy from name for field to be included in the save ***/
			jQuery(new_box_html).appendTo(sortable).find('img').attr('src', image_url).next().val(attachment_id);
			
		});
        
        /*** EDIT IMAGE ***/
        jQuery('.mtbxr-field').on('wpinsertmedia', '.mtbxr-image-edit', function(e, image_url, attachment_id) {
			jQuery(this).parents('.mtbxr-sortable-image').find('img').attr('src', image_url).next().val(attachment_id);
		});
        
        // Prepare the variable that holds our custom media manager.
        var mtbxr_media_frame;
        var triggering_element = null;
        
        // Bind to our click event in order to open up the new media experience.
        jQuery(document.body).on('click', '.mtbxr-add-image, .mtbxr-image-edit', function(e){
            // Prevent the default action from occuring.
            e.preventDefault();
            
            triggering_element = jQuery(this);/*** get current clicked element ***/
            
            
            // If the frame already exists, re-open it.
            if ( mtbxr_media_frame ) {
                mtbxr_media_frame.open();
                return;
            }
    

            mtbxr_media_frame = wp.media.frames.mtbxr_media_frame = wp.media({
                className: 'media-frame mtbxr-frame',
                frame: 'select',
                multiple: false,
                title: mtbxr.media_window_title,
                library: {
                    type: 'image'
                },
                button: {
                    text:  mtbxr.media_button_caption
                }
            });
    
            mtbxr_media_frame.on('select', function(){
                var media_attachment, img_url;
                
                // Grab our attachment selection and construct a JSON representation of the model.
                media_attachment = mtbxr_media_frame.state().get('selection').first().toJSON();
                
                if(undefined==media_attachment.sizes.thumbnail){ /*** Account for smaller images where thumbnail does not exist ***/
                    img_url = media_attachment.url;
                } else {
                    img_url = media_attachment.sizes.thumbnail.url;
                }
                
                triggering_element.trigger('wpinsertmedia', [img_url, media_attachment.id]);
            });
    
            // Now that everything has been set, let's open up the frame.
            mtbxr_media_frame.open();
        });
    })();
    
    /*** Sortable Images - Gallery Code - Pre 3.5 ***/
    (function() {
		if(mtbxr.new_media_gallery){
            return; // Exit. Use new gallery code
        }
		
		/*** We use this vars to determine if thickbox is being used in here. Also saves the field that was clicked. ***/
		window.mtbxr_insert_image = window.mtbxr_insert_file = false;
		
		/*** ADD IMAGE ***/
		jQuery('.mtbxr-field').on('click', '.mtbxr-add-image', function(e) {
	
			e.preventDefault();
			var post_id = jQuery(this).attr('data-post-id');
			reset_pointers(); 
			
			window.mtbxr_insert_image = jQuery(this);
			tb_show('', 'media-upload.php?referer=mtbxr&amp;post_id='+post_id+'&amp;type=image&amp;TB_iframe=true');/*** referer param needed to change button text ***/
		
		}).on('wpinsertmedia', '.mtbxr-add-image', function(e, image_url, attachment_id) {
			var field = jQuery(this).parents('.mtbxr-field');
			var sortable = field.find('.mtbxr-sortable-images');
			var new_box_html = field.find('.mtbxr-sortable-image-skeleton').html().replace(/_dummy/g,'');/*** Remove _dummy from name for field to be included in the save ***/
			jQuery(new_box_html).appendTo(sortable).find('img').attr('src', image_url).next().val(attachment_id);
			
		});
		
		/*** EDIT IMAGE ***/
		jQuery('.mtbxr-sortable-images').on('click', '.mtbxr-image-edit', function(e) {
			
			e.preventDefault();
			reset_pointers();
			var post_id = jQuery(this).attr('data-post-id');
			
			window.mtbxr_insert_image = jQuery(this);
			tb_show('', 'media-upload.php?referer=mtbxr&amp;post_id='+post_id+'&amp;type=image&amp;TB_iframe=true');/*** referer param needed to change button text ***/
		
		}).on('wpinsertmedia', '.mtbxr-image-edit', function(e, image_url, attachment_id) {

			jQuery(this).parents('.mtbxr-sortable-image').find('img').attr('src', image_url).next().val(attachment_id);

		});
		
		window.original_send_to_editor = window.send_to_editor;/*** backup original for other parts of admin that uses thickbox to work ***/
		window.send_to_editor = function(html) {
			
			if (window.mtbxr_insert_image) { /*** ADD/EDIT IMAGE ***/
				
				var image = get_image_from_html(html); /*** Get image object from html. false on fail. ***/

				if(image){
					var url = image.attr('src');
					var attachment_id = image.attr('data-id');
					window.mtbxr_insert_image.trigger('wpinsertmedia', [url, attachment_id]);
				} else {
					alert('Could not insert image.');
				}

				tb_remove();
				window.mtbxr_insert_image = false;

			} else {
				window.original_send_to_editor(html);
			}
		};
		
		/*** Reset this on every click to prevent bug with canceled thickbox  ***/
		function reset_pointers(){
			window.mtbxr_insert_image = window.mtbxr_insert_file = false; /*** Reset them ***/
		}
		
		function get_image_from_html(html){
			var image = false;
			if(jQuery(html).get(0) != undefined){ /*** Check if its a valid html tag ***/
				if(jQuery(html).get(0).nodeName.toLowerCase()=='img'){/*** Check if html is an img tag ***/
					image = jQuery(html);
				} else { /*** If not may be it contains the img tag ***/
					if(jQuery(html).find('img').length > 0){
						image = jQuery(html).find('img');
					}
				}
			}
			return image;
		}
		
	})();
    
    /*** Sortable Files ***/
    (function() {
		
		jQuery('.mtbxr-sortable-files').sortable({
			handle:'.mtbxr-file-drag',
			placeholder: "mtbxr-placeholder-file",
			forcePlaceholderSize:true
		});
		
		jQuery('.mtbxr-sortable-files').on('click', '.mtbxr-file-delete', function(e) {
			e.preventDefault();
			
			var box = jQuery(this).parents('.mtbxr-sortable-file');
			box.fadeOut('slow', function(){
				box.remove();
			});
		});
    })();
    
    /*** Sortable Files - Gallery Code - WP 3.5+ ***/
    (function() {
        if(!mtbxr.new_media_gallery){
            return; // Exit. Use old gallery code for older wp version
        }
        
        /*** ADD FILE ***/
		jQuery('.mtbxr-field').on('wpinsertmedia', '.mtbxr-add-file', function(e, file_url, attachment_id) {
			var field = jQuery(this).parents('.mtbxr-field');
			var sortable = field.find('.mtbxr-sortable-files');
			var new_box_html = field.find('.mtbxr-sortable-file-skeleton').html().replace(/_dummy/g,'');/*** Remove _dummy from name for field to be included in the save ***/
			jQuery(new_box_html).appendTo(sortable).find('p').html(file_url).next().val(attachment_id);
		});
		
		/*** EDIT FILE ***/
		jQuery('.mtbxr-sortable-files').on('wpinsertmedia', '.mtbxr-file-edit', function(e, file_url, attachment_id) {
			jQuery(this).parents('.mtbxr-sortable-file').find('p').html(file_url).next().val(attachment_id);
		});
        
        // Prepare the variable that holds our custom media manager.
        var mtbxr_media_frame;
        var triggering_element = null;
        
        // Bind to our click event in order to open up the new media experience.
        jQuery(document.body).on('click', '.mtbxr-add-file, .mtbxr-file-edit', function(e){
            // Prevent the default action from occuring.
            e.preventDefault();
            
            triggering_element = jQuery(this);/*** get current clicked element ***/
            
            
            // If the frame already exists, re-open it.
            if ( mtbxr_media_frame ) {
                mtbxr_media_frame.open();
                return;
            }
    

            mtbxr_media_frame = wp.media.frames.mtbxr_media_frame = wp.media({
                className: 'media-frame mtbxr-frame',
                frame: 'select',
                multiple: false,
                title: mtbxr.media_window_title,
               
                button: {
                    text:  mtbxr.media_button_caption
                }
            });
    
            mtbxr_media_frame.on('select', function(){
                var media_attachment, img_url;
                
                // Grab our attachment selection and construct a JSON representation of the model.
                media_attachment = mtbxr_media_frame.state().get('selection').first().toJSON();
                
                img_url = media_attachment.url;
                
                triggering_element.trigger('wpinsertmedia', [img_url, media_attachment.id]);
            });
    
            // Now that everything has been set, let's open up the frame.
            mtbxr_media_frame.open();
        });
    })();
    
    /*** Sortable Files - Gallery Code - Pre WP 3.5 ***/
    (function() {
        if(mtbxr.new_media_gallery){
            return; // Exit. Use new gallery code
        }
        
		/*** We use this vars to determine if thickbox is being used in here. Also saves the field that was clicked. ***/
		window.mtbxr_insert_image = window.mtbxr_insert_file = false;

		/*** ADD FILE ***/
		jQuery('.mtbxr-field').on('click', '.mtbxr-add-file', function(e) {
			
			e.preventDefault();
			reset_pointers();
			var post_id = jQuery(this).attr('data-post-id');
			
			window.mtbxr_insert_file = jQuery(this);
			tb_show('', 'media-upload.php?referer=mtbxr&amp;post_id='+post_id+'&amp;type=file&amp;TB_iframe=true');/*** referer param needed to change button text ***/
		
		}).on('wpinsertmedia', '.mtbxr-add-file', function(e, file_url, attachment_id) {
			
			var field = jQuery(this).parents('.mtbxr-field');
			var sortable = field.find('.mtbxr-sortable-files');
			var new_box_html = field.find('.mtbxr-sortable-file-skeleton').html().replace(/_dummy/g,'');/*** Remove _dummy from name for field to be included in the save ***/
			jQuery(new_box_html).appendTo(sortable).find('p').html(file_url).next().val(attachment_id);
			
		});
		
		/*** EDIT FILE ***/
		jQuery('.mtbxr-sortable-files').on('click', '.mtbxr-file-edit', function(e) {
			e.preventDefault();
			reset_pointers();
			var post_id = jQuery('.mtbxr-add-file').attr('data-post-id');
			
			window.mtbxr_insert_file = jQuery(this);
			tb_show('', 'media-upload.php?referer=mtbxr&amp;post_id='+post_id+'&amp;type=file&amp;TB_iframe=true');/*** referer param needed to change button text ***/
		
		}).on('wpinsertmedia', '.mtbxr-file-edit', function(e, file_url, attachment_id) {
			
			jQuery(this).parents('.mtbxr-sortable-file').find('p').html(file_url).next().val(attachment_id);
			
		});
		
		window.original_send_to_editor = window.send_to_editor;/*** backup original for other parts of admin that uses thickbox to work ***/
		window.send_to_editor = function(html) {
			
			if (window.mtbxr_insert_file) { /*** ADD/EDIT FILE ***/
				
				var link = get_link_from_html(html); /*** Get <a> object from html. false on fail. ***/
				
				if(link){
					var url = link.attr('href');
					var attachment_id = link.attr('data-id');
					window.mtbxr_insert_file.trigger('wpinsertmedia', [url, attachment_id]);
				} else {
					alert('Could not insert file.');
				}

				tb_remove();
				window.mtbxr_insert_file = false;
				
			} else {
				window.original_send_to_editor(html);
			}
		};
		
		/*** Reset this on every click to prevent bug with canceled thickbox  ***/
		function reset_pointers(){
			window.mtbxr_insert_image = window.mtbxr_insert_file = false; /*** Reset them ***/
		}
		
		function get_link_from_html(html){
			var link = false;
			if(jQuery(html).get(0) != undefined){ /*** Check if its a valid html tag ***/
				if(jQuery(html).get(0).nodeName.toLowerCase()=='a'){/*** Check if html is an anchor tag ***/
					link = jQuery(html);
				} else { /*** If not may be it contains the anchor tag ***/
					if(jQuery(html).find('a').length > 0){
						link = jQuery(html).find('a');
					}
				}
			}
			return link;
		}
	})();
    
    /*** Sortable Textareas ***/
    (function() {
		jQuery('.mtbxr-sortable-textareas').sortable({
			handle:'.mtbxr-textarea-drag',
			placeholder: "mtbxr-textarea-placeholder",
			forcePlaceholderSize:true
		});
		
		/*** Add ***/
		jQuery('.mtbxr-field').on('click', '.mtbxr-textarea-add', function(e) {
			e.preventDefault();
			var field = jQuery(this).parents('.mtbxr-field');
			var sortable = field.find('.mtbxr-sortable-textareas');
			var new_box_html = field.find('.mtbxr-sortable-textarea-skeleton').html().replace(/_dummy/g,'');/*** Remove _dummy from name for field to be included in the save ***/
			jQuery(new_box_html).appendTo(sortable);
		});
		
		/*** Delete ***/
		jQuery('.mtbxr-sortable-textareas').on('click', '.mtbxr-textarea-delete', function(e) {
			e.preventDefault();
			
			var box = jQuery(this).parents('.mtbxr-sortable-textarea');
			box.fadeOut('slow', function(){
				box.remove();
			});
		});
	})();
    
    /*** Sortable Textboxes ***/
    (function() {
		jQuery('.mtbxr-sortable-textboxes').sortable({
			handle:'.mtbxr-textbox-drag',
			placeholder: "mtbxr-textbox-placeholder",
			forcePlaceholderSize:true
		});
		
		/*** Add ***/
		jQuery('.mtbxr-field').on('click', '.mtbxr-textbox-add', function(e) {
			e.preventDefault();
			var field = jQuery(this).parents('.mtbxr-field');
			var sortable = field.find('.mtbxr-sortable-textboxes');
			var new_box_html = field.find('.mtbxr-sortable-textbox-skeleton').html().replace(/_dummy/g,'');/*** Remove _dummy from name for field to be included in the save ***/
			jQuery(new_box_html).appendTo(sortable);
		});
		
		/*** Delete ***/
		jQuery('.mtbxr-sortable-textboxes').on('click', '.mtbxr-textbox-delete', function(e) {
			e.preventDefault();
			
			var box = jQuery(this).parents('.mtbxr-sortable-textbox');
			box.fadeOut('slow', function(){
				box.remove();
			});
		});
	})();
    
    /*** Vimeo Videos ***/
    (function() {
		jQuery('.mtbxr-vimeo-videos').sortable({
			handle:'.mtbxr-vimeo-drag',
			placeholder: "mtbxr-vimeo-placeholder",
			forcePlaceholderSize:true
		});
		
		/*** Add ***/
		jQuery('.mtbxr-field').on('click', '.mtbxr-vimeo-add', function(e) {
			e.preventDefault();
			var field = jQuery(this).parents('.mtbxr-field');
			var sortable = field.find('.mtbxr-vimeo-videos');
			var new_box_html = field.find('.mtbxr-vimeo-skeleton').html().replace(/_dummy/g,'');/*** Remove _dummy from name for field to be included in the save ***/
			jQuery(new_box_html).appendTo(sortable);
		});
		
		/*** Update field ***/
		jQuery('.mtbxr-field').on('blur', '.mtbxr-vimeo-url', function(e) {
			var textbox = jQuery(this);
			var previewer = textbox.siblings('.mtbxr-vimeo-preview');
				
			if(jQuery.trim(jQuery(this).val())!='' && previewer.attr('href') != jQuery(this).val()){ /*** Not blank and change url detected ***/
				
				if(previewer.length <= 0){ /*** Non existent, create previewer and push textbox down for better look ***/
					textbox.addClass('push').before('<a href="'+textbox.val()+'" class="mtbxr-vimeo-preview" target="_blank"></a>');
				} else { /*** Existing previewer***/
					previewer.attr('href', textbox.val()); /*** Update link ***/
					previewer.children('img').remove();
				}
							
				jQuery.ajax({
					type: "POST",
					url: ajaxurl,
					data: "action=mtbxr_get_vimeo_thumb&url="+encodeURIComponent(jQuery(this).val()),
					dataType: 'json',
					success: function(data, textStatus, XMLHttpRequest){
						var previewer = textbox.siblings('.mtbxr-vimeo-preview');
						var img = previewer.children('img');
						
						if(data.success){
							previewer.append('<img src="'+data.url+'" alt="" />'); /*** Add our thumb with the updated url ***/
							
						} else {
							/*** Reset and let users know ***/
							previewer.remove();
							textbox.removeClass('push');
							
						}
						
					}
				});
			}
		});
		
		/*** Delete ***/
		jQuery('.mtbxr-vimeo-videos').on('click', '.mtbxr-vimeo-delete', function(e) {
			e.preventDefault();
			
			var box = jQuery(this).parents('.mtbxr-vimeo-video');
			box.fadeOut('slow', function(){
				box.remove();
			});
		});
	})();
    
    /*** Youtube Videos ***/
    (function() {
		jQuery('.mtbxr-youtube-videos').sortable({
			handle:'.mtbxr-youtube-drag',
			placeholder: "mtbxr-youtube-placeholder",
			forcePlaceholderSize:true
		});
		
		/*** Add ***/
		jQuery('.mtbxr-field').on('click', '.mtbxr-youtube-add', function(e) {
			e.preventDefault();
			var field = jQuery(this).parents('.mtbxr-field');
			var sortable = field.find('.mtbxr-youtube-videos');
			var new_box_html = field.find('.mtbxr-youtube-skeleton').html().replace(/_dummy/g,'');/*** Remove _dummy from name for field to be included in the save ***/
			jQuery(new_box_html).appendTo(sortable);
		});
		
		/*** Update field ***/
		jQuery('.mtbxr-field').on('blur', '.mtbxr-youtube-url', function(e) {
			var textbox = jQuery(this);
			var previewer = textbox.siblings('.mtbxr-youtube-preview');
				
			if(jQuery.trim(jQuery(this).val())!='' && previewer.attr('href') != jQuery(this).val()){ /*** Not blank and change url detected ***/
				
				if(previewer.length <= 0){ /*** Non existent, create previewer and push textbox down for better look ***/
					textbox.addClass('push').before('<a href="'+textbox.val()+'" class="mtbxr-youtube-preview" target="_blank"></a>');
				} else { /*** Existing previewer***/
					previewer.attr('href', textbox.val()); /*** Update link ***/
					previewer.children('img').remove();
				}
							
				jQuery.ajax({
					type: "POST",
					url: ajaxurl,
					data: "action=mtbxr_get_youtube_thumb&url="+encodeURIComponent(jQuery(this).val()),
					dataType: 'json',
					success: function(data, textStatus, XMLHttpRequest){
						var previewer = textbox.siblings('.mtbxr-youtube-preview');
						var img = previewer.children('img');
						
						if(data.success){
							previewer.append('<img src="'+data.url+'" alt="" />'); /*** Add our thumb with the updated url ***/
							
						} else {
							/*** Reset and let users know ***/
							previewer.remove();
							textbox.removeClass('push');
							
						}
						
					}
				});
			}
		});
		
		/*** Delete ***/
		jQuery('.mtbxr-youtube-videos').on('click', '.mtbxr-youtube-delete', function(e) {
			e.preventDefault();
			
			var box = jQuery(this).parents('.mtbxr-youtube-video');
			box.fadeOut('slow', function(){
				box.remove();
			});
		});
	})();
});