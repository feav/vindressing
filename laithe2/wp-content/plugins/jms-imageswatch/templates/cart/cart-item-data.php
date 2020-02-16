<?php
/**
 * The template for displaying product widget entries.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-widget-product.php.
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.2
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<dl class="variation">
    <?php foreach ( $item_data as $data ) : ?>
        <dt class="<?php echo sanitize_html_class( 'variation-' . $data['key'] ); ?>"><?php echo wp_kses_post( $data['key'] ); ?>:</dt>
        <dd class="<?php echo sanitize_html_class( 'variation-' . $data['key'] ); ?>">
            <?php if(isset($data['image']) && $data['image'] != ''): ?>
                <img src="<?php echo esc_url($data['image']);?>" class="img-openswatch" />
            <?php else: ?>
                <?php echo wp_kses_post( wpautop( $data['display'] ) ); ?>
            <?php endif; ?>

        </dd>
    <?php endforeach; ?>
</dl>
