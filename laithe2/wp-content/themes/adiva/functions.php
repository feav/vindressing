<?php
/**
 * Theme constants definition and functions.
*/

// Constants definition
define( 'ADIVA_PATH', get_template_directory()     );
define( 'ADIVA_URL',  get_template_directory_uri() );
define( 'ADIVA_DUMMY',  ADIVA_PATH . '/inc/admin/data' );
define( 'ADIVA_VERSION', '1.0.0' );


/**
 * ------------------------------------------------------------------------------------------------
 * Enqueue styles
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists( 'adiva_enqueue_styles' ) ) {
    function adiva_enqueue_styles() {
        wp_dequeue_style( 'yith-wcwl-font-awesome' );
        // Add custom fonts, used in the main stylesheet.
        if( ! class_exists('Redux') ) {
            wp_enqueue_style( 'adiva-fonts', adiva_enqueue_google_fonts(), array(), null );
        }
        wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/3rd-party/bootstrap/css/bootstrap.min.css', array(), '3.3.7');
        wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/assets/3rd-party/font-awesome/css/font-awesome.min.css', array(), '4.7.0' );
        wp_enqueue_style( 'simple-line-icons', get_template_directory_uri() . '/assets/3rd-party/simple-line-icons/simple-line-icons.css', array(), '' );
        wp_enqueue_style( 'linearicons', get_template_directory_uri() . '/assets/3rd-party/linearicons/style.css', array(), '1.0.0' );
        wp_enqueue_style( 'slick', get_template_directory_uri() . '/assets/3rd-party/slick/slick.css' );
        wp_enqueue_style( 'owl-carousel', get_template_directory_uri() . '/assets/3rd-party/owl-carousel/owl.carousel.min.css', array(), '2.0.0' );
        wp_enqueue_style( 'owl-carousel-theme', get_template_directory_uri() . '/assets/3rd-party/owl-carousel/owl.theme.default.min.css' );
        wp_enqueue_style( 'magnific-popup', get_template_directory_uri() . '/assets/3rd-party/magnific-popup/magnific-popup.css' );
        wp_enqueue_style( 'magnific-popup-effect', get_template_directory_uri() . '/assets/3rd-party/magnific-popup/magnific-popup-effect.css' );

        // Main stylesheet
    	wp_enqueue_style( 'adiva-style', get_stylesheet_uri() );

        global $post;

        $options = get_post_meta( get_the_ID(), '_custom_page_options', true );

        // background color
        if ( isset( $options['pagehead-bg-color'] ) && $options['pagehead-bg-color'] != '' ) {
        	$custom_css = "
                    .page-heading {
                        background-color: {$options['pagehead-bg-color']} !important;
                        background-image: none !important;
                    }";
            wp_add_inline_style( 'adiva-style', $custom_css );
        }

        //Background Image
        if ( isset( $options['pagehead-bg'] ) && $options['pagehead-bg'] != '' ) {
        	$image_id = $options['pagehead-bg'];
        	$bg_image = wp_get_attachment_image_src( $image_id, 'full' );

        	if ( isset($bg_image) && $bg_image != '' ) {
        		$custom_css = "
                        .page-heading {
                            background-image: url({$bg_image[0]}) !important;
                        }";
                wp_add_inline_style( 'adiva-style', $custom_css );
        	}
        }

        if( adiva_woocommerce_activated() && is_product_category() ) {
            $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
            if ( isset($term->term_id) && $term->term_id != '' ) {
                $term_options = get_term_meta( $term->term_id, '_custom_product_cat_options', true );
            }

            if ( isset( $term_options ) && $term_options && $term_options['product-cat-bg']['color'] != '' ) {
                $custom_css = "
                    .page-heading {
                        background-color: {$term_options['product-cat-bg']['color']};
                    }";
                wp_add_inline_style( 'adiva-style', $custom_css );
            }

            if ( isset( $term_options ) && $term_options && $term_options['product-cat-bg']['image'] != '' ) {
                $custom_css = "
                    .page-heading {
                        background-image: url({$term_options['product-cat-bg']['image']}) !important;
                    }";
                wp_add_inline_style( 'adiva-style', $custom_css );
            }
        }

        wp_add_inline_style( 'adiva-style', adiva_custom_inline_css() );
    }
	add_action( 'wp_enqueue_scripts', 'adiva_enqueue_styles', 10000 );
}

if( ! function_exists( 'adiva_enqueue_scripts' ) ) {
    function adiva_enqueue_scripts() {
        global $post;

        // Load required scripts.
        wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/assets/3rd-party/bootstrap/js/bootstrap.min.js', array(), '3.3.7', true);
        wp_enqueue_script( 'isotope' , get_template_directory_uri() . '/assets/3rd-party/isotope/isotope.pkgd.min.js', array(), false, true  );
        wp_enqueue_script( 'owl-carousel', get_template_directory_uri() . '/assets/3rd-party/owl-carousel/owl.carousel.min.js', array(), '2.2.0', true );
        wp_enqueue_script( 'slick', get_template_directory_uri() . '/assets/3rd-party/slick/slick.min.js', array(), '1.5.9', true );
        wp_enqueue_script( 'magnific-popup' , get_template_directory_uri() . '/assets/3rd-party/magnific-popup/jquery.magnific-popup.min.js', array(), false, true  );
        wp_register_script( 'threesixty', get_template_directory_uri() . '/assets/3rd-party/threesixty/threesixty.min.js', array(), '', true );
        wp_register_script( 'maplace', get_template_directory_uri() . '/assets/3rd-party/maplace/maplace.min.js', array('jquery', 'google.map.api'), '', true );
        wp_enqueue_script( 'moment', get_template_directory_uri() . '/assets/3rd-party/moment.min.js', array( 'jquery' ), '', true );
        wp_enqueue_script( 'moment-timezone', get_template_directory_uri() . '/assets/3rd-party/moment-timezone-with-data.min.js', array( 'jquery' ), '', true );
        wp_enqueue_script( 'theia-sticky-sidebar', get_template_directory_uri() . '/assets/3rd-party/theia-sticky-sidebar/theia-sticky-sidebar.js', array(), false, true );
        wp_enqueue_script( 'jquery-tweenmax', get_template_directory_uri() . '/assets/3rd-party/panr/TweenMax.js', array(), '', true );
        wp_enqueue_script( 'jquery-panr', get_template_directory_uri() . '/assets/3rd-party/panr/jquery.panr.js', array(), '', true );
        wp_enqueue_script( 'jquery-countdown' , get_template_directory_uri() . '/assets/3rd-party/countdown/jquery.countdown.js', array(), false, true  );

        if ( adiva_woocommerce_activated() ) {
            wp_enqueue_script( 'wc-add-to-cart-variation' );
            wp_enqueue_script( 'jquery-ui-autocomplete' );
            wp_enqueue_script( 'adiva-shop-filter', get_template_directory_uri() . '/assets/js/shop-filter.js', array(), false, true );

            // Zoom image
            if ( is_singular( 'product' ) ) {
                wp_enqueue_script( 'zoom' );
            }
        }

        // Check if browser smooth scroll is enabled
    	if ( adiva_get_option('browser-smooth-scroll', 0) == 1 ) {
    		wp_enqueue_script( 'jquery-niceScroll', get_template_directory_uri() . '/assets/3rd-party/nicescroll/jquery.nicescroll.min.js', array(), false, true );
    	}

    	// Load theme js
        wp_enqueue_script('adiva-theme', get_template_directory_uri() . '/assets/js/theme.js', array( 'jquery', 'imagesloaded' ), false, true);

        wp_add_inline_script('adiva-theme', adiva_settings_js(), 'after' );

        // Custom localize script
        wp_localize_script( 'adiva-theme', 'adiva_settings',
            array(
                'ajaxurl'          => esc_url(admin_url('admin-ajax.php')),
                'ajax_add_to_cart' => ( apply_filters( 'adiva_ajax_add_to_cart', true ) ) ? adiva_get_option( 'single_ajax_add_to_cart', true ) : false,
                '_nonce_adiva'     => wp_create_nonce('bb_adiva'),
                'JmsSiteURL'       => esc_url(get_option('siteurl')),
                'added_to_cart'    => esc_html__( 'Product was successfully added to your cart.', 'adiva' ),
                'View Wishlist'    => esc_html__( 'View Wishlist', 'adiva' ),
                'viewall_wishlist' => esc_html__( 'View all', 'adiva' ),
                'removed_notice'   => esc_html__( '%s has been removed from your cart.', 'adiva' ),
                'load_more'        => esc_html__( 'Load more', 'adiva' ),
                'loading'          => esc_html__( 'Loading...', 'adiva' ),
                'no_more_item'     => esc_html__( 'All items loaded', 'adiva' ),
                'days'             => esc_html__( 'days', 'adiva' ),
                'hours'            => esc_html__( 'hrs', 'adiva' ),
                'mins'             => esc_html__( 'mins', 'adiva' ),
                'secs'             => esc_html__( 'secs', 'adiva' ),
                'permalink'        => ( get_option( 'permalink_structure' ) == '' ) ? 'plain' : '',
            )
        );

        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
            wp_enqueue_script( 'comment-reply' );
        }
    }
    add_action( 'wp_enqueue_scripts', 'adiva_enqueue_scripts', 10000 );
}

if( ! function_exists( 'adiva_reset_loop' ) ) {
    // **********************************************************************//
    // Reset adiva loop
    // **********************************************************************//
	function adiva_reset_loop() {
		$GLOBALS['adiva_loop'] = array(
			'img_size'         => '',
            'columns'          => '',
            'product_style'    => '',
    		'product_design'   => '',
    		'enable_countdown' => '',
    		'items_spacing'    => ''
	    );
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Enqueue google fonts
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists( 'adiva_enqueue_google_fonts' ) ) {
	function adiva_enqueue_google_fonts() {
        $fonts_url = '';

        $roboto = _x( 'on', 'Roboto font: on or off', 'adiva' );

        if ( 'off' !== $roboto ) {
            $font_families = array();
            $font_families[] = 'Roboto:300,400,400i,500,700';

            $query_args = array(
                'family' => urlencode( implode( '|', $font_families ) ),
                'subset' => urlencode( 'latin,latin-ext' ),
            );

            $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );

        }

        return esc_url_raw( $fonts_url );
	}
}

// Initialize core file
require ADIVA_PATH . '/inc/init.php';

if( ! function_exists('adiva_script_admin') ) {
    function adiva_script_admin() {
    	wp_enqueue_style( 'adiva-custom-wp-admin', get_template_directory_uri() . '/assets/css/admin-style.css', false, '1.0.0' );
    }
    add_action('admin_enqueue_scripts', 'adiva_script_admin');
}

// load admin dashboard
require_once get_template_directory() . '/inc/admin/admin.php';
