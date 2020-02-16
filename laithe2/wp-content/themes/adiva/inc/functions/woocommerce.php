<?php
if ( !class_exists( 'WooCommerce' ) ) return;

/**
 * ------------------------------------------------------------------------------------------------
 * Unhook the WooCommerce wrappers
 * ------------------------------------------------------------------------------------------------
 */
 /**/

remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

if( !function_exists( 'adiva_primary_navigation_wrapper' ) ) {
	/**
	 * The primary navigation wrapper
	 */
	function adiva_primary_navigation_wrapper() {
		$content_class = '';
		echo '<div class="page-content">';
	}
}

if( !function_exists( 'adiva_primary_navigation_wrapper_close' ) ) {
	/**
	 * The primary navigation wrapper close
	 */
	function adiva_primary_navigation_wrapper_close() {
		echo '</div>';
	}
}
add_action('woocommerce_before_main_content', 'adiva_primary_navigation_wrapper', 10);
add_action('woocommerce_after_main_content', 'adiva_primary_navigation_wrapper_close', 10);


// Wrapp cart totals
add_action( 'woocommerce_before_cart_totals', function() {
	echo '<div class="cart-totals-inner">';
}, 1);
add_action( 'woocommerce_after_cart_totals', function() {
	echo '</div><!--.cart-totals-inner-->';
}, 200);

remove_action( 'woocommerce_before_single_product', 'wc_print_notices', 10 );
remove_action( 'woocommerce_before_shop_loop', 'wc_print_notices', 10 );
remove_action( 'woocommerce_before_checkout_form', 'wc_print_notices', 10 );

if ( ! function_exists('adiva_add_to_cart_message') ) {
	function adiva_add_to_cart_message() {
		if( ! (isset( $_REQUEST['product_id'] ) && (int) $_REQUEST['product_id'] > 0 ) )
			return;

		$titles 	= array();
		$product_id = (int) $_REQUEST['product_id'];

		if ( is_array( $product_id ) ) {
			foreach ( $product_id as $id ) {
				$titles[] = get_the_title( $id );
			}
		} else {
			$titles[] = get_the_title( $product_id );
		}

		$titles     = array_filter( $titles );
		$added_text = sprintf( _n( '<div class="text-inner"><b>%s</b> has been added to your cart.</div>', '%s have been added to your cart.', sizeof( $titles ), 'adiva' ), '"' . wc_format_list_of_items( $titles ) . '"' );
		$message    = sprintf( '%s <a href="%s" class="wc-forward db">%s</a>', wp_kses_post( $added_text ), esc_url( wc_get_page_permalink( 'cart' ) ), esc_html__( 'View Cart', 'adiva' ) );
		$data       =  array( 'message' => apply_filters( 'wc_add_to_cart_message', $message, $product_id ) );

		wp_send_json( $data );

		exit();
	}
	add_action( 'wp_ajax_add_to_cart_message', 'adiva_add_to_cart_message' );
	add_action( 'wp_ajax_nopriv_add_to_cart_message', 'adiva_add_to_cart_message' );
}

if ( !function_exists('adiva_product_get_sale_percent') ) {
	/*
	 *	Single product: Get sale percentage
	 */

	function adiva_product_get_sale_percent( $product ) {
		if ( $product->get_type() === 'variable' ) {
			// Get product variation prices (regular and sale)
			$product_variation_prices = $product->get_variation_prices();

			$highest_sale_percent = 0;

			foreach( $product_variation_prices['regular_price'] as $key => $regular_price ) {
				// Get sale price for current variation
				$sale_price = $product_variation_prices['sale_price'][$key];

				// Is product variation on sale?
				if ( $sale_price < $regular_price ) {
					$sale_percent = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );

					// Is current sale percent highest?
					if ( $sale_percent > $highest_sale_percent ) {
						$highest_sale_percent = $sale_percent;
					}
				}
			}

			// Return the highest product variation sale percent
			return $highest_sale_percent;
		} else {
			$regular_price = $product->get_regular_price();
			$sale_percent = 0;

			// Make sure the percentage value can be calculated
			if ( intval( $regular_price ) > 0 ) {
				$sale_percent = round( ( ( $regular_price - $product->get_sale_price() ) / $regular_price ) * 100 );
			}

			return $sale_percent;
		}
	}

}

if ( !function_exists('adiva_wc_quickview') ) {
	/**
	 * Customize product quick view.
	 */
	function adiva_wc_quickview() {
		// Get product from request.
		if ( isset( $_POST['product'] ) && (int) $_POST['product'] ) {
			global $post, $product, $woocommerce;

			$id      = ( int ) $_POST['product'];
			$post    = get_post( $id );
			$product = get_product( $id );

			if ( $product ) {
				// Get quickview template.
				include ADIVA_PATH . '/woocommerce/content-quickview-product.php';
			}
		}

		exit;
	}
	add_action( 'wp_ajax_adiva_quickview', 'adiva_wc_quickview' );
	add_action( 'wp_ajax_nopriv_adiva_quickview', 'adiva_wc_quickview' );
}

