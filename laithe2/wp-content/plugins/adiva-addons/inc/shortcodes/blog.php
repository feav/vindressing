<?php

// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'adiva_shortcode_blog_carousel' ) ) {
	function adiva_shortcode_blog_carousel( $atts, $content = null ) {
		$output = $orderby = $order = $excerpt = $total_items = $number_of_rows = $items_desktop = $items_small_desktop = $items_tablet = $items_mobile = $items_small_mobile = $navigation = $pagination = $autoplay = $loop = $css_animation = $el_class = $css = '';

		extract( shortcode_atts( array(
			'orderby'             => 'title',
            'order'               => 'desc',
            'total_items'         => '6',
            'number_of_rows'      => '1',
			'img_size'         	  => 'medium',
            'show_author'         => '',
            'show_category'       => '',
            'show_comment'        => 'yes',
			'show_date'       	  => 'yes',
            'show_excerpt'        => 'yes',
            'excerpt'             => '20',
			'items_space'		  => '40',
            'items_desktop'       => '3',
            'items_small_desktop' => '3',
            'items_tablet'        => '2',
            'items_mobile'        => '2',
            'items_small_mobile'  => '1',
            'navigation'          => 'yes',
            'pagination'          => 'no',
            'autoplay'            => 'no',
            'loop'                => 'no',
			'css_animation'       => '',
			'el_class'            => '',
			'css' 				  => ''
		), $atts ) );

        $rd_number = rand();

		$classes = array('jmsblog-box');

		if ( ! empty( $el_class ) ) {
			$classes[] = esc_attr( $el_class );
		}

		if ( '' !== $css_animation ) {
			wp_enqueue_script( 'waypoints' );
			$classes[] = 'wpb_animate_when_almost_visible wpb_' . esc_attr($css_animation) . ' wpb_start_animation ' . esc_attr($css_animation);
		}

        $sticky = get_option( 'sticky_posts' );

		// attr slider
		$attr_slider = $dataCarousel = array();

		if ( ! empty( $items_desktop ) ) {
			$attr_slider[] = '"itemDesktop": "' . intval($items_desktop) . '"';
		}

		if ( ! empty( $items_small_desktop ) ) {
			$attr_slider[] = '"itemSmallDesktop": "' . intval($items_small_desktop) . '"';
		}

		if ( ! empty( $items_tablet ) ) {
			$attr_slider[] = '"itemTablet": "' . intval($items_tablet) . '"';
		}

		if ( ! empty( $items_mobile ) ) {
			$attr_slider[] = '"itemMobile": "' . intval($items_mobile) . '"';
		}

		if ( ! empty( $items_small_mobile ) ) {
			$attr_slider[] = '"itemSmallMobile": "' . intval($items_small_mobile) . '"';
		}

		if ( ! empty( $items_space ) ) {
			$attr_slider[] = '"margin": ' . esc_attr($items_space);
		}

		if ( isset($navigation) && $navigation == 'yes'  ) {
			$attr_slider[] = '"navigation": true';
		} else {
			$attr_slider[] = '"navigation": false';
		}

		if ( isset($pagination) && $pagination == 'yes'  ) {
			$attr_slider[] = '"pagination": true';
		} else {
			$attr_slider[] = '"pagination": false';
		}

		if ( isset($autoplay) && $autoplay == 'yes'  ) {
			$attr_slider[] = '"autoplay": true';
		} else {
			$attr_slider[] = '"autoplay": false';
		}

		if ( isset($loop) && $loop == 'yes' ) {
			$attr_slider[] = '"loop": true';
		} else {
			$attr_slider[] = '"loop": false';
		}

		if ( ! empty( $attr_slider ) ) {
			$dataCarousel[] = 'data-carousel=\'{"selector": ".blog-carousel-'. intval($rd_number) .'", ' . esc_attr( implode( ', ', $attr_slider ) ) . '}\'';
		}

        $args = array(
        	'posts_per_page' => ( !empty($total_items) && intval($total_items) ) ? $total_items : 3,
        	'orderby'        => $orderby,
        	'order'          => $order,
            'post__not_in'   => $sticky,
            'ignore_sticky_posts' => 1,
        	'post_type'      => 'post',
        	'post_status'    => 'publish',
        );

        $posts = new WP_Query( $args );

        ob_start(); ?>
        <div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
            <?php if ( $posts->have_posts() ) : ?>
                <div class="blog-carousel-<?php echo intval($rd_number); ?> owl-carousel owl-theme" <?php echo implode( ' ', $dataCarousel ); ?>>
                    <?php
            		$row = 1;
            		while ( $posts->have_posts() ) : $posts->the_post();
            			if($row == 1) : ?>
                            <div class="item-wrap">
                        <?php endif; ?>

						<article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-post-loop blog-design-slider blog-style-flat' ); ?>>
							<div class="article-inner">
								<?php if ( has_post_thumbnail() && ! post_password_required() && ! is_attachment() ) : ?>
									<header class="entry-header pr">
										<figure class="entry-thumbnail">
											<div class="post-img-wrap">
												<a href="<?php echo esc_url( get_permalink() ); ?>">
													<?php
													if ( $img_size != '' ) {
														echo adiva_get_post_thumbnail( $img_size );
													} else {
														echo the_post_thumbnail();
													}
													?>
												</a>
											</div>
										</figure>
									</header><!-- .entry-header -->
								<?php endif; ?>

						        <div class="article-body-container">
									<?php if( isset($show_author) || isset($show_category) || isset($show_date) || isset($show_comment) ) : ?>
						                <ul class="blog-meta">
						                    <?php if( isset($show_author) && $show_author == 'yes' ) : ?>
						                        <li>
						                            <i class="fa fa-user"></i>
						                            <a href="<?php echo esc_url(get_author_posts_url( get_the_author_meta('ID'))); ?>"><?php echo esc_html( get_the_author() ); ?></a>
						                        </li>
						                    <?php endif; ?>
						                    <?php if( isset($show_category) && $show_category == 'yes' ) : ?>
						                        <li><i class="fa fa-folder-o"></i><?php echo the_category( ', ', 'single' ); ?></li>
											<?php endif; ?>
											<?php if( isset($show_date) && $show_date == 'yes' ) : ?>
												<li><i class="fa fa-calendar-o"></i><span class="time updated"> <?php echo get_the_date(); ?></span></li>
											<?php endif; ?>
						                    <?php if( isset($show_comment) && $show_comment == 'yes' ) : ?>
						                        <li>
						                            <i class="fa fa-eye"></i>
						                            <a href="<?php echo get_comments_link(); ?>"><?php echo get_comments_number(get_the_ID()) . ' ' . esc_html__( 'comments', 'adiva-addons' ); ?></a>
						                        </li>
						                    <?php endif; ?>
						                </ul>
									<?php endif; ?>

						            <h3 class="entry-title">
						                <a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark"><?php the_title(); ?></a>
						            </h3>

									<?php if( isset($show_excerpt) && $show_excerpt == 'yes' ) : ?>
						                <div class="blog-excerpt">
						                    <p><?php adiva_post_excerpt( intval($excerpt) ); ?></p>
						                </div>
						            <?php endif; ?>

						        </div>


							</div>
						</article><!-- #post-# -->


            			<?php if( $row == (int) $number_of_rows || $posts->current_post+1 == $posts->post_count) : ?>
                            <?php $row = 0; ?>
            				</div>
            			<?php endif; ?>
                    <?php $row++;
            		endwhile; // end of the loop.
					wp_reset_postdata();
                    ?>
            	</div>
            <?php else : ?>
                <p class="alert alert-danger"><?php esc_html_e( 'No posts were found matching your selection.', 'adiva-addons' ); ?></p>
            <?php endif; ?>
        </div>

		<?php
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}
