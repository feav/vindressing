'use strict';
jQuery(document).ready(function () {	
	jQuery('.ui.checkbox').checkbox();
	jQuery('.ui.dropdown.multi').each(function() { 		
		var values = jQuery(this).parent().find('.selected_vals').eq(0).val();
		if(values)
			jQuery(this).dropdown("set selected", values.split(','));					
		else 
			jQuery(this).dropdown();			
    })
	$('.color-field').wpColorPicker();
	function product_src_onchange() {
		var data = jQuery('select[name="woorebought_params[product_src]"]').val();
		jQuery('.src_field').hide();
		if (data == 'orders') {
			jQuery('.from_orders').show();
		} else if (data == 'categories') {
			jQuery('.from_categories').show();
		} else if (data == 'products') {
			jQuery('.from_products').show();
		}	
	}
	jQuery('select[name="woorebought_params[product_src]"]').on('change', function () {
		product_src_onchange();
	});
	product_src_onchange();
	function assign_page_oncheck() {		
		if(jQuery('input[name="woorebought_params[all_page]"]').is(":checked")) {			
			jQuery('.assign_pages').hide();
			jQuery('.exclude_pages').show();
		} else {
			jQuery('.assign_pages').show();
			jQuery('.exclude_pages').hide();
		}
	}
	jQuery('input[name="woorebought_params[all_page]"]').change(function() {
		assign_page_oncheck();
	});
	assign_page_oncheck();
});