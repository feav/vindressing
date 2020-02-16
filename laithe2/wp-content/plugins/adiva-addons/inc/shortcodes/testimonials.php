<?php

/**
* ------------------------------------------------------------------------------------------------
* Testimonials shortcodes
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'adiva_shortcode_testimonials' ) ) {
	function adiva_shortcode_testimonials($atts = array(), $content = null) {
		$output = $class = '';

        extract( shortcode_atts( array(
			'title'               => '',
            'layout'              => 'slider',
            'style'               => 'standard',
            'align'               => 'center',
            'columns'             => 3,
			'color_text'          => 'dark',
            'el_class'            => '',
			'items_spacing'       => '40',
            'items_desktop'       => '1',
            'items_small_desktop' => '1',
            'items_tablet'        => '1',
            'items_mobile'        => '1',
            'items_small_mobile'  => '1',
            'navigation'          => 'no',
            'pagination'          => 'yes',
            'autoplay'            => 'no',
            'loop'                => 'no',
		), $atts ) );

		$class .= ' testimonials-' . esc_attr($layout);
		$class .= ' testimonials-style-' . esc_attr($style);
		$class .= ' testimonials-columns-' . esc_attr($columns);
		$class .= ' testimonials-align-' . esc_attr($align);
		$class .= ' testimonials-color-' . esc_attr($color_text);

        $carousel_id = 'carousel-' . rand( 1000, 10000);

		$slider_class = '';
		if( $layout == 'slider' ) {
            $slider_class .= ' ' . $carousel_id . ' owl-carousel owl-theme';
        }

		$class .= ' ' . $el_class;

		ob_start(); ?>
			<div class="jmstestimonial-box<?php echo esc_attr( $class ); ?>">
                <?php if ( isset($title) && $title != '' ): ?>
                    <div class="addon-title">
            			<h3><?php echo esc_html( $title ); ?></h3>
                    </div>
                <?php endif; ?>
				<div class="testimonials<?php echo esc_attr( $slider_class ); ?>">
					<?php echo do_shortcode( $content ); ?>
				</div>
			</div>
		<?php
		if( $layout == 'slider' ) {
			wp_add_inline_script( 'adiva-theme', adiva_testimonial_carousel_js( $atts, $carousel_id ), 'after' );
		}

		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}

if ( !function_exists('adiva_testimonial_carousel_js') ) {
	function adiva_testimonial_carousel_js( $atts, $carousel_id ) {
		$output = $class = '';

        extract( shortcode_atts( array(
			'items_spacing'       => '40',
            'items_desktop'       => '1',
            'items_small_desktop' => '1',
            'items_tablet'        => '1',
            'items_mobile'        => '1',
            'items_small_mobile'  => '1',
            'navigation'          => 'no',
            'pagination'          => 'yes',
            'autoplay'            => 'no',
            'loop'                => 'no',
		), $atts ) );

		ob_start();
		?>
			jQuery(document).ready(function($) {
				var rtl = false;
			    if ($('body').hasClass('rtl')) rtl = true;

				$('.<?php echo esc_js($carousel_id); ?>').owlCarousel({
					responsive : {
						320 : {
							items: <?php if( (int) $items_small_mobile > 0) { echo intval($items_small_mobile); } else { echo '1'; } ?>,
						},
						480 : {
							items: <?php if( (int) $items_mobile > 0) { echo intval($items_mobile); } else { echo '1'; } ?>,
						},
						768 : {
							items: <?php if( (int) $items_tablet > 0) { echo intval($items_tablet); } else { echo '1'; } ?>,
						},
						991 : {
							items: <?php if( (int) $items_small_desktop > 0) { echo intval($items_small_desktop); } else { echo '1'; } ?>,
						},
						1199 : {
							items: <?php if( (int) $items_desktop > 0) { echo intval($items_desktop); } else { echo '1'; } ?>,
						}
					},
					stagePadding: 15,
					lazyLoad : true,
					rtl: rtl,
					margin: <?php if( (int) $items_spacing > 0) { echo esc_attr($items_spacing); } else { echo '40'; } ?>,
					dots: <?php if( $pagination && $pagination == 'yes'  ) { echo 'true'; } else { echo 'false'; } ?>,
					nav: <?php if( $navigation && $navigation == 'yes' ) { echo 'true'; } else { echo 'false'; } ?>,
					autoplay: <?php if( $autoplay && $autoplay == 'yes' ) { echo 'true'; } else { echo 'false'; } ?>,
					loop: <?php if( $loop && $loop == 'yes' ) { echo 'true'; } else { echo 'false'; } ?>,
					autoplayTimeout: 5000,
					smartSpeed: 500,
					navText: ['<i class="icon-arrow prev"></i>','<i class="icon-arrow next"></i>']
			    });
			});

		<?php
		return ob_get_clean();
	}
}

if( ! function_exists( 'adiva_shortcode_testimonial' ) ) {
	function adiva_shortcode_testimonial($atts, $content) {
		if( ! function_exists( 'wpb_getImageBySize' ) ) return;
		$output = $class = '';

        extract(shortcode_atts( array(
			'image'    => '',
			'img_size' => '100x100',
			'name'     => '',
			'title'    => '',
			'el_class' => ''
		), $atts ));

		$img_id = preg_replace( '/[^\d]/', '', $image );

		$img = wpb_getImageBySize( array( 'attach_id' => $img_id, 'thumb_size' => $img_size, 'class' => 'testimonial-avatar-image' ) );

        if ( isset($el_class) && $el_class != '' ) {
            $class .= ' ' . esc_attr($el_class);
        }

		ob_start(); ?>

			<div class="testimonial-box<?php echo esc_attr( $class ); ?>" >
				<div class="testimonial-inner">
					<?php if ( $img['thumbnail'] != ''): ?>
						<div class="testimonial-avatar">
							<?php echo wp_kses( $img['thumbnail'], array( 'img' => array('class' => true,'width' => true,'height' => true,'src' => true,'alt' => true) ) );?>
						</div>
					<?php endif ?>

					<div class="testimonial-content">
						<?php echo do_shortcode( $content ); ?>
						<footer>
							<?php if ( $name ): ?>
								<span class="name"><?php echo esc_html( $name ); ?></span>
							<?php endif ?>
							<?php if ( $title ): ?>
								<span class="office"><?php echo esc_html( $title ); ?></span>
							<?php endif ?>
						</footer>
					</div>
				</div>
			</div>

		<?php
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}
