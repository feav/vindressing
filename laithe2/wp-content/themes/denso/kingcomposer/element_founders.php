<?php
$atts  = array_merge( array(
	'founders'	=> array(),
	'title' => '',
    'image' => '',
    'title_item' => '',
    'job' => '',
), $atts);
extract( $atts );

if ( !empty($founders) ) {
	?>
	<div class="widget widget-founder">
		<div class="row-30 row">
			<?php foreach ($founders as $item) { ?>
				<div class="col-xs-12 col-md-<?php echo 12/count($founders); ?>">
					<div class="item">
				        <?php if ( isset($item->image) && $item->image ): ?>
							<?php $img = wp_get_attachment_image_src($item->image, 'full'); ?>
							<?php if (isset($img[0]) && $img[0]) { ?>
						    	<img src="<?php echo esc_url_raw($img[0]);?>" alt="" />
							<?php } ?>
						<?php endif; ?>
						<?php if (isset($item->title_item) && $item->title_item): ?>
			            	<h3 class="name"><?php echo trim($item->title_item); ?></h3>
					    <?php endif ?>
					    <?php if (isset($item->job) && $item->job): ?>
			            	<div class="job"><?php echo trim($item->job); ?></div>
					    <?php endif ?>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
	<?php
}