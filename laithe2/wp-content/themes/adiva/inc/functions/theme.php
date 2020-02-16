<?php

if ( ! function_exists( 'adiva_setup' ) ) {
    function adiva_setup() {
	    load_theme_textdomain( 'adiva', get_template_directory() . '/languages' );

	    // Add default posts and comments RSS feed links to head.
	    add_theme_support( 'automatic-feed-links' );
	    add_theme_support( 'title-tag' );
	    add_theme_support( 'post-thumbnails' );
	    add_theme_support( 'woocommerce' );
        add_theme_support( 'custom-background' );
        add_theme_support( 'custom-header' );
        add_theme_support( 'custom-background' );
        // Add theme support for selective refresh for widgets.
        add_theme_support( 'customize-selective-refresh-widgets' );

	    // This theme uses wp_nav_menu() in one location.
	    register_nav_menus( array(
		    'primary-menu'  => esc_html__('Primary Menu', 'adiva'),
            'category-menu' => esc_html__('Vertical Category Menu', 'adiva'),
            'topbar-menu'   => esc_html__('Topbar Menu', 'adiva'),
            'footer-menu'   => esc_html__('Footer Menu', 'adiva'),
            'bottom-menu'   => esc_html__('Bottom Menu (Header 5)', 'adiva')
	    ) );

        add_theme_support( 'woocommerce', array(
            'gallery_thumbnail_image_width' => 'full',
        ) );

	    /*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	    add_theme_support( 'html5', array(
		   'search-form',
		   'comment-form',
		   'comment-list',
		   'gallery',
		   'caption',
	    ) );

        add_image_size( 'adiva-portfolio-square', 450, 450, 1 );

        add_editor_style(); // add the default style

        if ( ! isset( $content_width ) ) $content_width = 900;
   }
}
add_action( 'after_setup_theme', 'adiva_setup' );

/*
* [ Remove all style woocommerce. ] - - - - - - - - - - - - - - - - - - - -
*/
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/*
* [ Check variable Theme option ] - - - - - - - - - - - - - - - - - - - -
*/
if ( ! function_exists( 'adiva_get_option' ) ) {
	function adiva_get_option($name, $default = '') {
		global $adiva_option;
		if ( isset($adiva_option[$name]) ) {
			return $adiva_option[$name];
		}
		return $default;
	}
}

if( ! function_exists( 'adiva_get_config' ) ) {
	function adiva_get_config( $filename ) {
		$path = ADIVA_PATH . '/inc/admin/configs/' . $filename . '.php';
		if( file_exists( $path ) ) {
			return include $path;
		} else {
			return array();
		}
	}
}

/* 	Check WooCommerce is activated
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'adiva_woocommerce_activated' ) ) {
	function adiva_woocommerce_activated() {
		return class_exists( 'woocommerce' ) ? true : false;
	}
}

/*
* [ Register Widget Area. ] - - - - - - - - - - - - - - - - - - - -
*/
if ( ! function_exists( 'adiva_register_sidebars' ) ) {
	function adiva_register_sidebars() {
		register_sidebar(
			array(
				'name'          => esc_html__( 'Primary Sidebar', 'adiva' ),
				'id'            => 'primary-sidebar',
				'description'   => esc_html__( 'The Primary Sidebar', 'adiva' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h3 class="widgettitle">',
				'after_title'   => '</h3>',
			)
		);

        $showToggleSidebar  = adiva_get_option('show-toggle-sidebar', 0);
        if ( $showToggleSidebar ) {
            register_sidebar( array(
        		'name'          => esc_html__( 'Toggle Sidebar', 'adiva' ),
        		'id'            => 'toggle-sidebar',
        		'description'   => esc_html__( 'Add widgets here to appear in toggle sidebar.', 'adiva' ),
        		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        		'after_widget'  => '</aside>',
        		'before_title'  => '<h3 class="widgettitle">',
        		'after_title'   => '</h3>',
        	) );
        }

        if ( adiva_woocommerce_activated() ) {
            register_sidebar( array(
        		'name'          => esc_html__( 'WooCommerce Sidebar', 'adiva' ),
        		'id'            => 'shop-page',
        		'description'   => esc_html__( 'Add widgets here to appear in shop page sidebar.', 'adiva' ),
        		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        		'after_widget'  => '</aside>',
        		'before_title'  => '<h3 class="widgettitle">',
        		'after_title'   => '</h3>',
        	) );

            register_sidebar( array(
        		'name'          => esc_html__( 'WooCommerce Filter', 'adiva' ),
        		'id'            => 'woocommerce-filter-sidebar',
        		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        		'after_widget'  => '</aside>',
        		'before_title'  => '<h3 class="widgettitle">',
        		'after_title'   => '</h3>',
        	) );

            register_sidebar( array(
        		'name'          => esc_html__( 'WooCommerce Single Product Sidebar', 'adiva' ),
        		'id'            => 'woocommerce-single-product-sidebar',
        		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        		'after_widget'  => '</aside>',
        		'before_title'  => '<h3 class="widgettitle">',
        		'after_title'   => '</h3>',
        	) );
        }

        $footer_column = adiva_get_option('footer-column', 4);
        if ( isset($footer_column) ) {
            for ( $i = 1, $n = $footer_column; $i <= $n; $i++ ) {
    			register_sidebar(
    				array(
    					'name'          => esc_html__( 'Footer Area #', 'adiva' ) . $i,
    					'id'            => 'footer-' . $i,
    					'description'   => sprintf( esc_html__( 'The #%s column in footer area', 'adiva' ), $i ),
    					'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    					'after_widget'  => '</aside>',
    					'before_title'  => '<h3 class="widgettitle">',
    					'after_title'   => '</h3>',
    				)
    			);
    		}
        }


	}
}
add_action( 'widgets_init', 'adiva_register_sidebars' );

// **********************************************************************//
// ! Text to one-line string
// **********************************************************************//
if( ! function_exists( 'adiva_format_css')) {
	function adiva_format_css( $str ) {
		return trim(preg_replace("/('|\"|\r?\n)/", '', $str));
	}
}

/*
* [ Add a pingback url auto-discovery header for singularly identifiable articles. ] - - - - - - - - - - - - - - - - - - - -
*/
function adiva_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'adiva_pingback_header' );

/*
* [ Adds custom classes to the array of body classes. ] - - - - - - - - - - - - - - - - - - - -
*/

if ( !function_exists('adiva_body_class') ) {
    function adiva_body_class( $classes ) {
        global $post;

        // Get page options
        $options = get_post_meta( get_the_ID(), '_custom_page_options', true );

        $site_width = adiva_get_option('site-width', 'fullwidth');

        if ( isset( $options['page-width'] ) && $options['page-width'] != 'inherit' ) $site_width = $options['page-width'];
        $classes[] = 'wrapper-' . $site_width;

    	// Adds a class of group-blog to blogs with more than 1 published author.
    	if ( is_multi_author() ) {
    		$classes[] = 'group-blog';
    	}

    	// Adds a class of hfeed to non-singular pages.
    	if ( ! is_singular() ) {
    		$classes[] = 'hfeed';
    	}

        $stickyHeader = adiva_get_option('sticky-header', 0);
        if ( isset($stickyHeader) && $stickyHeader == 1 ) {
    		$classes[] = 'has-sticky-header';
    	}

        $cart_style = adiva_get_option('wc-add-to-cart-style', 'alert');

        if( isset($_GET['cart_design']) && $_GET['cart_design'] != '' ) {
            $cart_style = $_GET['cart_design'];
        }

        if ( isset($cart_style) && $cart_style != 'alert' ) {
    		$classes[] = 'add-to-cart-style-sidebar';
    	} else {
            $classes[] = 'add-to-cart-style-alert';
        }

        // Enable ajax shop
        $ajax_shop = adiva_get_option( 'wc-ajax-shop' );
        if( $ajax_shop ) {
            $classes[] = 'adiva-ajax-shop-on';
        } else {
            $classes[] = 'adiva-ajax-shop-off';
        }

        $layout = adiva_get_option( 'header-layout', 1 );

        if ( isset( $options['page-header'] ) && $options['page-header'] != '' ) {
            $layout = $options['page-header'];
        }

        if ( isset( $options['page-header'] ) && $options['page-header'] == '7' ) {
            $layout = '5';
        }

        if ( isset( $layout ) && $layout == 5 ) {
            $classes[] = 'has-left-fixed-menu';
        }

        // Check if under construction page is enabled
        $maintenance_mode = adiva_get_option('maintenance-mode', 0);

        if ( isset($_GET['maintenance']) && $_GET['maintenance'] != '' ) {
            $maintenance_mode = $_GET['maintenance'];
        }

        if ( isset($maintenance_mode) && $maintenance_mode == 1 ) {
            if ( ! is_user_logged_in() ) {
                $classes[] = 'offline';
            }
    	}

    	return $classes;
    }
    add_filter( 'body_class', 'adiva_body_class' );
}


/**
 * Redirect to under construction page
 */
if ( ! function_exists( 'adiva_offline' ) ) {
	function adiva_offline() {
		$maintenance_mode = adiva_get_option('maintenance-mode', 0);

        if ( isset($_GET['maintenance']) && $_GET['maintenance'] != '' ) {
            $maintenance_mode = $_GET['maintenance'];
        }

		// Check if under construction page is enabled
		if ( $maintenance_mode ) {

			if ( ! is_feed() ) {
				// Check if user is not logged in
				if ( ! is_user_logged_in() ) {
					// Load under construction page
					include get_template_directory() . '/maintenance.php';
					exit;
				}
			}

			// Check if user is logged in
			if ( is_user_logged_in() ) {
				global $current_user;

				// Get user role
				wp_get_current_user();

				$loggedInUserID = $current_user->ID;
				$userData = get_userdata( $loggedInUserID );

				// If user role is not 'administrator' then redirect to under construction page
				if ( 'administrator' != $userData->roles[0] ) {
					if ( ! is_feed() ) {
						include get_template_directory() . '/maintenance.php';
						exit;
					}
				}
			}
		}
	}
}
add_action( 'template_redirect', 'adiva_offline' );

if ( !function_exists('adiva_customize_register') ) {
    function adiva_customize_register() {
        global $wp_customize;
        $wp_customize->remove_section( 'header_image' );  //Modify this line as needed
    }
    add_action( 'customize_register', 'adiva_customize_register', 11 );
}

/*  Custom Javascript
/* --------------------------------------------------------------------- */
if ( ! function_exists('adiva_settings_js') ) {
	function adiva_settings_js() {
        $custom_js       = adiva_get_option( 'custom_js', '' );
        $js_ready        = adiva_get_option( 'js_ready', '' );
        $smooth_scroll   = adiva_get_option( 'browser-smooth-scroll', 0 );

		ob_start();

        /*  Smooth Scroll
        /* --------------------------------------------------------------------- */

        if( $smooth_scroll == 1 ) : ?>
            jQuery(document).ready(function($) {
                $( 'html' ).niceScroll();
            });
        <?php endif;

        if( ! empty( $custom_js ) || ! empty( $js_ready ) ): ?>
            <?php if( ! empty( $custom_js ) ): ?>
                <?php echo ($custom_js); ?>
            <?php endif; ?>
            <?php if( ! empty( $js_ready ) ): ?>
                jQuery(document).ready(function() {
                    <?php echo ($js_ready); ?>
                });
            <?php endif; ?>
        <?php endif;

        return ob_get_clean();
	}
}

if ( ! function_exists('adiva_plugin_active') ) {
    function adiva_plugin_active( $plg_class = '', $plg_func = '' ) {
        if($plg_class) return class_exists($plg_class);
        if($plg_func) return function_exists($plg_func);
        return false;
    }
}
