<?php

/*
 * Name: Jms Mega Menu
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // disable direct access
}

if ( ! class_exists( 'Adiva_Megamenu' ) ) :

/**
 * Main plugin class
 */
final class Adiva_Megamenu {


    /**
     * @var string
     */
    public $version = '1.0';
    public static function init() {
        $plugin = new self();
    }

    /**
     * Constructor
     *
     * @since 1.0
     */
    public function __construct() {
		$this->define_constants();
		$this->includes();
        if ( is_admin() ) {
			// Save data megamenu
			add_action( 'wp_ajax_jms_save_megamenu', array( __CLASS__, 'ajax_save_megamenu' ) );
			add_action( 'wp_ajax_jms_save_options', array($this, 'save_menu_meta_options') );
			global $pagenow;
			if ( $pagenow == 'nav-menus.php' ) {
				add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts'), 11 );
				add_action( 'admin_footer', array( __CLASS__, 'megamenu_modal' ) );
			}
        } else {
			add_filter( 'wp_nav_menu_args', array( $this, 'modify_nav_menu_args' ), 9999 );
			add_action( 'wp_enqueue_scripts', array($this, 'load_front_script') );
		}
    }

	function load_front_script(){
		wp_register_script( 'jmsmegamenu-script', ADIVA_ADDONS_URL . 'inc/megamenu/js/megamenu.min.js' , array( 'jquery' ));
		wp_enqueue_script('jmsmegamenu-script');
	}

    /**
     * Add custom actions to allow enqueuing scripts on specific pages
     *
     * @since 1.8.3
     */
    public function admin_enqueue_scripts( $hook ) {

        wp_enqueue_style( 'jms-mega-menu', ADIVA_ADDONS_URL . 'inc/megamenu/css/admin-style.css', array(), MEGAMENU_VERSION );
		wp_enqueue_media();
		// Enqueue jQuery UI.
		wp_enqueue_script( 'jquery-ui-dialog' );
		wp_enqueue_style( 'wp-jquery-ui-dialog' );
		wp_enqueue_script( 'jms-mega-menu', ADIVA_ADDONS_URL . 'inc/megamenu/js/megamenu-admin.js', array(), false, true );
		// Embed inline script.
		wp_localize_script( 'jms-mega-menu', 'jms_megamenu', self::localize_script() );
		// Embed data for all menus.
		wp_localize_script( 'jms-mega-menu', 'jms_data_megamenu', self::get_menu_data() );
		wp_localize_script( 'jms-mega-menu', 'jmsmegamenu_data_default', self::gen_data_default() );
    }


    /**
     * Define Mega Menu constants
     *
     * @since 1.0
     */
    private function define_constants() {
        define( 'MEGAMENU_VERSION',    $this->version );
    }

    /**
     * Load required classes
     *
     * @since 1.0
     */
    private function includes() {

        $autoload_is_disabled = defined( 'MEGAMENU_AUTOLOAD_CLASSES' ) && MEGAMENU_AUTOLOAD_CLASSES === false;

        if ( function_exists( "spl_autoload_register" ) && ! $autoload_is_disabled ) {

            // >= PHP 5.2 - Use auto loading
            if ( function_exists( "__autoload" ) ) {
                spl_autoload_register( "__autoload" );
            }

            spl_autoload_register( array( $this, 'autoload' ) );

        } else {

            // < PHP5.2 - Require all classes
            foreach ( $this->plugin_classes() as $id => $path ) {
                if ( is_readable( $path ) && ! class_exists( $id ) ) {
                    require_once $path;
                }
            }

        }

    }


    /**
     * Autoload classes to reduce memory consumption
     *
     * @since 1.0
     * @param string $class
     */
    public function autoload( $class ) {

        $classes = $this->plugin_classes();

        $class_name = strtolower( $class );
        if ( isset( $classes[ $class_name ] ) && is_readable( $classes[ $class_name ] ) ) {
            require_once $classes[ $class_name ];
        }

    }
	/**
     * All Mega Menu classes
     *
     * @since 1.0
     */
    private function plugin_classes() {
        return array(
            'adiva_megamenu_walker'    => ADIVA_ADDONS_PATH . 'inc/megamenu/walker.php',
			'adiva_megamenu_walkersub' => ADIVA_ADDONS_PATH . 'inc/megamenu/walkersub.php',
        );

    }

