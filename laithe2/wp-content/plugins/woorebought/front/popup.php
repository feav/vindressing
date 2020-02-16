<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WooReBought_Popup {
	public $params;
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'init_scripts' ) );
		add_action( 'wp_footer', array( $this, 'wp_footer' ) );

		add_action( 'wp_ajax_nopriv_woorebought_show_product', array( $this, 'show_product_html' ) );
		add_action( 'wp_ajax_woorebought_show_product', array( $this, 'show_product_html' ) );

		add_action( 'woocommerce_order_status_completed', array( $this, 'woocommerce_order_status_completed' ) );
		add_action( 'woocommerce_order_status_pending', array( $this, 'woocommerce_order_status_completed' ) );
		$this->params = new WooReBought_Params();
	}

	public function woocommerce_order_status_completed( $order_id ) {
		$product_src = $this->params->get_param( 'product_src' );
		if ( ! $product_src ) {
			update_option( '_woorebought_prefix', substr( md5( date( "YmdHis" ) ), 0, 10 ) );
		}
	}

	public function show_product_html() {
		$enable        		= $this->params->get_param( 'enable' );
		$all_page  			= $this->params->get_param( 'all_page' );
		$assign_pages 		= array();
		if($this->params->get_param( 'assign_pages' ))
			$assign_pages  		= $this->params->get_param( 'assign_pages' );
		$exclude_pages 		= array();
		if($this->params->get_param( 'exclude_pages' ))
			$exclude_pages  	= $this->params->get_param( 'exclude_pages' );
		$page_check = false;
		$current_page_id = get_the_ID();
		if($all_page && !in_array( $current_page_id, $exclude_pages )) {
			$page_check = true;
		}
		if(	!$all_page && in_array( $current_page_id, $assign_pages )) {
			$page_check = true;
		}
		if ( $page_check && $enable ) {
			echo $this->gen_product( true );
		}
		die;
	}

	public function gen_product( $fisrt = false ) {
		$image_position         = $this->params->get_param( 'image_position' );
		$popup_position         = $this->params->get_param( 'popup_position' );

		$popup_show_trans 		= $this->params->get_param( 'popup_show_trans' );
		$popup_hide_trans  		= $this->params->get_param( 'popup_hide_trans' );
		$ajax_enable            = $this->params->get_param( 'ajax_enable' );

		$class                 = array();
		$class[]               = $image_position ? $image_position : '';

		$product_id            = filter_input( INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT );
		$background_image      = $this->params->get_param( 'background_image' );

		if ( $product_id ) {
			$product_id_str = 'data-product="' . esc_attr( $product_id ) . '">';
		} else {
			$product_id_str = '';
		}

		$class[] = $popup_position;
		ob_start();
		if ( !$ajax_enable ) {
			$rebought_content = $this->params->get_param( 'rebought_content' );
			$image_redirect    = $this->params->get_param( 'image_redirect' );
			$products = $this->get_data_args();
			if ( count( $products ) ) {
				?>
				<div id="woorebought-popup" class="<?php echo implode( ' ', $class ) ?> animated" style="display: none;"
					 data-show_trans="<?php echo empty( $popup_show_trans ) ? esc_attr( 'fadeIn' ) : esc_attr( $popup_show_trans ); ?>"
					 data-hide_trans="<?php echo empty( $popup_hide_trans ) ? esc_attr( 'fadeOut' ) : esc_attr( $popup_hide_trans ); ?>"
					 data-products="<?php echo base64_encode( json_encode( $this->get_data_args() ) ) ?>"
					 data-popup_content="<?php echo base64_encode( json_encode( $rebought_content ) ) ?>"
					 data-image="<?php echo esc_attr( $image_redirect ) ?>">
					<div class="woorebought-content"></div>
					<?php if ( $this->params->get_param( 'popup_close_icon' ) ) {
						?>
						<span id="popup-close"></span>
						<?php
					} ?>
				</div>
			<?php }
		} else {
			?>
			<div id="woorebought-popup" class="<?php echo implode( ' ', $class ) ?> animated" style="display: none;"
				 data-url="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ) ?>"
				 data-show_trans="<?php echo empty( $popup_show_trans ) ? esc_attr( 'fadein' ) : esc_attr( $popup_show_trans ); ?>"
				 data-hide_trans="<?php echo empty( $popup_hide_trans ) ? esc_attr( 'fadeout' ) : esc_attr( $popup_hide_trans ); ?>">

				<?php if ( $fisrt ) {
					echo $this->rebought_content();
				} ?>

			</div>
			<?php
		}

		return ob_get_clean();
	}
	public function wp_footer() {
		$enable        		= $this->params->get_param( 'enable' );
		$all_page  			= $this->params->get_param( 'all_page' );
		$assign_pages 		= array();
		if($this->params->get_param( 'assign_pages' ))
			$assign_pages  		= $this->params->get_param( 'assign_pages' );
		$exclude_pages 		= array();
		if($this->params->get_param( 'exclude_pages' ))
			$exclude_pages  	= $this->params->get_param( 'exclude_pages' );
		$page_check = false;
		$current_page_id = get_the_ID();
		if($all_page && !in_array( $current_page_id, $exclude_pages )) {
			$page_check = true;
		}
		if(	!$all_page && in_array( $current_page_id, $assign_pages )) {
			$page_check = true;
		}
		if ( $page_check && $enable ) {
			echo $this->gen_product();
		}
	}

	function init_scripts() {
		?>
		<script>
			var rb_loop = <?php echo $this->params->get_param( 'loop' ,'1' ); ?>;
			var rb_init_delay = <?php echo $this->params->get_param( 'init_delay', '5' ); ?>;
			var rb_total = <?php echo $this->params->get_param( 'total', '30' ); ?>;
			var rb_display_time = <?php echo $this->params->get_param( 'display_time', '5' ); ?>;
			var rb_next_time = <?php echo $this->params->get_param( 'next_time', '20' ); ?>;
		</script>
		<?php
		wp_enqueue_style( 'woorebought-animate', JMS_WOOREBOUGHT_CSS_URL . 'animate.min.css', array(), JMS_WOOREBOUGHT_VERSION );
		wp_enqueue_style( 'woorebought', JMS_WOOREBOUGHT_CSS_URL . 'woorebought.css', array(), JMS_WOOREBOUGHT_VERSION );
		wp_enqueue_script( 'woorebought', JMS_WOOREBOUGHT_JS_URL . 'woorebought.min.js', array( 'jquery' ), JMS_WOOREBOUGHT_VERSION );

		/*Custom*/
		$popup_link_cl    		= $this->params->get_param( 'popup_link_cl' );
		$popup_text_cl         	= $this->params->get_param( 'popup_text_cl' );
		$popup_time_cl         	= $this->params->get_param( 'popup_time_cl' );
		$popup_bg   			= $this->params->get_param( 'popup_bg' );
		$_custom_css 			= $this->params->get_param( 'custom_css' );
		$popup_border_radius   	= $this->params->get_param( 'popup_border_radius' );
		$popup_text_size		= $this->params->get_param( 'popup_text_size' ). 'px';
		$popup_link_size		= $this->params->get_param( 'popup_link_size' ). 'px';
		$popup_time_size		= $this->params->get_param( 'popup_time_size' ). 'px';

		$popup_border_radius = $popup_border_radius . 'px';
		$custom_css    = "
                #woorebought-popup {
                        background-color: {$popup_bg};
                        color:{$popup_text_cl} !important;
                        border-radius:{$popup_border_radius};
						font-size:{$popup_text_size};
                }
				#woorebought-popup small {
                        color:{$popup_time_cl};
						font-size:{$popup_time_size};
                }
                 #woorebought-popup a {
                        color:{$popup_link_cl} !important;
						font-size:{$popup_link_size};
                }" . $_custom_css;
		wp_add_inline_style( 'woorebought', $custom_css );


	}
	function woorebought_prefix() {
		$prefix = get_option( '_woorebought_prefix', date( "Ymd" ) );

		return $prefix . '_products_' . date( "Ymd" );
	}



	protected function get_data_args() {

		$prefix   = $this->woorebought_prefix();
		$products = get_transient( 'woorebought_data_nonajax' . $prefix );
		if ( ! is_array( $products ) || ! count( array_filter( $products ) ) ) {

			$products = $this->get_product( false );
			if ( count( $products ) && !empty($products)) {
				$new_data     = array();
				$product_link = $this->params->get_param( 'product_link' );
				foreach ( $products as $product ) {
					$p             = wc_get_product( $product['id'] );
					$data          = array();
					$data['title'] = $p->get_title();
					if ( $p->is_type( 'external' ) && $product_link ) {
						// do stuff for simple products
						$link = get_post_meta( $p->get_id(), '_product_url', '#' );
						if ( ! $link ) {
							$link = get_permalink( $p->get_id() );
							$link = wp_nonce_url( $link, 'woorebought_click', 'link' );
						}
					} else {
						// do stuff for everything else
						$link = get_permalink( $p->get_id() );
						$link = wp_nonce_url( $link, 'woorebought_click', 'link' );
					}
					$product_thumb = 'shop_thumbnail';

					if ( has_post_thumbnail( $product['id'] ) ) {
						$image_link = get_the_post_thumbnail_url( $product['id'], $product_thumb );
					} elseif ( $p->get_type() == 'variation' ) {
						$parent_id  = $p->get_parent_id();
						$image_link = get_the_post_thumbnail_url( $parent_id, $product_thumb );
					} else {
						$image_link = '';
					}

					$data['product_link'] = $link;
					$data['time']         = $product['time'];
					$data['address']         = $product['address'];
					$data['image_link']   = $image_link;
					$new_data[]           = $data;
				}
				shuffle( $new_data );
				if ( count( $new_data ) ) {
					set_transient( 'woorebought_data_nonajax' . $prefix, $new_data, 3600 );
				}
				if ( count( $new_data ) > 2 ) {
					return array_slice( $new_data, 0, 2 );
				} else {
					return $new_data;
				}
			} else {
				return false;
			}
		} else {
			shuffle( $products );
			if ( count( $products ) > 2 ) {
				return array_slice( $products, 0, 2 );
			} else {
				return $products;
			}
		}

	}
	protected function rebought_content() {
		$rebought_content 	= $this->params->get_param( 'rebought_content' );
		$popup_close_icon   = $this->params->get_param( 'popup_close_icon' );
		$product_src      	= $this->params->get_param( 'product_src' );
		$product_link      	= $this->params->get_param( 'product_link' );
		$image_redirect    	= $this->params->get_param( 'image_redirect' );

		$popup_content = '';
		$keys     = array(
			'{address}',
			'{product_name}',
			'{product_link}',
			'{time_ago}'
		);

		$product = $this->get_product();
		if ( $product ) {
			$product_id = $product['id'];
		} else {
			return false;
		}

		$address       = trim( $product['address'] );
		$time       = trim( $product['time'] );
		if ( $product_src ) {
			$time = $this->cal_time_ago( $time );
		}

		$_product = wc_get_product( $product_id );

		$product = esc_html( strip_tags( get_the_title( $product_id ) ) );
		//return $time;
		if ( $_product->is_type( 'external' ) && $product_link ) {
			$link = get_post_meta( $product_id, '_product_url', '#' );
			if ( ! $link ) {
				$link = get_permalink( $product_id );
				$link = wp_nonce_url( $link, 'woorebought_click', 'link' );
			}
		} else {
			$link = get_permalink( $product_id );
			$link = wp_nonce_url( $link, 'woorebought_click', 'link' );
		}
		ob_start(); ?>
		<a target="_blank" href="<?php echo esc_url( $link ) ?>"><?php echo esc_html( $product ) ?></a>
		<?php $product_with_link = ob_get_clean();
		ob_start(); ?>
		<small><?php echo esc_html__( 'About', 'woorebought' ) . ' ' . esc_html( $time ) . ' ' . esc_html__( 'ago', 'woorebought' ) ?></small>
		<?php $time_ago = ob_get_clean();
		$product_thumb  = 'shop_thumbnail';

		if ( has_post_thumbnail( $product_id ) ) {
			if ( $image_redirect ) {
				$popup_content .= '<a target="_blank" href="' . esc_url( $link ) . '">';
			}
			$popup_content .= '<img src="' . esc_url( get_the_post_thumbnail_url( $product_id, $product_thumb ) ) . '" class="wcn-product-image"/>';
			if ( $image_redirect ) {
				$popup_content .= '</a>';
			}
		} elseif ( $_product->get_type() == 'variation' ) {

			$parent_id = $_product->get_parent_id();
			if ( $parent_id ) {
				$popup_content .= '<a target="_blank" href="' . esc_url( $link ) . '">';
			}
			$popup_content .= '<img src="' . esc_url( get_the_post_thumbnail_url( $parent_id, $product_thumb ) ) . '" class="wcn-product-image"/>';
			if ( $image_redirect ) {
				$popup_content .= '</a>';
			}
		}

		$replaced         = array(
			$address,
			$product,
			$product_with_link,
			$time_ago
		);
		$popup_content         .= str_replace( $keys, $replaced, '<p>' . strip_tags( $rebought_content ) . '</p>' );
		ob_start();
		if ( $popup_close_icon ) {
			?>
			<span id="popup-close"></span>
			<?php
		}
		$popup_content .= ob_get_clean();

		return $popup_content;
	}

	protected function get_product( $ajax = true ) {

		/*Get All page*/
		$product_src 	= $this->params->get_param( 'product_src' );
		$frame_time   	= $this->params->get_param( 'frame_time' );
		$address_list  	= $this->params->get_param( 'address_list' );
		if ( $address_list ) {
			$address_list = explode( "\n", $address_list );
			$address_list = array_filter( $address_list );
		}
		$prefix       	= $this->woorebought_prefix();
		$use_cache 		= $this->params->get_param( 'use_cache' );

		$cache = get_transient( $prefix );
		if ( ! is_array( get_transient( $prefix ) ) ) {
			$cache = array();
		}
		$data_cache = array_filter( $cache );
		$sec_datas  = count( $data_cache ) ? $data_cache : array();
		if ( count( $sec_datas ) ) {
			$index = rand( 0, count( $sec_datas ) - 1 );
			$data  = $sec_datas[$index];
			if ( ! use_cache && $product_src ) {
				$data['time'] = $this->cal_time_ago( current_time( 'timestamp' ) - rand( 10, $frame_time * 60 ), true );
				if ( is_array( $address_list ) && count($address_list) > 0 ) {
					$index        		= rand( 0, count( $address_list ) - 1 );
					$address_text    	= $address_list[$index];
					$data['address'] 	= $address_text;
				}

			}

			return $data;

		}
		if ( $product_src == 'featured_products' ) {
			$tax_query[] = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'name',
				'terms'    => 'featured',
				'operator' => 'IN',
			);
			$args = array(
				'post_type'           => 'product',
				'post_status'         => 'publish',
				'ignore_sticky_posts' => 1,
				'posts_per_page'      => 6,
				'orderby'             => 'date',
				'order'               => 'DESC',
				'tax_query'           => $tax_query
			);
			$the_query = new WP_Query( $args );
			if ( $the_query->have_posts() ) {
				$_products = array();
				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					$_products[] = get_the_ID();
				}
			}
			// Reset Post Data
			wp_reset_postdata();


			$products = array();
			foreach ( $_products as $_product ) {
				$post_type = get_post_type( $_product );
				if ( $post_type == 'product_variation' || $post_type == 'product' ) {
				} else {
					continue;
				}

				if ( is_array( $address_list ) && count( $address_list ) > 0 ) {
					$index     = rand( 0, count( $address_list ) - 1 );
					$address_text = $address_list[$index];
				} else {
					$address_text = $address_list;
				}

				$product['id']         = $_product;
				$product['time']       = $this->cal_time_ago( current_time( 'timestamp' ) - rand( 10, $frame_time * 60 ), true );
				$product['address']       = $address_text;
				$products[]            = $product;
			}

			if ( count( $products ) ) {
				if ( $ajax ) {
					$index = rand( 0, count( $products ) - 1 );
					$data  = $products[$index];
					set_transient( $prefix, $products, 3600 );
				} else {
					return $products;
				}
			} else {
				return false;
			}
		} elseif ( $product_src == 'orders' ) {
			$order_time_num  	= $this->params->get_param( 'order_time_num', 'days' );
			$order_time_type 	= $this->params->get_param( 'order_time_type' );
			$order_status       = $this->params->get_param( 'order_status', array( 'wc-completed' ) );
			$current_time = '';
			if ( $order_time_num ) {
				$current_time = strtotime( "-" . $order_time_num . " " . $order_time_type );
			}
			$args = array(
				'post_type'      => 'shop_order',
				'post_status'    => $order_status,
				'posts_per_page' => '100',
				'orderby'        => 'date',
				'order'          => 'DESC'
			);
			if ( $current_time ) {
				$args['date_query'] = array(
					array(
						'after'     => array(
							'year'   => date( "Y", $current_time ),
							'month'  => date( "m", $current_time ),
							'day'    => date( "d", $current_time ),
							'hour'   => date( "H", $current_time ),
							'minute' => date( "i", $current_time ),
							'second' => date( "s", $current_time ),
						),
						'inclusive' => true,
						'compare'   => '<=',
						'column'    => 'post_date',
						'relation'  => 'AND'
					),
				);
			}
			$my_query = new WP_Query( $args );

			$products = array();
			if ( $my_query->have_posts() ) {
				while ( $my_query->have_posts() ) {
					$my_query->the_post();
					$order = new WC_Order( get_the_ID() );
					$items = $order->get_items();

					foreach ( $items as $item ) {
						if ( isset( $item['product_id'] ) ) {
							$p_data = wc_get_product( $item['product_id'] );
							if ( ! $p_data->is_in_stock() ) {
								continue;
							}
							$product['id']         = isset( $item['product_id'] ) ? $item['product_id'] : '';
							$product['time']       = get_the_date( "Y-m-d H:i:s" );
							$product['address']       = ucfirst( get_post_meta( get_the_ID(), '_billing_city', true ) );
							$products[]            = $product;
						}
					}
					$products = array_map( "unserialize", array_unique( array_map( "serialize", $products ) ) );

					if ( count( $products ) >= 2 ) {
						break;
					}
				}
				// Reset Post Data
				wp_reset_postdata();
			}

			if ( count( $products ) ) {
				if ( $ajax ) {
					$index = rand( 0, count( $products ) - 1 );
					$data  = $products[$index];
					set_transient( $prefix, $products, 3600 );
				} else {
					return $products;
				}
			} else {
				return false;
			}

		} else if ( $product_src == 'products' ) {
			$_products = $this->params->get_param( 'select_products' );

			$_products = is_array( $_products ) ? $_products : array();

			if ( count( array_filter( $_products ) ) < 1 ) {
				$args      = array(
					'post_type'      => 'product',
					'post_status'    => 'publish',
					'posts_per_page' => '2',
					'orderby'        => 'rand',
					'meta_query'     => array(
						array(
							'key'     => '_stock_status',
							'value'   => 'outofstock',
							'compare' => '!='
						),
					)

				);
				$the_query = new WP_Query( $args );
				if ( $the_query->have_posts() ) {
					$_products = array();
					while ( $the_query->have_posts() ) {
						$the_query->the_post();
						$_products[] = get_the_ID();
					}
					// Reset Post Data
					wp_reset_postdata();
				}

			}
			$products = array();
			foreach ( $_products as $_product ) {
				$post_type = get_post_type( $_product );
				if ( $post_type == 'product_variation' || $post_type == 'product' ) {
				} else {
					continue;
				}

				if ( is_array( $address_list ) ) {
					$index     = rand( 0, count( $address_list ) - 1 );
					$address_text = $address_list[$index];
				} else {
					$address_text = $address_list;
				}

				$product['id']         = $_product;
				$product['time']       = $this->cal_time_ago( current_time( 'timestamp' ) - rand( 10, $frame_time * 60 ), true );
				$product['address']       = $address_text;
				$products[]            = $product;
			}

			if ( count( $products ) ) {
				if ( $ajax ) {
					$index = rand( 0, count( $products ) - 1 );
					$data  = $products[$index];
					set_transient( $prefix, $products, 3600 );
				} else {
					return $products;
				}
			} else {
				return false;
			}

		} elseif ( $product_src == 'latest_products' ) {
			$args      = array(
				'post_type'      => 'product',
				'post_status'    => 'publish',
				'posts_per_page' => 2,
				'orderby'        => 'date',
				'order'          => 'DESC'
			);
			$the_query = new WP_Query( $args );
			if ( $the_query->have_posts() ) {
				$_products = array();
				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					$_products[] = get_the_ID();
				}
			}
			// Reset Post Data
			wp_reset_postdata();
			$products = array();
			if ( count( $_products ) ) {
				foreach ( $_products as $_product ) {
					$post_type = get_post_type( $_product );
					if ( $post_type == 'product_variation' || $post_type == 'product' ) {
					} else {
						continue;
					}

					if ( is_array( $address_list ) ) {
						$index     = rand( 0, count( $address_list ) - 1 );
						$address_text = $address_list[$index];
					} else {
						$address_text = $address_list;
					}

					$product['id']         = $_product;
					$product['time']       = $this->cal_time_ago( current_time( 'timestamp' ) - rand( 10, $frame_time * 60 ), true );
					$product['address']       = $address_text;
					$products[]            = $product;
				}
			}
			if ( count( $products ) ) {
				if ( $ajax ) {
					$index = rand( 0, count( $products ) - 1 );
					$data  = $products[$index];
					set_transient( $prefix, $products, 3600 );
				} else {
					return $products;
				}
			} else {
				return false;
			}
		} else {
			$cates = $this->params->get_param( 'select_categories' );
			if ( count( $cates ) ) {
				$categories = get_terms(
					array(
						'taxonomy' => 'product_cat',
						'include'  => $cates
					)
				);

				$categories_checked = array();
				if ( count( $categories ) ) {
					foreach ( $categories as $category ) {
						$categories_checked[] = $category->term_id;
					}
				} else {
					return false;
				}

				$args      = array(
					'post_type'      => 'product',
					'post_status'    => 'publish',
					'posts_per_page' => 2,
					'orderby'        => 'rand',
					'tax_query'      => array(
						array(
							'taxonomy'         => 'product_cat',
							'field'            => 'id',
							'terms'            => $categories_checked,
							'include_children' => false,
							'operator'         => 'IN'
						)
					),
					'meta_query'     => array(
						array(
							'key'     => '_stock_status',
							'value'   => 'outofstock',
							'compare' => '!='
						),
					)
				);
				$the_query = new WP_Query( $args );
				if ( $the_query->have_posts() ) {
					$_products = array();
					while ( $the_query->have_posts() ) {
						$the_query->the_post();
						$_products[] = get_the_ID();
					}
				}
				// Reset Post Data
				wp_reset_postdata();

				$products = array();
				foreach ( $_products as $_product ) {

					if ( is_array( $address_list ) ) {
						$index     = rand( 0, count( $address_list ) - 1 );
						$address_text = $address_list[$index];
					} else {
						$address_text = $address_list;
					}

					$product['id']         = $_product;
					$product['time']       = $this->cal_time_ago( current_time( 'timestamp' ) - rand( 10, $frame_time * 60 ), true );
					$product['address']       = $address_text;
					$products[]            = $product;
				}

				if ( count( $products ) ) {
					if ( $ajax ) {
						$index = rand( 0, count( $products ) - 1 );
						$data  = $products[$index];
						set_transient( $prefix, $products, 3600 );
					} else {
						return $products;
					}
				} else {
					return false;
				}
			}
		}

		return $data;

	}

	protected function cal_time_ago( $time, $number = false, $calculate = false ) {
		if ( ! $number ) {
			if ( $time ) {
				$time = strtotime( $time );
			} else {
				return false;
			}
		}
		if ( ! $calculate ) {
			$current_time = current_time( 'timestamp' );
			$time_ago = $current_time - $time;
		} else {
			$time_ago = $time;
		}
		if ( $time_ago > 0 ) {
			/*Check day*/
			$day = $time_ago / ( 24 * 3600 );
			$day = intval( $day );
			if ( $day > 1 ) {
				return $day . ' ' . esc_html__( 'days', 'woorebought' );
			} elseif ( $day > 0 ) {
				return $day . ' ' . esc_html__( 'day', 'woorebought' );
			}
			$hour = $time_ago / ( 3600 );
			$hour = intval( $hour );
			if ( $hour > 1 ) {
				return $hour . ' ' . esc_html__( 'hours', 'woorebought' );
			} elseif ( $hour > 0 ) {
				return $hour . ' ' . esc_html__( 'hour', 'woorebought' );
			}
			$min = $time_ago / ( 60 );
			$min = intval( $min );
			if ( $min > 1 ) {
				return $min . ' ' . esc_html__( 'minutes', 'woorebought' );
			} elseif ( $min > 0 ) {
				return $min . ' ' . esc_html__( 'minute', 'woorebought' );
			}
			return intval( $time_ago ) . ' ' . esc_html__( 'seconds', 'woorebought' );
		} else {
			return esc_html__( 'a few seconds', 'woorebought' );
		}
	}



}
