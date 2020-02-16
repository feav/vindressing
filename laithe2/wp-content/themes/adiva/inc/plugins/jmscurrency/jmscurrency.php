<?php
/**
* Plugin Name: Jms Currency
* Plugin URI: http://www.joommasters.com
* Description: Jms currency.
* Version: 1.1
* Author: JoomMasters
* Author URI: http://www.joommasters.com
*/

// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

// Define url to this plugin file.
if ( ! defined( 'JMS_CURRENCY_URL' ) )
	define( 'JMS_CURRENCY_URL', plugin_dir_url( __FILE__ ) );

// Define path to this plugin file.
if ( ! defined( 'JMS_CURRENCY_PATH' ) )
	define( 'JMS_CURRENCY_PATH', plugin_dir_path( __FILE__ ) );

class Jms_Currency {
	/**
	 * Construct function.
	 *
	 * @return  void
	 */
	function __construct() {
		add_action( 'plugins_loaded', array( $this, 'load_textdomain') );

        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ), 10 );
        add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ), 10 );

		// Admin menu
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 11 );

        add_action( 'wp_ajax_list-currency', array( $this, 'list_currency' ) );
		add_action( 'wp_ajax_nopriv_list-currency', array( $this, 'list_currency' ) );

		add_action( 'wp_ajax_save-currency', array( $this, 'save_currency' ) );
		add_action( 'wp_ajax_remove-currency', array( $this, 'remove_currency' ) );
		add_action( 'wp_ajax_update-currency-rate', array( $this, 'update_currency_rate' ) );

		add_filter( 'woocommerce_currency',     array( $this, 'jms_currency_woocommerce_currency'     ), 10, 1 );
		add_filter( 'woocommerce_price_format', array( $this, 'jms_currency_woocommerce_price_format' ), 10, 2 );
		add_filter( 'raw_woocommerce_price',    array( $this, 'jms_currency_raw_woocommerce_price'    ), 10, 1 );
		add_filter( 'wc_price_args',            array( $this, 'jms_currency_price_args'               ), 10, 1 );
	}

	function load_textdomain() {
	  	load_plugin_textdomain( 'jmscurrency', false, JMS_CURRENCY_PATH . '/languages' );
	}

	/**
	 * Add sub-menu to jms menu.
	 *
	 * @return  void
	 */
	public static function admin_menu() {
        add_menu_page(
            __('Currencies Manage', 'jmscurrency'),
            __('Jms Currencies', 'jmscurrency'),
            'manage_options',
            'currency_manage',
            array( __CLASS__, 'render_html' ),
            'dashicons-tickets',
            85
        );
	}

    /**
	 * Enqueue stylesheet and scripts to admin.
	 */
	public static function admin_scripts() {
		wp_enqueue_style( 'jms-currency', JMS_CURRENCY_URL . '/css/style.css', array(), '1.0');
		wp_enqueue_script( 'jquery-ui-dialog' );
		wp_enqueue_style( 'wp-jquery-ui-dialog' );
	}

    /**
	 * Enqueue stylesheet and scripts to frontend.
	 */
    public static function frontend_scripts() {
        wp_enqueue_script( 'jms-vendor-jquery-cookies', JMS_CURRENCY_URL . '/js/plugins.min.js', array(), false, true );
    }

	/**
	 * Render admin html.
	 *
	 * @return  void
	 */
	public static function render_html() {
		if ( current_user_can( 'manage_options' ) )  {
			include JMS_CURRENCY_PATH . '/views/backend.php';
		}
	}

	/**
	 * Get default currency.
	 *
	 * @return  void
	 */
	public static function get_default() {
		return array(
			'currency'                       => 'USD',
			'woocommerce_currency_pos'       => 'left',
			'woocommerce_price_thousand_sep' => ',',
			'woocommerce_price_decimal_sep'  => '.',
			'woocommerce_price_num_decimals' => '2',
			'woocommerce_price_rate'         => '1'
		);
	}

	/**
	 * Get woocommerce currency.
	 *
	 * @return  void
	 */
	public static function woo_currency() {
		$currency = apply_filters( 'jms_currency_woocommerce_currency', get_option( 'woocommerce_currency' ) );
		return array(
			'currency'                       => $currency,
			'woocommerce_currency_pos'       => get_option( 'woocommerce_currency_pos','left'    ),
			'woocommerce_price_thousand_sep' => get_option( 'woocommerce_price_thousand_sep',',' ),
			'woocommerce_price_decimal_sep'  => get_option( 'woocommerce_price_decimal_sep','.'  ),
			'woocommerce_price_num_decimals' => get_option( 'woocommerce_price_num_decimals','2' ),
			'woocommerce_price_rate'         => '1'
		);

	}

	/**
	 * Get all currencies.
	 *
	 * @return  void
	 */
	public static function getCurrencies() {
		return get_option( 'jms_currencies' );
	}

	/**
	 * Get custom currency.
	 *
	 * @return  void
	 */
	public static function getCurrency( $code ) {
		$currencies = self::getCurrencies();
		if ( isset( $currencies[$code] ) ) {
			return array_merge( self::get_default(), $currencies[$code] );
		}
		return false;
	}

	/**
	 * Save currency.
	 */
	public static function saveCurrency( $code, $data ) {
		if ( $code == get_option( 'woocommerce_currency' ) ) {
			$data = self::woo_currency();
		}
		if ( $code != '' ) {
			$data['currency']  = $code;
			$currencies        = self::getCurrencies();
			$currencies[$code] = array_merge( self::get_default(), $data );
			$curs = array();

			foreach( $currencies as $code => $c ) {
				if ( $code != '' && $c['currency'] != '' ) {
					$curs[$code] = $c;
				}

			}
			update_option( 'jms_currencies', $curs );
		}
	}

	/**
	 * Delete currency.
	 */
	public static function delCurrency( $code ) {
		$currencies = self::getCurrencies();
		if ( isset( $currencies[$code] ) ) {
			unset( $currencies[$code] );
			update_option( 'jms_currencies', $currencies );
		}
	}

    /**
	 * Update currency rate.
	 */
	public static function autoUpdateCurrencyRate() {
		$currencies = self::getCurrencies();
		$woo        = self::woo_currency();
		$woo_code   = $woo['currency'];

		//start get rate from yahoo
		$url = 'http://query.yahooapis.com/v1/public/yql?q=select * from yahoo.finance.xchange where pair in ';
		$codes = array();
		$code_rate = array();
		foreach( $currencies as $code => $val ) {
			if ( $code != $woo_code && $code != '' ) {
				$key = $woo_code.$code;
				$codes[$code] = $key;
				$code_rate[$key] = $code;
			}
		}
		$url .= '("';
		$url .= implode('", "',$codes);
		$url .= '")';
		$url .= '&env=store://datatables.org/alltableswithkeys';
		$tmp = simplexml_load_file($url) ;
		$tmp_rate = (array)$tmp->children()->results;

		$rates = array();
		foreach( $tmp_rate['rate'] as $r ) {
			$tmp_x = $r->attributes();
			$key = (string) $tmp_x['id'];
			$rates[$key] = floatval( $r->Rate );
		}
		foreach( $rates as $key => $rate ) {
			$code = $code_rate[$key];
			$current = $currencies[$code];
			$current['woocommerce_price_rate'] = $rate;
			self::saveCurrency( $code,$current );
		}
	}

	/**
	 * List custom currency.
	 */
	public static function list_currency() {
		$currencies  = self::getCurrencies();
		$woocurrency = self::woo_currency();
		$woocode     = $woocurrency['currency'];
		if ( ! isset($currencies[$woocode] ) ) {
			$currencies[$woocode] = $woocurrency;
		}
		$html = '';
		if ( ! empty( $currencies ) ) {
			foreach( $currencies as $c ) {

				if ( $c['currency'] != $woocode ) {
					$html .= '<tr>';
				} else {
					$html .= '<tr style="background-color: #db9925;">';
				}

				$html .= '<td>';
				$html .=  $c['currency'];
				$html .= '</td>';

				$html .= '<td>';
				$html .=  $c['woocommerce_currency_pos'];
				$html .= '</td>';

				$html .= '<td>';
				$html .=  $c['woocommerce_price_thousand_sep'];
				$html .= '</td>';

				$html .= '<td>';
				$html .=  $c['woocommerce_price_decimal_sep'];
				$html .= '</td>';

				$html .= '<td>';
				$html .=  $c['woocommerce_price_num_decimals'];
				$html .= '</td>';

				$html .= '<td>';
				$html .=  $c['woocommerce_price_rate'];
				$html .= '</td>';

				$html .= '<td>';
				if ( $c['currency'] != $woocode ) {
					$html .=  '<a href="javascript:void(0);" data-currency="' . esc_attr( $c['currency'] ) . '" class="remove-currency">Delete</a>';
				}
				$html .= '</td>';

				$html .= '</tr>';
			}
		}
		echo $html;
		exit;
	}

	/**
	 * Save currency.
	 */
	public static function save_currency() {
		$return = array( 'result' => 0 );
		if ( $_POST['currency'] != '' ) {
			$currency = array();
			$default  = self::get_default();
			foreach( $default as $key => $val ) {
				if ( isset($_POST[$key] ) ) {
					$currency[$key] = $_POST[$key];
				} else {
					$currency[$key] = $val;
				}
			}
			self::saveCurrency( $currency['currency'], $currency );
			$return['result'] = 1;
		}
		echo json_encode( $return );
		exit;
	}

	/**
	 * Remove currency.
	 */
	function remove_currency() {
		if ( $_POST['code'] != '' ) {
			$code = esc_attr($_POST['code'] );
			self::delCurrency( $code );
		}
	}

	/**
	 * Update currency rate.
	 */
	function update_currency_rate() {
		self::autoUpdateCurrencyRate();
		exit;
	}

	/**
	 * Get current currency.
	 */
	public static function getCurrentCurrency() {
		$default    = self::woo_currency();
		$currencies = self::getCurrencies();
		$current    = $default;
		$code       = isset( $_COOKIE['jms_currency'] ) ? $_COOKIE['jms_currency'] : '';
		if ( $code !='' && isset( $currencies[$code] ) ) {
			$current = $currencies[$code];
		}
		return $current;
	}

	/**
	 * Default currency.
	 */
	public static function jms_currency_woocommerce_currency( $default_currency ) {
		$current          = self::getCurrentCurrency();
		$default_currency = self::woo_currency();

		if ( isset( $current['currency'] ) && $current['currency'] != $default_currency['currency'] ) {
			return  $current['currency'];
		}

		return  $default_currency['currency'];
	}

	/**
	 * Currency price format.
	 */
	public static function jms_currency_woocommerce_price_format( $format, $currency_pos ) {
		global $post;
		$currency = false;
		if ( isset( $post->ID ) ) {
			$currency = get_post_meta( $post->ID, '_jms_currency', true );
		}
		$current = self::getCurrentCurrency();
		if ( $currency && is_array( $currency ) && !empty( $currency ) ) {
			$current = $currency;
		}

		$default_currency = self::woo_currency();
		if ( isset( $current['currency'] ) && $current['currency'] != $default_currency['currency'] ) {
			$currency_pos = $current['woocommerce_currency_pos'];
			$format = '%1$s%2$s';

			switch ( $currency_pos ) {
				case 'left' :
					$format = '%1$s%2$s';
					break;
				case 'right' :
					$format = '%2$s%1$s';
					break;
				case 'left_space' :
					$format = '%1$s&nbsp;%2$s';
					break;
				case 'right_space' :
					$format = '%2$s&nbsp;%1$s';
					break;
			}

		}
		return apply_filters( 'jms_currency_woocommerce_price_format', $format, $currency_pos );
	}

	/**
	 * Currency raw price.
	 */
	public static function jms_currency_raw_woocommerce_price( $price ) {
		global $post;
		$currency = false;
		if ( isset($post->ID ) ) {
			$currency = get_post_meta($post->ID,'_jms_currency',true);
		}
		$current = self::getCurrentCurrency();
		if ( $currency && is_array( $currency ) && ! empty( $currency ) ) {
			$current = $currency;
		}

		$default_currency = self::woo_currency();
		if ( isset( $current['currency'] ) && $current['currency'] != $default_currency['currency'] ) {
			if ( isset( $current['woocommerce_price_rate'] ) && $current['woocommerce_price_rate'] != 1 ) {
				$price = $price * floatval( $current['woocommerce_price_rate'] );
			}
		}
		return $price;
	}

	/**
	 * List custom currency.
	 */
	public static function jms_currency_price_args( $args ) {
		global $post;
		$currency = false;
		if ( isset( $post->ID ) ) {
			$currency = get_post_meta( $post->ID, '_jms_currency', true );
		}
		$current = self::getCurrentCurrency();
		if ( $currency && is_array( $currency ) && !empty( $currency ) ) {
			$current = $currency;
		}

		$default_currency = self::woo_currency();

		if ( isset( $current['currency'] ) && $current['currency'] != $default_currency['currency'] ) {
			if ( isset( $current['woocommerce_price_decimal_sep'] ) ) {
				$args['decimal_separator'] = $current['woocommerce_price_decimal_sep'];
			}
			if ( isset( $current['woocommerce_price_thousand_sep'] ) ) {
				$args['thousand_separator'] = $current['woocommerce_price_thousand_sep'];
			}
			if ( isset( $current['woocommerce_price_num_decimals'] ) ) {
				$args['decimals'] = $current['woocommerce_price_num_decimals'];
			}
		}
		return $args;
	}
}
new Jms_Currency;