if ( ! function_exists('adiva_after_shop_loop_product') ) {
	function adiva_after_shop_loop_product() {
		echo '</div>';
	}
	add_action( 'woocommerce_after_shop_loop_item', 'adiva_after_shop_loop_product', 5 );
}

if ( ! function_exists('adiva_second_product_thumbnail') ) {
	function adiva_second_product_thumbnail() {
		$product_hover  = adiva_get_option('wc-product-image-hover', 'second-image');
		global $product, $woocommerce;

		if ( isset($product_hover) && $product_hover != 'second-image' ) return;

		$attachment_ids = $product->get_gallery_image_ids();

		if ( isset($product_hover) && $product_hover == 'second-image' ) {
			if ( count($attachment_ids) > 0 ) {
				$secondary_image_id = $attachment_ids['0'];
				echo wp_get_attachment_image( $secondary_image_id, 'shop_catalog', '', $attr = array( 'class' => 'hover-image attachment-shop-catalog' ) );
			}
		}
	}
	add_action('woocommerce_before_shop_loop_item_title', 'adiva_second_product_thumbnail', 15);
}

/**
 * Show the product title in the product loop.
 */
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
if ( ! function_exists( 'adiva_woocommerce_template_loop_product_title' ) ) {

	/**
	 * Show the product title in the product loop. By default this is an H2.
	 */
	function adiva_woocommerce_template_loop_product_title() {
		echo get_the_title();
	}
}

add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_link_open', 5 );
add_action( 'woocommerce_shop_loop_item_title', 'adiva_woocommerce_template_loop_product_title', 10 );
add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 20 );

add_action( 'adiva_woocommerce_breadcrumb', 'woocommerce_breadcrumb', 10 );

// Product Shop
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
add_action( 'adiva_woocommerce_result_count', 'woocommerce_result_count', 5 );

if ( ! function_exists( 'adiva_woocommerce_catalog_ordering' ) ) {
	function adiva_woocommerce_catalog_ordering() {
		global $wp_query;

		if ( 1 === (int) $wp_query->found_posts || ! woocommerce_products_will_display() ) {
			return;
		}

		$orderby                 = isset( $_GET['orderby'] ) ? wc_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
		$show_default_orderby    = 'menu_order' === apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
		$catalog_orderby_options = apply_filters( 'woocommerce_catalog_orderby', array(
			'menu_order' => esc_html__( 'Default', 'adiva' ),
			'popularity' => esc_html__( 'Popularity', 'adiva' ),
			'rating'     => esc_html__( 'Average rating', 'adiva' ),
			'date'       => esc_html__( 'Sort by newness', 'adiva' ),
			'price'      => esc_html__( 'Price low to high', 'adiva' ),
			'price-desc' => esc_html__( 'Price high to low', 'adiva' ),
		) );

		if ( ! $show_default_orderby ) {
			unset( $catalog_orderby_options['menu_order'] );
		}

		if ( 'no' === get_option( 'woocommerce_enable_review_rating' ) ) {
			unset( $catalog_orderby_options['rating'] );
		}

		wc_get_template( 'loop/orderby.php', array( 'catalog_orderby_options' => $catalog_orderby_options, 'orderby' => $orderby, 'show_default_orderby' => $show_default_orderby ) );
	}
}

remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

/**
 * ------------------------------------------------------------------------------------------------
 * My account remove logout link
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists( 'adiva_remove_my_account_logout' ) ) {
	function adiva_remove_my_account_logout( $items ) {
		unset( $items['customer-logout'] );
		return $items;
	}
	add_filter( 'woocommerce_account_menu_items', 'adiva_remove_my_account_logout', 10 );
}

// -- MY ACCOUNT
if ( ! function_exists( 'adiva_my_account' ) ) {
	function adiva_my_account() {
		?>
		<div id="header-account" class="btn-group box-hover compact-hidden">
			<a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>" class="dropdown-toggle"><i class="sl icon-user"></i></a>
		    <div class="dropdown-menu">
                <ul>
					<?php if ( !is_user_logged_in() ) : ?>
						<li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--customer-login">
				        	<a href="<?php echo get_permalink( wc_get_page_id( 'myaccount' ) ); ?>"><?php echo esc_html__( 'Login', 'adiva' ); ?></a>
				        </li>

						<li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--customer-register">
				        	<a href="<?php echo get_permalink( wc_get_page_id( 'myaccount' ) ); ?>"><?php echo esc_html__( 'Register', 'adiva' ); ?></a>
				        </li>

						<?php if ( class_exists( 'YITH_WCWL' ) ) : ?>
		    				<?php $wishlist_page_id = yith_wcwl_object_id( get_option( 'yith_wcwl_wishlist_page_id' ) ); ?>
		    				<li><a href="<?php echo YITH_WCWL()->get_wishlist_url(); ?>"><?php echo get_the_title( $wishlist_page_id ); ?></a></li>
		    			<?php endif; ?>
					<?php endif; ?>

					<?php if ( is_user_logged_in() ) : ?>
						<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
				            <li class="<?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
								<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>"><span><?php echo esc_html( $label ); ?></span></a>
							</li>
				        <?php endforeach; ?>

						<?php if ( class_exists( 'YITH_WCWL' ) ) : ?>
		    				<?php $wishlist_page_id = yith_wcwl_object_id( get_option( 'yith_wcwl_wishlist_page_id' ) ); ?>
		    				<li><a href="<?php echo YITH_WCWL()->get_wishlist_url(); ?>"><?php echo get_the_title( $wishlist_page_id ); ?></a></li>
		    			<?php endif; ?>

						<?php if ( class_exists( 'WeDevs_Dokan' ) ) : ?>
							<li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--dokan">
								<a href="<?php echo dokan_get_navigation_url(); ?>"><?php echo esc_html__( 'Vendor dashboard', 'adiva' ); ?></a>
							</li>
						<?php endif; ?>

						<li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--customer-logout">
				        	<a href="<?php echo wp_logout_url( get_permalink( wc_get_page_id( 'myaccount' ) ) ); ?>"><?php echo esc_html__( 'Logout', 'adiva' ); ?></a>
				        </li>
					<?php endif; ?>
			    </ul>
		    </div>
		</div>
        <?php
	}
}

/**
 * Remove product in wishlist.
 */

