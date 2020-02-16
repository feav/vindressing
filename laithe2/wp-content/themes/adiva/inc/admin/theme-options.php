<?php
/**
 * ReduxFramework Barebones Sample Config File
 * For full documentation, please visit: http://docs.reduxframework.com/
 */

if ( ! class_exists( 'Redux' ) ) {
    return;
}

/**
 * ------------------------------------------------------------------------------------------------
 * Prepare CSS selectors for theme settions (colors, borders, typography etc.)
 * ------------------------------------------------------------------------------------------------
 */
//include ADIVA_PATH . '/inc/selectors.php';

// This is your option name where all the Redux data is stored.
$opt_name = "adiva_option";

/**
 * ---> SET ARGUMENTS
 * All the possible arguments for Redux.
 * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
 * */

$theme = wp_get_theme(); // For use with some settings. Not necessary.

$args = array(
    // TYPICAL -> Change these values as you need/desire
    'opt_name'             => $opt_name,
    // This is where your data is stored in the database and also becomes your global variable name.
    'display_name'         => $theme->get( 'Name' ),
    // Name that appears at the top of your panel
    'display_version'      => $theme->get( 'Version' ),
    // Version that appears at the top of your panel
    'menu_type'            => 'menu',
    //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
    'allow_sub_menu'       => false,
    // Show the sections below the admin menu item or not
    'menu_title'           => esc_html__( 'Theme Options', 'adiva' ),
    'page_title'           => esc_html__( 'Theme Options', 'adiva' ),
    // You will need to generate a Google API key to use this feature.
    // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
    'google_api_key'       => 'AIzaSyCF5_SS7dQ37SiwEHOcNMA5kvCpFurExk4',
    // Set it you want google fonts to update weekly. A google_api_key value is required.
    'google_update_weekly' => false,
    // Must be defined to add google fonts to the typography module
    'async_typography'     => true,
    // Use a asynchronous font on the front end or font string
    //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
    'admin_bar'            => false,
    // Show the panel pages on the admin bar
    'admin_bar_icon'       => 'dashicons-portfolio',
    // Choose an icon for the admin bar menu
    'admin_bar_priority'   => 50,
    // Choose an priority for the admin bar menu
    'global_variable'      => '',
    // Set a different name for your global variable other than the opt_name
    'dev_mode'             => false,
    // Show the time the page took to load, etc
    'update_notice'        => true,
    // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
    'customizer'           => true,

    // OPTIONAL -> Give you extra features
    'page_priority'        => null,
    // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
    'page_parent'          => 'themes.php',

    'page_permissions'     => 'administrator',
    // Permissions needed to access the options panel.
    'menu_icon'            => 'dashicons-palmtree',
    // Specify a custom URL to an icon
    'last_tab'             => '',
    // Force your panel to always open to a specific tab (by id)
    'page_icon'            => 'icon-themes',
    // Icon displayed in the admin panel next to your menu_title
    'page_slug'            => '_options',
    // Page slug used to denote the panel
    'save_defaults'        => true,
    // On load save the defaults to DB before user clicks save or not
    'default_show'         => false,
    // If true, shows the default value next to each field that is not the default value.
    'default_mark'         => '',
    // What to print by the field's title if the value shown is default. Suggested: *
    'show_import_export'   => true,
    // Shows the Import/Export panel when not used as a field.

    // CAREFUL -> These options are for advanced use only
    'transient_time'       => 60 * MINUTE_IN_SECONDS,
    'output'               => true,
    // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
    'output_tag'           => true,
    // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head

    // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
    'database'             => '',
    // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!

    'use_cdn'              => true,
    // If you prefer not to use the CDN for Select2, Ace Editor, and others, you may download the Redux Vendor Support plugin yourself and run locally or embed it in your code.


    // HINTS
    'hints'                => array(
        'icon'          => 'el el-question-sign',
        'icon_position' => 'right',
        'icon_color'    => 'lightgray',
        'icon_size'     => 'normal',
        'tip_style'     => array(
            'color'   => 'light',
            'shadow'  => true,
            'rounded' => false,
            'style'   => '',
        ),
        'tip_position'  => array(
            'my' => 'top left',
            'at' => 'bottom right',
        ),
        'tip_effect'    => array(
            'show' => array(
                'effect'   => 'slide',
                'duration' => '500',
                'event'    => 'mouseover',
            ),
            'hide' => array(
                'effect'   => 'slide',
                'duration' => '500',
                'event'    => 'click mouseleave',
            ),
        ),
    )
);

// SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
$args['share_icons'][] = array(
    'url'   => 'https://facebook.com/joommasters2015',
    'title' => 'Like us on Facebook',
    'icon'  => 'el el-facebook'
);
$args['share_icons'][] = array(
    'url'   => 'https://twitter.com/joommasters',
    'title' => 'Follow us on Twitter',
    'icon'  => 'el el-twitter'
);
$args['share_icons'][] = array(
    'url'   => 'https://www.linkedin.com/company/joommasters',
    'title' => 'Find us on LinkedIn',
    'icon'  => 'el el-linkedin'
);


Redux::setArgs( $opt_name, $args );

/*
 * ---> END ARGUMENTS
 */

/*
 * ---> START HELP TABS
 */

$tabs = array(
    array(
        'id'      => 'redux-help-tab-1',
        'title'   => esc_html__( 'Theme Information 1', 'adiva' ),
        'content' => esc_html__( '<p>This is the tab content, HTML is allowed.</p>', 'adiva' )
    ),
    array(
        'id'      => 'redux-help-tab-2',
        'title'   => esc_html__( 'Theme Information 2', 'adiva' ),
        'content' => esc_html__( '<p>This is the tab content, HTML is allowed.</p>', 'adiva' )
    )
);
Redux::setHelpTab( $opt_name, $tabs );

// Set the help sidebar
$content = esc_html__( '<p>This is the sidebar content, HTML is allowed.</p>', 'adiva' );
Redux::setHelpSidebar( $opt_name, $content );


/*
 * <--- END HELP TABS
 */

Redux::setSection( $opt_name, array(
    'title' => esc_html__( 'General', 'adiva' ),
    'id'     => 'general',
    'icon'   => 'el el-dashboard',
    'fields' => array(
        array(
            'id'       => 'site-width',
            'type'     => 'select',
            'title'    => esc_html__('Site width', 'adiva'),
            'subtitle' => esc_html__('You can make your content wrapper boxed or full width', 'adiva'),
            'desc'     => esc_html__('Default: 1400px, Wide: 1720px, Boxed: 1400px with shadow', 'adiva'),
            'options'  => array(
               'default' => esc_html__('Default', 'adiva'),
               'wide'    => esc_html__('Wide', 'adiva'),
               'boxed'   => esc_html__('Boxed', 'adiva'),
            ),
            'default' => 'default',
        ),
        array(
            'id'      => 'site-loader',
            'type'    => 'switch',
            'title'   => esc_html__('Site Loader', 'adiva'),
            'on'      => esc_html__('On','adiva'),
            'off'     => esc_html__('Off','adiva'),
            'default' => false,
        ),
        array(
            'id'       => 'site-loader-style',
            'type'     => 'select',
            'title'    => esc_html__( 'Site Loader Style', 'adiva' ),
            'options'  => array(
                '1' => esc_html__( 'Style 1', 'adiva' ),
                '2' => esc_html__( 'Style 2', 'adiva' ),
                '3' => esc_html__( 'Style 3', 'adiva' ),
                '4' => esc_html__( 'Style 4', 'adiva' ),
                '5' => esc_html__( 'Style 5', 'adiva' ),
                '6' => esc_html__( 'Style 6', 'adiva' ),
            ),
            'default'  => '5',
            'required' => array( 'site-loader', '=', 1 )
        ),
        array(
            'id'       => 'browser-smooth-scroll',
            'type'     => 'switch',
            'title'    => esc_html__('Smooth Browser Scroll', 'adiva'),
            'on'       => esc_html__('On', 'adiva'),
			'off'      => esc_html__('Off', 'adiva'),
			'default'  => 0,
        ),
        array(
            'id'      => 'smart-sidebar',
            'type'    => 'switch',
            'title'   => esc_html__('Smart sidebar', 'adiva'),
            'subtitle' => esc_html__('The smart sidebar is an affix (sticky) sidebar that has auto resize and it scrolls with the content.', 'adiva'),
            'on'      => esc_html__('On','adiva'),
            'off'     => esc_html__('Off','adiva'),
            'default' => false,
        ),
        array(
            'id'       => 'login-logo',
            'type'     => 'media',
            'url'      => true,
            'title'    => esc_html__('Login Logo', 'adiva'),
            'subtitle' => esc_html__('Max width: 302px - Max height: 67px','adiva')
        ),
        array(
            'id'       => 'favicon',
            'type'     => 'media',
            'url'      => true,
            'title'    => esc_html__('Favicon', 'adiva'),
            'subtitle' => esc_html__('Max width: 32px - Max height: 32px','adiva')
        ),
        array(
            'id'       => 'back-to-top',
            'type'     => 'switch',
            'title'    => esc_html__( 'Show Back To Top Button', 'adiva' ),
            'desc'     => esc_html__( 'Show back to top button.', 'adiva' ),
            'on'       => esc_html__( 'On', 'adiva' ),
            'off'      => esc_html__( 'Off', 'adiva' ),
            'default'  => 1,
        ),
        array(
            'id'       => 'google_map_api_key',
            'type'     => 'text',
            'title'    => esc_html__('Google map API key', 'adiva'),
            'subtitle' => wp_kses( __('Obtain API key <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">here</a> to use our Google Map VC element.', 'adiva'),
            array(
                'a' => array(
                    'href' => array(),
                    'target' => array()
                )
            ) ),
            'default'  => '',
        ),
    )
) );

