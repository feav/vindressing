<?php

function denso_woocommerce_setup() {
    global $pagenow;
    if ( is_admin() && isset($_GET['activated'] ) && $pagenow == 'themes.php' ) {
        $catalog = array(
            'width'     => '330',   // px
            'height'    => '330',   // px
            'crop'      => 1        // true
        );

        $single = array(
            'width'     => '660',   // px
            'height'    => '660',   // px
            'crop'      => 1        // true
        );

        $thumbnail = array(
            'width'     => '130',    // px
            'height'    => '130',   // px
            'crop'      => 1        // true
        );

        // Image sizes
        update_option( 'shop_catalog_image_size', $catalog );       // Product category thumbs
        update_option( 'shop_single_image_size', $single );         // Single product image
        update_option( 'shop_thumbnail_image_size', $thumbnail );   // Image gallery thumbs
    }
}

add_action( 'init', 'denso_woocommerce_setup');

if ( !function_exists('denso_get_products') ) {
    function denso_get_products($categories = array(), $product_type = 'featured_product', $paged = 1, $post_per_page = -1, $orderby = '', $order = '', $includes = array(), $excludes = array(), $author = null) {
        global $woocommerce, $wp_query;
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => $post_per_page,
            'post_status' => 'publish',
            'paged' => $paged,
            'orderby'   => $orderby,
            'order' => $order
        );
        
        if ( isset( $args['orderby'] ) ) {
            if ( 'price' == $args['orderby'] ) {
                $args = array_merge( $args, array(
                    'meta_key'  => '_price',
                    'orderby'   => 'meta_value_num'
                ) );
            }
            if ( 'featured' == $args['orderby'] ) {
                $args = array_merge( $args, array(
                    'meta_key'  => '_featured',
                    'orderby'   => 'meta_value'
                ) );
            }
            if ( 'sku' == $args['orderby'] ) {
                $args = array_merge( $args, array(
                    'meta_key'  => '_sku',
                    'orderby'   => 'meta_value'
                ) );
            }
        }

        switch ($product_type) {
            case 'best_selling':
                $args['meta_key']='total_sales';
                $args['orderby']='meta_value_num';
                $args['ignore_sticky_posts']   = 1;
                $args['meta_query'] = array();
                $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
                $args['meta_query'][] = $woocommerce->query->visibility_meta_query();
                break;
            case 'featured_product':
                $product_visibility_term_ids = wc_get_product_visibility_term_ids();
                $args['tax_query'][] = array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'term_taxonomy_id',
                    'terms'    => $product_visibility_term_ids['featured'],
                );
                break;
            case 'top_rate':
                add_filter( 'posts_clauses',  array( $woocommerce->query, 'order_by_rating_post_clauses' ) );
                $args['meta_query'] = array();
                $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
                $args['meta_query'][] = $woocommerce->query->visibility_meta_query();
                break;
            case 'recent_product':
                $args['meta_query'] = array();
                $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
                break;
            case 'deals':
                $args['meta_query'] = array();
                $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
                $args['meta_query'][] = $woocommerce->query->visibility_meta_query();
                $args['meta_query'][] =  array(
                    array(
                        'key'           => '_sale_price_dates_to',
                        'value'         => time(),
                        'compare'       => '>',
                        'type'          => 'numeric'
                    )
                );
                break;     
            case 'on_sale':
                $product_ids_on_sale    = wc_get_product_ids_on_sale();
                $product_ids_on_sale[]  = 0;
                $args['post__in'] = $product_ids_on_sale;
                break;
            case 'recent_review':
                if($post_per_page == -1) $_limit = 4;
                else $_limit = $post_per_page;
                global $wpdb;
                $query = "SELECT c.comment_post_ID FROM {$wpdb->prefix}posts p, {$wpdb->prefix}comments c
                        WHERE p.ID = c.comment_post_ID AND c.comment_approved > 0 AND p.post_type = 'product' AND p.post_status = 'publish' AND p.comment_count > 0
                        ORDER BY c.comment_date ASC";
                $results = $wpdb->get_results($query, OBJECT);
                $_pids = array();
                foreach ($results as $re) {
                    if(!in_array($re->comment_post_ID, $_pids))
                        $_pids[] = $re->comment_post_ID;
                    if(count($_pids) == $_limit)
                        break;
                }

                $args['meta_query'] = array();
                $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
                $args['meta_query'][] = $woocommerce->query->visibility_meta_query();
                $args['post__in'] = $_pids;

                break;
            case 'rand':
                $args['orderby'] = 'rand';
                break;
            case 'recommended':
                $args['ignore_sticky_posts']=1;
                $args['meta_query'] = array();
                $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
                $args['meta_query'][] = array(
                             'key' => '_apus_recommended',
                             'value' => 'yes'
                         );
                $query_args['meta_query'][] = $woocommerce->query->visibility_meta_query();
                break;
        }

        if ( !empty($categories) && is_array($categories) ) {
            $args['tax_query']    = array(
                array(
                    'taxonomy'      => 'product_cat',
                    'field'         => 'slug',
                    'terms'         => $categories,
                    'operator'      => 'IN'
                )
            );
        }

        if (!empty($includes) && is_array($includes)) {
            $args['post__in'] = $includes;
        }
        
        if ( !empty($excludes) && is_array($excludes) ) {
            $args['post__not_in'] = $excludes;
        }

        if ( !empty($author) ) {
            $args['author'] = $author;
        }
        
        return new WP_Query($args);
    }
}

