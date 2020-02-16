<?php
/**
 * Related Products
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $product, $woocommerce_loop;

if ( empty( $product ) || ! $product->exists() ) {
	return;
}

$item_style = 'inner';
$rows = 1;
$columns = denso_get_config('bought_viewed_product_columns', 4);

$per_page = denso_get_config('number_product_bought_viewed', 4);

$pids = denso_get_products_customer_also_viewed( $product->get_id() );

if ( empty($pids) || sizeof( $pids ) == 0 || !is_array($pids) ) return;

$args = apply_filters( 'woocommerce_related_products_args', array(
	'post_type'            => 'product',
	'ignore_sticky_posts'  => 1,
	'no_found_rows'        => 1,
	'posts_per_page'       => $per_page,
	'post__in'             => $pids,
	'orderby' => 'ID(ID, explode('.implode(',', $pids).'))'
) );

$products = new WP_Query( $args );

if ( $products->have_posts() ) : ?>

	<div class="viewedproducts products widget">
		<h3 class="widget-title"><span><?php esc_html_e( 'Customers Who Viewed This Item Also Viewed', 'denso' ); ?></span></h3>
		<?php wc_get_template( 'layout-products/carousel.php' , array( 'loop' => $products, 'columns' => $columns, 'rows' => $rows, 'product_item' => $item_style ) ); ?>
	</div>
<?php endif;
wp_reset_postdata();