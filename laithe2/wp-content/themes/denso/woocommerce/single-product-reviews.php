<?php
/**
 * Display single product reviews (comments)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product-reviews.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $product;

if ( ! comments_open() ) {
	return;
}
$product_id = $product->get_id();
?>
<div id="reviews">
	
	<div class="clearfix">
		<div class="left-inner">
			<?php
			if ( wc_review_ratings_enabled() ) {
				$avg = $product->get_average_rating();
				$total = $product->get_review_count();
				$comment_ratings = get_post_meta( $product_id, '_wc_rating_count', true );
			?>
				<h3 class="title-info"><?php printf( _n('Based on %s review', 'Based on %s reviews', $total, 'denso'), $total ); ?></h3>
				<div class="average-value">
					<?php echo ( $avg ) ? esc_html( round( $avg, 1 ) ) : 0; ?>
					<span><?php echo esc_html__('overall', 'denso'); ?></span>	
				</div>
				<div class="detailed-rating">
					<div class="rating-box">
						<div class="detailed-rating-inner">

							<?php for ( $i = 5; $i >= 1; $i -- ) : ?>
								<div class="skill special-progress">
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
			<?php } ?>
		</div>
		<div class="right-inner">
			<?php if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product_id ) ) : ?>

				<div id="review_form_wrapper">
					<div id="comments">
						<?php if ( have_comments() ) : ?>

							<ol class="commentlist">
								<?php wp_list_comments( apply_filters( 'woocommerce_product_review_list_args', array( 'callback' => 'woocommerce_comments' ) ) ); ?>
							</ol>

							<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
								echo '<nav class="woocommerce-pagination">';
								paginate_comments_links( apply_filters( 'woocommerce_comment_pagination_args', array(
									'prev_text' => '&larr;',
									'next_text' => '&rarr;',
									'type'      => 'list',
								) ) );
								echo '</nav>';
							endif; ?>

						<?php else : ?>

							<p class="woocommerce-noreviews"><?php esc_html_e( 'There are no reviews yet.', 'denso' ); ?></p>

						<?php endif; ?>
					</div>
					<div id="review_form">
						<?php
							$commenter = wp_get_current_commenter();

							$comment_form = array(
								'title_reply'          => have_comments() ? esc_html__( 'Add a review', 'denso' ) : sprintf( esc_html__( 'Be the first to review &ldquo;%s&rdquo;', 'denso' ), get_the_title() ),
								'title_reply_to'       => esc_html__( 'Leave a Reply to %s', 'denso' ),
								'comment_notes_before' => '',
								'comment_notes_after'  => '',
								'fields'               => array(
									'author' => '<div class="row"><div class="col-md-6 col-xs-12"><p class="comment-form-author">'.
									            '<input id="author" class="form-control" name="author" type="text" placeholder ="'.esc_html__( 'Name', 'denso' ).'" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" aria-required="true" /></p>',
									'email'  => '</div><div class="col-md-6 col-xs-12"><p class="comment-form-email">'.
									            '<input id="email" class="form-control" name="email" type="text" placeholder ="'.esc_html__( 'Email ', 'denso' ).'" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" aria-required="true" /></p></div></div>',
								),
								'label_submit'  => esc_html__( 'Add Review', 'denso' ),
								'logged_in_as'  => '',
								'comment_field' => ''
							);

							if ( $account_page_url = wc_get_page_permalink( 'myaccount' ) ) {
								$comment_form['must_log_in'] = '<p class="must-log-in">' .  sprintf( esc_html__( 'You must be <a href="%s">logged in</a> to post a review.', 'denso' ), esc_url( $account_page_url ) ) . '</p>';
							}

							if ( wc_review_ratings_enabled() ) {
								
								$comment_form['comment_field'] = '<div class="review"><label class="you-rating" for="rating">' . esc_html__( 'Your Rating', 'denso' ) .'</label><div class="comment-form-rating list-rating">
									<ul class="review-stars">
										<li><span class="fa fa-star-o active"></span></li>
										<li><span class="fa fa-star-o active"></span></li>
										<li><span class="fa fa-star-o active"></span></li>
										<li><span class="fa fa-star-o active"></span></li>
										<li><span class="fa fa-star-o active"></span></li>
									</ul>
									<input type="hidden" value="5" name="rating" id="rating"></div></div>';
							}

							$comment_form['comment_field'] .= '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="6" aria-required="true" placeholder="Your comment" ></textarea></p>';

							comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ) );
						?>
					</div>
				</div>

			<?php else : ?>

				<p class="woocommerce-verification-required"><?php esc_html_e( 'Only logged in customers who have purchased this product may leave a review.', 'denso' ); ?></p>

			<?php endif; ?>
		</div>
	</div>
	<div class="clear"></div>
</div>
