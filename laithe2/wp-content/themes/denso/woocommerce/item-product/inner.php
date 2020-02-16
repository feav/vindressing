<?php 
global $product, $post;
$product_id = $product->get_id();
$image_attributes = wp_get_attachment_image_src( get_post_thumbnail_id($product_id ), 'blog-thumbnails' );
$availability      = $product->get_availability();
$time_sale = get_post_meta( $product_id, '_sale_price_dates_to', true );
?>
<div class="product-block grid" data-product-id="<?php echo esc_attr($product_id); ?>">
    <div class="block-inner">
        <?php do_action( 'denso_woocommerce_before_shop_loop_item' ); ?>
        <figure class="image <?php echo ($availability['class'] == 'out-of-stock')?'out':''; ?>">
            
            <?php woocommerce_show_product_loop_sale_flash(); ?>
            <?php
                // Availability
                $availability_html = empty( $availability['availability'] ) ? '' : '<span class="stock ' . esc_attr( $availability['class'] ) . '">' . esc_html( $availability['availability'] ) . '</span>';
                echo apply_filters( 'woocommerce_stock_html', $availability_html, $availability['availability'], $product );
            ?>
            <a title="<?php the_title(); ?>" href="<?php the_permalink(); ?>" class="product-image">
                <?php
                    /**
                    * woocommerce_before_shop_loop_item_title hook
                    *
                    * @hooked woocommerce_show_product_loop_sale_flash - 10
                    * @hooked woocommerce_template_loop_product_thumbnail - 10
                    */
                    denso_swap_images();
                ?>
            </a>
            <?php if (denso_get_config('show_quickview', true)) { ?>
                <div class="quick-view">
                    <a href="<?php the_permalink(); ?>" class="quickview btn btn-primary" data-productslug="<?php echo trim($post->post_name); ?>">
                       <i class="mn-icon-121"> </i>
                    </a>
                </div>
            <?php } ?>
            <?php if ( $time_sale ): ?>
                <div class=" inner-time">
                    <div class="apus-countdown clearfix" data-time="timmer"
                        data-date="<?php echo date('m', $time_sale).'-'.date('d', $time_sale).'-'.date('Y', $time_sale).'-'. date('H', $time_sale) . '-' . date('i', $time_sale) . '-' .  date('s', $time_sale) ; ?>">
                    </div>
                    <div class="time-format hidden">
                        <div class="times"><div class="day">%%D%% <?php esc_html_e('days', 'denso'); ?> </div><div class="hours">%%H%% : %%M%% : %%S%% </div></div>
                    </div>
                </div>
            <?php endif; ?>
        </figure>
        <div class="caption">
            <h3 class="name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <div class="infor">
                <?php
                    /**
                    * woocommerce_after_shop_loop_item_title hook
                    *
                    * @hooked woocommerce_template_loop_rating - 5
                    * @hooked woocommerce_template_loop_price - 10
                    */
                    do_action( 'woocommerce_after_shop_loop_item_title');
                ?>
            </div> 
        </div>
        <div class="groups-button clearfix">
            <?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
            
            <?php if( class_exists( 'YITH_Woocompare' ) ) { ?>
                <?php
                    $action_add = 'yith-woocompare-add-product';
                    $url_args = array(
                        'action' => $action_add,
                        'id' => $product_id
                    );
                ?>
                <div class="yith-compare">
                    <a title="<?php echo esc_html__('compare','denso') ?>" href="<?php echo wp_nonce_url( add_query_arg( $url_args ), $action_add ); ?>" class="compare" data-product_id="<?php echo esc_attr($product_id); ?>">
                        <em class="mn-icon-1013"></em>
                    </a>
                </div>
            <?php } ?>

            <?php
                if( class_exists( 'YITH_WCWL' ) ) {
                    echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
                }
            ?>
        </div>
    </div>
    <?php do_action('denso_show_wooswatches'); ?>
</div>
