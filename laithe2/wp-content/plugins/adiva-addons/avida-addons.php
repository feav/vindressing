<?php
/**
 * Plugin Name: Adiva Addons
 * Plugin URI: http://joommasters.com
 * Description: Currently supports the following theme functionality: shortcodes, CPT.
 * Version: 1.6
 * Author: JoomMasters
 * Author URI: http://joommasters.com
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: adiva-addons
 */

define( 'ADIVA_ADDONS_URL', plugin_dir_url( __FILE__ ) );
define( 'ADIVA_ADDONS_PATH', plugin_dir_path( __FILE__ ) );

if( ! function_exists('is_plugin_active') ){
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

/*
 * Load plugin textdomain
 */

if ( !function_exists('adiva_addons_load_textdomain') ) {
    function adiva_addons_load_textdomain() {
        load_plugin_textdomain( 'adiva-addons', false, ADIVA_ADDONS_PATH . 'languages' );
    }
    add_action( 'plugins_loaded', 'adiva_addons_load_textdomain' );
}

if ( !function_exists('adiva_admin_styles') ) {
    function adiva_admin_styles() {
        wp_enqueue_style('custom-style', ADIVA_ADDONS_URL . 'assets/css/admin.css');
    }
    add_action('admin_print_styles', 'adiva_admin_styles');
}

// Require ReduxFramework
if ( !class_exists ( 'ReduxFramework' ) && file_exists ( ADIVA_ADDONS_PATH . 'inc/ReduxCore/framework.php' ) ) {
    require_once ( ADIVA_ADDONS_PATH .'inc/ReduxCore/framework.php' );

    function adiva_addons_custom_css_redux() {
        wp_register_style(
            'redux-custom-css',
            ADIVA_ADDONS_URL . 'assets/css/redux-framework.css',
            array( 'redux-admin-css' ),
            time(),
            'all'
        );
        wp_enqueue_style('redux-custom-css');
    }
    add_action( 'redux/page/adiva_option/enqueue', 'adiva_addons_custom_css_redux' );
}

function adiva_compress($variable){
	return base64_encode($variable);
}

function adiva_decompress($variable){
	return base64_decode($variable);
}

function adiva_get_svg($variable){
	return file_get_contents($variable);
}

/*
* [ CS Framework. ] - - - - - - - - - - - - - - - - - - - -
*/
require_once ADIVA_ADDONS_PATH . 'inc/cs-framework/cs-framework.php';

/*
* [ Visual Composer. ] - - - - - - - - - - - - - - - - - - - -
*/
foreach (glob( ADIVA_ADDONS_PATH . 'inc/visual-composer/*.php' ) as $filename) {
    include_once $filename;
}

/*
* [ Shortcode Visual Composer ] - - - - - - - - - - - - - - - - - - - -
*/
foreach (glob( ADIVA_ADDONS_PATH . 'inc/shortcodes/*.php' ) as $filename) {
    include_once $filename;
}

/*
* [ Mega Menu. ] - - - - - - - - - - - - - - - - - - - -
*/
require_once ADIVA_ADDONS_PATH . 'inc/megamenu/megamenu.php';

require_once ( ADIVA_ADDONS_PATH .'inc/portfolio/init.php' );

require_once ( ADIVA_ADDONS_PATH .'inc/shortcodes.php' );
