<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
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

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * woocommerce_before_single_product hook.
 *
 * @hooked wc_print_notices - 10
 */

do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
 	echo get_the_password_form();
 	return;
}


/* Prev/next product buttons */
$thumbnail = $single_background = $style = '';

$product_navigation = adiva_get_option('wc-single-nagivation', 1);

if ( isset($product_navigation) && $product_navigation == 1 ) {
	$prevPost = get_previous_post();
	if($prevPost) {
		$thumbnail = get_the_post_thumbnail($prevPost->ID , 'shop_thumbnail');
	}

	echo '<span id="next-product" class="hidden-sm hidden-xs">';
		previous_post_link( '%link', '<span></span>'. $thumbnail );
	echo '</span>';

	$nextPost = get_next_post();
	if($nextPost) {
		$thumbnail = get_the_post_thumbnail($nextPost->ID , 'shop_thumbnail');
	}

	echo '<span id="prev-product" class="hidden-sm hidden-xs">';
	next_post_link( '%link', '<span></span>' . $thumbnail );
	echo '</span>';
}

$tabs_layout = adiva_get_option('product-tab-layout', 'tabs');

if ( isset( $_GET['woo-tab'] ) && $_GET['woo-tab'] != '' ) {
	$tabs_layout = $_GET['woo-tab'];
}

// Get page options
$options = get_post_meta( get_the_ID(), '_custom_single_product_options', true );

// Get product single style
$style             = ( is_array( $options ) && $options['wc-single-product-style'] ) ? $options['wc-single-product-style'] : '1';
$single_background = ( is_array( $options ) && $options['wc-enable-background'] ) ? 'product-detail-bg pt_100 pb_100' : '';
?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class(); ?>>
	<div class="wc-single-product-<?php echo esc_attr( $style ); ?> wc-single-product <?php echo esc_attr($single_background); ?>">
		<?php if( is_array( $options ) && $options['wc-enable-background'] == 1 ) : ?>
			<div class="container">
		<?php endif; ?>
			<div class="row">
				<?php wc_get_template( 'extras/single/layout-' . esc_attr( $style ) . '.php' ); ?>
			</div>
		<?php if( is_array( $options ) && $options['wc-enable-background'] == 1 ) : ?>
			</div>
		<?php endif; ?>
	</div>

	<div class="product-detail-information tabs-<?php echo esc_attr( $tabs_layout ); ?> container">
		<?php
		remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
		woocommerce_output_product_data_tabs();
		?>
	</div>
	<div class="other-products">
		<?php if( is_array( $options ) && $options['wc-enable-background'] == 1 ) : ?>
			<div class="container">
		<?php endif; ?>
		<?php
			/**
			 * woocommerce_after_single_product_summary hook.
			 *
			 * @hooked woocommerce_output_product_data_tabs - 10
			 * @hooked woocommerce_upsell_display - 15
			 * @hooked woocommerce_output_related_products - 20
			 */
			do_action( 'woocommerce_after_single_product_summary' );
		?>
		<?php if( is_array( $options ) && $options['wc-enable-background'] == 1 ) : ?>
		</div>
		<?php endif; ?>
	</div>
</div>
