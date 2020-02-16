<?php

if ( is_user_logged_in() ) {
	$user_id = get_current_user_id();
	$already_favourited_vendors = get_user_option( "_favourited_vendors", $user_id );
	if ( is_array($already_favourited_vendors) && count($already_favourited_vendors) > 0 ) {
		?>
		<div class="vendor-favourite-list">
			<h3><?php esc_html_e('Favourite Vendors:', 'denso'); ?></h3>
			<div class="row">
			<?php
			foreach ($already_favourited_vendors as $vendor_id) {
				$store_name = get_user_meta( $vendor_id, 'pv_shop_name', true );

				// Store logo
				$store_icon = '';
				if ( class_exists('WCVendors_Pro') ) {
					$store_icon_src = wp_get_attachment_image_src( get_user_meta( $vendor_id, '_wcv_store_icon_id', true ), 'denso-vendor-main-logo' );
					if ( is_array( $store_icon_src ) ) {
						$store_icon = '<img src="'. esc_url($store_icon_src[0]) .'" alt="" class="store-icon" />';
					}
					$store_url = WCVendors_Pro_Vendor_Controller::get_vendor_store_url( $vendor_id );
					// Get all vendor products
					$vendor_products_ids = WCVendors_Pro_Vendor_Controller::get_products_by_id( $vendor_id );
					$products_count = count($vendor_products_ids);
				} else {
					$logo_url = get_user_meta( $vendor_id, '_logo_image', true );
					if ( $logo_url ) {
						$store_icon = '<img src="'. esc_url($logo_url) .'" alt="" class="store-icon" />';
					}
					$store_url = WCV_Vendors::get_vendor_shop_page($vendor_id);
					$products = new WP_Query( array('author' => $vendor_id, 'post_type'	=> 'product') );
					$products_count = $products->post_count;
				}

				// Output all info
				?>
				<div class="col-xs-12 col-sm-3">
					<div class="single-vendor">
						<?php if ($store_icon != '') { ?>
							<div class="store-brand"><a href="<?php echo esc_url($store_url); ?>"><?php echo trim($store_icon); ?></a></div>
						<?php } ?>
						<?php if ($store_name != '') { ?>
							<h3 class="shop-name"><a href="<?php echo esc_url($store_url); ?>"><?php echo trim($store_name); ?></a></h3>
						<?php } ?>
						<div class="meta-container">
							<?php if ( class_exists('WCVendors_Pro') ) { ?>
								<?php if ( ! WCVendors_Pro::get_option( 'ratings_management_cap' ) ) { ?>
									<span class="rating-container">
									<?php echo WCVendors_Pro_Ratings_Controller::ratings_link( $vendor_id, true ); ?>
									</span>
								<?php } ?>
							<?php } ?>
							<div class="counters-wrapper">
								<?php if ($products_count && $products_count>0) { ?>
									<span class="products-count"><i class="mn-icon-920"></i>
									<?php esc_html_e( 'Products: ', 'denso' ); echo trim($products_count); ?></span>
								<?php } ?>
							</div>
							<a href="#" class="denso-favourite-vendor remove-vendor" data-vendor_id="<?php echo esc_attr($vendor_id); ?>">
							<i class="mn-icon-4" aria-hidden="true"></i> <?php esc_html_e('Remove from Favourites', 'denso'); ?></a>
						</div>
					</div>
				</div>

				<?php
			}
			unset($vendor_id);
			?>
			</div>
		</div>
		<?php
	}
}