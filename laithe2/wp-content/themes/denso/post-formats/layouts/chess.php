
<div class="layout-blog style-chess chess-blog">
    <?php $count = 0; while ( have_posts() ) : the_post(); ?>
        <div class="chess-item">
        	<?php if ($count%2 == 0): ?>
                <div class="chess-left">
            	   <?php get_template_part( 'post-formats/loop/list-left-image/_item' ); ?>
                </div>
            <?php else: ?>
                <div class="chess-right">
            	  <?php get_template_part( 'post-formats/loop/list-right-image/_item' ); ?>
                </div>
            <?php endif; ?>
        </div>
    <?php $count++; endwhile; ?>
</div>
