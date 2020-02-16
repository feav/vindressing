<?php
global $product, $post;
$product_id = $product->get_id();

?>
<div class="media product-block widget-product list list-v2">
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
	</div>
</div>