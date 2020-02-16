<?php
/**
* ------------------------------------------------------------------------------------------------
* Buttons shortcode
* ------------------------------------------------------------------------------------------------
*/
if ( ! function_exists( 'adiva_shortcode_buttons' ) ) {
	function adiva_shortcode_buttons( $atts, $content = null ) {
		$output = $use_link = '';

        extract( shortcode_atts( array(
			'title'         => 'BUTTON',
			'link'          => '',
			'color'         => 'default',
			'style'         => 'default',
			'size'          => 'default',
			'align'         => 'center',
			'button_inline' => 'no',
			'full_width'    => 'no',
            'css_animation' => '',
			'el_class'      => '',
            'css' 			=> ''
		), $atts) );

        $link = ( '||' === $link ) ? '' : $link;
        $link = vc_build_link( $link );

        $use_link = false;
        if ( strlen( $link['url'] ) > 0 ) {
        	$use_link = true;
        	$a_href = $link['url'];
        	$a_href = apply_filters( 'vc_btn_a_href', $a_href );
        	$a_title = $link['title'];
        	$a_title = apply_filters( 'vc_btn_a_title', $a_title );
        	$a_target = $link['target'];
        	$a_rel = $link['rel'];
        }

        $attributes = array();

        if ( $use_link ) {
        	$attributes[] = 'href="' . trim( $a_href ) . '"';
        	$attributes[] = 'title="' . esc_attr( trim( $a_title ) ) . '"';
        	if ( ! empty( $a_target ) ) {
        		$attributes[] = 'target="' . esc_attr( trim( $a_target ) ) . '"';
        	}
        	if ( ! empty( $a_rel ) ) {
        		$attributes[] = 'rel="' . esc_attr( trim( $a_rel ) ) . '"';
        	}
        }

        $classes = array('adiva-button-wrapper');

        if ( isset($align) && $align != '' ) $classes[] = 'text-' . esc_attr($align);
        if( $button_inline == 'yes' ) $classes[] = 'btn-inline';

		if ( '' !== $css_animation ) {
			wp_enqueue_script( 'waypoints' );
			$classes[] = 'wpb_animate_when_almost_visible wpb_' . $css_animation . ' wpb_start_animation ' . $css_animation;
		}

        $btn_class = 'btn';
		$btn_class .= ' btn-color-' . esc_attr($color);
		$btn_class .= ' btn-style-' . esc_attr($style);
		$btn_class .= ' btn-size-' . esc_attr($size);
        if( $full_width == 'yes' ) $btn_class .= ' btn-full-width';
		if( isset($el_class) && $el_class != '' ) $btn_class .= ' ' . esc_attr($el_class);
		ob_start();
		?>

        <div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
			<a <?php echo implode( ' ', $attributes ); ?> class="<?php echo esc_attr($btn_class); ?>"><?php echo esc_html( $title ); ?></a>
		</div>

		<?php
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}
