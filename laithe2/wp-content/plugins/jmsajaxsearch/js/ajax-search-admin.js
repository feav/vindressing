"use strict";
jQuery(document).ready(function () {	
	jQuery('.ui.checkbox').checkbox();
	jQuery('.ui.dropdown.multi').each(function() { 		
		var values = jQuery(this).parent().find('.selected_vals').eq(0).val();
		if(values)
			jQuery(this).dropdown("set selected", values.split(','));					
		else 
			jQuery(this).dropdown();			
    })

	function assign_page_oncheck() {		
		if(jQuery('input[name="jmsajaxsearch_params[all_page]"]').is(":checked")) {			
			jQuery('.assign_pages').hide();
			jQuery('.exclude_pages').show();
		} else {
			jQuery('.assign_pages').show();
			jQuery('.exclude_pages').hide();
		}
	}
	jQuery('input[name="jmsajaxsearch_params[all_page]"]').change(function() {
		assign_page_oncheck();
	});
	assign_page_oncheck();

	jQuery('.color-field').wpColorPicker();
});