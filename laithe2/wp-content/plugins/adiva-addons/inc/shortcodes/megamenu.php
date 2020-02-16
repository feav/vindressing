<?php

// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'adiva_shortcode_megamenu' ) ) {
	function adiva_shortcode_megamenu( $atts, $content = null ) {
		$output = $title = $css_animation = $el_class = $css = '';

		extract( shortcode_atts( array(
			'title'         => 'Categories',
            'nav_menu'      => '',
            'color'         => '',
			'css_animation' => '',
			'el_class'      => '',
			'css'           => '',
		), $atts ) );

        $classes = array('megamenu-widget-wrapper');

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


        ob_start(); ?>
        <div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
			<?php if ( !empty($title) ) : ?>
				<h3><?php echo esc_html($title); ?></h3>
			<?php endif; ?>
            <div class="adiva-navigation vertical-navigation">
                <?php
                    wp_nav_menu( array(
                        'fallback_cb' => '',
                        'menu'        => $nav_menu,
                    ) );
                ?>
            </div>

        </div>

        <?php
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}
