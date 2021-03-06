'use strict';
var woorebought = {
	loop        : rb_loop,
	init_delay  : rb_init_delay,
	total       : rb_total,
	display_time: rb_display_time,
	next_time   : rb_next_time,
	count       : 0,
	intel       : 0,
	id          : 0,
	popup_content    : '',
	products    : '',
	ajax_url    : '',
	init        : function () {		
		setTimeout(function () {
			woorebought.get_product();
		}, this.init_delay * 1000);

	},
	popup_show: function () {
		var count = this.count++;
		if (this.total <= count) {
			return;
		}
		window.clearInterval(this.intel);
		var popup_id = jQuery('#woorebought-popup'),
			popup_show_trans = jQuery('#woorebought-popup').data('show_trans'),
			popup_hide_trans = jQuery('#woorebought-popup').data('hide_trans');
		if (popup_id.hasClass(popup_hide_trans)) {
			jQuery(popup_id).removeClass(popup_hide_trans);
		}
		jQuery(popup_id).addClass(popup_show_trans).css('display', 'flex');
		setTimeout(function () {
			woorebought.popup_hide();
		}, this.display_time * 1000);
	},

	popup_hide: function () {
		var popup_id = jQuery('#woorebought-popup'),
			popup_show_trans = jQuery('#woorebought-popup').data('show_trans'),
			popup_hide_trans = jQuery('#woorebought-popup').data('hide_trans');

		if (popup_id.hasClass(popup_show_trans)) {
			jQuery(popup_id).removeClass(popup_show_trans);
		}
		jQuery('#woorebought-popup').addClass(popup_hide_trans);
		jQuery('#woorebought-popup').fadeOut(1000);
		if (this.loop) {
			this.intel = setInterval(function () {
				woorebought.get_product();
			}, this.next_time * 1000);
		}
	},
	get_product : function () {
		
		if (this.ajax_url) {
			var str_data;
			if (this.id) {
				str_data = '&id=' + this.id;
			} else {
				str_data = '';
			}			
			jQuery.ajax({
				type   : 'POST',
				data   : 'action=woorebought_show_product' + str_data,
				url    : this.ajax_url,
				success: function (html) {
					var content = jQuery(html).children();
					jQuery("#woorebought-popup").html(content);
					woorebought.popup_show();
					jQuery('#notify-close').on('click', function () {
						woorebought.popup_hide();
					});
				},
				error  : function (html) {
				}
			})
		} else {
			var products = atob(this.products);
			var popup_content = atob(this.popup_content);
			var image_redirect = this.image;
			products = jQuery.parseJSON(products);
			popup_content = jQuery.parseJSON(popup_content);			
			if (products.length > 0) {				
				/*Get data*/
				var index = woorebought.random(0, products.length - 1);
				var product = products[index];				
				var data_address = product.address;				
				var data_product = product.title;
				var data_product_link = '<a target="_blank" href="' + product.product_link + '">' + product.title + '</a>';
				var data_time = '<small>About ' + product.time + ' ago </small>';				
				var image_html = '';
				if (product.image_link) {
					if (image_redirect) {
						image_html = '<a target="_blank" href="' + product.product_link + '"><img src="' + product.image_link + '"></a>'
					} else {
						image_html = '<img src="' + product.image_link + '">';
					}
				}
				/*Replace Content*/

				var replaceArray = ['{address}', '{product_name}', '{product_link}', '{time_ago}'];
				var replaceArrayValue = [data_address, data_product, data_product_link, data_time];
				var finalAns = popup_content;
				for (var i = replaceArray.length - 1; i >= 0; i--) {
					finalAns = finalAns.replace(replaceArray[i], replaceArrayValue[i]);
				}
				var html = image_html + '<p>' + finalAns + '</p>';
				jQuery('.woorebought-content').html(html);
				woorebought.popup_show();
			}
		}
	},
	close_notify: function () {
		jQuery('#popup-close').on('click', function () {
			woorebought.popup_hide();
		});
	},
	random      : function (min, max) {
		return Math.floor(Math.random() * (max - min + 1)) + min;
	}
}

jQuery(document).ready(function () {
	if (jQuery('#woorebought-popup').length > 0) {
		var data = jQuery('#woorebought-popup').data();
		var popup = woorebought;
		popup.ajax_url = data.url;
		popup.products = data.products;
		popup.popup_content = data.popup_content;
		popup.image = data.image;

		if (data.product) {
			popup.id = data.product;
		}
		popup.init();
	}

	jQuery('#popup-close').on('click', function () {
		woorebought.popup_hide();
	});
});