if ( ! function_exists('adiva_remove_product_wishlist') ) {
	function adiva_remove_product_wishlist() {
		if ( ! ( isset ( $_POST['product_id'] ) && isset( $_POST['_nonce'] ) && wp_verify_nonce( $_POST['_nonce'], 'bb_adiva' ) ) ) {
			wp_send_json ( array(
				'status' => 'false',
				'notice' => esc_html__( 'Not validate.', 'adiva' )
			));
		}

		$product_id = intval( $_POST['product_id'] );

		$user_id = get_current_user_id();

		if( $user_id ) {
			global $wpdb;
			$sql = "DELETE FROM {$wpdb->yith_wcwl_items} WHERE user_id = %d AND prod_id = %d";
			$sql_args = array(
				$user_id,
				$product_id
			);
			$wpdb->query( $wpdb->prepare( $sql, $sql_args ) );
		} else {
			$wishlist = yith_getcookie( 'yith_wcwl_products' );
			foreach( $wishlist as $key => $item ){
				if( $item['prod_id'] == $product_id ){
					unset( $wishlist[ $key ] );
				}
			}
			yith_setcookie( 'yith_wcwl_products', $wishlist );
		}
		$data = array(
			'status' => 'true',
		);

		wp_send_json( $data );

		die();
	}
	// Delete product in wishlish
	add_action( 'wp_ajax_adiva_remove_product_wishlist', 'adiva_remove_product_wishlist' );
	add_action( 'wp_ajax_nopriv_adiva_remove_product_wishlist', 'adiva_remove_product_wishlist' );
}

if ( !function_exists('adiva_add_title_to_wishlist') ) {
	/**
	 * Add product title to wishlist notice.
	 */
	function adiva_add_title_to_wishlist() {
		$product_id = isset( $_POST['add_to_wishlist'] ) ? intval( $_POST['add_to_wishlist'] ) : 0;

		if( ! $product_id ) return;

		$product_title = get_the_title( $product_id );

		return sprintf( __( '<b>%s</b> has been added to your Wishlist', 'adiva' ), $product_title );
	}
	add_filter( 'yith_wcwl_product_added_to_wishlist_message', 'adiva_add_title_to_wishlist' );
}

/**
 * Currency Dropdown
 */
if ( ! function_exists( 'adiva_currency' )  ) {
 	function adiva_currency() {
		if ( ! class_exists( 'Jms_Currency' ) ) return;
		$currencies = Jms_Currency::getCurrencies();

		if ( count( $currencies > 0 ) ) {
			$woocurrency = Jms_Currency::woo_currency();
			$woocode = $woocurrency['currency'];
			if ( ! isset( $currencies[$woocode] ) ) {
				$currencies[$woocode] = $woocurrency;
			}
			$default = Jms_Currency::woo_currency();
			$current = isset( $_COOKIE['jms_currency'] ) ? $_COOKIE['jms_currency'] : $default['currency'];

			?>

			<div class="btn-group compact-hidden box-hover">
				<a href="javascript:void(0)" class="dropdown-toggle">
					<span class="current"><?php echo esc_html( $current ) ?></span><i class="fa fa-angle-down"></i>
				</a>
				<div class="dropdown-menu currency-box">
					<ul>
						<?php foreach( $currencies as $code => $val ) : ?>
							<li>
								<a class="currency-item" href="javascript:void(0);" data-currency="<?php echo esc_attr( $code ); ?>"><?php echo esc_html( $code ); ?></a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>

			<?php

		}
 	}
}

