<?php

$atts  = array_merge( array(
	'image' => '',
	'text_position' => '',
	'title' => '',
	'subtitle' => '',
	'description' => '',
	'button_text' => '',
	'button_link' => '',
), $atts);
extract( $atts );
$img = wp_get_attachment_image_src($image, 'full');
?>
<div class="widget widget-banner text_position-<?php echo esc_attr($text_position); ?>">
	<?php 
		if (isset($img[0]) && $img[0]) {
	?>
		<div class="images">
			<img src="<?php echo esc_url($img[0]); ?>" alt="">
		</div>
	<?php } ?>
	<div class="banner-body">
		<?php if ( $subtitle ) { ?>
			<div class="sub"><?php echo trim($subtitle); ?></div>
		<?php } ?>		
		<?php if ( $title ) { ?>
			<h3 class="title"><?php echo trim($title); ?></h3>
		<?php } ?>
		<?php if ( $description ) { ?>
			<div class="banner-description"><?php echo trim($description); ?></div>
		<?php } ?>
		<?php if ( $button_link ) { ?>
			<a href="<?php echo esc_url($button_link); ?>" title="<?php echo esc_attr($button_text); ?>" class="btn btn-default">
				<?php echo trim($button_text); ?>
			</a>
		<?php } ?>
	</div>
</div>