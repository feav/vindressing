<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
$options     = array();

// -----------------------------------------
// Taxonomy Options                        -
// -----------------------------------------
$options[]   = array(
    'id'       => '_custom_product_cat_options',
    'taxonomy' => 'product_cat',
    'fields'   => array(
        array(
            'id'    => 'product-cat-bg',
            'type'  => 'background',
            'title' => esc_html__('Page Head Background', 'adiva-addons'),
        ),
    ),
);

CSFramework_Taxonomy::instance( $options );