/* 	Adiva Wishlist
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'adiva_wishlist' ) ){
	function adiva_wishlist(){
		if( function_exists( 'YITH_WCWL' ) ):
			$wishlist_url = YITH_WCWL()->get_wishlist_url(); ?>
			<div class="btn-group" id="header-wishlist">
	            <a href="<?php echo esc_url($wishlist_url);?>" class="dropdown-toggle">
	                <i class="sl icon-heart"></i>
					<span><?php echo YITH_WCWL()->count_products(); ?></span>
	            </a>
	        </div>
		<?php endif;
	}
}


/* 	Header cart
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'adiva_header_cart' ) ) {
	function adiva_header_cart(){
		global $woocommerce;
		$cart_style  = adiva_get_option('wc-add-to-cart-style', 'alert');

		if( isset($_GET['cart_design']) && $_GET['cart_design'] != '' ) {
			$cart_style = $_GET['cart_design'];
		}

    	?>
        <div class="btn-group box-hover <?php echo esc_attr($cart_style); ?>" id="header-cart">
            <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="dropdown-toggle cart-contents">
                <i class="sl icon-basket"></i>
                <samp class="cart-count pa"><?php echo esc_html($woocommerce->cart->cart_contents_count);?></samp>
            </a>
			<?php if ( isset($cart_style) && $cart_style != 'toggle-sidebar' ) : ?>
				<div class="widget_shopping_cart_content"></div>
			<?php endif; ?>
        </div>
	<?php
	}
}

/**
 * Ensure cart contents update when products are added to the cart via AJAX.
 */

if ( !function_exists('adiva_header_cart_fragment') ) {
	function adiva_header_cart_fragment( $fragments ) {
		global $woocommerce;

		ob_start();
		?>
	    <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="dropdown-toggle cart-contents" data-toggle="dropdown">
	        <i class="sl icon-basket"></i>
	        <samp class="cart-count pa"><?php echo esc_html($woocommerce->cart->cart_contents_count);?></samp>
	    </a>
		<?php
		$fragments['a.cart-contents'] = ob_get_clean();

		return $fragments;

	}
	add_filter('woocommerce_add_to_cart_fragments', 'adiva_header_cart_fragment');
}

/**
 * Load mini cart on header.
 */

if ( !function_exists('adiva_load_mini_cart') ) {
	function adiva_load_mini_cart() {
	 	$output = '';

	 	ob_start();
	 		$args['list_class'] = '';
	 		wc_get_template( 'cart/mini-cart.php' );

	 	$output = ob_get_clean();

	 	$result = array(
	 		'message'    => WC()->session->get( 'wc_notices' ),
	 		'cart_total' => WC()->cart->cart_contents_count,
	 		'cart_html'  => $output
	 	);
	 	echo json_encode( $result );
	 	exit;
	}
	add_action( 'wp_ajax_load_mini_cart', 'adiva_load_mini_cart' );
	add_action( 'wp_ajax_nopriv_load_mini_cart', 'adiva_load_mini_cart' );
}

if ( ! function_exists( 'adiva_shop_action_switch' ) ) {
	function adiva_shop_action_switch() {
		$product_view             = adiva_get_option( 'wc-product-view', 'grid' );
		$current_product_view     = adiva_get_shop_view();
		$current_per_row      	  = adiva_get_products_columns_per_row();
		$product_per_row_selector = adiva_get_option('wc-per-row-columns-selector', 1);

		if ( isset($product_per_row_selector) && $product_per_row_selector != 1 ) return;
		?>
		<div class="wc-switch flex middle-xs hidden-xs">
			<span class="mr_10"><?php echo esc_html__('View mode:', 'adiva'); ?></span>
			<a href="<?php echo add_query_arg( array( 'per_row' => 2, 'product_view' => 'grid' ), adiva_shop_page_link( true ) ); ?>" class="<?php echo ( 'grid' == $current_product_view && $current_per_row == 2 ) ? 'active ' : ''; ?>per-row-2">
				<i class="icon-view"></i>
			</a>
			<a href="<?php echo add_query_arg( array( 'per_row' => 3, 'product_view' => 'grid' ), adiva_shop_page_link( true ) ); ?>" class="<?php echo ( 'list' != $current_product_view && $current_per_row == 3 ) ? 'active ' : ''; ?>per-row-3">
				<i class="icon-view"></i>
			</a>
			<a href="<?php echo add_query_arg( array( 'per_row' => 4, 'product_view' => 'grid' ), adiva_shop_page_link( true ) ); ?>" class="<?php echo ( 'list' != $current_product_view && $current_per_row == 4 ) ? 'active ' : ''; ?>per-row-4">
				<i class="icon-view"></i>
			</a>
			<a href="<?php echo add_query_arg( array( 'product_view' => 'list' ), adiva_shop_page_link( true ) ); ?>" class="<?php echo ( 'list' == $current_product_view ) ? 'active ' : ''; ?>per-row-1">
				<i class="icon-view"></i>
			</a>
		</div>
		<?php
	}
}


