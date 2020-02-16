<?php
/**
 * Product loop sale flash
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/sale-flash.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $product;

$onsale_style = adiva_get_option('wc-product-onsale', 'txt');

if ( $product->is_on_sale() ) : ?>
	<span class="badge pa tc dib">
		<?php

		if ( isset($onsale_style) && $onsale_style == 'pct' ) {
			$sale_percent = adiva_product_get_sale_percent( $product );

			if ( $sale_percent > 0 ) {
				echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale"><span class="onsale-before">-</span>' . $sale_percent . '<span class="onsale-after">%</span></span>', $post, $product );
			}
		} else {
			echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . esc_html__( 'Sale', 'adiva' ) . '</span>', $post, $product );
		}
		?>
	</span>
<?php endif; ?>
