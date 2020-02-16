<?php

$atts  = array_merge( array(
	'columns'	=> 4,
	'layout_type' => 'grid',
	'title' => '',
), $atts);
extract( $atts );

$bcol = 12/$columns;
if ($columns == 5) {
	$bcol = 'cus-5';
}
if ( !empty($testimonials) && is_array($testimonials) ):
?>
	<div class="widget widget-testimonials">
		<?php if ($title) { ?>
			<h3 class="widget-title"><?php echo esc_attr($title); ?></h3>
		<?php } ?>
	    <div class="widget-content">
    		<?php if ( $layout_type == 'carousel' ): ?>
    			<div class="slick-carousel slick-small slick-small-top" data-carousel="slick" data-items="<?php echo esc_attr($columns); ?>" data-pagination="true" data-nav="true" data-smallmedium="1" data-extrasmall="1">
		    		<?php foreach ($testimonials as $testimonial) { ?>
				        <div class="testimonials-body">
						   <div class="testimonials-profile">
						   		<div class="description"><?php echo trim($testimonial->content); ?></div>
						      	<div class="media">
						         	<div class="testimonial-avatar media-left">
							            <?php if (isset($testimonial->image) && $testimonial->image): ?>
											<?php $img = wp_get_attachment_image_src($testimonial->image, 'full'); ?>
											<?php apus_themer_display_image($img); ?>
										<?php endif; ?>
						         	</div>
						         	<div class="testimonial-meta media-body">
							            <div class="info">
							               	<h3 class="name-client"> <?php echo trim($testimonial->name); ?></h3>
							               	<div class="job"><?php echo trim($testimonial->job); ?></div>   
							            </div>
						         	</div>
						      	</div>
						   </div> 
						</div>

		    		<?php } ?>
	    		</div>
	    	<?php else: ?>
	    		<div class="row">
		    		<?php foreach ($testimonials as $testimonial) { ?>
				        <div class="testimonials-body">
						   <div class="testimonials-profile">
						   		<div class="description"><?php echo trim($testimonial->content); ?></div>
						      	<div class="media">
						         	<div class="testimonial-avatar media-left">
							            <?php if (isset($testimonial->image) && $testimonial->image): ?>
											<?php $img = wp_get_attachment_image_src($testimonial->image, 'full'); ?>
											<?php apus_themer_display_image($img); ?>
										<?php endif; ?>
						         	</div>
						         	<div class="testimonial-meta media-body">
							            <div class="info">
							               	<h3 class="name-client"> <?php echo trim($testimonial->name); ?></h3>
							               	<div class="job"><?php echo trim($testimonial->job); ?></div>   
							            </div>
						         	</div>
						      	</div>
						   </div> 
						</div>

		    		<?php } ?>
	    		</div>
	    	<?php endif; ?>
	    </div>
	</div>
<?php endif; ?>