function denso_woocommerce_get_category_childs( $categories, $id_parent, $level, &$dropdown ) {
    foreach ( $categories as $key => $category ) {
        if ( $category->category_parent == $id_parent ) {
            $dropdown = array_merge( $dropdown, array( $category->slug => str_repeat( "- ", $level ) . $category->name ) );
            unset($categories[$key]);
            denso_woocommerce_get_category_childs( $categories, $category->term_id, $level + 1, $dropdown );
        }
    }
}

function denso_woocommerce_get_categories($show_top = true) {
    if ($show_top) {
        $return = array( '' => esc_html__(' --- Choose a Category --- ', 'denso') );
    } else {
        $return = array();
    }

    $args = array(
        'type' => 'post',
        'child_of' => 0,
        'orderby' => 'name',
        'order' => 'ASC',
        'hide_empty' => false,
        'hierarchical' => 1,
        'taxonomy' => 'product_cat'
    );

    $categories = get_categories( $args );
    denso_woocommerce_get_category_childs( $categories, 0, 0, $return );

    return $return;
}

function denso_autocomplete_options_helper( $options ){
    $output = array();
    $options = array_map('trim', explode(',', $options));
    foreach( $options as $option ){
        $tmp = explode( ":", $option );
        $output[] = $tmp[0];
    }
    return $output; 
}

// get product id who bought also bought
function denso_get_bought_together_products($pids, $exclude_pids = 1)
{
    $all_products = array();
    $pids_count = count($pids);
    $pid = implode(',',$pids);
    global $wpdb, $table_prefix;
    if ($pids_count > 1 ||  ($pids_count == 1 && !$all_products = wp_cache_get( 'apus_bought_together_'.$pid, 'apus_bought_together' )) ) {
        $subsql = "SELECT oim.order_item_id FROM ".$table_prefix."woocommerce_order_itemmeta oim where oim.meta_key='_product_id' and oim.meta_value in ($pid)";
        $sql = "SELECT oi.order_id from  ".$table_prefix."woocommerce_order_items oi where oi.order_item_id in ($subsql) limit 100";

        $all_orders = $wpdb->get_col($sql);
        if($all_orders){
            $all_orders_str = implode(',',$all_orders);
            $subsql2 = "select oi.order_item_id FROM ".$table_prefix."woocommerce_order_items oi where oi.order_id in ($all_orders_str) and oi.order_item_type='line_item'";
            if($exclude_pids){
                $sub_exsql2 = " and oim.meta_value not in ($pid)";
            }
            $sql2 = "select oim.meta_value as product_id,count(oim.meta_value) as total_count from ".$table_prefix."woocommerce_order_itemmeta oim where oim.meta_key='_product_id' $sub_exsql2 and oim.order_item_id in ($subsql2) group by oim.meta_value order by total_count desc limit 15";
            $all_products = $wpdb->get_col($sql2);
            if ($pids_count==1) {
                wp_cache_add( 'apus_bought_together_'.$pid, $all_products, 'apus_bought_together' );
            }
        }
    }
    return $all_products;
}

// add product viewed
function denso_track_product_view() {
    if ( ! is_singular( 'product' ) || !denso_get_config('show_product_product_viewed_together') ) {
        return;
    }

    global $post;

    if ( empty( $_COOKIE['apus_woocommerce_recently_viewed'] ) )
        $viewed_products = array();
    else
        $viewed_products = (array) explode( '|', $_COOKIE['apus_woocommerce_recently_viewed'] );

    if ( ! in_array( $post->ID, $viewed_products ) ) {
        $viewed_products[] = $post->ID;
    }

    if ( sizeof( $viewed_products ) > 15 ) {
        array_shift( $viewed_products );
    }

    // Store for session only
    wc_setcookie( 'apus_woocommerce_recently_viewed', implode( '|', $viewed_products ) );
}
add_action( 'template_redirect', 'denso_track_product_view', 20 );

function denso_woocommerce_relation_product_options()
{
    global $post;
    if ( !denso_get_config('show_product_product_viewed_together') ) {
        return;
    }
    $customer_also_viewed = ! empty( $_COOKIE['apus_woocommerce_recently_viewed'] ) ? (array) explode( '|', $_COOKIE['apus_woocommerce_recently_viewed'] ) : array();
    if ( ($key = array_search($post->ID, $customer_also_viewed)) !== false ) {
        unset($customer_also_viewed[$key] );
    }

    if(!empty($customer_also_viewed))
    {
        foreach($customer_also_viewed as $viewed)
        {
            $option = 'apus_customer_also_viewed_'.$viewed;
            $option_value = get_option($option);
            
            if(isset($option_value) && !empty($option_value))
            {
                $option_value = explode(',', $option_value);
                if(!in_array($post->ID, $option_value))
                {
                    $option_value[] = $post->ID;
                }
            }
                       
            $option_value = (count($option_value) > 1) ? implode(',', $option_value) : $post->ID;

            update_option($option, $option_value);
        }
    }    
}
add_action('woocommerce_after_single_product', 'denso_woocommerce_relation_product_options');

