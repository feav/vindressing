<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product, $woocommerce_loop;

// Ensure visibility
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

$product_view        = adiva_get_option('wc-product-view', 'grid');
$product_design      = adiva_get_option('wc-archive-style', 'grid');
$product_style       = adiva_get_option('wc-product-style', 1);
$product_swatches    = adiva_get_option('wc-attribute-variation', 1);
$product_thumb_hover = adiva_get_option('wc-product-image-hover', 'no-effect');
$product_spacing     = adiva_get_option('wc-gutter-space', 40);

// Get product options
$options = get_post_meta( get_the_ID(), '_custom_wc_thumb_options', true );

// Classes array
$classes = array('product-item');

// DEMO
if ( isset($_GET['layout']) && $_GET['layout'] != '' ) {
	$product_design = $_GET['layout'];
}

if ( isset($_GET['style']) && $_GET['style'] != '' ) {
	$product_style = $_GET['style'];
}

if ( isset($_GET['gutter']) && $_GET['gutter'] != '' ) {
	$product_spacing = $_GET['gutter'];
}

// Product design (grid or masonry)
if ( ! empty( $woocommerce_loop['product_design'] ) ) {
	$classes[] = 'product-design-' . $woocommerce_loop['product_design'];

	if ( isset( $options['wc-thumbnail-size'] ) && $options['wc-thumbnail-size'] && $woocommerce_loop['product_design'] == 'metro' )
	 	$classes[] = 'item-metro';
} else {
	$classes[] = 'product-design-' . $product_design;

	if ( isset( $options['wc-thumbnail-size'] ) && $options['wc-thumbnail-size'] && $product_design == 'metro' )
		$classes[] = 'item-metro';
}

// Product box style
if ( ! empty( $woocommerce_loop['product_style'] ) ) {
	$classes[] = 'product-style-' . $woocommerce_loop['product_style'];
	$product_style = $woocommerce_loop['product_style'];
} else {
	$classes[] = 'product-style-' . $product_style;
}

// Product box spacing bottom
if ( ! empty($woocommerce_loop['items_spacing']) && $woocommerce_loop['product_style'] != '1' ) {
	$classes[] = 'mb_' . $woocommerce_loop['items_spacing'];
} elseif( $product_style != '1' && $product_spacing ) {
	$classes[] = 'mb_' . $product_spacing;
}

// Product swatches
if ( !isset($product_swatches) || $product_swatches == 0 ) {
	$classes[] = 'product-no-attribute';
}

//Product view mode
if (  ( function_exists('is_shop') && is_shop() ) || ( function_exists('is_product_category') && is_product_category() ) || ( function_exists('is_product_tag') ) && is_product_tag() ) {
	$current_view = adiva_get_shop_view();

	if( $current_view == 'list' || $product_view == 'list' ) {
		$product_style = 'list';
		$classes[] = 'product-list-item';
	}
}

// Product thumb hover
if ( $product_thumb_hover == 'second-image' ) {
	$classes[] = 'hover-image-load';
}
?>

<div <?php post_class( $classes ); ?>>
	<?php wc_get_template_part( 'content', 'product-' . $product_style ); ?>
</div>
