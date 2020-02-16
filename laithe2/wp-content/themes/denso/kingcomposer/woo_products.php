<?php

$atts  = array_merge( array(
	'number'  => 8,
	'columns'	=> 4,
	'type'		=> 'recent_products',
	'categories'	=> '',
	'layout_type' => 'grid',
	'rows' => 1,
	'topcarousel' => 0,
), $atts);
extract( $atts );

if ( empty($type) ) {
	return ;
}
$categories = apus_themer_multiple_fields_to_array_helper($categories);
$loop = denso_get_products( $categories, $type, 1, $number );

$slick_nav_style = ($item_style == 'list' || $item_style == 'list-v1' || $topcarousel == 1 ? 'slick-small slick-small-top' : '');
$nb_extra = ($item_style == 'list' || $item_style == 'list-v1' ? 1 : 2);
?>
<div class="widget widget-<?php echo esc_attr($layout_type.' '.$item_style); ?> widget-products  products <?php echo ($item_style == 'list' || $item_style == 'list-v1' ? 'special-nav' : ''); ?>">
	<div class="widget-title-wrapper">
		<?php if ( !empty($title) ) { ?>
		    <h3 class="widget-title"><?php echo trim($title); ?></h3>
	    <?php } ?>
    </div>
	<?php if ( $loop->have_posts() ) : ?>
		<div class="widget-content woocommerce">
			<div class="<?php echo esc_attr( $layout_type ); ?>-wrapper">
				<?php wc_get_template( 'layout-products/'.$layout_type.'.php' , array( 'loop' => $loop, 'columns' => $columns, 'rows' => $rows , 'product_item' => $item_style, 'slick_nav_style' => $slick_nav_style, 'nb_extra' => $nb_extra ) ); ?>
			</div>
		</div>
	<?php endif; ?>
</div>