function denso_get_products_customer_also_viewed( $product_id ) {

    $customer_also_viewed = get_option('apus_customer_also_viewed_'.$product_id);  
    if(!empty($customer_also_viewed))        
    {  
        $customer_also_viewed = explode(',',$customer_also_viewed);
        $customer_also_viewed = array_reverse($customer_also_viewed);       
        
        //Skip same product on product page from the list
        if ( ($key = array_search($product_id, $customer_also_viewed)) !== false ) {
            unset($customer_also_viewed[$key] );
        }
        return $customer_also_viewed;
    }
    return false;
}

// cart modal
if ( !function_exists('denso_woocommerce_cart_modal') ) {
    function denso_woocommerce_cart_modal() {
        wc_get_template( 'content-product-cart-modal.php' , array( 'current_product_id' => (int)$_GET['product_id'] ) );
        die;
    }
}

add_action( 'wp_ajax_denso_add_to_cart_product', 'denso_woocommerce_cart_modal' );
add_action( 'wp_ajax_nopriv_denso_add_to_cart_product', 'denso_woocommerce_cart_modal' );


// hooks
if ( !function_exists('denso_woocommerce_enqueue_styles') ) {
    function denso_woocommerce_enqueue_styles() {
        $css_folder = denso_get_css_folder();
        $js_folder = denso_get_js_folder();
        $min = denso_get_asset_min();

        wp_enqueue_style( 'denso-woocommerce', $css_folder . '/woocommerce'.$min.'.css' , 'denso-woocommerce-front' , DENSO_THEME_VERSION, 'all' );
        
        
        if ( is_singular('product') ) {
            // photoswipe
            wp_enqueue_script( 'photoswipe-js', $js_folder . '/photoswipe/photoswipe'.$min.'.js', array( 'jquery' ), '20150315', true );
            wp_enqueue_script( 'photoswipe-ui-js', $js_folder . '/photoswipe/photoswipe-ui-default'.$min.'.js', array( 'jquery' ), '20150315', true );
            wp_enqueue_script( 'photoswipe-init', $js_folder . '/photoswipe/photoswipe.init'.$min.'.js', array( 'jquery' ), '20150315', true );
            wp_enqueue_style( 'photoswipe-style', $js_folder . '/photoswipe/photoswipe'.$min.'.css', array(), '3.2.0' );
            wp_enqueue_style( 'photoswipe-skin-style', $js_folder . '/photoswipe/default-skin/default-skin'.$min.'.css', array(), '3.2.0' );
        }
        $alert_message = array(
            'success'       => sprintf( '<div class="woocommerce-message">%s <a class="button btn btn-primary btn-inverse wc-forward" href="%s">%s</a></div>', esc_html__( 'Products was successfully added to your cart.', 'denso' ), wc_get_cart_url(), esc_html__( 'View Cart', 'denso' ) ),
            'empty'         => sprintf( '<div class="woocommerce-error">%s</div>', esc_html__( 'No Products selected.', 'denso' ) ),
            'no_variation'  => sprintf( '<div class="woocommerce-error">%s</div>', esc_html__( 'Product Variation does not selected.', 'denso' ) ),
            'nonce' => wp_create_nonce( 'ajax-nonce' ),
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'view_more_text' => esc_html__('View More', 'denso'),
            'view_less_text' => esc_html__('View Less', 'denso'),
        );
        wp_register_script( 'denso-woocommerce', $js_folder . '/woocommerce'.$min.'.js', array( 'jquery' ), '20150330', true );
        wp_localize_script( 'denso-woocommerce', 'denso_woo', $alert_message );
        wp_enqueue_script( 'denso-woocommerce' );

        wp_enqueue_script( 'wc-add-to-cart-variation' );
    }
}
add_action( 'wp_enqueue_scripts', 'denso_woocommerce_enqueue_styles', 99 );

// cart
if ( !function_exists('denso_woocommerce_header_add_to_cart_fragment') ) {
    function denso_woocommerce_header_add_to_cart_fragment( $fragments ){
        global $woocommerce;
        $fragments['.top-cart .count'] =  sprintf(_n(' <span class="count"> %d  </span> ', ' <span class="count"> %d </span> ', $woocommerce->cart->cart_contents_count, 'denso'), $woocommerce->cart->cart_contents_count);
        $fragments['.top-cart .total-price'] = '<span class="total-price">'.trim( $woocommerce->cart->get_cart_subtotal() ).'</span>';
        return $fragments;
    }
}
add_filter('woocommerce_add_to_cart_fragments', 'denso_woocommerce_header_add_to_cart_fragment' );

