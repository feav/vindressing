<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
 * If the current post is protected by a password and the visitor has not yet
 * entered the password we will return early without loading the comments.
 */
if ( post_password_required() )
	return;
?>

<div id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
				printf( wp_kses( _nx( 'One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'adiva' ), array() ),
					number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>' );
			?>			
		</h2>

		<ol class="comment-list">
			<?php
				wp_list_comments( array(
				 'style'    => 'ol',
				 'callback' => 'adiva_comments_list',
				) );
			?>
		</ol><!-- .comment-list -->

		<?php
			// Are there comments to navigate through?
			if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
		?>
		<nav class="navigation comment-navigation" role="navigation">
			<h1 class="screen-reader-text section-heading"><?php esc_html_e( 'Comment navigation', 'adiva' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( esc_html__( '&larr; Older Comments', 'adiva' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments &rarr;', 'adiva' ) ); ?></div>
		</nav><!-- .comment-navigation -->
		<?php endif; // Check for comment navigation ?>

		<?php if ( ! comments_open() && get_comments_number() ) : ?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.' , 'adiva' ); ?></p>
		<?php endif; ?>

	<?php endif; ?>

	<?php
		$args = array(
			'comment_notes_before' => '',
			// Redefine your own textarea (the comment body)
			'comment_field' => '<div class="comment-form-comment mb_30 mt_30"><textarea rows="8" placeholder="' . esc_attr__( 'Your comment *', 'adiva' ) . '" name="comment" aria-required="true"></textarea></div>',
            'fields' => '
				<div class="row">
					<div class="comment-form-author col-md-4 mb_30">
						<input placeholder="' . esc_attr__( 'Your name *', 'adiva' ) . '" type="text" required="required" size="30" value="" name="author" id="author">
					</div>
					<div class="comment-form-email col-md-4 mb_30">
						<input placeholder="' . esc_attr__( 'Your email *', 'adiva' ) . '" type="email" required="required" size="30" value="" name="email" id="email">
					</div>
					<div class="comment-form-url col-md-4 mb_30">
						<input placeholder="' . esc_attr__( 'Your website', 'adiva' ) . '" type="url" size="30" value="" name="url" id="url">
					</div>
				</div>
			',
			// Change the title of the reply section
			'title_reply'=> __( 'Leave your comment', 'adiva' ),

			// Change the title of send button
			'label_submit'=> __( 'Submit', 'adiva' ),
		);

		comment_form( $args );
	?>

</div><!-- #comments -->
