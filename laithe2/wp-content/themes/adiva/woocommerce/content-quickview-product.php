<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $quickview;
$quickview = true;
?>
<div id="product-<?php the_ID(); ?>" class="product-quickview pr mfp-with-anim">
	<div class="wc-single-product-1 wc-single-product row">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 column-left">
			<div class="single-product-thumbnail pr clearfix outside">
				<div class="single-product-thumbnail-inner pr">
					<div class="p-thumb images thumbnail-slider" data-slick='{"slidesToShow": 1, "slidesToScroll": 1, "asNavFor": ".p-nav", "fade":true,}'>
						<?php
							if ( has_post_thumbnail() ) {
								$post_thumbnail_id = get_post_thumbnail_id( $post->ID );
								$full_size_image   = wp_get_attachment_image_src( $post_thumbnail_id, 'full' );
								$thumbnail_post    = get_post( $post_thumbnail_id );
								$image_title       = $thumbnail_post->post_content;

								$attributes = array(
									'title'                   => $image_title,
									'data-src'                => $full_size_image[0],
									'data-large_image'        => $full_size_image[0],
									'data-zoom-image'  		  => $full_size_image[0],
									'data-large_image_width'  => $full_size_image[1],
									'data-large_image_height' => $full_size_image[2],
								);

								$html = '<div class="p-item woocommerce-product-gallery__image' . $zoom . '">';
									$html .= '<a href="' . esc_url( $full_size_image[0] ) . '" class="zoom" data-rel="prettyPhoto[product-gallery]">';
									$html .= get_the_post_thumbnail( $post->ID, 'shop_single', $attributes );
								$html .= '</a></div>';

							} else {
								$html  = '<div class="woocommerce-product-gallery__image--placeholder">';
									$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src() ), esc_attr__( 'Awaiting product image', 'adiva' ) );
								$html .= '</div>';
							}

							echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, get_post_thumbnail_id( $post->ID ) );

							$attachment_ids = $product->get_gallery_image_ids();

							if ( $attachment_ids ) {
								foreach ( $attachment_ids as $attachment_id ) {
									$full_size_image  = wp_get_attachment_image_src( $attachment_id, 'full' );
									$thumbnail        = wp_get_attachment_image_src( $attachment_id, 'shop_thumbnail' );
									$thumbnail_post   = get_post( $attachment_id );
									$image_title      = $thumbnail_post->post_content;

									$attributes = array(
										'title'                   => $image_title,
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
				</div>
			</div>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 column-right">
			<div class="summary entry-summary">

				<?php
					remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
					remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
					add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 15);
					add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 25);

					$product_sharing = adiva_get_option('wc-single-sharing', 1);

					if ( isset($product_sharing) && $product_sharing ) {
						add_action( 'woocommerce_single_product_summary', 'adiva_sharing_product', 50 );
					}

					/**
					 * woocommerce_single_product_summary hook.
					 *
					 * @hooked woocommerce_template_single_title - 5
					 * @hooked woocommerce_template_single_rating - 10
					 * @hooked woocommerce_template_single_meta - 15
					 * @hooked woocommerce_template_single_excerpt - 20
					 * @hooked woocommerce_template_single_price - 25
					 * @hooked woocommerce_template_single_add_to_cart - 30
					 * @hooked woocommerce_template_single_sharing - 50
					 * @hooked WC_Structured_Data::generate_product_data() - 60
					 */
					do_action( 'woocommerce_single_product_summary' );
				?>

			</div><!-- .summary -->
		</div>
	</div>
</div>
<!-- .product-quickview -->
