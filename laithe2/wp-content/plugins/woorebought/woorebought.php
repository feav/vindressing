<?php
/*
* Plugin Name: WooCommerce Recently Bought
* Plugin URI: http://joommasters.com
* Description: Show Recently Bought Product for WooCommerce
* Version: 1.1
* Author: Joommasters
* Author URI: http://joommasters.com
* License:     GPL2
* License URI: https://www.gnu.org/licenses/gpl-2.0.html
* Text Domain: woorebought
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define('JMS_WOOREBOUGHT_PLUGIN_PATH' , plugin_dir_path(__FILE__));
define('JMS_WOOREBOUGHT_URL', plugin_dir_url(__FILE__));
define('JMS_WOOREBOUGHT_CSS_URL', JMS_WOOREBOUGHT_URL . 'css/');
define('JMS_WOOREBOUGHT_JS_URL', JMS_WOOREBOUGHT_URL . 'js/');
define('JMS_WOOREBOUGHT_IMAGES_URL', JMS_WOOREBOUGHT_URL . 'images/');
define('JMS_WOOREBOUGHT_ADMIN_PATH' , JMS_WOOREBOUGHT_PLUGIN_PATH . 'admin/');
define( 'JMS_WOOREBOUGHT_VERSION', '1.1' );
require 'admin/params.php';
require 'admin/admin.php';
require 'front/popup.php';
register_activation_hook( __FILE__, 'woorebought_activate' );
function woorebought_activate() {
	global $wp_version;
		if ( version_compare( $wp_version, "3.0", "<" ) ) {
			deactivate_plugins( basename( __FILE__ ) ); // Deactivate our plugin
			wp_die( "This plugin requires WordPress version 3.0 or higher." );
		}
		$json_data = '{"enable":"1","popup_show_trans":"slideInUp","popup_hide_trans":"fadeOut","popup_position":"bottom_left","image_position":"img_left","product_link":"1","popup_close_icon":"1","use_cache":"1","product_src":"latest_products","select_categories":[],"order_time_num":"500","order_time_type":"days","order_status":["wc-completed"],"select_products":[],"frame_time":"60","rebought_content":"Someone in {address} purchased a {product_link} {time_ago}","address_list":"New York City, New York, USA\r\nParis, France\r\nAlaska, USA\r\nLondon, England","loop":"1","init_delay":"5","display_time":"5","next_time":"25","total":"30","popup_bg":"#ffffff","popup_border_radius":"3","popup_text_cl":"#000000","popup_link_cl":"#000000","popup_time_cl":"#000000","popup_text_size":"13","popup_link_size":"15","popup_time_size":"11","custom_css":"","all_page":"1"}';
		if ( ! get_option( 'woorebought_params', '' ) ) {
			update_option( 'woorebought_params', json_decode( $json_data, true ) );
		}
}
register_deactivation_hook(__FILE__, 'woorebought_deactivation');
function woorebought_deactivation() {
	return true;
}
new WooReBought_Admin();
new WooReBought_Params();
new WooReBought_Popup();
