<?php
/**
 * Single Product Thumbnails
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-thumbnails.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     3.5.1
 */

 defined( 'ABSPATH' ) || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
 	return;
}


global $post, $product, $woocommerce;

$attachment_ids   = $product->get_gallery_image_ids();
$attachment_count = count( $attachment_ids );

// Get page options
$options = get_post_meta( get_the_ID(), '_custom_single_product_options', true );

// Get product thumbnail position
$thumb_position = ( is_array( $options ) && $options['wc-single-product-style'] == 1 && $options['wc-thumbnail-position'] ) ? $options['wc-thumbnail-position'] : 'left';

if ( $thumb_position == 'outside' ) {
	$limit = $attachment_count + 1;
} else {
	$limit = 4;
}

if ( $attachment_ids && has_post_thumbnail() ) {
	?>
	<div class="p-nav oh thumbnail-slider<?php if ( $thumb_position == 'outside' ) echo ' p-nav-outside'; ?>" data-slick='{"slidesToShow": <?php echo (int) $limit; ?>,"slidesToScroll": 1,"asNavFor": ".p-thumb","arrows": false, "focusOnSelect": true, <?php if ( $thumb_position == 'left' || $thumb_position == 'right' ) echo '"vertical": true,'; ?> "responsive":[{"breakpoint": 991,"settings":{"slidesToShow": 4, "vertical":false}}]}'>
		<?php
			$image = get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_thumbnail' ), array(
				'title'	=> get_the_title( get_post_thumbnail_id() )
			) );

			echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<div>%s</div>', $image ), $post->ID );

			foreach ( $attachment_ids as $attachment_id ) {
				$full_size_image  = wp_get_attachment_image_src( $attachment_id, 'full' );
				$thumbnail        = wp_get_attachment_image_src( $attachment_id, 'shop_thumbnail' );

				$attributes = array(
					'data-src'                => $full_size_image[0],
					'data-large_image'        => $full_size_image[0],
					'data-large_image_width'  => $full_size_image[1],
					'data-large_image_height' => $full_size_image[2],
				);
				$html = '<div>' . wp_get_attachment_image( $attachment_id, 'shop_thumbnail', false, $attributes ) . '</div>';
                echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $attachment_id );
			}
		?>
	</div>
	<?php
}
