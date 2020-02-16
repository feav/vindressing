<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>

<?php if ( have_posts() ) : ?>
    <?php do_action('woocommerce_before_shop_loop'); ?>
    
    <!-- sub categories --> 
    <?php
        $term = get_queried_object();
        $name = '';
        if (!empty($term) && isset($term->taxonomy) && $term->taxonomy == 'product_cat') {
            $name = sprintf(__('%s Categories', 'denso'), $term->name);
        }
        $args = array(
            'before'        => '<div class="widget categories-wrapper">
                    <div class="widget-title"><h3>'.$name.'</h3></div>
                        <div class="row">',
            'after'         => '</div></div>',
            'force_display' => false
        );
        woocommerce_product_subcategories($args);
    ?>

    <!-- Block Products -->
    <?php
    
    $parent_slug = empty( $term->slug ) ? 0 : $term->slug;
    if (!empty($term) && isset($term->taxonomy) && $term->taxonomy == 'product_cat') {
        $blocks = denso_get_config( 'product_archive_sort_block' );
        $blocks = isset($blocks['enabled']) ? $blocks['enabled'] : array();
        foreach ($blocks as $key => $value) {
            $categories = denso_get_config( 'products_'.$key.'_categories' );
            if (is_array($categories) && in_array($term->slug, $categories)) {
                denso_display_products_by_category($parent_slug, $key);
            }
        }
    }
    ?>
    
    <?php
    if (  denso_get_config('product_control_bar_position') != 'top' ) {
        do_action( 'denso_before_products');
    }
    ?>
    
    <?php woocommerce_product_loop_start(); ?>
        
        <?php while ( have_posts() ) : the_post(); ?>
            <?php wc_get_template_part( 'content', 'product' ); ?>
        <?php endwhile; // end of the loop. ?>
        
    <?php woocommerce_product_loop_end(); ?>
    <?php do_action('woocommerce_after_shop_loop'); ?>

<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>
    <?php wc_get_template( 'loop/no-products-found.php' ); ?>
<?php endif;