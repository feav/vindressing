<?php
//convert hex to rgb
if ( !function_exists ('denso_getbowtied_hex2rgb') ) {
	function denso_getbowtied_hex2rgb($hex) {
		$hex = str_replace("#", "", $hex);
		
		if(strlen($hex) == 3) {
			$r = hexdec(substr($hex,0,1).substr($hex,0,1));
			$g = hexdec(substr($hex,1,1).substr($hex,1,1));
			$b = hexdec(substr($hex,2,1).substr($hex,2,1));
		} else {
			$r = hexdec(substr($hex,0,2));
			$g = hexdec(substr($hex,2,2));
			$b = hexdec(substr($hex,4,2));
		}
		$rgb = array($r, $g, $b);
		return implode(",", $rgb); // returns the rgb values separated by commas
		//return $rgb; // returns an array with the rgb values
	}
}
if ( !function_exists ('denso_custom_styles') ) {
	function denso_custom_styles() {
		global $post;	
		
		ob_start();	
		?>
		
		<!-- ******************************************************************** -->
		<!-- * Theme Options Styles ********************************************* -->
		<!-- ******************************************************************** -->
		<style>
			/* check main color */ 
			<?php if ( denso_get_config('main_color') != "" ) : ?>

				/* seting background main */
				.widget-products.widget-products-special.style2 .carousel-control:hover,
				.store-aurhor-inner .btn-mess:hover,
				.store-info .denso-favourite-vendor.added,
				.information .cart .btn, .information .cart .button,
				.accessoriesproducts .add-all-items-to-cart,
				.btn-theme.btn-outline:hover,.btn-theme.btn-outline:active,
				.widget-newletter.style2 [type="submit"]::before,
				.header-v3 .apus-search-form .select-category .dropdown_product_cat,
				.header-v3 .view-popup-compare span, .header-v3 .cart-icon .count,
				.header-v3 .apus-search-form .select-category,
				.slick-carousel.slick-small .slick-arrow:hover, .slick-carousel.slick-small .slick-arrow:active,
				.widget-category-info.layout2 .header-icon,
				.quickview-container .btn,
				.special-progress .progress .progress-bar,
				#back-to-top,
				.product-block .groups-button .add-cart .btn:active, .product-block .groups-button .add-cart .button:active, .product-block .groups-button .add-cart .btn:hover, .product-block .groups-button .add-cart .button:hover,
				.widget-features-box .feature-box:hover .fbox-icon,
				.slick-carousel .slick-dots li.slick-active button,
				.apus-search-form .input-group .input-group-btn .btn,
				.widget.widget_apus_vertical_menu > .widget-title,
				.bg-theme,.btn-theme
				{
					background: <?php echo esc_html( denso_get_config('main_color') ) ?> !important;
				}
				.widget-products.widget-products-special .widget-title-wrapper .widget-title,{
					background: <?php echo esc_html( denso_get_config('main_color') ) ?> ;
				}
				/* setting color*/
				.post-grid .meta a:hover, .post-grid .meta a:active,
				.widget-category-info.layout3 .category-info-list li.view-more a:hover,
				.store-aurhor-inner .btn-mess,
				.layout-blog .post-grid .entry-title a:hover, .layout-blog .post-grid .entry-title a:active,
				.widget-social .social > li a:hover i,
				.widget-features-box .feature-box .fbox-icon,
				.widget-products.widget-products-special.style2 .widget-title,
				.widget-social.style1 .widget-title,
				.widget-newletter.style2 .widget-title,
				.widget.carousel-list .post-author a,
				.widget-category-info.layout3 .category-info-list a:hover, .widget-category-info.layout3 .category-info-list a:active,
				.widget-products.widget-products-special.style2 .carousel-control:hover, .widget-products.widget-products-special.style2 .carousel-control:active,
				.widget-products.widget-products-special.style2 .carousel-control,
				.header-v3 .header-setting a:hover, .header-v3 .header-setting:active,
				.cart-icon:hover i,
				.cart-icon:hover .total-price .amount,
				.header-v3 .contact-header .media-left i,
				.post-grid .entry-title a:hover,
				.widget-features-box.style1 .feature-box .ourservice-heading,
				.slick-carousel.slick-small .slick-arrow,
				.widget-features-box.style1 .feature-box .fbox-icon i,
				.header-v2 .widget.widget_apus_vertical_menu > .widget-title,
				.groups-button .add-cart .added_to_cart:hover,
				.product-block:hover .groups-button .yith-wcwl-add-to-wishlist i, .product-block:hover .groups-button .yith-wcwl-add-to-wishlist em, .product-block:hover .groups-button .yith-compare i, .product-block:hover .groups-button .yith-compare em,
				.product-block:hover .groups-button .add-cart .btn, .product-block:hover .groups-button .add-cart .button,
				.contact-header .media-left i,
				.header-bottom .cart-icon i,
				.widget-features-box .feature-box:hover .ourservice-heading,
				.widget-category-info.layout1 h3 a,
				.style_border .feature-box i,
				a:hover,a:active,
				.name a:hover,
				.widget-category-info.layout1 .sub-categories a:hover,
				.widget-category-info.layout1 .sub-categories ul li.view-more a:hover
				{
					color: <?php echo esc_html( denso_get_config('main_color') ) ?>;
				}
				.text-theme,
				.btn-theme.btn-outline,
				.apus-vertical-menu a:hover, .apus-vertical-menu a:active, .apus-vertical-menu a:focus{
					color: <?php echo esc_html( denso_get_config('main_color') ) ?> !important;
				}
				/* setting border color*/
				.btn-theme:hover, .btn-theme:focus, .btn-theme:active, .btn-theme.active, .open > .btn-theme.dropdown-toggle,
				.btn-theme.btn-outline,
				.widget-social.style1 .social > li a:hover,
				.widget-features-box .feature-box .fbox-icon,
				.widget-newletter.style2 .form-control,
				.widget-newletter.style2 [type="submit"],
				.widget-products.widget-products-special.style2 .carousel-control,
				.widget-products.widget-products-special.style2 .carousel-control:hover, .widget-products.widget-products-special.style2 .carousel-control:active,
				.header-v3 .apus-search-form,
				.header-v3 .contact-header .media-left i,
				.widget-testimonials .image-wrapper,
				.widget-category-info.layout2 .header-info,
				.slick-carousel.slick-small .slick-arrow,
				ul.nav.style2 > li.active,
				.widget-features-box.style1 .feature-box .fbox-icon,
				.quickview-container .btn,
				.groups-button .add-cart .added_to_cart:hover,
				.slick-carousel .slick-arrow:hover, .slick-carousel .slick-arrow:active,
				.product-block:hover .groups-button .add-cart .btn, .product-block:hover .groups-button .add-cart .button,
				.widget-features-box .feature-box:hover .fbox-icon,
				.style_border .feature-box .fbox-icon,
				ul.nav.style1 > li.active,
				.slick-carousel .slick-dots li.slick-active,
				.header-v1 .apus-vertical-menu,
				.btn-theme,
				.border-theme{
					border-color: <?php echo esc_html( denso_get_config('main_color') ) ?>;
				}
				.store-aurhor-inner .btn-mess,
				.store-info .denso-favourite-vendor.added,
				.accessoriesproducts .add-all-items-to-cart,
				.btn-theme.btn-outline:hover,.btn-theme.btn-outline:active,
				.btn-theme.btn-outline{
					border-color: <?php echo esc_html( denso_get_config('main_color') ) ?> !important;
				}
				.btn-theme.btn-outline:hover,.btn-theme.btn-outline:active{
					color:#fff !important;
				}
				.apus-page-loading #loader, .apus-page-loading #loader::before, .apus-page-loading #loader::after {
					border-color: <?php echo esc_html( denso_get_config('main_color') ) ?> transparent transparent;
				}
				.woocommerce #respond input#submit:hover, .woocommerce #respond input#submit:active, .woocommerce a.button:hover, .woocommerce a.button:active, .woocommerce button.button:hover, .woocommerce button.button:active, .woocommerce input.button:hover, .woocommerce input.button:active, .btn-inverse.btn-primary:hover {
					background: <?php echo esc_html( denso_get_config('main_color') ) ?> !important;
					border-color: <?php echo esc_html( denso_get_config('main_color') ) ?> !important;
				}
			<?php endif; ?>
			<?php if ( denso_get_config('button_bghover') != "" ) : ?>
				.header-v5 .apus-search-form .input-group .input-group-btn .btn:hover,
				.header-v3 .apus-search-form .input-group .input-group-btn .btn:hover,
				.information .cart .btn:hover,.information .cart .btn:active, .information .cart .button:hover,.information .cart .button:active,
				.btn-theme:hover,.btn-theme:active
				{
					background: <?php echo esc_html( denso_get_config('button_bghover') ) ?> !important;
				}
				.btn-theme:hover,.btn-theme:active
				{
					border-color: <?php echo esc_html( denso_get_config('button_bghover') ) ?> !important;
				}
			<?php endif; ?>
			/* Typo */
			<?php
				$main_font = denso_get_config('main_font');
				if ( !empty($main_font) ) :
			?>
				/* seting background main */
				body, p
				{
					<?php if ( isset($main_font['font-family']) && $main_font['font-family'] ) { ?>
						font-family: <?php echo esc_html( $main_font['font-family'] ) ?>;
					<?php } ?>
					<?php if ( isset($main_font['font-weight']) && $main_font['font-weight'] ) { ?>
						font-weight: <?php echo esc_html( $main_font['font-weight'] ) ?>;
					<?php } ?>
					<?php if ( isset($main_font['font-style']) && $main_font['font-style'] ) { ?>
						font-style: <?php echo esc_html( $main_font['font-style'] ) ?>;
					<?php } ?>
					<?php if ( isset($main_font['font-size']) && $main_font['font-size'] ) { ?>
						font-size: <?php echo esc_html( $main_font['font-size'] ) ?>;
					<?php } ?>
					<?php if ( isset($main_font['line-height']) && $main_font['line-height'] ) { ?>
						line-height: <?php echo esc_html( $main_font['line-height'] ) ?>;
					<?php } ?>
					<?php if ( isset($main_font['color']) && $main_font['color'] ) { ?>
						color: <?php echo esc_html( $main_font['color'] ) ?>;
					<?php } ?>
				}
				
			<?php endif; ?>

			<?php
				$second_font = denso_get_config('second_font');
				if ( !empty($second_font) ) :
			?>
				/* seting background main */
				.widget-title
				{
					<?php if ( isset($second_font['font-family']) && $second_font['font-family'] ) { ?>
						font-family: <?php echo esc_html( $second_font['font-family'] ) ?>;
					<?php } ?>
					<?php if ( isset($second_font['font-weight']) && $second_font['font-weight'] ) { ?>
						font-weight: <?php echo esc_html( $second_font['font-weight'] ) ?>;
					<?php } ?>
					<?php if ( isset($second_font['font-style']) && $second_font['font-style'] ) { ?>
						font-style: <?php echo esc_html( $second_font['font-style'] ) ?>;
					<?php } ?>
					<?php if ( isset($second_font['font-size']) && $second_font['font-size'] ) { ?>
						font-size: <?php echo esc_html( $second_font['font-size'] ) ?>;
					<?php } ?>
					<?php if ( isset($second_font['line-height']) && $second_font['line-height'] ) { ?>
						line-height: <?php echo esc_html( $second_font['line-height'] ) ?>;
					<?php } ?>
					<?php if ( isset($second_font['color']) && $second_font['color'] ) { ?>
						color: <?php echo esc_html( $second_font['color'] ) ?>;
					<?php } ?>
				}
				
			<?php endif; ?>

			/* H1 */
			<?php
				$h1_font = denso_get_config('h1_font');
				if ( !empty($h1_font) ) :
			?>
				/* seting background main */
				h1
				{
					<?php if ( isset($h1_font['font-family']) && $h1_font['font-family'] ) { ?>
						font-family: <?php echo esc_html( $h1_font['font-family'] ) ?>;
					<?php } ?>
					<?php if ( isset($h1_font['font-weight']) && $h1_font['font-weight'] ) { ?>
						font-weight: <?php echo esc_html( $h1_font['font-weight'] ) ?>;
					<?php } ?>
					<?php if ( isset($h1_font['font-style']) && $h1_font['font-style'] ) { ?>
						font-style: <?php echo esc_html( $h1_font['font-style'] ) ?>;
					<?php } ?>
					<?php if ( isset($h1_font['font-size']) && $h1_font['font-size'] ) { ?>
						font-size: <?php echo esc_html( $h1_font['font-size'] ) ?>;
					<?php } ?>
					<?php if ( isset($h1_font['line-height']) && $h1_font['line-height'] ) { ?>
						line-height: <?php echo esc_html( $h1_font['line-height'] ) ?>;
					<?php } ?>
					<?php if ( isset($h1_font['color']) && $h1_font['color'] ) { ?>
						color: <?php echo esc_html( $h1_font['color'] ) ?>;
					<?php } ?>
				}
			<?php endif; ?>

			/* H2 */
			<?php
				$h2_font = denso_get_config('h2_font');
				if ( !empty($h2_font) ) :
			?>
				/* seting background main */
				h2
				{
					<?php if ( isset($h2_font['font-family']) && $h2_font['font-family'] ) { ?>
						font-family: <?php echo esc_html( $h2_font['font-family'] ) ?>;
					<?php } ?>
					<?php if ( isset($h2_font['font-weight']) && $h2_font['font-weight'] ) { ?>
						font-weight: <?php echo esc_html( $h2_font['font-weight'] ) ?>;
					<?php } ?>
					<?php if ( isset($h2_font['font-style']) && $h2_font['font-style'] ) { ?>
						font-style: <?php echo esc_html( $h2_font['font-style'] ) ?>;
					<?php } ?>
					<?php if ( isset($h2_font['font-size']) && $h2_font['font-size'] ) { ?>
						font-size: <?php echo esc_html( $h2_font['font-size'] ) ?>;
					<?php } ?>
					<?php if ( isset($h2_font['line-height']) && $h2_font['line-height'] ) { ?>
						line-height: <?php echo esc_html( $h2_font['line-height'] ) ?>;
					<?php } ?>
					<?php if ( isset($h2_font['color']) && $h2_font['color'] ) { ?>
						color: <?php echo esc_html( $h2_font['color'] ) ?>;
					<?php } ?>
				}
			<?php endif; ?>

			/* H3 */
			<?php
				$h3_font = denso_get_config('h3_font');
				if ( !empty($h3_font) ) :
			?>
				/* seting background main */
				h3, 
                .widgettitle, .widget-title
				{
					<?php if ( isset($h3_font['font-family']) && $h3_font['font-family'] ) { ?>
						font-family: <?php echo esc_html( $h3_font['font-family'] ) ?>;
					<?php } ?>
					<?php if ( isset($h3_font['font-weight']) && $h3_font['font-weight'] ) { ?>
						font-weight: <?php echo esc_html( $h3_font['font-weight'] ) ?>;
					<?php } ?>
					<?php if ( isset($h3_font['font-style']) && $h3_font['font-style'] ) { ?>
						font-style: <?php echo esc_html( $h3_font['font-style'] ) ?>;
					<?php } ?>
					<?php if ( isset($h3_font['font-size']) && $h3_font['font-size'] ) { ?>
						font-size: <?php echo esc_html( $h3_font['font-size'] ) ?>;
					<?php } ?>
					<?php if ( isset($h3_font['line-height']) && $h3_font['line-height'] ) { ?>
						line-height: <?php echo esc_html( $h3_font['line-height'] ) ?>;
					<?php } ?>
					<?php if ( isset($h3_font['color']) && $h3_font['color'] ) { ?>
						color: <?php echo esc_html( $h3_font['color'] ) ?>;
					<?php } ?>
				}
			<?php endif; ?>

			/* H4 */
			<?php
				$h4_font = denso_get_config('h4_font');
				if ( !empty($h4_font) ) :
			?>
				/* seting background main */
				h4
				{
					<?php if ( isset($h4_font['font-family']) && $h4_font['font-family'] ) { ?>
						font-family: <?php echo esc_html( $h4_font['font-family'] ) ?>;
					<?php } ?>
					<?php if ( isset($h4_font['font-weight']) && $h4_font['font-weight'] ) { ?>
						font-weight: <?php echo esc_html( $h4_font['font-weight'] ) ?>;
					<?php } ?>
					<?php if ( isset($h4_font['font-style']) && $h4_font['font-style'] ) { ?>
						font-style: <?php echo esc_html( $h4_font['font-style'] ) ?>;
					<?php } ?>
					<?php if ( isset($h4_font['font-size']) && $h4_font['font-size'] ) { ?>
						font-size: <?php echo esc_html( $h4_font['font-size'] ) ?>;
					<?php } ?>
					<?php if ( isset($h4_font['line-height']) && $h4_font['line-height'] ) { ?>
						line-height: <?php echo esc_html( $h4_font['line-height'] ) ?>;
					<?php } ?>
					<?php if ( isset($h4_font['color']) && $h4_font['color'] ) { ?>
						color: <?php echo esc_html( $h4_font['color'] ) ?>;
					<?php } ?>
				}
			<?php endif; ?>

			/* H5 */
			<?php
				$h5_font = denso_get_config('h5_font');
				if ( !empty($h5_font) ) :
			?>
				/* seting background main */
				h5
				{
					<?php if ( isset($h5_font['font-family']) && $h5_font['font-family'] ) { ?>
						font-family: <?php echo esc_html( $h5_font['font-family'] ) ?>;
					<?php } ?>
					<?php if ( isset($h5_font['font-weight']) && $h5_font['font-weight'] ) { ?>
						font-weight: <?php echo esc_html( $h5_font['font-weight'] ) ?>;
					<?php } ?>
					<?php if ( isset($h5_font['font-style']) && $h5_font['font-style'] ) { ?>
						font-style: <?php echo esc_html( $h5_font['font-style'] ) ?>;
					<?php } ?>
					<?php if ( isset($h5_font['font-size']) && $h5_font['font-size'] ) { ?>
						font-size: <?php echo esc_html( $h5_font['font-size'] ) ?>;
					<?php } ?>
					<?php if ( isset($h5_font['line-height']) && $h5_font['line-height'] ) { ?>
						line-height: <?php echo esc_html( $h5_font['line-height'] ) ?>;
					<?php } ?>
					<?php if ( isset($h5_font['color']) && $h5_font['color'] ) { ?>
						color: <?php echo esc_html( $h5_font['color'] ) ?>;
					<?php } ?>
				}
			<?php endif; ?>

			/* H6 */
			<?php
				$h6_font = denso_get_config('h6_font');
				if ( !empty($h6_font) ) :
			?>
				/* seting background main */
				h6
				{
					<?php if ( isset($h6_font['font-family']) && $h6_font['font-family'] ) { ?>
						font-family: <?php echo esc_html( $h6_font['font-family'] ) ?>;
					<?php } ?>
					<?php if ( isset($h6_font['font-weight']) && $h6_font['font-weight'] ) { ?>
						font-weight: <?php echo esc_html( $h6_font['font-weight'] ) ?>;
					<?php } ?>
					<?php if ( isset($h6_font['font-style']) && $h6_font['font-style'] ) { ?>
						font-style: <?php echo esc_html( $h6_font['font-style'] ) ?>;
					<?php } ?>
					<?php if ( isset($h6_font['font-size']) && $h6_font['font-size'] ) { ?>
						font-size: <?php echo esc_html( $h6_font['font-size'] ) ?>;
					<?php } ?>
					<?php if ( isset($h6_font['line-height']) && $h6_font['line-height'] ) { ?>
						line-height: <?php echo esc_html( $h6_font['line-height'] ) ?>;
					<?php } ?>
					<?php if ( isset($h6_font['color']) && $h6_font['color'] ) { ?>
						color: <?php echo esc_html( $h6_font['color'] ) ?>;
					<?php } ?>
				}
			<?php endif; ?>

			/* Custom CSS */
			<?php if ( denso_get_config('custom_css') != "" ) : ?>
				<?php echo denso_get_config('custom_css') ?>
			<?php endif; ?>

		</style>

	<?php
		$content = ob_get_clean();
		$content = str_replace(array("\r\n", "\r"), "\n", $content);
		$lines = explode("\n", $content);
		$new_lines = array();
		foreach ($lines as $i => $line) {
			if (!empty($line)) {
				$new_lines[] = trim($line);
			}
		}
		
		echo implode($new_lines);
	}
}

?>
<?php add_action( 'wp_head', 'denso_custom_styles', 99 ); ?>