Redux::setSection( $opt_name, array(
    'title' => esc_html__('Page heading', 'adiva'),
    'id' => 'page_titles',
    'icon' => 'el-icon-check',
    'fields' => array(
        array(
            'id'       => 'page-title-design',
            'type'     => 'button_set',
            'title'    => esc_html__('Page title design', 'adiva'),
            'options'  => array(
                'left'     => esc_html__('Left', 'adiva'),
                'centered' => esc_html__('Centered', 'adiva'),
                'right'    => esc_html__('Right', 'adiva'),
                'disable'  => esc_html__('Disable', 'adiva'),
            ),
            'default' => 'centered',
        ),
        array(
            'id'       => 'page-title-size',
            'type'     => 'button_set',
            'title'    => esc_html__('Page title size', 'adiva'),
            'options'  => array(
                'default' => esc_html__('Default', 'adiva'),
                'small'  => esc_html__('Small', 'adiva'),
                'medium' => esc_html__('Medium', 'adiva'),
                'large'  => esc_html__('Large', 'adiva'),
            ),
            'default' => 'default',
        ),
        array(
            'id'       => 'breadcrumbs',
            'type'     => 'switch',
            'title'    => esc_html__('Show breadcrumbs', 'adiva'),
            'subtitle' => esc_html__('Displays a full chain of links to the current page.', 'adiva'),
            'default' => true
        ),
        array(
            'id'       => 'title-background',
            'type'     => 'background',
            'title'    => esc_html__('Page title background', 'adiva'),
            'subtitle' => esc_html__('Set background image or color, that will be used as a default for all page titles, shop page and blog.', 'adiva'),
            'desc'     => esc_html__('You can also specify other image for particular page', 'adiva'),
            'output'   => array('.page-heading'),
            'default'  => array(
                'background-color'    => '#f9f9f9',
                'background-position' => 'center center',
                'background-size'     => 'cover'
            ),
        ),
        array(
            'id'       => 'page-title-color',
            'type'     => 'button_set',
            'title'    => esc_html__('Text color for page title', 'adiva'),
            'subtitle' => esc_html__('You can set different colors depending on it\'s background. May be light or dark', 'adiva'),
            'options'  => array(
                'light'   => esc_html__('Light', 'adiva'),
                'dark'    => esc_html__('Dark', 'adiva'),
            ),
            'default' => 'dark'
        ),
    ),
) );


Redux::setSection( $opt_name, array(
    'title' => esc_html__('Promo bar', 'adiva'),
    'id' => 'promo-bar',
    'fields' => array(
        array(
            'id'       => 'promo-bar',
            'type'     => 'switch',
            'title'    => esc_html__('Enable promo bar', 'adiva'),
            'subtitle' => esc_html__('Show promo bar to users when they enter the site.', 'adiva'),
            'default' => 0
        ),
        array(
            'id'       => 'promo-bar-text',
            'type'     => 'editor',
            'title'    => esc_html__('Promo bar text', 'adiva'),
            'subtitle' => esc_html__('Place here some promo text or use HTML block and place here it\'s shortcode', 'adiva'),
            'default' => ''
        ),
        array(
            'id'       => 'promo-bar-background',
            'type'     => 'background',
            'title'    => esc_html__('Promo background', 'adiva'),
            'subtitle' => esc_html__('Set background color for promo bar', 'adiva'),
            'output'   => '.adiva-promo-bar',
            'background-repeat'     => false,
            'background-position'   => false,
            'background-size'       => false,
            'background-attachment' => false,
            'background-image'      => false,
            'default'               => array(
                'background-color' => '#f86b73'
            )

        ),
        array(
            'id'       => 'promo-bar-text-color',
            'type'     => 'color',
            'title'    => esc_html__('Promo text color', 'adiva'),
            'output'   => '.adiva-promo-bar',
            'default'  => '#ffffff'
        ),
    ),
) );

// Header
Redux::setSection( $opt_name, array(
    'title'  => esc_html__( 'Header', 'adiva' ),
    'id'     => 'header',
    'icon'   => 'el el-circle-arrow-up',
) );

