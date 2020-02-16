<?php
/**
 * Single Product Sale Flash
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/sale-flash.php.
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
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $product;

$classes = array();

// Get page options
$options = get_post_meta( get_the_ID(), '_custom_single_product_options', true );

// Get product single style
$style = ( is_array( $options ) && $options['wc-single-product-style'] ) ? $options['wc-single-product-style'] : '1';

$thumb_position = ( is_array( $options ) && $options['wc-single-product-style'] == 1 && $options['wc-thumbnail-position'] ) ? $options['wc-thumbnail-position'] : 'left';

if ( $thumb_position && $style == 1 ) {
	$classes[] = $thumb_position;
}

// Sale badge type
$onsale_style = adiva_get_option('wc-product-onsale', 'txt');

if ( $product->is_on_sale() || ! $product->is_in_stock() ) : ?>
	<span class="badge pa tc dib <?php echo 'position-' . implode( ' ', $classes ); ?>">
		<?php
		if ( $product->is_on_sale() && $product->is_in_stock() ) {
			if ( $onsale_style == 'txt' ) {
				echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . esc_html__( 'Sale', 'adiva' ) . '</span>', $post, $product );
			} else {
				$regular_price = $product->get_regular_price();
				$sale_price    = $product->get_sale_price();

				if ( $regular_price != '' ) {
					$sale = '-' . ceil( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 ) . '%';
				} else {
					$sale = esc_html__( 'Sale', 'adiva' );
				}
				echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . esc_html( $sale ) . '</span>', $post, $product );
			}
		}

		if ( ! $product->is_in_stock() ) {
			echo '<span class="sold-out">' . esc_html__( 'Sold Out', 'adiva' ) . '</span>';
		}
		?>
	</span>
<?php endif; ?>
