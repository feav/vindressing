<?php

if ( !class_exists("Denso_Woo_Custom") ) {
	class Denso_Woo_Custom {

		public static function init() {
			// accessory
			if ( denso_get_config('show_product_accessory', true) ) {
				add_action( 'wp_ajax_nopriv_denso_variable_add_to_cart', array( __CLASS__, 'add_to_cart' ) );
				add_action( 'wp_ajax_denso_variable_add_to_cart', array( __CLASS__, 'add_to_cart' ) );

				add_action( 'wp_ajax_nopriv_denso_get_total_price', array( __CLASS__, 'display_total_price' ) );
				add_action( 'wp_ajax_denso_get_total_price', array( __CLASS__, 'display_total_price' ) );

				// Add
				add_action( 'woocommerce_product_write_panel_tabs', array( __CLASS__, 'add_accessories_field_tab' ) );
				add_action( 'woocommerce_product_data_panels', array( __CLASS__, 'add_accessories_add_fields' ) );

				// Save
				add_action( 'woocommerce_process_product_meta_simple', array( __CLASS__, 'save_accessories' ) );
				add_action( 'woocommerce_process_product_meta_variable', array( __CLASS__, 'save_accessories' ) );
				add_action( 'woocommerce_process_product_meta_grouped', array( __CLASS__, 'save_accessories' ) );
				add_action( 'woocommerce_process_product_meta_external', array( __CLASS__, 'save_accessories' ) );
			}
			// recommend
			add_action( 'woocommerce_product_options_general_product_data',	array( __CLASS__, 'recommend_product_options' ) );
			// save
			add_action( 'woocommerce_process_product_meta_simple', array( __CLASS__, 'save_options' ) );
			add_action( 'woocommerce_process_product_meta_variable', array( __CLASS__, 'save_options' ) );
			add_action( 'woocommerce_process_product_meta_grouped', array( __CLASS__, 'save_options' ) );
			add_action( 'woocommerce_process_product_meta_external', array( __CLASS__, 'save_options' ) );

			// brand
			$tax = denso_get_config( 'product_brand_attribute' );
			if ( !empty($tax) ) {
				add_filter( "manage_edit-{$tax}_columns", array( __CLASS__, 'brand_columns' ) );
				add_filter( "manage_{$tax}_custom_column", array( __CLASS__, 'brand_column' ), 10, 3 );
				add_action( "{$tax}_add_form_fields", array( __CLASS__, 'add_brand' ) );
				add_action( "{$tax}_edit_form_fields", array( __CLASS__, 'edit_brand' ) );
				add_action( 'create_term', array( __CLASS__, 'save_brand_image' )  );
				add_action( 'edit_term', array( __CLASS__, 'save_brand_image' ) );
			}
		}

		public static function add_accessories_field_tab() {
			?>
			<li class="advanced_options show_if_simple show_if_variable">
				<a href="#apus_product_accessories"><?php echo esc_html__( 'Accessories', 'denso' ); ?></a>
			</li>
			<?php
		}

		public static function add_accessories_add_fields() {
			global $post;
			$json_ids = self::get_products_by_ids();
			?>
			<div id="apus_product_accessories" class="panel woocommerce_options_panel">
				<div class="options_group">
					<p class="form-field">
						<label for="product_accessory_ids"><?php esc_html_e( 'Accessories', 'denso' ); ?></label>
						<select id="product_accessory_ids" class="wc-product-search" multiple="multiple" style="width: 50%;" name="product_accessory_ids[]" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'woocommerce' ); ?>" data-action="woocommerce_json_search_products_and_variations" data-exclude="<?php echo intval( $post->ID ); ?>">
							<?php
								foreach ( $json_ids as $product_id => $product_name) {
									echo '<option value="' . esc_attr( $product_id ) . '"' . selected( true, true, false ) . '>' . wp_kses_post( $product_name ) . '</option>';
								}
							?>
						</select>

					</p>
				</div>
			</div>
			<?php
		}

		public static function save_accessories( $post_id ) {
			$accessories = isset( $_POST['product_accessory_ids'] ) ? $_POST['product_accessory_ids'] : array();
			$accessories = array_filter( array_map( 'intval', $accessories ) );
			update_post_meta( $post_id, '_product_accessory_ids', $accessories );
		}

		public static function get_accessories( $product_id ) {
			$product_accessory_ids = get_post_meta( $product_id, '_product_accessory_ids', true );
			if (!empty($product_accessory_ids)) {
				return (array)maybe_unserialize( $product_accessory_ids );
			} else {
				return array();
			}
		}

		public static function get_products_by_ids() {
			global $post;
			$product_ids = self::get_accessories($post->ID);
			$json_ids = array();
			foreach ( $product_ids as $product_id ) {
				$product = wc_get_product( $product_id );
				if ( is_object( $product ) ) {
					$json_ids[ $product_id ] = wp_kses_post(html_entity_decode($product->get_formatted_name(), ENT_QUOTES, get_bloginfo( 'charset' )));
				}
			}
			return $json_ids;
		}

		public static function add_to_cart() {
			$product_id = absint( $_POST['product_id'] );
			$quantity = empty( $_POST['quantity'] ) ? 1 : wc_stock_amount( $_POST['quantity'] );
			$variation_id = empty( $_POST['variation_id'] ) ? 0 : $_POST['variation_id'];
			$variation = empty( $_POST['variation'] ) ? 0 : $_POST['variation'];
			$product_status = get_post_status( $product_id );
			
			if ( WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation ) && 'publish' === $product_status ) {
				do_action( 'woocommerce_ajax_added_to_cart', $product_id );

				if ( get_option( 'woocommerce_cart_redirect_after_add' ) == 'yes' ) {
					wc_add_to_cart_message( $product_id );
				}
				WC_AJAX::get_refreshed_fragments();

			} else {
				$data = array(
					'error' => true,
					'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id )
				);
				wp_send_json( $data );
			}
			die();
		}

		public static function display_total_price() {
			$price = empty( $_POST['data'] ) ? 0 : $_POST['data'];
			if ( $price ) {
				$price_html = wc_price( $price );
				echo wp_kses_post( $price_html );
			}
			die();
		}

		public static function recommend_product_options() {
			echo '<div class="options_group">';
				woocommerce_wp_checkbox(
					array(
						'id' => '_apus_recommended',
						'label' => esc_html__( 'Recommended this product', 'denso' ),
					)
				);
			echo '</div>';
		}

		public static function save_options($post_id) {
			$_apus_recommended = isset( $_POST['_apus_recommended'] ) ? wc_clean( $_POST['_apus_recommended'] ) : '' ;
			update_post_meta( $post_id, '_apus_recommended', $_apus_recommended );
		}

		public static function add_brand() {
			?>
			<div class="form-field">
				<label><?php esc_html_e( 'Thumbnail', 'denso' ); ?></label>
				<?php self::brand_image_field(); ?>
			</div>
			<?php
		}

		public static function edit_brand( $term ) {
			$image = get_woocommerce_term_meta( $term->term_id, 'product_brand_image', true );
			?>
			<tr class="form-field">
				<th scope="row" valign="top"><label><?php esc_html_e( 'Thumbnail', 'denso' ); ?></label></th>
				<td>
					<?php self::brand_image_field($image); ?>
				</td>
			</tr>
			<?php
		}

		public static function brand_image_field( $image = '' ) {
			?>
			<div class="screenshot">
				<?php if ( $image ) { ?>
	                <img src="<?php echo esc_url($image); ?>" alt=""/>
	            <?php } ?>
			</div>
			<input type="hidden" id="product_brand_image" name="product_brand_image" value="<?php echo esc_attr( $image ); ?>" class="upload_image" />
			<div class="upload_image_action">
	            <input type="button" class="button add-image" value="<?php esc_html_e( 'Add', 'denso' ); ?>">
	            <input type="button" class="button remove-image" value="<?php esc_html_e( 'Remove', 'denso' ); ?>">
	        </div>
			<?php
		}

		public static function save_brand_image( $term_id ) {
			if ( isset($_POST['product_brand_image']) ) {
				update_woocommerce_term_meta( $term_id, 'product_brand_image', $_POST['product_brand_image'] );
			}
			delete_transient( 'wc_term_counts' );
		}

		public static function brand_columns( $columns ) {
			$new_columns = array();
			foreach ($columns as $key => $value) {
				if ( $key == 'name' ) {
					$new_columns['image'] = esc_html__( 'Image', 'denso' );
				}
				$new_columns[$key] = $value;
			}
			return $new_columns;
		}

		public static function brand_column( $columns, $column, $id ) {
			if ( $column == 'image' ) {
				$image = get_woocommerce_term_meta( $id, 'product_brand_image', true );
				$columns .= '<img style="max-width: 60px;" src="' . esc_url( $image ) . '" alt="'.esc_html__( 'Image', 'denso' ).'" class="wp-post-image" />';
			}

			return $columns;
		}

		public static function get_product_brands() {
		    global $product;
		    $brands_tax = denso_get_config( 'product_brand_attribute' );
		    $terms = get_the_terms( $product->get_id(), $brands_tax );
		    $brand_html = '';

		    if ( $terms && ! is_wp_error( $terms ) ) {
		    	$i = 0;
		        foreach ( $terms as $term ) {
		            $brand_html  .= '<a href="' . esc_url( get_term_link( $term ) ). '">' . esc_attr( $term->name ) . '</a>'.($i != count($terms - 1) ? ', ' : '');
		            $i++;
		        }
		    }
		    if ( ! empty( $brand_html ) ) { ?>
		        <div class="product-brand">
		            <?php echo wp_kses_post( $brand_html ); ?>
		        </div>
		    <?php }
		}

		public static function get_brands($number = 8) {
			$brands_tax = denso_get_config( 'product_brand_attribute' );
			$terms = array();
			if ( $brands_tax ) {
				$terms = get_terms( array(
				    'taxonomy' => $brands_tax,
				    'hide_empty' => true,
				    'number' => $number
				) );
			}
			return $terms;
		}
	}
	add_action( 'init', array('Denso_Woo_Custom', 'init') );
}