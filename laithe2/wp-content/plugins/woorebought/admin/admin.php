<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WooReBought_Admin {
	function __construct() {
		add_action( 'init', array( $this, 'woorebought_load_textdomain' ) );
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 99999 );
	}

	/**
	 * Register a custom menu page.
	 */
	public function add_admin_menu() {
		add_menu_page( esc_html__( 'Woo Recently Bought', 'woorebought' ), esc_html__( 'Woo Recently Bought', 'woorebought' ), 'manage_options', 'woorebought', array(
				'WooReBought_Params',
				'params_form'
			), 'dashicons-admin-comments', 888 );

	}
	function woorebought_load_textdomain() {
		load_plugin_textdomain( 'woorebought', false, dirname( plugin_basename(__FILE__) ) . '/languages' );
	}
	public function admin_enqueue_scripts() {
		$page = isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : '';
		if ( $page == 'woorebought' ) {
			wp_enqueue_style( 'woorebought-bootstrap', JMS_WOOREBOUGHT_CSS_URL . 'bootstrap.min.css' );
			wp_enqueue_style( 'woorebought-semantic', JMS_WOOREBOUGHT_CSS_URL . 'semantic.min.css' );
			wp_enqueue_style( 'woorebought-admin', JMS_WOOREBOUGHT_CSS_URL . 'admin.css' );
			wp_enqueue_style('wp-color-picker');
			wp_enqueue_script( 'woorebought-bootstrap', JMS_WOOREBOUGHT_JS_URL . 'bootstrap.min.js', array( 'jquery' ) );
			wp_enqueue_script( 'woorebought-semantic', JMS_WOOREBOUGHT_JS_URL . 'semantic.min.js', array( 'jquery' ) );
			wp_enqueue_script( 'woorebought-admin', JMS_WOOREBOUGHT_JS_URL . 'admin.js', array( 'jquery' ) );
			wp_enqueue_script('wp-color-picker');

		}
	}
}

?>
