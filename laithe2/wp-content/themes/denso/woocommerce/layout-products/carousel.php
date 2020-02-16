<?php
$product_item = isset($product_item) ? $product_item : 'inner';
$columns = isset($columns) ? $columns : 4;

$small_cols = $columns <= 1 ? 1 : 2;
$rows = (isset($rows) && ((int)$rows > 0)) ? (int)$rows : 1;
$slick_nav_style = isset($slick_nav_style) ? $slick_nav_style : '';
$nb_extra = isset($nb_extra) ? $nb_extra : 2; 
?>

<div class="slick-carousel <?php echo trim($slick_nav_style.' '.$product_item); ?>" data-carousel="slick" data-items="<?php echo esc_attr($columns); ?>" 
		<?php echo trim($columns >= 8 ? 'data-large="6"' : ''); ?> 
		<?php echo trim($columns >= 8 ? 'data-medium="4"' : ''); ?> 
		data-smallmedium="<?php echo esc_attr($small_cols); ?>" data-extrasmall="<?php echo esc_attr($nb_extra); ?>" 
		data-pagination="true" data-nav="true" <?php echo ($loop->post_count > $columns ? 'data-infinite="true"' : ''); ?> 
		data-rows="<?php echo esc_attr($rows); ?>">

    <?php $count = 0; while ( $loop->have_posts() ): $loop->the_post(); global $product; ?>
        <div class="products-grid product">
            <?php wc_get_template_part( 'item-product/'.$product_item ); ?>
        </div>
    <?php $count++; endwhile; ?>

</div>
<?php wp_reset_postdata(); ?>