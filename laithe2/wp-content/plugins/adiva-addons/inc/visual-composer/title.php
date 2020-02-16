<?php
/**
* ------------------------------------------------------------------------------------------------
* Custom title shortcode
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'adiva_vc_map_title' ) ) {
	function adiva_vc_map_title() {
        vc_map(
            array(
                'name'        => esc_html__( 'Custom Title', 'adiva-addons' ),
                'base'        => 'adiva_addons_title',
                'icon'        => 'jms-icon',
                'category'    => esc_html__( 'JMS Addons', 'adiva-addons' ),
                'params'      => array(
                    array(
                        'param_name'  => 'title',
                        'heading'     => esc_html__( 'Title', 'adiva-addons' ),
                        'type'        => 'textfield',
                        'value'       => 'Custom Title',
                        'admin_label' => true,
                    ),
                    array(
                        'param_name'  => 'subtitle',
                        'heading'     => esc_html__( 'Subtitle', 'adiva-addons' ),
                        'type'        => 'textfield',
                        'value'       => '',
                        'admin_label' => true,
                    ),
                    array(
                        'param_name'  => 'desc',
                        'heading'     => esc_html__( 'Description', 'adiva-addons' ),
                        'type'        => 'textarea',
                        'value'       => '',
                        'admin_label' => true,
                    ),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Subtitle style', 'adiva-addons' ),
						'param_name' => 'subtitle_style',
						'value'      => array(
							esc_html__( 'Default', 'adiva-addons' )    => 'default',
							esc_html__( 'Background', 'adiva-addons' ) => 'background'
						)
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Title color', 'adiva-addons' ),
						'param_name' => 'color',
						'value'      => array(
							esc_html__( 'Default', 'adiva-addons' )           => 'default',
							esc_html__( 'Primary color', 'adiva-addons' )     => 'primary',
							esc_html__( 'Black', 'adiva-addons' )             => 'black',
							esc_html__( 'White', 'adiva-addons' )             => 'white',
						)
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Title size', 'adiva-addons' ),
						'param_name' => 'size',
						'value'      => array(
							esc_html__( 'Default', 'adiva-addons' )     => 'default',
							esc_html__( 'Small', 'adiva-addons' )       => 'small',
							esc_html__( 'Medium', 'adiva-addons' )      => 'medium',
							esc_html__( 'Large', 'adiva-addons' )       => 'large',
							esc_html__( 'Extra Large', 'adiva-addons' ) => 'extra-large'
						),
						'std'	=> 'medium'
					),
					array(
                        'type'       => 'dropdown',
                        'heading'    => esc_html__( 'Align', 'adiva-addons' ),
                        'param_name' => 'align',
                        'value'      => array(
                            esc_html__( 'Left', 'adiva-addons' )   => 'left',
							esc_html__( 'Center', 'adiva-addons' ) => 'center',
                            esc_html__( 'Right', 'adiva-addons' )  => 'right',
                        ),
						'std'	=> 'center'
                    ),
                    vc_map_add_css_animation(),
                    array(
                        'param_name'  => 'el_class',
                        'heading'     => esc_html__( 'Extra class name', 'adiva-addons' ),
                        'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'adiva-addons' ),
                        'type' 	      => 'textfield',
                    ),
                    array(
                        'type'       => 'css_editor',
                        'heading'    => esc_html__( 'CSS', 'adiva-addons' ),
                        'param_name' => 'css',
                        'group'      => esc_html__( 'Design options', 'adiva-addons' ),
                    ),
                )
            )
        );
    }
    add_action( 'vc_before_init', 'adiva_vc_map_title' );
}
