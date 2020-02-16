<?php

$atts  = array_merge( array(
    'title' => 'title',
	'category' => '',
	'number' => 7,
	'layout_type' => 'layout1',
	'description' => '',
	'image' => '',
	'icon' => '',
	'image_icon' => '',
), $atts);
extract( $atts );

$term = get_term_by( 'slug', $category, 'product_cat' );
if ( $term ) {
	$args = array(
        'taxonomy'     => 'product_cat',
        'child_of'     => 0,
        'hide_empty'   => false,
        'parent'       => $term->term_id,
        'number'       => $number,
    );
    $sub_cats = get_categories( $args );
	?>
	<div class="widget widget-category-info <?php echo esc_attr($layout_type); ?>">
		<?php if ( $layout_type == 'layout1' ) { ?>
			<div class="header-info">
				<div class="row">
					<div class="col-md-8">
						<?php if ($title) { ?>
							<h3><a href="<?php echo esc_url(get_term_link($term, 'product_cat')); ?>"><?php echo trim($title); ?></a></h3>
						<?php } else { ?>
							<h3><a href="<?php echo esc_url(get_term_link($term, 'product_cat')); ?>"><?php echo trim($term->name); ?></a></h3>
						<?php } ?>
						<?php if ($description) { ?>
							<div class="description"><?php echo trim($description); ?></div>
						<?php } ?>
					</div>
					<div class="col-md-4">
						<?php $img = wp_get_attachment_image_src($image, 'full'); ?>
						<?php if (isset($img[0]) && $img[0]) { ?>
			    			<a href="<?php echo esc_url(get_term_link($term, 'product_cat')); ?>"><?php denso_display_image($img); ?></a>
						<?php } ?>
					</div>
				</div>
			</div>
			<?php if ( $sub_cats && !empty($sub_cats)) { ?>
				<div class="sub-categories">
                    <ul class="list-unstyled category-info-list clearfix">
                        <?php foreach ( $sub_cats as $cat) { ?>
                            <li class="category-info-list-item">
                                <a href="<?php echo esc_url( get_term_link( $cat->slug, 'product_cat') ); ?>">
                                    <?php echo trim( $cat->name ); ?>
                                </a>
                            </li>
                        <?php } ?>
                        <li class="category-info-list-item view-more">
                        	<a href="<?php echo esc_url( get_term_link( $term->term_id, 'product_cat' ) ); ?>">
                                <?php echo esc_html__( 'View more', 'denso' ); ?>
                            </a>
                        </li>
                    </ul>
                </div>
            <?php } ?>
			
		<?php } elseif($layout_type == 'layout2') { ?>
			<div class="header-info clearfix">
				<div class="header-icon pull-left">
					<?php if ($image_icon) {
						$img = wp_get_attachment_image_src($image_icon, 'full');
						if (isset($img[0]) && $img[0]) { ?>
		    				<?php denso_display_image($img); ?>
		    			<?php } ?>
					<?php } else { ?>
						<i class="<?php echo esc_attr($icon); ?>"></i>
					<?php } ?>
				</div>
				<div class="pull-left">
					<?php if ($title) { ?>
						<h3><a href="<?php echo esc_url(get_term_link($term, 'product_cat')); ?>"><?php echo trim($title); ?></a></h3>
					<?php } else { ?>
						<h3><a href="<?php echo esc_url(get_term_link($term, 'product_cat')); ?>"><?php echo trim($term->name); ?></a></h3>
					<?php } ?>
				</div>
			</div>

			<?php if ( $sub_cats && !empty($sub_cats)) { ?>
				<div class="sub-categories clearfix">
                    <ul class="list-unstyled category-info-list">
                        <?php foreach ( $sub_cats as $cat) { ?>
                            <li class="category-info-list-item">
                                <a href="<?php echo esc_url( get_term_link( $cat->slug, 'product_cat') ); ?>">
                                    <?php echo trim( $cat->name ); ?>
                                </a>
                            </li>
                        <?php } ?>
                        <li class="category-info-list-item view-more">
                        	<a href="<?php echo esc_url( get_term_link( $term->term_id, 'product_cat' ) ); ?>">
                                <?php echo esc_html__( 'View more', 'denso' ); ?>
                            </a>
                        </li>
                    </ul>
                </div>
            <?php } ?>
		<?php } else { ?>
			<div class="row">
				<div class="col-md-5 col-xs-5">
					<?php $img = wp_get_attachment_image_src($image, 'full'); ?>
					<?php if (isset($img[0]) && $img[0]) { ?>
		    			<a href="<?php echo esc_url(get_term_link($term, 'product_cat')); ?>"><?php denso_display_image($img); ?></a>
					<?php } ?>
				</div>
				<div class="col-md-7 col-xs-7">
					<?php if ($title) { ?>
						<h3><a href="<?php echo esc_url(get_term_link($term, 'product_cat')); ?>"><?php echo trim($title); ?></a></h3>
					<?php } else { ?>
						<h3><a href="<?php echo esc_url(get_term_link($term, 'product_cat')); ?>"><?php echo trim($term->name); ?></a></h3>
					<?php } ?>
					<?php if ( $sub_cats && !empty($sub_cats)) { ?>
						<div class="sub-categories clearfix">
		                    <ul class="list-unstyled category-info-list">
		                        <?php foreach ( $sub_cats as $cat) { ?>
		                            <li class="category-info-list-item">
		                                <a href="<?php echo esc_url( get_term_link( $cat->slug, 'product_cat') ); ?>">
		                                    <?php echo trim( $cat->name ); ?>
		                                </a>
		                            </li>
		                        <?php } ?>
		                        <li class="category-info-list-item view-more">
		                        	<a href="<?php echo esc_url( get_term_link( $term->term_id, 'product_cat' ) ); ?>">
		                                <?php echo esc_html__( 'Shop All', 'denso' ); ?>
		                            </a>
		                        </li>
		                    </ul>
		                </div>
		            <?php } ?>
				</div>
			</div>
		<?php } ?>
	</div>
	<?php
}