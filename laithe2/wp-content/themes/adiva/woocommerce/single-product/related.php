<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
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
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$related_title = adiva_get_option( 'related-product-title', 'RELATED PRODUCTS' );
$related_desc  = adiva_get_option( 'related-product-desc', 'Includes products updated are similar or are same of quality' );

if ( $related_products ) : ?>

	<div class="related-product">
		<?php if ( !empty( $related_title ) ): ?>
			<div class="addon-title text-center title-color-default title-size-medium">
				<div class="line-wrap">
					<span class="left-line"></span>
					<h3 class="title"><?php echo esc_html( $related_title ); ?></h3>
					<span class="right-line"></span>
				</div>
				<?php if ( !empty( $related_desc ) ): ?>
					<div class="description"><?php echo esc_html( $related_desc ); ?></div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<div class="product-related-carousel owl-theme owl-carousel">
			<?php foreach ( $related_products as $related_product ) : ?>
				<?php
				 	$post_object = get_post( $related_product->get_id() );
					setup_postdata( $GLOBALS['post'] =& $post_object );
					wc_get_template_part( 'content', 'product' ); ?>
			<?php endforeach; ?>
		</div>
		<?php wp_add_inline_script( 'adiva-theme', adiva_related_product_carousel_js(), 'after' ); ?>
	</div>

<?php endif;

wp_reset_postdata();
