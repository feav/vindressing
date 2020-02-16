<?php
$style = isset($style) ? $style : '';
$columns = isset($columns) ? $columns : 4;
$small_cols = $columns <= 1 ? 1 : 2;
$rows = (isset($rows) && ((int)$rows > 0)) ? (int)$rows : 1;

?>
<div id="special-product-carousel" class="carousel slide" data-interval="false">
	<div class="carousel-inner" role="listbox">
	    <?php $count = 0; while ( $loop->have_posts() ): $loop->the_post(); global $product; ?>
	    	<div class="item <?php echo ($count == 0 ? ' active' : ''); ?>">
		        <div class="products-grid product">
		            <?php wc_get_template( 'item-product/inner-special.php',array('style' => $style) ); ?>
		        </div>
	        </div>
	    <?php $count++; endwhile; ?>
	</div>
	<div class="nav-wrapper">
		<a class="left carousel-control" href="#special-product-carousel" role="button" data-slide="prev">
			<span class="mn-icon-164" aria-hidden="true"></span>
		</a>
		<a class="right carousel-control" href="#special-product-carousel" role="button" data-slide="next">
			<span class="mn-icon-165" aria-hidden="true"></span>
		</a>
  </div>
</div> 
<?php wp_reset_postdata(); ?>