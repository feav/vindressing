<?php
/**
* ------------------------------------------------------------------------------------------------
* Adiva team member shortcode
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'adiva_vc_map_team_member' ) ) {
	function adiva_vc_map_team_member() {
        vc_map(
            array(
                'name'        => esc_html__( 'Team Member', 'adiva-addons' ),
                'base'        => 'team_member',
                'category'    => esc_html__( 'JMS Addons', 'adiva-addons' ),
                'description' => esc_html__( 'Display information about some person', 'adiva-addons' ),
                'icon'        => 'jms-icon',
                'params'      => array(
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
                        'param_name'  => 'position',
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
                        'type'       => 'dropdown',
                        'heading'    => esc_html__( 'Align', 'adiva-addons' ),
                        'param_name' => 'align',
                        'value'      => array(
                            esc_html__( 'Left', 'adiva-addons' )   => 'left',
                            esc_html__( 'Center', 'adiva-addons' ) => 'center',
                            esc_html__( 'Right', 'adiva-addons' )  => 'right',
                        ),
                    ),
                    array(
                        'type'          => 'dropdown',
                        'heading'       => esc_html__( 'Color Scheme', 'adiva-addons' ),
                        'param_name'    => 'adiva_color_scheme',
                        'value'         => array(
                            esc_html__( 'choose', 'adiva-addons' ) => '',
                            esc_html__( 'Light', 'adiva-addons' )  => 'light',
                            esc_html__( 'Dark', 'adiva-addons' )   => 'dark',
                        ),
                    ),
                    array(
                        'type'        => 'textarea_html',
                        'heading'     => esc_html__( 'Text', 'adiva-addons' ),
                        'param_name'  => 'content',
                        'description' => esc_html__( 'You can add some member bio here.', 'adiva-addons' )
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
                    ),
                    array(
                        'type'       => 'dropdown',
                        'heading'    => esc_html__( 'Layout', 'adiva-addons' ),
                        'param_name' => 'layout',
                        'value'      => array(
                            esc_html__( 'Default', 'adiva-addons' )    => 'default',
                            esc_html__( 'With hover', 'adiva-addons' ) => 'hover',
                        ),
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
                    ),
                    array(
                        'type'       => 'dropdown',
                        'heading'    => esc_html__( 'Social buttons form', 'adiva-addons' ),
                        'param_name' => 'form',
                        'value'      => array(
                            esc_html__( 'Circle', 'adiva-addons' ) => 'circle',
                            esc_html__( 'Square', 'adiva-addons' ) => 'square',
                        ),
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
	add_action( 'vc_before_init', 'adiva_vc_map_team_member' );
}