if ( ! function_exists( 'adiva_woocommerce_shop_action' ) ) {
	function adiva_woocommerce_shop_action() {
		$shop_filter          = adiva_get_option( 'wc-shop-filter', 1 );
		$shop_ordering        = adiva_get_option( 'wc-shop-ordering', 1 );
		$products_per_page	  = adiva_get_option( 'wc-products-per-page', 1 );

		if ( isset($_GET['filter']) && $_GET['filter'] != '' ) {
			$shop_filter = $_GET['filter'];
		}

		?>
		<div class="shop-action">
			<div class="shop-action-inner flex between-xs">
				<?php adiva_shop_action_switch(); ?>
				<div class="action-right flex middle-xs">
					<?php if( $shop_ordering ) adiva_woocommerce_catalog_ordering(); ?>
					<?php if( $products_per_page ) adiva_product_show_pager(); ?>
					<?php if ( $shop_filter && is_active_sidebar( 'woocommerce-filter-sidebar') ) : ?>
						<div class="shop-filter-toggle flex">
							<span class="filter-text"><?php esc_html_e( 'Filter', 'adiva' ); ?></span>
							<span class="hamburger-box pr">
								<span class="hamburger-inner"></span>
							</span>
						</div>
					<?php endif; ?>
				</div>
			</div>

			<?php if ( $shop_filter && is_active_sidebar( 'woocommerce-filter-sidebar') ) : ?>
				<div class="shop-filter">
					<div class="filter-toggle-box clearfix">
					<?php dynamic_sidebar( 'woocommerce-filter-sidebar' ); ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'adiva_taxonomy_archive_description' ) ) {
	function adiva_taxonomy_archive_description() {
		if ( is_product_taxonomy() && 0 === absint( get_query_var( 'paged' ) ) ) {
			$description = wc_format_content( term_description() );
			if ( $description ) {
				echo '<div class="term-description">' . $description . '</div>';
			}
		}
	}
	add_action( 'woocommerce_archive_description', 'adiva_taxonomy_archive_description', 10 );
}
remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );


if ( ! function_exists('adiva_cart_remove_item') ) {
	function adiva_cart_remove_item() {
	    $item_key = $_POST['item_key'];

        $removed = WC()->cart->remove_cart_item( $item_key ); // Note: WP 2.3 >

        if ( $removed ) {
           $data['status'] = '1';
           $data['cart_count'] = WC()->cart->get_cart_contents_count();
           $data['cart_subtotal'] = WC()->cart->get_cart_subtotal();
        } else {
            $data['status'] = '0';
        }

        echo json_encode( $data );

        exit;
	}
	add_action( 'wp_ajax_cart_remove_item', 'adiva_cart_remove_item' );
	add_action( 'wp_ajax_nopriv_cart_remove_item', 'adiva_cart_remove_item' );
}

/* 	Product show per page
/* --------------------------------------------------------------------- */
if( ! function_exists( 'adiva_product_show_pager' ) ) {
	function adiva_product_show_pager() {
		$numbers = array(6, 8, 10, 12, 15, 16, 18, 20, 24, 27, 28, 30, 32, 33, 36, 40, 48, 60, 72 );

		$options   = array();
		$showproducts = get_query_var( 'posts_per_page' );
		if( ! $showproducts ) {
			$showproducts = adiva_get_option('products-show-per-page','12');
		}
		foreach ( $numbers as $number ):
			$options[] = sprintf(
				'<option value="%s" %s>%s %s</option>',
				esc_attr( $number ),
				selected( $number, $showproducts, false ),
				$number,'','');
		endforeach;
		?>
		<form class="show-products-number hidden-xs" method="get">
			<span><?php esc_html_e( 'Show:', 'adiva' ) ?></span>
			<select name="showproducts">
				<?php echo implode( '', $options ); ?>
			</select>
			<?php
			foreach( $_GET as $name => $value ) {
				if ( 'showproducts' != $name ) {
					printf( '<input type="hidden" name="%s" value="%s">', esc_attr( $name ), esc_attr( $value ) );
				}
			}
			?>
		</form>
		<?php
	}
}

/**
 * Change number of products to be displayed
 */

if ( !function_exists('adiva_change_product_per_page') ) {
	function adiva_change_product_per_page() {
		if ( isset( $_GET['showproducts'] ) ) {
			$number = absint( $_GET['showproducts'] );
		} else {
			$number = adiva_get_option( 'wc-number-per-page', '12' );
		}
		return $number;
	}
	add_filter( 'loop_shop_per_page', 'adiva_change_product_per_page' );
}

/**
 * Change product image thumbnail size.
 */

