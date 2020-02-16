<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $woocommerce_loop;
	
// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) ) {
	$woocommerce_loop['loop'] = 0;
}

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) ) {
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );
}

// Ensure visibility
if ( ! $product || ! $product->is_visible() ) {
	return;
}

// Extra post classes
$classes = array();
if($woocommerce_loop['columns'] == 5) {
	$columns = 'cus-5';
} elseif($woocommerce_loop['columns'] == 7) {
	$columns = 'cus-7';
} elseif($woocommerce_loop['columns'] == 8) {
	$columns = 'cus-8';
} else {
	$columns = 12/$woocommerce_loop['columns'];
}
$classes[] = 'col-lg-'.$columns.' col-md-'.$columns.' col-sm-6 col-xs-12 product';
?>
<div <?php post_class( $classes, $product->get_id() ); ?> >
	<?php $product_item = isset($product_item) ? $product_item : 'inner'; ?>
 	<?php wc_get_template_part( 'item-product/'.$product_item ); ?>
</div>