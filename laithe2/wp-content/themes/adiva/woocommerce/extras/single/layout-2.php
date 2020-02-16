<?php
// Get page options
$options = get_post_meta( get_the_ID(), '_custom_single_product_options', true );

// Get product single style
$gallery_style = ( is_array( $options ) && $options['wc-product-gallery-style'] ) ? $options['wc-product-gallery-style'] : '1';
?>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 column-left product-gallery-style-<?php echo esc_attr($gallery_style); ?>">
	<?php
		/**
		 * woocommerce_before_single_product_summary hook.
		 *
		 * @hooked woocommerce_show_product_sale_flash - 10
		 * @hooked woocommerce_show_product_images - 20
		 */
		do_action( 'woocommerce_before_single_product_summary' );
	?>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 column-right<?php if( $gallery_style != '' ) echo ' smart-sidebar'; ?>">
	<div class="summary entry-summary">

		<?php
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
			add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 15);
			add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 25);

			/**
			 * woocommerce_single_product_summary hook.
			 *
			 * @hooked woocommerce_template_single_title - 5
			 * @hooked woocommerce_template_single_rating - 10
			 * @hooked woocommerce_template_single_meta - 15
			 * @hooked woocommerce_template_single_excerpt - 20
			 * @hooked woocommerce_template_single_price - 25
			 * @hooked woocommerce_template_single_add_to_cart - 30
			 * @hooked woocommerce_template_single_sharing - 50
			 * @hooked WC_Structured_Data::generate_product_data() - 60
			 */
			do_action( 'woocommerce_single_product_summary' );
		?>

	</div><!-- .summary -->
</div>
