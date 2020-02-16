<?php

if ( ! defined( 'ABSPATH' ) ) {
    die;
} // Cannot access pages directly.

$options = array();

if ( isset( $_GET['post'] ) && $_GET['post'] == get_option( 'page_for_posts' ) ) return;

// -----------------------------------------
// Page Metabox Options                    -
// -----------------------------------------
$options[]    = array(
    'id'        => '_custom_page_options',
    'title'     => esc_html__('Page Options', 'adiva-addons'),
    'post_type' => 'page',
    'context'   => 'normal',
    'priority'  => 'default',
    'sections'  => array(
        array(
            'name'  => 's1',
            'fields' => array(
                array(
                    'id'    => 'page-width',
                    'type'  => 'select',
                    'title' => esc_html__('Page width', 'adiva-addons'),
                    'options'  => array(
                        'inherit' => esc_html__( 'Inherit', 'adiva-addons' ),
                        'default' => esc_html__( 'Default', 'adiva-addons' ),
                        'wide'    => esc_html__( 'Wide', 'adiva-addons' ),
                        'boxed'   => esc_html__( 'Boxed', 'adiva-addons' ),
                    ),
                    'default'  => 'inherit',
                ),
                array(
					'id'      => 'pagehead',
					'type'    => 'switcher',
					'title'   => esc_html__( 'Enable page title', 'adiva-addons' ),
					'default' => true
				),
                array(
					'id'      => 'breadcrumb',
					'type'    => 'switcher',
					'title'   => esc_html__( 'Enable Breadcrumb', 'adiva-addons' ),
					'default' => true,
				),
                array(
					'id'      => 'page-title',
					'type'    => 'text',
					'title'   => esc_html__( 'Page Title', 'adiva-addons' ),
					'default' => '',
                    'dependency'   => array( 'pagehead', '==', 'true' ),
				),
                array(
					'id'      => 'page-subtitle',
					'type'    => 'text',
					'title'   => esc_html__( 'Subtitle', 'adiva-addons' ),
					'default' => '',
                    'dependency'   => array( 'pagehead', '==', 'true' ),
				),
                array(
					'id'      => 'pagehead-bg',
					'type'    => 'image',
					'title'   => esc_html__( 'Page heading background image', 'adiva-addons' ),
                    'dependency'   => array( 'pagehead', '==', 'true' ),
				),
                array(
					'id'      => 'pagehead-bg-color',
					'type'    => 'color_picker',
					'title'   => esc_html__( 'Page heading background color', 'adiva-addons' ),
                    'dependency'   => array( 'pagehead', '==', 'true' ),
				),
                array(
					'id'      => 'pagehead-text-color',
					'type'    => 'radio',
					'title'   => esc_html__( 'Text color for heading', 'adiva-addons' ),
                    'options' => array(
                        ''        => esc_html__('Inherit from Theme Options (Page Heading >> Text color for page title)', 'adiva-addons'),
                        'light'   => esc_html__('Light', 'adiva-addons'),
                        'dark'    => esc_html__('Dark', 'adiva-addons'),
                    ),
                    'default'    => '',
                    'dependency' => array( 'pagehead', '==', 'true' ),
                ),
                array(
                    'id'    => 'page-sidebar-position',
                    'type'  => 'image_select',
                    'title' => esc_html__('Sidebar Position', 'adiva-addons'),
                    'desc'  => esc_html__('Choose The Sidebar', 'adiva-addons'),
                    'options'  => array(
                        'left'  => CS_URI . '/assets/images/layout/left-sidebar.jpg',
						'no'    => CS_URI . '/assets/images/layout/no-sidebar.jpg',
						'right' => CS_URI . '/assets/images/layout/right-sidebar.jpg',
                    ),
                    'default'  => 'right',
                ),
                array(
                    'id'    => 'page-header',
                    'type'  => 'select',
                    'title' => esc_html__('Header Style', 'adiva-addons'),
                    'desc'  => esc_html__('Choose the header style', 'adiva-addons'),
                    'options'  => array(
                        '' => esc_html__( '- Select header -', 'adiva-addons' ),
                        '1' => esc_html__( 'Header 1', 'adiva-addons' ),
                        '2' => esc_html__( 'Header 2', 'adiva-addons' ),
                        '3' => esc_html__( 'Header 3', 'adiva-addons' ),
                        '4' => esc_html__( 'Header 4', 'adiva-addons' ),
                        '5' => esc_html__( 'Header 5', 'adiva-addons' ),
                        '6' => esc_html__( 'Header 6', 'adiva-addons' ),
                    ),
                    'default'  => '',
                ),
                array(
					'id'      => 'header-fullwidth',
					'type'    => 'switcher',
					'title'   => esc_html__( 'Header fullwidth', 'adiva-addons' ),
					'default' => false,
				),
                array(
					'id'      => 'header-overlap',
					'type'    => 'switcher',
					'title'   => esc_html__( 'Header above the content', 'adiva-addons' ),
					'default' => false,
				),
                array(
					'id'      => 'header-text-color',
					'type'    => 'radio',
					'title'   => esc_html__( 'Text color for header', 'adiva-addons' ),
                    'options' => array(
                        ''      => esc_html__('Inherit from Theme Option (Header >> Header layout >> Header text color)', 'adiva-addons'),
                        'light' => esc_html__('Light', 'adiva-addons'),
                        'dark'  => esc_html__('Dark', 'adiva-addons'),
                    ),
                    'default'    => '',
                ),
                array(
					'id'      => 'open-categories',
					'type'    => 'switcher',
					'title'   => esc_html__( 'Open categories menu', 'adiva-addons' ),
                    'desc'    => esc_html__( 'Always shows categories navigation on this page', 'adiva-addons' ),
					'default' => false,
				),
                array(
					'id'      => 'disable-topbar',
					'type'    => 'switcher',
					'title'   => esc_html__( 'Disable topbar', 'adiva-addons' ),
                    'desc'    => esc_html__( 'You can disable topbar for this page', 'adiva-addons' ),
					'default' => false,
				),
                array(
					'id'      => 'disable-footer',
					'type'    => 'switcher',
					'title'   => esc_html__( 'Disable footer', 'adiva-addons' ),
                    'desc'    => esc_html__( 'You can disable footer for this page', 'adiva-addons' ),
					'default' => false,
				),
                array(
					'id'      => 'disable-copyright',
					'type'    => 'switcher',
					'title'   => esc_html__( 'Disable copyrights', 'adiva-addons' ),
                    'desc'    => esc_html__( 'You can disable copyright for this page', 'adiva-addons' ),
					'default' => false,
				),
            ),
        ),
    ),
);

