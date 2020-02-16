<?php
	$js_folder = denso_get_js_folder();
    $min = denso_get_asset_min();
    wp_enqueue_script( 'denso-isotope-js', $js_folder . '/isotope.pkgd'.$min.'.js', array( 'jquery' ) );
    $columns = denso_get_config('blog_columns', 1);
	$bcol = floor( 12 / $columns );
?>
<div class="row row-40">
    <div class="isotope-items" data-isotope-duration="400">
        <?php while ( have_posts() ) : the_post(); ?>
            <div class="isotope-item col-md-<?php echo esc_attr($bcol); ?> col-sm-<?php echo esc_attr($bcol); ?> col-xs-12">
                <?php get_template_part( 'post-formats/content', get_post_format() ); ?>
            </div>
        <?php endwhile; ?>
    </div>
</div>