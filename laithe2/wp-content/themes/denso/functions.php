<?php
/**
 * denso functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @link https://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * {@link https://codex.wordpress.org/Plugin_API}
 *
 * @package WordPress
 * @subpackage Denso
 * @since Denso 2.16
 */

define( 'DENSO_THEME_VERSION', '2.16' );
define( 'DENSO_DEMO_MODE', false );

if ( ! isset( $content_width ) ) {
	$content_width = 660;
}

if ( ! function_exists( 'denso_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 * @since Denso 1.0
 */
function denso_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on denso, use a find and replace
	 * to change 'denso' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'denso', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * See: https://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 825, 510, true );

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'denso' ),
		'vertical_menu'  => esc_html__( 'Vertical Menu', 'denso' ),
		'topmenu'  => esc_html__( 'Top Menu', 'denso' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	) );

	add_theme_support( "woocommerce" );
	/*
	 * Enable support for Post Formats.
	 *
	 * See: https://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link', 'gallery', 'status', 'audio', 'chat'
	) );

	$color_scheme  = denso_get_color_scheme();
	$default_color = trim( $color_scheme[0], '#' );

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'denso_custom_background_args', array(
		'default-color'      => $default_color,
		'default-attachment' => 'fixed',
	) ) );

	// Add support for Block Styles.
	add_theme_support( 'wp-block-styles' );

	add_theme_support( 'responsive-embeds' );
	
	// Add support for full and wide align images.
	add_theme_support( 'align-wide' );

	// Add support for editor styles.
	add_theme_support( 'editor-styles' );

	// Enqueue editor styles.
	add_editor_style( array( 'css/style-editor.css', denso_fonts_url() ) );
	
	denso_get_load_plugins();
}
endif; // denso_setup
add_action( 'after_setup_theme', 'denso_setup' );


/**
 * Load Google Front
 */
function denso_fonts_url() {
    $fonts_url = '';

    /* Translators: If there are characters in your language that are not
    * supported by Montserrat, translate this to 'off'. Do not translate
    * into your own language.
    */
    $libreFranklin = _x( 'on', 'libreFranklin font: on or off', 'denso' );
 
    if ( 'off' !== $libreFranklin) {
        $font_families = array();
 
        if ( 'off' !== $libreFranklin ) {
            $font_families[] = 'Libre+Franklin:200:400,700';
        }
 
        $query_args = array(
            'family' => ( implode( '|', $font_families ) ),
            'subset' => urlencode( 'latin,latin-ext' ),
        );
 		
 		$protocol = is_ssl() ? 'https:' : 'http:';
        $fonts_url = add_query_arg( $query_args, $protocol .'//fonts.googleapis.com/css' );
    }
 
    return esc_url_raw( $fonts_url );
}

function denso_full_fonts_url() {  
	$protocol = is_ssl() ? 'https:' : 'http:';
	wp_enqueue_style( 'denso-theme-fonts', denso_fonts_url(), array(), null );
}
add_action('wp_enqueue_scripts', 'denso_full_fonts_url');

/**
 * JavaScript Detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @since Denso 1.1
 */
function denso_javascript_detection() {
	wp_add_inline_script( 'denso-typekit', "(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);" );
}
add_action( 'wp_enqueue_scripts', 'denso_javascript_detection', 0 );

/**
 * Enqueue scripts and styles.
 *
 * @since Denso 1.0
 */
