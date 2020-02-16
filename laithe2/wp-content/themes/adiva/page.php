<?php
get_header();

$options = $content_class = $sidebar_class = $layout_classes = '';

// Get page options
$options = get_post_meta( get_the_ID(), '_custom_page_options', true );

// Get VC setting
$vc = get_post_meta( get_the_ID(), '_wpb_vc_js_status', true );

if ( is_array($options) && isset( $options['page-sidebar-position'] ) && $options['page-sidebar-position'] != '' ) {
	$page_sidebar = $options['page-sidebar-position'];
} else {
	$page_sidebar = 'right';
}

if ( ( $page_sidebar == 'left' || $page_sidebar == 'right' ) && is_active_sidebar( 'primary-sidebar' ) ) {
	$content_class = 'col-md-9 col-xs-12 mt_100 mb_100';
	$sidebar_class = 'col-md-3 col-xs-12 mt_100 mb_100';
} elseif( $page_sidebar == 'no' || !is_active_sidebar( 'primary-sidebar' ) ) {
	$content_class = 'col-md-12 col-xs-12';

	if ( $vc == 'false' || empty( $vc ) ) {
		$content_class = 'col-md-12 col-xs-12 mt_100 mb_100';
	}
}

$smart_sidebar   = adiva_get_option( 'smart-sidebar', 0 );
if ( isset($smart_sidebar) && $smart_sidebar == 1 ) {
	$sidebar_class .= ' smart-sidebar';
}

if ( isset( $page_sidebar ) && $page_sidebar == 'left' && is_active_sidebar( 'primary-sidebar' ) ) {
	$layout_classes = 'left-sidebar';
}

?>

<div class="page-content">
	<div class="page-content-inner">

		<div class="container">

			<div class="row <?php echo esc_attr($layout_classes); ?>">

				<div id="main-content" class="<?php echo esc_attr( $content_class ); ?>">
					<?php
						while ( have_posts() ) : the_post();
							the_content();
							?>
							<div class="clearfix"></div>
							<?php
							// If comments are open or we have at least one comment, load up the comment template.
							if ( comments_open() || get_comments_number() ) {
								comments_template();
							}
						endwhile;

						// Displays page-links
						wp_link_pages();
					?>
				</div>

				<?php if ( isset( $page_sidebar ) && $page_sidebar != 'no' && is_active_sidebar( 'primary-sidebar' ) ) : ?>
					<div id="main-sidebar" class="<?php echo esc_attr( $sidebar_class ); ?>">
						<?php dynamic_sidebar( 'primary-sidebar' ); ?>
					</div>
				<?php endif; ?>

			</div>


		</div>

	</div>
</div><!-- page-content -->

<?php get_footer();
