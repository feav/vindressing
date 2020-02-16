<?php
/**
 * Single Product Up-Sells
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/up-sells.php.
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

$upsell_title = adiva_get_option( 'upsell-product-title', 'YOU MAY ALSO LIKE...' );
$upsell_desc  = adiva_get_option( 'upsell-product-desc', 'Includes products updated are similar or are same of quality' );

if ( $upsells ) : ?>
	<div class="upsell-products">
		<?php if ( !empty( $upsell_title ) ): ?>
			<div class="addon-title text-center title-color-default title-size-medium">
				<div class="line-wrap">
					<span class="left-line"></span>
					<h3 class="title"><?php echo esc_html( $upsell_title ); ?></h3>
					<span class="right-line"></span>
				</div>
				<?php if ( !empty( $upsell_desc ) ): ?>
					<div class="description"><?php echo esc_html( $upsell_desc ); ?></div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<div class="upsell-product-carousel owl-theme owl-carousel">
			<?php foreach ( $upsells as $upsell ) : ?>
				<?php
				 	$post_object = get_post( $upsell->get_id() );
					setup_postdata( $GLOBALS['post'] =& $post_object );
					wc_get_template_part( 'content', 'product' ); ?>
			<?php endforeach; ?>
		</div>
		<?php wp_add_inline_script( 'adiva-theme', adiva_upsell_product_carousel_js(), 'after' ); ?>
	</div>
<?php endif;
wp_reset_postdata();