if ( !function_exists('adiva_change_image_thumbnail_size') ) {
	function adiva_change_image_thumbnail_size( $size ) {
		$shop_style = adiva_get_option( 'wc-archive-style', 'grid' );

		// DEMO
		if ( isset($_GET['layout']) && $_GET['layout'] != '' ) {
			$shop_style = $_GET['layout'];
		}

		// Get image size
		$shop_catalog = wc_get_image_size( 'shop_catalog' );

		// Get product options
		$options = get_post_meta( get_the_ID(), '_custom_wc_thumb_options', true );

		if ( isset( $options['wc-thumbnail-size'] ) && $options['wc-thumbnail-size'] && $shop_style == 'metro' ) {
			add_image_size( 'adiva_shop_metro', $shop_catalog['width'] * 2, $shop_catalog['height'] * 2, true );
			$size = 'adiva_shop_metro';
		} else {
			$size = 'shop_catalog';
		}
		return $size;
	}
	add_filter( 'single_product_archive_thumbnail_size', 'adiva_change_image_thumbnail_size' );
}

/**
 * ------------------------------------------------------------------------------------------------
 * Product deal countdown
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists( 'adiva_product_countdown_timer' ) ) {
	function adiva_product_countdown_timer() {
		global $post;
        $sale_date = get_post_meta( $post->ID, '_sale_price_dates_to', true );

		if( ! $sale_date ) return;

        $timezone = 'GMT';

        if ( apply_filters( 'adiva_wp_timezone', false ) ) $timezone = wc_timezone_string();

		echo '<div class="adiva-product-countdown adiva-countdown" data-end-date="' . esc_attr( date( 'Y-m-d H:i:s', $sale_date ) ) . '" data-timezone="' . $timezone . '"></div>';
	}
	add_action( 'woocommerce_single_product_summary', 'adiva_product_countdown_timer', 26 );
}

/**
 * Add extra link after single cart.
 */

if ( !function_exists('adiva_add_extra_link_after_cart') ) {
	function adiva_add_extra_link_after_cart() {
		$shipping   = adiva_get_option('wc-single-shipping-return', '');
		if ( ! empty( $shipping ) ) {
			echo '<div class="extra-link mt_30 mb_30">';
					echo '<a data-type="shipping-return" class="adiva-wc-help" href="#">' . esc_html__( 'Delivery & Return', 'adiva' ) . '</a>';
			echo '</div>';
		}
	}
	add_action( 'woocommerce_single_product_summary', 'adiva_add_extra_link_after_cart', 35 );
}

if ( !function_exists('adiva_shipping_return') ) {
	/**
	 * Customize shipping & return content.
	 */

	function adiva_shipping_return() {
		// Get help content
		$shipping   = adiva_get_option('wc-single-shipping-return', '');
		if ( ! $shipping ) return;

		$output = '<div class="wc-shipping-return pr">' . $shipping . '</div>';

		echo wp_kses_post( $output );
		exit;
	}
	add_action( 'wp_ajax_adiva_shipping_return', 'adiva_shipping_return' );
	add_action( 'wp_ajax_nopriv_adiva_shipping_return', 'adiva_shipping_return' );
}

if ( !function_exists('adiva_product_categories') ) {
	function adiva_product_categories() {
		global $product;
		$show_category_name  = adiva_get_option('wc-category-name', 1);

		if ( $show_category_name ) {
			echo wc_get_product_category_list( $product->get_id(), ', ', '<div class="product-cat"> ', '</div>' );
		}
	}
}

if ( !function_exists('adiva_product_quickview') ) {
	function adiva_product_quickview() {
		global $post;

		$show_quickview = adiva_get_option('wc-quick-view', 1);

		if ( $show_quickview ) : ?>
			<li class="quickview hidden-xs"><a href="javascript:void(0)" class="button btn-quickview" data-product="<?php echo esc_attr( $post->ID ); ?>"><span class="tooltip"><?php echo esc_html__('Quick View', 'adiva'); ?></span></a></li>
		<?php endif;
	}
}

if ( !function_exists('adiva_product_wishlist') ) {
	function adiva_product_wishlist() {
		$show_wishlist = adiva_get_option('wc-wishlist', 1);

		if ( $show_wishlist && class_exists( 'YITH_WCWL' ) ) {
			echo '<li class="btn-wishlist">' . do_shortcode( '[yith_wcwl_add_to_wishlist]' ) . '</li>';
		}
	}
}

if ( !function_exists('adiva_product_rating') ) {
	function adiva_product_rating() {
		$show_rating = adiva_get_option('wc-rating', 0);

		if ( isset($_GET['show-rating']) && $_GET['show-rating'] != '' ) {
			$show_rating = $_GET['show-rating'];
		}

		if ( $show_rating ) {
			woocommerce_template_loop_rating();
		}
	}
}

