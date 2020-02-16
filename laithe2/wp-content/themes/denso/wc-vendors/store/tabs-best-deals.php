<?php

$loop = denso_get_products( array(), 'on_sale', 1, 10, '', '', array(), array(), $vendor_id );
if ( $loop->have_posts() ):
?>

	<div class="products">
		<div class="widget-content woocommerce">
			<div class="carousel-wrapper">
				<?php wc_get_template( 'layout-products/carousel.php' , array( 'loop' => $loop, 'columns' => 7, 'rows' => 1 ) ); ?>
			</div>
		</div>
	</div>
<?php else: ?>
	<?php esc_html_e( 'No products', 'denso' ); ?>
<?php endif;