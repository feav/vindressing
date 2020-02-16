<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class JmsHelper {
    public $params;
    public function __construct() {
        $this->params = new JmsAjaxSearch_Params();

        add_action( 'wp_enqueue_scripts', array( $this, 'init_scripts' ) );

        add_action( 'wp_ajax_get_search', array( $this, 'get_search' ) );
        add_action( 'wp_ajax_nopriv_get_search', array( $this, 'get_search' ) );
    }
    public function init_scripts() {
        wp_enqueue_style( 'search-fornt', JMS_AJAXSEARCH_CSS_URL . 'style.css' );

        wp_enqueue_script( 'ajax-script', JMS_AJAXSEARCH_JS_URL . 'ajax-search.min.js', array( 'jquery' ), JMS_AJAXSEARCH_VERSION );
        wp_localize_script( 'ajax-script', 'jmsajaxsearch_ajax', array( 'ajax_url' => admin_url('admin-ajax.php'), 'time_out' => $this->params->get_param( 'time_to_show' ) ) );

        /*Custom*/
        $popup_link_cl    		= $this->params->get_param( 'popup_link_cl' );
        $popup_text_cl         	= $this->params->get_param( 'popup_text_cl' );
        $popup_bg   			= $this->params->get_param( 'popup_bg' );
        $_custom_css 			= $this->params->get_param( 'custom_css' );
        $popup_border_radius   	= $this->params->get_param( 'popup_border_radius' );
        $popup_text_size		= $this->params->get_param( 'popup_text_size' ). 'px';
        $popup_link_size		= $this->params->get_param( 'popup_link_size' ). 'px';

        $popup_border_radius = $popup_border_radius . 'px';

        $custom_css    = "
                #search_result {
                        background-color: {$popup_bg};
                        color:{$popup_text_cl} !important;
                        border-radius:{$popup_border_radius};
						font-size:{$popup_text_size};
                }
                 #search_result a {
                        color:{$popup_link_cl} !important;
						font-size:{$popup_link_size} !important;
                }" . $_custom_css;

        wp_add_inline_style( 'search-fornt', $custom_css );
    }

    public function get_categories_tree() {
        $product_categories = $this->params->get_param( 'product_categories', true );
        if ($product_categories == 1 || $product_categories == null) {
            $args = array(
                'taxonomy'     => 'product_cat',
                'child_of'     => 0,
                'parent'       => 0,
                'orderby'      => 'name',
                'show_count'   => 1,
                'pad_counts'   => 0,
                'hierarchical' => 1,
                'title_li'     => '',
                'hide_empty'   => 0
            );
            $product_categories = get_categories( $args );
            ?>
            <select name="product_categories" id="product_categories" class="product_categories">
                <option value="none" selected=""><?php esc_html_e( 'All Categories', 'jmsajaxsearch' ) ?></option>
                <?php
                foreach ($product_categories as $item) {
                    ?>
                    <option value="<?php echo $item->term_id; ?>"><?php echo $item->name; ?></option>
                    <?php
                    $this->get_option($item->term_id, $level = 0);
                }
                ?>
            </select>
            <?php
        }
        else { ?>
            <select name="product_categories" id="product_categories">
                <?php
                foreach ($product_categories as $category => $k) {
                    ?>
                    <option value="<?php echo $k; ?>"><?php echo get_the_category_by_ID($k); ?></option>
                    <?php
                    $this->get_option($k, $level = 0);
                }
                ?>
            </select>
            <?php
        }
    }

    public function get_option($id_cat, $level) {
        $args2 = array(
            'taxonomy'     => 'product_cat',
            'child_of'     => 0,
            'parent'       => $id_cat,
            'orderby'      => 'name',
            'show_count'   => 1,
            'pad_counts'   => 0,
            'hierarchical' => 1,
            'title_li'     => '',
            'hide_empty'   => 0
        );
        $sub_cats = get_categories( $args2 );
        if (count($sub_cats) > 0) {
            $level = $level + 1;
            foreach ($sub_cats as $cat) {
                ?>
                <option value="<?php echo $cat->term_id; ?>"><?php for ($i=0; $i < $level; $i++) {
                        echo '-';
                    } echo $cat->name; ?></option>
                <?php
                $this->get_option($cat->term_id, $level);
            }
        }

    }

    public function get_search() {
        if(isset($_GET['keyword'])) {
            $keyword = $_GET['keyword'];
            if(isset($_GET['product_cat'])) {
                $product_cat_id = $_GET['product_cat'];
            }
            $key_word_lenght = $this->params->get_param( 'key_word_lenght' );
            if( strlen($keyword) < $key_word_lenght) {
                echo '<div id="result">'. esc_html__('Please enter at least', 'jmsajaxsearch') . ' ' .$key_word_lenght. ' ' . esc_html__('characters', 'jmsajaxsearch') . '</div>';
            }
            else {
                $filter = "AND hs.post_title LIKE '%".$keyword."%' ";
                $search_type = $this->params->get_param( 'search_type', 'product');
                $limit="";
                $items_show = $this->params->get_param( 'items_show' );
                if( $items_show > 0 ) {
                    $limit = "LIMIT ".$items_show;
                }
                if ( isset($search_type) && $search_type == 'product' ) {
					$post_type = "product";
					$products = $this->get_data($post_type, $keyword, $items_show, $product_cat_id);
					if(count($products) < 1) {
                        $products = 'no';
                    }
                }
                if ( isset($search_type) && $search_type == 'post' ) {
					$post_type = "post";
					$posts = $this->get_data($post_type, $keyword, $items_show);
                    if(count($posts) < 1) {
                        $posts = 'no';
                    }
                }
                if ( isset($search_type) && $search_type == 'page' ) {
					$post_type = "page";
					$pages = $this->get_data($post_type, $keyword, $items_show);
                    if(count($pages) < 1) {
                        $pages = 'no';
                    }
                }
                $this->get_result_html($products, $posts, $pages );
            }
            wp_die();
        }
        die();
    }

    public function get_result_html($products, $posts, $pages) {
        ?>
        <div id="result">
            <?php
            if ($products != null) {
                $image_show = $this->params->get_param( 'product_image_show' );
                $price_show = $this->params->get_param( 'product_price_show' );
                $short_desc_show = $this->params->get_param( 'product_short_desc_show' );
                ?>
                <div class="result-wrapper">
                    <h4>
                        <?php esc_html_e( 'Products', 'jmsajaxsearch' ) ?>
                    </h4>
                    <?php
                    foreach ($products as $product) {
                        ?>
                        <div class="content-preview">
                            <?php
                            if( $image_show != 0 && isset($product['thumbnail']) ) {
                                ?>
                                <div class="featured-image">
                                    <a href="<?php echo $product['permalink']; ?>">
                                        <?php echo $product['thumbnail']; ?>
                                    </a>
                                </div>
                                <?php
                            }
                            ?>
                            <div class="item-desc">
                                <a href="<?php echo $product['permalink']; ?>" class="product-name"><?php echo $product['value']; ?></a>
                                <?php
                                if( $price_show != 0 && $product['price'] ) {
                                    ?>
                                    <div class="content-price ">
                                        <?php echo $product['price']; ?>
                                    </div>
                                    <?php
                                    if( $short_desc_show != 0 && isset($product['description']) ){
                                        ?>
                                        <div class="short-desc">
                                            <?php echo $product['description']; ?>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <?php
                    }
                    if($products == 'no') {
                        echo "No Product";
                    }
                    ?>
                </div>
                <?php
            }
            if ($posts != null) {
                $post_image_show = $this->params->get_param( 'post_image_show' );
                $post_author_show = $this->params->get_param( 'post_author_show' );
                $post_date_show = $this->params->get_param( 'post_date_show' );
                $post_read_more_show = $this->params->get_param( 'post_read_more_show' );
                ?>
                <div class="result-wrapper">
                    <h4>
                        <?php esc_html_e( 'Posts', 'jmsajaxsearch' ) ?>
                    </h4>
                    <?php
                    foreach ($posts as $post) {
                        ?>
                        <div class="content-preview">
                            <?php
                            if( $post_image_show != 0 ) {
                                ?>
                                <div class="featured-image">
                                    <a href="<?php echo $post['permalink']; ?>">
                                        <?php echo $post['thumbnail']; ?>
                                    </a>
                                </div>
                                <?php
                            }
                            ?>
                            <div class="item-desc">
                                <a href="<?php echo $post['permalink']; ?>" class="product-name">
									<?php echo $post['value']; ?>
								</a>
                                <?php
                                if( $post_author_show != 0) {
                                    ?>
                                    <div class="auth">
                                        <?php echo $post['auth_name']; ?>
                                    </div>
                                    <?php
                                }
                                ?>
                                <?php
                                if( $post_date_show != 0) {
                                    ?>
                                    <div class="date">
                                        <?php echo date("Y/m/d", strtotime($post['date'])) ; ?>
                                    </div>
                                    <?php
                                }
                                ?>
                                <?php
                                if( $post_read_more_show != 0) {
                                    ?>
                                    <div class="read-more">
                                        <a href="<?php echo $post['permalink']; ?>"><?php esc_html_e( 'Read more', 'jmsajaxsearch' ); ?></a>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <?php
                    }
                    if($posts == 'no') {
                        echo "No Post";
                    }
                    ?>
                </div>
                <?php
            }
            if ($pages != null) {
                $page_image_show = $this->params->get_param( 'page_image_show' );
                $page_date_show = $this->params->get_param( 'page_date_show' );
                $page_read_more_show = $this->params->get_param( 'page_read_more_show' );
                ?>
                <div class="result-wrapper">
                    <h4>
                        <?php esc_html_e( 'Pages', 'jmsajaxsearch' ) ?>
                    </h4>
                    <?php
                    foreach ($pages as $page) {
                        ?>
                        <div class="content-preview">
                            <?php
                            if( $page_image_show != 0 ) {
                                ?>
                                <div class="featured-image">
                                    <a href="<?php echo $page['permalink']; ?>">
                                        <?php echo $page['thumbnail']; ?>
                                    </a>
                                </div>
                                <?php
                            }
                            ?>
                            <div class="item-desc">
                                <a href="<?php echo $page['permalink']; ?>" class="product-name">
									<?php echo $page['value']; ?>
								</a>
                                <?php
                                if( $page_date_show != 0) {
                                    ?>
                                    <div class="date">
                                        <?php echo date("Y/m/d", strtotime($page['date'])) ; ?>
                                    </div>
                                    <?php
                                }
                                ?>
                                <?php
                                if( $page_read_more_show != 0) {
                                    ?>
                                    <div class="read-more">
                                        <a href="<?php echo $page['permalink']; ?>">
											<?php esc_html_e( 'Read more', 'jmsajaxsearch' ); ?>
										</a>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <?php
                    }
                    if($pages == 'no') {
                        echo "No Page";
                    }
                    ?>
                </div>
                <?php
            }
            ?>
        </div>
        <?php
    }

	public function get_data($post_type, $keyword, $items_show, $cat_id="") {
		$tax = 'category';
		$tax_arr = null;
		if($post_type == 'product' ) {
			$tax = 'product_cat';
			$tax_arr = array(
				array(
					'taxonomy' => $tax,
					'field' => 'id',
					'terms' => $cat_id,
					'operator' => 'IN'
				)
			);
		}
		$args = array(
			'post_type' => $post_type,
			'post_status'    => 'publish',
			'posts_per_page' => $items_show,
			's' => $keyword,
			'tax_query' => $tax_arr
		);
		$suggestions = array();

		$results = new WP_Query( $args );
		if( $results->have_posts() ) {
			$factory = new WC_Product_Factory();
			while( $results->have_posts() ) {
				$results->the_post();
				if( $post_type == 'product' ) {
					$product = $factory->get_product( get_the_ID() );
					$suggestions[] = array(
						'value' => get_the_title(),
						'permalink' => get_the_permalink(),
						'price' => $product->get_price_html(),
						'thumbnail' => $product->get_image(),
						'description' => get_the_content()
					);
				} else {
					$suggestions[] = array(
						'value' => get_the_title(),
						'permalink' => get_the_permalink(),
						'thumbnail' => get_the_post_thumbnail( null, 'medium', '' ),
						'auth_name' => get_the_author_meta('display_name'),
						'date' => get_the_date()
					);
				}
			}
			wp_reset_postdata();
		}
		return $suggestions;
	}
}