function denso_scripts() {
	// Load our main stylesheet.
	$css_folder = denso_get_css_folder();
	$js_folder = denso_get_js_folder();
	$min = denso_get_asset_min();
	// load bootstrap style
	if( is_rtl() ){
		wp_enqueue_style( 'bootstrap', $css_folder . '/bootstrap-rtl'.$min.'.css', array(), '3.2.0' );
	}else{
		wp_enqueue_style( 'bootstrap', $css_folder . '/bootstrap'.$min.'.css', array(), '3.2.0' );
	}
	$css_path = $css_folder . '/template'.$min.'.css';
	wp_enqueue_style( 'denso-template', $css_path, array(), '3.2' );
	wp_enqueue_style( 'denso-style', get_template_directory_uri() . '/style.css', array(), '3.2' );
	//load font awesome
	wp_enqueue_style( 'font-awesome', $css_folder . '/font-awesome'.$min.'.css', array(), '4.5.0' );

	//load font monia
	wp_enqueue_style( 'font-monia', $css_folder . '/font-monia'.$min.'.css', array(), '1.8.0' );

	// load animate version 3.5.0
	wp_enqueue_style( 'animate', $css_folder . '/animate'.$min.'.css', array(), '3.5.0' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	wp_enqueue_style( 'perfect-scrollbar', $css_folder . '/perfect-scrollbar'.$min.'.css', array(), '2.3.2' );

	wp_enqueue_script( 'bootstrap', $js_folder . '/bootstrap'.$min.'.js', array( 'jquery' ), '20150330', true );
	// slick
	wp_enqueue_style( 'slick', $css_folder . '/slick'.$min.'.css', array(), '1.6.0' );
	wp_enqueue_script( 'slick', $js_folder . '/slick'.$min.'.js', array( 'jquery' ), '1.6.0', true );
	
	wp_enqueue_script( 'perfect-scrollbar-jquery', $js_folder . '/perfect-scrollbar.jquery'.$min.'.js', array( 'jquery' ), '2.0.0', true );

	wp_enqueue_script( 'jquery-magnific-popup', $js_folder . '/magnific/jquery.magnific-popup'.$min.'.js', array( 'jquery' ), '1.1.0', true );
	wp_enqueue_style( 'magnific-popup', $js_folder . '/magnific/magnific-popup'.$min.'.css', array(), '1.1.0' );
	
	// lazyload image
	wp_enqueue_script( 'jquery-unveil', $js_folder . '/jquery.unveil'.$min.'.js', array( 'jquery' ), '20150330', true );

	wp_register_script( 'denso-functions', $js_folder . '/functions'.$min.'.js', array( 'jquery' ), '20150330', true );
	wp_localize_script( 'denso-functions', 'denso_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
	wp_enqueue_script( 'denso-functions' );

	if ( denso_get_config('header_js') != "" ) {
		wp_add_inline_script( 'denso-functions', denso_get_config('header_js') );
	}
}
add_action( 'wp_enqueue_scripts', 'denso_scripts', 100 );

// style compare
function denso_apus_head_compare_scripts() {
	$css_folder = denso_get_css_folder();
	$min = denso_get_asset_min();
	echo '<link rel="stylesheet" href="'. $css_folder.'/compare'.$min.'.css"/>';
}
add_action('wp_head', 'denso_apus_head_compare_scripts');

function denso_footer_scripts() {
	if ( denso_get_config('footer_js') != "" ) {
		wp_add_inline_script( 'denso-functions', denso_get_config('footer_js') );
	}
}
add_action('wp_enqueue_scripts', 'denso_footer_scripts');
/**
 * Display descriptions in main navigation.
 *
 * @since Denso 1.0
 *
 * @param string  $item_output The menu item output.
 * @param WP_Post $item        Menu item object.
 * @param int     $depth       Depth of the menu.
 * @param array   $args        wp_nav_menu() arguments.
 * @return string Menu item with possible description.
 */
function denso_nav_description( $item_output, $item, $depth, $args ) {
	if ( 'primary' == $args->theme_location && $item->description ) {
		$item_output = str_replace( $args->link_after . '</a>', '<div class="menu-item-description">' . $item->description . '</div>' . $args->link_after . '</a>', $item_output );
	}

	return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'denso_nav_description', 10, 4 );

/**
 * Add a `screen-reader-text` class to the search form's submit button.
 *
 * @since Denso 1.0
 *
 * @param string $html Search form HTML.
 * @return string Modified search form HTML.
 */
function denso_search_form_modify( $html ) {
	return str_replace( 'class="search-submit"', 'class="search-submit screen-reader-text"', $html );
}
add_filter( 'get_search_form', 'denso_search_form_modify' );

/**
 * Function get opt_name
 *
 */
function denso_get_opt_name() {
	return 'denso_theme_options';
}
add_filter( 'apus_themer_get_opt_name', 'denso_get_opt_name' );

function denso_register_demo_mode() {
	if ( defined('DENSO_DEMO_MODE') && DENSO_DEMO_MODE ) {
		return true;
	}
	return false;
}
add_filter( 'apus_themer_register_demo_mode', 'denso_register_demo_mode' );

function denso_get_demo_preset() {
	$preset = '';
    if ( defined('DENSO_DEMO_MODE') && DENSO_DEMO_MODE ) {
        if ( isset($_GET['_preset']) && $_GET['_preset'] ) {
            $presets = get_option( 'apus_themer_presets' );
            if ( is_array($presets) && isset($presets[$_GET['_preset']]) ) {
                $preset = $_GET['_preset'];
            }
        } else {
            $preset = get_option( 'apus_themer_preset_default' );
        }
    }
    return $preset;
}

function denso_get_config($name, $default = '') {
	global $denso_options;
    if ( isset($denso_options[$name]) ) {
        return $denso_options[$name];
    }
    return $default;
}

function denso_get_global_config($name, $default = '') {
	$options = get_option( 'denso_theme_options', array() );
	if ( isset($options[$name]) ) {
        return $options[$name];
    }
    return $default;
}

function denso_get_image_lazy_loading() {
	return denso_get_config('image_lazy_loading');
}

add_filter( 'apus_themer_get_image_lazy_loading', 'denso_get_image_lazy_loading');

function denso_register_sidebar() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar Default', 'denso' ),
		'id'            => 'sidebar-default',
		'description'   => esc_html__( 'Add widgets here to appear in your Sidebar.', 'denso' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Blog right sidebar', 'denso' ),
		'id'            => 'blog-right-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'denso' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Product left sidebar', 'denso' ),
		'id'            => 'product-left-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'denso' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Product right sidebar', 'denso' ),
		'id'            => 'product-right-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'denso' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );
}
add_action( 'widgets_init', 'denso_register_sidebar' );

/*
 * Init widgets
 */
