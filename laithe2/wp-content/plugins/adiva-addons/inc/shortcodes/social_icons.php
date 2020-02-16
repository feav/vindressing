<?php
/**
* ------------------------------------------------------------------------------------------------
* Team member shortcode
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'adiva_shortcode_social_icons' )) {
	function adiva_shortcode_social_icons($atts, $content = '') {
		$output = '';
		extract( shortcode_atts( array(
            'twitter'     => '',
	        'facebook'    => '',
	        'google_plus' => '',
	        'skype'       => '',
	        'linkedin'    => '',
	        'instagram'   => '',
	        'align'       => 'left',
			'style'       => 'default',
			'size'        => 'default',
			'form'        => 'circle',
			'layout'      => 'default',
			'el_class'    => ''
		), $atts ) );

        $classes = array('adiva-social-icons flex');

        if ($el_class != '') $classes[] = $el_class;
        if ($align != '') $classes[] = 'icon-align-' . esc_attr( $align );
        if ($style != '') $classes[] = 'icon-design-' . esc_attr( $style );
        if ($size != '') $classes[] = 'icons-size-' . esc_attr( $size );
        if ($form != '') $classes[] = 'social-form-' . esc_attr( $form );

		ob_start();

        if ($linkedin != '' || $twitter != '' || $facebook != '' || $skype != '' || $google_plus != '' || $instagram != '') : ?>
            <div class="<?php echo implode(' ', $classes); ?>">
                <?php if ($facebook != '') : ?>
                    <div class="adiva-social-icon social-facebook"><a href="<?php echo esc_url( $facebook ); ?>"><i class="fa fa-facebook"></i></a></div>
                <?php endif; ?>
                <?php if ($twitter != '') : ?>
                    <div class="adiva-social-icon social-twitter"><a href="<?php echo esc_url( $twitter ); ?>"><i class="fa fa-twitter"></i></a></div>
                <?php endif; ?>
                <?php if ($google_plus != '') : ?>
                    <div class="adiva-social-icon social-google-plus"><a href="<?php echo esc_url( $google_plus ); ?>"><i class="fa fa-google-plus"></i></a></div>
                <?php endif; ?>
                <?php if ($linkedin != '') : ?>
                    <div class="adiva-social-icon social-linkedin"><a href="<?php echo esc_url( $linkedin ); ?>"><i class="fa fa-linkedin"></i></a></div>
                <?php endif; ?>
                <?php if ($skype != '') : ?>
                    <div class="adiva-social-icon social-skype"><a href="<?php echo esc_url( $skype ); ?>"><i class="fa fa-skype"></i></a></div>
                <?php endif; ?>
                <?php if ($instagram != '') : ?>
                    <div class="adiva-social-icon social-instagram"><a href="<?php echo esc_url( $instagram ); ?>"><i class="fa fa-instagram"></i></a></div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

		<?php
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}
