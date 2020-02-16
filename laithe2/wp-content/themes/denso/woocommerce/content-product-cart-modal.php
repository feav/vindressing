<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce;
?>

<div class="row popup-cart">

	<?php if ( sizeof( WC()->cart->get_cart() ) > 0 ) : ?>

		<?php
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
				if ( $product_id == $current_product_id ) {
					$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					
					if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {

						$product_name  = apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key );
						$thumbnail     = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
						$product_price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );

						?>
						<div class="col-md-6">
							<h2 class="title-add"><?php esc_html_e('Product successfully added to your shopping cart', 'denso'); ?></h2>
							<div class="media widget-product">
								<a href="<?php echo get_permalink( $product_id ); ?>" class="image pull-left">
									<?php echo trim($thumbnail); ?>
								</a>
								<div class="cart-main-content media-body">
									
									<h3 class="name">
										<a href="<?php echo get_permalink( $product_id ); ?>">
											<?php echo trim($product_name); ?>
										</a>
									</h3>
									<p class="cart-item">
										<?php echo wc_get_formatted_cart_item_data( $cart_item ); ?>
										<?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); ?>
									</p>

								</div>
							</div>
						</div>
						<div class="col-md-6">
							<h2 class="title-count">
								<?php echo sprintf(_n('There is %d item in your cart', 'There are %d items in your cart', $woocommerce->cart->cart_contents_count, 'denso'), $woocommerce->cart->cart_contents_count);?>
							</h2>
							<div>
								<div class="total"><strong><?php esc_html_e( 'Subtotal', 'denso' ); ?>:</strong> <?php echo WC()->cart->get_cart_subtotal(); ?></div>
								<?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>

								<div class="gr-buttons clearfix ">
									<a href="<?php echo wc_get_cart_url(); ?>" class="btn btn-primary btn-outline wc-forward"><?php esc_html_e( 'View Cart', 'denso' ); ?></a>
									<a href="<?php echo wc_get_checkout_url(); ?>" class="btn btn-primary btn-outline pull-right checkout wc-forward"><?php esc_html_e( 'Checkout', 'denso' ); ?></a>
								</div>
							</div>
						</div>
						<?php
					}
					break;
				}
			}
		?>
		

	<?php else : ?>

		<div class="empty"><?php esc_html_e( 'No products in the cart.', 'denso' ); ?></div>

	<?php endif; ?>

</div>