	/**
	 * Embed data for all menus.
	 *
	 * @return  array
	 */
	public static function get_menu_data() {
		// Get current menu.
		if ( isset( $_GET['menu'] ) && (int) $_GET['menu'] && is_nav_menu( $_GET['menu'] ) ) {
			$menu = ( int ) $_GET['menu'];
		} else {
			$menu = ( int ) get_user_option( 'nav_menu_recently_edited' );
		}

		// Get menu data.
		$data = get_option( 'jms_data_megamenu', '' );
		$data = is_string( $data ) ? json_decode( $data, true ) : $data;

		if ( $data && isset( $data[$menu] ) && $data[$menu] ) {

			// Set data product
			foreach( $data[$menu] as $key => $val ) {

				if( ! ( isset( $val['element_type'] ) && isset( $val['element_data'] ) && $val['element_data'] ) ) continue;

				$list_id = explode( ',', $val['element_data'] );
				$list_id = array_reverse( $list_id );

				if( $list_id ) {
					if( $val['element_type'] == 'element-products' ) {
						foreach( $list_id as $key_item => $val_item ) {
							$val_item = (int) $val_item;
							$product  = wc_get_product( $val_item );

							if( $val_item > 0 && $product ) {
								if( $product->post->post_status == 'publish' ) {
									$data[$menu][$key]['element_data_product'][] = array(
										'id' 	=> $val_item,
										'title' => $product->get_title(),
										'image' => $product->get_image( array( 50, 50) ),
										'price' => $product->get_price_html(),
									);
								}
							} else {
								// Delete product data
								unset( $list_id[$key_item] );
							}
						}
					} else if ( $val['element_type'] == 'element-categories' )  {
						foreach( $list_id as $key_item => $val_item ) {
							$val_item   = (int) $val_item;
							$categories = get_term( $val_item, 'product_cat', ARRAY_A );

							if( $val_item > 0 && $categories ) {
								$image = self::get_image_term_product_category( $val_item, array( 100, 100 ) );

								$data[$menu][$key]['element_data_categories'][] = array(
									'id' 	=> $val_item,
									'name'  => $categories['name'],
									'count' => $categories['count'],
									'image' => $image
								);
							} else {
								// Delete product data
								unset( $list_id[$key_item] );
							}
						}
					}
				}
				$data[$menu][$key]['element_data'] = implode( ',', $list_id );
			}
			return $data[$menu];
		}
		return array();
	}

	/**
	 * Data menu item settings default.
	 *
	 * @return  array
	 */
	public static function gen_data_default() {
		$data = array(
			'lvl_1' => array(
				'mega'              => '0',
				'show_title'		=> '1',
				'show_logo'			=> '0',
				'column_heading'	=> '0',
				'width_type'		=> 'fixed',
				'class'				=> '',
				'align'				=> 'left',
				'width'               => '1000',
				'submenu_layout'          => '2-2-2-2-2-2'
			),
			'lvl_2' => array(
				'show_title'   => '1',
				'column_heading'	=> '0',
			),
			'lvl_3' => array(
				'show_title'		=> '1',
			)
		);

		return $data;
	}
	public static function localize_script() {

		// Get current menu.
		if ( isset( $_GET['menu'] ) && (int) $_GET['menu'] && is_nav_menu( $_GET['menu'] ) ) {
			$menu = ( int ) $_GET['menu'];
		} else {
			$menu = ( int ) get_user_option( 'nav_menu_recently_edited' );

			if( ! is_nav_menu( $menu ) ) {
				$menu = 0;
			}
		}

		return array(
			'ajaxurl'   => admin_url( 'admin-ajax.php' ),
			'adminroot' => admin_url(),
			'rooturl'   => admin_url( 'index.php' ),
			'_nonce'    => wp_create_nonce( 'jms_megamenu_nonce_check' ),
			'menu_id' 	=> $menu
		);
	}
	/**
	 * Save mega menu data by ajax.
	 *
	 * @return  json
	 */
	public static function ajax_save_megamenu() {

		// Check nonce
		if ( ! isset( $_POST['_nonce'] ) || ! wp_verify_nonce( $_POST['_nonce'], 'jms_megamenu_nonce_check' ) ) {
			exit( json_encode( array( 'status' => 'false', 'message' => __( 'The nonce check wrong.', 'adiva-addons' ) ) ) );
		}

		// Check is menu
		if ( ! ( isset( $_POST['menu_id'] ) && ( $_POST['menu_id'] == 0 || is_nav_menu( $_POST['menu_id'] ) ) ) ) {
			exit( json_encode( array( 'status' => 'false', 'message' => __( 'Menu ID is empty.', 'adiva-addons' ) ) ) );
		}

		// Get current data.
		$cur_data = get_option( 'jms_data_megamenu', '' );
		$cur_data = is_string( $cur_data ) ? json_decode( $cur_data , true ) : $cur_data;

		$data_post = isset( $_POST['data'] ) ? wp_unslash ( $_POST['data'] ) : NULL;
		$data_menu_update = array();
		if( $data_post ) {
			if ( isset( $_POST['data_last_update'] ) && $_POST['data_last_update'] == 'ok' ) {
				foreach ( $data_post as $key => $val ) {
					$data_menu_update[$key] = $val;
				}
			} else {
				array_pop( $data_post );

				$list_id_updated = array();

				foreach ( $data_post as $key => $val ) {
					$data_menu_update[$key] = $val;
					$list_id_updated[] = $key;
				}

				exit( json_encode( array( 'status' => 'updating', 'list_id_updated' => $list_id_updated ) ) );
			};
		}

		if ( $data_menu_update ) {
			$cur_data[ $_POST['menu_id'] ] = $data_menu_update;
		} else {
			unset( $cur_data[ $_POST['menu_id'] ] );
		}

		update_option( 'jms_data_megamenu', $cur_data );
		exit( json_encode( array( 'status' => 'true' ) ) ) ;
	}


