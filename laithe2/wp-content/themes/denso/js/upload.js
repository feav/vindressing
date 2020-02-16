jQuery(document).ready(function($){

	var denso_upload;
	var denso_selector;

	function denso_add_file(event, selector) {

		var upload = $(".uploaded-file"), frame;
		var $el = $(this);
		denso_selector = selector;

		event.preventDefault();

		// If the media frame already exists, reopen it.
		if ( denso_upload ) {
			denso_upload.open();
			return;
		} else {
			// Create the media frame.
			denso_upload = wp.media.frames.denso_upload =  wp.media({
				// Set the title of the modal.
				title: "Select Image",

				// Customize the submit button.
				button: {
					// Set the text of the button.
					text: "Selected",
					// Tell the button not to close the modal, since we're
					// going to refresh the page when the image is selected.
					close: false
				}
			});

			// When an image is selected, run a callback.
			denso_upload.on( 'select', function() {
				// Grab the selected attachment.
				var attachment = denso_upload.state().get('selection').first();

				denso_upload.close();
				denso_selector.find('.upload_image').val(attachment.attributes.url).change();
				if ( attachment.attributes.type == 'image' ) {
					denso_selector.find('.denso_screenshot').empty().hide().prepend('<img src="' + attachment.attributes.url + '">').slideDown('fast');
				}
			});

		}
		// Finally, open the modal.
		denso_upload.open();
	}

	function denso_remove_file(selector) {
		selector.find('.denso_screenshot').slideUp('fast').next().val('').trigger('change');
	}
	
	$('body').on('click', '.denso_upload_image_action .remove-image', function(event) {
		denso_remove_file( $(this).parent().parent() );
	});

	$('body').on('click', '.denso_upload_image_action .add-image', function(event) {
		denso_add_file(event, $(this).parent().parent());
	});

});