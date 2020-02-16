<?php
/**
* ------------------------------------------------------------------------------------------------
* Adiva testimonial shortcode
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'adiva_vc_map_testimonials' ) ) {
	function adiva_vc_map_testimonials() {
        vc_map( array(
			'name'                    => esc_html__( 'Testimonials', 'adiva-addons' ),
			'base'                    => 'testimonials',
			"as_parent"               => array('only' => 'testimonial'),
			"content_element"         => true,
			"show_settings_on_create" => false,
    		'icon'                    => 'jms-icon',
			'category'                => esc_html__( 'JMS Addons', 'adiva-addons' ),
			'description'             => esc_html__( 'User testimonials slider or grid', 'adiva-addons' ),
			'params'                  => array(
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Title', 'adiva-addons' ),
					'param_name' => 'title',
					'value'      => '',
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Layout', 'adiva-addons' ),
					'param_name' => 'layout',
					'value'      => array(
						esc_html__( 'Slider', 'adiva-addons' ) => 'slider',
						esc_html__( 'Grid', 'adiva-addons' )   => 'grid',
					),
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Style', 'adiva-addons' ),
					'param_name' => 'style',
					'value' => array(
						esc_html__( 'Standard', 'adiva-addons' ) => 'standard',
						esc_html__( 'Boxed', 'adiva-addons' )    => 'boxed',
					),
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Align', 'adiva-addons' ),
					'param_name' => 'align',
					'value'      => array(
						esc_html__( 'Center', 'adiva-addons' ) => 'center',
						esc_html__( 'Left', 'adiva-addons' ) => 'left',
						esc_html__( 'Right', 'adiva-addons' ) => 'right',
					),
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Columns', 'adiva-addons' ),
					'param_name' => 'columns',
					'value'      => array(
						1,2,3,4,5,6
					),
					'dependency' => array(
						'element' => 'layout',
						'value'   => array( 'grid' ),
					),
				),
                array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Color Text', 'adiva-addons' ),
					'param_name' => 'color_text',
					'value'      => array(
						esc_html__('Dark', 'adiva-addons') => 'dark',
                        esc_html__('Light', 'adiva-addons') => 'light',
					),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Extra class name', 'adiva-addons' ),
					'param_name'  => 'el_class',
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'adiva-addons' )
				),
                array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Item Spacing', 'adiva-addons' ),
					'param_name' => 'items_spacing',
                    'group'       => esc_html__( 'Slider Settings', 'adiva-addons' ),
                    'std'         => 40,
					'value'      => array(
						10,20,30,40,50,60
					),
					'dependency' => array(
						'element' => 'layout',
						'value'   => array( 'slider' ),
					),
				),
    			array(
					'param_name'  => 'items_desktop',
					'heading'     => esc_html__( 'Items Show On Desktop', 'adiva-addons' ),
                    'description' => esc_html__( 'Show number of items on desktop', 'adiva-addons'),
					'type'        => 'dropdown',
                    'group'       => esc_html__( 'Slider Settings', 'adiva-addons' ),
                    'std'         => 1,
                    'value'       => array(
        				esc_html__( '1 Item', 'adiva-addons' ) => 1,
        				esc_html__( '2 Items', 'adiva-addons' ) => 2,
        				esc_html__( '3 Items', 'adiva-addons' ) => 3,
        				esc_html__( '4 Items', 'adiva-addons' ) => 4,
        				esc_html__( '5 Items', 'adiva-addons' ) => 5
        			),
                    'dependency' => array(
						'element' => 'layout',
						'value'   => array( 'slider' ),
					),
				),
                array(
					'param_name'  => 'items_small_desktop',
					'heading'     => esc_html__( 'Items Show On Small Desktop', 'adiva-addons' ),
                    'description' => esc_html__( 'Show number of items on small desktop. Screen resolution of device >=992px and < 1199px.', 'adiva-addons'),
                    'type'        => 'dropdown',
                    'group'       => esc_html__( 'Slider Settings', 'adiva-addons' ),
                    'std'         => 1,
                    'value'       => array(
        				esc_html__( '1 Item', 'adiva-addons' ) => 1,
        				esc_html__( '2 Items', 'adiva-addons' ) => 2,
        				esc_html__( '3 Items', 'adiva-addons' ) => 3,
        				esc_html__( '4 Items', 'adiva-addons' ) => 4,
        			),
                    'dependency' => array(
						'element' => 'layout',
						'value'   => array( 'slider' ),
					),
				),
                array(
					'param_name'  => 'items_tablet',
					'heading'     => esc_html__( 'Items Show On Tablet Device', 'adiva-addons' ),
					'description' => esc_html__( 'Show number of items on tablet. Screen resolution of device >=621px and < 992px', 'adiva-addons'),
                    'type'        => 'dropdown',
                    'group'       => esc_html__( 'Slider Settings', 'adiva-addons' ),
                    'std'         => 1,
                    'value'       => array(
        				esc_html__( '1 Item', 'adiva-addons' ) => 1,
        				esc_html__( '2 Items', 'adiva-addons' ) => 2,
        				esc_html__( '3 Items', 'adiva-addons' ) => 3,
        			),
                    'dependency' => array(
						'element' => 'layout',
						'value'   => array( 'slider' ),
					),
				),
                array(
					'param_name'  => 'items_mobile',
					'heading'     => esc_html__( 'Items Show On Mobile Device', 'adiva-addons' ),
					'description' => esc_html__( 'Show number of items on mobile. Screen resolution of device >=445px and < 621px.', 'adiva-addons'),
                    'type'        => 'dropdown',
                    'group'       => esc_html__( 'Slider Settings', 'adiva-addons' ),
                    'std'         => 1,
                    'value'       => array(
        				esc_html__( '1 Item', 'adiva-addons' ) => 1,
        				esc_html__( '2 Items', 'adiva-addons' ) => 2,
        			),
                    'dependency' => array(
						'element' => 'layout',
						'value'   => array( 'slider' ),
					),
				),
                array(
					'param_name'  => 'items_small_mobile',
					'heading'     => esc_html__( 'Items Show On Small Mobile Device', 'adiva-addons' ),
					'description' => esc_html__( 'Show number of items on small mobile. Screen resolution of device < 445px.', 'adiva-addons'),
                    'type'        => 'dropdown',
                    'group'       => esc_html__( 'Slider Settings', 'adiva-addons' ),
                    'std'         => 1,
                    'value'       => array(
        				esc_html__( '1 Item', 'adiva-addons' ) => 1,
        				esc_html__( '2 Items', 'adiva-addons' ) => 2,
        			),
                    'dependency' => array(
						'element' => 'layout',
						'value'   => array( 'slider' ),
					),
				),
				array(
					'param_name' => 'navigation',
					'heading'    => esc_html__( 'Enable Navigation', 'adiva-addons' ),
					'type'       => 'checkbox',
                    'group'      => esc_html__( 'Slider Settings', 'adiva-addons' ),
                    'value'      => array( esc_html__( 'Yes', 'adiva-addons' ) => 'yes' ),
					'std'        => 'no',
                    'dependency' => array(
						'element' => 'layout',
						'value'   => array( 'slider' ),
					),
				),
				array(
					'param_name' => 'pagination',
					'heading'    => esc_html__( 'Enable Dots Pagination', 'adiva-addons' ),
					'type'       => 'checkbox',
                    'group'      => esc_html__( 'Slider Settings', 'adiva-addons' ),
					'value'      => array( esc_html__( 'Yes', 'adiva-addons' ) => 'yes' ),
					'std'        => 'yes',
                    'dependency' => array(
						'element' => 'layout',
						'value'   => array( 'slider' ),
					),
				),
                array(
                    'param_name'  	=> 'autoplay',
                    'heading'     	=> esc_html__( 'Autoplay', 'adiva-addons' ),
        			'description' 	=> esc_html__( 'Enables autoplay mode', 'adiva-addons' ),
        			'type'        	=> 'checkbox',
        			'group'         => esc_html__( 'Slider Settings', 'adiva-addons' ),
        			'value'       	=> array( esc_html__( 'Yes', 'adiva-addons' ) => 'yes' ),
					'std'        	=> 'no',
                    'dependency' => array(
						'element' => 'layout',
						'value'   => array( 'slider' ),
					),
        		),
        		array(
                    'param_name'  	=> 'loop',
        			'heading'     	=> esc_html__( 'Loop', 'adiva-addons' ),
                    'description' 	=> esc_html__( 'Inifnity loop. Duplicate last and first items to get loop illusion', 'adiva-addons' ),
                    'type'        	=> 'checkbox',
                    'group'         => esc_html__( 'Slider Settings', 'adiva-addons' ),
        			'value'       	=> array( esc_html__( 'Yes', 'adiva-addons' ) => 'yes' ),
					'std'        	=> 'no',
                    'dependency' => array(
						'element' => 'layout',
						'value'   => array( 'slider' ),
					),
        		),
			),
		    "js_view" => 'VcColumnView'
		));
	}
	add_action( 'vc_before_init', 'adiva_vc_map_testimonials' );
}

if( ! function_exists( 'adiva_vc_map_testimonial' ) ) {
	function adiva_vc_map_testimonial() {
        vc_map( array(
			'name'            => esc_html__( 'Testimonial', 'adiva-addons' ),
			'base'            => 'testimonial',
			'class'           => '',
			'as_child'        => array('only' => 'testimonials'),
			'content_element' => true,
            'icon'            => 'jms-icon',
			'category'        => esc_html__( 'JMS Addons', 'adiva-addons' ),
			'description'     => esc_html__( 'User testimonial', 'adiva-addons' ),
			'params'          => array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Name', 'adiva-addons' ),
					'param_name'  => 'name',
					'value'       => '',
					'description' => esc_html__( 'User name', 'adiva-addons' )
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Title', 'adiva-addons' ),
					'param_name'  => 'title',
					'value'       => '',
					'description' => esc_html__( 'User title', 'adiva-addons' )
				),
				array(
					'type'        => 'attach_image',
					'heading'     => esc_html__( 'User Avatar', 'adiva-addons' ),
					'param_name'  => 'image',
					'value'       => '',
					'description' => esc_html__( 'Select image from media library.', 'adiva-addons' )
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Image size', 'adiva-addons' ),
					'param_name'  => 'img_size',
					'description' => esc_html__( 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', 'adiva-addons' )
				),
				array(
					'type'       => 'textarea_html',
					'holder'     => 'div',
					'heading'    => esc_html__( 'Text', 'adiva-addons' ),
					'param_name' => 'content'
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Extra class name', 'adiva-addons' ),
					'param_name'  => 'el_class',
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'adiva-addons' )
				)
			)
		));
	}
	add_action( 'vc_before_init', 'adiva_vc_map_testimonial' );
}
