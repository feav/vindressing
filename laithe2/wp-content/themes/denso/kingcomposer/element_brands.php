<?php
$atts  = array_merge( array(
    'title' => 'title',
	'columns' => 8,
	'rows' => 1,
	'number' => 8,
	'style' => 'style1'
), $atts);
extract( $atts );

if ( class_exists('Denso_Woo_Custom') ) {
	$brands = Denso_Woo_Custom::get_brands($number);

	if ( !empty($brands) ) {
		?>
		<div class="widget widget-brands <?php echo esc_attr($style); ?>">
			<?php if ($title) { ?>
				<h3 class="widget-title"><?php echo esc_attr($title); ?></h3>
			<?php } ?>
			<div class="widget-content">
				<div class="slick-carousel <?php echo ($style != 'style1')?'slick-small slick-small-top':''; ?>" data-carousel="slick" data-items="<?php echo esc_attr($columns); ?>" data-smallmedium="2" data-extrasmall="2" data-pagination="false" data-nav="true" <?php echo (count($brands) > $columns ? 'data-infinite="true"' : ''); ?> data-rows="<?php echo esc_attr($rows); ?>">
					<?php foreach ($brands as $brand) {
						$link = get_term_link($brand->term_id);
						if ( !is_wp_error( $link ) ) { ?>
						<div class="item">
							<a href="<?php echo esc_url($link); ?>" title="<?php echo esc_attr($brand->name); ?>">
								<?php
									$image = get_woocommerce_term_meta( $brand->term_id, 'product_brand_image', true );
									if ($image) {
								?>
									<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr($brand->name); ?>" />
								<?php } ?>
							</a>
						</div>
					<?php } ?>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php
	}
}