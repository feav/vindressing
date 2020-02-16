<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WooReBought_Params {
	static $params;
	private static $messages = array();
	private static $errors   = array();
	public function __construct() {
		add_action( 'admin_init', array( $this, 'save_params_trigger' ) );
	}

	public function save_params_trigger() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}
		if ( ! isset( $_POST['_woorebought_nonce'] ) || ! isset( $_POST['woorebought_params'] ) ) {
			return false;
		}
		update_option( '_woorebought_prefix', substr( md5( date( "YmdHis" ) ), 0, 10 ) );
		update_option( 'woorebought_params', $_POST['woorebought_params'] );
		if(isset( $_POST['woorebought_params']))
			self::add_message( __( 'Your settings have been saved.', 'woorebought' ) );

	}
	public static function add_error( $text ) {
		self::$errors[] = $text;
	}
	public static function add_message( $text ) {
		self::$messages[] = $text;
	}
	public static function show_messages() {
		if ( count( self::$errors ) > 0 ) {
			foreach ( self::$errors as $error ) {
				echo '<div id="message" class="error inline"><p><strong>' . esc_html( $error ) . '</strong></p></div>';
			}
		} elseif ( count( self::$messages ) > 0 ) {
			echo '<div id="message" class="updated inline"><p><strong>' . esc_html( self::$messages[0] ) . '</strong></p></div>';
		}
	}
	protected static function set_nonce() {
		return wp_nonce_field( '_woorebought_setting_form_', '_woorebought_nonce' );
	}
	protected static function gen_param_name( $_param, $multi = false ) {
		if ( $_param ) {
			if ( $multi ) {
				return 'woorebought_params[' . $_param . '][]';
			} else {
				return 'woorebought_params[' . $_param . ']';
			}
		} else {
			return '';
		}
	}
	public static function get_param( $_param, $default = '' ) {
		$params = get_option( 'woorebought_params', array() );
		if ( self::$params ) {
			$params = self::$params;
		} else {
			self::$params = $params;
		}
		if ( isset( $params[$_param] ) && $_param ) {
			return $params[$_param];
		} else {
			return $default;
		}
	}
	public static function params_form() {
		self::$params = get_option( 'woorebought_params', array() );
		?>
		<div class="wrap woo-notification">
			<h2><?php esc_attr_e( 'WooCommerce Recently Bought Settings', 'woorebought' ) ?></h2>
			<?php self::show_messages(); ?>
			<form method="post" action="" class="ui form">
				<?php echo ent2ncr( self::set_nonce() ) ?>
				<ul class="nav nav-tabs">
				  <li class="active"><a data-toggle="tab" href="#general"><?php esc_html_e( 'General', 'woorebought' ) ?></a></li>
				  <li><a data-toggle="tab" href="#products"><?php esc_html_e( 'Products', 'woorebought' ) ?></a></li>
				  <li><a data-toggle="tab" href="#popupcontent"><?php esc_html_e( 'Popup Content', 'woorebought' ) ?></a></li>
				  <li><a data-toggle="tab" href="#time"><?php esc_html_e( 'Time', 'woorebought' ) ?></a></li>
				  <li><a data-toggle="tab" href="#customstyle"><?php esc_html_e( 'Custom Style', 'woorebought' ) ?></a></li>
				  <li><a data-toggle="tab" href="#assignpages"><?php esc_html_e( 'Assign Pages', 'woorebought' ) ?></a></li>
				</ul>
				<div class="tab-content">
					<div id="general" class="tab-pane fade in active">
						<div class="form-group row">
							<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Enable', 'woorebought' ) ?></label>
							<div class="col-sm-10">
								<div class="ui toggle checkbox">
									<input id="<?php echo self::gen_param_name( 'enable' ) ?>" type="checkbox" <?php checked( self::get_param( 'enable' ), 1 ) ?> tabindex="0" class="hidden" value="1" name="<?php echo self::gen_param_name( 'enable' ) ?>" />
									<label></label>
								</div>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Popup Show Animate', 'woorebought' ) ?></label>
							<div class="col-sm-10">
								<select name="<?php echo self::gen_param_name( 'popup_show_trans' ) ?>" class="ui fluid dropdown">
									<optgroup label="Attention Seekers">
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'bounce' ) ?> value="bounce">bounce</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'flash' ) ?> value="flash">flash</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'pulse' ) ?> value="pulse">pulse</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'rubberBand' ) ?> value="rubberBand">rubberBand</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'shake' ) ?> value="shake">shake</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'swing' ) ?> value="swing">swing</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'tada' ) ?> value="tada">tada</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'wobble' ) ?> value="wobble">wobble</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'jello' ) ?> value="jello">jello</option>
									</optgroup>

									<optgroup label="Bouncing Entrances">
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'bounceIn' ) ?> value="bounceIn">bounceIn</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'bounceInDown' ) ?> value="bounceInDown">bounceInDown</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'bounceInLeft' ) ?> value="bounceInLeft">bounceInLeft</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'bounceInRight' ) ?> value="bounceInRight">bounceInRight</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'bounceInUp' ) ?> value="bounceInUp">bounceInUp</option>
									</optgroup>

									<optgroup label="Bouncing Exits">
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'bounceOut' ) ?> value="bounceOut">bounceOut</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'bounceOutDown' ) ?> value="bounceOutDown">bounceOutDown</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'bounceOutLeft' ) ?> value="bounceOutLeft">bounceOutLeft</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'bounceOutRight' ) ?> value="bounceOutRight">bounceOutRight</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'bounceOutUp' ) ?> value="bounceOutUp">bounceOutUp</option>
									</optgroup>

									<optgroup label="Fading Entrances">
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'fadeIn' ) ?> value="fadeIn">fadeIn</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'fadeInDown' ) ?> value="fadeInDown">fadeInDown</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'fadeInDownBig' ) ?> value="fadeInDownBig">fadeInDownBig</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'fadeInLeft' ) ?> value="fadeInLeft">fadeInLeft</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'fadeInLeftBig' ) ?> value="fadeInLeftBig">fadeInLeftBig</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'fadeInRight' ) ?> value="fadeInRight">fadeInRight</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'fadeInRightBig' ) ?> value="fadeInRightBig">fadeInRightBig</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'fadeInUp' ) ?> value="fadeInUp">fadeInUp</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'fadeInUpBig' ) ?> value="fadeInUpBig">fadeInUpBig</option>
									</optgroup>

									<optgroup label="Fading Exits">
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'fadeOut' ) ?> value="fadeOut">fadeOut</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'fadeOutDown' ) ?> value="fadeOutDown">fadeOutDown</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'fadeOutDownBig' ) ?> value="fadeOutDownBig">fadeOutDownBig</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'fadeOutLeft' ) ?> value="fadeOutLeft">fadeOutLeft</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'fadeOutLeftBig' ) ?> value="fadeOutLeftBig">fadeOutLeftBig</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'fadeIn' ) ?> value="fadeOutRight">fadeOutRight</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'fadeOutRightBig' ) ?> value="fadeOutRightBig">fadeOutRightBig</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'fadeOutUp' ) ?> value="fadeOutUp">fadeOutUp</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'fadeOutUpBig' ) ?> value="fadeOutUpBig">fadeOutUpBig</option>
									</optgroup>

									<optgroup label="Flippers">
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'flip' ) ?> value="flip">flip</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'flipInX' ) ?> value="flipInX">flipInX</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'flipInY' ) ?> value="flipInY">flipInY</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'flipOutX' ) ?> value="flipOutX">flipOutX</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'flipOutY' ) ?> value="flipOutY">flipOutY</option>
									</optgroup>

									<optgroup label="Lightspeed">
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'lightSpeedIn' ) ?> value="lightSpeedIn">lightSpeedIn</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'lightSpeedOut' ) ?> value="lightSpeedOut">lightSpeedOut</option>
									</optgroup>

									<optgroup label="Rotating Entrances">
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'rotateIn' ) ?> value="rotateIn">rotateIn</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'rotateInDownLeft' ) ?> value="rotateInDownLeft">rotateInDownLeft</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'rotateInDownRight' ) ?> value="rotateInDownRight">rotateInDownRight</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'rotateInUpLeft' ) ?> value="rotateInUpLeft">rotateInUpLeft</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'rotateInUpRight' ) ?> value="rotateInUpRight">rotateInUpRight</option>
									</optgroup>

									<optgroup label="Rotating Exits">
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'rotateOut' ) ?> value="rotateOut">rotateOut</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'rotateOutDownLeft' ) ?> value="rotateOutDownLeft">rotateOutDownLeft</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'rotateOutDownRight' ) ?> value="rotateOutDownRight">rotateOutDownRight</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'rotateOutUpLeft' ) ?> value="rotateOutUpLeft">rotateOutUpLeft</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'rotateOutUpRight' ) ?> value="rotateOutUpRight">rotateOutUpRight</option>
									</optgroup>

									<optgroup label="Sliding Entrances">
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'slideInUp' ) ?> value="slideInUp">slideInUp</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'slideInDown' ) ?> value="slideInDown">slideInDown</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'slideInLeft' ) ?> value="slideInLeft">slideInLeft</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'slideInRight' ) ?> value="slideInRight">slideInRight</option>

									</optgroup>
									<optgroup label="Sliding Exits">
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'slideOutUp' ) ?> value="slideOutUp">slideOutUp</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'slideOutDown' ) ?> value="slideOutDown">slideOutDown</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'slideOutLeft' ) ?> value="slideOutLeft">slideOutLeft</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'slideOutRight' ) ?> value="slideOutRight">slideOutRight</option>

									</optgroup>

									<optgroup label="Zoom Entrances">
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'zoomIn' ) ?> value="zoomIn">zoomIn</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'zoomInDown' ) ?> value="zoomInDown">zoomInDown</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'zoomInLeft' ) ?> value="zoomInLeft">zoomInLeft</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'zoomInRight' ) ?> value="zoomInRight">zoomInRight</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'zoomInUp' ) ?> value="zoomInUp">zoomInUp</option>
									</optgroup>

									<optgroup label="Zoom Exits">
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'zoomOut' ) ?> value="zoomOut">zoomOut</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'zoomOutDown' ) ?> value="zoomOutDown">zoomOutDown</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'zoomOutLeft' ) ?> value="zoomOutLeft">zoomOutLeft</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'zoomOutRight' ) ?> value="zoomOutRight">zoomOutRight</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'zoomOutUp' ) ?> value="zoomOutUp">zoomOutUp</option>
									</optgroup>

									<optgroup label="Specials">
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'hinge' ) ?> value="hinge">hinge</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'jackInTheBox' ) ?> value="jackInTheBox">jackInTheBox</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'rollIn' ) ?> value="rollIn">rollIn</option>
									  <option <?php selected( self::get_param( 'popup_show_trans' ), 'rollOut' ) ?> value="rollOut">rollOut</option>
									</optgroup>
								</select>
								<p class="description"><?php esc_html_e( 'Popup will show with this animation effect.', 'woorebought' ) ?></p>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Popup Hide Animate', 'woorebought' ) ?></label>
							<div class="col-sm-10">
								<select name="<?php echo self::gen_param_name( 'popup_hide_trans' ) ?>" class="ui fluid dropdown">
									<optgroup label="Attention Seekers">
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'bounce' ) ?> value="bounce">bounce</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'flash' ) ?> value="flash">flash</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'pulse' ) ?> value="pulse">pulse</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'rubberBand' ) ?> value="rubberBand">rubberBand</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'shake' ) ?> value="shake">shake</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'swing' ) ?> value="swing">swing</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'tada' ) ?> value="tada">tada</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'wobble' ) ?> value="wobble">wobble</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'jello' ) ?> value="jello">jello</option>
									</optgroup>

									<optgroup label="Bouncing Entrances">
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'bounceIn' ) ?> value="bounceIn">bounceIn</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'bounceInDown' ) ?> value="bounceInDown">bounceInDown</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'bounceInLeft' ) ?> value="bounceInLeft">bounceInLeft</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'bounceInRight' ) ?> value="bounceInRight">bounceInRight</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'bounceInUp' ) ?> value="bounceInUp">bounceInUp</option>
									</optgroup>

									<optgroup label="Bouncing Exits">
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'bounceOut' ) ?> value="bounceOut">bounceOut</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'bounceOutDown' ) ?> value="bounceOutDown">bounceOutDown</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'bounceOutLeft' ) ?> value="bounceOutLeft">bounceOutLeft</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'bounceOutRight' ) ?> value="bounceOutRight">bounceOutRight</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'bounceOutUp' ) ?> value="bounceOutUp">bounceOutUp</option>
									</optgroup>

									<optgroup label="Fading Entrances">
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'fadeIn' ) ?> value="fadeIn">fadeIn</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'fadeInDown' ) ?> value="fadeInDown">fadeInDown</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'fadeInDownBig' ) ?> value="fadeInDownBig">fadeInDownBig</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'fadeInLeft' ) ?> value="fadeInLeft">fadeInLeft</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'fadeInLeftBig' ) ?> value="fadeInLeftBig">fadeInLeftBig</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'fadeInRight' ) ?> value="fadeInRight">fadeInRight</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'fadeInRightBig' ) ?> value="fadeInRightBig">fadeInRightBig</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'fadeInUp' ) ?> value="fadeInUp">fadeInUp</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'fadeInUpBig' ) ?> value="fadeInUpBig">fadeInUpBig</option>
									</optgroup>

									<optgroup label="Fading Exits">
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'fadeOut' ) ?> value="fadeOut">fadeOut</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'fadeOutDown' ) ?> value="fadeOutDown">fadeOutDown</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'fadeOutDownBig' ) ?> value="fadeOutDownBig">fadeOutDownBig</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'fadeOutLeft' ) ?> value="fadeOutLeft">fadeOutLeft</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'fadeOutLeftBig' ) ?> value="fadeOutLeftBig">fadeOutLeftBig</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'fadeIn' ) ?> value="fadeOutRight">fadeOutRight</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'fadeOutRightBig' ) ?> value="fadeOutRightBig">fadeOutRightBig</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'fadeOutUp' ) ?> value="fadeOutUp">fadeOutUp</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'fadeOutUpBig' ) ?> value="fadeOutUpBig">fadeOutUpBig</option>
									</optgroup>

									<optgroup label="Flippers">
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'flip' ) ?> value="flip">flip</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'flipInX' ) ?> value="flipInX">flipInX</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'flipInY' ) ?> value="flipInY">flipInY</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'flipOutX' ) ?> value="flipOutX">flipOutX</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'flipOutY' ) ?> value="flipOutY">flipOutY</option>
									</optgroup>

									<optgroup label="Lightspeed">
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'lightSpeedIn' ) ?> value="lightSpeedIn">lightSpeedIn</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'lightSpeedOut' ) ?> value="lightSpeedOut">lightSpeedOut</option>
									</optgroup>

									<optgroup label="Rotating Entrances">
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'rotateIn' ) ?> value="rotateIn">rotateIn</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'rotateInDownLeft' ) ?> value="rotateInDownLeft">rotateInDownLeft</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'rotateInDownRight' ) ?> value="rotateInDownRight">rotateInDownRight</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'rotateInUpLeft' ) ?> value="rotateInUpLeft">rotateInUpLeft</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'rotateInUpRight' ) ?> value="rotateInUpRight">rotateInUpRight</option>
									</optgroup>

									<optgroup label="Rotating Exits">
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'rotateOut' ) ?> value="rotateOut">rotateOut</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'rotateOutDownLeft' ) ?> value="rotateOutDownLeft">rotateOutDownLeft</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'rotateOutDownRight' ) ?> value="rotateOutDownRight">rotateOutDownRight</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'rotateOutUpLeft' ) ?> value="rotateOutUpLeft">rotateOutUpLeft</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'rotateOutUpRight' ) ?> value="rotateOutUpRight">rotateOutUpRight</option>
									</optgroup>
									<optgroup label="Sliding Entrances">
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'slideInUp' ) ?> value="slideInUp">slideInUp</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'slideInDown' ) ?> value="slideInDown">slideInDown</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'slideInLeft' ) ?> value="slideInLeft">slideInLeft</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'slideInRight' ) ?> value="slideInRight">slideInRight</option>

									</optgroup>
									<optgroup label="Sliding Exits">
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'slideOutUp' ) ?> value="slideOutUp">slideOutUp</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'slideOutDown' ) ?> value="slideOutDown">slideOutDown</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'slideOutLeft' ) ?> value="slideOutLeft">slideOutLeft</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'slideOutRight' ) ?> value="slideOutRight">slideOutRight</option>

									</optgroup>

									<optgroup label="Zoom Entrances">
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'zoomIn' ) ?> value="zoomIn">zoomIn</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'zoomInDown' ) ?> value="zoomInDown">zoomInDown</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'zoomInLeft' ) ?> value="zoomInLeft">zoomInLeft</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'zoomInRight' ) ?> value="zoomInRight">zoomInRight</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'zoomInUp' ) ?> value="zoomInUp">zoomInUp</option>
									</optgroup>

									<optgroup label="Zoom Exits">
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'zoomOut' ) ?> value="zoomOut">zoomOut</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'zoomOutDown' ) ?> value="zoomOutDown">zoomOutDown</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'zoomOutLeft' ) ?> value="zoomOutLeft">zoomOutLeft</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'zoomOutRight' ) ?> value="zoomOutRight">zoomOutRight</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'zoomOutUp' ) ?> value="zoomOutUp">zoomOutUp</option>
									</optgroup>

									<optgroup label="Specials">
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'hinge' ) ?> value="hinge">hinge</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'jackInTheBox' ) ?> value="jackInTheBox">jackInTheBox</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'rollIn' ) ?> value="rollIn">rollIn</option>
									  <option <?php selected( self::get_param( 'popup_hide_trans' ), 'rollOut' ) ?> value="rollOut">rollOut</option>
									</optgroup>
								</select>
								<p class="description"><?php esc_html_e( 'Popup will hide with this animation effect.', 'woorebought' ) ?></p>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Popup Position', 'woorebought' ) ?></label>
							<div class="col-sm-10">
								<select name="<?php echo self::gen_param_name( 'popup_position' ) ?>" class="ui fluid dropdown">
									<option <?php selected( self::get_param( 'popup_position' ), 'bottom_left' ) ?> value="bottom_left">Bottom Left</option>
									<option <?php selected( self::get_param( 'popup_position' ), 'bottom_right' ) ?> value="bottom_right">Bottom Right</option>
									<option <?php selected( self::get_param( 'popup_position' ), 'top_left' ) ?> value="top_left">Top Left</option>
									<option <?php selected( self::get_param( 'popup_position' ), 'top_right' ) ?> value="top_right">Top Right</option>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Image Position', 'woorebought' ) ?></label>
							<div class="col-sm-10">
								<select name="<?php echo self::gen_param_name( 'image_position' ) ?>" class="ui fluid dropdown">
									<option <?php selected( self::get_param( 'image_position' ), 'img_left' ) ?> value="img_left">Image at Left</option>
									<option <?php selected( self::get_param( 'image_position' ), 'img_right' ) ?> value="img_right">Image at Right</option>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-2 col-form-label"><?php esc_html_e( 'External link', 'woorebought' ) ?></label>
							<div class="col-sm-10">
								<div class="ui toggle checkbox">
									<input id="<?php echo self::gen_param_name( 'product_link' ) ?>" type="checkbox" <?php checked( self::get_param( 'product_link' ), 1 ) ?> tabindex="0" class="hidden" value="1" name="<?php echo self::gen_param_name( 'product_link' ) ?>" />
										<label></label>
								</div>
								<p class="description"><?php esc_html_e( 'Working with  External/Affiliate product. Product link is product URL.', 'woorebought' ) ?></p>
							</div>
						</div>
						<!-- End External link -->
						<div class="form-group row">
							<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Popup Close Icon', 'woorebought' ) ?></label>
							<div class="col-sm-10">
								<div class="ui toggle checkbox">
									<input id="<?php echo self::gen_param_name( 'popup_close_icon' ) ?>" type="checkbox" <?php checked( self::get_param( 'popup_close_icon' ), 1 ) ?> tabindex="0" class="hidden" value="1" name="<?php echo self::gen_param_name( 'popup_close_icon' ) ?>" />
									<label></label>
								</div>
								<p class="description"><?php esc_html_e( 'Show popup close icon.', 'woorebought' ) ?></p>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Use Cache', 'woorebought' ) ?></label>
							<div class="col-sm-10">
								<div class="ui toggle checkbox">
									<input id="<?php echo self::gen_param_name( 'use_cache' ) ?>" type="checkbox" <?php checked( self::get_param( 'use_cache' ), 1 ) ?> tabindex="0" class="hidden" value="1" name="<?php echo self::gen_param_name( 'use_cache' ) ?>" />
									<label></label>
								</div>
								<p class="description"><?php esc_html_e( 'Use cache help get products faster. Recommend enable it.', 'woorebought' ) ?></p>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Ajax Enable', 'woorebought' ) ?></label>
							<div class="col-sm-10">
								<div class="ui toggle checkbox">
									<input id="<?php echo self::gen_param_name( 'ajax_enable' ) ?>" type="checkbox" <?php checked( self::get_param( 'ajax_enable' ), 1 ) ?> tabindex="0" class="hidden" value="1" name="<?php echo self::gen_param_name( 'ajax_enable' ) ?>" />
									<label></label>
								</div>
								<p class="description"><?php esc_html_e( 'Load popup use ajax (it not working with Product from Orders). Recommend disable it,   Your site will be load faster.', 'woorebought' ) ?></p>
							</div>
						</div>
						<!-- End Ajax -->
					</div>
					<div id="products" class="tab-pane fade">
						<div class="form-group row">
							<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Get Products From', 'woorebought' ) ?></label>
							<div class="col-sm-10">
								<select name="<?php echo self::gen_param_name( 'product_src' ) ?>" class="ui fluid dropdown product_src">
									<option <?php selected( self::get_param( 'product_src' ), 'featured_products' ) ?> value="featured_products"><?php esc_attr_e( 'Featured Products', 'woorebought' ) ?></option>
									<option <?php selected( self::get_param( 'product_src' ), 'latest_products' ) ?> value="latest_products"><?php esc_attr_e( 'Latest Products', 'woorebought' ) ?></option>
									<option <?php selected( self::get_param( 'product_src' ), 'orders' ) ?> value="orders"><?php esc_attr_e( 'Orders', 'woorebought' ) ?></option>
									<option <?php selected( self::get_param( 'product_src' ), 'products' ) ?> value="products"><?php esc_attr_e( 'Select Products', 'woorebought' ) ?></option>
									<option <?php selected( self::get_param( 'product_src' ), 'categories' ) ?> value="categories"><?php esc_attr_e( 'Select Categories', 'woorebought' ) ?></option>
								</select>
								<p class="description"><?php esc_html_e( 'You can arrange product order or special product which you want to up-sell.', 'woorebought' ) ?></p>
							</div>
						</div>
						<!-- end Show Products -->
						<div class="form-group row src_field from_categories">
							<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Select Categories', 'woorebought' ) ?></label>
							<div class="col-sm-10">
								<?php
								$cates = self::get_param( 'select_categories', array() );
								?>
								<input type="hidden" name="select_categories_val" class="selected_vals" value="<?php echo implode(",",$cates);?>" />
								<select name="<?php echo self::gen_param_name( 'select_categories', true ) ?>" class="ui fluid search dropdown multi" multiple="">
									<option value=""><?php esc_attr_e( 'Please select categories', 'woorebought' ) ?></option>
									<?php
											$categories = get_terms(
												array(
													'taxonomy' => 'product_cat'
												)
											);
											if ( count( $categories ) ) {
												foreach ( $categories as $category ) { ?>
													<option value="<?php echo esc_attr( $category->term_id ) ?>"><?php echo esc_html( $category->name ) ?> (ID :<?php echo esc_attr( $category->term_id ) ?>)</option>
													<?php
													// get children product categories
													$subcats = get_categories( array( 'hide_empty' => 0, 'parent' => $category->term_id, 'taxonomy' => 'product_cat' ) );
													if ( count($subcats) > 0 ) {
														foreach ( $subcats as $subcat ) { ?>
														<option value="<?php echo esc_attr( $subcat->term_id ) ?>"><?php echo esc_html( $subcat->name ) ?> (ID :<?php echo esc_attr( $subcat->term_id ) ?>)</option>
														<?php
														}
													}
												}
											}
										?>
								</select>
								<p class="description"><?php esc_html_e( 'Input category title to see suggestions.', 'woorebought' ) ?></p>
							</div>
						</div>
						<!-- end categories -->

						<div class="form-group row src_field from_orders">
							<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Order Time', 'woorebought' ) ?></label>
							<div class="col-sm-10">
								<div class="fields">
									<div class="twelve field">
											<input type="number" value="<?php echo self::get_param( 'order_time_num', 30 ) ?>" name="<?php echo self::gen_param_name( 'order_time_num' ) ?>" />
									</div>
									<div class="two field">
										<select name="<?php echo self::gen_param_name( 'order_time_type' ) ?>" class="ui dropdown">
											<option <?php selected( self::get_param( 'order_time_type' ), 'days' ) ?> value="days"><?php esc_attr_e( 'Days', 'woorebought' ) ?></option>
											<option <?php selected( self::get_param( 'order_time_type' ), 'hours' ) ?> value="hours"><?php esc_attr_e( 'Hours', 'woorebought' ) ?></option>
											<option <?php selected( self::get_param( 'order_time_type' ), 'minutes' ) ?> value="minutes"><?php esc_attr_e( 'Minutes', 'woorebought' ) ?></option>
										</select>
									</div>
								</div>
								<p class="description"><?php esc_html_e( 'Products in this recently time will get from order.  ', 'woorebought' ) ?></p>
							</div>
						</div>
						<!-- End Order Time -->
						<div class="form-group row src_field from_orders">
							<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Order Status', 'woorebought' ) ?></label>
							<div class="col-sm-10">
								<?php
									$order_status = self::get_param( 'order_status', array( 'wc-completed' ) );
									$statuses       = wc_get_order_statuses();

									?>
									<select multiple="multiple" name="<?php echo self::gen_param_name( 'order_status', true ) ?>" class="ui">
										<?php foreach ( $statuses as $k => $status ) {
											$selected = '';
											if ( in_array( $k, $order_status ) ) {
												$selected = 'selected="selected"';
											}
											?>
											<option <?php echo $selected; ?> value="<?php echo esc_attr( $k ) ?>"><?php echo esc_html( $status ) ?></option>
										<?php } ?>
									</select>
							</div>
						</div>
						<!-- End Order Status -->
						<div class="form-group row src_field from_products">
							<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Select Products', 'woorebought' ) ?></label>
							<div class="col-sm-10">
								<?php
								$select_products = self::get_param( 'select_products', array() ); ?>
								<input type="hidden" name="select_products_val" class="selected_vals" value="<?php echo implode(",",$select_products);?>" />

									<select multiple="multiple" name="<?php echo self::gen_param_name( 'select_products', true ) ?>" class="ui fluid search dropdown multi">
										<option value=""><?php esc_html_e( 'Please select products', 'woorebought' ) ?></option>
										<?php
											$args = array(
												'post_type'           => array( 'product' , 'product_variation' ),
												'post_status'         => 'publish',
												'ignore_sticky_posts' => 1,
												'posts_per_page'      => -1,
												'orderby'             => 'date',
												'order'               => 'DESC'
											);
											$p_query = new WP_Query( $args );
											if ( $p_query->have_posts() ) {
												$_products = $p_query->posts;
												foreach ( $_products as $_product ) {
													$_product_data = wc_get_product( $_product );
														if ( $_product_data->get_type() == 'variation' ) {
															$_product_name = $_product_data->get_name();
														} else {
															$_product_name = $_product_data->get_title();
														}
													if ( $_product_data ) { ?>
														<option value="<?php echo esc_attr( $_product_data->get_id() ); ?>"><?php echo esc_html( $_product_name ); ?> (ID : <?php echo esc_attr( $_product_data->get_id() ); ?>)</option>
													<?php }
												}
											}
											// Reset Post Data
											wp_reset_postdata();
										?>
									</select>
									<p class="description"><?php esc_html_e( 'Input product title to see suggestions.', 'woorebought' ) ?></p>
							</div>
						</div>
						<!-- End Select Products -->
						<div class="form-group row">
							<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Frame Time', 'woorebought' ) ?></label>
							<div class="col-sm-10">
								<div class="fields">
									<div class="twelve field">
										<input type="number" name="<?php echo self::gen_param_name( 'frame_time' ) ?>" value="<?php echo self::get_param( 'frame_time', '60' ) ?>" />
									</div>
									<div class="two field">
										<label><?php esc_html_e( 'minutes', 'woorebought' ) ?></label>
									</div>
								</div>
								<p class="description"><?php esc_html_e( 'Time will auto get random in this frame.', 'woorebought' ) ?></p>
							</div>
						</div>
						<!-- End Frame Time -->
					</div>
					<div id="popupcontent" class="tab-pane fade">
						<div class="form-group row">
							<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Recently Bought Content', 'woorebought' ) ?></label>
							<div class="col-sm-10">
								<?php $rebought_content = self::get_param( 'rebought_content', 'Someone in {address} purchased a {product_link} {time_ago}' );?>
								<textarea name="<?php echo self::gen_param_name( 'rebought_content') ?>"><?php echo $rebought_content ?></textarea>
								<ul class="description" style="list-style: none">
									<li><span>{address}</span> - <?php esc_html_e( 'Customer\'s address', 'woorebought' ) ?></li>
									<li><span>{product_name}</span> - <?php esc_html_e( 'Product title', 'woorebought' ) ?></li>
									<li><span>{product_link}</span> - <?php esc_html_e( 'Product title + link', 'woorebought' ) ?></li>
									<li><span>{time_ago}</span> - <?php esc_html_e( 'Time after purchase', 'woorebought' ) ?></li>
								</ul>
							</div>
						</div>
						<!-- End Content purchased -->
						<div class="form-group row">
							<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Address List', 'woorebought' ) ?></label>
							<div class="col-sm-10">
								<?php $address_list = self::get_param( 'address_list', '' );?>
								<textarea name="<?php echo self::gen_param_name( 'address_list') ?>"><?php echo $address_list ?></textarea>
								<p class="description"><?php esc_html_e( 'If this field is set, Popup content will get random address from this list', 'woorebought' ) ?></p>
							</div>
						</div>
					</div>
					<div id="time" class="tab-pane fade">
						<div class="form-group row">
							<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Loop', 'woorebought' ) ?></label>
							<div class="col-sm-10">
								<div class="ui toggle checkbox">
									<input id="<?php echo self::gen_param_name( 'loop' ) ?>" type="checkbox" <?php checked( self::get_param( 'loop' ), 1 ) ?> tabindex="0" class="hidden" value="1" name="<?php echo self::gen_param_name( 'loop' ) ?>" />
									<label></label>
								</div>
							</div>
						</div>
						<!-- End Loop -->
						<div class="form-group row">
							<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Init Delay', 'woorebought' ) ?></label>
							<div class="col-sm-10">
								<input id="<?php echo self::gen_param_name( 'init_delay' ) ?>" type="number" tabindex="0" value="<?php echo self::get_param( 'init_delay', 5 ) ?>" name="<?php echo self::gen_param_name( 'init_delay' ) ?>" />
								<p class="description"><?php esc_html_e( 'When site loaded after this time popup will appear', 'woorebought' ) ?></p>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Display Time', 'woorebought' ) ?></label>
							<div class="col-sm-10">
								<input id="<?php echo self::gen_param_name( 'display_time' ) ?>" type="number" tabindex="0" value="<?php echo self::get_param( 'display_time', 5 ) ?>" name="<?php echo self::gen_param_name( 'display_time' ) ?>" />
								<p class="description"><?php esc_html_e( 'Popup will show in during this Time', 'woorebought' ) ?></p>
							</div>
						</div>
						<!-- End Display Time -->
						<div class="form-group row">
							<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Next Time', 'woorebought' ) ?></label>
							<div class="col-sm-10">
								<input id="<?php echo self::gen_param_name( 'next_time' ) ?>" type="number" tabindex="0" value="<?php echo self::get_param( 'next_time', 20 ) ?>" name="<?php echo self::gen_param_name( 'next_time' ) ?>" />
								<p class="description"><?php esc_html_e( 'The Time next popup will appear', 'woorebought' ) ?></p>
							</div>
						</div>
						<!-- End Next Time -->
						<div class="form-group row">
							<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Total', 'woorebought' ) ?></label>
							<div class="col-sm-10">
								<input id="<?php echo self::gen_param_name( 'total' ) ?>" type="number" tabindex="0" value="<?php echo self::get_param( 'total', 30 ) ?>" name="<?php echo self::gen_param_name( 'total' ) ?>" />
								<p class="description"><?php esc_html_e( 'Number of popups will appear', 'woorebought' ) ?></p>
							</div>
						</div>
						<!-- End Total-->
					</div>
					<div id="customstyle" class="tab-pane fade">
						<div class="form-group row">
							<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Popup Background', 'woorebought' ) ?></label>
							<div class="col-sm-10">
								<input type="text" value="<?php echo self::get_param( 'popup_bg', '#FFFFFF' ) ?>" name="<?php echo self::gen_param_name( 'popup_bg' ) ?>" class="color-field" data-default-color="#FFFFFF" />
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Popup Border Radius', 'woorebought' ) ?></label>
							<div class="col-sm-10">
								<input id="<?php echo self::gen_param_name( 'popup_border_radius' ) ?>" type="number" tabindex="0" value="<?php echo self::get_param( 'popup_border_radius', 5 ) ?>" name="<?php echo self::gen_param_name( 'popup_border_radius' ) ?>" />
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Text Color', 'woorebought' ) ?></label>
							<div class="col-sm-10">
								<input type="text" value="<?php echo self::get_param( 'popup_text_cl', '#000000' ) ?>" name="<?php echo self::gen_param_name( 'popup_text_cl' ) ?>" class="color-field" data-default-color="#000000" />
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Link Color', 'woorebought' ) ?></label>
							<div class="col-sm-10">
								<input type="text" value="<?php echo self::get_param( 'popup_link_cl', '#000000' ) ?>" name="<?php echo self::gen_param_name( 'popup_link_cl' ) ?>" class="color-field" data-default-color="#000000" />
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Time Color', 'woorebought' ) ?></label>
							<div class="col-sm-10">
								<input type="text" value="<?php echo self::get_param( 'popup_time_cl', '#000000' ) ?>" name="<?php echo self::gen_param_name( 'popup_time_cl' ) ?>" class="color-field" data-default-color="#000000" />
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Text Size', 'woorebought' ) ?></label>
							<div class="col-sm-10">
								<input id="<?php echo self::gen_param_name( 'popup_text_size' ) ?>" type="number" tabindex="0" value="<?php echo self::get_param( 'popup_text_size', 13 ) ?>" name="<?php echo self::gen_param_name( 'popup_text_size' ) ?>" />
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Link Size', 'woorebought' ) ?></label>
							<div class="col-sm-10">
								<input id="<?php echo self::gen_param_name( 'popup_link_size' ) ?>" type="number" tabindex="0" value="<?php echo self::get_param( 'popup_link_size', 16 ) ?>" name="<?php echo self::gen_param_name( 'popup_link_size' ) ?>" />
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Time Size', 'woorebought' ) ?></label>
							<div class="col-sm-10">
								<input id="<?php echo self::gen_param_name( 'popup_time_size' ) ?>" type="number" tabindex="0" value="<?php echo self::get_param( 'popup_time_size', 11 ) ?>" name="<?php echo self::gen_param_name( 'popup_time_size' ) ?>" />
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Custom CSS', 'woorebought' ) ?></label>
							<div class="col-sm-10">
								<?php $custom_css = self::get_param( 'custom_css', '' );?>
								<textarea name="<?php echo self::gen_param_name( 'custom_css') ?>"><?php echo $custom_css ?></textarea>
								<p class="description"><?php esc_html_e( 'You can add custom css for popup style', 'woorebought' ) ?></p>
							</div>
						</div>
					</div>
					<div id="assignpages" class="tab-pane fade">
						<div class="form-group row">
							<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Show in All Page', 'woorebought' ) ?></label>
							<div class="col-sm-10">
								<div class="ui toggle checkbox">
								<input id="<?php echo self::gen_param_name( 'all_page' ) ?>" type="checkbox" <?php checked( self::get_param( 'all_page' ), 1 ) ?> tabindex="0" class="hidden" value="1" name="<?php echo self::gen_param_name( 'all_page' ) ?>" />
								<label></label>
								</div>
							</div>
						</div>
						<div class="form-group row assign_pages">
							<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Assign Pages', 'woorebought' ) ?></label>
							<div class="col-sm-10">
								<?php
								$assign_pages = self::get_param( 'assign_pages', array() );
								?>
								<input type="hidden" name="assign_pages_val" class="selected_vals" value="<?php echo implode(",",$assign_pages);?>" />
								<select multiple="multiple" name="<?php echo self::gen_param_name( 'assign_pages', true ) ?>" class="ui fluid search dropdown multi">
									<option value=""><?php esc_attr_e( 'Please select pages', 'woorebought' ) ?></option>
									<?php $args = array(
										'sort_order' => 'asc',
										'sort_column' => 'post_title',
										'hierarchical' => 1,
										'exclude' => '',
										'include' => '',
										'meta_key' => '',
										'meta_value' => '',
										'authors' => '',
										'child_of' => 0,
										'parent' => -1,
										'exclude_tree' => '',
										'number' => '',
										'offset' => 0,
										'post_type' => 'page',
										'post_status' => 'publish'
									);
									$pages = get_pages($args);
									foreach ( $pages as $_page ) {?>
										<option value="<?php echo esc_html( $_page->ID ); ?>"><?php echo esc_html( $_page->post_title ); ?> (ID : <?php echo esc_html( $_page->ID ); ?>)</option>
									<?php
									}
								?>
								</select>
								<p class="description"><?php esc_html_e( 'The popup will show in pages selected !', 'woorebought' ) ?></p>
							</div>
						</div>
						<div class="form-group row exclude_pages">
							<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Exclude Pages', 'woorebought' ) ?></label>
							<div class="col-sm-10">
								<?php
								$exclude_pages = self::get_param( 'exclude_pages', array() );
								?>
								<input type="hidden" name="exclude_pages_val" class="selected_vals" value="<?php echo implode(",",$exclude_pages);?>" />
								<select multiple="multiple" name="<?php echo self::gen_param_name( 'exclude_pages', true ) ?>" class="ui fluid search dropdown multi">
									<option value=""><?php esc_attr_e( 'Please select exclude pages', 'woorebought' ) ?></option>
									<?php $args = array(
										'sort_order' => 'asc',
										'sort_column' => 'post_title',
										'hierarchical' => 1,
										'exclude' => '',
										'include' => '',
										'meta_key' => '',
										'meta_value' => '',
										'authors' => '',
										'child_of' => 0,
										'parent' => -1,
										'exclude_tree' => '',
										'number' => '',
										'offset' => 0,
										'post_type' => 'page',
										'post_status' => 'publish'
									);
									$pages = get_pages($args);
									foreach ( $pages as $_page ) {?>
										<option value="<?php echo esc_html( $_page->ID ); ?>"><?php echo esc_html( $_page->post_title ); ?> (ID : <?php echo esc_html( $_page->ID ); ?>)</option>
									<?php
									}
								?>
								</select>
								<p class="description"><?php esc_html_e( 'The popup will not show in pages selected !', 'woorebought' ) ?></p>
							</div>
						</div>
					</div>
				</div>
				<p style="position: relative; z-index: 99999; margin-bottom: 70px; display: inline-block;">
					<input type="submit" class="ui button primary" value=" <?php esc_html_e( 'Save', 'woorebought' ) ?> " />
				</p>
			</form>
		</div>
	<?php }
} ?>
