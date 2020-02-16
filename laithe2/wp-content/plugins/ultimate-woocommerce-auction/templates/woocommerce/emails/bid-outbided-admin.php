<?php/** * Admin notification when auction outbid by user. (HTML) * */// Exit if accessed directlyif ( !defined( 'ABSPATH' ) ) exit;?><?php do_action('woocommerce_email_header', $email_heading, $email); ?><?php$product = $email->object['product'];$auction_url = $email->object['url_product'];$auction_title = $product->get_title();$auction_bid_value = wc_price($product->get_woo_ua_current_bid());$thumb_image = $product->get_image( 'thumbnail' );?><p><?php printf( __( "Hi Admin,", 'woo_ua' )); ?></p><p><?php printf( __( "Auction outbided by user. Auction url <a href='%s'>%s</a>.", 	'woo_ua' ), $auction_url, $auction_title); ?></p><p><?php printf( __( "Here are the details : ", 'woo_ua' )); ?></p><table>     <tr>	 	 <td><?php echo __( 'Image', 'woo_ua' ); ?></td>	 <td><?php echo __( 'Product', 'woo_ua' ); ?></td>	 <td><?php echo __( 'Current bid', 'woo_ua' ); ?></td>		 </tr>    <tr>      <td><?php echo $thumb_image;?></td>	  <td><a href="<?php echo $auction_url ;?>"><?php echo $auction_title; ?></a></td>      <td><?php echo $auction_bid_value;  ?></td>    </tr></table><?php do_action('woocommerce_email_footer', $email);?>