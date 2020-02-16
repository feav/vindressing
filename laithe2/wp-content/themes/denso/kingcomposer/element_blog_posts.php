<?php

$atts  = array_merge( array(
	'number'  => 8,
	'columns'	=> 4,
	'order_by'		=> 'ID',
	'order'	=> 'ASC',
	'layout_type' => 'grid',
    'title' => '',
), $atts);
extract( $atts );


$args = array(
	'paged' => 1,
	'posts_per_page' => $number,
	'post_status' => 'publish',
	'orderby' => $order_by,
	'order' => $order,
);

$bcol = 12/$columns;
if ($columns == 5) {
    $bcol = 'cus-5';
}

$loop = new WP_Query($args);

?>
<div class="widget clearfix widget-blog <?php echo esc_attr($layout_type); ?>">
    <?php if ($title) { ?>
        <h3 class="widget-title"><?php echo esc_attr($title); ?></h3>
    <?php } ?>
    <?php if ( $loop->have_posts() ): ?>
        <div class="widget-content">
            <?php if ( $layout_type == 'carousel' ): ?>

                <div class="slick-carousel posts" data-carousel="slick" data-smallmedium="2" data-extrasmall="1" data-items="<?php echo esc_attr($columns); ?>"  data-pagination="false" data-nav="true">
                    <?php while ( $loop->have_posts() ): $loop->the_post(); ?>
                        <?php get_template_part( 'post-formats/loop/grid/_item' ); ?>
                    <?php endwhile; ?>
                </div>
            <?php elseif ( $layout_type == 'carousel-list' ): ?>
                <div class="slick-carousel posts slick-small slick-small-top carousel-list" data-carousel="slick" data-smallmedium="2" data-extrasmall="1" data-items="<?php echo esc_attr($columns); ?>"  data-pagination="false" data-nav="true">
                    <?php while ( $loop->have_posts() ): $loop->the_post(); ?>
                        <?php get_template_part( 'post-formats/loop/grid/_item_list' ); ?>
                    <?php endwhile; ?>
                </div>
            <?php elseif ( $layout_type == 'grid' ): ?>

                <div class="layout-blog style-grid">
                    <div class="row">
                        <?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
                            <div class="col-md-<?php echo esc_attr($bcol); ?>">
                                <?php get_template_part( 'post-formats/loop/grid/_item' ); ?>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            <?php elseif ( $layout_type == 'special' ): ?>

                <div class="layout-blog-special">
                    <div class="row">
                        <?php $count = 0; while ( $loop->have_posts() ) : $loop->the_post(); ?>
                            <?php if ($count == 0) { ?>
                                <div class="col-sm-6 special-layout">
                                    <?php get_template_part( 'post-formats/loop/grid/_item' ); ?>
                                </div>
                            <?php } else { ?>
                                <?php if ($count == 1) { ?>
                                    <div class="col-sm-6 blog-no-image">
                                <?php } ?>
                                    <?php get_template_part( 'post-formats/loop/grid/_item' ); ?>
                                <?php if ($count == ($loop->post_count - 1) ) { ?>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        <?php $count++; endwhile; ?>
                    </div>
                </div>
            <?php elseif ( $layout_type == 'list' ): ?>

                <ul class="posts-list">
                    <?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
                        <li>
                            <?php get_template_part( 'post-formats/loop/list/_item' ); ?>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php endif; ?>
        </div>
        <?php wp_reset_postdata(); ?>
    <?php endif; ?>
</div>