// Top bar
Redux::setSection( $opt_name, array(
    'title'      => esc_html__( 'Top Bar', 'adiva' ),
    'id'         => 'top-bar',
    'subsection' => true,
    'fields'     => array(
        array(
            'id'          => 'topbar',
            'type'        => 'switch',
            'title'       => esc_html__('Enable Top Bar', 'adiva'),
            'default'     => false,
        ),
        array(
            'id'       => 'topbar-text',
            'type'     => 'text',
            'title'    => esc_html__('Text', 'adiva'),
            'default'  => 'Order Online Or Call Us: (001) 2222-3333'
        ),
        array(
            'id'          => 'show-language-box',
            'type'        => 'switch',
            'title'       => esc_html__('Show Language', 'adiva'),
            'default'     => false,
        ),
        array(
            'id'          => 'language-name-1',
            'type'        => 'text',
            'title'       => esc_html__('1st Language Name', 'adiva'),
            'default'     => 'English',
            'required'    => array( 'show-language-box', '=', 1 )
        ),
        array(
            'id'          => 'language-link-1',
            'type'        => 'text',
            'title'       => esc_html__('1st Language URL', 'adiva'),
            'default'     => '#',
            'required'    => array( 'show-language-box', '=', 1 )
        ),
        array(
            'id'          => 'language-name-2',
            'type'        => 'text',
            'title'       => esc_html__('2nd Language Name', 'adiva'),
            'default'     => 'Italiano',
            'required'    => array( 'show-language-box', '=', 1 )
        ),
        array(
            'id'          => 'language-link-2',
            'type'        => 'text',
            'title'       => esc_html__('2nd Language URL', 'adiva'),
            'default'     => '#',
            'required'    => array( 'show-language-box', '=', 1 )
        ),
        array(
            'id'          => 'language-name-3',
            'type'        => 'text',
            'title'       => esc_html__('3rd Language Name', 'adiva'),
            'default'     => '',
            'required'    => array( 'show-language-box', '=', 1 )
        ),
        array(
            'id'          => 'language-link-3',
            'type'        => 'text',
            'title'       => esc_html__('3rd Language URL', 'adiva'),
            'default'     => '',
            'required'    => array( 'show-language-box', '=', 1 )
        ),
        array(
            'id'          => 'show-currency-box',
            'type'        => 'switch',
            'title'       => esc_html__('Show Currency', 'adiva'),
            'default'     => false,
        ),
        array(
            'id'                    => 'topbar-background-color',
            'type'                  => 'background',
            'title'                 => esc_html__('Top bar background', 'adiva'),
            'background-position'   => false,
            'background-size'       => false,
            'background-image'      => false,
            'background-repeat'     => false,
            'background-attachment' => false,
            'output'                => array('.topbar'),
            'default'               => array(
                'background-color'    => '',
            ),
        ),
        array(
            'id'       => 'topbar-border-color',
            'type'     => 'color',
            'title'    => esc_html__('Border color', 'adiva'),
            'default' => ''
        ),
        array(
            'id'       => 'topbar-text-color',
            'type'     => 'button_set',
            'title'    => esc_html__('Text color', 'adiva'),
            'options'  => array(
                'light' => esc_html__('Light', 'adiva'),
                'dark'  => esc_html__('Dark', 'adiva')
            ),
            'default' => 'light'
        ),
    )
) );
// Header
Redux::setSection( $opt_name, array(
    'title'      => esc_html__( 'Header layout', 'adiva' ),
    'id'         => 'header_layout',
    'subsection' => true,
    'fields'     => array(
        array(
            'id'       => 'header-fullwidth',
            'type'     => 'switch',
            'title'    => esc_html__('Header fullwidth', 'adiva'),
            'subtitle' => esc_html__('Make header full width', 'adiva'),
            'default'  => false,
        ),
        array(
            'id'          => 'sticky-header',
            'type'        => 'switch',
            'title'       => esc_html__('Sticky Header', 'adiva'),
            'subtitle'    => esc_html__('How to display the header menu on scroll.', 'adiva'),
            'default'     => false,
        ),
        array(
            'id'       => 'header-logo',
            'type'     => 'media',
            'url'      => true,
            'title'    => esc_html__('Logo', 'adiva'),
        ),
        array(
            'id'       => 'header-layout',
            'type'     => 'select',
            'title'    => esc_html__( 'Header Layout', 'adiva' ),
            'subtitle' => esc_html__( 'Set the header layout', 'adiva' ),
            'options'  => array(
                '1' => esc_html__( 'Header 1', 'adiva' ),
                '2' => esc_html__( 'Header 2', 'adiva' ),
                '3' => esc_html__( 'Header 3', 'adiva' ),
                '4' => esc_html__( 'Header 4', 'adiva' ),
                '5' => esc_html__( 'Header 5', 'adiva' ),
                '6' => esc_html__( 'Header 6', 'adiva' ),
            ),
            'default'  => '1',
        ),
        array(
            'id'          => 'hotline',
            'type'        => 'text',
            'title'       => esc_html__('Hotline', 'adiva'),
            'default'     => '(001) 2222-3333',
        ),
        array(
            'id'       => 'header-extra-text',
            'type'     => 'textarea',
            'title'    => esc_html__('Header extra text', 'adiva'),
            'default'  => '',
        ),
        array(
            'id'          => 'show-search-form',
            'type'        => 'switch',
            'title'       => esc_html__('Show Search Form', 'adiva'),
            'default'     => true
        ),
        array(
            'id'          => 'show-cart-button',
            'type'        => 'switch',
            'title'       => esc_html__('Show Cart Button', 'adiva'),
            'default'     => true
        ),
        array(
            'id'       => 'wc-add-to-cart-style',
            'type'     => 'button_set',
            'title'    => esc_html__( 'Add To Cart Design', 'adiva' ),
            'options'  => array(
                'alert'          => esc_html__('Default', 'adiva'),
                'toggle-sidebar' => esc_html__('Toggle Sidebar', 'adiva'),
            ),
            'default'  => 'alert'
        ),
        array(
            'id'          => 'show-wishlist-button',
            'type'        => 'switch',
            'title'       => esc_html__('Show Wishlist Button', 'adiva'),
            'default'     => true
        ),
        array(
            'id'          => 'show-toggle-sidebar',
            'type'        => 'switch',
            'title'       => esc_html__('Show Toggle Sidebar Button', 'adiva'),
            'default'     => false
        ),
        array(
            'id'       => 'header-menu-align',
            'type'     => 'button_set',
            'title'    => esc_html__('Menu Align', 'adiva'),
            'options'  => array(
                'left'   => esc_html__('Left', 'adiva'),
                'center' => esc_html__('Center', 'adiva'),
                'right'  => esc_html__('Right', 'adiva'),
            ),
            'default' => 'center',
        ),
        array(
            'id'                    => 'header-background-color',
            'type'                  => 'background',
            'title'                 => esc_html__('Header background', 'adiva'),
            'background-position'   => false,
            'background-size'       => false,
            'background-image'      => false,
            'background-repeat'     => false,
            'background-attachment' => false,
            'output'                => array('.header-wrapper'),
            'default'               => array(
                'background-color'    => '',
            ),
        ),
        array(
            'id'       => 'header-text-color',
            'type'     => 'button_set',
            'title'    => esc_html__('Header text color', 'adiva'),
            'options'  => array(
                'light' => esc_html__('Light', 'adiva'),
                'dark'  => esc_html__('Dark', 'adiva')
            ),
            'default' => 'dark'
        ),
    ),
) );

// -> START Footer Fields
Redux::setSection( $opt_name, array(
    'title'  => esc_html__( 'Footer', 'adiva' ),
    'id'     => 'footer',
    'icon'   => 'el el-circle-arrow-down',
    'fields' => array(
        array(
            'id'       => 'show-footer',
            'type'     => 'switch',
            'title'    => esc_html__('Footer Top', 'adiva'),
            'on'       => esc_html__('On', 'adiva'),
			'off'      => esc_html__('Off', 'adiva'),
			'default'  => 1,
        ),
        array(
            'id'       => 'footer-column',
            'type'     => 'image_select',
            'title'    => esc_html__( 'Footer Column', 'adiva' ),
            'options'  => array(
                '1' => array(
                    'alt' => '1 Columns',
                    'img' => ADIVA_URL . '/assets/images/layout/one.jpg'
                ),
                '2' => array(
                    'alt' => '2 Columns',
                    'img' => ADIVA_URL . '/assets/images/layout/two.jpg'
                ),
                '3' => array(
                    'alt' => '3 Columns',
                    'img' => ADIVA_URL . '/assets/images/layout/three.jpg'
                ),
                '4' => array(
                    'alt' => '4 Columns',
                    'img' => ADIVA_URL . '/assets/images/layout/four.jpg'
                )
            ),
            'default'  => '4',
            'required' => array( 'show-footer', '=', 1 )
        ),
        array(
            'id'       => 'show-copyright',
            'type'     => 'switch',
            'title'    => esc_html__('Footer Bottom', 'adiva'),
            'on'       => esc_html__('On', 'adiva'),
			'off'      => esc_html__('Off', 'adiva'),
			'default'  => 1,
        ),
        array(
            'id'       => 'copyright-layout',
            'type'     => 'select',
            'title'    => esc_html__('Copyrights layout', 'adiva'),
            'options'  => array(
               'three-columns' => esc_html__('Three columns', 'adiva'),
               'centered'      => esc_html__('Centered', 'adiva'),
            ),
            'default' => 'three-columns'
        ),
        array(
            'id'       => 'footer-copyright',
            'type'     => 'textarea',
            'title'    => esc_html__('Copyright', 'adiva'),
            'subtitle' => esc_html__('HTML is allowed.', 'adiva'),
            'validate' => 'html',
            'default'  => '2018 ADIVA Store. All Rights Reserved.',
        ),
        array(
            'id'       => 'footer-payment',
            'type'     => 'media',
            'url'      => true,
            'title'    => esc_html__('Payment image', 'adiva'),
            'default'  => '',
        ),
        array(
            'id'      => 'footer-background',
            'type'    => 'background',
            'title'   => esc_html__( 'Background', 'adiva' ),
            'default' => array(
                'background-image'      => '',
                'background-color'      => ''
            ),
            'output' => '#footer-wrapper'
        ),
        array(
            'id'      => 'footer-color',
            'type'    => 'button_set',
            'title'   => esc_html__( 'Footer text color', 'adiva' ),
            'options' => array(
                'light' => esc_html__('Light', 'adiva'),
                'dark'  => esc_html__('Dark', 'adiva'),
            ),
            'default' => 'dark'
        ),
    )
) );
/*
 * <--- END SECTIONS
 */

