<?php

if( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    

    add_filter( 'apus_themer_kingcomposer_map_woo_categories_tabs', 'denso_kingcomposer_map_woo_categories_tabs' );
    function denso_kingcomposer_map_woo_categories_tabs($args){
        $types = array(
            'recent_product'  => esc_html__( 'Recent Products', 'denso' ),
            'best_selling' => esc_html__( 'Best Selling', 'denso' ),
            'featured_product' => esc_html__( 'Featured Products', 'denso' ),
            'top_rate'  => esc_html__( 'Top Rate', 'denso' ),
            'on_sale'  => esc_html__( 'On Sale', 'denso' ),
            'recent_review'  => esc_html__( 'Recent Review', 'denso' ),
            'rand'  => esc_html__( 'Random Products', 'denso' )
        );
        $categories = array();
        if ( is_admin() ) {
            $categories = denso_woocommerce_get_categories();
        }
        return array(
            'name' => esc_html__( 'Apus Products Tabs', 'denso' ),
            'description' => esc_html__('Display products/categories tabs with icon in frontend', 'denso'),
            'icon' => 'sl-paper-plane',
            'category' => 'Woocommerce',
            'params' => array(
            	array(
                    'name' => 'title',
                    'label' => esc_html__( 'Title' ,'denso' ),
                    'type' => 'text',
                    'admin_label' => true
                ),
                array(
                    'type' => 'group',
                    'label' => esc_html__('Tabs', 'denso'),
                    'name' => 'tabs',
                    'params' => array(
                        array(
                            'name' => 'name',
                            'label' => esc_html__( 'Tab Name' ,'denso' ),
                            'type' => 'text',
                        ),
                        array(
                            'type'           => 'select',
                            'label'          => esc_html__( 'Select Category', 'denso' ),
                            'name'           => 'category',
                            'description'    => esc_html__( 'Select Category to display', 'denso' ),
                            'admin_label'    => true,
                            'options' => $categories
                        ),
                        array(
                            'name' => 'type',
                            'label' => esc_html__( 'Product Type' ,'denso' ),
                            'type' => 'select',
                            'admin_label' => true,
                            'options' => $types,
                            'value' => 4,
                        ),
                    ),
                ),
                array(
                    'name' => 'number',
                    'label' => esc_html__( 'Number products' ,'denso' ),
                    'type' => 'number_slider',
                    'options' => array(
                        'min' => 1,
                        'max' => 30,
                        'unit' => '',
                        'show_input' => true
                    ),
                    'value' => 8,
                    'description' => esc_html__( 'Display number of products', 'denso' )
                ),
                array(
                    'name' => 'columns',
                    'label' => esc_html__( 'Number Column' ,'denso' ),
                    'type' => 'number_slider',
                    'options' => array(
                        'min' => 1,
                        'max' => 8,
                        'unit' => '',
                        'show_input' => true
                    ),
                    'value' => 4
                ),
                array(
                    'name' => 'tab_style',
                    'label' => esc_html__( 'Tab Style' ,'denso' ),
                    'type' => 'select',
                    'admin_label' => true,
                    'options' => array(
                        'style1' => esc_html__( 'Style 1' ,'denso' ),
                        'style2' => esc_html__( 'Style 2' ,'denso' ),
                    ),
                    'value' => 'style1'
                ),
                array(
                    'name' => 'layout_type',
                    'label' => esc_html__( 'Product Layout Type' ,'denso' ),
                    'type' => 'select',
                    'admin_label' => true,
                    'options' => array(
                        'grid' => esc_html__( 'Grid' ,'denso' ),
                        'list-tab' => esc_html__( 'List Tab' ,'denso' ),
                        'carousel' => esc_html__( 'Carousel', 'denso' ),
                    ),
                    'value' => 'grid'
                ),
            )
        );
    }

    add_action('init', 'denso_woocommerce_kingcomposer_map', 100 );
    function denso_woocommerce_kingcomposer_map() {
    	global $kc;

    	$order_by_options = array(
    		'',
    		'date'     	     =>  esc_html__( 'Date', 'denso' ) ,
    		'ID'  	    	 =>  esc_html__( 'ID', 'denso' ),
    		'author'    	 =>  esc_html__( 'Author', 'denso' ) ,
    		'title'  	  	 =>  esc_html__( 'Title', 'denso' ) ,
    		'modified'  	 =>  esc_html__( 'Modified', 'denso' ),
    		'rand'           =>  esc_html__( 'Random', 'denso' ),
    		'comment_count'  =>  esc_html__( 'Comment count', 'denso' ),
    		'menu_order'  	 => esc_html__( 'Menu order', 'denso' ),
    	);

    	$order_way_options = array(
    		'',
    		'DESC' =>  esc_html__( 'Descending', 'denso' ) ,
    		'ASC'  =>  esc_html__( 'Ascending', 'denso' ),
    	);

    	$kc->add_map( array('woo_products_special' => array(
            'name' => esc_html__( 'Apus Products Special', 'denso' ),
            'description' => esc_html__('Display Products Special (Sale off products) in frontend', 'denso'),
            'icon' => 'sl-paper-plane',
            'category' => 'Woocommerce',
            'params' => array(
                array(
                    'name' => 'title',
                    'label' => esc_html__( 'Title', 'denso' ),
                    'type' => 'text'
                ),
				array(
					'name'    => 'advanced',
					'label'   => esc_html__('Choose Special Products?', 'denso'),
					'type'    => 'select',
					'options' => array(
						'no' => esc_html__('No', 'denso'),
						'yes' => esc_html__('Yes', 'denso'),
					)
				),
				array(
					'name'    => 'orderby',
					'label'   => esc_html__('Order By', 'denso'),
					'type'    => 'select',
					'options' => $order_by_options,
					'relation' => array(
						'parent' => 'advanced',
						'show_when' => 'no'
					),
				),
				array(
					'name'    => 'orderway',
					'label'   => esc_html__('Order Way', 'denso'),
					'type'    => 'select',
					'options' => $order_way_options,
					'relation' => array(
						'parent' => 'advanced',
						'show_when' => 'no'
					),
				),
                array(
                    'name' => 'number',
                    'label' => esc_html__( 'Number product show', 'denso' ),
                    'type' => 'number_slider',
                    'options' => array(
                        'min' => 1,
                        'max' => 24,
                        'unit' => '',
                        'show_input' => true
                    ),
                    'description' => esc_html__( 'Display number of product', 'denso' ),
                    'relation' => array(
						'parent' => 'advanced',
						'show_when' => 'no'
					),
					'value' => 3
                ),
                array(
                    'type'          => 'autocomplete',
                    'label'         => esc_html__('Choose Products', 'denso'),
                    'name'          => 'product_special',
                    'options'       => array(
                        'multiple'      => true,
                        'post_type'     => 'product',
                    ),
                    'relation' => array(
						'parent' => 'advanced',
						'show_when' => 'yes'
					),
                ),
                array(
                    'name' => 'style',
                    'label' => esc_html__( 'Style' ,'denso' ),
                    'type' => 'select',
                    'admin_label' => true,
                    'options' => array(
                        'style1' => esc_html__( 'Style 1' ,'denso' ),
                        'style2' => esc_html__( 'Style 2' ,'denso' ),
                        'style3' => esc_html__( 'Style 3' ,'denso' ),
                    ),
                    'value' => 'style1'
                ),
            )
        )));

    	$types = array(
            'best_selling' => esc_html__( 'Best Selling', 'denso' ),
            'featured_product' => esc_html__( 'Featured Products', 'denso' ),
            'top_rate'  => esc_html__( 'Top Rate', 'denso' ),
            'recent_product'  => esc_html__( 'Recent Products', 'denso' ),
            'on_sale'  => esc_html__( 'On Sale', 'denso' ),
            'recent_review'  => esc_html__( 'Recent Review', 'denso' )
        );
        $categories = array();
        if ( is_admin() ) {
            $categories = denso_woocommerce_get_categories();
        }

        $kc->add_map( array('woo_products' => array(
	        'name' => esc_html__( 'Apus Products', 'denso' ),
	        'description' => esc_html__('Display Bestseller, Latest, Most Review ... in frontend', 'denso'),
	        'icon' => 'sl-paper-plane',
	        'category' => 'Woocommerce',
	        'params' => array(
	        	array(
                    'name' => 'title',
                    'label' => esc_html__( 'Title', 'denso' ),
                    'type' => 'text'
                ),
	            array(
	                'name' => 'type',
	                'label' => esc_html__( 'Get Products By', 'denso' ),
	                'type' => 'select',
	                'admin_label' => true,
	                'options' => $types
	            ),
	            array(
					'type' => 'multiple',
					'label' => esc_html__( 'Select Categories', 'denso' ),
					'name' => 'categories',
					'description' => esc_html__( 'Select Categories to display', 'denso' ),
					'admin_label' => true,
					'options' => $categories
	            ),
	            array(
	                'name' => 'number',
	                'label' => esc_html__( 'Number products', 'denso' ),
	                'type' => 'number_slider',
	                'options' => array(
	                    'min' => 1,
	                    'max' => 24,
	                    'unit' => '',
	                    'show_input' => true
	                ),
	                'description' => esc_html__( 'Display number of products', 'denso' )
	            ),
	            array(
	                'name' => 'columns',
	                'label' => esc_html__( 'Number Column', 'denso' ),
	                'type' => 'number_slider',
	                'options' => array(
	                    'min' => 1,
	                    'max' => 8,
	                    'unit' => '',
	                    'show_input' => true
	                ),
	                'value' => 1
	            ),
	            array(
	                'name' => 'layout_type',
	                'label' => esc_html__( 'Products Layout Type', 'denso' ),
	                'type' => 'select',
	                'admin_label' => true,
	                'options' => array(
	                	'grid' => esc_html__( 'Grid', 'denso' ),
	                	'carousel' => esc_html__( 'Carousel', 'denso' ),
                	),
                	'value' => 'grid'
	            ),
	            array(
	                'name' => 'rows',
	                'label' => esc_html__( 'Rows', 'denso' ),
	                'type' => 'number_slider',
	                'options' => array(
	                    'min' => 1,
	                    'max' => 6,
	                    'unit' => '',
	                    'show_input' => true
	                ),
	                'relation' => array(
						'parent' => 'layout_type',
						'show_when' => 'carousel'
					),
					'value' => 1
	            ),
                array(
                    'name' => 'topcarousel',
                    'label' => esc_html__( 'Carousel Top', 'denso' ),
                    'type' => 'select',
                    'options' => array(
                        0 => esc_html__( 'No' ,'denso' ),
                        1 => esc_html__( 'Yes' ,'denso' ),
                    ),
                    'value' => 0
                ),
	            array(
	                'name' => 'item_style',
	                'label' => esc_html__( 'Product Item Style', 'denso' ),
	                'type' => 'select',
	                'admin_label' => true,
	                'options' => array(
	                	'inner' => esc_html__( 'Grid', 'denso' ),
                        'list' => esc_html__( 'List', 'denso' ),
	                	'list-v1' => esc_html__( 'List 1', 'denso' ),
                        'list-v2' => esc_html__( 'List 2', 'denso' ),
                	),
                	'value' => 'grid'
	            ),
	        )
	    )));

        $kc->add_map( array('woo_category_info' => array(
            'name' => esc_html__( 'Apus Category Info', 'denso' ),
            'description' => esc_html__('Display category info ... in frontend', 'denso'),
            'icon' => 'sl-paper-plane',
            'category' => 'Woocommerce',
            'params' => array(
                array(
                    'name' => 'title',
                    'label' => esc_html__( 'Title', 'denso' ),
                    'type' => 'text'
                ),
                array(
                    'type' => 'select',
                    'label' => esc_html__( 'Select Category', 'denso' ),
                    'name' => 'category',
                    'description' => esc_html__( 'Select Category to display', 'denso' ),
                    'admin_label' => true,
                    'options' => $categories
                ),
                array(
                    'name' => 'number',
                    'label' => esc_html__( 'Number Sub Categories', 'denso' ),
                    'type' => 'number_slider',
                    'options' => array(
                        'min' => 1,
                        'max' => 24,
                        'unit' => '',
                        'show_input' => true
                    ),
                    'value' => 7
                ),
                array(
                    'name' => 'layout_type',
                    'label' => esc_html__( 'Layout Version', 'denso' ),
                    'type' => 'select',
                    'admin_label' => true,
                    'options' => array(
                        'layout1' => esc_html__( 'Layout 1', 'denso' ),
                        'layout2' => esc_html__( 'Layout 2', 'denso' ),
                        'layout3' => esc_html__( 'Layout 3', 'denso' ),
                    ),
                    'value' => 'layout1'
                ),
                array(
                    'name' => 'description',
                    'label' => esc_html__( 'Description', 'denso' ),
                    'type' => 'textarea',
                    'relation' => array(
                        'parent' => 'layout_type',
                        'show_when' => 'layout1'
                    ),
                ),
                array(
                    "type" => "attach_image",
                    "label" => esc_html__('Image', 'denso'),
                    "name" => 'image',
                    'relation' => array(
                        'parent' => 'layout_type',
                    )
                ),
                array(
                    "type" => "icon_picker",
                    "label" => esc_html__("Icon font", 'denso'),
                    "name" => "icon",
                    'relation' => array(
                        'parent' => 'layout_type',
                        'show_when' => 'layout2'
                    )
                ),
                array(
                    "type" => "attach_image",
                    "description" => esc_html__("If you upload an image, icon font will not show.", 'denso'),
                    "name" => "image_icon",
                    'label' => esc_html__('Image Icon', 'denso' ),
                    'relation' => array(
                        'parent' => 'layout_type',
                        'show_when' => 'layout2'
                    )
                ),
            )
        )));
        $kc->add_map( array('element_brands' => array(
            'name' => esc_html__( 'Apus Brands', 'denso' ),
            'icon' => 'sl-paper-plane',
            'category' => 'Woocommerce',
            'description' => esc_html__( 'List of brands with more layouts.', 'denso' ),
            'params' => array(
                array(
                    'name' => 'title',
                    'label' => esc_html__( 'Title', 'denso' ),
                    'type' => 'text'
                ),
                array(
                    'name' => 'number',
                    'label' => esc_html__( 'Number Brands', 'denso' ),
                    'type' => 'number_slider',
                    'options' => array(
                        'min' => 1,
                        'max' => 30,
                        'unit' => '',
                        'show_input' => true
                    ),
                    "admin_label" => true,
                    'value' => 8
                ),
                array(
                    'name' => 'columns',
                    'label' => esc_html__( 'Columns', 'denso' ),
                    'type' => 'number_slider',
                    'options' => array(
                        'min' => 1,
                        'max' => 10,
                        'unit' => '',
                        'show_input' => true
                    ),
                    "admin_label" => true,
                    'value' => 8
                ),
                array(
                    'name' => 'rows',
                    'label' => esc_html__( 'Rows', 'denso' ),
                    'type' => 'number_slider',
                    'options' => array(
                        'min' => 1,
                        'max' => 8,
                        'unit' => '',
                        'show_input' => true
                    ),
                    "admin_label" => true,
                    'value' => 1
                ),
                array(
                    'name' => 'style',
                    'label' => esc_html__( 'Style', 'denso' ),
                    'type' => 'select',
                    'admin_label' => true,
                    'options' => array(
                        'style1' => esc_html__( 'Style 1', 'denso' ),
                        'style2' => esc_html__( 'Style 2', 'denso' ),
                        'style3 style2' => esc_html__( 'Style 3', 'denso' )
                    ),
                    'value' => 'style1'
                ),
            )
        )));
    }
}

