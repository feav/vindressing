<?php
/**
 * Display single product reviews (comments)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product-reviews.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.2
 */
global $product;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! comments_open() ) {
	return;
}
$product_id = $product->get_id();
?>
<div class="short-reviews">
	
	<?php
		$avg = $product->get_average_rating();
		$total = $product->get_review_count();
		$comment_ratings = get_post_meta( $product_id, '_wc_rating_count', true );
		//$rating = intval( get_comment_meta( $comment->comment_ID, 'rating', true ) );

		$rating_html = wc_get_rating_html( $product->get_average_rating() );
	?>
	<div class="overal-review row">
		<div class="col-sm-5">
			<div class="average-value">
				<?php echo ( $avg ) ? esc_html( round( $avg, 1 ) ) : 0; ?>
				<?php echo trim($rating_html); ?>
				<span class="review-text"><?php printf( _n('%s review', '%s reviews', $total, 'denso'), $total ); ?></span>
			</div>
			<?php
			$product_link = get_permalink($product_id).'#reviews';
			?>
			<a href="<?php echo esc_url( $product_link ); ?>"><?php echo esc_html__( 'See all reviews', 'denso' ); ?></a>
		</div>
		<div class="col-sm-7">
			<div class="detailed-rating">
				<div class="rating-box">
					<div class="detailed-rating-inner">
						<?php for ( $i = 5; $i >= 1; $i -- ) : ?>
							<div class="skill">
								<div class="star-text"><?php echo sprintf(__('%d Star', 'denso'), $i); ?></div>
								<div class="star-rating" title="Rated 4 out of 5">
									<span style="width:<?php echo esc_attr($i * 20); ?>%"></span>
								</div>
								<div class="progress">
									<div class="value-percent hidden"><?php echo ( $total && !empty( $comment_ratings[$i] ) ) ? esc_attr(  round( $comment_ratings[$i] / $total * 100, 2 ) . '%' ) : '0%'; ?></div>
									<div class="progress-bar progress-bar-default" style="<?php echo ( $total && !empty( $comment_ratings[$i] ) ) ? esc_attr( 'width: ' . ( $comment_ratings[$i] / $total * 100 ) . '%' ) : 'width: 0%'; ?>">
									</div>
								</div>
								<div class="value"><?php echo empty( $comment_ratings[$i] ) ? '0' : esc_html( $comment_ratings[$i] ); ?></div>
							</div>
						<?php endfor; ?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="short-commentlist">
		<?php
	    $number = 3;
	    $all_comments = get_comments( array('status' => 'approve', 'number' => $number, 'post_id' => $product_id) );

	    if (is_array( $all_comments)) {
	        foreach($all_comments as $comment) { ?>
	            <div class="commentlist-item">
                    <?php echo denso_substring($comment->comment_content, 12, '...'); ?>
	                <div class="comment-meta">
                        <span class="comment-author"><i class="mn-icon-410"></i><?php echo strip_tags($comment->comment_author); ?></span> | <span class="comment-date"><?php echo trim(mysql2date('F d, Y', $comment->comment_date, false)); ?></span>
	                </div>
	            </div>
	    <?php } 
	    } ?>
	</div>

	<div class="clear"></div>
</div>