// BLOG
Redux::setSection( $opt_name, array(
    'title' => esc_html__( 'Blog', 'adiva' ),
    'id'     => 'blog',
    'icon'   => 'el el-pencil',
    'fields' => array(
        array(
            'id'       => 'blog-title-background',
            'type'     => 'background',
            'title'    => esc_html__('Pages heading background', 'adiva'),
            'subtitle' => esc_html__('Set background image or color for blog.', 'adiva'),
            'desc'     => esc_html__('You can also specify other image for particular page', 'adiva'),
            'output'   => array('.title-blog'),
            'default'  => array(
                'background-position' => 'center center',
                'background-size'     => 'cover'
            ),
        ),
        array(
            'id'       => 'blog-fullwidth',
            'type'     => 'switch',
            'title'    => esc_html__('Full Width', 'adiva'),
            'subtitle' => esc_html__('Makes container 100% width of the page', 'adiva'),
            'on'       => esc_html__('On', 'adiva'),
			'off'      => esc_html__('Off', 'adiva'),
			'default'  => 0,
        ),
        array(
            'id'       => 'blog-design',
            'type'     => 'select',
            'title'    => esc_html__( 'Blog Design', 'adiva' ),
            'subtitle' => esc_html__( 'You can use different design for your blog styled for the theme.', 'adiva' ),
            'options'  => array(
                'default'      => esc_html__('Default', 'adiva'),
                'small-images' => esc_html__('Small images', 'adiva'),
                'chess'        => esc_html__('Chess', 'adiva'),
                'masonry'      => esc_html__('Masonry grid', 'adiva')
            ),
            'default' => 'default'
        ),
        array(
            'id'       => 'blog-style',
            'type'     => 'button_set',
            'title'    => esc_html__('Blog Style', 'adiva'),
            'options'  => array(
                'flat'   => esc_html__('Flat', 'adiva'),
                'shadow' => esc_html__('With Shadow', 'adiva')
            ),
            'default' => 'flat'
        ),
        array(
            'id'       => 'blog-columns',
            'type'     => 'button_set',
            'title'    => esc_html__('Blog items columns', 'adiva'),
            'subtitle' => esc_html__('For masonry grid design', 'adiva'),
            'options'  => array(
                2 => '2',
                3 => '3',
                4 => '4',
            ),
            'default' => 3,
            'required' => array(
                array('blog-design','equals','masonry'),
            )
        ),
        array(
            'id'       => 'blog-image-size',
            'type'     => 'text',
            'title'    => esc_html__( 'Blog image size', 'adiva' ),
            'desc'     => esc_html__( 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme. Alternatively enter image size in pixels: 500x300 (Width x Height). Leave empty to use "1080x570" size.', 'adiva' ),
            'default'  => '1080x570'
        ),
        array(
            'id'       => 'show-date-image',
            'type'     => 'switch',
            'title'    => esc_html__('Show date in images', 'adiva'),
            'on'       => esc_html__('Show', 'adiva'),
			'off'      => esc_html__('Hide', 'adiva'),
			'default'  => 1,
        ),
        array(
            'id'       => 'show-date',
            'type'     => 'switch',
            'title'    => esc_html__('Show date', 'adiva'),
            'on'       => esc_html__('Show', 'adiva'),
			'off'      => esc_html__('Hide', 'adiva'),
			'default'  => 0,
        ),
        array(
            'id'       => 'show-author',
            'type'     => 'switch',
            'title'    => esc_html__('Show author', 'adiva'),
            'on'       => esc_html__('Show', 'adiva'),
			'off'      => esc_html__('Hide', 'adiva'),
			'default'  => 1,
        ),
        array(
            'id'       => 'show-category',
            'type'     => 'switch',
            'title'    => esc_html__('Show category', 'adiva'),
            'on'       => esc_html__('Show', 'adiva'),
			'off'      => esc_html__('Hide', 'adiva'),
			'default'  => 1,
        ),
        array(
            'id'       => 'show-comment',
            'type'     => 'switch',
            'title'    => esc_html__('Show comment', 'adiva'),
            'on'       => esc_html__('Show', 'adiva'),
			'off'      => esc_html__('Hide', 'adiva'),
			'default'  => 1,
        ),
        array(
            'id'       => 'blog-words-or-letters',
            'type'     => 'button_set',
            'title'    => esc_html__('Excerpt length by words or letters', 'adiva'),
            'options'  => array(
                'word'   => esc_html__('Words', 'adiva'),
                'letter' => esc_html__('Letters', 'adiva'),
            ),
            'default' => 'letter',
        ),
        array(
            'id'       => 'blog-excerpt-length',
            'type'     => 'text',
            'title'    => esc_html__('Excerpt length', 'adiva'),
            'subtitle' => esc_html__('Number of words or letters that will be displayed for each post if you use "Excerpt" mode and don\'t set custom excerpt for each post.', 'adiva'),
            'default' => 125,
        ),
        array(
            'id'       => 'blog-pagination-type',
            'type'     => 'button_set',
            'title'    => esc_html__('Blog Pagination', 'adiva'),
            'options'  => array(
                'number'   => esc_html__('Pagination links', 'adiva'),
                'loadmore' => esc_html__('Load more button', 'adiva'),
                'infinite' => esc_html__('Infinit scrolling', 'adiva'),
            ),
            'default' => 'number'
        ),
        array(
            'id'       => 'blog-layout',
            'type'     => 'image_select',
            'title'    => esc_html__( 'Blog Layout', 'adiva' ),
            'subtitle' => esc_html__( 'Select blog layout with sidebar postion.', 'adiva' ),
            'options'  => array(
                'left' => array(
                    'alt' => esc_html__('Left Sidebar', 'adiva'),
                    'img' => ADIVA_URL . '/assets/images/layout/left-sidebar.jpg'
                ),
                'no' => array(
                    'alt' => esc_html__('No Sidebar', 'adiva'),
                    'img' => ADIVA_URL . '/assets/images/layout/no-sidebar.jpg'
                ),
                'right' => array(
                    'alt' => esc_html__('Right Sidebar', 'adiva'),
                    'img' => ADIVA_URL . '/assets/images/layout/right-sidebar.jpg'
                ),
            ),
            'default'  => 'right'
        ),
    )
) );

// Blog single
Redux::setSection( $opt_name, array(
    'title'      => esc_html__( 'Blog Single', 'adiva' ),
    'id'         => 'blog-single',
    'subsection' => true,
    'fields'     => array(
        array(
            'id'       => 'show-feature-image',
            'type'     => 'switch',
            'title'    => esc_html__('Featured Image', 'adiva'),
            'on'       => esc_html__('Show', 'adiva'),
			'off'      => esc_html__('Hide', 'adiva'),
			'default'  => 1,
        ),
        array(
            'id'       => 'show-author-bio',
            'type'     => 'switch',
            'title'    => esc_html__('Author bio', 'adiva'),
            'subtitle' => esc_html__('Display information about the post author', 'adiva'),
            'default' => true
        ),
        array(
            'id'       => 'show-related-posts',
            'type'     => 'switch',
            'title'    => esc_html__('Show Related Posts', 'adiva'),
            'on'       => esc_html__('Show', 'adiva'),
			'off'      => esc_html__('Hide', 'adiva'),
			'default'  => 1,
        ),
        array(
            'id'       => 'show-post-navigation',
            'type'     => 'switch',
            'title'    => esc_html__('Show Post Navigation', 'adiva'),
            'on'       => esc_html__('Show', 'adiva'),
			'off'      => esc_html__('Hide', 'adiva'),
			'default'  => 1,
        ),
        array(
            'id'       => 'blog-single-layout',
            'type'     => 'image_select',
            'title'    => esc_html__( 'Blog Single Layout', 'adiva' ),
            'subtitle' => esc_html__( 'Select blog single layout with sidebar postion.', 'adiva' ),
            'options'  => array(
                'left' => array(
                    'alt' => esc_html__('Left Sidebar', 'adiva'),
                    'img' => ADIVA_URL . '/assets/images/layout/left-sidebar.jpg'
                ),
                'no' => array(
                    'alt' => esc_html__('No Sidebar', 'adiva'),
                    'img' => ADIVA_URL . '/assets/images/layout/no-sidebar.jpg'
                ),
                'right' => array(
                    'alt' => esc_html__('Right Sidebar', 'adiva'),
                    'img' => ADIVA_URL . '/assets/images/layout/right-sidebar.jpg'
                ),
            ),
            'default'  => 'left'
        ),
    )
) );

// Woocommerce
Redux::setSection( $opt_name, array(
    'title' => esc_html__( 'Shop', 'adiva' ),
    'id'     => 'shop',
    'icon'   => 'el el-shopping-cart',
    'fields' => array(
        array(
            'id'       => 'wc-shop-fullwidth',
            'type'     => 'switch',
            'title'    => esc_html__('Enable Fullwidth', 'adiva'),
            'on'       => esc_html__('Enable', 'adiva'),
			'off'      => esc_html__('Disable', 'adiva'),
			'default'  => 0,
        ),
        array(
            'id'       => 'wc-product-style',
            'type'     => 'select',
            'title'    => esc_html__( 'Product Hover Style', 'adiva' ),
            'options'  => array(
                '1' => esc_html__('Default', 'adiva'),
                '2' => esc_html__('All info on hover', 'adiva'),
                '3' => esc_html__('Product Style 3', 'adiva'),
            ),
            'default'  => '1'
        ),
        array(
            'id'       => 'wc-product-hover-presets',
            'type'     => 'image_select',
            'title'    => esc_html__( 'Product Hover Presets', 'adiva' ),
            'options'  => array(
                '1abc9c' => array(
                    'alt' => '1abc9c',
                    'img' => ADIVA_URL . '/assets/images/color-icons/1abc9c.png',
                ),
                '2e2e2e' => array(
                    'alt' => '2e2e2e',
                    'img' => ADIVA_URL . '/assets/images/color-icons/2e2e2e.png',
                ),
                '2ecc71' => array(
                    'alt' => '2ecc71',
                    'img' => ADIVA_URL . '/assets/images/color-icons/2ecc71.png',
                ),
                '7f8c8d' => array(
                    'alt' => '7f8c8d',
                    'img' => ADIVA_URL . '/assets/images/color-icons/7f8c8d.png',
                ),
                '9b59b6' => array(
                    'alt' => '9b59b6',
                    'img' => ADIVA_URL . '/assets/images/color-icons/9b59b6.png',
                ),
                '95a5a6' => array(
                    'alt' => '95a5a6',
                    'img' => ADIVA_URL . '/assets/images/color-icons/95a5a6.png',
                ),
                '01558f' => array(
                    'alt' => '01558f',
                    'img' => ADIVA_URL . '/assets/images/color-icons/01558f.png',
                ),
                '3498db' => array(
                    'alt' => '3498db',
                    'img' => ADIVA_URL . '/assets/images/color-icons/3498db.png',
                ),
                'bdc3c7' => array(
                    'alt' => 'bdc3c7',
                    'img' => ADIVA_URL . '/assets/images/color-icons/bdc3c7.png',
                ),
                'c0392b' => array(
                    'alt' => 'c0392b',
                    'img' => ADIVA_URL . '/assets/images/color-icons/c0392b.png',
                ),
                'd35400' => array(
                    'alt' => 'd35400',
                    'img' => ADIVA_URL . '/assets/images/color-icons/d35400.png',
                ),
                'e67e22' => array(
                    'alt' => 'e67e22',
                    'img' => ADIVA_URL . '/assets/images/color-icons/e67e22.png',
                ),
                'e74c3c' => array(
                    'alt' => 'e74c3c',
                    'img' => ADIVA_URL . '/assets/images/color-icons/e74c3c.png',
                ),
                'ecf0f1' => array(
                    'alt' => 'ecf0f1',
                    'img' => ADIVA_URL . '/assets/images/color-icons/ecf0f1.png',
                ),
                'f1c40f' => array(
                    'alt' => 'f1c40f',
                    'img' => ADIVA_URL . '/assets/images/color-icons/f1c40f.png',
                ),
                'f39c12' => array(
                    'alt' => 'f39c12',
                    'img' => ADIVA_URL . '/assets/images/color-icons/f39c12.png',
                ),
            ),
            'default'  => '2e2e2e',
        ),
        array(
			'id'		=> 'wc-product-onsale',
			'type'		=> 'button_set',
			'title'		=> esc_html__( 'Product Sale Flash', 'adiva' ),
			'subtitle'	=> esc_html__( 'Product sale flash badges.', 'adiva' ),
			'options'	=> array(
                'txt' => esc_html__('Display sale Text', 'adiva'),
                'pct' => esc_html__('Display sale Percentage', 'adiva')
            ),
			'default'	=> 'txt'
		),
        array(
            'id'       => 'wc-quick-view',
            'type'     => 'switch',
            'title'    => esc_html__('Show/Hide Quickview Button', 'adiva'),
            'on'       => esc_html__('Show', 'adiva'),
			'off'      => esc_html__('Hide', 'adiva'),
			'default'  => 1,
        ),
        array(
            'id'       => 'wc-wishlist',
            'type'     => 'switch',
            'title'    => esc_html__('Show/Hide Wishlist', 'adiva'),
            'on'       => esc_html__('Show', 'adiva'),
			'off'      => esc_html__('Hide', 'adiva'),
			'default'  => 1,
        ),
        array(
            'id'       => 'wc-rating',
            'type'     => 'switch',
            'title'    => esc_html__('Show/Hide Rating', 'adiva'),
            'on'       => esc_html__('Show', 'adiva'),
			'off'      => esc_html__('Hide', 'adiva'),
			'default'  => 0,
        ),
        array(
            'id'       => 'wc-category-name',
            'type'     => 'switch',
            'title'    => esc_html__('Show/Hide Category Name', 'adiva'),
            'on'       => esc_html__('Show', 'adiva'),
			'off'      => esc_html__('Hide', 'adiva'),
			'default'  => 1
        ),
        array(
            'id'    => 'wc-attribute-variation',
            'type'  => 'switch',
            'title' => esc_html__('Show Attribute Variation','adiva'),
            'desc'  => esc_html__('Show attribute variation on product box', 'adiva'),
            'on'    => esc_html__('Show','adiva'),
			'off'   => esc_html__('Hide','adiva'),
			'default' => 1,
        ),
        array(
            'id'       => 'wc-product-image-hover',
            'type'     => 'select',
            'title'    => esc_html__('Hover Image Hover', 'adiva'),
            'options' => array(
                'no-effect'    => esc_html__( 'No Effect', 'adiva' ),
                'second-image' => esc_html__( 'Load Second Image', 'adiva' ),
            ),
            'default'  => 'second-image'
        ),
    )
) );

Redux::setSection( $opt_name, array(
    'title'      => esc_html__( 'Page Title', 'adiva' ),
    'id'         => 'wc_shop_page_title',
    'subsection' => true,
    'fields'     => array(
        array(
            'id'       => 'shop-title-background',
            'type'     => 'background',
            'title'    => esc_html__('Pages heading background', 'adiva'),
            'subtitle' => esc_html__('Set background image or color for shop.', 'adiva'),
            'output'   => array('.title-shop'),
            'default'  => array(
                'background-position' => 'center center',
                'background-size'     => 'cover'
            ),
        ),
    )
) );

Redux::setSection( $opt_name, array(
    'title'      => esc_html__( 'Shop action', 'adiva' ),
    'id'         => 'wc_shop_action',
    'subsection' => true,
    'fields'     => array(
        array(
            'id'       => 'wc-ajax-shop',
            'type'     => 'switch',
            'title'    => esc_html__('Enable Ajax Shop', 'adiva'),
            'on'       => esc_html__('Enable', 'adiva'),
			'off'      => esc_html__('Disable', 'adiva'),
			'default'  => 1,
        ),
        array(
            'id'       => 'wc-product-view',
            'type'     => 'button_set',
            'title'    => esc_html__('Shop products view', 'adiva'),
            'subtitle' => esc_html__('You can set different view mode for the shop page', 'adiva'),
            'options'  => array(
                'grid' => esc_html__('Grid', 'adiva'),
                'list' => esc_html__('List', 'adiva'),
            ),
            'default'  => 'grid'
        ),
        array(
            'id'       => 'wc-per-row-columns-selector',
            'type'     => 'switch',
            'title'    => esc_html__('Number of columns selector', 'adiva'),
            'subtitle' => esc_html__('Allow customers to change number of columns per row', 'adiva'),
            'default' => true,
        ),
        array(
            'id'       => 'wc-shop-filter',
            'type'     => 'switch',
            'title'    => esc_html__('Enable Filter', 'adiva'),
            'on'       => esc_html__('Enable', 'adiva'),
			'off'      => esc_html__('Disable', 'adiva'),
			'default'  => 1,
        ),
        array(
            'id'       => 'wc-shop-ordering',
            'type'     => 'switch',
            'title'    => esc_html__('Products ordering', 'adiva'),
            'on'       => esc_html__('Enable', 'adiva'),
			'off'      => esc_html__('Disable', 'adiva'),
			'default'  => 1,
        ),
        array(
            'id'       => 'wc-products-per-page',
            'type'     => 'switch',
            'title'    => esc_html__('Products per page', 'adiva'),
            'on'       => esc_html__('Enable', 'adiva'),
			'off'      => esc_html__('Disable', 'adiva'),
			'default'  => 1,
        ),
    )
) );

Redux::setSection( $opt_name, array(
    'title'      => esc_html__( 'Sub Category Setting', 'adiva' ),
    'id'         => 'wc_sub_category_setting',
    'subsection' => true,
    'fields'     => array(
        array(
            'id'       => 'wc-sub-category-column',
            'type'     => 'button_set',
            'title'    => esc_html__('Columns', 'adiva'),
            'options'  => array(
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
                '6' => '6',
            ),
            'default'  => '4'
        ),
    )
) );

Redux::setSection( $opt_name, array(
    'title'      => esc_html__( 'Product List Setting', 'adiva' ),
    'id'         => 'wc_product_list_setting',
    'subsection' => true,
    'fields'     => array(
        array(
            'id'       => 'wc-number-per-page',
            'type'     => 'text',
            'title'    => esc_html__( 'Per page', 'adiva' ),
            'subtitle' => esc_html__( 'How much items per page to show.', 'adiva' ),
            'validate' => 'numeric',
            'default'  => '12'
        ),
        array(
            'id'       => 'wc-product-column',
            'type'     => 'button_set',
            'title'    => esc_html__('Columns', 'adiva'),
            'options'  => array(
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
                '6' => '6',
            ),
            'default'  => '4'
        ),
        array(
            'id'       => 'wc-archive-style',
            'type'     => 'image_select',
            'title'    => esc_html__( 'Product List Style', 'adiva' ),
            'options'  => array(
                'grid' => array(
                    'alt' => esc_html__('Grid', 'adiva'),
                    'img' => ADIVA_URL . '/assets/images/layout/grid.jpg'
                ),
                'masonry' => array(
                    'alt' => esc_html__('Masonry', 'adiva'),
                    'img' => ADIVA_URL . '/assets/images/layout/masonry.jpg'
                ),
                'metro' => array(
                    'alt' => esc_html__('Metro', 'adiva'),
                    'img' => ADIVA_URL . '/assets/images/layout/masonry.jpg'
                )
            ),
            'default'  => 'grid'
        ),
        array(
            'id'       => 'wc-gutter-space',
            'type'     => 'select',
            'title'    => esc_html__('Gutter Space', 'adiva'),
            'options'  => array(
                '0'  => '0',
                '10' => '10',
                '20' => '20',
                '30' => '20',
                '40' => '40',
                '50' => '50',
                '60' => '60'
            ),
            'default' => '40'
        ),
        array(
            'id'       => 'wc-pagination-type',
            'type'     => 'button_set',
            'title'    => esc_html__('Shop Pagination', 'adiva'),
            'options'  => array(
                'number'   => esc_html__('Pagination links', 'adiva'),
                'loadmore' => esc_html__('Load more button', 'adiva'),
                'infinite' => esc_html__('Infinit scrolling', 'adiva'),
            ),
            'default' => 'number'
        ),
        array(
            'id'       => 'wc-shop-layout',
            'type'     => 'image_select',
            'title'    => esc_html__( 'Product List Layout', 'adiva' ),
            'subtitle' => esc_html__( 'Select shop page layout with sidebar postion.', 'adiva' ),
            'options'  => array(
                'left' => array(
                    'alt' => esc_html__('Left Sidebar', 'adiva'),
                    'img' => ADIVA_URL . '/assets/images/layout/left-sidebar.jpg'
                ),
                'no' => array(
                    'alt' => esc_html__('No Sidebar', 'adiva'),
                    'img' => ADIVA_URL . '/assets/images/layout/no-sidebar.jpg'
                ),
                'right' => array(
                    'alt' => esc_html__('Right Sidebar', 'adiva'),
                    'img' => ADIVA_URL . '/assets/images/layout/right-sidebar.jpg'
                ),
            ),
            'default'  => 'no'
        ),
    )
) );

Redux::setSection( $opt_name, array(
    'title'      => esc_html__( 'Single Product Setting', 'adiva' ),
    'id'         => 'wc_product_page',
    'subsection' => true,
    'fields'     => array(
        array(
            'id'       => 'single-product-sidebar',
            'type'     => 'image_select',
            'title'    => esc_html__( 'Single Product Layout', 'adiva' ),
            'subtitle' => esc_html__( 'Select single product page layout with sidebar postion.', 'adiva' ),
            'options'  => array(
                'left' => array(
                    'alt' => esc_html__('Left Sidebar', 'adiva'),
                    'img' => ADIVA_URL . '/assets/images/layout/left-sidebar.jpg'
                ),
                'no' => array(
                    'alt' => esc_html__('No Sidebar', 'adiva'),
                    'img' => ADIVA_URL . '/assets/images/layout/no-sidebar.jpg'
                ),
                'right' => array(
                    'alt' => esc_html__('Right Sidebar', 'adiva'),
                    'img' => ADIVA_URL . '/assets/images/layout/right-sidebar.jpg'
                ),
            ),
            'default'  => 'no'
        ),
        array(
            'id'       => 'wc-product-zoom-image',
            'type'     => 'switch',
            'title'    => esc_html__('Zoom image?', 'adiva'),
			'default'  => 1,
        ),
        array(
            'id'       => 'product-tab-layout',
            'type'     => 'button_set',
            'title'    => esc_html__('Tabs layout', 'adiva'),
            'options'  => array(
                'tabs'      => esc_html__('Tabs', 'adiva'),
                'accordion' => esc_html__('Accordion', 'adiva'),
            ),
            'default' => 'tabs'
        ),
        array (
            'id'      => 'single_ajax_add_to_cart',
            'type'    => 'switch',
            'title'   => esc_html__('AJAX Add to cart', 'adiva'),
            'subtitle'    => esc_html__('Turn on the AJAX add to cart option on the single product page. Will not work with plugins that add some custom fields to the add to cart form.

', 'adiva'),
            'default' => true
        ),
        array(
            'id'    => 'wc-single-shipping-return',
            'type'  => 'editor',
            'title' => esc_html__( 'Shipping & Return content', 'adiva' ),
            'desc'  => esc_html__( 'HTML is allowed', 'adiva' ),
        ),
        array(
            'id'       => 'wc-single-nagivation',
            'type'     => 'switch',
            'title'    => esc_html__('Enable Navigation?', 'adiva'),
			'default'  => 1,
        ),
        array(
            'id'       => 'section-upsell-product-start',
            'type'     => 'section',
            'title'    => esc_html__( 'You may also like..', 'adiva' ),
            'indent'   => true,
        ),
        array(
            'id'       => 'upsell-product-title',
            'type'     => 'text',
            'title'    => esc_html__('Title', 'adiva'),
            'default'  => 'YOU MAY ALSO LIKE..',
        ),
        array(
            'id'       => 'upsell-product-desc',
            'type'     => 'text',
            'title'    => esc_html__('Description', 'adiva'),
            'default'  => 'Includes products updated are similar or are same of quality',
        ),
        array(
            'id'       => 'section-upsell-product-end',
            'type'     => 'section',
            'indent'   => true,
        ),
        array(
            'id'       => 'section-related-product-start',
            'type'     => 'section',
            'title'    => esc_html__( 'Related Products', 'adiva' ),
            'indent'   => true,
        ),
        array(
            'id'       => 'related-product-title',
            'type'     => 'text',
            'title'    => esc_html__('Title', 'adiva'),
            'default'  => 'RELATED PRODUCTS',
        ),
        array(
            'id'       => 'related-product-desc',
            'type'     => 'text',
            'title'    => esc_html__('Description', 'adiva'),
            'default'  => 'Includes products updated are similar or are same of quality',
        ),
        array(
            'id'       => 'section-related-product-end',
            'type'     => 'section',
            'indent'   => true,
        ),
    )
) );

Redux::setSection( $opt_name, array(
    'title'      => esc_html__('Catalog mode', 'adiva'),
    'id'         => 'shop-catalog',
    'subsection' => true,
    'fields'     => array(
        array(
            'id'       => 'catalog-mode',
            'type'     => 'switch',
            'title'    => esc_html__('Enable catalog mode', 'adiva'),
            'subtitle' => esc_html__('You can hide all "Add to cart" buttons, cart widget, cart and checkout pages. This will allow you to showcase your products as an online catalog without ability to make a purchase.', 'adiva'),
            'default'  => false
        ),
    ),
) );

// Portfolio
Redux::setSection( $opt_name, array(
    'title'  => esc_html__( 'Portfolio', 'adiva' ),
    'id'     => 'portfolio',
    'icon'   => 'el el-filter',
    'fields' => array(
        array(
            'id'       => 'portfolio-title',
            'type'     => 'text',
            'title'    => esc_html__('Portfolio Title', 'adiva'),
            'default'  => esc_html__('Portfolio', 'adiva')
        ),
        array(
            'id'               => 'portfolio-title-background',
            'type'             => 'background',
            'title'            => esc_html__('Pages heading background', 'adiva'),
            'subtitle'         => esc_html__('Set background image or color for portfolio.', 'adiva'),
            'background-color' => false,
            'output'           => array('.title-portfolio'),
            'default'          => array(
                'background-position' => 'center center',
                'background-size'     => 'cover'
            ),
        ),
        array(
            'id'       => 'portfolio-fullwidth',
            'type'     => 'switch',
            'title'    => esc_html__('Full Width portfolio', 'adiva'),
            'subtitle' => esc_html__('Makes container 100% width of the page', 'adiva'),
            'on'       => esc_html__('On', 'adiva'),
			'off'      => esc_html__('Off', 'adiva'),
			'default'  => 0,
        ),
        array(
            'id'       => 'portfolio-cat-filter',
            'type'     => 'switch',
            'title'    => esc_html__('Show categories filters', 'adiva'),
            'on'       => esc_html__('On', 'adiva'),
			'off'      => esc_html__('Off', 'adiva'),
			'default'  => 1,
        ),
        array(
            'id'       => 'portfolio-style',
            'type'     => 'select',
            'title'    => esc_html__( 'Portfolio Style', 'adiva' ),
            'subtitle' => esc_html__('You can use different styles for your projects.', 'adiva'),
            'options'  => array(
                'default'                 => esc_html__('Show text on mouse over', 'adiva'),
                'hover-inverse'           => esc_html__('Alternative', 'adiva'),
                'text-under-image'        => esc_html__('Text under image', 'adiva'),
                'text-under-image-shadow' => esc_html__('Text under image with shadow', 'adiva'),
            ),
            'default'  => 'default'
        ),
        array(
            'id'       => 'portfolio-columns',
            'type'     => 'button_set',
            'title'    => esc_html__('Portfolio columns', 'adiva'),
            'subtitle' => esc_html__('How many projects you want to show per row', 'adiva'),
            'options'  => array(
                2 => '2',
                3 => '3',
                4 => '4',
                5 => '5',
                6 => '6'
            ),
            'default' => 3
        ),
        array(
            'id'       => 'portfolio-spacing',
            'type'     => 'button_set',
            'title'    => esc_html__('Space between projects', 'adiva'),
            'subtitle' => esc_html__('You can set different spacing between blocks on portfolio page', 'adiva'),
            'options'  => array(
                0  => '0',
                10 => '10',
                20 => '20',
                30 => '30',
                40 => '40',
            ),
            'default' => 10
        ),
        array(
            'id'       => 'portfolio-number-per-page',
            'type'     => 'text',
            'title'    => esc_html__( 'Items per page', 'adiva' ),
            'subtitle' => esc_html__( 'How much items per page to show.', 'adiva' ),
            'validate' => 'numeric',
            'default'  => '6'
        ),
        array(
            'id'       => 'portfolio-pagination-type',
            'type'     => 'button_set',
            'title'    => esc_html__('Portfolio pagination', 'adiva'),
            'options'  => array(
                'number'   => esc_html__('Pagination links', 'adiva'),
                'loadmore' => esc_html__('Load more button', 'adiva'),
                'infinite' => esc_html__('Infinit scrolling', 'adiva'),
            ),
            'default' => 'number'
        ),
        array(
            'id'       => 'portfolio-navigation',
            'type'     => 'switch',
            'title'    => esc_html__('Portfolio navigation', 'adiva'),
            'on'       => esc_html__('On', 'adiva'),
			'off'      => esc_html__('Off', 'adiva'),
			'default'  => 1,
        ),
        array(
            'id'       => 'portfolio-related',
            'type'     => 'switch',
            'title'    => esc_html__('Related Portfolio', 'adiva'),
            'subtitle' => esc_html__('Show related portfolio carousel.', 'adiva'),
            'default' => true
        ),
    )
) );


// START Typography
Redux::setSection( $opt_name, array(
    'title'  => esc_html__( 'Typography', 'adiva' ),
    'id'     => 'theme-typography',
    'icon'   => 'el el-font',
    'fields' => array(
        array(
            'id'          => 'text-font',
            'type'        => 'typography',
            'title'       => esc_html__('Text font', 'adiva'),
            'all_styles'  => true,
            'google'      => true,
            'font-backup' => true,
            'text-align'  => false,
            'letter-spacing' => true,
            'output'      => 'body',
            'units'       =>'px',
            'subtitle'    => esc_html__('Set you typography options for body, paragraphs.', 'adiva'),
            'default'     => array(
                'font-family'    => 'Roboto',
                'font-weight'    => 300,
                'line-height'    => '25px',
                'letter-spacing' => '0.4px',
                'font-size'      => '15px',
                'google'         => true,
                'color'          => '#555555',
                'font-backup'    => 'Arial, Helvetica, sans-serif'
            ),
        ),
        array(
            'id'             => 'heading-font',
            'type'           => 'typography',
            'title'          => esc_html__('Heading font', 'adiva'),
            'all_styles'     => true,
            'google'         => true,
            'font-backup'    => true,
            'text-align'     => false,
            'font-size'      => false,
            'letter-spacing' => true,
            'line-height'    => false,
            'output'         => array('h1, h2, h3, h4, h5, h6'),
            'units'          => 'px',
            'subtitle'       => esc_html__('Set you typography options for heading.', 'adiva'),
            'default'        => array(
                'font-family'    => 'Roboto',
                'font-weight'    => 500,
                'line-height'    => '28px',
                'letter-spacing' => '0.4px',
                'google'         => true,
                'color'          => '#000000',
                'font-backup'    => 'Arial, Helvetica, sans-serif'
            ),
        ),
        array(
            'id'             => 'menu-font',
            'type'           => 'typography',
            'title'          => esc_html__('Main menu font', 'adiva'),
            'all_styles'     => true,
            'google'         => true,
            'font-backup'    => true,
            'text-align'     => false,
            'line-height'    => false,
            'letter-spacing' => false,
            'line-height'    => false,
            'output'         => array('.primary-menu > li > a'),
            'units'          => 'px',
            'subtitle'       => esc_html__('Set you typography options for main menu.', 'adiva'),
            'default'        => array(
                'font-family'    => 'Roboto',
                'font-weight'    => 400,
                'google'         => true,
                'color'          => '#222222',
                'font-backup'    => 'Arial, Helvetica, sans-serif'
            ),
        ),
    )
) );

Redux::setSection( $opt_name, array(
    'title'  => esc_html__( 'Color Scheme', 'adiva' ),
    'id'     => 'color-scheme',
    'icon'   => 'el el-brush',
    'fields' => array(
        array(
			'id'              => 'primary-color',
			'type'            => 'color',
			'title'           => esc_html__('Primary Color', 'adiva'),
            'default'         => '#F86B73'
		),
    )
) );

// START Social Network
Redux::setSection( $opt_name, array(
    'title'  => esc_html__( 'Social Network', 'adiva' ),
    'id'     => 'social',
    'icon'   => 'el el-dribbble',
    'fields' => array(
        array(
            'id'       => 'facebook',
            'type'     => 'text',
            'title'    => esc_html__('Facebook', 'adiva'),
            'default'  => '#'
        ),
        array(
            'id'       => 'twitter',
            'type'     => 'text',
            'title'    => esc_html__('Twitter', 'adiva'),
            'default'  => '#'
        ),
        array(
            'id'       => 'google-plus',
            'type'     => 'text',
            'title'    => esc_html__('Google Plus', 'adiva'),
            'default'  => '#'
        ),
        array(
            'id'       => 'instagram',
            'type'     => 'text',
            'title'    => esc_html__('Instagram', 'adiva'),
            'default'  => '#'
        ),
        array(
            'id'       => 'pinterest',
            'type'     => 'text',
            'title'    => esc_html__('Pinterest', 'adiva'),
            'default'  => ''
        ),
        array(
            'id'       => 'vimeo',
            'type'     => 'text',
            'title'    => esc_html__('Vimeo', 'adiva'),
            'default'  => ''
        ),
        array(
            'id'       => 'youtube',
            'type'     => 'text',
            'title'    => esc_html__('YouTube', 'adiva'),
            'default'  => ''
        ),
        array(
            'id'       => 'dribbble',
            'type'     => 'text',
            'title'    => esc_html__('Dribbble', 'adiva'),
            'default'  => ''
        ),
        array(
            'id'       => 'tumblr',
            'type'     => 'text',
            'title'    => esc_html__('Tumblr', 'adiva'),
            'default'  => ''
        ),
        array(
            'id'       => 'linkedin',
            'type'     => 'text',
            'title'    => esc_html__('LinkedIn', 'adiva'),
            'default'  => ''
        ),
        array(
            'id'       => 'flickr',
            'type'     => 'text',
            'title'    => esc_html__('Flickr', 'adiva'),
            'default'  => ''
        ),
        array(
            'id'       => 'github',
            'type'     => 'text',
            'title'    => esc_html__('GitHub', 'adiva'),
            'default'  => ''
        ),
        array(
            'id'       => 'lastfm',
            'type'     => 'text',
            'title'    => esc_html__('Last.fm', 'adiva'),
            'default'  => ''
        ),
        array(
            'id'       => 'paypal',
            'type'     => 'text',
            'title'    => esc_html__('PayPal', 'adiva'),
            'default'  => ''
        ),
        array(
            'id'       => 'wordpress',
            'type'     => 'text',
            'title'    => esc_html__('WordPress', 'adiva'),
            'default'  => ''
        ),
        array(
            'id'       => 'skype',
            'type'     => 'text',
            'title'    => esc_html__('Skype', 'adiva'),
            'default'  => ''
        ),
        array(
            'id'       => 'yahoo',
            'type'     => 'text',
            'title'    => esc_html__('Yahoo', 'adiva'),
            'default'  => ''
        ),
        array(
            'id'       => 'reddit',
            'type'     => 'text',
            'title'    => esc_html__('Reddit', 'adiva'),
            'default'  => ''
        ),
        array(
            'id'       => 'deviantart',
            'type'     => 'text',
            'title'    => esc_html__('DeviantART', 'adiva'),
            'default'  => ''
        ),
        array(
            'id'       => 'steam',
            'type'     => 'text',
            'title'    => esc_html__('Steam', 'adiva'),
            'default'  => ''
        ),
        array(
            'id'       => 'foursquare',
            'type'     => 'text',
            'title'    => esc_html__('Foursquare', 'adiva'),
            'default'  => ''
        ),
        array(
            'id'       => 'behance',
            'type'     => 'text',
            'title'    => esc_html__('Behance', 'adiva'),
            'default'  => ''
        ),
        array(
            'id'       => 'xing',
            'type'     => 'text',
            'title'    => esc_html__('Xing', 'adiva'),
            'default'  => ''
        ),
        array(
            'id'       => 'stumbleupon',
            'type'     => 'text',
            'title'    => esc_html__('StumbleUpon', 'adiva'),
            'default'  => ''
        ),
    )
) );

// Maintenance
Redux::setSection( $opt_name, array(
    'title' => esc_html__( 'Maintenance Mode', 'adiva' ),
    'id'     => 'maintenance',
    'icon'   => 'el el-time',
    'fields' => array(
        array(
            'id'       => 'maintenance-mode',
            'type'     => 'switch',
            'title'    => esc_html__('Maintenance Mode', 'adiva'),
            'on'       => esc_html__('Enable', 'adiva'),
			'off'      => esc_html__('Disable', 'adiva'),
			'default'  => 0,
        ),
        array(
            'id'       => 'section-maintenance-background-start',
            'title'    => esc_html__('Maintenance Background', 'adiva'),
            'type'     => 'section',
            'indent'   => true,
        ),
        array(
            'id'      => 'maintenance-background',
            'type'    => 'background',
            'title'   => esc_html__( 'Background', 'adiva' ),
            'background-color'      => false,
            'default' => array(
                'background-image'      => '',
                'background-color'      => ''
            ),
            'output' => 'body.offline'
        ),
        array(
            'id'       => 'section-maintenance-background-end',
            'type'     => 'section',
            'indent'   => true,
        ),
        array(
            'id'       => 'section-maintenance-text-start',
            'title'    => esc_html__('Maintenance Text', 'adiva'),
            'type'     => 'section',
            'indent'   => true,
        ),
        array(
            'id'    => 'maintenance-title',
            'type'  => 'text',
            'title' => esc_html__( 'Title', 'adiva' ),
            'default' => 'COMING SOON'
        ),
        array(
            'id'    => 'maintenance-message',
            'type'  => 'textarea',
            'title' => esc_html__( 'Message', 'adiva' ),
            'default' => 'We are working very hard to give you the best experience with this one. You will love Jms Adiva as much as we do. It will morph perfectly on your needs!'
        ),
        array(
            'id'       => 'section-maintenance-text-end',
            'type'     => 'section',
            'indent'   => true,
        ),
        array(
            'id'       => 'maintenance-countdown',
            'type'     => 'switch',
            'title'    => esc_html__('Enable Countdown', 'adiva'),
            'on'       => esc_html__('Enable', 'adiva'),
			'off'      => esc_html__('Disable', 'adiva'),
			'default'  => 1,
        ),
        array(
            'id'       => 'maintenance-date',
            'type'     => 'select',
            'title'    => esc_html__('Date', 'adiva'),
            'options'  => array(
                '01' => '01',
				'02' => '02',
				'03' => '03',
				'04' => '04',
				'05' => '05',
				'06' => '06',
				'07' => '07',
				'08' => '08',
				'09' => '09',
				'10' => '10',
				'11' => '11',
				'12' => '12',
				'13' => '13',
				'14' => '14',
				'15' => '15',
				'16' => '16',
				'17' => '17',
				'18' => '18',
				'19' => '19',
				'20' => '20',
				'21' => '21',
				'22' => '22',
				'23' => '23',
				'24' => '24',
				'25' => '25',
				'26' => '26',
				'27' => '27',
				'28' => '28',
				'29' => '29',
				'30' => '30',
				'31' => '31'
            ),
            'default'  => '15',
            'required' => array( 'maintenance-countdown', '=', 1 )
        ),
        array(
            'id'       => 'maintenance-month',
            'type'     => 'select',
            'title'    => esc_html__('Month', 'adiva'),
            'options'  => array(
                '01' => esc_html__('January', 'adiva'),
			    '02'  => esc_html__('Febuary', 'adiva'),
			    '03'  => esc_html__('March', 'adiva'),
			    '04'  => esc_html__('April', 'adiva'),
			    '05'  => esc_html__('May', 'adiva'),
			    '06'  => esc_html__('June', 'adiva'),
			    '07'  => esc_html__('July', 'adiva'),
			    '08'  => esc_html__('August', 'adiva'),
			    '09'  => esc_html__('September', 'adiva'),
			    '10' => esc_html__('October', 'adiva'),
			    '11' => esc_html__('November', 'adiva'),
			    '12' => esc_html__('December', 'adiva'),
            ),
            'default'  => '03',
            'required' => array( 'maintenance-countdown', '=', 1 )
        ),
        array(
            'id'       => 'maintenance-year',
            'type'     => 'select',
            'title'    => esc_html__('Year', 'adiva'),
            'options'  => array(
				'2018' => '2018',
				'2019' => '2019',
				'2020' => '2020'
            ),
            'default'  => '2018',
            'required' => array( 'maintenance-countdown', '=', 1 )
        ),
    ),
) );

// Custom Js
Redux::setSection( $opt_name, array(
    'title'  => esc_html__('Custom JS', 'adiva'),
    'id'     => 'custom_js',
    'icon'   => 'el-icon-magic',
    'fields' => array (
        array(
            'id'    => 'custom_js',
            'type'  => 'ace_editor',
            'mode'  => 'javascript',
            'title' => esc_html__('Global Custom JS', 'adiva'),
        ),
        array(
            'id'    => 'js_ready',
            'type'  => 'ace_editor',
            'mode'  => 'javascript',
            'title' => esc_html__('On document ready', 'adiva'),
            'desc'  => esc_html__('Will be executed on $(document).ready()', 'adiva')
        ),
    ),
) );