if( ! function_exists( 'adiva_shop_page_link' ) ) {
	/**
	 * ------------------------------------------------------------------------------------------------
	 * Get base shop page link
	 * ------------------------------------------------------------------------------------------------
	 */
	function adiva_shop_page_link( $keep_query = false, $taxonomy = '' ) {
		// Base Link decided by current page
		if ( defined( 'SHOP_IS_ON_FRONT' ) ) {
			$link = home_url();
		} elseif ( is_post_type_archive( 'product' ) || is_page( wc_get_page_id('shop') ) ) {
			$link = get_post_type_archive_link( 'product' );
		} elseif( is_product_category() ) {
			$link = get_term_link( get_query_var('product_cat'), 'product_cat' );
		} elseif( is_product_tag() ) {
			$link = get_term_link( get_query_var('product_tag'), 'product_tag' );
		} else {
			$link = get_term_link( get_query_var('term'), get_query_var('taxonomy') );
		}

		return $link;
	}
}


if( ! function_exists( 'adiva_get_shop_view' ) ) {
	function adiva_get_shop_view() {
		if( ! class_exists('WC_Session_Handler') ) return;
		$s = WC()->session;
		if ( is_null( $s ) ) return adiva_get_option('wc-product-view', 'grid');

		if ( isset( $_REQUEST['product_view'] ) ) {
			return $_REQUEST['product_view'];
		}elseif ( $s->__isset( 'product_view' ) ) {
			return $s->__get( 'product_view' );
		} else {
			$product_view = adiva_get_option('wc-product-view', 'grid');
			if ( $product_view == 'grid' ) {
				return 'grid';
			} elseif( $product_view == 'list'){
				return 'list';
			}
		}
	}
}

if( ! function_exists( 'adiva_shop_view_action' ) ) {
	function adiva_shop_view_action() {
		if( ! class_exists('WC_Session_Handler')) return;
		$s = WC()->session;
		if ( is_null( $s ) ) return;

		if ( isset( $_REQUEST['product_view'] ) ) {
			$s->set( 'product_view', $_REQUEST['product_view'] );
		}
		if ( isset( $_REQUEST['per_row'] ) ) {
			$s->set( 'shop_per_row', $_REQUEST['per_row'] );
		}
	}
}

if( ! function_exists( 'adiva_get_products_columns_per_row' ) ) {
	// -------------------------------------------
	// ! adiva_get_products_columns_per_row
	// -------------------------------------------

	function adiva_get_products_columns_per_row() {
		if( ! class_exists('WC_Session_Handler') ) return;

		$s = WC()->session;
		if ( is_null( $s ) ) return intval( adiva_get_option('wc-product-column', 4) );

		if ( isset( $_REQUEST['per_row'] ) ) {
			return intval( $_REQUEST['per_row'] );
		} elseif ( $s->__isset( 'shop_per_row' ) ) {
			return intval( $s->__get( 'shop_per_row' ) );
		} else {
			return intval( adiva_get_option('wc-product-column', 4) );
		}
	}
}

if ( ! function_exists( 'adiva_is_woo_ajax' ) ) {
	/**
	 * ------------------------------------------------------------------------------------------------
	 * is ajax request
	 * ------------------------------------------------------------------------------------------------
	 */
	function adiva_is_woo_ajax() {
		$request_headers = getallheaders();

		if( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return 'wp-ajax';
		}
		if( isset( $request_headers['x-pjax'] ) || isset( $request_headers['X-PJAX'] ) || isset( $request_headers['X-Pjax'] ) ) {
			return 'full-page';
		}
		if( isset( $_REQUEST['woo_ajax'] ) ) {
			return 'fragments';
		}
		if( isset( $_REQUEST['_pjax'] ) ) {
			return 'full-page';
		}

		if( isset( $_REQUEST['_pjax'] ) ) {
			return true;
		}
		return false;
	}
}

if( ! function_exists( 'adiva_my_account_wrapper_start' ) ) {
	/**
	 * ------------------
	 * My account wrapper
	 * ------------------
	 */
	function adiva_my_account_wrapper_start(){
		echo '<div class="woocommerce-my-account-wrapper">';
	}
	add_action( 'woocommerce_account_navigation', 'adiva_my_account_wrapper_start', 1 );
}

if( ! function_exists( 'adiva_my_account_wrapper_end' ) ) {
	function adiva_my_account_wrapper_end(){
		echo '</div><!-- .woocommerce-my-account-wrapper -->';
	}
	add_action( 'woocommerce_account_content', 'adiva_my_account_wrapper_end', 10000 );
}

if ( !function_exists('adiva_catalog_mode_init') ) {
	function adiva_catalog_mode_init() {
		/**
		 * WooCommerce catalog mode functions
		 */
		$catalog_mode = adiva_get_option( 'catalog-mode', 0 );

		if ( isset($_GET['catalog-mode']) && $_GET['catalog-mode'] == 1 ) {
	        $catalog_mode = 1;
	    }

		if( ! $catalog_mode  ) return false;

		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
	}
	add_action( 'wp', 'adiva_catalog_mode_init' );
}

