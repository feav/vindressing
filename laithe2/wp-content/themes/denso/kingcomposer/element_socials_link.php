<?php

$atts  = array_merge( array(
	'socials'	=> array(),
	'title' => '',
	'style' => '',
), $atts);
extract( $atts );

if (!empty($socials)) {
?>
<div class="widget widget-social <?php echo esc_attr($style); ?>">
	<?php if ($title) { ?>
        <h3 class="widget-title"><?php echo esc_attr($title); ?></h3>
    <?php } ?>
    <div class="widget-content">
		<ul class="social list-inline">
		    <?php foreach( $socials as $item): ?>
                <li>
                    <a href="<?php echo esc_url($item->url);?>" title="<?php echo esc_attr($item->title); ?>">
                        <?php if ( isset($item->image) && $item->image ): ?>
							<?php $img = wp_get_attachment_image_src($item->image, 'full'); ?>
							<?php if (isset($img[0]) && $img[0]) { ?>
						    	<img src="<?php echo esc_url_raw($img[0]);?>" alt="" />
							<?php } ?>
						<?php elseif (isset($item->icon) && $item->icon): ?>
			            	<i class="<?php echo esc_attr($item->icon); ?>"></i>
					    <?php endif ?>
                    </a>
                </li>
		    <?php endforeach; ?>
		</ul>
	</div>
</div>
<?php } ?>