<?php
/**
 * ReduxFramework Sample Config File
 * For full documentation, please visit: http://docs.reduxframework.com/
 */

if (!class_exists('Denso_Redux_Framework_Config')) {

    class Denso_Redux_Framework_Config
    {
        public $args = array();
        public $sections = array();
        public $ReduxFramework;

        public function __construct()
        {
            if (!class_exists('ReduxFramework')) {
                return;
            }
            add_action('init', array($this, 'initSettings'), 10);
        }

        public function initSettings()
        {
            // Set the default arguments
            $this->setArguments();

            // Create the sections and fields
            $this->setSections();

            if (!isset($this->args['opt_name'])) { // No errors please
                return;
            }

            // If Redux is running as a plugin, this will remove the demo notice and links
            $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
        }

        public function setSections()
        {
            global $wp_registered_sidebars;
            $sidebars = array();

            if ( !empty($wp_registered_sidebars) ) {
                foreach ($wp_registered_sidebars as $sidebar) {
                    $sidebars[$sidebar['id']] = $sidebar['name'];
                }
            }
            $columns = array( '1' => esc_html__('1 Column', 'denso'),
                '2' => esc_html__('2 Columns', 'denso'),
                '3' => esc_html__('3 Columns', 'denso'),
                '4' => esc_html__('4 Columns', 'denso'),
                '5' => esc_html__('5 Columns', 'denso'),
                '6' => esc_html__('6 Columns', 'denso'),
                '7' => esc_html__('7 Columns', 'denso'),
                '8' => esc_html__('8 Columns', 'denso')
            );
            
            $general_fields = array();
            if ( !function_exists( 'wp_site_icon' ) ) {
                $general_fields[] = array(
                    'id' => 'media-favicon',
                    'type' => 'media',
                    'title' => esc_html__('Favicon Upload', 'denso'),
                    'desc' => esc_html__('', 'denso'),
                    'subtitle' => esc_html__('Upload a 16px x 16px .png or .gif image that will be your favicon.', 'denso'),
                );
            }
            $general_fields[] = array(
                'id' => 'preload',
                'type' => 'switch',
                'title' => esc_html__('Preload Website', 'denso'),
                'default' => true,
            );
            $general_fields[] = array(
                'id' => 'image_lazy_loading',
                'type' => 'switch',
                'title' => esc_html__('Image Lazy Loading', 'denso'),
                'default' => true,
            );
            // General Settings Tab
            $this->sections[] = array(
                'icon' => 'el-icon-cogs',
                'title' => esc_html__('General', 'denso'),
                'fields' => $general_fields
            );
            // Header
            $this->sections[] = array(
                'icon' => 'el el-website',
                'title' => esc_html__('Header', 'denso'),
                'fields' => array(
                    array(
                        'id' => 'media-logo',
                        'type' => 'media',
                        'title' => esc_html__('Logo Upload', 'denso'),
                        'subtitle' => esc_html__('Upload a .png or .gif image that will be your logo.', 'denso'),
                    ),
                    array(
                        'id' => 'media-mobile-logo',
                        'type' => 'media',
                        'title' => esc_html__('Mobile Logo Upload', 'denso'),
                        'subtitle' => esc_html__('Upload a .png or .gif image that will be your logo.', 'denso'),
                    ),
                    array(
                        'id' => 'header_type',
                        'type' => 'select',
                        'title' => esc_html__('Header Layout Type', 'denso'),
                        'subtitle' => esc_html__('Choose a header for your website.', 'denso'),
                        'options' => denso_get_header_layouts()
                    ),
                    array(
                        'id' => 'keep_header',
                        'type' => 'switch',
                        'title' => esc_html__('Keep Header When Scroll Mouse', 'denso'),
                        'default' => false
                    ),
                    array(
                        'id' => 'header_contact_info',
                        'type' => 'textarea',
                        'title' => esc_html__('Contact Info Text', 'denso')
                    ),
                    array(
                        'id' => 'header_freeshipping',
                        'type' => 'textarea',
                        'title' => esc_html__('Free Shipping Text', 'denso'),
                        'default' => 'Free Shipping on Orders $50+',
                        'required' => array('header_type', '=', 'v1')
                    ),
                    array(
                        'id' => 'header_welcome',
                        'type' => 'textarea',
                        'title' => esc_html__('Welcome Text', 'denso'),
                        'default' => 'Welcome to Worldwide Denso Store',
                        'required' => array('header_type', '=', array('v2', 'v5', 'v6'))
                    ),
                )
            );
            $this->sections[] = array(
                'subsection' => true,
                'title' => esc_html__('Search Form', 'denso'),
                'fields' => array(
                    array(
                        'id'=>'show_searchform',
                        'type' => 'switch',
                        'title' => esc_html__('Show Search Form', 'denso'),
                        'default' => true,
                        'on' => esc_html__('Yes', 'denso'),
                        'off' => esc_html__('No', 'denso'),
                    ),
                    array(
                        'id'=>'search_type',
                        'type' => 'button_set',
                        'title' => esc_html__('Search Content Type', 'denso'),
                        'required' => array('show_searchform','equals',true),
                        'options' => array('all' => esc_html__('All', 'denso'), 'post' => esc_html__('Post', 'denso'), 'product' => esc_html__('Product', 'denso')),
                        'default' => 'all'
                    ),
                    array(
                        'id'=>'search_category',
                        'type' => 'switch',
                        'title' => esc_html__('Show Categories', 'denso'),
                        'required' => array('search_type', 'equals', array('post', 'product')),
                        'default' => false,
                        'on' => esc_html__('Yes', 'denso'),
                        'off' => esc_html__('No', 'denso'),
                    ),
                    array(
                        'id' => 'autocomplete_search',
                        'type' => 'switch',
                        'title' => esc_html__('Autocomplete search?', 'denso'),
                        'required' => array('show_searchform','equals',true),
                        'default' => 1
                    ),
                    array(
                        'id' => 'show_search_product_image',
                        'type' => 'switch',
                        'title' => esc_html__('Show Search Result Image', 'denso'),
                        'required' => array('autocomplete_search', '=', '1'),
                        'default' => 1
                    ),
                    array(
                        'id' => 'show_search_product_price',
                        'type' => 'switch',
                        'title' => esc_html__('Show Search Result Price', 'denso'),
                        'required' => array(array('autocomplete_search', '=', '1'), array('search_type', '=', 'product')),
                        'default' => 1
                    ),
                )
            );
            // Footer
            $this->sections[] = array(
                'icon' => 'el el-website',
                'title' => esc_html__('Footer', 'denso'),
                'fields' => array(
                    array(
                        'id' => 'footer_type',
                        'type' => 'select',
                        'title' => esc_html__('Footer Layout Type', 'denso'),
                        'subtitle' => esc_html__('Choose a footer for your website.', 'denso'),
                        'options' => denso_get_footer_layouts()
                    ),
                    array(
                        'id' => 'back_to_top',
                        'type' => 'switch',
                        'title' => esc_html__('Back To Top Button', 'denso'),
                        'subtitle' => esc_html__('Toggle whether or not to enable a back to top button on your pages.', 'denso'),
                        'default' => true,
                    ),
                )
            );

            // Blog settings
            $this->sections[] = array(
                'icon' => 'el el-pencil',
                'title' => esc_html__('Blog', 'denso'),
                'fields' => array(
                    array(
                        'id' => 'show_blog_breadcrumbs',
                        'type' => 'switch',
                        'title' => esc_html__('Breadcrumbs', 'denso'),
                        'default' => 1
                    ),
                    array (
                        'title' => esc_html__('Breadcrumbs Background Color', 'denso'),
                        'subtitle' => '<em>'.esc_html__('The breadcrumbs background color of the site.', 'denso').'</em>',
                        'id' => 'blog_breadcrumb_color',
                        'type' => 'color',
                        'transparent' => false,
                    ),
                    array(
                        'id' => 'blog_breadcrumb_image',
                        'type' => 'media',
                        'title' => esc_html__('Breadcrumbs Background', 'denso'),
                        'subtitle' => esc_html__('Upload a .jpg or .png image that will be your breadcrumbs.', 'denso'),
                    ),
                )
            );
            // Archive Blogs settings
            $this->sections[] = array(
                'subsection' => true,
                'title' => esc_html__('Blog & Post Archives', 'denso'),
                'fields' => array(
                    array(
                        'id' => 'blog_archive_layout',
                        'type' => 'image_select',
                        'compiler' => true,
                        'title' => esc_html__('Sidebar position', 'denso'),
                        'subtitle' => esc_html__('Select the variation you want to apply on your store.', 'denso'),
                        'options' => array(
                            'main' => array(
                                'title' => esc_html__('Main Only', 'denso'),
                                'alt' => esc_html__('Main Only', 'denso'),
                                'img' => get_template_directory_uri() . '/inc/assets/images/screen1.png'
                            ),
                            'left-main' => array(
                                'title' => esc_html__('Left - Main Sidebar', 'denso'),
                                'alt' => esc_html__('Left - Main Sidebar', 'denso'),
                                'img' => get_template_directory_uri() . '/inc/assets/images/screen2.png'
                            ),
                            'main-right' => array(
                                'title' => esc_html__('Main - Right Sidebar', 'denso'),
                                'alt' => esc_html__('Main - Right Sidebar', 'denso'),
                                'img' => get_template_directory_uri() . '/inc/assets/images/screen3.png'
                            ),
                        ),
                        'default' => 'left-main'
                    ),
                    array(
                        'id' => 'blog_archive_fullwidth',
                        'type' => 'switch',
                        'title' => esc_html__('Is Full Width?', 'denso'),
                        'default' => false
                    ),
                    array(
                        'id' => 'blog_archive_left_sidebar',
                        'type' => 'select',
                        'title' => esc_html__('Archive Left Sidebar', 'denso'),
                        'subtitle' => esc_html__('Choose a sidebar for left sidebar.', 'denso'),
                        'options' => $sidebars
                    ),
                    array(
                        'id' => 'blog_archive_right_sidebar',
                        'type' => 'select',
                        'title' => esc_html__('Archive Right Sidebar', 'denso'),
                        'subtitle' => esc_html__('Choose a sidebar for right sidebar.', 'denso'),
                        'options' => $sidebars
                        
                    ),
                    array(
                        'id' => 'blog_display_mode',
                        'type' => 'select',
                        'title' => esc_html__('Display Mode', 'denso'),
                        'options' => array(
                            'grid' => esc_html__('Grid Layout', 'denso'),
                            'mansory' => esc_html__('Mansory Layout', 'denso'),
                            'list' => esc_html__('List Layout', 'denso'),
                            'chess' => esc_html__('Chess Layout', 'denso'),
                            'timeline' => esc_html__('Timeline Layout', 'denso'),
                        ),
                        'default' => 'grid'
                    ),
                    array(
                        'id' => 'blog_columns',
                        'type' => 'select',
                        'title' => esc_html__('Blog Columns', 'denso'),
                        'options' => $columns,
                        'default' => 4
                    ),
                    array(
                        'id' => 'blog_item_style',
                        'type' => 'select',
                        'title' => esc_html__('Blog Item Style', 'denso'),
                        'options' => array(
                            'grid' => esc_html__('Grid', 'denso'),
                            'list' => esc_html__('List', 'denso')
                        ),
                        'default' => 'grid'
                    ),
                    array(
                        'id' => 'blog_item_thumbsize',
                        'type' => 'text',
                        'title' => esc_html__('Thumbnail Size', 'denso'),
                        'desc' => esc_html__('Enter thumbnail size. Example: thumbnail, medium, large, full or other sizes defined by current theme.', 'denso'),
                    ),

                )
            );
            // Single Blogs settings
            $this->sections[] = array(
                'subsection' => true,
                'title' => esc_html__('Blog', 'denso'),
                'fields' => array(
                    array(
                        'id' => 'blog_single_layout',
                        'type' => 'image_select',
                        'compiler' => true,
                        'title' => esc_html__('Sidebar position', 'denso'),
                        'subtitle' => esc_html__('Select the variation you want to apply on your store.', 'denso'),
                        'options' => array(
                            'main' => array(
                                'title' => esc_html__('Main Only', 'denso'),
                                'alt' => esc_html__('Main Only', 'denso'),
                                'img' => get_template_directory_uri() . '/inc/assets/images/screen1.png'
                            ),
                            'left-main' => array(
                                'title' => esc_html__('Left - Main Sidebar', 'denso'),
                                'alt' => esc_html__('Left - Main Sidebar', 'denso'),
                                'img' => get_template_directory_uri() . '/inc/assets/images/screen2.png'
                            ),
                            'main-right' => array(
                                'title' => esc_html__('Main - Right Sidebar', 'denso'),
                                'alt' => esc_html__('Main - Right Sidebar', 'denso'),
                                'img' => get_template_directory_uri() . '/inc/assets/images/screen3.png'
                            )
                        ),
                        'default' => 'left-main'
                    ),
                    array(
                        'id' => 'blog_single_fullwidth',
                        'type' => 'switch',
                        'title' => esc_html__('Is Full Width?', 'denso'),
                        'default' => false
                    ),
                    array(
                        'id' => 'blog_single_left_sidebar',
                        'type' => 'select',
                        'title' => esc_html__('Single Blog Left Sidebar', 'denso'),
                        'subtitle' => esc_html__('Choose a sidebar for left sidebar.', 'denso'),
                        'options' => $sidebars
                    ),
                    array(
                        'id' => 'blog_single_right_sidebar',
                        'type' => 'select',
                        'title' => esc_html__('Single Blog Right Sidebar', 'denso'),
                        'subtitle' => esc_html__('Choose a sidebar for right sidebar.', 'denso'),
                        'options' => $sidebars
                    ),
                    array(
                        'id' => 'show_blog_social_share',
                        'type' => 'switch',
                        'title' => esc_html__('Show Social Share', 'denso'),
                        'default' => 1
                    ),
                    array(
                        'id' => 'show_blog_releated',
                        'type' => 'switch',
                        'title' => esc_html__('Show Releated Posts', 'denso'),
                        'default' => 1
                    ),
                    array(
                        'id' => 'number_blog_releated',
                        'type' => 'text',
                        'title' => esc_html__('Number of related posts to show', 'denso'),
                        'required' => array('show_blog_releated', '=', '1'),
                        'default' => 4,
                        'min' => '1',
                        'step' => '1',
                        'max' => '20',
                        'type' => 'slider'
                    ),
                    array(
                        'id' => 'releated_blog_columns',
                        'type' => 'select',
                        'title' => esc_html__('Releated Blogs Columns', 'denso'),
                        'required' => array('show_blog_releated', '=', '1'),
                        'options' => $columns,
                        'default' => 4
                    ),

                )
            );
            
            // Shop Archive settings
            if( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
                $categories = array();
                $attributes = array();
                if ( is_admin() ) {
                    $categories = denso_woocommerce_get_categories(false);

                    $attrs = wc_get_attribute_taxonomies();
                    if ( $attrs ) {
                        foreach ( $attrs as $tax ) {
                            $attributes[wc_attribute_taxonomy_name( $tax->attribute_name )] = $tax->attribute_label;
                        }
                    }
                }
                
                $this->sections[] = array(
                    'icon' => 'el el-shopping-cart',
                    'title' => esc_html__('Shop Archive/Category Page', 'denso'),
                    'fields' => array(
                        array (
                            'id' => 'products_general_setting',
                            'icon' => true,
                            'type' => 'info',
                            'raw' => '<h3 style="margin: 0;"> '.esc_html__('General Setting', 'denso').'</h3>',
                        ),
                        array(
                            'id' => 'product_brand_attribute',
                            'type' => 'select',
                            'title' => esc_html__( 'Brand Attribute', 'denso' ),
                            'subtitle' => esc_html__( 'Choose a product attribute that will be used as brands', 'denso' ),
                            'desc' => esc_html__( 'When you have choosed a brand attribute, you will be able to add brand image to the attributes', 'denso' ),
                            'options' => $attributes
                        ),
                        array(
                            'id' => 'show_category_image_title',
                            'type' => 'switch',
                            'title' => esc_html__('Show Category Image/Title', 'denso'),
                            'default' => false
                        ),
                        array(
                            'id' => 'product_control_bar_position',
                            'type' => 'select',
                            'title' => esc_html__('Control Bar Position', 'denso'),
                            'options' => array(
                                'top' => esc_html__('Top', 'denso'),
                                'before-products' => esc_html__('Before Product Lists', 'denso')
                            ),
                            'default' => 'before-products'
                        ),
                        array(
                            'id' => 'product_display_mode',
                            'type' => 'select',
                            'title' => esc_html__('Display Mode', 'denso'),
                            'subtitle' => esc_html__('Choose a default layout archive product.', 'denso'),
                            'options' => array(
                                'grid' => esc_html__('Grid', 'denso'),
                                'grid-expand' => esc_html__('Grid Expand', 'denso'),
                                'list' => esc_html__('List', 'denso')
                            ),
                            'default' => 'grid'
                        ),
                        array(
                            'id' => 'number_products_per_page',
                            'type' => 'text',
                            'title' => esc_html__('Number of Products Per Page', 'denso'),
                            'default' => 12,
                            'min' => '1',
                            'step' => '1',
                            'max' => '100',
                            'type' => 'slider'
                        ),
                        array(
                            'id' => 'product_columns',
                            'type' => 'select',
                            'title' => esc_html__('Product Columns', 'denso'),
                            'options' => $columns,
                            'default' => 4
                        ),
                        array(
                            'id' => 'show_quickview',
                            'type' => 'switch',
                            'title' => esc_html__('Show Quick View', 'denso'),
                            'default' => 1
                        ),
                        array(
                            'id' => 'show_swap_image',
                            'type' => 'switch',
                            'title' => esc_html__('Show Second Image (Hover)', 'denso'),
                            'default' => 1
                        ),
                        array (
                            'id' => 'products_breadcrumb_setting',
                            'icon' => true,
                            'type' => 'info',
                            'raw' => '<h3 style="margin: 0;"> '.esc_html__('Breadcrumb Setting', 'denso').'</h3>',
                        ),
                        array(
                            'id' => 'show_products_breadcrumbs',
                            'type' => 'switch',
                            'title' => esc_html__('Breadcrumbs', 'denso'),
                            'default' => 1
                        ),
                        array (
                            'title' => esc_html__('Breadcrumbs Background Color', 'denso'),
                            'subtitle' => '<em>'.esc_html__('The breadcrumbs background color of the site.', 'denso').'</em>',
                            'id' => 'products_breadcrumb_color',
                            'type' => 'color',
                            'transparent' => false,
                        ),
                        array(
                            'id' => 'products_breadcrumb_image',
                            'type' => 'media',
                            'title' => esc_html__('Breadcrumbs Background', 'denso'),
                            'subtitle' => esc_html__('Upload a .jpg or .png image that will be your breadcrumbs.', 'denso'),
                        ),
                        array(
                            'id' => 'products_sidebar_setting',
                            'icon' => true,
                            'type' => 'info',
                            'raw' => '<h3 style="margin: 0;"> '.esc_html__('Sidebar Setting', 'denso').'</h3>',
                        ),
                        array(
                            'id' => 'product_archive_layout',
                            'type' => 'image_select',
                            'compiler' => true,
                            'title' => esc_html__('Sidebar position', 'denso'),
                            'subtitle' => esc_html__('Select the layout you want to apply on your archive product page.', 'denso'),
                            'options' => array(
                                'main' => array(
                                    'title' => esc_html__('Main Content', 'denso'),
                                    'alt' => esc_html__('Main Content', 'denso'),
                                    'img' => get_template_directory_uri() . '/inc/assets/images/screen1.png'
                                ),
                                'left-main' => array(
                                    'title' => esc_html__('Left Sidebar - Main Content', 'denso'),
                                    'alt' => esc_html__('Left Sidebar - Main Content', 'denso'),
                                    'img' => get_template_directory_uri() . '/inc/assets/images/screen2.png'
                                ),
                                'main-right' => array(
                                    'title' => esc_html__('Main Content - Right Sidebar', 'denso'),
                                    'alt' => esc_html__('Main Content - Right Sidebar', 'denso'),
                                    'img' => get_template_directory_uri() . '/inc/assets/images/screen3.png'
                                ),
                            ),
                            'default' => 'left-main'
                        ),
                        array(
                            'id' => 'product_archive_fullwidth',
                            'type' => 'switch',
                            'title' => esc_html__('Is Full Width?', 'denso'),
                            'default' => false
                        ),
                        array(
                            'id' => 'product_archive_left_sidebar',
                            'type' => 'select',
                            'title' => esc_html__('Archive Left Sidebar', 'denso'),
                            'subtitle' => esc_html__('Choose a sidebar for left sidebar.', 'denso'),
                            'options' => $sidebars
                        ),
                        array(
                            'id' => 'product_archive_right_sidebar',
                            'type' => 'select',
                            'title' => esc_html__('Archive Right Sidebar', 'denso'),
                            'subtitle' => esc_html__('Choose a sidebar for right sidebar.', 'denso'),
                            'options' => $sidebars
                        ),
                        array(
                            'id' => 'products_block_setting',
                            'icon' => true,
                            'type' => 'info',
                            'raw' => '<h3 style="margin: 0;"> '.esc_html__('Products Block Setting', 'denso').'</h3>',
                        ),
                        array(
                            'id'        => 'product_archive_sort_block',
                            'type'      => 'sorter',
                            'title'     => esc_html__( 'Products Block', 'denso' ),
                            'subtitle'  => esc_html__( 'Please drag and arrange the block', 'denso' ),
                            'options'   => array(
                                'enabled' => array(
                                    'deal'            => esc_html__( 'Products Deals', 'denso' ),
                                    'bestseller'   => esc_html__( 'Best Sellers', 'denso' ),
                                    'new'       => esc_html__( 'New Releases', 'denso' ),
                                    'toprated' => esc_html__( 'Top Rated Products', 'denso' ),
                                    'recommended' => esc_html__( 'Recommended Products', 'denso' )
                                )
                            )
                        ),
                    )
                );
                $this->sections[] = array(
                    'title' => esc_html__('Deals Block Setting', 'denso'),
                    'subsection' => true,
                    'fields' => array(
                        array(
                            'id' => 'products_deal_categories',
                            'type' => 'select',
                            'title' => esc_html__('Display this block in categories', 'denso'),
                            'options' => $categories,
                            'multi' => true,
                        ),
                        array(
                            'id' => 'products_deal_title',
                            'type' => 'text',
                            'title' => esc_html__('Title', 'denso'),
                            'default' => 'Featured Deals in %s products',
                            'desc' => esc_html__('%s for category name', 'denso')
                        ),
                        array(
                            'id' => 'products_deal_number',
                            'type' => 'text',
                            'title' => esc_html__('Number Products', 'denso'),
                            'default' => 12,
                            'min' => '1',
                            'step' => '1',
                            'max' => '100',
                            'type' => 'slider',
                        ),
                        array(
                            'id' => 'products_deal_columns',
                            'type' => 'select',
                            'title' => esc_html__('Product Columns', 'denso'),
                            'options' => $columns,
                            'default' => 4
                        ),
                        array(
                            'id' => 'products_deal_layout',
                            'type' => 'select',
                            'title' => esc_html__('Products Layout', 'denso'),
                            'options' => array(
                                'grid' => esc_html__('Grid', 'denso'),
                                'carousel' => esc_html__('Carousel', 'denso'),
                            ),
                            'default' => 'grid'
                        ),
                        array(
                            'id' => 'products_deal_rows',
                            'type' => 'select',
                            'title' => esc_html__('Number Rows', 'denso'),
                            'options' => array(
                                1 => esc_html__('1 Row', 'denso'),
                                2 => esc_html__('2 Rows', 'denso'),
                                3 => esc_html__('3 Rows', 'denso'),
                            ),
                            'default' => 1,
                            'required' => array('products_deal_layout', 'equals', 'carousel'),
                        ),
                        array(
                            'id' => 'products_deal_style',
                            'type' => 'image_select',
                            'compiler' => true,
                            'title' => esc_html__('Product Style', 'denso'),
                            'options' => array(
                                'inner' => array( 'img' => get_template_directory_uri() . '/inc/assets/images/product-grid.png' ),
                                'list' => array( 'img' => get_template_directory_uri() . '/inc/assets/images/product-list.png' ),
                            ),
                            'default' => 'inner'
                        ),
                        array(
                            'id' => 'products_deal_show_view_more',
                            'type' => 'switch',
                            'title' => esc_html__('Show View More Button', 'denso'),
                            'default' => false
                        ),
                        array(
                            'id' => 'products_deal_view_more',
                            'type' => 'text',
                            'title' => esc_html__('View More Text', 'denso'),
                            'default' => 'See all product deals in %s',
                            'desc' => esc_html__('%s for category name', 'denso'),
                            'required' => array('products_deal_show_view_more', 'equals', true),
                        ),
                    )
                );
                $this->sections[] = array(
                    'title' => esc_html__('BestSeller Block Setting', 'denso'),
                    'subsection' => true,
                    'fields' => array(
                        array(
                            'id' => 'products_bestseller_categories',
                            'type' => 'select',
                            'title' => esc_html__('Display this block in categories', 'denso'),
                            'options' => $categories,
                            'multi' => true,
                        ),
                        array(
                            'id' => 'products_bestseller_title',
                            'type' => 'text',
                            'title' => esc_html__('Title', 'denso'),
                            'default' => 'Best sellers',
                            'desc' => esc_html__('%s for category name', 'denso')
                        ),
                        array(
                            'id' => 'products_bestseller_number',
                            'type' => 'text',
                            'title' => esc_html__('Number Products', 'denso'),
                            'default' => 12,
                            'min' => '1',
                            'step' => '1',
                            'max' => '100',
                            'type' => 'slider',
                        ),
                        array(
                            'id' => 'products_bestseller_columns',
                            'type' => 'select',
                            'title' => esc_html__('Product Columns', 'denso'),
                            'options' => $columns,
                            'default' => 4
                        ),
                        array(
                            'id' => 'products_bestseller_layout',
                            'type' => 'select',
                            'title' => esc_html__('Products Layout', 'denso'),
                            'options' => array(
                                'grid' => esc_html__('Grid', 'denso'),
                                'carousel' => esc_html__('Carousel', 'denso'),
                            ),
                            'default' => 'grid'
                        ),
                        array(
                            'id' => 'products_bestseller_rows',
                            'type' => 'select',
                            'title' => esc_html__('Number Rows', 'denso'),
                            'options' => array(
                                1 => esc_html__('1 Row', 'denso'),
                                2 => esc_html__('2 Rows', 'denso'),
                                3 => esc_html__('3 Rows', 'denso'),
                            ),
                            'default' => 1,
                            'required' => array('products_bestseller_layout', 'equals', 'carousel'),
                        ),
                        array(
                            'id' => 'products_bestseller_style',
                            'type' => 'image_select',
                            'compiler' => true,
                            'title' => esc_html__('Product Style', 'denso'),
                            'options' => array(
                                'inner' => array( 'img' => get_template_directory_uri() . '/inc/assets/images/product-grid.png' ),
                                'list' => array( 'img' => get_template_directory_uri() . '/inc/assets/images/product-list.png' ),
                            ),
                            'default' => 'inner'
                        ),
                        array(
                            'id' => 'products_bestseller_show_view_more',
                            'type' => 'switch',
                            'title' => esc_html__('Show View More Button', 'denso'),
                            'default' => false
                        ),
                        array(
                            'id' => 'products_bestseller_view_more',
                            'type' => 'text',
                            'title' => esc_html__('View More Text', 'denso'),
                            'default' => 'See all best sellers in %s',
                            'desc' => esc_html__('%s for category name', 'denso'),
                            'required' => array('products_bestseller_show_view_more', 'equals', true),
                        ),
                    )
                );
                $this->sections[] = array(
                    'title' => esc_html__('New Releases Block Setting', 'denso'),
                    'subsection' => true,
                    'fields' => array(
                        array(
                            'id' => 'products_new_categories',
                            'type' => 'select',
                            'title' => esc_html__('Display this block in categories', 'denso'),
                            'options' => $categories,
                            'multi' => true,
                        ),
                        array(
                            'id' => 'products_new_title',
                            'type' => 'text',
                            'title' => esc_html__('Title', 'denso'),
                            'default' => 'Hot new releases in %s',
                            'desc' => esc_html__('%s for category name', 'denso')
                        ),
                        array(
                            'id' => 'products_new_number',
                            'type' => 'text',
                            'title' => esc_html__('Number Products', 'denso'),
                            'default' => 12,
                            'min' => '1',
                            'step' => '1',
                            'max' => '100',
                            'type' => 'slider',
                        ),
                        array(
                            'id' => 'products_new_columns',
                            'type' => 'select',
                            'title' => esc_html__('Product Columns', 'denso'),
                            'options' => $columns,
                            'default' => 4
                        ),
                        array(
                            'id' => 'products_new_layout',
                            'type' => 'select',
                            'title' => esc_html__('Products Layout', 'denso'),
                            'options' => array(
                                'grid' => esc_html__('Grid', 'denso'),
                                'carousel' => esc_html__('Carousel', 'denso'),
                            ),
                            'default' => 'grid'
                        ),
                        array(
                            'id' => 'products_new_rows',
                            'type' => 'select',
                            'title' => esc_html__('Number Rows', 'denso'),
                            'options' => array(
                                1 => esc_html__('1 Row', 'denso'),
                                2 => esc_html__('2 Rows', 'denso'),
                                3 => esc_html__('3 Rows', 'denso'),
                            ),
                            'default' => 1,
                            'required' => array('products_new_layout', 'equals', 'carousel'),
                        ),
                        array(
                            'id' => 'products_new_style',
                            'type' => 'image_select',
                            'compiler' => true,
                            'title' => esc_html__('Product Style', 'denso'),
                            'options' => array(
                                'inner' => array( 'img' => get_template_directory_uri() . '/inc/assets/images/product-grid.png' ),
                                'list' => array( 'img' => get_template_directory_uri() . '/inc/assets/images/product-list.png' ),
                            ),
                            'default' => 'inner'
                        ),
                        array(
                            'id' => 'products_new_show_view_more',
                            'type' => 'switch',
                            'title' => esc_html__('Show View More Button', 'denso'),
                            'default' => false
                        ),
                        array(
                            'id' => 'products_new_view_more',
                            'type' => 'text',
                            'title' => esc_html__('View More Text', 'denso'),
                            'default' => 'See all new releases in %s',
                            'desc' => esc_html__('%s for category name', 'denso'),
                            'required' => array('products_new_show_view_more', 'equals', true),
                        ),
                    )
                );
                $this->sections[] = array(
                    'title' => esc_html__('Top Rated Block Setting', 'denso'),
                    'subsection' => true,
                    'fields' => array(
                        array(
                            'id' => 'products_toprated_categories',
                            'type' => 'select',
                            'title' => esc_html__('Display this block in categories', 'denso'),
                            'options' => $categories,
                            'multi' => true,
                        ),
                        array(
                            'id' => 'products_toprated_title',
                            'type' => 'text',
                            'title' => esc_html__('Title', 'denso'),
                            'default' => 'Top rated in %s',
                            'desc' => esc_html__('%s for category name', 'denso')
                        ),
                        array(
                            'id' => 'products_toprated_number',
                            'type' => 'text',
                            'title' => esc_html__('Number Products', 'denso'),
                            'default' => 12,
                            'min' => '1',
                            'step' => '1',
                            'max' => '100',
                            'type' => 'slider',
                        ),
                        array(
                            'id' => 'products_toprated_columns',
                            'type' => 'select',
                            'title' => esc_html__('Product Columns', 'denso'),
                            'options' => $columns,
                            'default' => 4
                        ),
                        array(
                            'id' => 'products_toprated_layout',
                            'type' => 'select',
                            'title' => esc_html__('Products Layout', 'denso'),
                            'options' => array(
                                'grid' => esc_html__('Grid', 'denso'),
                                'carousel' => esc_html__('Carousel', 'denso'),
                            ),
                            'default' => 'grid'
                        ),
                        array(
                            'id' => 'products_toprated_rows',
                            'type' => 'select',
                            'title' => esc_html__('Number Rows', 'denso'),
                            'options' => array(
                                1 => esc_html__('1 Row', 'denso'),
                                2 => esc_html__('2 Rows', 'denso'),
                                3 => esc_html__('3 Rows', 'denso'),
                            ),
                            'default' => 1,
                            'required' => array('products_toprated_layout', 'equals', 'carousel'),
                        ),
                        array(
                            'id' => 'products_toprated_style',
                            'type' => 'image_select',
                            'compiler' => true,
                            'title' => esc_html__('Product Style', 'denso'),
                            'options' => array(
                                'inner' => array( 'img' => get_template_directory_uri() . '/inc/assets/images/product-grid.png' ),
                                'list' => array( 'img' => get_template_directory_uri() . '/inc/assets/images/product-list.png' ),
                            ),
                            'default' => 'inner'
                        ),
                        array(
                            'id' => 'products_toprated_show_view_more',
                            'type' => 'switch',
                            'title' => esc_html__('Show View More Button', 'denso'),
                            'default' => false
                        ),
                        array(
                            'id' => 'products_toprated_view_more',
                            'type' => 'text',
                            'title' => esc_html__('View More Text', 'denso'),
                            'default' => 'See all top rated in %s',
                            'desc' => esc_html__('%s for category name', 'denso'),
                            'required' => array('products_toprated_show_view_more', 'equals', true),
                        ),
                    )
                );
                $this->sections[] = array(
                    'title' => esc_html__('Recommended Block Setting', 'denso'),
                    'subsection' => true,
                    'fields' => array(
                        array(
                            'id' => 'products_recommended_categories',
                            'type' => 'select',
                            'title' => esc_html__('Display this block in categories', 'denso'),
                            'options' => $categories,
                            'multi' => true,
                        ),
                        array(
                            'id' => 'products_recommended_title',
                            'type' => 'text',
                            'title' => esc_html__('Title', 'denso'),
                            'default' => 'Recommended Products',
                            'desc' => esc_html__('%s for category name', 'denso')
                        ),
                        array(
                            'id' => 'products_recommended_number',
                            'type' => 'text',
                            'title' => esc_html__('Number Products', 'denso'),
                            'default' => 12,
                            'min' => '1',
                            'step' => '1',
                            'max' => '100',
                            'type' => 'slider',
                        ),
                        array(
                            'id' => 'products_recommended_columns',
                            'type' => 'select',
                            'title' => esc_html__('Product Columns', 'denso'),
                            'options' => $columns,
                            'default' => 4
                        ),
                        array(
                            'id' => 'products_recommended_layout',
                            'type' => 'select',
                            'title' => esc_html__('Products Layout', 'denso'),
                            'options' => array(
                                'grid' => esc_html__('Grid', 'denso'),
                                'carousel' => esc_html__('Carousel', 'denso'),
                            ),
                            'default' => 'grid'
                        ),
                        array(
                            'id' => 'products_recommended_rows',
                            'type' => 'select',
                            'title' => esc_html__('Number Rows', 'denso'),
                            'options' => array(
                                1 => esc_html__('1 Row', 'denso'),
                                2 => esc_html__('2 Rows', 'denso'),
                                3 => esc_html__('3 Rows', 'denso'),
                            ),
                            'default' => 1,
                            'required' => array('products_recommended_layout', 'equals', 'carousel'),
                        ),
                        array(
                            'id' => 'products_recommended_style',
                            'type' => 'image_select',
                            'compiler' => true,
                            'title' => esc_html__('Product Style', 'denso'),
                            'options' => array(
                                'inner' => array( 'img' => get_template_directory_uri() . '/inc/assets/images/product-grid.png' ),
                                'list' => array( 'img' => get_template_directory_uri() . '/inc/assets/images/product-list.png' ),
                            ),
                            'default' => 'inner'
                        ),
                        array(
                            'id' => 'products_recommended_show_view_more',
                            'type' => 'switch',
                            'title' => esc_html__('Show View More Button', 'denso'),
                            'default' => false
                        ),
                        array(
                            'id' => 'products_recommended_view_more',
                            'type' => 'text',
                            'title' => esc_html__('View More Text', 'denso'),
                            'default' => 'See all top rated in %s',
                            'desc' => esc_html__('%s for category name', 'denso'),
                            'required' => array('products_recommended_show_view_more', 'equals', true),
                        ),
                    )
                );
                // Shop Product Page
                $this->sections[] = array(
                    'title' => esc_html__('Single Product Page', 'denso'),
                    'icon' => 'el el-shopping-cart',
                    'fields' => array(
                        array (
                            'id' => 'product_breadcrumb_setting',
                            'icon' => true,
                            'type' => 'info',
                            'raw' => '<h3 style="margin: 0;"> '.esc_html__('Breadcrumb Setting', 'denso').'</h3>',
                        ),
                        array(
                            'id' => 'show_product_breadcrumbs',
                            'type' => 'switch',
                            'title' => esc_html__('Breadcrumbs', 'denso'),
                            'default' => 1
                        ),
                        array (
                            'title' => esc_html__('Breadcrumbs Background Color', 'denso'),
                            'subtitle' => '<em>'.esc_html__('The breadcrumbs background color of the site.', 'denso').'</em>',
                            'id' => 'product_breadcrumb_color',
                            'type' => 'color',
                            'transparent' => false,
                        ),
                        array(
                            'id' => 'product_breadcrumb_image',
                            'type' => 'media',
                            'title' => esc_html__('Breadcrumbs Background', 'denso'),
                            'subtitle' => esc_html__('Upload a .jpg or .png image that will be your breadcrumbs.', 'denso'),
                        ),
                        array(
                            'id' => 'product_general_setting',
                            'icon' => true,
                            'type' => 'info',
                            'raw' => '<h3 style="margin: 0;"> '.esc_html__('General Setting', 'denso').'</h3>',
                        ),
                        array(
                            'id' => 'show_product_social_share',
                            'type' => 'switch',
                            'title' => esc_html__('Show Social Share', 'denso'),
                            'default' => 1
                        ),
                        array(
                            'id' => 'show_product_review_tab',
                            'type' => 'switch',
                            'title' => esc_html__('Show Product Review Tab', 'denso'),
                            'default' => 1
                        ),
                        array(
                            'id' => 'show_product_product_bought_together',
                            'type' => 'switch',
                            'title' => esc_html__('Show Products Bought together this product', 'denso'),
                            'default' => 1
                        ),
                        array(
                            'id' => 'show_product_product_viewed_together',
                            'type' => 'switch',
                            'title' => esc_html__('Show Products Viewed together this product', 'denso'),
                            'default' => 1
                        ),
                        array(
                            'id' => 'number_product_bought_viewed',
                            'title' => esc_html__('Number of Bought Together/Viewed Together products to show', 'denso'),
                            'default' => 4,
                            'min' => '1',
                            'step' => '1',
                            'max' => '20',
                            'type' => 'slider'
                        ),
                        array(
                            'id' => 'bought_viewed_product_columns',
                            'type' => 'select',
                            'title' => esc_html__('Bought Together/Viewed Together Products Columns', 'denso'),
                            'options' => $columns,
                            'default' => 4
                        ),
                        array(
                            'id' => 'show_product_accessory',
                            'type' => 'switch',
                            'title' => esc_html__('Show Products Accessory', 'denso'),
                            'default' => 1
                        ),
                        array(
                            'id' => 'show_product_releated',
                            'type' => 'switch',
                            'title' => esc_html__('Show Products Releated', 'denso'),
                            'default' => 1
                        ),
                        array(
                            'id' => 'show_product_upsells',
                            'type' => 'switch',
                            'title' => esc_html__('Show Products upsells', 'denso'),
                            'default' => 1
                        ),
                        array(
                            'id' => 'number_product_releated',
                            'title' => esc_html__('Number of related/upsells products to show', 'denso'),
                            'default' => 4,
                            'min' => '1',
                            'step' => '1',
                            'max' => '20',
                            'type' => 'slider'
                        ),
                        array(
                            'id' => 'releated_product_columns',
                            'type' => 'select',
                            'title' => esc_html__('Releated Products Columns', 'denso'),
                            'options' => $columns,
                            'default' => 4
                        ),
                    )
                );
            }

            $this->sections = apply_filters( 'denso_redux_framwork_configs', $this->sections, $sidebars, $columns );
            
            // 404 Page
            $this->sections[] = array(
                'icon' => 'el el-shopping-cart',
                'title' => esc_html__('404 Page', 'denso'),
                'fields' => array(
                    array(
                        'id' => '404_title',
                        'type' => 'text',
                        'title' => esc_html__('Title', 'denso'),
                        'default' => 'Page not found'
                    ),
                    array(
                        'id' => '404_description',
                        'type' => 'textarea',
                        'title' => esc_html__('Desciption', 'denso'),
                        'default' => 'We are sorry, but we can not find the page you were looking for'
                    ),
                )
            );
            // Style
            $this->sections[] = array(
                'icon' => 'el el-icon-css',
                'title' => esc_html__('Style', 'denso'),
                'fields' => array(
                    array (
                        'id' => 'main_font_info',
                        'icon' => true,
                        'type' => 'info',
                        'raw' => '<h3 style="margin: 0;"> '.esc_html__('Content', 'denso').'</h3>',
                    ),
                    array (
                        'title' => esc_html__('Main Theme Color', 'denso'),
                        'subtitle' => esc_html__('The main color of the site.', 'denso'),
                        'id' => 'main_color',
                        'type' => 'color',
                        'transparent' => false,
                    ),
                    array (
                        'title' => esc_html__('Button Hover Background Color', 'denso'),
                        'subtitle' => esc_html__('Button Hover Background Color of the site.', 'denso'),
                        'id' => 'button_bghover',
                        'type' => 'color',
                        'transparent' => false,
                    ),
                    array (
                        'id' => 'site_background',
                        'type' => 'background',
                        'title' => esc_html__('Site Background', 'denso'),
                        'output' => 'body'
                    ),
                    array (
                        'id' => 'container_bg',
                        'type' => 'color_rgba',
                        'title' => esc_html__('Container Background Color', 'denso'),
                        'output' => array(
                            'background-color' =>'#apus-main-content,.wrapper-shop,.single-product .wrapper-shop, .detail-post #comments::before,.detail-post #comments::after,.detail-post #comments
                            .widget.upsells::before, .widget.upsells::after, .widget.related::before, .widget.related::after,.widget.related
                            '
                        )
                    ),
                    array (
                        'id' => 'forms_inputs_bg',
                        'type' => 'color_rgba',
                        'title' => esc_html__('Forms inputs Color', 'denso'),
                        'output' => array(
                            'background-color' =>'.form-control, select, input[type="text"], input[type="email"], input[type="password"], input[type="tel"], textarea, textarea.form-control'
                        )
                    ),
                )
            );
            $this->sections[] = array(
                'subsection' => true,
                'title' => esc_html__('Typography', 'denso'),
                'fields' => array(
                    
                    array (
                        'id' => 'main_font_info',
                        'icon' => true,
                        'type' => 'info',
                        'raw' => '<h3 style="margin: 0;"> '.esc_html__('Body Font', 'denso').'</h3>',
                    ),
                    // Standard + Google Webfonts
                    array (
                        'title' => esc_html__('Font Face', 'denso'),
                        'subtitle' => '<em>'.esc_html__('Pick the Main Font for your site.', 'denso').'</em>',
                        'id' => 'main_font',
                        'type' => 'typography',
                        'line-height' => true,
                        'text-align' => false,
                        'font-style' => false,
                        'font-weight' => true,
                        'all_styles'=> true,
                        'font-size' => true,
                        'color' => true
                    ),
                    array (
                        'title' => esc_html__('Font Face Second', 'denso'),
                        'subtitle' => '<em>'.esc_html__('Pick the Second Font for your site( Heading).', 'denso').'</em>',
                        'id' => 'second_font',
                        'type' => 'typography',
                        'line-height' => true,
                        'text-align' => false,
                        'font-style' => false,
                        'font-weight' => true,
                        'all_styles'=> true,
                        'font-size' => true,
                        'color' => true
                    ),
                    
                    // Header
                    array (
                        'id' => 'secondary_font_info',
                        'icon' => true,
                        'type' => 'info',
                        'raw' => '<h3 style="margin: 0;"> '.esc_html__('Heading', 'denso').'</h3>',
                    ),
                    array (
                        'title' => esc_html__('H1 Font', 'denso'),
                        'subtitle' => '<em>'.esc_html__('Pick the H1 Font for your site.', 'denso').'</em>',
                        'id' => 'h1_font',
                        'type' => 'typography',
                        'line-height' => true,
                        'text-align' => false,
                        'font-style' => false,
                        'font-weight' => true,
                        'all_styles'=> true,
                        'font-size' => true,
                        'color' => true
                    ),
                    array (
                        'title' => esc_html__('H2 Font', 'denso'),
                        'subtitle' => '<em>'.esc_html__('Pick the H2 Font for your site.', 'denso').'</em>',
                        'id' => 'h2_font',
                        'type' => 'typography',
                        'line-height' => true,
                        'text-align' => false,
                        'font-style' => false,
                        'font-weight' => true,
                        'all_styles'=> true,
                        'font-size' => true,
                        'color' => true
                    ),
                    array (
                        'title' => esc_html__('H3 Font', 'denso'),
                        'subtitle' => '<em>'.esc_html__('Pick the H3 Font for your site.', 'denso').'</em>',
                        'id' => 'h3_font',
                        'type' => 'typography',
                        'line-height' => true,
                        'text-align' => false,
                        'font-style' => false,
                        'font-weight' => true,
                        'all_styles'=> true,
                        'font-size' => true,
                        'color' => true
                    ),
                    array (
                        'title' => esc_html__('H4 Font', 'denso'),
                        'subtitle' => '<em>'.esc_html__('Pick the H4 Font for your site.', 'denso').'</em>',
                        'id' => 'h4_font',
                        'type' => 'typography',
                        'line-height' => true,
                        'text-align' => false,
                        'font-style' => false,
                        'font-weight' => true,
                        'all_styles'=> true,
                        'font-size' => true,
                        'color' => true
                    ),
                    array (
                        'title' => esc_html__('H5 Font', 'denso'),
                        'subtitle' => '<em>'.esc_html__('Pick the H5 Font for your site.', 'denso').'</em>',
                        'id' => 'h5_font',
                        'type' => 'typography',
                        'line-height' => true,
                        'text-align' => false,
                        'font-style' => false,
                        'font-weight' => true,
                        'all_styles'=> true,
                        'font-size' => true,
                        'color' => true
                    ),
                    array (
                        'title' => esc_html__('H6 Font', 'denso'),
                        'subtitle' => '<em>'.esc_html__('Pick the H6 Font for your site.', 'denso').'</em>',
                        'id' => 'h6_font',
                        'type' => 'typography',
                        'line-height' => true,
                        'text-align' => false,
                        'font-style' => false,
                        'font-weight' => true,
                        'all_styles'=> true,
                        'font-size' => true,
                        'color' => true
                    ),
                )
            );
            $this->sections[] = array(
                'subsection' => true,
                'title' => esc_html__('Top Bar', 'denso'),
                'fields' => array(
                    array(
                        'id'=>'topbar_bg',
                        'type' => 'background',
                        'title' => esc_html__('Background', 'denso'),
                        'output' => '.apus-topbar'
                    ),
                    array(
                        'title' => esc_html__('Text Color', 'denso'),
                        'id' => 'topbar_text_color',
                        'type' => 'color_rgba',
                        'output' => array(
                            'color' =>'#apus-topbar, .contact-topbar-1 .textwidget .media .media-body .phone-info'
                        )
                    ),
                    array(
                        'title' => esc_html__('Link Color', 'denso'),
                        'id' => 'topbar_link_color',
                        'type' => 'color_rgba',
                        'output' => array(
                            'color' =>'#apus-topbar a'
                        )
                    ),
                    array(
                        'title' => esc_html__('Link Color When Hover', 'denso'),
                        'id' => 'topbar_link_color_hover',
                        'type' => 'color_rgba',
                        'output' => array(
                            'color' =>'#apus-topbar a:hover'
                        )
                    ),
                )
            );
            $this->sections[] = array(
                'subsection' => true,
                'title' => esc_html__('Header', 'denso'),
                'fields' => array(
                    array(
                        'id'=>'header_bg',
                        'type' => 'background',
                        'title' => esc_html__('Background', 'denso'),
                        'output' => '.header-bottom,.header-v3 .header-menu,.header-v4 .header-inner,.header-v5 .header-inner,.header-v2 .header-inner, .header-mobile'
                    ),
                    array(
                        'title' => esc_html__('Text Color', 'denso'),
                        'id' => 'header_text_color',
                        'type' => 'color',
                        'output' => array(
                            'color' =>'#apus-header'
                        )
                    ),
                    array(
                        'title' => esc_html__('Link Color', 'denso'),
                        'id' => 'header_link_color',
                        'type' => 'color',
                        'output' => array(
                            'color' =>'#apus-header a'
                        )
                    ),
                    array(
                        'title' => esc_html__('Link Color Active', 'denso'),
                        'id' => 'header_link_color_active',
                        'type' => 'color',
                        'output' => array(
                            'color' =>'#apus-header .active > a, #apus-header a:active, #apus-header a:hover'
                        )
                    ),
                )
            );
            $this->sections[] = array(
                'subsection' => true,
                'title' => esc_html__('Main Menu', 'denso'),
                'fields' => array(
                    array(
                        'title' => esc_html__('Link Color', 'denso'),
                        'id' => 'main_menu_link_color',
                        'type' => 'color',
                        'output' => array(
                            'color' =>'#apus-header .navbar-nav.megamenu > li > a'
                        )
                    ),
                    array(
                        'title' => esc_html__('Link Color Active', 'denso'),
                        'id' => 'main_menu_link_color_active',
                        'type' => 'color',
                        'output' => array(
                            'color' =>'.navbar-nav.megamenu .dropdown-menu > li.open > a, .navbar-nav.megamenu .dropdown-menu > li.active > a,#apus-header .navbar-nav.megamenu > li.active > a,#apus-header .navbar-nav.megamenu > li:hover > a,#apus-header .navbar-nav.megamenu > li:active > a,.navbar-nav.megamenu .dropdown-menu > li > a:hover, .navbar-nav.megamenu .dropdown-menu > li > a:active'
                        )
                    ),
                )
            );
            $this->sections[] = array(
                'subsection' => true,
                'title' => esc_html__('Footer', 'denso'),
                'fields' => array(
                    array(
                        'id'=>'footer_bg',
                        'type' => 'background',
                        'title' => esc_html__('Background', 'denso'),
                        'output' => '.apus-footer .dark'
                    ),
                    array(
                        'title' => esc_html__('Heading Color', 'denso'),
                        'id' => 'footer_heading_color',
                        'type' => 'color',
                        'output' => array(
                            'color' => '#apus-footer .widgettitle ,#apus-footer .widget-title'
                        )
                    ),
                    array(
                        'title' => esc_html__('Text Color', 'denso'),
                        'id' => 'footer_text_color',
                        'type' => 'color',
                        'output' => array(
                            'color' => '#apus-footer, .apus-footer .contact-info, .apus-copyright'
                        )
                    ),
                    array(
                        'title' => esc_html__('Link Color', 'denso'),
                        'id' => 'footer_link_color',
                        'type' => 'color',
                        'output' => array(
                            'color' => '#apus-footer a'
                        )
                    ),
                    array(
                        'title' => esc_html__('Link Color Hover', 'denso'),
                        'id' => 'footer_link_color_hover',
                        'type' => 'color',
                        'output' => array(
                            'color' => '#apus-footer a:hover'
                        )
                    ),
                )
            );
            
            $this->sections[] = array(
                'subsection' => true,
                'title' => esc_html__('Copyright', 'denso'),
                'fields' => array(
                    array(
                        'id'=>'copyright_bg',
                        'type' => 'background',
                        'title' => esc_html__('Background', 'denso'),
                        'output' => '.apus-copyright'
                    ),
                    array(
                        'title' => esc_html__('Text Color', 'denso'),
                        'id' => 'copyright_text_color',
                        'type' => 'color',
                        'output' => array(
                            'color' => '.apus-copyright'
                        )
                    ),
                    array(
                        'title' => esc_html__('Link Color', 'denso'),
                        'id' => 'copyright_link_color',
                        'type' => 'color',
                        'output' => array(
                            'color' => '.apus-copyright a, .apus-copyright a i'
                        )
                    ),
                    array(
                        'title' => esc_html__('Link Color Hover', 'denso'),
                        'id' => 'copyright_link_color_hover',
                        'type' => 'color',
                        'output' => array(
                            'color' => '.apus-copyright a:hover .apus-copyright a i:hover'
                        )
                    ),
                )
            );

            // Social Media
            $this->sections[] = array(
                'icon' => 'el el-file',
                'title' => esc_html__('Social Media', 'denso'),
                'fields' => array(
                    array(
                        'id' => 'facebook_share',
                        'type' => 'switch',
                        'title' => esc_html__('Enable Facebook Share', 'denso'),
                        'default' => 1
                    ),
                    array(
                        'id' => 'twitter_share',
                        'type' => 'switch',
                        'title' => esc_html__('Enable twitter Share', 'denso'),
                        'default' => 1
                    ),
                    array(
                        'id' => 'linkedin_share',
                        'type' => 'switch',
                        'title' => esc_html__('Enable linkedin Share', 'denso'),
                        'default' => 1
                    ),
                    array(
                        'id' => 'tumblr_share',
                        'type' => 'switch',
                        'title' => esc_html__('Enable tumblr Share', 'denso'),
                        'default' => 1
                    ),
                    array(
                        'id' => 'google_share',
                        'type' => 'switch',
                        'title' => esc_html__('Enable google plus Share', 'denso'),
                        'default' => 1
                    ),
                    array(
                        'id' => 'pinterest_share',
                        'type' => 'switch',
                        'title' => esc_html__('Enable pinterest Share', 'denso'),
                        'default' => 1
                    )
                )
            );
            // Custom Code
            $this->sections[] = array(
                'icon' => 'el-icon-css',
                'title' => esc_html__('Custom CSS/JS', 'denso'),
                'fields' => array(
                    array (
                        'title' => esc_html__('Custom CSS', 'denso'),
                        'subtitle' => esc_html__('Paste your custom CSS code here.', 'denso'),
                        'id' => 'custom_css',
                        'type' => 'ace_editor',
                        'mode' => 'css',
                    ),
                    
                    array (
                        'title' => esc_html__('Header JavaScript Code', 'denso'),
                        'subtitle' => esc_html__('Paste your custom JS code here. The code will be added to the header of your site.', 'denso'),
                        'id' => 'header_js',
                        'type' => 'ace_editor',
                        'mode' => 'javascript',
                    ),
                    
                    array (
                        'title' => esc_html__('Footer JavaScript Code', 'denso'),
                        'subtitle' => esc_html__('Here is the place to paste your Google Analytics code or any other JS code you might want to add to be loaded in the footer of your website.', 'denso'),
                        'id' => 'footer_js',
                        'type' => 'ace_editor',
                        'mode' => 'javascript',
                    ),
                )
            );
            $this->sections[] = array(
                'title' => esc_html__('Import / Export', 'denso'),
                'desc' => esc_html__('Import and Export your Redux Framework settings from file, text or URL.', 'denso'),
                'icon' => 'el-icon-refresh',
                'fields' => array(
                    array(
                        'id' => 'opt-import-export',
                        'type' => 'import_export',
                        'title' => esc_html__('Import Export', 'denso'),
                        'subtitle' => esc_html__('Save and restore your Redux options', 'denso'),
                        'full_width' => false,
                    ),
                ),
            );

            $this->sections[] = array(
                'type' => 'divide',
            );
        }

        /**
         * All the possible arguments for Redux.
         * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
         * */
        public function setArguments()
        {
            $theme = wp_get_theme(); // For use with some settings. Not necessary.

            $preset = denso_get_demo_preset();
            $this->args = array(
                // TYPICAL -> Change these values as you need/desire
                'opt_name' => 'denso_theme_options'.$preset,
                // This is where your data is stored in the database and also becomes your global variable name.
                'display_name' => $theme->get('Name'),
                // Name that appears at the top of your panel
                'display_version' => $theme->get('Version'),
                // Version that appears at the top of your panel
                'menu_type' => 'menu',
                //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                'allow_sub_menu' => true,
                // Show the sections below the admin menu item or not
                'menu_title' => esc_html__('Theme Options', 'denso'),
                'page_title' => esc_html__('Theme Options', 'denso'),

                // You will need to generate a Google API key to use this feature.
                // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                'google_api_key' => '',
                // Set it you want google fonts to update weekly. A google_api_key value is required.
                'google_update_weekly' => false,
                // Must be defined to add google fonts to the typography module
                'async_typography' => true,
                // Use a asynchronous font on the front end or font string
                //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
                'admin_bar' => true,
                // Show the panel pages on the admin bar
                'admin_bar_icon' => 'dashicons-portfolio',
                // Choose an icon for the admin bar menu
                'admin_bar_priority' => 50,
                // Choose an priority for the admin bar menu
                'global_variable' => 'denso_options',
                // Set a different name for your global variable other than the opt_name
                'dev_mode' => false,
                // Show the time the page took to load, etc
                'update_notice' => true,
                // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
                'customizer' => true,
                // Enable basic customizer support
                //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
                //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

                // OPTIONAL -> Give you extra features
                'page_priority' => null,
                // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
                'page_parent' => 'themes.php',
                // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
                'page_permissions' => 'manage_options',
                // Permissions needed to access the options panel.
                'menu_icon' => '',
                // Specify a custom URL to an icon
                'last_tab' => '',
                // Force your panel to always open to a specific tab (by id)
                'page_icon' => 'icon-themes',
                // Icon displayed in the admin panel next to your menu_title
                'page_slug' => '_options',
                // Page slug used to denote the panel
                'save_defaults' => true,
                // On load save the defaults to DB before user clicks save or not
                'default_show' => false,
                // If true, shows the default value next to each field that is not the default value.
                'default_mark' => '',
                // What to print by the field's title if the value shown is default. Suggested: *
                'show_import_export' => true,
                // Shows the Import/Export panel when not used as a field.

                // CAREFUL -> These options are for advanced use only
                'transient_time' => 60 * MINUTE_IN_SECONDS,
                'output' => true,
                // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                'output_tag' => true,
                // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.

                // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                'database' => '',
                // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                'system_info' => false,
                // REMOVE
                'use_cdn' => true
            );

            return $this->args;
        }

    }

    global $reduxConfig;
    $reduxConfig = new Denso_Redux_Framework_Config();
}