// breadcrumb for woocommerce page
if ( !function_exists('denso_woocommerce_breadcrumb_defaults') ) {
    function denso_woocommerce_breadcrumb_defaults( $args ) {
        $prefix = 'products';
        $class = '';
        if ( is_singular('product') ) {
            $prefix = 'product';
        } else {
            if ( !denso_get_config('product_archive_fullwidth') ) {
                $class = 'container container-shop';
            }
        }
        $breadcrumb_img = denso_get_config($prefix.'_breadcrumb_image');
        $breadcrumb_color = denso_get_config($prefix.'_breadcrumb_color');
        $style = array();
        $breadcrumb_enable = denso_get_config('show_'.$prefix.'_breadcrumbs');
        $archive = '';
        if ( !$breadcrumb_enable ) {
            $style[] = 'display:none';
        }
        if( $breadcrumb_color  ){
            $style[] = 'background-color:'.$breadcrumb_color;
        }
        if ( isset($breadcrumb_img['url']) && !empty($breadcrumb_img['url']) ) {
            $style[] = 'background-image:url(\''.esc_url($breadcrumb_img['url']).'\')';
        }
        $estyle = !empty($style)? ' style="'.implode(";", $style).'"':"";

        if ( is_single() ) {
            $title = esc_html__('Product Detail', 'denso');
        } else {
            $title = esc_html__('Products List', 'denso');
            $archive ='woo-archive';
        }
        $args['wrap_before'] = '<section id="apus-breadscrumb" class="apus-breadscrumb '.$archive.'"'.$estyle.'><div class="clearfix '.esc_attr($class).'"><div class="wrapper-breads"><div class="breadscrumb-inner"><ol class="apus-woocommerce-breadcrumb breadcrumb" ' . ( is_single() ? 'itemprop="breadcrumb"' : '' ) . '>';
        $args['wrap_after'] = '</ol></div></div></div></section>';

        return $args;
    }
}
add_filter( 'woocommerce_breadcrumb_defaults', 'denso_woocommerce_breadcrumb_defaults' );
add_action( 'denso_woo_template_main_before', 'woocommerce_breadcrumb', 30, 0 );

// display woocommerce modes
if ( !function_exists('denso_woocommerce_display_modes') ) {
    function denso_woocommerce_display_modes(){
        global $wp;
        $current_url = denso_shop_page_link(true);

        $url_grid_expand = add_query_arg( 'display_mode', 'grid-expand', remove_query_arg( 'display_mode', $current_url ) );
        $url_grid = add_query_arg( 'display_mode', 'grid', remove_query_arg( 'display_mode', $current_url ) );
        $url_list = add_query_arg( 'display_mode', 'list', remove_query_arg( 'display_mode', $current_url ) );

        $woo_mode = denso_woocommerce_get_display_mode();

        echo '<div class="display-mode">';
        echo '<a href="'.  $url_grid_expand  .'" class=" change-view '.($woo_mode == 'grid-expand' ? 'active' : '').'"><i class="mn-icon-98"></i>'.'</a>';
        echo '<a href="'.  $url_grid  .'" class=" change-view '.($woo_mode == 'grid' ? 'active' : '').'"><i class="mn-icon-99"></i>'.'</a>';
        echo '<a href="'.  $url_list  .'" class=" change-view '.($woo_mode == 'list' ? 'active' : '').'"><i class="mn-icon-105"></i>'.'</a>';
        echo '</div>'; 
    }
}

if ( !function_exists('denso_woocommerce_get_display_mode') ) {
    function denso_woocommerce_get_display_mode() {
        $woo_mode = denso_get_config('product_display_mode', 'grid');
        $modes = array( 'grid', 'grid-expand', 'list' );
        if ( isset($_COOKIE['denso_woo_mode']) && in_array($_COOKIE['denso_woo_mode'], $modes) ) {
            $woo_mode = $_COOKIE['denso_woo_mode'];
        }
        return $woo_mode;
    }
}

if(!function_exists('denso_shop_page_link')) {
    function denso_shop_page_link($keep_query = false ) {
        if ( defined( 'SHOP_IS_ON_FRONT' ) ) {
            $link = home_url();
        } elseif ( is_post_type_archive( 'product' ) || is_page( wc_get_page_id('shop') ) ) {
            $link = get_post_type_archive_link( 'product' );
        } else {
            $link = get_term_link( get_query_var('term'), get_query_var('taxonomy') );
        }

        if( $keep_query ) {
            // Keep query string vars intact
            foreach ( $_GET as $key => $val ) {
                if ( 'orderby' === $key || 'submit' === $key ) {
                    continue;
                }
                $link = add_query_arg( $key, $val, $link );

            }
        }
        return $link;
    }
}

// set display mode to cookie
if ( !function_exists('denso_before_woocommerce_init') ) {
    function denso_before_woocommerce_init() {
        $modes = array( 'grid', 'grid-expand', 'list' );
        if ( isset($_GET['display_mode']) && in_array($_GET['display_mode'], $modes) ) {  
            setcookie( 'denso_woo_mode', trim($_GET['display_mode']) , time()+3600*24*100,'/' );
            $_COOKIE['denso_woo_mode'] = trim($_GET['display_mode']);
        }
    }
}
add_action( 'init', 'denso_before_woocommerce_init' );

