<?php
/**
 * Single Product Up-Sells
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $woocommerce_loop;

$per_page = denso_get_config('number_product_releated', 4);

$upsells = $product->get_upsell_ids();

if ( sizeof( $upsells ) == 0 ) {
	return;
}

$meta_query = WC()->query->get_meta_query();

$args = array(
	'post_type'           => 'product',
	'ignore_sticky_posts' => 1,
	'no_found_rows'       => 1,
	'posts_per_page'      => $posts_per_page,
	'orderby'             => $orderby,
	'post__in'            => $upsells,
	'post__not_in'        => array( $product->get_id() ),
	'meta_query'          => $meta_query
);

$products = new WP_Query( $args );

$woocommerce_loop['columns'] = denso_get_config('releated_product_columns', 4);

if ( $products->have_posts() ) : ?>

	<div class="upsells widget products">
	<h2 class="widget-title "><span><?php esc_html_e( 'You may also like&hellip;', 'denso' ) ?></span></h2>
		<?php wc_get_template( 'layout-products/carousel.php',array( 'loop'=>$products,'columns_count'=> $woocommerce_loop['columns'], 'posts_per_page'=> $products->post_count ) ); ?>
	</div>

<?php endif;

wp_reset_postdata();
