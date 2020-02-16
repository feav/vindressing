<?php
$blog_design  = adiva_get_option( 'blog-design', 'default' );
$blog_style   = adiva_get_option( 'blog-style', 'flat' );
$blog_columns = adiva_get_option( 'blog-columns', 3 );
$blog_image_size = adiva_get_option('blog-image-size', '1080x570');

$classes = array();

if ( !has_post_thumbnail() ) {
	$classes[] = 'no-post-thumbnail';
}

// DEMO
if ( isset($_GET['design']) && $_GET['design'] != '' ) {
	$blog_design = $_GET['design'];
}

if ( isset($_GET['style']) && $_GET['style'] != '' ) {
	$blog_style = $_GET['style'];
}

if ( isset($_GET['columns']) && $_GET['columns'] != '' ) {
	$blog_columns = $_GET['columns'];
}



if( isset( $blog_design ) && $blog_design != '' ) {
	$classes[] = 'blog-post-loop';
	$classes[] = 'blog-design-' . $blog_design;
}

if( isset( $blog_style ) && $blog_style != '' ) {
	$classes[] = 'blog-style-' . $blog_style;

	if( $blog_design == 'chess' ) {
		$classes[] = 'blog-design-small-images';
	}
}


if( isset( $blog_columns ) && $blog_columns == 2 && $blog_design == 'masonry' ) {
	$classes[] = 'col-lg-6 col-md-6 col-sm-6 col-xs-12';
} elseif( isset( $blog_columns ) && $blog_columns == 3 && $blog_design == 'masonry' ) {
	$classes[] = 'col-lg-4 col-md-4 col-sm-6 col-xs-12';
} elseif( isset( $blog_columns ) && $blog_columns == 4 && $blog_design == 'masonry' ) {
	$classes[] = 'col-lg-3 col-md-3 col-sm-6 col-xs-12';
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>
	<div class="article-inner">
		<?php if ( has_post_thumbnail() && ! post_password_required() && ! is_attachment() ) : ?>
			<header class="entry-header pr">
				<figure class="entry-thumbnail">
					<div class="post-img-wrap">
						<a href="<?php echo esc_url( get_permalink() ); ?>">
							<?php
							if ( isset( $blog_image_size ) && $blog_image_size != '' ) {
								echo adiva_get_post_thumbnail($blog_image_size);
							} else {
								the_post_thumbnail('large');
							}
							?>
						</a>
					</div>
				</figure>
			</header><!-- .entry-header -->
		<?php endif; ?>

        <div class="article-body-container">
            <h3 class="entry-title">
                <a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark"><?php the_title(); ?></a>
            </h3>

            <div class="entry-meta adiva-entry-meta">
                <?php adiva_post_meta(); ?>
            </div><!-- .entry-meta -->

            <?php if ( is_search() ) : // Only display Excerpts for Search ?>
				<div class="entry-summary">
					<?php the_excerpt(); ?>
				</div><!-- .entry-summary -->
			<?php else : ?>
				<div class="entry-content adiva-entry-content mt_20">
					<?php
					if( isset( $blog_design ) && $blog_design == 'default' && ! class_exists('Redux') ) {
						the_content();
						echo '<div class="clearfix"></div>';
					} else {
						adiva_get_content();
					}
					?>

					<?php wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'adiva' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) ); ?>
				</div><!-- .entry-content -->
			<?php endif; ?>

        </div>


	</div>
</article><!-- #post-# -->
