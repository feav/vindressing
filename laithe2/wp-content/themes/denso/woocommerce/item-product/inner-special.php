<?php 
global $product, $post;
$product_id = $product->get_id();
$image_attributes = wp_get_attachment_image_src( get_post_thumbnail_id($product_id ), 'blog-thumbnails' );
$time_sale = get_post_meta( $product_id, '_sale_price_dates_to', true );

$stock_sold = ( $total_sales = get_post_meta( $product_id, 'total_sales', true ) ) ? round( $total_sales ) : 0;
$stock_available = ( $stock = get_post_meta( $product_id, '_stock', true ) ) ? round( $stock ) : 0;
$total_stock = $stock_sold + $stock_available;
$percentage = ( $stock_available > 0 ? round($stock_sold/$total_stock * 100) : 0 );
$style = isset($style) ? $style : '';

$regular_price = $product->get_regular_price();
$sale_price = $product->get_sale_price();
?>
<div class="product-block inner-special <?php echo esc_attr($style); ?>" data-product-id="<?php echo esc_attr($product_id); ?>">
    <?php if($style == 'style3'){?>
        <?php do_action( 'denso_woocommerce_before_shop_loop_item' ); ?>
                <div class="apus-special-images">
                    <figure class="image">
                        <a title="<?php the_title(); ?>" href="<?php the_permalink(); ?>" class="product-image">
                            <?php denso_product_get_image('shop_single'); ?>
                        </a>
                    </figure>
                    <?php if ($regular_price ) { ?>
                        <div class="sale-off">
                            <?php
                                $percent = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
                                echo sprintf(__('Save <span>%s</span>', 'denso'), $percent . '%');
                            ?>
                        </div>
                    <?php } ?>
                    <?php
                    $images = $product->get_gallery_image_ids();

                    $attachment_ids = array();
                    if ( in_array(get_post_thumbnail_id(), $images) ) {
                        $attachment_ids = $images;
                    } elseif ( get_post_thumbnail_id() || $images ) {
                        $attachment_ids = array_merge_recursive( array( get_post_thumbnail_id() ) , $images ) ;
                    }
                     
                    if ( $attachment_ids ) {
                        $columns    = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
                        ?>
                        
                        <div class="thumbnails-image hidden-xs">
                            <div class="slick-carousel slick-small thumbnails-image-carousel" data-carousel="slick" data-items="<?php echo esc_attr($columns); ?>" data-smallmedium="2" data-extrasmall="2" data-pagination="false" data-nav="true">

                            <?php
                            foreach ( $attachment_ids as $attachment_id ) {
                                $classes = array( 'thumb-link' );

                                $image_large_src = wp_get_attachment_image_src( $attachment_id, 'shop_single' );
                                $image_large_link = isset($image_large_src[0]) ? $image_large_src[0] : '';

                                $image_src = wp_get_attachment_image_src( $attachment_id, 'shop_thumbnail' );
                                $image_link = isset($image_src[0]) ? $image_src[0] : '';

                                if ( ! $image_link )
                                    continue;

                                $image_title    = esc_attr( get_the_title( $attachment_id ) );
                                $image_caption  = esc_attr( get_post_field( 'post_excerpt', $attachment_id ) );
                                
                                if (denso_get_config('image_lazy_loading')) {
                                    $placeholder_image = denso_create_placeholder(array($image_src[1],$image_src[2]));
                                    $image = '<img src="'.esc_url($placeholder_image).'" data-src="'.esc_url($image_link).'" class="attachment-shop_thumbnail size-shop_thumbnail unveil-image" title="'.esc_attr($image_title).'" alt="'.esc_attr($image_title).'">';
                                } else {
                                    $image = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_large_thumbnail_size', 'shop_thumbnail' ), 0, $attr = array(
                                        'title' => $image_title,
                                        'alt'   => $image_title
                                        ) );
                                }

                                $image_class = esc_attr( implode( ' ', $classes ) );
                                echo '<div class="image-wrapper">';
                                echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', sprintf( '<a href="%s" class="%s" title="%s">%s</a>', $image_large_link, $image_class, $image_caption, $image ), $attachment_id, $post->ID, $image_class );
                                echo '</div>';
                            }

                            ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="caption">
                    <div class="meta">
                        <h3 class="name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <div class="entry-rating">
                            <?php
                                woocommerce_template_loop_rating();
                            ?>
                        </div>
                        <div class="entry-excerpt">
                            <?php the_excerpt(); ?>
                        </div>
                        <?php
                            /**
                            * woocommerce_after_shop_loop_item_title hook
                            *
                            * @hooked woocommerce_template_loop_rating - 5
                            * @hooked woocommerce_template_loop_price - 10
                            */
                            remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
                            do_action( 'woocommerce_after_shop_loop_item_title' );
                        ?>
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
                                    <a href="<?php echo wp_nonce_url( add_query_arg( $url_args ), $action_add ); ?>" class="compare" data-product_id="<?php echo esc_attr($product_id); ?>">
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
                        <div class="time">
                            <?php if ( $time_sale ): ?>
                                <span><?php echo esc_html__( 'Start for you in: ', 'denso' ); ?></span>
                                <div class="apus-countdown clearfix" data-time="timmer"
                                    data-date="<?php echo date('m', $time_sale).'-'.date('d', $time_sale).'-'.date('Y', $time_sale).'-'. date('H', $time_sale) . '-' . date('i', $time_sale) . '-' .  date('s', $time_sale) ; ?>">
                                </div>
                            <?php endif; ?> 
                        </div>

                        <?php if ( $stock_available > 0 ) { ?>
                        <div class="special-progress">
                            <div class="claimed">
                                <?php echo sprintf(__('Claimed: <strong>%s</strong>', 'denso'), $percentage . '%'); ?>
                            </div>
                            <div class="progress">
                                <span class="progress-bar" style="<?php echo esc_attr('width:' . $percentage . '%'); ?>"></span>
                            </div>
                        </div>
                        <?php } ?>

                    </div>    
                    
                </div>
    <?php }else{ ?>
    <?php do_action( 'denso_woocommerce_before_shop_loop_item' ); ?>
    <div class="row">
        <div class="col-md-6">
            <div class="apus-special-images">
                <figure class="image">
                    <a title="<?php the_title(); ?>" href="<?php the_permalink(); ?>" class="product-image">
                        <?php denso_product_get_image('shop_single'); ?>
                    </a>
                </figure>
                <?php
                    if ( $regular_price && is_numeric($regular_price) && is_numeric($sale_price) ) {
                        $percent = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
                ?>
                    <div class="sale-off">
                        <?php echo sprintf(__('Save <span>%s</span>', 'denso'), $percent . '%'); ?>
                    </div>
                <?php } ?>
                
                <?php
                $images = $product->get_gallery_image_ids();

                $attachment_ids = array();
                if ( in_array(get_post_thumbnail_id(), $images) ) {
                    $attachment_ids = $images;
                } elseif ( get_post_thumbnail_id() || $images ) {
                    $attachment_ids = array_merge_recursive( array( get_post_thumbnail_id() ) , $images ) ;
                }
                 
                if ( $attachment_ids ) {
                    $columns    = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
                    ?>
                    
                    <div class="thumbnails-image hidden-xs">
                        <div class="slick-carousel slick-small thumbnails-image-carousel" data-carousel="slick" data-items="<?php echo esc_attr($columns); ?>" data-smallmedium="2" data-extrasmall="2" data-pagination="false" data-nav="true">

                        <?php
                        foreach ( $attachment_ids as $attachment_id ) {
                            $classes = array( 'thumb-link' );

                            $image_large_src = wp_get_attachment_image_src( $attachment_id, 'shop_single' );
                            $image_large_link = isset($image_large_src[0]) ? $image_large_src[0] : '';

                            $image_src = wp_get_attachment_image_src( $attachment_id, 'shop_thumbnail' );
                            $image_link = isset($image_src[0]) ? $image_src[0] : '';

                            if ( ! $image_link )
                                continue;

                            $image_title    = esc_attr( get_the_title( $attachment_id ) );
                            $image_caption  = esc_attr( get_post_field( 'post_excerpt', $attachment_id ) );
                            
                            if (denso_get_config('image_lazy_loading')) {
                                $placeholder_image = denso_create_placeholder(array($image_src[1],$image_src[2]));
                                $image = '<img src="'.esc_url($placeholder_image).'" data-src="'.esc_url($image_link).'" class="attachment-shop_thumbnail size-shop_thumbnail unveil-image" title="'.esc_attr($image_title).'" alt="'.esc_attr($image_title).'">';
                            } else {
                                $image = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_large_thumbnail_size', 'shop_thumbnail' ), 0, $attr = array(
                                    'title' => $image_title,
                                    'alt'   => $image_title
                                    ) );
                            }

                            $image_class = esc_attr( implode( ' ', $classes ) );
                            echo '<div class="image-wrapper">';
                            echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', sprintf( '<a href="%s" class="%s" title="%s">%s</a>', $image_large_link, $image_class, $image_caption, $image ), $attachment_id, $post->ID, $image_class );
                            echo '</div>';
                        }

                        ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="caption">
                <div class="meta">
                    <h3 class="name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <div class="entry-rating">
                        <?php
                            woocommerce_template_loop_rating();
                        ?>
                    </div>
                    <div class="entry-excerpt">
                        <?php the_excerpt(); ?>
                    </div>
                    <?php
                        /**
                        * woocommerce_after_shop_loop_item_title hook
                        *
                        * @hooked woocommerce_template_loop_rating - 5
                        * @hooked woocommerce_template_loop_price - 10
                        */
                        remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
                        do_action( 'woocommerce_after_shop_loop_item_title' );
                    ?>
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
                                <a href="<?php echo wp_nonce_url( add_query_arg( $url_args ), $action_add ); ?>" class="compare" data-product_id="<?php echo esc_attr($product_id); ?>">
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
                    <div class="time">
                        <?php if ( $time_sale ): ?>
                            <span><?php echo esc_html__( 'Start for you in: ', 'denso' ); ?></span>
                            <div class="apus-countdown clearfix" data-time="timmer"
                                data-date="<?php echo date('m', $time_sale).'-'.date('d', $time_sale).'-'.date('Y', $time_sale).'-'. date('H', $time_sale) . '-' . date('i', $time_sale) . '-' .  date('s', $time_sale) ; ?>">
                            </div>
                        <?php endif; ?> 
                    </div>

                    <?php if ( $stock_available > 0 ) { ?>
                    <div class="special-progress">
                        <div class="claimed">
                            <?php echo sprintf(__('Claimed: <strong>%s</strong>', 'denso'), $percentage . '%'); ?>
                        </div>
                        <div class="progress">
                            <span class="progress-bar" style="<?php echo esc_attr('width:' . $percentage . '%'); ?>"></span>
                        </div>
                    </div>
                    <?php } ?>

                </div>    
                
            </div>
        </div>
    </div>
    <?php } ?>
</div>
