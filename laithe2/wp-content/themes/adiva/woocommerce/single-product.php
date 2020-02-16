<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
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

// Get page options
$options = get_post_meta( get_the_ID(), '_custom_single_product_options', true );

// Get product single style
$style = ( is_array( $options ) && $options['wc-single-product-style'] ) ? $options['wc-single-product-style'] : '1';
$single_background = ( is_array( $options ) && $options['wc-enable-background'] ) ? $options['wc-enable-background'] : '';

// Render columns number
$smart_sidebar   = adiva_get_option( 'smart-sidebar', 0 );
$single_sidebar  = adiva_get_option( 'single-product-sidebar', 'no' );

if ( isset($_GET['sidebar']) && $_GET['sidebar'] != '' ) {
	$single_sidebar = $_GET['sidebar'];
}

$sidebar_class = $content_class = $layout_classes = '';

if ( $single_sidebar == 'left' && is_active_sidebar( 'woocommerce-single-product-sidebar' ) ) {
	$content_class = 'col-lg-9 col-md-9 col-sm-8 col-xs-12';
	$sidebar_class = 'col-lg-3 col-md-3 col-sm-4 col-xs-12';
	$layout_classes = 'left-sidebar';
} elseif ( $single_sidebar == 'right' && is_active_sidebar( 'woocommerce-single-product-sidebar' ) ) {
	$content_class = 'col-lg-9 col-md-9 col-sm-8 col-xs-12';
	$sidebar_class = 'col-lg-3 col-md-3 col-sm-4 col-xs-12';
} elseif ( $single_sidebar == 'no' || ! is_active_sidebar( 'woocommerce-single-product-sidebar' ) ) {
	$content_class = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';
	$sidebar_class = '';
}

if ( isset($smart_sidebar) && $smart_sidebar == 1 ) {
	$sidebar_class .= ' smart-sidebar';
}

// Get page options
$options = get_post_meta( get_the_ID(), '_custom_single_product_options', true );

get_header( 'shop' );

/**
 * woocommerce_before_main_content hook.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 */
do_action( 'woocommerce_before_main_content' );
?>
<?php if( is_array( $options ) && $options['wc-enable-background'] == 1 ) : ?>
	<div class="single-product-container mb_90">
<?php else : ?>
	<div class="single-product-container container mt_100 mb_90">
<?php endif; ?>
	<div class="row <?php echo esc_attr($layout_classes); ?>">
		<div id="main-content" class="<?php echo esc_attr( $content_class ); ?>">
			<?php while ( have_posts() ) : the_post(); ?>
				<?php wc_get_template_part( 'content', 'single-product' ); ?>
			<?php endwhile; // end of the loop. ?>
		</div>

		<?php if ( $single_sidebar != 'no' && is_active_sidebar( 'woocommerce-single-product-sidebar' ) ) : ?>
			<div id="main-sidebar" class="<?php echo esc_attr( $sidebar_class ); ?>">
				<?php dynamic_sidebar( 'woocommerce-single-product-sidebar' ); ?>
			</div>
		<?php endif; ?>
	</div>
</div>

<?php
	/**
	 * woocommerce_after_main_content hook.
	 *
	 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
	 */
	do_action( 'woocommerce_after_main_content' );
?>
<?php get_footer( 'shop' ); ?>
