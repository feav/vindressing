<?php
$product_hover_preset = adiva_get_option('wc-product-hover-presets', '2e2e2e');
?>
<div class="product-box product-box-style-2<?php echo ' product-preset-' . esc_attr( $product_hover_preset ); ?>">
	<div class="product-thumb pr oh">
		<?php
		/**
		 * woocommerce_before_shop_loop_item_title hook.
		 *
		 * @hooked woocommerce_template_loop_product_link_open - 5
		 * @hooked woocommerce_show_product_loop_sale_flash - 10
		 * @hooked woocommerce_template_loop_product_thumbnail - 15
		 * @hooked adiva_second_product_thumbnail - 15
		 * @hooked woocommerce_template_loop_product_link_close - 20
		 */
		do_action( 'woocommerce_before_shop_loop_item_title' );
		?>
	</div>

	<div class="product-info pa-center">
		<?php
		adiva_product_categories();
		adiva_product_rating();
		?>

		<?php do_action( 'woocommerce_shop_loop_item_title' ); ?>

		<?php
		/**
		 * woocommerce_after_shop_loop_item_title hook.
		 *
		 * @hooked woocommerce_template_loop_price - 10
		 */
		do_action( 'woocommerce_after_shop_loop_item_title' );
		?>

	</div>

</div>
