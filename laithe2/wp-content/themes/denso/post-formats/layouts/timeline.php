<?php
	global $wp_query;
	$month_date = '';
	$i = 0;
?>
<div class="layout-blog style-timeline">
	<div class="apus-timeline-blog"></div>
    <?php $count = 0; while ( have_posts() ) : the_post(); ?>
    	<?php
    	$date = get_the_time( 'M Y' );

    	if ( $month_date != $date ) {
    		$i = 0;
    		if ($month_date != '') {
    			?>
    			</div>
    			<?php
    		}
		?>

    		<h3 class="apus-timeline-date"><?php echo trim($date); ?></h3>
    		<div class="apus-posts-month row">
		<?php } ?>

        <div class="col-md-6 <?php echo ($i%2 == 1 ? 'right ' : 'item-first clear'); ?>">
            <?php get_template_part( 'post-formats/content', get_post_format() ); ?>
        </div>

        <?php if ( $month_date == $date && $count == ($wp_query->post_count - 1) ) { ?>
			</div>
		<?php } ?>

    <?php $month_date = $date; $count++; $i++; endwhile; ?>
</div>