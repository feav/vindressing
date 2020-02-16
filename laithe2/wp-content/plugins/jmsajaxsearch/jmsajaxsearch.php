<?php
/*
* Plugin Name: Ajax Search Product
* Plugin URI: http://joommasters.com
* Description: Show Product Search
* Version: 1.1
* Author: JoomMasters
* Author URI: http://joommasters.com
* License:     GPL2
* License URI: https://www.gnu.org/licenses/gpl-2.0.html
* Text Domain: jmsajaxsearch
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define('JMS_AJAXSEARCH_PLUGIN_PATH' , plugin_dir_path(__FILE__));
define('JMS_AJAXSEARCH_URL', plugin_dir_url(__FILE__));
define('JMS_AJAXSEARCH_CSS_URL', JMS_AJAXSEARCH_URL . 'css/');
define('JMS_AJAXSEARCH_JS_URL', JMS_AJAXSEARCH_URL . 'js/');
define('JMS_AJAXSEARCH_ADMIN_PATH' , JMS_AJAXSEARCH_PLUGIN_PATH . 'admin/');
define('JMS_AJAXSEARCH_FRONT_PATH' , JMS_AJAXSEARCH_PLUGIN_PATH . 'front/');
define('JMS_AJAXSEARCH_VERSION', '1.1');

require 'admin/params.php';
require 'admin/admin.php';
require 'front/helper.php';
require 'front/widget.php';
require 'front/widget-no-categories.php';

register_activation_hook( __FILE__, 'jmsajaxsearch_activate' );

function jmsajaxsearch_activate () {
	$json_data = '{"time_to_show":"1000","key_word_lenght":"3","search_type":"product","items_show":"5","product_categories":[],"product_short_desc_show":"1","product_image_show":"1","product_price_show":"1","post_categories":[],"post_image_show":"1","post_author_show":"1","post_date_show":"1","post_read_more_show":"1","page_image_show":"1","page_date_show":"1","page_read_more_show":"1","popup_bg":"#ffffff","popup_border_radius":"3","popup_text_cl":"#000000","popup_link_cl":"#000000","popup_text_size":"13","popup_link_size":"15","custom_css":""}';
	if ( ! get_option( 'jmsajaxsearch_params', '' ) ) {
		update_option( 'jmsajaxsearch_params', json_decode( $json_data, true ) );
	}
}

register_deactivation_hook(__FILE__, 'jmsajaxsearch_deactivation');

function jmsajaxsearch_deactivation() {
	return true;
}

new JmsAjaxSearch_Admin();
new JmsAjaxSearch_Params();
new JmsHelper();
new JmsAjaxSearch_Widget();
new JmsAjaxSearch_Widget_NoCats();