if ( !function_exists('adiva_pages_redirect') ) {
	function adiva_pages_redirect() {
		$catalog_mode = adiva_get_option( 'catalog-mode', 0 );

		if( ! $catalog_mode  ) return false;

		$cart     = is_page( wc_get_page_id( 'cart' ) );
		$checkout = is_page( wc_get_page_id( 'checkout' ) );

		wp_reset_postdata();

		if ( $cart || $checkout ) {
			wp_redirect( home_url() );
			exit;
		}
	}
	add_action( 'wp', 'adiva_pages_redirect' );
}

if ( !function_exists('adiva_related_product_carousel_js') ) {
	function adiva_related_product_carousel_js() {
		ob_start();
		?>
		jQuery(document).ready(function($) {
			var owl_product = $('.product-related-carousel');
			var rtl = false;
			if ($('body').hasClass('rtl')) rtl = true;

			owl_product.owlCarousel({
				responsive : {
					320 : {
						items: 2,
						margin: 10
					},
					445 : {
						items: 2,
						margin: 10
					},
					620 : {
						items: 3,
						margin: 10
					},
					768 : {
						items: 3,
						margin: 20
					},
					991 : {
						items: 4,
						margin: 30
					},
					1199 : {
						items: 4,
					}
				},
				lazyLoad : true,
				rtl: rtl,
				margin: 40,
				dots: false,
				nav: true,
				autoplay: false,
				loop: false,
				autoplayTimeout: 5000,
				smartSpeed: 1000,
				navText: ['<i class="icon-arrow prev"></i>','<i class="icon-arrow next"></i>']
			});
		});
		<?php
		return ob_get_clean();
	}
}

if ( !function_exists('adiva_cross_sell_product_carousel_js') ) {
	function adiva_cross_sell_product_carousel_js() {
		ob_start();
		?>
		jQuery(document).ready(function($) {
			var owl_product = $('.cross-sell-carousel');
			var rtl = false;
			if ($('body').hasClass('rtl')) rtl = true;

			owl_product.owlCarousel({
				responsive : {
					320 : {
						items: 2,
						margin: 10
					},
					445 : {
						items: 2,
						margin: 10
					},
					620 : {
						items: 3,
						margin: 10
					},
					768 : {
						items: 3,
						margin: 20
					},
					991 : {
						items: 4,
						margin: 30
					},
					1199 : {
						items: 4,
					}
				},
				lazyLoad : true,
				rtl: rtl,
				margin: 40,
				dots: false,
				nav: true,
				autoplay: false,
				loop: false,
				autoplayTimeout: 5000,
				smartSpeed: 1000,
				navText: ['<i class="icon-arrow prev"></i>','<i class="icon-arrow next"></i>']
			});
		});
		<?php
		return ob_get_clean();
	}
}

if ( !function_exists('adiva_upsell_product_carousel_js') ) {
	function adiva_upsell_product_carousel_js() {
		ob_start();
		?>
		jQuery(document).ready(function($) {
			var owl_product = $('.upsell-product-carousel');
			var rtl = false;
			if ($('body').hasClass('rtl')) rtl = true;

			owl_product.owlCarousel({
				responsive : {
					320 : {
						items: 2,
						margin: 10
					},
					445 : {
						items: 2,
						margin: 10
					},
					620 : {
						items: 3,
						margin: 10
					},
					768 : {
						items: 3,
						margin: 20
					},
					991 : {
						items: 4,
						margin: 30
					},
					1199 : {
						items: 4,
					}
				},
				lazyLoad : true,
				rtl: rtl,
				margin: 40,
				dots: false,
				nav: true,
				autoplay: false,
				loop: false,
				autoplayTimeout: 5000,
				smartSpeed: 1000,
				navText: ['<i class="icon-arrow prev"></i>','<i class="icon-arrow next"></i>']
			});
		});
		<?php
		return ob_get_clean();
	}
}


remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );

add_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_open', 5);
add_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 4);
add_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 15);
add_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 20);

/*
 * Add to cart ajax
 */
if( ! function_exists( 'adiva_ajax_add_to_cart' ) ) {
	function adiva_ajax_add_to_cart() {

		// Get messages
		ob_start();

		wc_print_notices();

		$notices = ob_get_clean();


		// Get mini cart
		ob_start();

		woocommerce_mini_cart();

		$mini_cart = ob_get_clean();

		// Fragments and mini cart are returned
		$data = array(
			'notices' => $notices,
			'fragments' => apply_filters( 'woocommerce_add_to_cart_fragments', array(
					'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>'
				)
			),
			'cart_hash' => apply_filters( 'woocommerce_add_to_cart_hash', WC()->cart->get_cart_for_session() ? md5( json_encode( WC()->cart->get_cart_for_session() ) ) : '', WC()->cart->get_cart_for_session() )
		);

		wp_send_json( $data );

		die();
	}
	add_action( 'wp_ajax_adiva_ajax_add_to_cart', 'adiva_ajax_add_to_cart' );
	add_action( 'wp_ajax_nopriv_adiva_ajax_add_to_cart', 'adiva_ajax_add_to_cart' );
}
