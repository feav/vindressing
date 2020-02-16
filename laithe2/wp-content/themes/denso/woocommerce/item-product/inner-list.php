<?php 
global $product, $post;
$product_id = $product->get_id();
$image_attributes = wp_get_attachment_image_src( get_post_thumbnail_id($product_id ), 'blog-thumbnails' );
$time_sale = get_post_meta( $product_id, '_sale_price_dates_to', true );
?>
<div class="product-block list list-archive" data-product-id="<?php echo esc_attr($product_id); ?>">
	<div class="media widget-product ">
		<div class="media-left">
			<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>" class="image">
				<?php denso_product_get_image('shop_catalog'); ?>
			</a>
		</div>
		<div class="media-body">
			<div class="row">
				<div class="col-md-8">
			        <div class="infor">
			        	<?php do_action( 'denso_woocommerce_before_shop_loop_item' ); ?>
			    		<h3 class="name">
			    			<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>"><?php echo trim( $product->get_title() ); ?></a>
			    		</h3>
					    <?php
		                    /**
		                    * woocommerce_after_shop_loop_item_title hook
		                    *
		                    * @hooked woocommerce_template_loop_rating - 5
		                    * @hooked woocommerce_template_loop_price - 10
		                    */
		                    remove_action('woocommerce_after_shop_loop_item_title','woocommerce_template_loop_price',10);
		                    do_action( 'woocommerce_after_shop_loop_item_title');
		                ?>
	    		        <div class="product-excerpt">
				            <?php the_excerpt(); ?>
				        </div>
			        </div>
			    </div>
			    <div class="col-md-4">
			    	<div class="left-list">
			    		<?php if ( $time_sale ): ?>
			                <div class="inner-time">
			                    <div class="apus-countdown clearfix" data-time="timmer"
			                        data-date="<?php echo date('m', $time_sale).'-'.date('d', $time_sale).'-'.date('Y', $time_sale).'-'. date('H', $time_sale) . '-' . date('i', $time_sale) . '-' .  date('s', $time_sale) ; ?>">
			                    </div>
			                    <div class="time-format hidden">
			                        <div class="times"><div class="day">%%D%% <?php esc_html_e('days', 'denso'); ?> </div><div class="hours">%%H%% : %%M%% : %%S%% </div></div>
			                    </div>
			                </div>
			            <?php endif; ?>
			    		<div class="price">
			    			<?php echo trim($product->get_price_html()); ?>
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
			</div>
		</div>
	</div>    
</div>