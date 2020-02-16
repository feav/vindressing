<?php
/**
* ------------------------------------------------------------------------------------------------
* Adiva social icons shortcode
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'adiva_vc_map_social_icons' ) ) {
	function adiva_vc_map_social_icons() {
        vc_map(
            array(
                'name'        => esc_html__( 'Social icons', 'adiva-addons' ),
                'base'        => 'adiva_social_icons',
                'category'    => esc_html__( 'JMS Addons', 'adiva-addons' ),
                'icon'        => 'jms-icon',
                'params'      => array(
                    array(
                        'type'       => 'dropdown',
                        'heading'    => esc_html__( 'Align', 'adiva-addons' ),
                        'param_name' => 'align',
                        'value'      => array(
                            esc_html__( 'Left', 'adiva-addons' )   => 'left',
                            esc_html__( 'Center', 'adiva-addons' ) => 'center',
                            esc_html__( 'Right', 'adiva-addons' )  => 'right',
                        ),
                        'admin_label' => true,
                    ),
                    array(
                        'type'       => 'textfield',
                        'heading'    => esc_html__( 'Facebook link', 'adiva-addons' ),
                        'param_name' => 'facebook',
                    ),
                    array(
                        'type'       => 'textfield',
                        'heading'    => esc_html__( 'Twitter link', 'adiva-addons' ),
                        'param_name' => 'twitter',
                    ),
                    array(
                        'type'       => 'textfield',
                        'heading'    => esc_html__( 'Google+ link', 'adiva-addons' ),
                        'param_name' => 'google_plus',
                    ),
                    array(
                        'type'       => 'textfield',
                        'heading'    => esc_html__( 'Linkedin link', 'adiva-addons' ),
                        'param_name' => 'linkedin',
                    ),
                    array(
                        'type'       => 'textfield',
                        'heading'    => esc_html__( 'Skype link', 'adiva-addons' ),
                        'param_name' => 'skype',
                    ),
                    array(
                        'type'       => 'textfield',
                        'heading'    => esc_html__( 'Instagram link', 'adiva-addons' ),
                        'param_name' => 'instagram',
                    ),
                    array(
                        'type'       => 'dropdown',
                        'heading'    => esc_html__( 'Layout', 'adiva-addons' ),
                        'param_name' => 'layout',
                        'value'      => array(
                            esc_html__( 'Default', 'adiva-addons' )    => 'default',
                            esc_html__( 'With hover', 'adiva-addons' ) => 'hover',
                        ),
                        'admin_label' => true,
                    ),
                    array(
                        'type'       => 'dropdown',
                        'heading'    => esc_html__( 'Social buttons size', 'adiva-addons' ),
                        'param_name' => 'size',
                        'value'      => array(
                            esc_html__( 'Default', 'adiva-addons' ) => '',
                            esc_html__( 'Small', 'adiva-addons' )   => 'small',
                            esc_html__( 'Large', 'adiva-addons' )   => 'large',
                        ),
                        'admin_label' => true,
                    ),
                    array(
                        'type'       => 'dropdown',
                        'heading'    => esc_html__( 'Layout', 'adiva-addons' ),
                        'param_name' => 'layout',
                        'value'      => array(
                            esc_html__( 'Default', 'adiva-addons' )    => 'default',
                            esc_html__( 'With hover', 'adiva-addons' ) => 'hover',
                        ),
                        'admin_label' => true,
                    ),
                    array(
                        'type'       => 'dropdown',
                        'heading'    => esc_html__( 'Social buttons style', 'adiva-addons' ),
                        'param_name' => 'style',
                        'value'      => array(
                            esc_html__( 'Default', 'adiva-addons' )             => '',
                            esc_html__( 'Colored', 'adiva-addons' )             => 'colored',
                            esc_html__( 'Colored alternative', 'adiva-addons' ) => 'colored-alt',
                            esc_html__( 'Bordered', 'adiva-addons' )            => 'bordered',
                        ),
                        'admin_label' => true,
                    ),
                    array(
                        'type'       => 'dropdown',
                        'heading'    => esc_html__( 'Social buttons form', 'adiva-addons' ),
                        'param_name' => 'form',
                        'value'      => array(
                            esc_html__( 'Circle', 'adiva-addons' ) => 'circle',
                            esc_html__( 'Square', 'adiva-addons' ) => 'square',
                        ),
                        'admin_label' => true,
                    ),
                    array(
                        'type'        => 'textfield',
                        'heading'     => esc_html__( 'Extra class name', 'adiva-addons' ),
                        'param_name'  => 'el_class',
                        'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'adiva-addons' )
                    )
                ),
            )
        );
	}
	add_action( 'vc_before_init', 'adiva_vc_map_social_icons' );
}