	public static function megamenu_modal() {
	?>
	<?php	echo '<scr' . 'ipt type="text/html" id="jms-modal-html">'; ?>
		<div class="jms-modal">
			<div class="jms-theme-overlay"></div>
			<div class="jms-dialog"></div>
		</div>
	<?php echo '</scr' . 'ipt>'; ?>
	<?php	echo '<scr' . 'ipt type="text/html" id="jms-template">'; ?>
		<div class="dialog-title"><span class="title"><% print( title_modal ); %></span><span class="close dashicons dashicons-no-alt"></span></div>
		<div class="jms-wrapper" data-id="<% print( id ); %>">
				<div class="wrapper-row">
					<div class="col-30">
						<label class="check-style <% if( level == 0 ) print( "dis-enable" ); %>">
							<% if( level == 0 ) { %>
							<div class="des-dis"><?php esc_html_e('This parameter is disabled for menu level 1.', 'adiva-addons'); ?></div>
							<% } %>
							<input <% if( data_item.show_title == 1 ) print("checked=\'checked\'"); %> class="chb-of show-title" type="checkbox" />
							<span class="label"><?php esc_html_e( 'Show Title', 'adiva-addons' ); ?></span>
						</label>
					</div>
					<div class="col-30">
						<label class="check-style <% if( level > 0 ) print( "dis-enable" ); %>">
							<% if( level > 0 ) { %>
							<div class="des-dis"><?php esc_html_e('This parameter is disabled for menu level >= 1.', 'adiva-addons'); ?></div>
							<% } %>
							<input <% if( data_item.show_logo == 1 ) print("checked=\'checked\'"); %> class="chb-of show-logo" type="checkbox" />
							<span class="label"><?php esc_html_e( 'Show Logo', 'adiva-addons' ); ?></span>
						</label>
					</div>
					<div class="col-40">
						<label><?php esc_html_e( 'Icon Class', 'adiva-addons' ); ?></label>
						<div class="menu-class-box">
							<input type="text" value="<%= data_item.icon_class %>" class="icon-class" />
						</div>
					</div>
				</div>
				<% if ( has_children ) { %>
				<div class="wrapper-row">
					<div class="col-50">
						<label><?php esc_html_e( 'SubMenu Class', 'adiva-addons' ); ?></label>
						<div class="menu-class-box">
							<input type="text" value="<%= data_item.submenu_class %>" class="submenu-class" />
						</div>
					</div>
					<div class="col-50">
						<label><?php esc_html_e( 'SubMenu Align', 'adiva-addons' ); ?></label>
						<div class="btn-align-group">
							<a title="Left" data-align="left" data-action="alignment" href="#" class="btn toolbox-action tool-align tool-align-left <% if( data_item.align == "left" ) print( "active" ); %>"><i class="dashicons-before icon-align-left"></i></a>
							<a title="Right" data-align="right" data-action="alignment" href="#" class="btn toolbox-action tool-align tool-align-right <% if( data_item.align == "right" ) print( "active" ); %>"><i class="dashicons-before icon-align-right"></i></a>
							<a title="Center" data-align="center" data-action="alignment" href="#" class="btn toolbox-action tool-align tool-align-center <% if( data_item.align == "center" ) print( "active" ); %>"><i class="dashicons-before icon-align-center"></i></a>
							<a title="Justify" data-align="justify" data-action="alignment" href="#" class="btn toolbox-action tool-align tool-align-justify <% if( data_item.align == "justify" ) print( "active" ); %>"><i class="dashicons-before icon-align-justify"></i></a>
						</div>
					</div>
				</div>
				<% } %>
				<div class="hr"></div>
			<% if ( level == 0 ) { %>
				<div class="wrapper-row mega-on-off <% if( ! has_children ) print( "dis-enable" ); %>">
					<div class="col-50">
						<% if( ! has_children ) { %>
							<div class="des-dis"><?php esc_html_e( 'This parameter is disabled because this menu item has no children.', 'adiva-addons' ) ?></div>
						<% } %>
						<label class="check-style">
							<input <% if( mega_active == 1 ) print("checked=\'checked\'"); %> class="mega-enable" type="checkbox" />
							<span class="label"><?php esc_html_e( 'Enable MegaMenu', 'adiva-addons' ); ?></span>
						</label>
					</div>
					<div class="col-50">
						<label><?php esc_html_e( 'Mega Type', 'adiva-addons' ); ?></label>
						<select class="mega-type">
							<option <% if( data_item.mega_type == "" ) print("selected=\'selected\'"); %> value=""><?php esc_html_e( 'Default', 'adiva-addons' ); ?></option>
							<option <% if( data_item.mega_type == "tab" ) print("selected=\'selected\'"); %> value="tab"><?php esc_html_e( 'Tab', 'adiva-addons' ); ?></option>
							<option <% if( data_item.mega_type == "accordion" ) print("selected=\'selected\'"); %> value="accordion"><?php esc_html_e( 'Accordion', 'adiva-addons' ); ?></option>
						</select>
					</div>
				</div>
				<div class="mega-options" <% if( mega_active != 1 ) print( "style=\'display:none;\'" ); %>>
					<div class="wrapper-row mega-option">
						<div class="col-50">
							<label><?php esc_html_e( 'SubMenu Width Type', 'adiva-addons' ); ?></label>
							<select class="width-type">
								<option <% if( data_item.width_type == "fullwidth" ) print("selected=\'selected\'"); %> value="fullwidth"><?php esc_html_e( 'Full Width', 'adiva-addons' ); ?></option>
								<option <% if( data_item.width_type == "fixed" ) print("selected=\'selected\'"); %> value="fixed"><?php esc_html_e( 'Fixed', 'adiva-addons' ); ?></option>
							</select>
						</label>
						</div>
						<div class="col-50 width-box" <% if( data_item.width_type != "fixed" ) print( "style=\'display:none;\'" ); %>>
							<label><?php esc_html_e( 'SubMenu Width', 'adiva-addons' ); ?></label>
							<div class="number-width-box">
								<input type="number" value="<%= data_item.width %>" class="number-width" />
								<span class="value-width">px</span>
							</div>
						</div>
					</div>
					<div class="hr"></div>
					<div class="wrapper-row mega-option mega-layout" <% if( data_item.mega_type ) print( "style=\'display:none;\'" ); %>>
						<label><?php esc_html_e( 'SubMenu Layout', 'adiva-addons' ); ?></label>
						<input type="text" value="<%= data_item.submenu_layout%>" class="submenu-layout" /> (<?php esc_html_e( 'Grid 12', 'adiva-addons' ); ?>)
						<div class="layout-wrapper">
							<div class="layout-grid">
							<% $.each( childs , function ( child_key, child_value ) { %>
								<div class="mega-column col-<%= cols[child_key] %>" data-col="<%= cols[child_key] %>">
								<div class="mega-column-inner"><span class="menu-title"><%= child_value %></span><span class="width-tool red-width"><i class="dashicons-before icon-reduce-width"></i></span><span class="width-tool inc-width"><i class="dashicons-before icon-increase-width"></i></span>
								</div>
								</div>
							<% } ) %>
							</div>
						</div>
					</div>
				</div>
			<% } %>
			<% if ( level == 1 ) { %>
				<div class="wrapper-row mega-option">
					<div class="col-50">
						<label class="check-style">
							<input <% if( data_item.column_heading == 1 ) print("checked=\'checked\'"); %> class="chb-of column-heading" type="checkbox" />
							<span class="label"><?php esc_html_e( 'Column Heading', 'adiva-addons' ); ?></span>
						</label>
					</div>
					<div class="col-50">
						<label><?php esc_html_e( 'Content Element', 'adiva-addons' ); ?></label>
						<select class="element-type">
							<option <% if( data_item.element_type == "" ) print("selected=\'selected\'"); %> value=""><?php esc_html_e( 'None', 'adiva-addons' ); ?></option>
							<option <% if( data_item.element_type == "html" ) print("selected=\'selected\'"); %> value="html"><?php esc_html_e( 'Html', 'adiva-addons' ); ?></option>
						</select>
					</div>
				</div>
				<div class="hr"></div>
				<div class="element-content">

				</div>
			<% } %>
		</div>
	<?php echo '</scr' . 'ipt>'; ?>
	<?php echo '<scr' . 'ipt type="text/html" id="jms-html-element">'; ?>
		<div class="jms-html-element">
			<div class="editor-wrapper">
				<?php
					echo wp_editor( '_WR_CONTENT_', 'jms-editor', array(
							'editor_class'  => 'jms-editor',
							'editor_height' => 200,
							'tinymce'       => array(
								'setup' => "function( editor ) {
									editor.on('change', function(e) {
										var content    = editor.getContent();
										var input_hide = jQuery( editor.targetElm ).closest( '.editor-wrapper' ).find( '.jms-editor-hidden' );
										input_hide.val( content ).trigger('change');
									} );
								}"
							),
						)
					);
				 ?>
				 <input type="hidden" class="jms-editor-hidden" value="">
			</div>
		</div>
	<?php echo '</scr' . 'ipt>'; ?>
	<?php }

	/**
     * Use the Mega Menu walker to output the menu
     * Resets all parameters used in the wp_nav_menu call
     * Wraps the menu in mega-menu IDs and classes
     *
     * @since 1.0
     * @param $args array
     * @return array
     */
    public function modify_nav_menu_args( $args ) {
        if ( ( isset( $args['menu'] ) && $args['menu'] ) || ( isset( $args['theme_location'] ) && $args['theme_location'] ) ) {
			if ( isset( $args['menu']->term_id ) ) {
				$id_menu = $args['menu']->term_id;
			} elseif ( $args['menu'] ) {
				$id_menu = $args['menu'];
			} elseif ( $args['theme_location'] ) {
                // Get location menu current
				$locations = get_nav_menu_locations();
				$id_menu   = $locations[$args['theme_location']];
			}

			if ( isset( $id_menu ) && is_nav_menu( $id_menu ) ) {
				$megamenu_options = get_option( 'jmsmegamenu_options' );
				$_options = is_string( $megamenu_options ) ? json_decode( $megamenu_options, true ) : $megamenu_options;
				// Define default arguments.
				$defaults = array(
					'items_wrap' => '<ul class="%2$s">%3$s</ul>',
				);
				return array_merge( $args, $defaults );

			} else {
				return $args;
			}
		} else {
			// Define default arguments.
			$defaults = array(
				'echo' => false,
			);

			return array_merge( $args, $defaults );
		}
    }
	/**
	 * Plug into WordPress's front-end.
	 *
	 * @return  void
	 */
	public static function get_data() {
		$data = get_option( 'jms_data_megamenu', '' );
		$data = is_string( $data ) ? json_decode( $data, true ) : $data;
		return $data;
	}

}

add_action( 'init', array( 'Adiva_Megamenu', 'init' ), 10 );

endif;
