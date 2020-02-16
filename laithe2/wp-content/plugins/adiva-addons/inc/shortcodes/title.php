<?php

// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'adiva_shortcode_title' ) ) {
	function adiva_shortcode_title( $atts, $content = null ) {
		$output = $subtitle_class = '';

		extract( shortcode_atts( array(
			'title'          => 'Custom Title',
			'subtitle'       => '',
			'desc'           => '',
			'subtitle_style' => 'default',
			'align'          => 'center',
			'size'           => 'medium',
			'color'			 => 'default',
			'css_animation'  => '',
			'el_class'       => '',
            'css'   => ''
		), $atts ) );

        $classes = array( 'addon-title' );

		// Align
		if ( isset( $align ) && $align != '' ) {
			$classes[] = 'text-' . esc_attr($align);
		}

		// Color
		if ( isset( $color ) && $color != '' ) {
			$classes[] = 'title-color-' . esc_attr($color);
		}

		// Title size
		if ( isset( $size ) && $size != '' ) {
			$classes[] = 'title-size-' . esc_attr($size);
		}

		// Custom class
		if ( isset( $el_class ) && $el_class != '' ) {
			$classes[] = esc_attr($el_class);
		}

		if ( ! empty( $css ) ) {
            $classes[] = vc_shortcode_custom_css_class( $css, ' ' );
        }

		if ( '' !== $css_animation ) {
			wp_enqueue_script( 'waypoints' );
			$classes[] = 'wpb_animate_when_almost_visible wpb_' . $css_animation . ' wpb_start_animation ' . $css_animation;
		}

		//subtitle class
		$subtitle_class .= ' style-'. esc_attr($subtitle_style);

		ob_start();

		?>
		<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
			<?php if ( !empty($subtitle) ) : ?>
				<div class="subtitle <?php echo esc_attr( $subtitle_class ); ?>"><?php echo esc_html( $subtitle ); ?></div>
			<?php endif; ?>

			<div class="line-wrap"><span class="left-line"></span><h3 class="title"><?php echo esc_html($title); ?></h3><span class="right-line"></span></div>

			<?php if ( !empty($desc) ) : ?>
				<div class="description"><?php echo esc_html($desc); ?></div>
			<?php endif; ?>

		</div>

		<?php
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}
