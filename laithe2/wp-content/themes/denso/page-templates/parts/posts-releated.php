<?php
    $relate_count = denso_get_config('number_blog_releated', 3);
    $relate_columns = denso_get_config('releated_blog_columns', 3);
    $terms = get_the_terms( get_the_ID(), 'category' );
    $termids =array();

    if ($terms) {
        foreach($terms as $term) {
            $termids[] = $term->term_id;
        }
    }

    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $relate_count,
        'post__not_in' => array( get_the_ID() ),
        'tax_query' => array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'category',
                'field' => 'id',
                'terms' => $termids,
                'operator' => 'IN'
            )
        )
    );
    $relates = new WP_Query( $args );
    if( $relates->have_posts() ):
?>
    <div class="widget">
        <h4 class="widget-title">
            <span><?php esc_html_e( 'Releated Posts', 'denso' ); ?></span>
        </h4>

        <div class="related-posts-content widget-content">
            <div class="slick-carousel" data-carousel="slick" data-smallmedium="2" data-extrasmall="1" data-items="<?php echo esc_attr($relate_columns); ?>" data-pagination="false" data-nav="true">
                <?php while ( $relates->have_posts() ) : $relates->the_post(); ?>
                    <?php get_template_part( 'post-formats/loop/grid/_item' ); ?>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            </div>
        </div>
        
    </div>
<?php endif; ?>