function denso_widgets_init($widgets) {
	$widgets = array_merge($widgets, array( 'woo-price-filter', 'woo-product-sorting', 'vertical_menu' ));
	return $widgets;
}
add_filter( 'apus_themer_register_widgets', 'denso_widgets_init' );

function denso_get_load_plugins() {
	// framework
	$plugins[] =(array(
		'name'                     => esc_html__( 'Apus Themer For Themes', 'denso' ),
        'slug'                     => 'apus-themer',
        'required'                 => true,
        'source'				   => get_template_directory() . '/inc/plugins/apus-themer.zip'
	));

	$plugins[] =(array(
		'name'                     => esc_html__( 'Cmb2', 'denso' ),
	    'slug'                     => 'cmb2',
	    'required'                 => true,
	));
	
	$plugins[] =(array(
		'name'                     => esc_html__('King Composer - Page Builder', 'denso'),
	    'slug'                     => 'kingcomposer',
	    'required'                 => true,
	));

	$plugins[] =(array(
		'name'                     => esc_html__( 'Revolution Slider', 'denso' ),
        'slug'                     => 'revslider',
        'required'                 => true,
        'source'				   => get_template_directory() . '/inc/plugins/revslider.zip'
	));

	// for woocommerce
	$plugins[] =(array(
		'name'                     => esc_html__( 'WooCommerce', 'denso' ),
	    'slug'                     => 'woocommerce',
	    'required'                 => true,
	));

	$plugins[] =(array(
		'name'                     => esc_html__( 'YITH WooCommerce Wishlist', 'denso' ),
	    'slug'                     => 'yith-woocommerce-wishlist',
	    'required'                 =>  true
	));

	$plugins[] =(array(
		'name'                     => esc_html__( 'YITH WooCommerce Compare', 'denso' ),
        'slug'                     => 'yith-woocommerce-compare',
        'required'                 => false,
	));

	$plugins[] =(array(
		'name'                     => esc_html__( 'WC Vendors', 'denso' ),
        'slug'                     => 'wc-vendors',
        'required'                 => false,
	));

	// for other plugins
	$plugins[] =(array(
		'name'                     => esc_html__( 'MailChimp for WordPress', 'denso' ),
	    'slug'                     => 'mailchimp-for-wp',
	    'required'                 =>  true
	));

	$plugins[] =(array(
		'name'                     => esc_html__( 'Contact Form 7', 'denso' ),
	    'slug'                     => 'contact-form-7',
	    'required'                 => true,
	));
	
	tgmpa( $plugins );
}

require get_template_directory() . '/inc/plugins/class-tgm-plugin-activation.php';
require get_template_directory() . '/inc/functions-helper.php';
require get_template_directory() . '/inc/functions-frontend.php';

/**
 * Implement the Custom Header feature.
 *
 */
require get_template_directory() . '/inc/custom-header.php';
require get_template_directory() . '/inc/classes/megamenu.php';
require get_template_directory() . '/inc/classes/mobilemenu.php';

/**
 * Custom template tags for this theme.
 *
 */
require get_template_directory() . '/inc/template-tags.php';


if ( defined( 'APUS_THEMER_REDUX_ACTIVED' ) ) {
	require get_template_directory() . '/inc/vendors/redux-framework/redux-config.php';
	define( 'DENSO_REDUX_THEMER_ACTIVED', true );
}
if( in_array( 'cmb2/init.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	require get_template_directory() . '/inc/vendors/cmb2/page.php';
	require get_template_directory() . '/inc/vendors/cmb2/footer.php';
	require get_template_directory() . '/inc/vendors/cmb2/product.php';
	define( 'DENSO_CMB2_ACTIVED', true );
}
if( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	require get_template_directory() . '/inc/vendors/woocommerce/functions.php';
	require get_template_directory() . '/inc/vendors/woocommerce/woo-custom.php';
	define( 'DENSO_WOOCOMMERCE_ACTIVED', true );
}
if( in_array( 'yith-woocommerce-wishlist/init.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	define( 'DENSO_WOOCOMMERCE_WISHLIST_ACTIVED', true );
}
if( in_array( 'yith-woocommerce-compare/init.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	define( 'DENSO_WOOCOMMERCE_COMPARE_ACTIVED', true );
}
if( in_array( 'kingcomposer/kingcomposer.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	require get_template_directory() . '/inc/vendors/kingcomposer/functions.php';
	require get_template_directory() . '/inc/vendors/kingcomposer/maps.php';
	define( 'DENSO_KINGCOMPOSER_ACTIVED', true );
}
if( in_array( 'wc-vendors/class-wc-vendors.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	require get_template_directory() . '/inc/vendors/wc-vendors/functions.php';
	require get_template_directory() . '/inc/vendors/wc-vendors/favourite.php';
	define( 'DENSO_WC_VENDORS_ACTIVED', true );
}
if( in_array( 'dokan-lite/dokan.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	require get_template_directory() . '/inc/vendors/dokan/functions.php';
}
if( in_array( 'apus-themer/apus-themer.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	require get_template_directory() . '/inc/widgets/popup_promotion.php';
}
/**
 * Customizer additions.
 *
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Custom Styles
 *
 */
require get_template_directory() . '/inc/custom-styles.php';