// Number of products per page
if ( !function_exists('denso_woocommerce_shop_per_page') ) {
    function denso_woocommerce_shop_per_page($number) {
        $value = denso_get_config('number_products_per_page');
        if ( is_numeric( $value ) && $value ) {
            $number = absint( $value );
        }
        return $number;
    }
}
add_filter( 'loop_shop_per_page', 'denso_woocommerce_shop_per_page' );

// Number of products per row
if ( !function_exists('denso_woocommerce_shop_columns') ) {
    function denso_woocommerce_shop_columns($number) {
        $value = denso_get_config('product_columns');
        if ( in_array( $value, array(1,2,3,4,5,6,7,8) ) ) {
            $number = $value;
        }
        return $number;
    }
}
add_filter( 'loop_shop_columns', 'denso_woocommerce_shop_columns' );

// share box
if ( !function_exists('denso_woocommerce_share_box') ) {
    function denso_woocommerce_share_box() {
        if ( denso_get_config('show_product_social_share') ) {
            get_template_part( 'page-templates/parts/sharebox-product' );
        }
    }
}
add_filter( 'woocommerce_single_product_summary', 'denso_woocommerce_share_box', 100 );

// quickview
if ( !function_exists('denso_woocommerce_quickview') ) {
    function denso_woocommerce_quickview() {
        $args = array(
            'post_type'=>'product',
            'product' => $_GET['productslug']
        );
        $query = new WP_Query($args);
        if ( $query->have_posts() ) {
            while ($query->have_posts()): $query->the_post(); global $product;
                wc_get_template_part( 'content', 'product-quickview' );
            endwhile;
        }
        wp_reset_postdata();
        die;
    }
}

if ( denso_get_global_config('show_quickview') ) {
    add_action( 'wp_ajax_denso_quickview_product', 'denso_woocommerce_quickview' );
    add_action( 'wp_ajax_nopriv_denso_quickview_product', 'denso_woocommerce_quickview' );
}


// swap effect
if ( !function_exists('denso_swap_images') ) {
    function denso_swap_images($size = 'shop_catalog') {
        global $post, $product, $woocommerce;
        
        $output = '';
        $class = 'image-no-effect unveil-image';
        if (has_post_thumbnail()) {
            $product_thumbnail_id = get_post_thumbnail_id();
            $product_thumbnail_title = get_the_title( $product_thumbnail_id );
            $product_thumbnail = wp_get_attachment_image_src( $product_thumbnail_id, $size );
            $placeholder_image = denso_create_placeholder(array($product_thumbnail[1],$product_thumbnail[2]));

            if ( denso_get_config('show_swap_image') ) {
                $attachment_ids = $product->get_gallery_image_ids();
                if ($attachment_ids && isset($attachment_ids[0])) {
                    $class = 'image-hover';
                    $product_thumbnail_hover_title = get_the_title( $attachment_ids[0] );
                    $product_thumbnail_hover = wp_get_attachment_image_src( $attachment_ids[0], $size );
                    
                    if ( denso_get_config('image_lazy_loading') ) {
                        echo '<img src="' . trim( $placeholder_image ) . '" data-src="' . esc_url( $product_thumbnail_hover[0] ) . '" width="' . esc_attr( $product_thumbnail_hover[1] ) . '" height="' . esc_attr( $product_thumbnail_hover[2] ) . '" alt="' . esc_attr( $product_thumbnail_hover_title ) . '" class="attachment-shop-catalog unveil-image image-effect" />';
                    } else {
                        echo '<img src="' . esc_url( $product_thumbnail_hover[0] ) . '" width="' . esc_attr( $product_thumbnail_hover[1] ) . '" height="' . esc_attr( $product_thumbnail_hover[2] ) . '" alt="' . esc_attr( $product_thumbnail_hover_title ) . '" class="attachment-shop-catalog image-effect" />';
                    }
                }
            }
            
            if ( denso_get_config('image_lazy_loading') ) {
                echo '<img src="' . trim( $placeholder_image ) . '" data-src="' . esc_url( $product_thumbnail[0] ) . '" width="' . esc_attr( $product_thumbnail[1] ) . '" height="' . esc_attr( $product_thumbnail[2] ) . '" alt="' . esc_attr( $product_thumbnail_title ) . '" class="attachment-shop-catalog unveil-image '.esc_attr($class).'" />';
            } else {
                echo '<img src="' . esc_url( $product_thumbnail[0] ) . '" width="' . esc_attr( $product_thumbnail[1] ) . '" height="' . esc_attr( $product_thumbnail[2] ) . '" alt="' . esc_attr( $product_thumbnail_title ) . '" class="attachment-shop-catalog '.esc_attr($class).'" />';
            }
        } else {
            $image_sizes = get_option($size.'_image_size');
            $placeholder_width = $image_sizes['width'];
            $placeholder_height = $image_sizes['height'];

            $output .= '<img src="'.wc_placeholder_img_src().'" alt="'.esc_html__('Placeholder' , 'denso').'" class="'.$class.'" width="'.$placeholder_width.'" height="'.$placeholder_height.'" />';
        }
        echo trim($output);
    }
}


