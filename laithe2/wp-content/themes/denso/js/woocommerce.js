(function($) {
	'use strict';
	// add to cart modal
    var product_info = null;
    var apus_product_id = null;
    jQuery('body').bind('adding_to_cart', function( button, data , data2 ) {
        product_info = data;
        apus_product_id =  data.data('product_id');
    });

    jQuery('body').bind('added_to_cart', function( fragments, cart_hash ){
        if( apus_product_id ){
            var imgtodrag = $('[data-product-id="'+apus_product_id+'"] .image img').eq(0);
            var cart =  $('#cart');
            if (imgtodrag) {
                var imgclone = imgtodrag.clone()
                    .offset({
                    top: product_info.offset().top-imgtodrag.height(),
                    left: product_info.offset().left
                })
                .css({
                    'opacity': '0.8',
                        'position': 'absolute',
                        'height': '150px',
                        'width': 'auto',
                        'z-index': '100000'
                })
                .appendTo($('body'))
                .animate({
                    'top': cart.offset().top + 10,
                        'left': cart.offset().left + 10,
                        'width': 75,
                        'height': 75
                }, 1000);
            
                setTimeout(function () {
                    $('.mini-cart').click();
                    cart.stop().animate({'margin-left':10},100).animate( {'margin-left':-10}, 200 ).animate( {'margin-left':0}, 100);

                }, 1500);
            
                imgclone.animate({
                    'width': 0,
                    'height': 0
                }, function () {
                    $(this).detach()
                });
            }
            $("html, body").stop().animate({ scrollTop:  cart.offset().top-50  }, "slow");
        }
    });
    
	// Ajax QuickView
	jQuery(document).ready(function($){
		$('body').on( 'click', 'a.quickview', function (e) {
			e.preventDefault();
			var self = $(this);
			self.parent().parent().parent().addClass('loading');
		    var productslug = jQuery(this).data('productslug');
		    var url = denso_ajax.ajaxurl + '?action=denso_quickview_product&productslug=' + productslug;
		    
	    	jQuery.get(url,function(data,status){
		    	$.magnificPopup.open({
					mainClass: 'apus-mfp-zoom-in',
					items    : {
						src : data,
						type: 'inline'
					}
				});
				// variation
                if ( typeof wc_add_to_cart_variation_params !== 'undefined' ) {
                    $( '.variations_form' ).each( function() {
                        $( this ).wc_variation_form().find('.variations select:eq(0)').change();
                    });
                }
                var config = {
                    infinite: true,
                    arrows: true,
                    dots: true,
                    slidesToShow: 1,
                    slidesToScroll: 1
                };
                $(".quickview-slick").slick( config );
                
				self.parent().parent().parent().removeClass('loading');
		    });
		});
	});
	
	// thumb image
	$('.thumbnails-image-carousel .thumb-link, .lite-carousel-play .thumb-link').each(function(e){
		$(this).click(function(event){
			event.preventDefault();
			var $slick = $( '.main-image-carousel' );
			if ($slick.hasClass('slick-initialized')) {
				$slick[0].slick.slickGoTo( e );
			}

			$('.thumbnails-image-carousel .image-wrapper').removeClass('slick-current');
			$(this).parent().addClass('slick-current');
			return false;
		});
	});
	// change thumb variants
	$( 'body' ).on( 'found_variation', function( event, variation ) {
    	if ( variation && variation.image_src && variation.image_src.length > 1 ) {
    		var $slick = $( '.main-image-carousel' );
    		$('.main-image-carousel a').each(function(e){
    			var src = $('img', $(this)).attr('src');
    			if (src === variation.image_src) {
    				if ($slick.hasClass('slick-initialized')) {
	    				$slick[0].slick.slickGoTo( e );
	    			}
    			}
    		});
    	}
	});
	// special image
	$('.apus-special-images').each(function(){
		var self = $(this);
		$('.thumbnails-image-carousel .thumb-link', self).click(function(event){
			event.preventDefault();
			var href = $(this).attr( 'href' );
			self.find('.product-image img').attr( 'src', href );
			
			$('.thumbnails-image-carousel .thumb-link').removeClass('active');
			$(this).addClass('active');
			return false;
		});
	});

	// review rating
    $('.woocommerce-review-link').click(function(){
        $('.woocommerce-tabs a[href=#tabs-list-reviews]').click();
        $('html, body').animate({
            scrollTop: $("#reviews").offset().top
        }, 1000);
        return false;
    });
    if ( $('.comment-form-rating').length > 0 ) {
        var $star = $('.comment-form-rating .review-stars');
        var $review = $('#rating');
        $star.find('li').on('mouseover',
            function () {
                $(this).nextAll().find('span').removeClass('active');
                $(this).prevAll().find('span').removeClass('active').addClass('active');
                $(this).find('span').removeClass('active').addClass('active');
                $review.val($(this).index() + 1);
            }
        );
    }
	// Ajax QuickView Review Rating
	jQuery(document).ready(function($){
		$('body').on('mouseenter', 'a.quickview-rating', function(e){
			e.preventDefault();
			if ($(window).width() < 768) {
				return false;
			}
			$('a.quickview-rating').removeClass('active');
			$('.rating-popover-content').attr('style', '');
			var self = $(this);
			self.addClass('loading');
		    var productslug = jQuery(this).data('productslug');
		    var url = denso_ajax.ajaxurl + '?action=denso_quickview_rating_product&productslug=' + productslug;
		    var window_width = $( window ).width();
		    var offset = $( this ).offset();

	    	jQuery.get(url,function(data,status){
	    		var right = window_width - offset.left;
			    if (right < 315) {
			    	$('.rating-popover-content').css({
			    		'position': 'absolute',
			    		'right': 15,
			    		'left': 'inherit',
			    		'top': offset.top + 20
			    	});
			    } else {
			    	$('.rating-popover-content').css({
			    		'position': 'absolute',
			    		'left': offset.left - 100,
			    		'top': offset.top + 20
			    	});
			    }
			    self.addClass('active');
		    	$('.rating-popover-content').html(data).show();
				self.removeClass('loading');
		    });
		});
		$('body').on('mouseleave', '.rating-popover-content', function(e){
			if ($(window).width() < 768) {
				return false;
			}
			$(this).hide();
			$('a.quickview-rating').removeClass('active');
		}).on('click', '.rating-popover-content', function(e){
			e.stopPropagation();
		});
		$(document).click(function(){
		    $('.rating-popover-content').hide();
		    $('a.quickview-rating').removeClass('active');
		});
	});
	// compare
  	$(document).on('click','.view-popup-compare', function(e){
        e.preventDefault();
        var href = $(this).attr('href');
        $('body').trigger('yith_woocompare_open_popup', { response: href });
    });
  	// favourite vendor
  	$('body').on('click','.denso-favourite-vendor', function(e){
		"use strict";
		e.preventDefault();
		var btn = $(this);
		var vendor_id = btn.data("vendor_id");
		btn.addClass('loading');
		$.ajax({
			type: "post",
			dataType: 'json',
			url: denso_woo.ajaxurl,
			data: "action=denso-favourite-vendor&nonce=" + denso_woo.nonce + "&vendor_id=" + vendor_id,
			success: function(data){
				btn.removeClass('loading');
				if ( btn.hasClass('remove-vendor') ) {
					btn.removeClass('denso-favourite-vendor');
					btn.html('<i class="mn-icon-4"></i> Removed Successfully');
				} else {
					if (data.status == 'success') {
						btn.addClass('added');
						btn.prop('title', data.title);
						btn.find('span').html(data.msg);
					} else if (data.status == 'no-access') {
						$(".wcv-header-container").before(data.msg);
					} else {
						btn.removeClass('added');
						btn.prop('title', data.title);
						btn.find('span').html(data.msg);
					}
				}
			}
		});
	});
	// categories
	$('.widget_product_categories ul li.cat-item').each(function(){
        if ($(this).find('ul.children').length > 0) {
            $(this).prepend('<i class="closed mn-icon-161"></i>');
        }
        $(this).find('ul.children').hide();
    });
    $( "body" ).on( "click", '.widget_product_categories ul li.cat-item .closed', function(){
        $(this).parent().find('ul.children').first().slideDown();
        $(this).removeClass('closed').removeClass('mn-icon-161').addClass('opened').addClass('mn-icon-160');
    });
    $( "body" ).on( "click", '.widget_product_categories ul li.cat-item .opened', function(){
        $(this).parent().find('ul.children').first().slideUp();
        $(this).removeClass('opened').removeClass('mn-icon-160').addClass('closed').addClass('mn-icon-161');
    });

    // view more for filter
    $('.woocommerce-widget-layered-nav-list').each(function(e){
        var height = $(this).outerHeight();
        if ( height > 260 ) {
            var view_more = '<a href="javascript:void(0);" class="view-more-list view-more"><span>'+denso_woo.view_more_text+'</span> <i class="fa fa-angle-double-right"></i></a>';
            $(this).parent().append(view_more);
            $(this).addClass('hideContent');
        }
    });

    $('body').on('click', '.view-more-list', function() {
       
        var $this = $(this); 
        var $content = $this.parent().find(".woocommerce-widget-layered-nav-list"); 
        
        if ( $this.hasClass('view-more') ) {
            var linkText = denso_woo.view_less_text;
            $content.removeClass("hideContent").addClass("showContent");
            $this.removeClass("view-more").addClass("view-less");
        } else {
            var linkText = denso_woo.view_more_text;
            $content.removeClass("showContent").addClass("hideContent");
            $this.removeClass("view-less").addClass("view-more");
        };

        $this.find('span').text(linkText);
    });


    // accessories
    var densoAccessories = {
    	init: function() {
    		var self = this;
    		// check box click
    		$('body').on('click', '.accessoriesproducts .accessory-add-product', function() {
    			self.change_event();
			});
			// check all
			self.check_all_items();
    		// add to cart
    		self.add_to_cart();
    	},
    	add_to_cart: function() {
    		var self = this;
    		$('body').on('click', '.add-all-items-to-cart:not(.loading)', function(e){
    			e.preventDefault();
    			var self_this = $(this);
    			self_this.addClass('loading');
				var all_product_ids = self.get_checked_product_ids();

				if( all_product_ids.length === 0 ) {
					var msg = denso_woo.empty;
				} else {
					for (var i = 0; i < all_product_ids.length; i++ ) {
						$.ajax({
							type: "POST",
							async: false,
							url: denso_ajax.ajaxurl,
							data: {
								'product_id': all_product_ids[i],
								'action': 'woocommerce_add_to_cart'
							},
							success : function( response ) {
								self.refresh_fragments( response );
							}
						});
					}
					var msg = denso_woo.success;
				}
				$( '.denso-wc-message' ).html(msg);
				self_this.removeClass('loading');
			});
    	},
    	change_event: function() {
    		var self = this;
    		$('.accessoriesproducts-wrapper').addClass('loading');
			var total_price = self.get_total_price();
			$.ajax({
				type: "POST",
				async: false,
				url: denso_ajax.ajaxurl,
				data: { 'action': "denso_get_total_price", 'data': total_price  },
				success : function( response ) {
					$( 'span.total-price .amount' ).html( response );
					$( 'span.product-count' ).html( self.product_count() );

					var product_ids = self.get_unchecked_product_ids();
					$( '.accessoriesproducts .list-v2' ).each(function() {
						$(this).parent().removeClass('is-disable');
						for (var i = 0; i < product_ids.length; i++ ) {
							if( $(this).hasClass( 'list-v2-'+product_ids[i] ) ) {
								$(this).parent().addClass('is-disable');
							}
						}
					});
				}
			});
			$('.accessoriesproducts-wrapper').removeClass('loading');
    	},
    	check_all_items: function() {
    		var self = this;
    		$('.check-all-items').click(function(){
    			$('.accessory-add-product:checkbox').not(this).prop('checked', this.checked);
    			if ($(this).is(":checked")) {
					$('.accessory-add-product:checkbox').prop('checked', true);  
				} else {
					$('.accessory-add-product:checkbox').prop("checked", false);
				}

				self.change_event();
    		});
    	},
    	// count product
    	product_count: function(){
			var pcount = 0;
			$('.accessoriesproducts .accessory-add-product').each(function() {
				if ($(this).is(':checked')) {
					pcount++;
				}
			});
			return pcount;
		},
		// get total price
		get_total_price(){
			var tprice = 0;
			$('.accessoriesproducts .accessory-add-product').each(function() {
				if( $(this).is(':checked') ) {
					tprice += parseFloat( $(this).data( 'price' ) );
				}
			});
			return tprice;
		},
		// get checked product ids
		get_checked_product_ids: function(){
			var pids = [];
			$('.accessoriesproducts .accessory-add-product').each(function() {
				if( $(this).is(':checked') ) {
					pids.push( $(this).data( 'id' ) );
				}
			});
			return pids;
		},
		// get unchecked product ids
		get_unchecked_product_ids(){
			var pids = [];
			$('.accessoriesproducts .accessory-add-product').each(function() {
				if( ! $(this).is(':checked') ) {
					pids.push( $(this).data( 'id' ) );
				}
			});
			return pids;
		},
		refresh_fragments: function( response ){
			var frags = response.fragments;

			// Block fragments class
			if ( frags ) {
				$.each( frags, function( key ) {
					$( key ).addClass( 'updating' );
				});
			}
			if ( frags ) {
				$.each( frags, function( key, value ) {
					$( key ).replaceWith( value );
				});
			}
		}
    }
    densoAccessories.init();

})(jQuery)

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires+";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";
}