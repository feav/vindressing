<?php
/**
* ------------------------------------------------------------------------------------------------
* Adiva service shortcode
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'adiva_vc_map_services' ) ) {
	function adiva_vc_map_services() {
        vc_map(
    		array(
    			'name'     => esc_html__( 'Icon and Text', 'adiva-addons' ),
    			'base'     => 'adiva_addons_service',
    			'icon'     => 'jms-icon',
    			'category' => esc_html__( 'JMS Addons', 'adiva-addons' ),
    			'params'   => array(
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Icon/Image alignment', 'adiva-addons' ),
						'param_name' => 'alignment',
						'value'      => array(
							esc_html__( 'Top', 'adiva-addons' )   => 'top',
							esc_html__( 'Left', 'adiva-addons' )  => 'left',
							esc_html__( 'Right', 'adiva-addons' ) => 'right'
						),
						'save_always' => true,
					),
    				array(
    					'param_name' => 'icon',
    					'heading'    => esc_html__( 'Icon', 'adiva-addons' ),
    					'type'       => 'iconpicker',
    					'settings'   => array(
    						'emptyIcon'    => false,
    						'iconsPerPage' => 4000,
    						'type'         => 'linearicons'
    					) ,
    				),
    				array(
    					'param_name'  => 'title',
    					'heading'     => esc_html__( 'Title', 'adiva-addons' ),
    					'type'        => 'textfield',
    					'admin_label' => true,
    				),
    				array(
    					'param_name' => 'entry',
    					'heading'    => esc_html__( 'Content', 'adiva-addons' ),
    					'type'       => 'textarea'
    				),
    				array(
    					'param_name' => 'service_size',
    					'heading'    => esc_html__( 'Service Size', 'adiva-addons' ),
    					'type'       => 'dropdown',
    					'value' => array(
    						esc_html__( 'Default', 'adiva-addons' ) => '',
    						esc_html__( 'Large', 'adiva-addons' )   => 'large',
    					),
    				),
    				array(
    					'param_name'       => 'icon_color',
    					'heading'          => esc_html__( 'Icon Color', 'adiva-addons' ),
    					'type'             => 'colorpicker',
    					'edit_field_class' => 'vc_col-sm-4 vc_column',
    				),
    				array(
    					'param_name'       => 'title_color',
    					'heading'          => esc_html__( 'Title Color', 'adiva-addons' ),
    					'type'             => 'colorpicker',
    					'edit_field_class' => 'vc_col-sm-4 vc_column',
    				),
    				array(
    					'param_name'       => 'content_color',
    					'heading'          => esc_html__( 'Content Color', 'adiva-addons' ),
    					'type'             => 'colorpicker',
    					'edit_field_class' => 'vc_col-sm-4 vc_column',
    				),
    				vc_map_add_css_animation(),
    				array(
    					'param_name'  => 'el_class',
    					'heading'     => esc_html__( 'Extra class name', 'adiva-addons' ),
    					'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'adiva-addons' ),
    					'type' 	      => 'textfield',
    				),
    				array(
    		            'type'        	=> 'css_editor',
    		            'heading'     	=> esc_html__( 'Css', 'adiva-addons' ),
    		            'param_name'  	=> 'css',
    		            'group'       	=> esc_html__( 'Design options', 'adiva-addons' ),
    		            'admin_label' 	=> false,
    				),
    			)
    		)
    	);
	}
	add_action( 'vc_before_init', 'adiva_vc_map_services' );
}
