<?php
/**
 * Denso Customizer functionality
 *
 * @package WordPress
 * @subpackage Denso
 * @since Denso 1.0
 */

/**
 * Add postMessage support for site title and description for the Customizer.
 *
 * @since Denso 1.0
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */

function denso_customize_register( $wp_customize ) {
	$color_scheme = denso_get_color_scheme();

	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	// Add color scheme setting and control.
	$wp_customize->add_setting( 'color_scheme', array(
		'default'           => 'default',
		'sanitize_callback' => 'denso_sanitize_color_scheme',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'color_scheme', array(
		'label'    => esc_html__( 'Base Color Scheme', 'denso' ),
		'section'  => 'colors',
		'type'     => 'select',
		'choices'  => denso_get_color_scheme_choices(),
		'priority' => 1,
	) );

	// Add custom header and sidebar text color setting and control.
	$wp_customize->add_setting( 'sidebar_textcolor', array(
		'default'           => $color_scheme[4],
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sidebar_textcolor', array(
		'label'       => esc_html__( 'Header and Sidebar Text Color', 'denso' ),
		'description' => esc_html__( 'Applied to the header on small screens and the sidebar on wide screens.', 'denso' ),
		'section'     => 'colors',
	) ) );

	// Remove the core header textcolor control, as it shares the sidebar text color.
	

	// Add custom header and sidebar background color setting and control.
	$wp_customize->add_setting( 'header_background_color', array(
		'default'           => $color_scheme[1],
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'header_background_color', array(
		'label'       => esc_html__( 'Header and Sidebar Background Color', 'denso' ),
		'description' => esc_html__( 'Applied to the header on small screens and the sidebar on wide screens.', 'denso' ),
		'section'     => 'colors',
	) ) );

	// Add an additional description to the header image section.
	$wp_customize->get_section( 'header_image' )->description = esc_html__( 'Applied to the header on small screens and the sidebar on wide screens.', 'denso' );
	$wp_customize->remove_section( 'header_image' );
	$wp_customize->remove_section( 'colors' );
	$wp_customize->remove_section( 'background_image' );
}
add_action( 'customize_register', 'denso_customize_register', 20 );


/**
 * Register color schemes for Denso.
 *
 * Can be filtered with {@see 'denso_color_schemes'}.
 *
 * The order of colors in a colors array:
 * 1. Main Background Color.
 * 2. Sidebar Background Color.
 * 3. Box Background Color.
 * 4. Main Text and Link Color.
 * 5. Sidebar Text and Link Color.
 * 6. Meta Box Background Color.
 *
 * @since Denso 1.0
 *
 * @return array An associative array of color scheme options.
 */
function denso_get_color_schemes() {
	/**
	 * Filter the color schemes registered for use with Denso.
	 *
	 * The default schemes include 'default', 'dark', 'yellow', 'pink', 'purple', and 'blue'.
	 *
	 * @since Denso 1.0
	 *
	 * @param array $schemes {
	 *     Associative array of color schemes data.
	 *
	 *     @type array $slug {
	 *         Associative array of information for setting up the color scheme.
	 *
	 *         @type string $label  Color scheme label.
	 *         @type array  $colors HEX codes for default colors prepended with a hash symbol ('#').
	 *                              Colors are defined in the following order: Main background, sidebar
	 *                              background, box background, main text and link, sidebar text and link,
	 *                              meta box background.
	 *     }
	 * }
	 */
	return apply_filters( 'denso_color_schemes', array(
		'default' => array(
			'label'  => esc_html__( 'Default', 'denso' ),
			'colors' => array(
				'#f1f1f1',
				'#ffffff',
				'#ffffff',
				'#333333',
				'#333333',
				'#f7f7f7',
			),
		),
		'dark'    => array(
			'label'  => esc_html__( 'Dark', 'denso' ),
			'colors' => array(
				'#111111',
				'#202020',
				'#202020',
				'#bebebe',
				'#bebebe',
				'#1b1b1b',
			),
		),
		'yellow'  => array(
			'label'  => esc_html__( 'Yellow', 'denso' ),
			'colors' => array(
				'#f4ca16',
				'#ffdf00',
				'#ffffff',
				'#111111',
				'#111111',
				'#f1f1f1',
			),
		),
		'pink'    => array(
			'label'  => esc_html__( 'Pink', 'denso' ),
			'colors' => array(
				'#ffe5d1',
				'#e53b51',
				'#ffffff',
				'#352712',
				'#ffffff',
				'#f1f1f1',
			),
		),
		'purple'  => array(
			'label'  => esc_html__( 'Purple', 'denso' ),
			'colors' => array(
				'#674970',
				'#2e2256',
				'#ffffff',
				'#2e2256',
				'#ffffff',
				'#f1f1f1',
			),
		),
		'blue'   => array(
			'label'  => esc_html__( 'Blue', 'denso' ),
			'colors' => array(
				'#e9f2f9',
				'#55c3dc',
				'#ffffff',
				'#22313f',
				'#ffffff',
				'#f1f1f1',
			),
		),
	) );
}

