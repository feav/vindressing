<?php
/**
* ------------------------------------------------------------------------------------------------
* Section divider shortcode
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'adiva_shortcode_section_divider' ) ) {
	function adiva_shortcode_section_divider( $atts ) {
		$output = '';

		extract( shortcode_atts( array(
			'position'        => 'top',
			'color'           => '#e1e1e1',
			'style'           => 'waves-small',
			'content_overlap' => '',
			'custom_height'   => '',
			'el_class'        => '',
		), $atts) );

        $folder = $file = '';

        $folder = get_template_directory() . '/assets/images/svg';
		$file   = $folder . '/' . esc_attr($style) . '-' . esc_attr($position) . '.svg';

		if( file_exists( $file ) ) {
            $svg = adiva_get_svg( $file );
        } else {
            return false;
        }

		$divider_id = 'svg-wrap-' . rand( 10, 9999 );

        $classes = array();
		$classes[] = $divider_id;

        if ( isset( $position ) && $position != '' ) {
            $classes[] = 'divider-position-' . esc_attr($position);
        }

        if ( isset( $style ) && $style != '' ) {
            $classes[] = 'divider-style-' . esc_attr($style);
        }

		if ( isset( $content_overlap ) && $content_overlap != '' ) {
            $classes[] = 'divider-overlap';
        }

        if ( isset($el_class) && $el_class != '' ) {
            $classes[] = esc_attr($el_class);
        }


		ob_start();
		?>
			<div class="adiva-row-divider <?php echo implode(' ', $classes); ?>">
				<?php echo ( $svg ); ?>
				<style>.<?php echo esc_attr( $divider_id ); ?> svg {
						fill: <?php echo esc_html( $color ); ?>;
						<?php echo ( $custom_height ) ? 'height:' . esc_html( $custom_height ) : false ; ?>
					}
				</style>
			</div>
		<?php
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}
