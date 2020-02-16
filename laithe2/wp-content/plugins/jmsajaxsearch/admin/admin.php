<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class JmsAjaxSearch_Admin {
	function __construct() {
		add_action( 'init', array( $this, 'woorebought_load_textdomain' ) );
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 99999 );
	}

	/**
	 * Register a custom menu page.
	 */

	public function add_admin_menu() {
		add_menu_page( esc_html__( 'Ajax Search', 'jmsajaxsearch' ), esc_html__( 'Ajax Search', 'jmsajaxsearch' ), 'manage_options', 'jmsajaxsearch', array(
				'jmsajaxsearch_params',
				'params_form'
			), 'dashicons-search', 888 );
	}

	function woorebought_load_textdomain() {
		load_plugin_textdomain( 'jmsajaxsearch', false, dirname( plugin_basename(__FILE__) ) . '/languages' );
	}

	public function admin_enqueue_scripts() {
		$page = isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : '';
		if ( $page == 'jmsajaxsearch' ) {
			wp_enqueue_style( 'jmsajaxsearch-bootstrap', JMS_AJAXSEARCH_CSS_URL . 'bootstrap.min.css' );
			wp_enqueue_style( 'jmsajaxsearch-semantic', JMS_AJAXSEARCH_CSS_URL . 'semantic.min.css' );
			wp_enqueue_style( 'jmsajaxsearch-admin', JMS_AJAXSEARCH_CSS_URL . 'admin-style.css' );
			wp_enqueue_script( 'jmsajaxsearch-bootstrap', JMS_AJAXSEARCH_JS_URL . 'bootstrap.min.js', array( 'jquery' ) );
			wp_enqueue_script( 'jmsajaxsearch-semantic', JMS_AJAXSEARCH_JS_URL . 'semantic.min.js', array( 'jquery' ) );
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'jmsajaxsearch-script-handle', JMS_AJAXSEARCH_JS_URL . 'ajax-search-admin.js', array( 'wp-color-picker' ), false, true );
		}
	}
}
