<?php/** * Admin notification when auction outbid by user. (plain) * */if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directlyglobal $woocommerce;$product = $email->object['product'];$auction_url = $email->object['url_product'];$auction_title = $product->get_title();$auction_bid_value = wc_price($product->get_woo_ua_current_bid());echo $email_heading . "</br>";printf( __( "Hi Admin,", 'woo_ua' ));echo "</br>";printf( __( "Auction outbided by user. Auction url <a href='%s'>%s</a>.", 'woo_ua' ),	$auction_url, $auction_title);echo "</br>";printf( __( "Bid Value %s.", 'woo_ua' ),$auction_bid_value);echo "</br>";echo apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) );