<?php

// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'adiva_shortcode_product_tab' ) ) {
	function adiva_shortcode_product_tab( $atts, $content = null ) {
		$output = $items_space = $orderby = $order = $total_items = $number_of_rows = $items_desktop = $items_small_desktop = $items_tablet = $items_mobile = $items_small_mobile = $navigation = $pagination = $autoplay = $loop = $css_animation = $el_class = $css = '';

		extract( shortcode_atts( array(
			'orderby'                => 'title',
            'order'                  => 'desc',
            'total_items'            => '12',
			'product_style'			 => '1',
			'number_of_rows'         => '1',
			'items_space' 			 => '40',
            'items_desktop'          => '4',
            'items_small_desktop'    => '3',
            'items_tablet'           => '2',
            'items_mobile'           => '2',
            'items_small_mobile'     => '1',
            'navigation'             => 'yes',
            'pagination'             => 'no',
            'autoplay'               => 'no',
            'loop'                   => 'no',
			'css_animation'          => '',
			'el_class'               => '',
			'css' => ''
		), $atts ) );

		global $woocommerce_loop;

        $rd_number = rand();

		$classes = array('jmsproduct-tab');

		if ( ! empty( $el_class ) ) {
			$classes[] = esc_attr($el_class);
		}

		if ( ! empty( $css ) ) {
            $classes[] = vc_shortcode_custom_css_class( $css, ' ' );
        }

		if ( '' !== $css_animation ) {
			wp_enqueue_script( 'waypoints' );
			$classes[] = 'wpb_animate_when_almost_visible wpb_' . $css_animation . ' wpb_start_animation ' . $css_animation;
		}

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

		if ( isset($loop) && $loop == 'yes'  ) {
			$attr_slider[] = '"loop": true';
		} else {
			$attr_slider[] = '"loop": false';
		}

		if ( ! empty( $attr_slider ) ) {
			$dataCarousel[] = 'data-carousel=\'{"selector": ".producttab-carousel", ' . esc_attr( implode( ', ', $attr_slider ) ) . '}\'';
		}


        // Global Query
        $args = array(
            'post_type'           => 'product',
            'post_status'         => 'publish',
            'ignore_sticky_posts' => 1,
            'posts_per_page'      => intval($total_items),
            'meta_query'          => WC()->query->get_meta_query(),
            'tax_query'           => WC()->query->get_tax_query()
        );

        //recent products
        $recent_args = $args;
        $recent_products = new WP_Query( apply_filters( 'woocommerce_shortcode_products_query', $recent_args, $atts ) );

        //featured products
        $featured_args				= $args;
        $featured_args['tax_query'][]	= array(
            'taxonomy' => 'product_visibility',
            'field'    => 'name',
            'terms'    => 'featured',
            'operator' => 'IN',
        );

        $featured_products = new WP_Query( apply_filters( 'woocommerce_shortcode_products_query', $featured_args, $atts ) );

        //best selling
        $best_selling_args				= $args;
        $best_selling_args['meta_key'] 	= 'total_sales';
        $best_selling_args['orderby'] 	= 'meta_value_num';
        $best_selling_products = new WP_Query( apply_filters( 'woocommerce_shortcode_products_query', $best_selling_args, $atts ) );

        //sale products
        $product_ids_on_sale = wc_get_product_ids_on_sale();
        $sale_args				= $args;
        $sale_args['post__in'] 	= array_merge( array( 0 ), $product_ids_on_sale );
        $sale_products = new WP_Query( apply_filters( 'woocommerce_shortcode_products_query', $sale_args, $atts ) );

        ob_start(); ?>
        <div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">

            <div class="nav-wrap">
                <?php $t = 0;?>
                <ul class="nav nav-tabs">
                    <?php if ( $recent_products->have_posts() ) :?>
                        <li <?php echo  $t == 0 ? 'class="active"': '' ?>>
                            <a href="#tab-new" data-toggle="tab"><?php esc_html_e( 'NOUVELLES ARRIVÉES', 'adiva-addons' ); ?></a>
                        </li>
                        <?php $t++;
                    endif;
                    if ( $featured_products->have_posts() ) :?>
                        <li <?php echo  $t == 0 ? 'class="active"': '' ?>>
                            <a href="#tab-featured" data-toggle="tab"><?php esc_html_e( 'Featured', 'adiva-addons' ); ?></a>
                        </li><?php $t++;
                    endif;
                    if ( $best_selling_products->have_posts() ) :?>
                        <li <?php echo  $t == 0 ? 'class="active"': '' ?>>
                            <a href="#tab-bestseller" data-toggle="tab"><?php esc_html_e( 'MEILLEURE VENTE','adiva-addons' );?></a>
                        </li><?php $t++;
                    endif;

                    if ( $sale_products->have_posts() ) :?>
                        <li <?php echo  $t == 0 ? 'class="active"': '' ?>>
                            <a href="#tab-onsale" data-toggle="tab"><?php esc_html_e( 'EN VENTE','adiva-addons' );?></a>
                        </li><?php $t++;
                    endif;
                    ?>
                </ul>
            </div>

            <div class="tab-content">
                <?php $p = 0;
                if ( $recent_products->have_posts() ) :?>
					<?php $woocommerce_loop = $atts; ?>
                    <div class="tab-pane fade<?php echo  $p == 0 ? ' in active': '' ?>" id="tab-new">
                        <div class="owl-carousel owl-theme producttab-carousel" <?php echo implode( ' ', $dataCarousel ); ?>>
                            <?php $row = 1; ?>
                            <?php while ( $recent_products->have_posts() ) : $recent_products->the_post(); ?>
                                <?php if( $row == 1 ) : ?>
                                    <div class="item-wrap">
                                <?php endif; ?>
                                    <?php wc_get_template_part( 'content', 'product' ); ?>
                                <?php if( $row == (int) $atts['number_of_rows'] || $recent_products->current_post+1 == $recent_products->post_count) { $row = 0; ?>
                                    </div>
                                <?php } $row++;?>
                            <?php endwhile; // end of the loop.
                            wp_reset_postdata(); ?>
                        </div>
                    </div>
                    <?php $p++; ?>
                <?php endif;
                if ( $featured_products->have_posts() ) :?>
					<?php $woocommerce_loop = $atts; ?>
                    <div class="tab-pane fade<?php echo  $p == 0 ? ' in active': '' ?>" id="tab-featured">
                        <div class="owl-carousel owl-theme producttab-carousel" <?php echo implode( ' ', $dataCarousel ); ?>>
                            <?php $row = 1; ?>
                            <?php while ( $featured_products->have_posts() ) : $featured_products->the_post(); ?>
								<?php if( $row == 1 ) : ?>
                                    <div class="item-wrap">
                                <?php endif; ?>
                                    <?php wc_get_template_part( 'content', 'product' ); ?>
                                <?php if($row == (int) $atts['number_of_rows'] || $featured_products->current_post+1 == $featured_products->post_count) { $row=0; ?>
                                    </div>
                                <?php } $row++;?>
                            <?php endwhile; // end of the loop.
                            wp_reset_postdata(); ?>
                        </div>
                    </div>
                    <?php $p++; ?>
                <?php endif;
                if ( $best_selling_products->have_posts() ) :?>
					<?php $woocommerce_loop = $atts; ?>
                    <div class="tab-pane fade<?php echo  $p == 0 ? ' in active': '' ?>" id="tab-bestseller">
                        <div class="owl-carousel owl-theme producttab-carousel" <?php echo implode( ' ', $dataCarousel ); ?>>
                            <?php $row = 1; ?>
                            <?php while ( $best_selling_products->have_posts() ) : $best_selling_products->the_post(); ?>
								<?php if( $row == 1 ) : ?>
                                    <div class="item-wrap">
                                <?php endif; ?>
                                    <?php wc_get_template_part( 'content', 'product' ); ?>
                                <?php if($row == (int) $atts['number_of_rows'] || $best_selling_products->current_post+1 == $best_selling_products->post_count) { $row=0; ?>
                                    </div>
                                <?php } $row++;?>
                            <?php endwhile; // end of the loop.
                            wp_reset_postdata(); ?>
                        </div>
                    </div>
                    <?php $p++; ?>
                <?php endif; ?>

                <?php if ( $sale_products->have_posts() ) :?>
					<?php $woocommerce_loop = $atts; ?>
                    <div class="tab-pane fade<?php echo  $p == 0 ? ' in active': '' ?>" id="tab-onsale">
                        <div class="owl-carousel owl-theme producttab-carousel" <?php echo implode( ' ', $dataCarousel ); ?>>
                            <?php $row = 1; ?>
                            <?php while ( $sale_products->have_posts() ) : $sale_products->the_post(); ?>
								<?php if( $row == 1 ) : ?>
                                    <div class="item-wrap">
                                <?php endif; ?>
                                    <?php wc_get_template_part( 'content', 'product' ); ?>
                                <?php if($row == (int) $atts['number_of_rows'] || $sale_products->current_post+1 == $sale_products->post_count) { $row=0; ?>
                                    </div>
                                <?php } $row++;?>
                            <?php endwhile; // end of the loop.
                            wp_reset_postdata(); ?>
                        </div>
                    </div>
                    <?php $p++; ?>
                <?php endif; ?>
            </div>
        </div>

		<?php
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}