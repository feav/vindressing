<?php 
/**
 * The Template for displaying a store header
 *
 * Override this template by copying it to yourtheme/wc-vendors/store
 *
 * @package    WCVendors_Pro
 * @version    1.3.5
 */
global $post;

$store_icon_src 	= wp_get_attachment_image_src( get_user_meta( $vendor_id, '_wcv_store_icon_id', true ), array( 160, 160 ) ); 
$store_icon 		= ''; 
$store_banner_src 	= wp_get_attachment_image_src( get_user_meta( $vendor_id, '_wcv_store_banner_id', true ), 'full'); 
$store_banner 		= ''; 

// see if the array is valid
if ( is_array( $store_icon_src ) ) { 
	$store_icon 	= '<img src="'. $store_icon_src[0].'" alt="" class="store-icon" />'; 
} 

if ( is_array( $store_banner_src ) ) { 
	$store_banner	= '<img src="'. $store_banner_src[0].'" alt="" class="store-banner" />'; 
} else { 
	//  Getting default banner 
	$default_banner_src = WCVendors_Pro::get_option( 'default_store_banner_src' ); 
	$store_banner	= '<img src="'. $default_banner_src.'" alt="" class="wcv-store-banner" style="max-height: 200px;"/>'; 
}

// Verified vendor 
$verified_vendor 			= ( array_key_exists( '_wcv_verified_vendor', $vendor_meta ) ) ? $vendor_meta[ '_wcv_verified_vendor' ] : false; 
$verified_vendor_label 		= WCVendors_Pro::get_option( 'verified_vendor_label' ); 
// $verified_vendor_icon_src 	= WCVendors_Pro::get_option( 'verified_vendor_icon_src' ); 

// Store title 
$store_title 		=  ( is_product() ) ? '<a href="'. WCV_Vendors::get_vendor_shop_page( $post->post_author ).'">'. $vendor_meta['pv_shop_name'] . '</a>' : $vendor_meta['pv_shop_name']; 

// Get store details including social, adddresses and phone number 
$twitter_username 	= get_user_meta( $vendor_id , '_wcv_twitter_username', true ); 
$instagram_username = get_user_meta( $vendor_id , '_wcv_instagram_username', true ); 
$facebook_url 		= get_user_meta( $vendor_id , '_wcv_facebook_url', true ); 
$linkedin_url 		= get_user_meta( $vendor_id , '_wcv_linkedin_url', true ); 
$youtube_url 		= get_user_meta( $vendor_id , '_wcv_youtube_url', true ); 
$googleplus_url 	= get_user_meta( $vendor_id , '_wcv_googleplus_url', true ); 
$pinterest_url 		= get_user_meta( $vendor_id , '_wcv_pinterest_url', true ); 
$snapchat_username 	= get_user_meta( $vendor_id , '_wcv_snapchat_username', true ); 

// Migrate to store address array 
$address1 			= ( array_key_exists( '_wcv_store_address1', $vendor_meta ) ) ? $vendor_meta[ '_wcv_store_address1' ] : ''; 
$address2 			= ( array_key_exists( '_wcv_store_address2', $vendor_meta ) ) ? $vendor_meta[ '_wcv_store_address2' ] : '';
$city	 			= ( array_key_exists( '_wcv_store_city', $vendor_meta ) ) ? $vendor_meta[ '_wcv_store_city' ]  : '';
$state	 			= ( array_key_exists( '_wcv_store_state', $vendor_meta ) ) ? $vendor_meta[ '_wcv_store_state' ] : '';
$phone				= ( array_key_exists( '_wcv_store_phone', $vendor_meta ) ) ? $vendor_meta[ '_wcv_store_phone' ]  : '';
$store_postcode		= ( array_key_exists( '_wcv_store_postcode', $vendor_meta ) ) ? $vendor_meta[ '_wcv_store_postcode' ]  : '';

$address 			= ( $address1 != '') ? $address1 .', ' . $city .', '. $state .', '. $store_postcode : '';   

$social_icons = empty( $twitter_username ) && empty( $instagram_username ) && empty( $facebook_url ) && empty( $linkedin_url ) && empty( $youtube_url ) && empty( $googleplus_url ) && empty( $pinterst_url ) && empty( $snapchat_username ) ? false : true; 

// This is where you would load your own custom meta fields if you stored any in the settings page for the dashboard

$user_info = get_userdata( $vendor_id );
$email = $user_info->data->user_email;
?>