// get image
if ( !function_exists('denso_product_get_image') ) {
    function denso_product_get_image($thumb = 'shop_thumbnail') {
        global $product;
        if (has_post_thumbnail()) {
            $product_thumbnail_id = get_post_thumbnail_id();
            $product_thumbnail_title = get_the_title( $product_thumbnail_id );
            $product_thumbnail = wp_get_attachment_image_src( $product_thumbnail_id, $thumb );
            
            $placeholder_image = denso_create_placeholder(array($product_thumbnail[1],$product_thumbnail[2]));

            echo '<div class="product-image">';
            if ( denso_get_config('image_lazy_loading') ) {
                echo '<img src="' . trim( $placeholder_image ) . '" data-src="' . esc_url( $product_thumbnail[0] ) . '" width="' . esc_attr( $product_thumbnail[1] ) . '" height="' . esc_attr( $product_thumbnail[2] ) . '" alt="' . esc_attr( $product_thumbnail_title ) . '" class="attachment-'.esc_attr($thumb).' size-'.esc_attr($thumb).' wp-post-image unveil-image" />';
            } else {
                echo '<img src="' . esc_url( $product_thumbnail[0] ) . '" width="' . esc_attr( $product_thumbnail[1] ) . '" height="' . esc_attr( $product_thumbnail[2] ) . '" alt="' . esc_attr( $product_thumbnail_title ) . '" class="attachment-'.esc_attr($thumb).' size-'.esc_attr($thumb).' wp-post-image" />';
            }
            echo '</div>';
        } else {
            $image_sizes = get_option($thumb.'_image_size');
            $placeholder_width = $image_sizes['width'];
            $placeholder_height = $image_sizes['height'];

            echo '<div class="product-image">';
            echo '<img src="'.wc_placeholder_img_src().'" alt="'.esc_html__('Placeholder' , 'denso').'" width="'.$placeholder_width.'" height="'.$placeholder_height.'" />';
            echo '</div>';
        }
    }
}

// layout class for woo page
if ( !function_exists('denso_woocommerce_content_class') ) {
    function denso_woocommerce_content_class( $class ) {
        $page = 'archive';
        if ( is_singular( 'product' ) ) {
            $page = 'single';
        }
        if( denso_get_config('product_'.$page.'_fullwidth') ) {
            return 'clearfix';
        }
        return $class;
    }
}
add_filter( 'denso_woocommerce_content_class', 'denso_woocommerce_content_class' );

// get layout configs
if ( !function_exists('denso_get_woocommerce_layout_configs') ) {
    function denso_get_woocommerce_layout_configs() {
        $page = 'archive';
        
        $left = denso_get_config('product_'.$page.'_left_sidebar');
        $right = denso_get_config('product_'.$page.'_right_sidebar');

        switch ( denso_get_config('product_'.$page.'_layout') ) {
            case 'left-main':
                $configs['left'] = array( 'sidebar' => $left, 'class' => 'col-lg-2 col-md-3 col-xs-12'  );
                $configs['main'] = array( 'class' => 'col-md-9 col-lg-10 col-xs-12' );
                break;
            case 'main-right':
                $configs['right'] = array( 'sidebar' => $right,  'class' => 'col-lg-2 col-md-3 col-xs-12' ); 
                $configs['main'] = array( 'class' => 'col-md-9 col-lg-10 col-xs-12' );
                break;
            case 'main':
                $configs['main'] = array( 'class' => 'col-md-12 col-xs-12' );
                break;
            case 'left-main-right':
                $configs['left'] = array( 'sidebar' => $left,  'class' => 'col-lg-2 col-md-3 col-xs-12'  );
                $configs['right'] = array( 'sidebar' => $right, 'class' => 'col-lg-2 col-md-3 col-xs-12' ); 
                $configs['main'] = array( 'class' => 'col-md-9 col-lg-10 col-xs-12' );
                break;
            default:
                $configs['main'] = array( 'class' => 'col-md-12 col-xs-12' );
                break;
        }

        return $configs; 
    }
}

// Show/Hide related, upsells products
if ( !function_exists('denso_woocommerce_related_upsells_products') ) {
    function denso_woocommerce_related_upsells_products($located, $template_name) {
        $content_none = get_template_directory() . '/woocommerce/content-none.php';
        $show_product_releated = denso_get_config('show_product_releated');
        if ( 'single-product/related.php' == $template_name ) {
            if ( !$show_product_releated  ) {
                $located = $content_none;
            }
        } elseif ( 'single-product/up-sells.php' == $template_name ) {
            $show_product_upsells = denso_get_config('show_product_upsells');
            if ( !$show_product_upsells ) {
                $located = $content_none;
            }
        }

        return apply_filters( 'denso_woocommerce_related_upsells_products', $located, $template_name );
    }
}
add_filter( 'wc_get_template', 'denso_woocommerce_related_upsells_products', 10, 2 );

