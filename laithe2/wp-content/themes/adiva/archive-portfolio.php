<?php
get_header();

global $portfolio_loop;

$portfolio_container_class = $cat = $classes = '';

$portfolio_limit      = adiva_get_option( 'portfolio-number-per-page', '12' );
$portfolio_style      = adiva_get_option( 'portfolio-style', 'default' );
$portfolio_spacing    = adiva_get_option( 'portfolio-spacing', '40' );
$portfolio_columns    = adiva_get_option( 'portfolio-columns', '3' );
$portfolio_fullwidth  = adiva_get_option( 'portfolio-fullwidth', 0 );
$portfolio_pagination = adiva_get_option( 'portfolio-pagination-type', 'number' );

//DEMO
if ( isset($_GET['design']) && $_GET['design'] != '' ) {
	$portfolio_style = $_GET['design'];
}

if ( isset($_GET['column']) && $_GET['column'] != '' ) {
	$portfolio_columns = $_GET['column'];
}

if ( isset($_GET['gutter']) && $_GET['gutter'] != '' ) {
	$portfolio_spacing = $_GET['gutter'];
}

if ( isset($_GET['fullwidth']) && $_GET['fullwidth'] != '' ) {
	$portfolio_fullwidth = $_GET['fullwidth'];
}

if ( isset($_GET['pagination']) && $_GET['pagination'] != '' ) {
	$portfolio_pagination = $_GET['pagination'];
}


// Fullwidth
if ( $portfolio_fullwidth == 0 ) {
	$portfolio_container_class = 'portfolio-container container';
} else {
	$portfolio_container_class = 'portfolio-container fullwidth';
}

$i = 0;

// Portfolio style
$classes = array( 'item fl pr' );

if( ! empty( $portfolio_loop['style'] ) ) {
    $classes[] = 'portfolio-' . $portfolio_loop['style'];
} else {
    $classes[] = 'portfolio-' . $portfolio_style;
}

// Portfolio columns
$class_wrap = '';
if( ! empty( $portfolio_loop['columns'] ) ) {
    $class_wrap .= ' columns-' . $portfolio_loop['columns'];
} else {
    $class_wrap .= ' columns-' . $portfolio_columns;
}

if ( isset($portfolio_spacing) && $portfolio_spacing == 0 ) {
	$class_wrap .= ' mb_30';
}

if ( isset($portfolio_spacing) && $portfolio_spacing != '' ) {
	$class_wrap .= ' layout-spacing-' . $portfolio_spacing;
	$classes[] = 'mb_' . esc_attr( $portfolio_spacing );
}

// Filter portfolio post type
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

$args = array(
	'post_type'      => 'portfolio',
	'post_status'    => 'publish',
	'posts_per_page' => $portfolio_limit,
	'paged'          => $paged
);

if ( is_tax( 'portfolio-cat' ) ) {
	$cats = get_queried_object()->term_id;
}

if ( ! empty( $cat ) ) {
	$args['tax_query'] = array(
		'relation' => 'AND',
		array(
			'taxonomy' => 'portfolio-cat',
			'field'    => 'id',
			'terms'    => explode( ',', $cat )
		),
	);
}

$portfolio_query = new WP_Query( $args );


// Retrieve all the categories
$filters = get_terms( 'portfolio-cat', array( 'include' => $cat ) );


?>

<div class="page-content">
	<div class="page-content-inner">
		<div class="<?php echo esc_attr( $portfolio_container_class ); ?>">
			<?php if ( ! is_tax( 'portfolio-cat' ) ) : ?>
				<div class="portfolio-filter tc mb_40">
					<a data-filter="*" class="selected dib" href="javascript:void(0);"><?php esc_html_e( 'All', 'adiva' ); ?></a>
					<?php foreach ( $filters as $cat ) : ?>
						<a data-filter=".portfolio-cat-<?php echo esc_attr( $cat->slug ); ?>" class="dib" href="javascript:void(0);"><?php echo esc_html( $cat->name ); ?></a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<div class="portfolio-layout portfolio-masonry oh <?php echo esc_attr( $class_wrap ); ?>">
				<?php while ( $portfolio_query->have_posts() ) : $portfolio_query->the_post(); ?>
					<article id="portfolio-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>
						<div class="portfolio-item pr">
							<?php if ( has_post_thumbnail() && ! post_password_required() && ! is_attachment()  && ! is_singular( 'portfolio' ) ) : ?>
								<div class="portfolio-thumbnail pr oh">
									<a href="<?php echo esc_url( get_permalink() ); ?>">
										<?php the_post_thumbnail(); ?>
									</a>
									<a href="<?php echo esc_url( wp_get_attachment_url( get_post_thumbnail_id($post->ID) ) ); ?>" data-rel="mfp[gallery]" class="enlarge"><i class="sl icon-size-fullscreen"></i></a>
								</div>
							<?php endif; ?>
							<div class="portfolio-content">
								<?php $categories = wp_get_post_terms( get_the_ID(), 'portfolio-cat' ); ?>
								<?php if ( $categories ) : ?>
									<div class="portfolio-category"><?php echo get_the_term_list( $post->ID, 'portfolio-cat', '', ', ' ); ?></div>
								<?php endif; ?>
								<h4 class="portfolio-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
							</div>
						</div>
					</article>
					<?php $i++; ?>
				<?php endwhile; ?>
			</div>
			<div class="clearfix"></div>
			<?php if ( $portfolio_pagination == 'loadmore' || $portfolio_pagination == 'infinite' ) : ?>
				<div class="adiva-ajax-loadmore tc mt_20" data-load-more='{"page":"<?php echo esc_attr( $wp_query->max_num_pages ); ?>","container":"portfolio-layout","layout":"<?php echo esc_attr( $portfolio_pagination ); ?>"}'>
					<?php echo next_posts_link( esc_html__( 'Load More', 'adiva' ) ); ?>
				</div>
			<?php else : ?>
				<?php adiva_post_pagination(); ?>
			<?php endif; ?>
		</div>
	</div>
</div>

<?php get_footer();
