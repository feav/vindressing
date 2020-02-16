<?php
/**
 * Loop Rating
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/rating.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

if ( ! wc_review_ratings_enabled() ) {
	return;
}
?>

<?php if ( $rating_html = wc_get_rating_html($product->get_average_rating()) ) { ?>
	<div class="rating rating-popover-wrapper">
		<div class="rating-box">
			<?php echo trim( $rating_html ); ?>
			<a class="quickview-rating" href="#quickview-rating" data-productslug="<?php echo trim($post->post_name); ?>"><i class="mn-icon-41"></i></a>
		</div>
		<!-- <div class="rating-popover-content"></div> -->
	</div>
<?php } ?>

