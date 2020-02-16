<?php
global $product, $post;
$product_id = $product->get_id();

?>
<div class="media product-block widget-product list list-v1">
	<div class="media-left">
		<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>" class="image">
			<?php denso_product_get_image('shop_catalog'); ?>
		</a>
	</div>
	<div class="media-body">
        <?php do_action( 'denso_woocommerce_before_shop_loop_item' ); ?>
        <div class="infor">
    		<h3 class="name">
    			<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>"><?php echo trim( $product->get_title() ); ?></a>
    		</h3>

    		<?php do_action( 'woocommerce_after_shop_loop_item_title' ); ?>
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
	</div>
</div>