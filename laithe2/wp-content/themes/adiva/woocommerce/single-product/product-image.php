<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.1
 */


defined( 'ABSPATH' ) || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
	return;
}

global $post, $product;

$classes = array();

$attachment_count = count( $product->get_gallery_image_ids() );

// Get page options
$options = get_post_meta( get_the_ID(), '_custom_single_product_options', true );

// Get product single style
$style = ( is_array( $options ) && $options['wc-single-product-style'] ) ? $options['wc-single-product-style'] : '1';

// Get product thumbnail position
$thumb_position = ( is_array( $options ) && $options['wc-single-product-style'] == 1 && $options['wc-thumbnail-position'] ) ? $options['wc-thumbnail-position'] : 'left';

$classes[] = 'single-product-thumbnail pr clearfix';
$p_thumb[] = 'p-thumb images';

if ( $thumb_position && $style == 1 ) {
	$classes[] = $thumb_position;
}

if ( $attachment_count == 0 ) {
	$classes[] = 'no-nav';
}

$attr = '';
if ( $style == 1 ) {
	$attr = 'data-slick=\'{"slidesToShow": 1, "slidesToScroll": 1, "asNavFor": ".p-nav", "fade":true}\'';
	$p_thumb[] = 'thumbnail-slider';
} elseif ( $style == 2 ) {
	$p_thumb[] = 'jms-masonry';

	if ( $attachment_count < 1 ) {
		$p_thumb[] = 'columns-full';
	}
} else {
	if ( wp_is_mobile() ) {
		$attr = 'data-slick=\'{"responsive":[{"breakpoint": 767,"settings":{"slidesToShow": 1, "vertical": false, "arrows": true}}]}\'';
		$p_thumb[] = 'thumbnail-slider';
	} else {
		$attr = '';
	}
}

$zoom = '';
$zoom_image = adiva_get_option('wc-product-zoom-image', 1);
if ( isset($zoom_image) && $zoom_image ) {
	$zoom = ' image-zoom';
}

if ( isset( $_GET['zoom'] ) && $_GET['zoom'] == 1 ) {
	$zoom = ' image-zoom';
}

?>

<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
	<div class="single-product-thumbnail-inner pr">
		<div class="<?php echo esc_attr( implode( ' ', $p_thumb ) ); ?>" <?php echo wp_kses_post( $attr ); ?>>

			<?php
				if ( has_post_thumbnail() ) {
					$post_thumbnail_id = $product->get_image_id();
					$full_size_image   = wp_get_attachment_image_src( $post_thumbnail_id, 'full' );

					$attributes = array(
						'data-src'                => $full_size_image[0],
						'data-large_image'        => $full_size_image[0],
						'data-zoom-image'  		  => $full_size_image[0],
						'data-large_image_width'  => $full_size_image[1],
						'data-large_image_height' => $full_size_image[2],
					);
					$html = '';
					$html = '<div class="p-item woocommerce-product-gallery__image' . $zoom . '">';
						$html .= '<a href="' . esc_url( $full_size_image[0] ) . '" class="zoom" data-rel="prettyPhoto[product-gallery]">';
						$html .= get_the_post_thumbnail( $post->ID, 'shop_single', $attributes );
						$html .= '</a>';
					$html .= '</div>';
				} else {
					$html  = '<div class="p-item woocommerce-product-gallery__image--placeholder">';
						$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src() ), esc_attr__( 'Awaiting product image', 'adiva' ) );
					$html .= '</div>';
				}

				echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id );

				$attachment_ids = $product->get_gallery_image_ids();

				if ( $attachment_ids ) {
					foreach ( $attachment_ids as $attachment_id ) {
						$full_size_image  = wp_get_attachment_image_src( $attachment_id, 'full' );
						$thumbnail        = wp_get_attachment_image_src( $attachment_id, 'shop_thumbnail' );
						$thumbnail_post   = get_post( $attachment_id );

						$attributes = array(
							'title'                   => get_post_field( 'post_title', $post_thumbnail_id ),
							'data-src'                => $full_size_image[0],
							'data-large_image'        => $full_size_image[0],
							'data-large_image_width'  => $full_size_image[1],
							'data-large_image_height' => $full_size_image[2],
						);

						$html = '<div class="p-item woocommerce-product-gallery__image' . $zoom . '">';
							$html .= '<a href="' . esc_url( $full_size_image[0] ) . '" class="zoom" data-rel="prettyPhoto[product-gallery]">';
								$html .= wp_get_attachment_image( $attachment_id, 'shop_single', false, $attributes );
							$html .= '</a>';
						$html .= '</div>';

						echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $attachment_id );
					}
				}
			?>
		</div>


		<?php if ( is_array( $options ) && $options['wc-product-video-url'] != '' ) : ?>
			<div class="wc-single-video">
				<a href="<?php echo esc_url( $options['wc-product-video-url'] ); ?>" class="tc wc-popup-url"><?php echo esc_html__( 'Watch video', 'adiva' ); ?></a>
			</div>
		<?php endif; ?>

	</div>

	<?php
		if ( $style == 1 && $thumb_position != 'outside' ) {
			do_action( 'woocommerce_product_thumbnails' );
		}
	?>
</div>