if ( !function_exists( 'denso_product_tabs' ) ) {
    function denso_product_tabs($tabs) {
        global $post;
        
        if (get_post_meta( $post->ID, 'apus_product_features', true )) {
            $tabs['specifications'] = array(
                'title' => esc_html__('Features', 'denso'),
                'priority' => 15,
                'callback' => 'denso_display_features'
            );
        }

        if ( !denso_get_config('show_product_review_tab') && isset($tabs['reviews']) ) {
            unset( $tabs['reviews'] ); 
        }
        if ( isset($tabs['vendor_ratings_tab']) ) {
            unset( $tabs['vendor_ratings_tab'] );
        }
        unset( $tabs['additional_information'] ); 
        return $tabs;
    }
}
add_filter( 'woocommerce_product_tabs', 'denso_product_tabs', 90 );

function denso_woocommerce_output_product_data_tabs_content() {
    get_template_part( 'woocommerce/single-product/tabs/tabs-content' );
}

if ( !function_exists( 'denso_minicart') ) {
    function denso_minicart() {
        $template = apply_filters( 'denso_minicart_version', '' );
        get_template_part( 'woocommerce/cart/mini-cart-button', $template ); 
    }
}
// Wishlist
add_filter( 'yith_wcwl_button_label', 'denso_woocomerce_icon_wishlist'  );
add_filter( 'yith-wcwl-browse-wishlist-label', 'denso_woocomerce_icon_wishlist_add' );
function denso_woocomerce_icon_wishlist( $value='' ){
    return '<i class="mn-icon-1246"></i>'.'<span class="sub-title">'.esc_html__('Add to Wishlist','denso').'</span>';
}

function denso_woocomerce_icon_wishlist_add(){
    return '<i class="mn-icon-7"></i>'.'<span class="sub-title">'.esc_html__('Wishlisted','denso').'</span>';
}
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );


function denso_woocommerce_get_ajax_products() {
    $categories = isset($_POST['categories']) ? $_POST['categories'] : '';
    $columns = isset($_POST['columns']) ? $_POST['columns'] : 4;
    $number = isset($_POST['number']) ? $_POST['number'] : 4;
    $product_type = isset($_POST['product_type']) ? $_POST['product_type'] : '';
    $layout_type = isset($_POST['layout_type']) ? $_POST['layout_type'] : '';

    $categories_id = !empty($categories) ? array($categories) : array();
    $loop = apus_themer_get_products( $categories_id, $product_type, 1, $number );
    if ( $loop->have_posts()) {
        wc_get_template( 'layout-products/'.$layout_type.'.php' , array( 'loop' => $loop, 'columns' => $columns, 'number' => $number ) );
    }
    exit();
}
add_action( 'wp_ajax_denso_get_products', 'denso_woocommerce_get_ajax_products' );
add_action( 'wp_ajax_nopriv_denso_get_products', 'denso_woocommerce_get_ajax_products' );


function denso_show_percent_disount() {
    global $product;
    $regular_price = $product->get_regular_price();
    $sale_price = $product->get_sale_price();

    if ( !empty($regular_price) && !empty($sale_price) ) {
        $percentage = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );

        return $percentage.esc_html__('%', 'denso');
    } else {
        return '';
    }
}
function denso_show_wooswatches() {
    return 'denso_show_wooswatches';
}
add_filter( 'apus-wooswatches-show-on-loop', 'denso_show_wooswatches' );

function denso_next_product_link($output, $format, $link, $post, $adjacent) {
    if (empty($post) || $post->post_type != 'product') {
        return $output;
    }
    $title = get_the_title( $post->ID );
    $product = wc_get_product( $post->ID );
    return '<div class="nav-right"><div class="icon"><i class="mn-icon-165"></i></div><div class="next-product product-nav">
        <a class="before-hover" href="'.esc_url(get_permalink($post->ID)).'" title="'.esc_attr($title).'">
            '.get_the_post_thumbnail( $post->ID,'shop_thumbnail' ).'
        </a>
        <a class="on-hover" href="'.esc_url(get_permalink($post->ID)).'" title="'.esc_attr($title).'">
            <span class="nav-product-title">'.$title.'</span>
            <span class="price">'.$product->get_price_html().'</span>
        </a>
        </div></div>';
}

add_filter( 'next_post_link', 'denso_next_product_link', 100, 5 );

function denso_previous_product_link($output, $format, $link, $post, $adjacent) {
    if (empty($post) || $post->post_type != 'product') {
        return $output;
    }
    $title = get_the_title( $post->ID );
    $product = wc_get_product( $post->ID );
    return '<div class="nav-left"><div class="icon"><i class="mn-icon-164"></i></div><div class="previous-product product-nav">
        <a class="before-hover" href="'.esc_url(get_permalink($post->ID)).'" title="'.esc_attr($title).'">
            '.get_the_post_thumbnail( $post->ID, 'shop_thumbnail' ).'
        </a>
        <a class="on-hover" href="'.esc_url(get_permalink($post->ID)).'" title="'.esc_attr($title).'">
            <span class="nav-product-title">'.$title.'</span>
            <span class="price">'.$product->get_price_html().'</span>
        </a>
        </div></div>';
    
}
add_filter( 'previous_post_link', 'denso_previous_product_link', 100, 5 );


