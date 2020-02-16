<?php

$atts  = array_merge( array(
    'title' => '',
    'advanced' => 'no',
	'number' => 3,
	'orderby' => '',
	'orderway' => '',
	'product_special' => '',
	'style' => 'style1',
), $atts);
extract( $atts );

if ( $advanced == 'no' ) {
	$loop = denso_get_products( array(), 'on_sale', 1, $number );
} elseif ( !empty($product_special) )  {
	$pids = denso_autocomplete_options_helper($product_special);
	if (is_array($pids) && !empty($pids)) {
		$loop = denso_get_products( array(), 'on_sale', 1, -1, '' , '', $pids );
	}
}
if ( isset($loop) && $loop->have_posts() ) {
?>
	<div class="widget widget-products-special widget-products products <?php echo esc_attr($style); ?>">
		<div class="widget-title-wrapper">
	        <?php if ( !empty($title) ) { ?>
	        <h3 class="widget-title"><?php echo trim($title); ?></h3>
	        <?php } ?>
	    </div>
	    <div class="widget-content woocommerce">
	    	<?php wc_get_template( 'layout-products/carousel-special.php' , array( 'loop' => $loop, 'columns' => 1, 'product_item' => 'inner-special' ,'style' => $style) ); ?>
	    </div>
	</div>
<?php
}
