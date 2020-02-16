<?php

// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'adiva_shortcode_products' ) ) {
	function adiva_shortcode_products( $atts, $content = null ) {
		$output = '';

		extract( shortcode_atts( array(
            'product_design'      => 'grid',
            'filter'              => '',
			'product_type'        => 'all',
            'orderby'             => 'title',
            'order'               => 'ASC',
            'id'                  => '',
			'sku'                 => '',
            'cat_id'              => '',
			'enable_countdown' 	  => '',
            'total_items'         => '12',
            'columns'             => '4',
            'slider'              => '',
			'product_style'       => '1',
            'items_spacing'       => '40',
            'items_desktop'       => '4',
            'items_small_desktop' => '4',
            'items_tablet'        => '3',
            'items_mobile'        => '2',
            'items_small_mobile'  => '1',
            'navigation'          => 'yes',
            'pagination'          => 'no',
            'autoplay'            => 'no',
            'loop'                => 'no',
            'css_animation'       => '',
			'el_class'            => '',
			'css'                 => '',
		), $atts ) );

        global $woocommerce_loop, $adiva_loop;

		if ( WC()->version < '3.3.0' ) {
			$woocommerce_loop['columns'] = $columns;
		} else{
			wc_set_loop_prop( 'columns', $columns );
		}

		$woocommerce_loop['is_shortcode'] = true;
		$woocommerce_loop['product_style']    = $product_style;
		$woocommerce_loop['product_design']   = $product_design;
		$woocommerce_loop['enable_countdown'] = $enable_countdown;
		$woocommerce_loop['items_spacing']    = $items_spacing;

        $rd_number = rand();

		$classes = array('adiva-products');

		if ( isset($el_class) && $el_class != '' ) {
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

		if ( ! empty( $items_spacing ) ) {
			$attr_slider[] = '"margin": ' . esc_attr($items_spacing);
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
			$dataCarousel[] = 'data-carousel=\'{"selector": ".product-carousel-'. $rd_number .'", ' . esc_attr( implode( ', ', $attr_slider ) ) . '}\'';
		}

        // Global Query
        $args = array(
			'post_type'              => 'product',
            'post_status'            => 'publish',
			'posts_per_page'         => (int) $total_items,
			'no_found_rows'          => true,
			'cache_results'          => false,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
            'orderby'                => $orderby,
			'order'                  => $order,
			'meta_query'             => WC()->query->get_meta_query(),
			'tax_query'              => WC()->query->get_tax_query()
		);

        // all products
        if( isset($product_type) && ( $product_type == 'all' ) ) {
            if ( $sku != '' ) {
                $args['meta_query'][] = array(
                    'key'     => '_sku',
                    'value'   => array_map( 'trim', explode( ',', $sku ) ),
                    'compare' => 'IN'
                );
            }

            if ( $id != '' ) {
                $args['post__in'] = array_map( 'trim', explode( ',', $id ) );
            }
        }

        //recent products
        if( isset($product_type) && ( $product_type == 'recent' ) ) {
			$args['orderby'] = 'date';
			$args['order']   = 'desc';
        }

        //featured products
		if( isset($product_type) && ( $product_type == 'featured' ) ) {
			$args['tax_query'][] = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'name',
				'terms'    => 'featured',
				'operator' => 'IN',
			);
		}

		//best selling
		if( isset($product_type) && ( $product_type == 'selling' ) ) {
			$args['meta_key'] = 'total_sales';
			$args['orderby']  = 'meta_value_num';
			$args['order'] 	  = 'desc';
		}

		//top reviews
		if( isset($product_type) && ( $product_type == 'rated' ) ) {
			add_filter( 'posts_clauses', array( 'WC_Shortcodes', 'order_by_rating_post_clauses' ) );
		}

		//sale products
		if( isset($product_type) && ( $product_type=='sale' ) ) {
            $args['post__in'] = array_merge( array( 0 ), wc_get_product_ids_on_sale() );
		}

        //product by categories
        if( isset($product_type) && ( $product_type=='cat' ) ) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'id',
                    'terms'    => $cat_id,
                ),
            );
        }

        $products = new WP_Query( $args );

        ob_start(); ?>

        <div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
            <?php
                if ( $product_design == 'masonry' && $filter ) {
                    // Retrieve all the categories
                    $categories = get_terms( 'product_cat' );

                    echo '<div class="product-filter masonry-filter tc">';
                        echo '<a data-filter="*" class="active" href="javascript:void(0);">' . esc_html__( 'All', 'adiva-addons' ) . '</a>';
                        foreach ( $categories as $cat ) :
                            echo '<a data-filter=".product_cat-' . esc_attr( $cat->slug ) . '" href="javascript:void(0);">' . esc_html( $cat->name ) . '</a>';
                        endforeach;
                    echo '</div>';
                }
            ?>

            <?php if ( $products->have_posts() ) : ?>
                <?php if ( $slider == 'yes' ): ?>
                    <div class="product-carousel-<?php echo esc_attr($rd_number); ?> owl-carousel owl-theme" <?php echo implode( ' ', $dataCarousel ); ?>>
                <?php else : ?>
                    <?php woocommerce_product_loop_start(); ?>
                <?php endif; ?>

    				<?php while ( $products->have_posts() ) : $products->the_post(); ?>

    					<?php wc_get_template_part( 'content', 'product' ); ?>

    				<?php endwhile; // end of the loop. ?>

                <?php if ( $slider == 'yes' ): ?>
                    </div>
                <?php else : ?>
                    <?php woocommerce_product_loop_end(); ?>
                <?php endif; ?>
            <?php else : ?>
                <p class="woocommerce-info"><?php esc_html_e( 'No products were found matching your selection.', 'adiva-addons' ); ?></p>
            <?php endif; ?>



        </div>

		<?php
		woocommerce_reset_loop();
        wp_reset_postdata();
		adiva_reset_loop();

		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}
