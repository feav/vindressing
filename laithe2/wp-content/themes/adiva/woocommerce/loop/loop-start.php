<?php
/**
 * Product Loop Start
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/loop-start.php.
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
 * @version     3.3.0
 */

global $woocommerce_loop;

$data = $sizer = '';

$classes = array('wc-product-masonry');

$product_columns = adiva_get_option( 'wc-product-column', 4 );
$product_design  = adiva_get_option( 'wc-archive-style', 'grid' );
$product_spacing = adiva_get_option( 'wc-gutter-space', 40 );
$pagination_type = adiva_get_option( 'wc-pagination-type', 'number' );

// DEMO
if ( isset($_GET['layout']) && $_GET['layout'] != '' ) {
	$product_design = $_GET['layout'];
}

if ( isset($_GET['per_row']) && $_GET['per_row'] != '' ) {
	$product_columns = $_GET['per_row'];
}

if ( isset($_GET['gutter']) && $_GET['gutter'] != '' ) {
	$product_spacing = $_GET['gutter'];
}

// shop layout style
if ( ! empty( $woocommerce_loop['product_design'] ) ) {
	if( $woocommerce_loop['product_design'] == 'grid' ) {
		$data = 'data-masonry=\'{"selector":".product-design-grid","layoutMode":"fitRows","columnWidth":".product-design-grid"}\'';
	} elseif ( $woocommerce_loop['product_design'] == 'masonry' ) {
		$data = 'data-masonry=\'{"selector":".product-design-masonry","layoutMode":"masonry","columnWidth":".product-design-masonry"}\'';
	} else {
		$data = 'data-masonry=\'{"selector":".product-design-metro","layoutMode":"masonry","columnWidth":".product-design-metro"}\'';
	}
} else {
	if( $product_design == 'grid' ) {
		$data = 'data-masonry=\'{"selector":".product-design-grid","layoutMode":"fitRows","columnWidth":".product-design-grid"}\'';
	} elseif ( $product_design == 'masonry' ) {
		$data = 'data-masonry=\'{"selector":".product-design-masonry","layoutMode":"masonry","columnWidth":".product-design-masonry"}\'';
	} else {
		$data = 'data-masonry=\'{"selector":".product-design-metro","layoutMode":"masonry","columnWidth":".product-design-metro"}\'';
	}
}

if ( isset($woocommerce_loop['items_spacing']) && !empty( $woocommerce_loop['items_spacing'] ) ) {
    $classes[] = 'product-spacing-' . $woocommerce_loop['items_spacing'];
} else {
    $classes[] = 'product-spacing-' . $product_spacing;
}

$is_shortcode = isset( $woocommerce_loop['is_shortcode'] ) ? $woocommerce_loop['is_shortcode'] : false;
if ( WC()->version < '3.3.0' ) {
	// Store column count for displaying the grid
	if ( !empty( $woocommerce_loop['columns'] ) ) {
		$product_columns = $woocommerce_loop['columns'];
	} else {
		$woocommerce_loop['columns'] = ( adiva_get_option('wc-per-row-columns-selector' ) ) ? apply_filters( 'loop_shop_columns', adiva_get_products_columns_per_row() ) : $shop_column;
	}

	$classes[] = 'grid-columns-' . $woocommerce_loop['columns'];

} else{
	if ( !$is_shortcode && !wc_get_loop_prop( 'is_shortcode' ) ) {
		$value = ( adiva_get_option('wc-per-row-columns-selector' ) ) ? apply_filters( 'loop_shop_columns', adiva_get_products_columns_per_row() ) : $product_columns;
		wc_set_loop_prop( 'columns', $value );
	}
	$classes[] = 'grid-columns-' . wc_get_loop_prop( 'columns' );
}


?>
<div class="product-layout-wrapper">
	<div class="wc-loading w-30"></div>
	<div class="products product-layout clearfix <?php echo implode(' ', $classes); ?>" <?php echo wp_kses_post( $data ); ?>>
