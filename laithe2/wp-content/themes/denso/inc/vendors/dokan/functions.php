<?php

if ( ! function_exists( 'denso_dokan_sidebars' ) ) {
	
	function denso_dokan_sidebars() {
		register_sidebar( array(
			'name' 				=> esc_html__( 'Store Sidebar', 'denso' ),
			'id' 				=> 'store-sidebar',
			'before_widget'		=> '<aside class="widget %2$s">',
			'after_widget' 		=> '</aside>',
			'before_title' 		=> '<h2 class="widget-title">',
			'after_title' 		=> '</h2>'
		));
	}

}

add_action( 'widgets_init', 'denso_dokan_sidebars' );


function denso_dokan_redux_config( $sections, $sidebars, $columns ) {
	// Dokan Store Sidebar
    $dokan_fields = array(
        array(
            'id' => 'dokan_sidebar_layout',
            'type' => 'image_select',
            'compiler' => true,
            'title' => esc_html__('Dokan Store Layout', 'denso'),
            'subtitle' => esc_html__('Select the layout you want to apply on your Single Product Page.', 'denso'),
            'options' => array(
                'main' => array(
                    'title' => esc_html__('Main Only', 'denso'),
                    'alt' => esc_html__('Main Only', 'denso'),
                    'img' => get_template_directory_uri() . '/inc/assets/images/screen1.png'
                ),
                'left-main' => array(
                    'title' => esc_html__('Left - Main Sidebar', 'denso'),
                    'alt' => esc_html__('Left - Main Sidebar', 'denso'),
                    'img' => get_template_directory_uri() . '/inc/assets/images/screen2.png'
                ),
                'main-right' => array(
                    'title' => esc_html__('Main - Right Sidebar', 'denso'),
                    'alt' => esc_html__('Main - Right Sidebar', 'denso'),
                    'img' => get_template_directory_uri() . '/inc/assets/images/screen3.png'
                ),
            ),
            'default' => 'left-main'
        ),
        array(
            'id' => 'dokan_sidebar_fullwidth',
            'type' => 'switch',
            'title' => esc_html__('Is Full Width?', 'denso'),
            'default' => false
        ),
    );

    if ( dokan_get_option( 'enable_theme_store_sidebar', 'dokan_general', 'off' ) !== 'off' ) {
    	
    	$dokan_fields[] = array(
            'id' => 'dokan_left_sidebar',
            'type' => 'select',
            'title' => esc_html__('Dokan Store Left Sidebar', 'denso'),
            'subtitle' => esc_html__('Choose a sidebar for left sidebar.', 'denso'),
            'options' => $sidebars
        );

        $dokan_fields[] = array(
            'id' => 'dokan_right_sidebar',
            'type' => 'select',
            'title' => esc_html__('Dokan Store Right Sidebar', 'denso'),
            'subtitle' => esc_html__('Choose a sidebar for right sidebar.', 'denso'),
            'options' => $sidebars
        );
    }
    $sections[] = array(
        'title' => esc_html__('Dokan Store Sidebar', 'denso'),
        'fields' => $dokan_fields
    );

    return $sections;
}
add_filter( 'denso_redux_framwork_configs', 'denso_dokan_redux_config', 20, 3 );



// layout class for woo page
if ( !function_exists('denso_dokan_content_class') ) {
    function denso_dokan_content_class( $class ) {
        if( denso_get_config('dokan_sidebar_fullwidth') ) {
            return 'container-fluid';
        }
        return $class;
    }
}
add_filter( 'denso_dokan_content_class', 'denso_dokan_content_class' );

// get layout configs
if ( !function_exists('denso_get_dokan_layout_configs') ) {
    function denso_get_dokan_layout_configs() {
        
        $left = denso_get_config('dokan_left_sidebar');
        $right = denso_get_config('dokan_right_sidebar');

        switch ( denso_get_config('dokan_sidebar_layout') ) {
            case 'left-main':
                $configs['left'] = array( 'sidebar' => $left, 'class' => 'col-lg-2 col-md-3 col-sm-12 col-xs-12'  );
                $configs['main'] = array( 'class' => 'col-lg-10 col-md-9 col-sm-12 col-xs-12' );
                break;
            case 'main-right':
                $configs['right'] = array( 'sidebar' => $right,  'class' => 'col-lg-2 col-md-3 col-sm-12 col-xs-12' ); 
                $configs['main'] = array( 'class' => 'col-lg-10 col-md-9 col-sm-12 col-xs-12' );
                break;
            case 'main':
                $configs['main'] = array( 'class' => 'col-lg-12 col-md-12 col-sm-12 col-xs-12' );
                break;
            case 'left-main-right':
                $configs['left'] = array( 'sidebar' => $left,  'class' => 'col-lg-2 col-md-3 col-sm-12 col-xs-12'  );
                $configs['right'] = array( 'sidebar' => $right, 'class' => 'col-lg-2 col-md-3 col-sm-12 col-xs-12' ); 
                $configs['main'] = array( 'class' => 'col-lg-8 col-md-6 col-sm-12 col-xs-12' );
                break;
            default:
                $configs['main'] = array( 'class' => 'col-lg-12 col-md-12 col-sm-12 col-xs-12' );
                break;
        }

        return $configs; 
    }
}