if ( ! function_exists( 'denso_get_color_scheme' ) ) :
/**
 * Get the current Denso color scheme.
 *
 * @since Denso 1.0
 *
 * @return array An associative array of either the current or default color scheme hex values.
 */
function denso_get_color_scheme() {
	$color_scheme_option = get_theme_mod( 'color_scheme', 'default' );
	$color_schemes       = denso_get_color_schemes();

	if ( array_key_exists( $color_scheme_option, $color_schemes ) ) {
		return $color_schemes[ $color_scheme_option ]['colors'];
	}

	return $color_schemes['default']['colors'];
}
endif; // denso_get_color_scheme

if ( ! function_exists( 'denso_get_color_scheme_choices' ) ) :
/**
 * Returns an array of color scheme choices registered for Denso.
 *
 * @since Denso 1.0
 *
 * @return array Array of color schemes.
 */
function denso_get_color_scheme_choices() {
	$color_schemes                = denso_get_color_schemes();
	$color_scheme_control_options = array();

	foreach ( $color_schemes as $color_scheme => $value ) {
		$color_scheme_control_options[ $color_scheme ] = $value['label'];
	}

	return $color_scheme_control_options;
}
endif; // denso_get_color_scheme_choices

if ( ! function_exists( 'denso_sanitize_color_scheme' ) ) :
/**
 * Sanitization callback for color schemes.
 *
 * @since Denso 1.0
 *
 * @param string $value Color scheme name value.
 * @return string Color scheme name.
 */
function denso_sanitize_color_scheme( $value ) {
	$color_schemes = denso_get_color_scheme_choices();

	if ( ! array_key_exists( $value, $color_schemes ) ) {
		$value = 'default';
	}

	return $value;
}
endif; // denso_sanitize_color_scheme

/**
 * Binds JS listener to make Customizer color_scheme control.
 *
 * Passes color scheme data as colorScheme global.
 *
 * @since Denso 1.0
 */
function denso_customize_control_js() {
	$js_folder = denso_get_js_folder();
    $min = denso_get_asset_min();
	wp_enqueue_script( 'color-scheme-control', $js_folder . '/customize/color-scheme-control'.$min.'.js', array( 'customize-controls', 'iris', 'underscore', 'wp-util' ), '20141216', true );
	wp_localize_script( 'color-scheme-control', 'colorScheme', denso_get_color_schemes() );
}
add_action( 'customize_controls_enqueue_scripts', 'denso_customize_control_js' );

/**
 * Binds JS handlers to make the Customizer preview reload changes asynchronously.
 *
 * @since Denso 1.0
 */
function denso_customize_preview_js() {
	$js_folder = denso_get_js_folder();
    $min = denso_get_asset_min();
	wp_enqueue_script( 'denso-customize-preview', $js_folder . '/customize/customize-preview'.$min.'.js', array( 'customize-preview' ), '20141216', true );
}
add_action( 'customize_preview_init', 'denso_customize_preview_js' );