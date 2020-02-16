<?php

function denso_output_favourite_button( $vendor_id ) {
  	if ( !is_user_logged_in() ) {
  		// not login
		$class = '';
		$title = esc_html__('Add this shop to your Favourites', 'denso');
		$msg = esc_html__('Add to Favourites', 'denso');

	} else {
	    $user_id = get_current_user_id();
	    $already_favourited_vendors = get_user_option( "_favourited_vendors", $user_id );
		$favourited_vendors = array();

		if ( is_array($already_favourited_vendors) && count($already_favourited_vendors) != 0 ) {
			$favourited_vendors = $already_favourited_vendors;
		}

	    if ( !in_array( $vendor_id, $favourited_vendors ) ) {
	    	// not add
	      	$class = '';
	      	$title = esc_html__('Add this shop to your Favourites', 'denso');
			$msg = esc_html__('Add to Favourites', 'denso');
	    } else {
	    	// added
	      	$class = ' added';
	      	$title = esc_html__('Remove this shop from your Favourites', 'denso');
			$msg = esc_html__('Favourite Shop', 'denso');
	    }

	}
	?>
	<div class="favourite-wrapper">
		<a href="#favourite-vendor" class="btn denso-favourite-vendor <?php echo esc_attr($class);?>" data-vendor_id="<?php echo esc_attr($vendor_id); ?>" title="<?php echo esc_attr($title); ?>">
			<i class="post-icon-like mn-icon-1246"></i>
			<span><?php echo esc_attr($msg); ?></span>
		</a>
	</div>
	<?php
}

add_action( 'wp_ajax_nopriv_denso-favourite-vendor', 'denso_favourite_vendor' );
add_action( 'wp_ajax_denso-favourite-vendor', 'denso_favourite_vendor' );

function denso_favourite_vendor() {
	$nonce = $_POST['nonce'];
  	if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) )
        die ( 'None!' );

	$vendor_id = $_POST['vendor_id'];

	if ( !is_user_logged_in() ) {
		// not login
		ob_start();
		$message = sprintf(__('Only registered users can add vendor shop to Favourite List. Please <a rel="nofollow" href="%s">log in or register here</a>.', 'denso'), esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ));
		wc_print_notice( $message, 'error' );
		$output = ob_get_contents();
		ob_end_clean();
		$output = str_replace("woocommerce-error", "woocommerce-error vendor-notice", $output);
		echo json_encode(array( 'status' => 'no-access', 'msg' => $output ));

	} else {
		$user_id = get_current_user_id();
		$already_favourited_vendors = get_user_option( "_favourited_vendors", $user_id );
		$favourited_vendors = array();

		if ( is_array($already_favourited_vendors) && count($already_favourited_vendors) != 0 ) {
			$favourited_vendors = $already_favourited_vendors;
		}

		if ( !in_array( $vendor_id, $favourited_vendors ) ) {
			// add vendor
			$favourited_vendors[] = $vendor_id;
			update_user_option( $user_id, "_favourited_vendors", $favourited_vendors );
			echo json_encode(array( 'status' => 'success', 'msg' => esc_html__('Favourite Shop', 'denso'), 'title' => esc_html__('Remove this shop from your Favourites', 'denso') ));
		} else {
			// remove vendor
			if ( ($key = array_search($vendor_id, $favourited_vendors)) !== false ) {
    			unset($favourited_vendors[$key]);
			}
			$favourited_vendors = array_values($favourited_vendors);
			update_user_option( $user_id, "_favourited_vendors", $favourited_vendors );
			echo json_encode(array( 'status' => 'success-remove', 'msg' => esc_html__('Add to Favourites', 'denso'), 'title' => esc_html__('Add this shop to your Favourites', 'denso') ));
		}
	}

	exit;
}

/**
 *  Output favourite shops on my account page
 */
function denso_my_account_favourite_list() {
	get_template_part( 'wc-vendors/store/favourite-vendor' );
}
add_action('woocommerce_after_my_account', 'denso_my_account_favourite_list');
