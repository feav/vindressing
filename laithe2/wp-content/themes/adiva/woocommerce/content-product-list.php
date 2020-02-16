<?php
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );

add_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_open', 5);
add_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 4);
add_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 15);
add_action('woocommerce_before_shop_loop_item_title', 'adiva_second_product_thumbnail', 15);
add_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 20);
?>

<div class="product-box">
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

	<div class="product-list-info">
		<div class="product-list-info-top">
			<?php adiva_product_categories(); ?>
			<?php adiva_product_rating(); ?>

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
		<?php woocommerce_template_single_excerpt(); ?>

		<ul class="product-btn flex">
			<?php
			$catalog_mode = adiva_get_option( 'catalog-mode', 0 );

			if ( isset($_GET['catalog-mode']) && $_GET['catalog-mode'] == 1 ) {
		        $catalog_mode = 1;
		    }

			if ( !$catalog_mode ) : ?>
				<li><?php woocommerce_template_loop_add_to_cart(); ?></li>
			<?php endif; ?>

			<?php
				adiva_product_wishlist();
				adiva_product_quickview();
			?>
		</ul>

	</div>

</div>
