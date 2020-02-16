<?php
/**
 * Cross-sells
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cross-sells.php.
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

if ( $cross_sells ) : ?>
	<div class="cross-sells">
        <div class="addon-title text-center title-color-default title-size-medium">
            <div class="line-wrap">
                <span class="left-line"></span>
                <h3 class="title"><?php esc_html_e( 'You may be interested in&hellip;', 'woocommerce' ) ?></h3>
                <span class="right-line"></span>
            </div>
        </div>

        <div class="cross-sell-carousel owl-theme owl-carousel">
            <?php foreach ( $cross_sells as $cross_sell ) : ?>
                <?php
				 	$post_object = get_post( $cross_sell->get_id() );

					setup_postdata( $GLOBALS['post'] =& $post_object );

					wc_get_template_part( 'content', 'product' ); ?>

            <?php endforeach; ?>
        </div>
	</div>
	<?php wp_add_inline_script( 'adiva-theme', adiva_cross_sell_product_carousel_js(), 'after' ); ?>
<?php endif;

wp_reset_postdata();