function denso_woocommerce_photoswipe() {
    ?>
    <div class="rating-popover-content woocommerce"></div>
    <?php
    if ( !is_singular('product') ) {
        return;
    }
    ?>
    <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="pswp__bg"></div>

        <div class="pswp__scroll-wrap">

          <div class="pswp__container">
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
          </div>

          <div class="pswp__ui pswp__ui--hidden">

            <div class="pswp__top-bar">
                <div class="pswp__counter"></div>
                <button class="pswp__button pswp__button--close" title="<?php echo esc_html__('Close (Esc)', 'denso'); ?>"></button>
                <button class="pswp__button pswp__button--share" title="<?php echo esc_html__('Share', 'denso'); ?>"></button>
                <button class="pswp__button pswp__button--fs" title="<?php echo esc_html__('Toggle fullscreen', 'denso'); ?>"></button>
                <button class="pswp__button pswp__button--zoom" title="<?php echo esc_html__('Zoom in/out', 'denso'); ?>"></button>
                <div class="pswp__preloader">
                    <div class="pswp__preloader__icn">
                      <div class="pswp__preloader__cut">
                        <div class="pswp__preloader__donut"></div>
                      </div>
                    </div>
                </div>
            </div>
            <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                <div class="pswp__share-tooltip"></div>
            </div>
            <button class="pswp__button pswp__button--arrow--left" title="<?php echo esc_html__('Previous (arrow left)', 'denso'); ?>"></button>
            <button class="pswp__button pswp__button--arrow--right" title="<?php echo esc_html__('Next (arrow right)', 'denso'); ?>"></button>
            <div class="pswp__caption">
              <div class="pswp__caption__center"></div>
            </div>
          </div>

        </div>
    </div>
    <?php
}
add_action( 'wp_footer', 'denso_woocommerce_photoswipe' );


if ( denso_get_global_config('show_product_review_tab') ) {
    add_action( 'wp_ajax_denso_quickview_rating_product', 'denso_woocommerce_quickview_rating_product' );
    add_action( 'wp_ajax_nopriv_denso_quickview_rating_product', 'denso_woocommerce_quickview_rating_product' );
}
function denso_woocommerce_quickview_rating_product() {
    $args = array(
        'post_type' => 'product',
        'product' => $_GET['productslug']
    );
    $query = new WP_Query($args);
    if ( $query->have_posts() ) {
        while ($query->have_posts()): $query->the_post(); global $product;
            wc_get_template_part( 'content', 'product-quickview-review' );
        endwhile;
    }
    wp_reset_postdata();
    die;
}

function denso_woocommerce_get_price_html_format_sale_price($price, $regular_price, $sale_price) {
    $price = '<span class="sale-price"><ins>' . ( is_numeric( $sale_price ) ? wc_price( $sale_price ) : $sale_price ) . '</ins> <del>' . ( is_numeric( $regular_price ) ? wc_price( $regular_price ) : $regular_price ) . '</del></span>';
    return $price;
}
add_filter( 'woocommerce_format_sale_price', 'denso_woocommerce_get_price_html_format_sale_price', 10, 3 );


//
if ( ! function_exists( 'denso_woocommerce_content' ) ) {

    function denso_woocommerce_content() {
        if ( is_singular( 'product' ) ) {

            while ( have_posts() ) : the_post();
                wc_get_template_part( 'content', 'single-product' );
            endwhile;

        } else {

            wc_get_template_part( 'content', 'archive-product' );

        }
    }
}

function denso_get_all_subcategories_levels($parent_id, $parent_slug, &$return = array()) {
    $return[] = $parent_slug;
    $args = array(
        'hierarchical' => true,
        'show_option_none' => '',
        'hide_empty' => true,
        'parent' => $parent_id,
        'taxonomy' => 'product_cat'
    );
    $cats = get_categories($args);
    foreach ($cats as $cat) {
        denso_get_all_subcategories_levels($cat->term_id, $cat->slug, $return);
    }
    return $return;
}

function denso_display_products_by_category($parent_slug, $type) {
    wc_get_template( 'content-archive-block-products.php' , array( 'parent_slug' => $parent_slug, 'type' => $type ) );
}

function denso_display_features() {
    get_template_part( 'woocommerce/single-product/tabs/features' );
}

function denso_woocommerce_accessories() {
    get_template_part( 'woocommerce/single-product/tabs/accessories' );
}

function denso_display_bought_together_product() {
    if ( denso_get_config('show_product_product_bought_together') ) {
        get_template_part( 'woocommerce/single-product/bought-together-product' );
    }
}

function denso_display_viewed_together_product() {
    if ( denso_get_config('show_product_product_viewed_together') ) {
        get_template_part( 'woocommerce/single-product/viewed-together-product' );
    }
}

function denso_woocommerce_single_countdown() {
    get_template_part( 'woocommerce/single-product/countdown' );
}

function denso_shop_control_bar() {
    echo '<div class="apus-filter">';
    do_action( 'denso_shop_control_bar' );
    echo '</div>';
}

remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

add_action( 'denso_shop_control_bar', 'denso_woocommerce_display_modes', 5 );
add_action( 'denso_shop_control_bar', 'woocommerce_result_count', 10 );

add_action( 'denso_before_products', 'denso_shop_control_bar' , 2 );