<?php do_action( 'wcv_before_vendor_store_header' ); ?>
<div class="wcv-header-container-wrapper" >
	<div class="wcv-header-container" <?php if(is_array( $store_banner_src )){ ?> style="background-image:url('<?php echo trim($store_banner_src[0]); ?>');"	 <?php } ?>>

		<div class="wcv-store-grid wcv-store-header"> 

			<div id="banner-wrap">

				<div id="inner-element">
					<div class="store-info pull-left">
						<div class="media">
							<?php if ( ! empty( $store_icon ) ) : ?>
						  		<div class="media-left ">	  
						  			<div class="store-brand clearfix">
							   			<?php echo trim($store_icon); ?>
							   			<?php denso_output_favourite_button( $vendor_id ); ?>
							   		</div>
							   	</div>
						   	<?php endif; ?>
						   	<div class="media-body">
						   		<div class="title-wrapper">
							   		<?php do_action( 'wcv_before_vendor_store_title' ); ?>
							   		<h3 class="title-store"><?php echo trim($store_title); ?></h3>	   	
							   		<?php do_action( 'wcv_after_vendor_store_title' ); ?>

							   		<?php do_action( 'wcv_before_vendor_store_description' ); ?>	
									<?php if ( $verified_vendor ) : ?>	   			
										<div class="wcv-verified-vendor">
											<i class="mn-icon-1240" aria-hidden="true"></i> &nbsp; <?php echo trim($verified_vendor_label); ?>
										</div>
									<?php endif; ?>
								</div>
						   		<!-- rating -->
						   		<div class="rating-products-wrapper">
							   		<?php do_action( 'wcv_before_vendor_store_rating' ); ?>

								   	<?php
								   	if ( ! WCVendors_Pro::get_option( 'ratings_management_cap' ) ) {
								   		?>
								   		<div class="total-ratings">
							   				<span class="total-label"><?php esc_html_e('Author Rating', 'denso'); ?></span>
							   				<span class="rating-value">
								   				<?php echo WCVendors_Pro_Ratings_Controller::ratings_link( $vendor_id, true ); ?>
								   			</span>
								   		</div>
								   		<?php
								   	}
							   		?>
							   		
									<?php do_action( 'wcv_after_vendor_store_rating' ); ?>

									<div class="total-products">
										<span class="total-label"><?php esc_html_e('Total products', 'denso'); ?></span>
										<?php
											$products = new WP_Query( array('author' => $vendor_id, 'post_type'	=> 'product', 'posts_per_page' => -1) );
										?>
										<span class="total-value"><?php echo trim($products->post_count); ?></span>
									</div>
								</div>
								<!-- contact -->
								<?php if ( $address != '' ) { ?>
									<div class="store-address">
										<a href="//maps.google.com/maps?&q=<?php echo esc_url($address); ?>"><address><i class="mn-icon-1142"></i><?php echo trim($address); ?></address></a>
									</div>
								<?php } ?>
								<?php if ($phone != '')  { ?>
									<div class="store-phone">	  
										<a href="tel:<?php echo esc_url($phone); ?>"><i class="mn-icon-250"></i><?php echo trim($phone); ?></a>
									</div>
								<?php } ?>
								<?php if ($email != '')  { ?>
									<div class="store-phone">	  
										<a href="mailto:<?php echo esc_url($email); ?>"><i class="mn-icon-220"></i><?php echo trim($email); ?></a>
									</div>
								<?php } ?>
								<?php if ( $social_icons ) : ?> 				   		   			
								   	<ul class="social-icons"> 
							   			<?php if ( $facebook_url != '') { ?>
							   				<li><a href="<?php echo esc_url($facebook_url); ?>" class="facebook" target="_blank"><i class="mn-icon-1405"></i></a></li>
						   				<?php } ?>
							   			<?php if ( $instagram_username != '') { ?>
							   				<li><a href="//instagram.com/<?php echo esc_url($instagram_username); ?>" class="instagram" target="_blank"><i class="mn-icon-1411"></i></a></li>
						   				<?php } ?>
							   			<?php if ( $twitter_username != '') { ?>
							   				<li><a href="//twitter.com/<?php echo esc_url($twitter_username); ?>" class="twitter" target="_blank"><i class="mn-icon-1406"></i></a></li>
						   				<?php } ?>
							   			<?php if ( $googleplus_url != '') { ?>
							   				<li><a href="<?php echo esc_url($googleplus_url); ?>" class="googleplus" target="_blank"><i class="mn-icon-1409"></i></a></li>
						   				<?php } ?>
							   			<?php if ( $pinterest_url != '') { ?>
							   				<li><a href="<?php echo esc_url($pinterest_url); ?>" class="pinterest" target="_blank"><i class="mn-icon-1416"></i></a></li>
						   				<?php } ?>
							   			<?php if ( $youtube_url != '') { ?>
							   				<li><a href="<?php echo esc_url($youtube_url); ?>" class="youtube" target="_blank"><i class="mn-icon-1407"></i></a></li>
						   				<?php } ?>
							   			<?php if ( $linkedin_url != '') { ?>
							   				<li><a href="<?php echo esc_url($linkedin_url); ?>" class="linkedin" target="_blank"><i class="mn-icon-1408"></i></a></li>
						   				<?php } ?>
							   			<?php if ( $snapchat_username != '') { ?>
							   				<li><a href="//www.snapchat.com/add/<?php echo esc_url($snapchat_username); ?>" target="_blank"><i class="mn-icon-1413" aria-hidden="true"></i></a></li>
						   				<?php } ?>
								   	</ul>
								<?php endif; ?>
						   	</div>
					   	</div>
					   	
				   	</div>

				   	<div class="store-aurhor pull-right">
						<div class="store-aurhor-inner">
							<?php echo get_avatar( $vendor_id, 70 ); ?>
							<p class="name-author"><?php echo trim($user_info->first_name .'&nbsp;'. $user_info->last_name); ?></p>
							<a href="mailto:<?php echo trim($user_info->data->user_email); ?>" class="btn-mess"><i class="mn-icon-220"></i><?php esc_html_e( 'Send a message', 'denso' ); ?></a>
						</div>
					</div>
					   	

				</div>
			</div>
		</div>
		<div class="tabs">
			<?php get_template_part( 'wc-vendors/store/tabs' ); ?>
		</div>
	</div>
</div>

<?php do_action( 'wcv_after_vendor_store_header' ); ?>

	