<?php
/**
* ------------------------------------------------------------------------------------------------
* Adiva products shortcode
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'adiva_vc_map_products' ) ) {
	function adiva_vc_map_products() {
		// Get all terms of woocommerce
    	$product_cat = array();
    	$terms = get_terms( 'product_cat' );
    	if ( $terms && ! isset( $terms->errors ) ) {
    		foreach ( $terms as $key => $value ) {
    			$product_cat[$value->name] = $value->term_id;
    		}
    	}

        vc_map(
     		array(
     			'name'        => esc_html__( 'Products (grid or carousel)', 'adiva-addons' ),
     			'description' => esc_html__( 'Display products list', 'adiva-addons' ),
     			'base'        => 'adiva_products',
     			'icon'        => 'jms-icon',
     			'category'    => esc_html__( 'JMS Addons', 'adiva-addons' ),
     			'params'      => array(
                    array(
    					'param_name' => 'product_design',
    					'heading'    => esc_html__( 'Display', 'adiva-addons' ),
    					'type' 	     => 'dropdown',
    					'value'      => array(
    						esc_html__( 'Grid', 'adiva-addons' )    => 'grid',
    						esc_html__( 'Masonry', 'adiva-addons' ) => 'masonry',
    						esc_html__( 'Metro', 'adiva-addons' )   => 'metro',
    					),
    					'admin_label' => true,
                        'save_always' => true,
    				),
                    array(
    					'param_name' => 'slider',
    					'heading'    => esc_html__( 'Enable Slider', 'adiva-addons' ),
    					'type'       => 'dropdown',
    					'value'      => array(
    						esc_html__( 'No', 'adiva-addons' )  => 'no',
    						esc_html__( 'Yes', 'adiva-addons' ) => 'yes',
    					),
    					'dependency' => array(
    						'element' => 'product_design',
    						'value'   => 'grid'
    					),
                        'admin_label' => true,
    				),
                    array(
    					'param_name'  => 'filter',
    					'heading'     => esc_html__( 'Enable Isotope Category Filter', 'adiva-addons' ),
    					'type' 	      => 'checkbox',
    					'dependency' => array(
    						'element' => 'product_design',
    						'value'   => 'masonry'
    					),
    				),
                    array(
    					'param_name' => 'product_type',
    					'heading'    => esc_html__( 'Display', 'adiva-addons' ),
    					'type' 	     => 'dropdown',
    					'value'      => array(
    						esc_html__( 'All products', 'adiva-addons' ) 		   => 'all',
    						esc_html__( 'Recent products', 'adiva-addons' ) 	   => 'recent',
    						esc_html__( 'Featured products', 'adiva-addons' ) 	   => 'featured',
    						esc_html__( 'Sale products', 'adiva-addons' ) 		   => 'sale',
    						esc_html__( 'Best selling products', 'adiva-addons' ) => 'selling',
    						esc_html__( 'Top Rated Products', 'adiva-addons' )    => 'rated',
    						esc_html__( 'Products by category', 'adiva-addons' )  => 'cat',
    					),
    					'admin_label' => true,
                        'save_always' => true,
    				),
                    array(
    					'param_name'  => 'orderby',
    					'heading'     => esc_html__( 'Order By', 'adiva-addons' ),
    					'description' => sprintf( wp_kses_post( 'Select how to sort retrieved products. More at %s. Default by Title', 'adiva-addons' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
    					'type'        => 'dropdown',
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
                        'admin_label' => true,
                        'save_always' => true,
    					'dependency'  => array(
    						'element' => 'product_type',
    						'value'   => array( 'all', 'featured', 'sale', 'rated', 'cat' ),
    					),
    				),
                    array(
    					'param_name'  => 'order',
    					'heading'     => esc_html__( 'Order', 'adiva-addons' ),
    					'description' => sprintf( __( 'Designates the ascending or descending order. More at %s. Default by ASC', 'adiva-addons' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
    					'type'        => 'dropdown',
    					'value'       => array(
    						esc_html__( 'Ascending', 'adiva-addons' ) => 'ASC',
    						esc_html__( 'Descending', 'adiva-addons' ) => 'DESC',
    					),
                        'admin_label' => true,
                        'save_always' => true,
    					'dependency' => array(
    						'element' => 'product_type',
    						'value'   => array( 'all', 'featured', 'sale', 'rated', 'cat' ),
    					),
    				),
                    array(
    					'param_name'  => 'id',
    					'heading'     => esc_html__( 'Products', 'adiva-addons' ),
    					'description' => esc_html__( 'Input product ID or product SKU or product title to see suggestions', 'adiva-addons' ),
    					'type'        => 'autocomplete',
    					'settings'    => array(
    						'multiple'      => true,
    						'sortable'      => true,
    						'unique_values' => true,
    					),
    					'dependency'  => array(
    						'element' => 'product_type',
    						'value'   => 'all',
    					),
    				),
    				array(
    					'param_name' => 'sku',
    					'type'       => 'hidden',
    					'dependency' => array(
    						'element' => 'product_type',
    						'value'   => 'all',
    					),
    				),
                    array(
    					'heading'    => esc_html__( 'Product Category', 'adiva-addons' ),
    					'param_name' => 'cat_id',
    					'type'       => 'dropdown',
    					'value'      => $product_cat,
                        'admin_label' => true,
    					'dependency' => array(
    						'element' => 'product_type',
    						'value'   => 'cat',
    					),
    				),
                    array(
    					'param_name'  => 'enable_countdown',
    					'heading'     => esc_html__( 'Enable countdown', 'adiva-addons' ),
    					'type'        => 'checkbox',
    				),
                    array(
    					'param_name'  => 'total_items',
    					'heading'     => esc_html__( 'Total Items', 'adiva-addons' ),
    					'description' => esc_html__( 'How much items per page to show (-1 to show all products)', 'adiva-addons' ),
    					'type'        => 'textfield',
    					'value'       => 12,
                        'admin_label' => true,
    				),
                    array(
     					'param_name'  => 'columns',
     					'heading'     => esc_html__( 'Columns', 'adiva-addons' ),
                        'type'        => 'dropdown',
                        'std'         => 4,
                        'value'       => array(
                            esc_html__( '1 Column', 'adiva-addons' ) => 1,
             				esc_html__( '2 Columns', 'adiva-addons' ) => 2,
             				esc_html__( '3 Columns', 'adiva-addons' ) => 3,
             				esc_html__( '4 Columns', 'adiva-addons' ) => 4,
             				esc_html__( '5 Columns', 'adiva-addons' ) => 5,
                            esc_html__( '6 Columns', 'adiva-addons' ) => 6,
             			),
     				),
    				array(
     					'param_name'  => 'product_style',
     					'heading'     => esc_html__( 'Product Box Style', 'adiva-addons' ),
                        'type'        => 'dropdown',
                        'std'         => 1,
                        'value'       => array(
             				esc_html__( 'Style 1', 'adiva-addons' ) => 1,
             				esc_html__( 'Style 2', 'adiva-addons' ) => 2,
             				esc_html__( 'Style 3', 'adiva-addons' ) => 3,
             			),
     				),
                    array(
     					'param_name'  => 'items_spacing',
     					'heading'     => esc_html__( 'Items spacing', 'adiva-addons' ),
                        'type'        => 'dropdown',
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
                    // SLIDER SETTINGS
                    array(
     					'param_name'  => 'items_desktop',
     					'heading'     => esc_html__( 'Items Show On Desktop', 'adiva-addons' ),
                        'description' => esc_html__( 'Show number of items on desktop', 'adiva-addons'),
     					'type'        => 'dropdown',
                        'group'       => esc_html__( 'Slider Settings', 'adiva-addons' ),
                        'std'         => 4,
                        'value'       => array(
             				esc_html__( '1 Item', 'adiva-addons' ) => 1,
             				esc_html__( '2 Items', 'adiva-addons' ) => 2,
             				esc_html__( '3 Items', 'adiva-addons' ) => 3,
             				esc_html__( '4 Items', 'adiva-addons' ) => 4,
             				esc_html__( '5 Items', 'adiva-addons' ) => 5,
                            esc_html__( '6 Items', 'adiva-addons' ) => 6,
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
                        'value'       => array(
             				esc_html__( '1 Item', 'adiva-addons' )  => 1,
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
     					'param_name'  => 'items_tablet',
     					'heading'     => esc_html__( 'Items Show On Tablet Device', 'adiva-addons' ),
     					'description' => esc_html__( 'Show number of items on tablet. Screen resolution of device >=621px and < 992px', 'adiva-addons'),
                        'type'        => 'dropdown',
                        'group'       => esc_html__( 'Slider Settings', 'adiva-addons' ),
                        'std'         => 3,
                        'value'       => array(
             				esc_html__( '1 Item', 'adiva-addons' ) => 1,
             				esc_html__( '2 Items', 'adiva-addons' ) => 2,
             				esc_html__( '3 Items', 'adiva-addons' ) => 3,
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
	add_action( 'vc_before_init', 'adiva_vc_map_products' );
}
