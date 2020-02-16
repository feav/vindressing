<?php
$product_item = isset($product_item) ? $product_item : 'inner';
$bcol = 12/$columns;
?>
<div class="special <?php echo ($columns <= 1) ? 'w-products-list' : 'products products-grid';?>">
	<div class="row">
		<?php $count = 0; while ( $loop->have_posts() ) : $loop->the_post(); global $product; ?>
			<?php if ($count == 0) { ?>
				<div class="col-md-4">
					<?php wc_get_template_part( 'item-product/inner-v1' ); ?>
				</div>
			<?php } else { ?>
				<?php if ($count == 1) { ?>
					<div class="col-md-8">
						<div class="row">
				<?php } ?>
							<div class="col-md-<?php echo esc_attr($bcol); ?> col-xs-6">
								<?php wc_get_template_part( 'item-product/'.$product_item ); ?>
							</div>
				<?php if ( $count == ($loop->post_count - 1) ) { ?>
						</div>
					</div>
				<?php } ?>
			<?php } ?>
		<?php $count++; endwhile; ?>
	</div>
</div>
<?php wp_reset_postdata(); ?>