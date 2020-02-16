<?php
/**
 * Review Comments Template
 *
 * Closing li is left out on purpose!
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$rating = intval( get_comment_meta( $comment->comment_ID, 'rating', true ) );

?>
<li itemprop="review" itemscope itemtype="http://schema.org/Review" <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">

	<div id="comment-<?php comment_ID(); ?>" class="comment_container media">
		<div class="apus-avata pull-left">
			<div class="apus-image">
				<?php echo get_avatar( $comment, apply_filters( 'woocommerce_review_gravatar_size', '60' ), '' ); ?>
			</div>
			
		</div>
		<div class="comment-text media-body">
			<div class="clearfix top-info">
				<div class="pull-left">
					<div class="apus-author clear" itemprop="author"><?php comment_author(); ?></div> 	
					<?php if ( $comment->comment_approved == '0' ) : ?>
						<p class="meta"><em><?php esc_html_e( 'Your comment is awaiting approval', 'denso' ); ?></em></p>
					<?php else : ?>
						<p class="meta">
							<?php
								if ( get_option( 'woocommerce_review_rating_verification_label' ) === 'yes' )
									if ( wc_customer_bought_product( $comment->comment_author_email, $comment->user_id, $comment->comment_post_ID ) )
										echo '<em class="verified">(' . esc_html__( 'verified owner', 'denso' ) . ')</em> ';

							?><time itemprop="datePublished" datetime="<?php echo get_comment_date( 'c' ); ?>"><?php echo get_comment_date( wc_date_format() ); ?></time>
						</p>
					<?php endif; ?>
				</div>
				
				<?php if ( $rating && get_option( 'woocommerce_enable_review_rating' ) == 'yes' ) : ?>

					<div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" class="star-rating pull-right" title="<?php echo sprintf( esc_html__( 'Rated %d out of 5', 'denso' ), $rating ) ?>">
						<span style="width:<?php echo ( $rating / 5 ) * 100; ?>%"><strong itemprop="ratingValue"><?php echo trim($rating); ?></strong> <?php esc_html_e( 'out of 5', 'denso' ); ?></span>
					</div>

				<?php endif; ?>
			</div>

			<div itemprop="description" class="description clear"><?php comment_text(); ?></div>
		</div>
	</div>
</li>