// -----------------------------------------
// Single Product Metabox Options             -
// -----------------------------------------
$options[]    = array(
    'id'        => '_custom_single_product_options',
    'title'     => esc_html__('Product Settings', 'adiva-addons'),
    'post_type' => 'product',
    'context'   => 'normal',
    'priority'  => 'default',
    'sections'  => array(
        array(
            'name'  => 's4',
            'fields' => array(
                array(
                    'id'    => 'wc-single-product-style',
                    'type'  => 'image_select',
                    'title' => esc_html__('Style', 'adiva-addons'),
                    'options'  => array(
                        '1' => CS_URI . '/assets/images/product/product-style-1.jpg',
                        '2' => CS_URI . '/assets/images/product/product-style-2.jpg',
                        '3' => CS_URI . '/assets/images/product/product-style-3.jpg',
                        '4' => CS_URI . '/assets/images/product/product-style-4.jpg',
                    ),
                    'default'  => '1',
                ),
                array(
					'id'      => 'wc-thumbnail-position',
					'type'    => 'select',
					'title'   => esc_html__( 'Thumbnail Position', 'adiva-addons' ),
					'options' => array(
						'left'    => esc_html__( 'Left', 'adiva-addons' ),
						'bottom'  => esc_html__( 'Bottom', 'adiva-addons' ),
						'right'   => esc_html__( 'Right', 'adiva-addons' ),
						'outside' => esc_html__( 'Outside', 'adiva-addons' )
					),
					'default'    => 'left',
					'dependency' => array( 'wc-single-product-style_1', '==', true ),
				),
                array(
					'id'      => 'wc-product-gallery-style',
					'type'    => 'image_select',
					'title'   => esc_html__( 'Product Gallery Style', 'adiva-addons' ),
					'options' => array(
                        '1' => CS_URI . '/assets/images/product/product-style-2.jpg',
                        '2' => CS_URI . '/assets/images/product/combined-grid-1.jpg',
                        '3' => CS_URI . '/assets/images/product/combined-grid-2.jpg',
					),
					'default'    => '1',
					'dependency' => array( 'wc-single-product-style_2', '==', true ),
				),
                array(
					'id'      => 'wc-enable-background',
					'type'    => 'switcher',
					'title'   => esc_html__( 'Enable Background?', 'adiva-addons' ),
					'default'    => false,
				),
                array(
					'id'      => 'wc-single-background-color',
					'type'    => 'color_picker',
					'title'   => esc_html__( 'Background Color', 'adiva-addons' ),
					'default'    => '',
					'dependency' => array( 'wc-enable-background', '==', true ),
				),
                array(
                    'id'      => 'wc-product-video-url',
					'type'    => 'text',
                    'title'   => esc_html__( 'Video URL', 'adiva-addons' ),
					'default'    => '',
				),
            ),
        ),
    ),
);

$options[] = array(
	'id'        => '_custom_wc_thumb_options',
	'title'     => esc_html__( 'Thumbnail Size', 'adiva-addons'),
	'post_type' => 'product',
	'context'   => 'side',
	'priority'  => 'default',
	'sections'  => array(
		array(
			'name'  => 's3',
			'fields' => array(
				array(
					'id'      => 'wc-thumbnail-size',
					'type'    => 'switcher',
					'title'   => esc_html__( 'Enable Large Thumbnail', 'adiva-addons' ),
					'default' => false
				),
			),
		),
	),
);




CSFramework_Metabox::instance( $options );
