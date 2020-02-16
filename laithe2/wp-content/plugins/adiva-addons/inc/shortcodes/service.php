<?php
/**
 * Service shortcode.
 */

// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'adiva_shortcode_service' ) ) {
	function adiva_shortcode_service( $atts, $content = null ) {
		$output = $icon = $icon_style = $title = $entry = $service_size = $icon_color = $title_color = $content_color = $css_animation = $icon_color_attr = $title_color_attr = $content_color_attr = $el_class = $css = '';

		extract( shortcode_atts( array(
			'alignment'	    => 'top',
			'icon'          => '',
			'icon_style'    => '',
			'title'         => '',
			'entry'         => '',
            'service_size'  => '',
			'icon_color'    => '',
			'title_color'   => '',
			'content_color' => '',
			'css_animation' => '',
			'el_class'      => '',
            'css'			=> ''
		), $atts ) );

		$classes = array();

        if ( ! empty( $css ) ) {
            $classes[] = vc_shortcode_custom_css_class( $css, ' ' );
        }

        if ( '' !== $css_animation ) {
			wp_enqueue_script( 'waypoints' );
			$classes[] = 'wpb_animate_when_almost_visible wpb_' . $css_animation . ' wpb_start_animation ' . $css_animation;
		}

		if ( isset($service_size) && ! empty( $service_size ) ) {
			$classes[] = 'service-size-' . esc_attr($service_size);
		}

		if ( isset($alignment) && ! empty( $alignment ) ) {
			$classes[] = 'service-align-' . esc_attr($alignment);
		}

        if ( ! empty( $el_class ) ) {
			$classes[] = esc_attr($el_class);
		}

		if ( ! empty( $css ) ) {
            $classes[] = vc_shortcode_custom_css_class( $css, ' ' );
        }

		ob_start();
		?>
		<div class="service-box <?php echo esc_attr( implode( ' ', $classes ) ); ?>">
			<div class="service-box-icon">
				<i class="<?php echo esc_attr( $icon ); ?>"></i>
			</div>
			<div class="service-box-content">
				<h3 class="title"><?php echo esc_html($title); ?></h3>
				<?php
					$allowed_html = array(
						'a' => array(
							'href' => array(),
							'title' => array()
						),
						'br' => array(),
						'em' => array(),
						'strong' => array(),
					);
				?>
				<p><?php echo wp_kses($entry, $allowed_html); ?></p>
			</div>
		</div>

		<style>
			<?php if ( isset($icon_color) && $icon_color != '' ): ?>
				.service-box .service-box-icon {
					color: <?php echo esc_attr( $icon_color ); ?>;
				}
			<?php endif ?>

			<?php if ( isset($title_color) && $title_color != '' ): ?>
				.service-box .service-box-content {
					color: <?php echo esc_attr( $title_color ); ?>;
				}
			<?php endif; ?>

			<?php if ( isset($content_color) && $content_color != '' ): ?>
				.service-box p {
					color: <?php echo esc_attr( $content_color ); ?>;
				}
			<?php endif; ?>
		</style>

		<?php
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}
