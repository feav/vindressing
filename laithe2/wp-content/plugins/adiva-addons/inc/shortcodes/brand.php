<?php

// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'adiva_shortcode_brand_carousel' ) ) {
	function adiva_shortcode_brand_carousel( $atts, $content = null ) {
		$output = $title = $description = $images = $total_items = $number_of_rows = $items_space = $items_desktop = $items_small_desktop = $items_tablet = $items_mobile = $items_small_mobile = $navigation = $pagination = $autoplay = $loop = $css_animation = $el_class = $css = '';

		extract( shortcode_atts( array(
			'images'         	  => '',
			'items_space'         => '40',
            'items_desktop'       => '6',
            'items_small_desktop' => '5',
            'items_tablet'        => '3',
            'items_mobile'        => '2',
            'items_small_mobile'  => '2',
            'navigation'          => 'no',
            'pagination'          => 'no',
            'autoplay'            => 'no',
            'loop'                => 'no',
			'css_animation'       => '',
			'el_class'            => '',
			'css' 				  => '',
		), $atts ) );

        $rd_number = rand();

		$classes = array('jmsbrand-box');

		if ( ! empty( $el_class ) ) {
			$classes[] = esc_attr($el_class);
		}

		if ( ! empty( $css ) ) {
            $classes[] = vc_shortcode_custom_css_class( $css, ' ' );
        }

		if ( '' !== $css_animation ) {
			wp_enqueue_script( 'waypoints' );
			$classes[] = 'wpb_animate_when_almost_visible wpb_' . esc_attr($css_animation) . ' wpb_start_animation ' . esc_attr($css_animation);
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
			$dataCarousel[] = 'data-carousel=\'{"selector": ".brand-carousel-'. intval($rd_number) .'", ' . esc_attr( implode( ', ', $attr_slider ) ) . '}\'';
		}

		$images_url = explode( ',', $images);

        ob_start(); ?>
        <div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
            <div class="brand-carousel-<?php echo intval($rd_number); ?> owl-carousel owl-theme" <?php echo implode( ' ', $dataCarousel ); ?>>
				<?php
				foreach($images_url as $image_url) {
					$_img = wp_get_attachment_image_src($image_url, 'full');
					$img = $_img[0];
					?>
					<div class="item tc">
						<img src="<?php echo esc_url( $img ); ?>" alt="">
					</div>
					<?php
				}
				?>
        	</div>
        </div>

		<?php
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}
