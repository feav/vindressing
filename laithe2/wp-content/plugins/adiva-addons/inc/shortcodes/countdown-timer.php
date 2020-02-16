<?php
/**
* ------------------------------------------------------------------------------------------------
* Countdown timer
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'adiva_shortcode_countdown_timer' )) {
	function adiva_shortcode_countdown_timer($atts, $content) {
		if( ! function_exists( 'wpb_getImageBySize' ) ) return;

		$click = $output = '';

        extract(shortcode_atts( array(
			'date'         => '2018/03/12',
			'color_scheme' => 'dark',
			'size'         => 'medium',
			'align'        => 'center',
			'style'        => 'base',
			'el_class'     => ''
		), $atts ));

        $classes = array('adiva-countdown-timer');

		if ( isset( $color_scheme ) && $color_scheme != '' ) {
            $classes[] = 'color-scheme-' . esc_attr( $color_scheme );
        }

        if ( isset( $align ) && $align != '' ) {
            $classes[] = 'text-' . esc_attr( $align );
        }

        if ( isset( $size ) && $size != '' ) {
            $classes[] = 'countdown-size-' . esc_attr( $size );
        }

        if ( isset( $style ) && $style != '' ) {
            $classes[] = 'countdown-style-' . esc_attr( $style );
        }

		if ( isset( $el_class ) && $el_class != '' ) {
            $classes[] = esc_attr( $el_class );
        }

		$timezone = 'GMT';

		$date = str_replace( '/', '-', $date );

		ob_start(); ?>
			<div class="<?php echo esc_attr( implode(' ', $classes) ); ?>">
				<div class="adiva-countdown" data-end-date="<?php echo esc_attr( $date ) ?>" data-timezone="<?php echo esc_attr( $timezone ) ?>"></div>
			</div>
		<?php
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}
