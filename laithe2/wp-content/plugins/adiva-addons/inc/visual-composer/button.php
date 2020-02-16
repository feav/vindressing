<?php
/**
* ------------------------------------------------------------------------------------------------
* Adiva button shortcode
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'adiva_vc_map_button' ) ) {
	function adiva_vc_map_button() {
        vc_map(
            array(
                'name'        => esc_html__( 'Buttons', 'adiva-addons' ),
                'base'        => 'adiva_button',
                'icon'        => 'jms-icon',
                'category'    => esc_html__( 'JMS Addons', 'adiva-addons' ),
                'params'      => array(
                    array(
                        'type'       => 'textfield',
                        'heading'    => esc_html__( 'Title', 'adiva-addons' ),
                        'param_name' => 'title',
                        'admin_label' => true
                    ),
                    array(
                        'type'       => 'vc_link',
                        'heading'    => esc_html__( 'Link', 'adiva-addons' ),
                        'param_name' => 'link',
                    ),
                    array(
                        'type'       => 'dropdown',
                        'heading'    => esc_html__( 'Button color', 'adiva-addons' ),
                        'param_name' => 'color',
                        'value'      => array(
                            esc_html__( 'Default', 'adiva-addons' )           => 'default',
                            esc_html__( 'Primary color', 'adiva-addons' )     => 'primary',
                            esc_html__( 'Alternative color', 'adiva-addons' ) => 'alt',
                            esc_html__( 'Black', 'adiva-addons' )             => 'black',
                            esc_html__( 'White', 'adiva-addons' )             => 'white',
                        ),
                        'admin_label' => true
                    ),
                    array(
                        'type'       => 'dropdown',
                        'heading'    => esc_html__( 'Button style', 'adiva-addons' ),
                        'param_name' => 'style',
                        'value'      => array(
                            esc_html__( 'Default', 'adiva-addons' )     => 'default',
                            esc_html__( 'Bordered', 'adiva-addons' )    => 'bordered',
                            esc_html__( 'Link button', 'adiva-addons' ) => 'link',
                            esc_html__( 'Rounded', 'adiva-addons' )     => 'rounded',
                            esc_html__( '3D', 'adiva-addons' )          => '3d',
                        ),
                        'admin_label' => true
                    ),
                    array(
                        'type'       => 'dropdown',
                        'heading'    => esc_html__( 'Button size', 'adiva-addons' ),
                        'param_name' => 'size',
                        'value'      => array(
                            esc_html__( 'Default', 'adiva-addons' )     => 'default',
                            esc_html__( 'Extra Small', 'adiva-addons' ) => 'extra-small',
                            esc_html__( 'Small', 'adiva-addons' )       => 'small',
                            esc_html__( 'Large', 'adiva-addons' )       => 'large',
                            esc_html__( 'Extra Large', 'adiva-addons' ) => 'extra-large',
                        ),
                        'admin_label' => true
                    ),
                    array(
                        'type'       => 'checkbox',
                        'heading'    => esc_html__( 'Full width', 'adiva-addons' ),
                        'param_name' => 'full_width',
                        'value'      => array( esc_html__( 'Yes, please', 'adiva-addons' ) => 'yes' ),
                    ),
                    array(
                        'type'       => 'checkbox',
                        'heading'    => esc_html__( 'Button inline', 'adiva-addons' ),
                        'param_name' => 'button_inline',
                        'value'      => array( esc_html__( 'Yes, please', 'adiva-addons' ) => 'yes' ),
                    ),
                    array(
                        'type'       => 'dropdown',
                        'heading'    => esc_html__( 'Align', 'adiva-addons' ),
                        'param_name' => 'align',
                        'value'      => array(
                            ''                              => '',
                            esc_html__( 'left', 'adiva-addons' )   => 'left',
                            esc_html__( 'center', 'adiva-addons' ) => 'center',
                            esc_html__( 'right', 'adiva-addons' )  => 'right',
                        ),
                        'admin_label' => true,
                        'dependency'  => array(
                            'element'            => 'button_inline',
                            'value_not_equal_to' => array( 'yes' ),
                        ),
                    ),
                    vc_map_add_css_animation(),
                    array(
                        'param_name'  => 'el_class',
                        'heading'     => esc_html__( 'Extra class name', 'adiva-addons' ),
                        'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'adiva-addons' ),
                        'type' 	      => 'textfield',
                    ),
                    'admin_label' => true
                )
            )
        );
    }
    add_action( 'vc_before_init', 'adiva_vc_map_button' );
}
