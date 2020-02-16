<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class JmsAjaxSearch_Params {
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
		if ( ! isset( $_POST['jmsajaxsearch_params'] ) ) {
			return false;
		}
		update_option( '_jms_ajax_search_prefix', substr( md5( date( "YmdHis" ) ), 0, 10 ) );
		update_option( 'jmsajaxsearch_params', $_POST['jmsajaxsearch_params'] );
		if(isset( $_POST['jmsajaxsearch_params']))
			self::add_message( __( 'Your settings have been saved.', 'jmsajaxsearch' ) );
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

	protected static function gen_param_name( $_param, $multi = false ) {
		if ( $_param ) {
			if ( $multi ) {
				return 'jmsajaxsearch_params[' . $_param . '][]';
			} else {
				return 'jmsajaxsearch_params[' . $_param . ']';
			}
		} else {
			return '';
		}
	}

	public static function get_param( $_param, $default = '' ) {
		$params = get_option( 'jmsajaxsearch_params', false );
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
		self::$params = get_option( 'jmsajaxsearch_params', array() );
		?>
		<div class="wrap jmsajaxsearch-setting">
			<h2><?php esc_attr_e( 'Ajax Search Settings', 'jmsajaxsearch' ) ?></h2>
			<?php self::show_messages(); ?>
			<ul class="nav nav-tabs">
				  <li class="active"><a data-toggle="tab" href="#general"><?php esc_html_e( 'General', 'jmsajaxsearch' ) ?></a></li>
				  <li><a data-toggle="tab" href="#pages"><?php esc_html_e( 'Pages', 'jmsajaxsearch' ) ?></a></li>
				  <li><a data-toggle="tab" href="#posts"><?php esc_html_e( 'Post', 'jmsajaxsearch' ) ?></a></li>
				  <li><a data-toggle="tab" href="#products"><?php esc_html_e( 'Products', 'jmsajaxsearch' ) ?></a></li>
				  <li><a data-toggle="tab" href="#customstyle"><?php esc_html_e( 'Custom Style', 'jmsajaxsearch' ) ?></a></li>
				</ul>
			<div class="setting-wrapper">
				<form method="post" action="" class="ui form">
					<div class="tab-content">
						<div id="general" class="tab-pane fade in active">
							<div class="form-group row">
								<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Time to show', 'jmsajaxsearch' ) ?></label>
								<div class="col-sm-10">
									<div class="fields">
										<div class="twelve field">
											<input type="number" name="<?php echo self::gen_param_name( 'time_to_show' ) ?>" value="<?php echo self::get_param( 'time_to_show', '5' ) ?>" />
										</div>
									</div>
									<p class="description"><?php esc_html_e( 'Time delay after enter text. (1 seconds = 1000)', 'jmsajaxsearch' ) ?></p>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Number of Item show', 'jmsajaxsearch' ) ?></label>
								<div class="col-sm-10">
									<div class="fields">
										<div class="twelve field">
											<input type="number" name="<?php echo self::gen_param_name( 'items_show' ) ?>" value="<?php echo self::get_param( 'items_show', '5' ) ?>" />
										</div>
									</div>
									<p class="description"><?php esc_html_e( 'Maximum number of Items to show.', 'jmsajaxsearch' ) ?></p>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Seach Source', 'jmsajaxsearch' ) ?></label>
								<div class="col-sm-10">
									<div class="fields">
										<?php
											$search_type = self::get_param( 'search_type', 'product' );
										?>

										<div class="twelve field">
											<input type="radio" name="<?php echo self::gen_param_name( 'search_type' ); ?>" value = "page"
											<?php
												if ($search_type == 'page') {
												echo 'checked';
												}
											?> >
											<?php esc_html_e( 'page', 'jmsajaxsearch' ) ?>
										</div>
										<div class="twelve field">
											<input type="radio" name="<?php echo self::gen_param_name( 'search_type' ); ?>" value = "post"
											<?php
												if ($search_type == 'post') {
												echo 'checked';
												}
											?> >
											<?php esc_html_e( 'post', 'jmsajaxsearch' ) ?>
										</div>
										<div class="twelve field">
											<input type="radio" name="<?php echo self::gen_param_name( 'search_type' ); ?>" value = "product" <?php
												if ($search_type == 'product') {
												echo 'checked';
												}
											?> >
											<?php esc_html_e( 'product', 'jmsajaxsearch' ) ?>
										</div>
									</div>
									<p class="description"><?php esc_html_e( 'Seach Source.', 'jmsajaxsearch' ) ?></p>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Keyword Character number', 'jmsajaxsearch' ) ?></label>
								<div class="col-sm-10">
									<div class="fields">
										<div class="twelve field">
											<input type="number" name="<?php echo self::gen_param_name( 'key_word_lenght' ) ?>" value="<?php echo self::get_param( 'key_word_lenght', '3' ) ?>" />
										</div>
									</div>
									<p class="description"><?php esc_html_e( 'Minimun number of character require to search.', 'jmsajaxsearch' ) ?></p>
								</div>
							</div>
						</div>
						<div id="pages" class="tab-pane fade in">
							<div class="form-group row">
								<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Show Image', 'jmsajaxsearch' ) ?></label>
								<div class="col-sm-10">
									<div class="ui toggle checkbox">
										<input id="<?php echo self::gen_param_name( 'page_image_show' ) ?>" type="checkbox" <?php checked( self::get_param( 'page_image_show' ), 1 ) ?> tabindex="0" class="hidden" value="1" name="<?php echo self::gen_param_name( 'page_image_show' ) ?>" />
											<label></label>
									</div>
									<p class="description"><?php esc_html_e( 'Show or hide featured imgae Page.', 'jmsajaxsearch' ) ?></p>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Show date', 'jmsajaxsearch' ) ?></label>
								<div class="col-sm-10">
									<div class="ui toggle checkbox">
										<input id="<?php echo self::gen_param_name( 'page_date_show' ) ?>" type="checkbox" <?php checked( self::get_param( 'page_date_show' ), 1 ) ?> tabindex="0" class="hidden" value="1" name="<?php echo self::gen_param_name( 'page_date_show' ) ?>" />
											<label></label>
									</div>
									<p class="description"><?php esc_html_e( 'Show or hide date Page.', 'jmsajaxsearch' ) ?></p>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Show read more', 'jmsajaxsearch' ) ?></label>
								<div class="col-sm-10">
									<div class="ui toggle checkbox">
										<input id="<?php echo self::gen_param_name( 'page_read_more_show' ) ?>" type="checkbox" <?php checked( self::get_param( 'page_read_more_show' ), 1 ) ?> tabindex="0" class="hidden" value="1" name="<?php echo self::gen_param_name( 'page_read_more_show' ) ?>" />
											<label></label>
									</div>
									<p class="description"><?php esc_html_e( 'Show or read more.', 'jmsajaxsearch' ) ?></p>
								</div>
							</div>
						</div>
						<div id="posts" class="tab-pane fade in">
							<div class="form-group row src_field from_categories">
								<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Select Categories', 'jmsajaxsearch' ) ?></label>
								<div class="col-sm-10">
									<?php
									$post_cates = self::get_param( 'post_categories', array() );
									?>
									<input type="hidden" name="select_categories_val" class="selected_vals" value="<?php echo implode(",",$post_cates);?>" />
									<select name="<?php echo self::gen_param_name( 'post_categories', true ) ?>" class="ui fluid search dropdown multi" multiple="">
										<option value=""><?php esc_attr_e( 'Please select categories', 'jmsajaxsearch' ) ?></option>
										<?php
												$post_categories = get_terms(
													array(
														'taxonomy' => 'category'
													)
												);
												if ( count( $post_categories ) ) {
													foreach ( $post_categories as $post_category ) { ?>
														<option value="<?php echo esc_attr( $post_category->term_id ) ?>"><?php echo esc_html( $post_category->name ) ?> (ID :<?php echo esc_attr( $post_category->term_id ) ?>)</option>
														<?php
														// get children product categories
														$subcats = get_categories( array( 'hide_empty' => 0, 'parent' => $post_category->term_id, 'taxonomy' => 'category' ) );
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
									<p class="description"><?php esc_html_e( 'Input category title to see suggestions.', 'jmsajaxsearch' ) ?></p>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Show post image', 'jmsajaxsearch' ) ?></label>
								<div class="col-sm-10">
									<div class="ui toggle checkbox">
										<input id="<?php echo self::gen_param_name( 'post_image_show' ) ?>" type="checkbox" <?php checked( self::get_param( 'post_image_show' ), 1 ) ?> tabindex="0" class="hidden" value="1" name="<?php echo self::gen_param_name( 'post_image_show' ) ?>" />
											<label></label>
									</div>
									<p class="description"><?php esc_html_e( 'Show or hide featured image.', 'jmsajaxsearch' ) ?></p>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Show author', 'jmsajaxsearch' ) ?></label>
								<div class="col-sm-10">
									<div class="ui toggle checkbox">
										<input id="<?php echo self::gen_param_name( 'post_author_show' ) ?>" type="checkbox" <?php checked( self::get_param( 'post_author_show' ), 1 ) ?> tabindex="0" class="hidden" value="1" name="<?php echo self::gen_param_name( 'post_author_show' ) ?>" />
											<label></label>
									</div>
									<p class="description"><?php esc_html_e( 'Show or hide author.', 'jmsajaxsearch' ) ?></p>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Show date', 'jmsajaxsearch' ) ?></label>
								<div class="col-sm-10">
									<div class="ui toggle checkbox">
										<input id="<?php echo self::gen_param_name( 'post_date_show' ) ?>" type="checkbox" <?php checked( self::get_param( 'post_date_show' ), 1 ) ?> tabindex="0" class="hidden" value="1" name="<?php echo self::gen_param_name( 'post_date_show' ) ?>" />
											<label></label>
									</div>
									<p class="description"><?php esc_html_e( 'Show or hide date.', 'jmsajaxsearch' ) ?></p>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Show read more', 'jmsajaxsearch' ) ?></label>
								<div class="col-sm-10">
									<div class="ui toggle checkbox">
										<input id="<?php echo self::gen_param_name( 'post_read_more_show' ) ?>" type="checkbox" <?php checked( self::get_param( 'post_read_more_show' ), 1 ) ?> tabindex="0" class="hidden" value="1" name="<?php echo self::gen_param_name( 'post_read_more_show' ) ?>" />
											<label></label>
									</div>
									<p class="description"><?php esc_html_e( 'Show or hide read more.', 'jmsajaxsearch' ) ?></p>
								</div>
							</div>
						</div>
						<div id="products" class="tab-pane fade in">
							<div class="form-group row src_field from_categories">
								<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Select Categories', 'jmsajaxsearch' ) ?></label>
								<div class="col-sm-10">
									<?php
									$product_cates = self::get_param( 'product_categories', array() );
									?>
									<input type="hidden" name="select_categories_val" class="selected_vals" value="<?php echo implode(",",$product_cates);?>" />
									<select name="<?php echo self::gen_param_name( 'product_categories', true ) ?>" class="ui fluid search dropdown multi" multiple="">
										<option value=""><?php esc_attr_e( 'Please select categories', 'jmsajaxsearch' ) ?></option>
										<?php
												$product_categories = get_terms(
													array(
														'taxonomy' => 'product_cat'
													)
												);
												if ( count( $product_categories ) ) {
													foreach ( $product_categories as $category ) { ?>
														<option value="<?php echo esc_attr( $category->term_id ) ?>"><?php echo esc_html( $category->name ) ?> (ID :<?php echo esc_attr( $category->term_id ) ?>)</option>
													<?php
													}
												}
											?>
									</select>
									<p class="description"><?php esc_html_e( 'Input category title to see suggestions.', 'jmsajaxsearch' ) ?></p>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Show short description', 'jmsajaxsearch' ) ?></label>
								<div class="col-sm-10">
									<div class="ui toggle checkbox">
										<input id="<?php echo self::gen_param_name( 'product_short_desc_show' ) ?>" type="checkbox" <?php checked( self::get_param( 'product_short_desc_show' ), 1 ) ?> tabindex="0" class="hidden" value="1" name="<?php echo self::gen_param_name( 'product_short_desc_show' ) ?>" />
											<label></label>
									</div>
									<p class="description"><?php esc_html_e( 'Show or hide Product short description.', 'jmsajaxsearch' ) ?></p>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Show Image', 'jmsajaxsearch' ) ?></label>
								<div class="col-sm-10">
									<div class="ui toggle checkbox">
										<input id="<?php echo self::gen_param_name( 'product_image_show' ) ?>" type="checkbox" <?php checked( self::get_param( 'product_image_show' ), 1 ) ?> tabindex="0" class="hidden" value="1" name="<?php echo self::gen_param_name( 'product_image_show' ) ?>" />
											<label></label>
									</div>
									<p class="description"><?php esc_html_e( 'Show or hide imgae Product.', 'jmsajaxsearch' ) ?></p>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Show Price', 'jmsajaxsearch' ) ?></label>
								<div class="col-sm-10">
									<div class="ui toggle checkbox">
										<input id="<?php echo self::gen_param_name( 'product_price_show' ) ?>" type="checkbox" <?php checked( self::get_param( 'product_price_show' ), 1 ) ?> tabindex="0" class="hidden" value="1" name="<?php echo self::gen_param_name( 'product_price_show' ) ?>" />
											<label></label>
									</div>
									<p class="description"><?php esc_html_e( 'Show or hide price Product.', 'jmsajaxsearch' ) ?></p>
								</div>
							</div>
						</div>
						<div id="customstyle" class="tab-pane fade">
							<div class="form-group row">
								<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Popup Background', 'jmsajaxsearch' ) ?></label>
								<div class="col-sm-10">
									<input type="text" value="<?php echo self::get_param( 'popup_bg', '#FFFFFF' ) ?>" name="<?php echo self::gen_param_name( 'popup_bg' ) ?>" class="color-field" data-default-color="#FFFFFF" />
								</div>
							</div>
						<div class="form-group row">
							<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Popup Border Radius', 'jmsajaxsearch' ) ?></label>
							<div class="col-sm-10">
								<input id="<?php echo self::gen_param_name( 'popup_border_radius' ) ?>" type="number" tabindex="0" value="<?php echo self::get_param( 'popup_border_radius', 5 ) ?>" name="<?php echo self::gen_param_name( 'popup_border_radius' ) ?>" />
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Text Color', 'jmsajaxsearch' ) ?></label>
							<div class="col-sm-10">
								<input type="text" value="<?php echo self::get_param( 'popup_text_cl', '#000000' ) ?>" name="<?php echo self::gen_param_name( 'popup_text_cl' ) ?>" class="color-field" data-default-color="#000000" />
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Link Color', 'jmsajaxsearch' ) ?></label>
							<div class="col-sm-10">
								<input type="text" value="<?php echo self::get_param( 'popup_link_cl', '#000000' ) ?>" name="<?php echo self::gen_param_name( 'popup_link_cl' ) ?>" class="color-field" data-default-color="#000000" />
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Text Size', 'jmsajaxsearch' ) ?></label>
							<div class="col-sm-10">
								<input id="<?php echo self::gen_param_name( 'popup_text_size' ) ?>" type="number" tabindex="0" value="<?php echo self::get_param( 'popup_text_size', 13 ) ?>" name="<?php echo self::gen_param_name( 'popup_text_size' ) ?>" />
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Link Size', 'jmsajaxsearch' ) ?></label>
							<div class="col-sm-10">
								<input id="<?php echo self::gen_param_name( 'popup_link_size' ) ?>" type="number" tabindex="0" value="<?php echo self::get_param( 'popup_link_size', 16 ) ?>" name="<?php echo self::gen_param_name( 'popup_link_size' ) ?>" />
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-2 col-form-label"><?php esc_html_e( 'Custom CSS', 'jmsajaxsearch' ) ?></label>
							<div class="col-sm-10">
								<?php $custom_css = self::get_param( 'custom_css', '' );?>
								<textarea name="<?php echo self::gen_param_name( 'custom_css') ?>"><?php echo $custom_css ?></textarea>
								<p class="description"><?php esc_html_e( 'You can add custom css for popup style', 'jmsajaxsearch' ) ?></p>
							</div>
						</div>
					</div>
					</div>

					<p style="position: relative; z-index: 99999; margin-bottom: 70px; display: inline-block;">
						<input type="submit" class="btn btn-primary" value=" <?php esc_html_e( 'Save', 'jmsajaxsearch' ) ?> " />
					</p>
				</form>
			</div>
		</div>
		<?php }
} ?>
