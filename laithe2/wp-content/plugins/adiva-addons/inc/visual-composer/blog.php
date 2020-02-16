<?php
/**
* ------------------------------------------------------------------------------------------------
* Adiva blog shortcode
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'adiva_vc_map_blog' ) ) {
	function adiva_vc_map_blog() {
        vc_map(
     		array(
     			'name'        => esc_html__( 'Blog Carousel', 'adiva-addons' ),
     			'description' => esc_html__( 'Display blog carousel slider.', 'adiva-addons' ),
     			'base'        => 'adiva_blog_carousel',
     			'icon'        => 'jms-icon',
     			'category'    => esc_html__( 'JMS Addons', 'adiva-addons' ),
     			'params'      => array(
     				array(
     					'param_name'  => 'orderby',
     					'heading'     => esc_html__( 'Order By', 'adiva-addons' ),
     					'description' => sprintf( wp_kses_post( 'Select how to sort retrieved posts. More at %s. Default by Title', 'adiva-addons' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
     					'type'        => 'dropdown',
                        'std'         => 'title',
                        'save_always' => true,
     					'value'       => array(
     						esc_html__( 'Title', 'adiva-addons' )         => 'title',
     						esc_html__( 'Date', 'adiva-addons' )          => 'date',
     						esc_html__( 'ID', 'adiva-addons' )            => 'ID',
     						esc_html__( 'Author', 'adiva-addons' )        => 'author',
     						esc_html__( 'Modified', 'adiva-addons' )      => 'modified',
     						esc_html__( 'Random', 'adiva-addons' )        => 'rand',
     						esc_html__( 'Comment count', 'adiva-addons' ) => 'comment_count',
     						esc_html__( 'Menu order', 'adiva-addons' )    => 'menu_order',
     					),
     				),
     				array(
     					'param_name'  => 'order',
     					'heading'     => esc_html__( 'Order', 'adiva-addons' ),
     					'description' => sprintf( __( 'Designates the ascending or descending order. More at %s. Default by ASC', 'adiva-addons' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
     					'type'        => 'dropdown',
                        'std'         => 'desc',
                        'save_always' => true,
     					'value'       => array(
                            esc_html__( 'Descending', 'adiva-addons' ) => 'DESC',
     						esc_html__( 'Ascending', 'adiva-addons' ) => 'ASC',
     					),
     				),
     				array(
     					'param_name'  => 'total_items',
     					'heading'     => esc_html__( 'Per Page', 'adiva-addons' ),
     					'description' => esc_html__( 'How much items per page to show (-1 to show all blogs)', 'adiva-addons' ),
     					'type'        => 'textfield',
     					'value'       => 6,
     				),
                     array(
                        'param_name'  => 'number_of_rows',
                        'heading'     => esc_html__( 'Show No. Of blogs row', 'adiva-addons' ),
                        'description' => esc_html__( 'Show number of blogs row', 'adiva-addons' ),
                        'type'        => 'dropdown',
                        'std'         => 1,
             			'value'       => array(
             				esc_html__( '1 Row', 'adiva-addons' ) => '1',
             				esc_html__( '2 Row', 'adiva-addons' ) => '2',
             				esc_html__( '3 Row', 'adiva-addons' ) => '3',
             			),
                    ),
     				array(
     					'param_name'  => 'img_size',
     					'type'        => 'textfield',
     					'heading'     => esc_html__( 'Images size', 'adiva-addons' ),
     					'description' => esc_html__( 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', 'adiva-addons' )
     				),
                     array(
     					'param_name' => 'show_author',
     					'heading'    => esc_html__( 'Show author?', 'adiva-addons' ),
     					'type'       => 'checkbox',
                         'value'      => array( __('Yes', 'adiva-addons')   => 'yes' ),
     					'std'        => ''
     				),
                     array(
     					'param_name' => 'show_comment',
     					'heading'    => esc_html__( 'Show comment?', 'adiva-addons' ),
     					'type'       => 'checkbox',
                        'value'      => array( __('Yes', 'adiva-addons')   => 'yes' ),
     					'std'        => 'yes'
     				),
     				array(
     					'param_name' => 'show_date',
     					'heading'    => esc_html__( 'Show date?', 'adiva-addons' ),
     					'type'       => 'checkbox',
                         'value'      => array( __('Yes', 'adiva-addons')   => 'yes' ),
     					'std'        => 'yes'
     				),
                     array(
     					'param_name' => 'show_category',
     					'heading'    => esc_html__( 'Show category?', 'adiva-addons' ),
     					'type'       => 'checkbox',
                         'value'      => array( esc_html__('Yes', 'adiva-addons')   => 'yes' ),
     					'std'        => ''
     				),
                     array(
     					'param_name' => 'show_excerpt',
     					'heading'    => esc_html__( 'Show excerpt?', 'adiva-addons' ),
     					'type'       => 'checkbox',
                        'value'      => array( esc_html__('Yes', 'adiva-addons')   => 'yes' ),
     					'std'        => 'no'
     				),
                     array(
     					'param_name' => 'excerpt',
     					'heading'    => esc_html__( 'Limit the character length', 'adiva-addons' ),
     					'type'       => 'textfield',
                        'value'      => '20',
                        'dependency' => array(
     						'element' => 'show_excerpt',
     						'value'   => 'yes',
     					),
     				),
     				array(
     					'param_name'  => 'items_space',
     					'heading'     => esc_html__( 'Gutter', 'adiva-addons' ),
                        'type'        => 'dropdown',
                        'group'       => esc_html__( 'Slider Settings', 'adiva-addons' ),
                        'std'         => 40,
                        'value'       => array(
             				esc_html__( '0px', 'adiva-addons' ) => 0,
             				esc_html__( '10px', 'adiva-addons' ) => 10,
     						esc_html__( '20px', 'adiva-addons' ) => 20,
     						esc_html__( '30px', 'adiva-addons' ) => 30,
     						esc_html__( '40px', 'adiva-addons' ) => 40,
     						esc_html__( '50px', 'adiva-addons' ) => 50,
     						esc_html__( '60px', 'adiva-addons' ) => 60,
             			),
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
                        'std'         => 3,
                        'value'       => array(
             				esc_html__( '1 Item', 'adiva-addons' ) => 1,
             				esc_html__( '2 Items', 'adiva-addons' ) => 2,
             				esc_html__( '3 Items', 'adiva-addons' ) => 3,
             				esc_html__( '4 Items', 'adiva-addons' ) => 4,
             				esc_html__( '5 Items', 'adiva-addons' ) => 5
             			),
     				),
                    array(
     					'param_name'  => 'items_small_desktop',
     					'heading'     => esc_html__( 'Items Show On Small Desktop', 'adiva-addons' ),
                        'description' => esc_html__( 'Show number of items on small desktop. Screen resolution of device >=992px and < 1199px.', 'adiva-addons'),
                        'type'        => 'dropdown',
                        'group'       => esc_html__( 'Slider Settings', 'adiva-addons' ),
                        'std'         => 3,
                        'value'       => array(
             				esc_html__( '1 Item', 'adiva-addons' ) => 1,
             				esc_html__( '2 Items', 'adiva-addons' ) => 2,
             				esc_html__( '3 Items', 'adiva-addons' ) => 3,
             				esc_html__( '4 Items', 'adiva-addons' ) => 4,
             			),
     				),
                    array(
     					'param_name'  => 'items_tablet',
     					'heading'     => esc_html__( 'Items Show On Tablet Device', 'adiva-addons' ),
     					'description' => esc_html__( 'Show number of items on tablet. Screen resolution of device >=621px and < 992px', 'adiva-addons'),
                        'type'        => 'dropdown',
                        'group'       => esc_html__( 'Slider Settings', 'adiva-addons' ),
                        'std'         => 2,
                        'value'       => array(
             				esc_html__( '1 Item', 'adiva-addons' ) => 1,
             				esc_html__( '2 Items', 'adiva-addons' ) => 2,
             				esc_html__( '3 Items', 'adiva-addons' ) => 3,
             			),
     				),
                    array(
     					'param_name'  => 'items_mobile',
     					'heading'     => esc_html__( 'Items Show On Mobile Device', 'adiva-addons' ),
     					'description' => esc_html__( 'Show number of items on mobile. Screen resolution of device >=445px and < 621px.', 'adiva-addons'),
                        'type'        => 'dropdown',
                        'group'       => esc_html__( 'Slider Settings', 'adiva-addons' ),
                        'std'         => 2,
                        'value'       => array(
             				esc_html__( '1 Item', 'adiva-addons' ) => 1,
             				esc_html__( '2 Items', 'adiva-addons' ) => 2,
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
             				esc_html__( '1 Item', 'adiva-addons' )  => 1,
             				esc_html__( '2 Items', 'adiva-addons' ) => 2,
             			),
     				),
     				array(
     					'param_name' => 'navigation',
     					'heading'    => esc_html__( 'Enable Navigation', 'adiva-addons' ),
     					'type'       => 'checkbox',
                        'group'      => esc_html__( 'Slider Settings', 'adiva-addons' ),
                        'value'      => array( esc_html__( 'Yes', 'adiva-addons' ) => 'yes' ),
     					'std'        => 'yes'
     				),
     				array(
     					'param_name' => 'pagination',
     					'heading'    => esc_html__( 'Enable Dots Pagination', 'adiva-addons' ),
     					'type'       => 'checkbox',
                        'group'      => esc_html__( 'Slider Settings', 'adiva-addons' ),
     					'value'      => array( esc_html__( 'Yes', 'adiva-addons' ) => 'yes' ),
     					'std'        => 'no'
     				),
                     array(
                        'param_name'  	=> 'autoplay',
                        'heading'     	=> esc_html__( 'Autoplay', 'adiva-addons' ),
             			'description' 	=> esc_html__( 'Enables autoplay mode', 'adiva-addons' ),
             			'type'        	=> 'checkbox',
             			'group'         => esc_html__( 'Slider Settings', 'adiva-addons' ),
             			'value'       	=> array( esc_html__( 'Yes', 'adiva-addons' ) => 'yes' ),
     					'std'        	=> 'no'
             		),
             		array(
                        'param_name'  	=> 'loop',
             			'heading'     	=> esc_html__( 'Loop', 'adiva-addons' ),
                        'description' 	=> esc_html__( 'Inifnity loop. Duplicate last and first items to get loop illusion', 'adiva-addons' ),
                        'type'        	=> 'checkbox',
                        'group'         => esc_html__( 'Slider Settings', 'adiva-addons' ),
             			'value'       	=> array( esc_html__( 'Yes', 'adiva-addons' ) => 'yes' ),
     					'std'        	=> 'no'
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
	add_action( 'vc_before_init', 'adiva_vc_map_blog' );
}
