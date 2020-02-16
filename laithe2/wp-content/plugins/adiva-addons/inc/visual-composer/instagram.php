<?php
/**
* ------------------------------------------------------------------------------------------------
* Adiva instagram shortcode
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'adiva_vc_map_instagram' ) ) {
	function adiva_vc_map_instagram() {
        vc_map(
     		array(
     			'name'     => esc_html__( 'Instagram', 'adiva-addons' ),
     			'base'     => 'adiva_addons_instagram',
     			'icon'     => 'jms-icon',
     			'category' => esc_html__( 'JMS Addons', 'adiva-addons' ),
     			'params'   => array(
     				array(
     					'param_name'  => 'user_id',
     					'heading'     => esc_html__( 'User ID', 'adiva-addons' ),
     					'description' => sprintf( wp_kses_post( 'Lookup User ID <a target="_blank" href="%s">here</a>', 'adiva-addons' ), 'https://smashballoon.com/instagram-feed/find-instagram-user-id/' ),
     					'type'        => 'textfield',
     					'admin_label' => true,
     				),
     				array(
     					'param_name'  => 'access_token',
     					'heading'     => esc_html__( 'Access Token', 'adiva-addons' ),
     					'description' => sprintf( wp_kses_post( 'Lookup Access Token <a target="_blank" href="%s">here</a>', 'adiva-addons' ), 'http://instagram.pixelunion.net/' ),
     					'type'        => 'textfield',
     					'admin_label' => true,
     				),
     				array(
     					'param_name' => 'slider',
     					'heading'    => esc_html__( 'Enable Slider', 'adiva-addons' ),
     					'type'       => 'dropdown',
     					'std'        => 'no',
     					'admin_label' => true,
     					'save_always' => true,
     					'value'      => array(
     						esc_html__( 'No', 'adiva-addons' )  => 'no',
     						esc_html__( 'Yes', 'adiva-addons' ) => 'yes',
     					)
     				),
     				array(
     					'param_name'  => 'limit',
     					'heading'     => esc_html__( 'Per Page', 'adiva-addons' ),
     					'description' => esc_html__( 'How much items per page to show', 'adiva-addons' ),
     					'type'        => 'textfield',
     					'value'       => 6,
     					'dependency' => array(
     						'element' => 'slider',
     						'value'   => 'no'
     					),
     				),
     				array(
     					'param_name'  => 'columns',
     					'heading'     => esc_html__( 'Columns', 'adiva-addons' ),
     					'description' => esc_html__( 'This parameter is not working if slider has enabled', 'adiva-addons' ),
     					'type'        => 'dropdown',
     					'value'       => array(
     						esc_html__( '2 columns', 'adiva-addons' ) => 2,
     						esc_html__( '3 columns', 'adiva-addons' ) => 3,
     						esc_html__( '4 columns', 'adiva-addons' ) => 4,
     						esc_html__( '5 columns', 'adiva-addons' ) => 5,
     						esc_html__( '6 columns', 'adiva-addons' ) => 6
     					),
     					'save_always' => true,
     					'dependency' => array(
     						'element' => 'slider',
     						'value'   => 'no'
     					),
     				),
     				array(
     					'param_name' => 'gutter',
     					'heading'    => esc_html__( 'Gutter Width', 'adiva-addons' ),
     					'type'       => 'dropdown',
     					'save_always' => true,
     					'value'      => array(
     						'0px'  => '0',
     						'10px'  => '10',
     						'20px'  => '20',
     						'30px'  => '30',
     						'40px'  => '40',
     					)
     				),
     				array(
     					'type'       => 'checkbox',
     					'heading'    => esc_html__( 'Rounded corners for images', 'adiva-addons' ),
     					'param_name' => 'rounded',
     					'value'      => array( esc_html__( 'Yes, please', 'adiva-addons' ) => 1 )
     				),
     				array(
     					'type'        => 'textarea_html',
     					'holder'      => 'div',
     					'heading'     => esc_html__( 'Instagram text', 'adiva-addons' ),
     					'param_name'  => 'content',
     					'description' => esc_html__( 'Add here few words about your instagram profile.', 'adiva-addons' )
     				),
     				vc_map_add_css_animation(),
     				array(
     					'param_name'  => 'el_class',
     					'heading'     => esc_html__( 'Extra class name', 'adiva-addons' ),
     					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'adiva-addons' ),
     					'type'        => 'textfield',
                         'admin_label' => false,
     				),
     				array(
     					'param_name'  => 'items_desktop',
     					'heading'     => esc_html__( 'Items Show On Desktop', 'adiva-addons' ),
                         'description' => esc_html__( 'Show number of items on desktop', 'adiva-addons'),
     					'type'        => 'dropdown',
                         'group'       => esc_html__( 'Slider Settings', 'adiva-addons' ),
                         'std'         => 6,
     					'save_always' => true,
                         'value'       => array(
             				esc_html__( '1 Item', 'adiva-addons' ) => 1,
             				esc_html__( '2 Items', 'adiva-addons' ) => 2,
             				esc_html__( '3 Items', 'adiva-addons' ) => 3,
             				esc_html__( '4 Items', 'adiva-addons' ) => 4,
             				esc_html__( '5 Items', 'adiva-addons' ) => 5,
                             esc_html__( '6 Items', 'adiva-addons' ) => 6
             			),
     					'dependency' => array(
     						'element' => 'slider',
     						'value'   => 'yes'
     					),
     				),
                     array(
     					'param_name'  => 'items_small_desktop',
     					'heading'     => esc_html__( 'Items Show On Small Desktop', 'adiva-addons' ),
                         'description' => esc_html__( 'Show number of items on small desktop. Screen resolution of device >=992px and < 1199px.', 'adiva-addons'),
                         'type'        => 'dropdown',
                         'group'       => esc_html__( 'Slider Settings', 'adiva-addons' ),
                         'std'         => 4,
     					'save_always' => true,
                         'value'       => array(
     						esc_html__( '1 Item', 'adiva-addons' ) => 1,
             				esc_html__( '2 Items', 'adiva-addons' ) => 2,
             				esc_html__( '3 Items', 'adiva-addons' ) => 3,
             				esc_html__( '4 Items', 'adiva-addons' ) => 4,
             				esc_html__( '5 Items', 'adiva-addons' ) => 5,
             			),
     					'dependency' => array(
     						'element' => 'slider',
     						'value'   => 'yes'
     					),
     				),
                     array(
     					'param_name'  => 'items_tablet',
     					'heading'     => esc_html__( 'Items Show On Tablet Device', 'adiva-addons' ),
     					'description' => esc_html__( 'Show number of items on tablet. Screen resolution of device >=621px and < 992px', 'adiva-addons'),
                         'type'        => 'dropdown',
                         'group'       => esc_html__( 'Slider Settings', 'adiva-addons' ),
                         'std'         => 3,
     					'save_always' => true,
                         'value'       => array(
     						esc_html__( '1 Item', 'adiva-addons' ) => 1,
             				esc_html__( '2 Items', 'adiva-addons' ) => 2,
             				esc_html__( '3 Items', 'adiva-addons' ) => 3,
             				esc_html__( '4 Items', 'adiva-addons' ) => 4,
             			),
     					'dependency' => array(
     						'element' => 'slider',
     						'value'   => 'yes'
     					),
     				),
                     array(
     					'param_name'  => 'items_mobile',
     					'heading'     => esc_html__( 'Items Show On Mobile Device', 'adiva-addons' ),
     					'description' => esc_html__( 'Show number of items on mobile. Screen resolution of device >=445px and < 621px.', 'adiva-addons'),
                         'type'        => 'dropdown',
                         'group'       => esc_html__( 'Slider Settings', 'adiva-addons' ),
                         'std'         => 2,
     					'save_always' => true,
                         'value'       => array(
             				esc_html__( '1 Item', 'adiva-addons' )  => 1,
             				esc_html__( '2 Items', 'adiva-addons' ) => 2,
     						esc_html__( '2 Items', 'adiva-addons' ) => 3
             			),
     					'dependency' => array(
     						'element' => 'slider',
     						'value'   => 'yes'
     					),
     				),
                     array(
     					'param_name'  => 'items_small_mobile',
     					'heading'     => esc_html__( 'Items Show On Small Mobile Device', 'adiva-addons' ),
     					'description' => esc_html__( 'Show number of items on small mobile. Screen resolution of device < 445px.', 'adiva-addons'),
                         'type'        => 'dropdown',
                         'group'       => esc_html__( 'Slider Settings', 'adiva-addons' ),
                         'std'         => 2,
     					'save_always' => true,
                         'value'       => array(
             				esc_html__( '1 Item', 'adiva-addons' ) => 1,
             				esc_html__( '2 Items', 'adiva-addons' ) => 2,
             			),
     					'dependency' => array(
     						'element' => 'slider',
     						'value'   => 'yes'
     					),
     				),
     				array(
     					'param_name' => 'navigation',
     					'heading'    => esc_html__( 'Enable Navigation', 'adiva-addons' ),
     					'type'       => 'checkbox',
                         'group'      => esc_html__( 'Slider Settings', 'adiva-addons' ),
                         'value'      => array( esc_html__( 'Yes', 'adiva-addons' ) => 'yes' ),
     					'std'        => 'yes',
     					'dependency' => array(
     						'element' => 'slider',
     						'value'   => 'yes'
     					),
     				),
     				array(
     					'param_name' => 'pagination',
     					'heading'    => esc_html__( 'Enable Dots Pagination', 'adiva-addons' ),
     					'type'       => 'checkbox',
                         'group'      => esc_html__( 'Slider Settings', 'adiva-addons' ),
     					'value'      => array( esc_html__( 'Yes', 'adiva-addons' ) => 'yes' ),
     					'std'        => 'no',
     					'dependency' => array(
     						'element' => 'slider',
     						'value'   => 'yes'
     					),
     				),
                     array(
                         'param_name'  	=> 'autoplay',
                         'heading'     	=> esc_html__( 'Autoplay', 'adiva-addons' ),
             			'description' 	=> esc_html__( 'Enables autoplay mode', 'adiva-addons' ),
             			'type'        	=> 'checkbox',
             			'group'         => esc_html__( 'Slider Settings', 'adiva-addons' ),
     					'value'      	=> array( esc_html__( 'Yes', 'adiva-addons' ) => 'yes' ),
     					'std'        	=> 'no',
     					'dependency' => array(
     						'element' => 'slider',
     						'value'   => 'yes'
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
     						'element' => 'slider',
     						'value'   => 'yes'
     					),
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
	add_action( 'vc_before_init', 'adiva_vc_map_instagram' );
}