add_action('init', 'denso_elements_kingcomposer_map', 100 );
function denso_elements_kingcomposer_map() {
    global $kc;
    $kc->add_map( array('element_blog_posts' => array(
        'name' => esc_html__( 'Apus Blog Posts', 'denso' ),
        'title' => esc_html__( 'Blog Posts Settings', 'denso' ),
        'icon' => 'fa fa-newspaper-o',
        'category' => 'Elements',
        'wrapper_class' => 'clearfix',
        'description' => esc_html__( 'List of latest post with more layouts.', 'denso' ),
        'params' => array(
            array(
                'name' => 'title',
                'label' => esc_html__( 'Title', 'denso' ),
                'type' => 'text'
            ),
            array(
                'name' => 'columns',
                'label' => esc_html__( 'Grid Column' ,'denso' ),
                'type' => 'number_slider',
                'options' => array(
                    'min' => 1,
                    'max' => 6,
                    'unit' => '',
                    'show_input' => true
                ),
                "admin_label" => true,
                'description' => esc_html__( 'Display number of post', 'denso' )
            ),    
            array(
                'name' => 'number',
                'label' => esc_html__( 'Items Limit', 'denso' ),
                'type' => 'number_slider',
                'value' => '5',
                'options' => array(
                    'min' => 1,
                    'max' => 10,
                    'unit' => '',
                    'show_input' => false
                ),
                "admin_label" => true,
                'description' => esc_html__('Specify number of post that you want to show. Enter -1 to get all team', 'denso'),
            ),
            array(
                'type'          => 'dropdown',
                'label'         => esc_html__( 'Order by', 'denso' ),
                'name'          => 'order_by',
                'description'   => esc_html__( '', 'denso' ),
                'admin_label'   => true,
                'options'       => array(
                    'ID'        => esc_html__('Post ID', 'denso'),
                    'author'    => esc_html__('Author', 'denso'),
                    'title'     => esc_html__('Title', 'denso'),
                    'name'      => esc_html__('Post name (post slug)', 'denso'),
                    'type'      => esc_html__('Post type (available since Version 4.0)', 'denso'),
                    'date'      => esc_html__('Date', 'denso'),
                    'modified'  => esc_html__('Last modified date', 'denso'),
                    'rand'      => esc_html__('Random order', 'denso'),
                    'comment_count' => esc_html__('Number of comments', 'denso')
                )
            ),
            array(
                'type' => 'select',
                'label' => esc_html__( 'Order By', 'denso' ),
                'name' => 'order',
                'options' => array(
                    'DESC' => esc_html__( 'Descending', 'denso' ),
                    'ASC' => esc_html__( 'Ascending', 'denso' )
                )
            ),
            array(
                'name' => 'layout_type',
                'label' => esc_html__( 'Layout Type', 'denso' ),
                'type' => 'select',
                'admin_label' => true,
                'options' => array(
                    'grid' => esc_html__( 'Grid', 'denso' ),
                    'carousel' => esc_html__( 'Carousel', 'denso' ),
                    'carousel-list' => esc_html__( 'Carousel List', 'denso' ),
                    'special' => esc_html__( 'Special', 'denso' ),
                ),
                'value' => 'carousel'
            ),
        )
    )));

    $kc->add_map( array('element_banner' => array(
        'name' => esc_html__( 'Apus Banner', 'denso' ),
        'description' => esc_html__('Display Banner in frontend', 'denso'),
        'icon' => 'sl-paper-plane',
        'category' => 'Elements',
        'params' => array(
            array(
                "type" => "attach_image",
                "name" => "image",
                'label' => esc_html__('Image Banner', 'denso' )
            ),
             array(
                'name' => 'subtitle',
                'label' => esc_html__( 'Sub Title', 'denso' ),
                'type' => 'text'
            ),
            array(
                'name' => 'title',
                'label' => esc_html__( 'Title', 'denso' ),
                'type' => 'textarea'
            ),
            array(
                'name' => 'description',
                'label' => esc_html__( 'Description', 'denso' ),
                'type' => 'textarea'
            ),
            array(
                'name' => 'button_text',
                'label' => esc_html__( 'Button Text', 'denso' ),
                'type' => 'text'
            ),
            array(
                'name' => 'button_link',
                'label' => esc_html__( 'Button Link', 'denso' ),
                'type' => 'text'
            ),
            array(
                'name' => 'text_position',
                'label' => esc_html__( 'Content Position' ,'denso' ),
                'type' => 'select',
                'admin_label' => true,
                'options' => array(
                    'left' => esc_html__( 'Left' ,'denso' ),
                    'right' => esc_html__( 'Right' ,'denso' ),
                    'large-left' => esc_html__( 'Large Left' ,'denso' ),
                    'left full' => esc_html__( 'Left Full' ,'denso' ),
                    'right full' => esc_html__( 'Right Full' ,'denso' ),
                    'left full dark' => esc_html__( 'Left Full Dark' ,'denso' ),
                    'left info' => esc_html__( 'Left Full Info' ,'denso' ),
                    'top' => esc_html__( 'Top' ,'denso' ),
                )
            ),
        )
    )));
    $kc->add_map( array('element_founders' => array(
        'name' => esc_html__( 'Apus Founders', 'denso' ),
        'description' => esc_html__('Display Founders in frontend', 'denso'),
        'icon' => 'sl-paper-plane',
        'category' => 'Elements',
        'params' => array(
            array(
                'name' => 'title',
                'label' => esc_html__( 'Title', 'denso' ),
                'type' => 'text'
            ),
            array(
                'type'            => 'group',
                'label'           => esc_html__('Features Items', 'denso'),
                'name'            => 'founders',
                'params'          => array(
                    array(
                        "type" => "attach_image",
                        "name" => "image",
                        'label' => esc_html__('Image Banner', 'denso' )
                    ),
                    array(
                        'name' => 'title_item',
                        'label' => esc_html__( 'Name', 'denso' ),
                        'type' => 'text'
                    ),
                    array(
                        'name' => 'job',
                        'label' => esc_html__( 'Job Management', 'denso' ),
                        'type' => 'text'
                    ),
                ),   
            ),
        ),
    )));
    $kc->add_map( array('element_testimonials' => array(
        'name' => esc_html__( 'Apus Testimonials', 'denso' ),
        'title' => esc_html__( 'Apus Testimonials Settings', 'denso' ),
        'icon' => 'fa fa-newspaper-o',
        'category' => 'Elements',
        'wrapper_class' => 'clearfix',
        'description' => esc_html__( 'List of testimonials with more layouts.', 'denso' ),
        'params' => array(
            array(
                'name' => 'title',
                'label' => esc_html__( 'Title', 'denso' ),
                'type' => 'text'
            ),
            array(
                'type'            => 'group',
                'label'            => esc_html__('Testimonial Items', 'denso'),
                'name'            => 'testimonials',
                'params' => array(
                    array(
                        "type" => "attach_image",
                        "label" => esc_html__('Photo', 'denso'),
                        "name" => 'image',
                        "value" => '',
                    ),
                    array(
                        'type' => 'text',
                        'label' => esc_html__( 'Name', 'denso' ),
                        'name' => 'name',
                        'admin_label' => true,
                    ),
                    array(
                        'type' => 'text',
                        'label' => esc_html__( 'Job', 'denso' ),
                        'name' => 'job',
                        'admin_label' => true,
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => esc_html__( 'Content', 'denso' ),
                        'name' => 'content',
                        'admin_label' => true,
                    ),
                ),
            ),
            array(
                'name' => 'columns',
                'label' => esc_html__( 'Grid Column' ,'denso' ),
                'type' => 'number_slider',
                'options' => array(
                    'min' => 1,
                    'max' => 6,
                    'unit' => '',
                    'show_input' => true
                ),
                "admin_label" => true
            ),
            array(
                'name' => 'layout_type',
                'label' => esc_html__( 'Layout Type' ,'denso' ),
                'type' => 'select',
                'admin_label' => true,
                'options' => array(
                    'grid' => esc_html__( 'Grid', 'denso' ),
                    'carousel' => esc_html__( 'Carousel', 'denso' ),
                ),
            ),
        )
    )));


}
add_filter( 'apus_themer_kingcomposer_map_element_features_box', 'apustheme_kingcomposer_map_features_box');
function apustheme_kingcomposer_map_features_box($args) {
    if ( isset($args['params'][2]['options']) ) {
        $args['params'][2]['options'] = array(
                'default' => esc_html__('Default ', 'denso'), 
                'style1' => esc_html__('Styel 1', 'denso'),
                'style_border' => esc_html__('Style Border ', 'denso'),
                'style2 style1' => esc_html__('Style Light ', 'denso')
            );
    }
    return $args;
}

add_action('init', 'kc_add_data', 99 );
function kc_add_data(){
    global $kc;
    $kc->add_map_param(
        'element_socials_link',
        array(
            'type'          => 'text',
            'label'         => esc_html__( 'Title', 'denso' ),
            'name'          => 'title',
            'admin_label'   => true,
            'value'         => '',
            'description'   => esc_html__( 'Enter title for Element.', 'denso' )
     ), 2 );
    $kc->add_map_param(
        'element_socials_link',
        array(
            'name' => 'style',
            'label' => esc_html__( 'Style for Element' ,'denso' ),
            'type' => 'select',
            'admin_label' => true,
            'options' => array(
                '' => esc_html__( 'Default' ,'denso' ),
                'style1' => esc_html__( 'Style 1' ,'denso' ),
            )
     ), 1 );
}