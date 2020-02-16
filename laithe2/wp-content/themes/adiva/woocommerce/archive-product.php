<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

global $paged, $wp_query, $woocommerce_loop;

$sidebar_class = $content_class = $layout_classes = $shop_container_class = '';

$smart_sidebar   = adiva_get_option( 'smart-sidebar', 0 );
$shop_display    = get_option( 'woocommerce_shop_page_display' );
$shop_layout     = adiva_get_option( 'wc-shop-layout', 'no' );
$shop_fullwidth  = adiva_get_option( 'wc-shop-fullwidth', 0 );
$pagination_type = adiva_get_option( 'wc-pagination-type', 'number' );
$subcat_column   = adiva_get_option( 'wc-sub-category-column', 4 );

// DEMO
if ( isset($_GET['fullwidth']) && $_GET['fullwidth'] != '' ) {
	$shop_fullwidth = $_GET['fullwidth'];
}

if ( isset($_GET['pagination']) && $_GET['pagination'] != '' ) {
	$pagination_type = $_GET['pagination'];
}

if ( isset($_GET['sidebar']) && $_GET['sidebar'] != '' ) {
	$shop_layout = $_GET['sidebar'];
}

if ( $shop_fullwidth == 0 ) {
	$shop_container_class = 'container';
} else {
	$shop_container_class = 'fullwidth';
}

if ( $shop_layout == 'left' && is_active_sidebar( 'shop-page' ) ) {
	$sidebar_class = 'col-md-3 col-sm-4 col-xs-12';
	$content_class = 'col-md-9 col-sm-8 col-xs-12 with-sidebar';
	$layout_classes = 'left-sidebar';
} elseif ( $shop_layout == 'right' && is_active_sidebar( 'shop-page' ) ) {
	$sidebar_class = 'col-md-3 col-sm-4 col-xs-12';
	$content_class = 'col-md-9 col-sm-8 col-xs-12 with-sidebar';
} elseif ( $shop_layout == 'no' || !is_active_sidebar( 'shop-page' ) ) {
	$sidebar_class = '';
	$content_class = 'col-md-12 col-sm-12 col-xs-12';
}

if ( isset($smart_sidebar) && $smart_sidebar == 1 ) {
	$sidebar_class .= ' smart-sidebar';
}

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action( 'woocommerce_before_main_content' );

?>
	<div class="shop-container <?php echo esc_attr( $shop_container_class ); ?> pt_93 <?php if ( $shop_display == 'subcategories' ) { echo 'pb_60'; } else { echo 'pb_100'; } ?>">
		<div class="row <?php echo esc_attr($layout_classes); ?>">

			<div id="main-content" class="<?php echo esc_attr( $content_class ); ?>">
				<header class="woocommerce-products-header">
					<?php
					/**
					 * Hook: woocommerce_archive_description.
					 *
					 * @hooked woocommerce_taxonomy_archive_description - 10
					 * @hooked woocommerce_product_archive_description - 10
					 */
					do_action( 'woocommerce_archive_description' );
					?>
				</header>
				<?php if ( have_posts() ) : ?>
					<?php if ( $shop_display != 'subcategories' ) adiva_woocommerce_shop_action(); ?>
					<?php
					/**
					 * Hook: woocommerce_before_shop_loop.
					 *
					 * @hooked wc_print_notices - 10
					 * @hooked woocommerce_result_count - 20
					 * @hooked woocommerce_catalog_ordering - 30
					 */
					do_action( 'woocommerce_before_shop_loop' ); ?>

					<?php woocommerce_product_loop_start(); ?>

						<?php
						if ( wc_get_loop_prop( 'total' ) ) {
							while ( have_posts() ) {
								the_post();

								/**
								 * Hook: woocommerce_shop_loop.
								 *
								 * @hooked WC_Structured_Data::generate_product_data() - 10
								 */
								do_action( 'woocommerce_shop_loop' );

								wc_get_template_part( 'content', 'product' );
							}
						}
						?>
					<?php woocommerce_product_loop_end(); ?>

					<?php if ( isset($pagination_type) && $pagination_type == 'number' ) : ?>
						<?php if ( $shop_display != 'subcategories' ) : ?>
							<div class="shop-action-bottom pt_20 clearfix">
								<?php
									do_action('adiva_woocommerce_result_count');

									/**
									 * woocommerce_after_shop_loop hook.
									 *
									 * @hooked woocommerce_pagination - 10
									 */
									do_action( 'woocommerce_after_shop_loop' );
								?>
							</div>
						<?php endif; ?>
					<?php else : ?>
						<?php
						/**
						 * Hook: woocommerce_after_shop_loop.
						 *
						 * @hooked woocommerce_pagination - 10
						 */
						do_action( 'woocommerce_after_shop_loop' );
						?>
					<?php endif; ?>

				<?php else : ?>
					<?php
						/**
						 * woocommerce_no_products_found hook.
						 *
						 * @hooked wc_no_products_found - 10
						 */
						do_action( 'woocommerce_no_products_found' );
					?>

				<?php endif; ?>

			</div>
			<!-- end content -->

			<?php if ( isset( $shop_layout ) && $shop_layout != 'no' && is_active_sidebar( 'shop-page' ) ) : ?>
				<div id="main-sidebar" class="<?php echo esc_attr( $sidebar_class ); ?>">
					<?php
						/**
						 * woocommerce_sidebar hook.
						 *
						 * @hooked woocommerce_get_sidebar - 10
						 */
						do_action( 'woocommerce_sidebar' );
					?>
				</div>
			<?php endif; ?>

		</div>
	</div>
<?php
/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' );
?>

<?php get_footer( 'shop' ); ?>
