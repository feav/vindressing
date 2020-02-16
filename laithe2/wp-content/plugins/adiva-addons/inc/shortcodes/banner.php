<?php

// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'adiva_shortcode_banner' ) ) {
	function adiva_shortcode_banner( $atts, $content = null ) {
		$output = $title = $title_size = $title_color = $banner_style = $title_google_fonts = $subtitle = $subtitle_size = $image = $box_align = $el_class = $subtitle_style = $title_style = $css = '';

		extract( shortcode_atts( array(
			'image'                    => '',
			'title'                    => '',
			'subtitle'                 => '',
			'subsubtitle'              => '',
			'link'                     => '',
			'hover_effect'			   => 'zoom',
			'content_width' 		   => '100',
			'banner_style'			   => '',
			'title_size'               => '',
			'title_lineheight'         => '',
			'title_color'              => '',
			'title_use_theme_fonts'    => '',
			'title_google_fonts'       => '',
			'subtitle_size'            => '',
			'subtitle_lineheight'      => '',
			'subtitle_color'           => '',
			'subtitle_use_theme_fonts' => '',
			'subtitle_google_fonts'    => '',
			'subsubtitle_size'            => '',
			'subsubtitle_lineheight'      => '',
			'subsubtitle_color'           => '',
			'subsubtitle_use_theme_fonts' => '',
			'subsubtitle_google_fonts'    => '',
			'btn_text' => '',
			'btn_link' 		 => '',
			'css_animation'            => '',
			'box_align'                => '',
			'vertical_alignment'	   => '',
			'el_class'                 => '',
			'css'                      => ''
		), $atts ) );

		$classes = array( 'banner-box pr oh' );

		if ( ! empty( $el_class ) ) {
			$classes[] = esc_attr($el_class);
		}

		if ( ! empty( $hover_effect ) ) {
			$classes[] = 'banner-hover-' . esc_attr($hover_effect);
		}

		if ( !empty( $vertical_alignment ) ) {
			$classes[] = 'banner-vertical-' . esc_attr($vertical_alignment);
		}

		if ( !empty( $banner_style ) ) {
			$classes[] = 'banner-' . esc_attr($banner_style);
		}


		if ( ! empty( $css ) ) {
            $classes[] = vc_shortcode_custom_css_class( $css, ' ' );
        }

		if ( '' !== $css_animation ) {
			wp_enqueue_script( 'waypoints' );
			$classes[] = 'wpb_animate_when_almost_visible wpb_' . esc_attr($css_animation) . ' wpb_start_animation ' . esc_attr($css_animation);
		}

		// box content
		$textwrap_class = array();

		if ( isset( $box_align ) ) {
			$textwrap_class[] = esc_attr($box_align);
		}

		$google_fonts_obj = new Vc_Google_Fonts();
		$google_fonts_field_settings = isset( $google_fonts_field['settings'], $google_fonts_field['settings']['fields'] ) ? $google_fonts_field['settings']['fields'] : array();

		// Title style
		$title_style = array();

		$title_google_fonts_data = strlen( $title_google_fonts ) > 0 ? $google_fonts_obj->_vc_google_fonts_parse_attributes( $google_fonts_field_settings, $title_google_fonts ) : '';

		if ( ( ! isset( $title_use_theme_fonts ) || 'yes' !== $title_use_theme_fonts ) && ! empty( $title_google_fonts_data ) && isset( $title_google_fonts_data['values'], $title_google_fonts_data['values']['font_family'], $title_google_fonts_data['values']['font_style'] ) ) {
			$title_google_fonts_family = explode( ':', $title_google_fonts_data['values']['font_family'] );
			$title_style[] = 'font-family:' . $title_google_fonts_family[0];
			$title_google_fonts_styles = explode( ':', $title_google_fonts_data['values']['font_style'] );
			$title_style[] = 'font-weight:' . $title_google_fonts_styles[1];
			$title_style[] = 'font-style:' . $title_google_fonts_styles[2];
		}

		if ( '' !== $title_size ) {
			$title_style[] = 'font-size: '. esc_attr($title_size) .'px';
		}

		if ( '' !== $title_lineheight ) {
			$title_style[] = 'line-height: '. esc_attr($title_lineheight);
		}

		if ( '' !== $title_color ) {
			$title_style[] = 'color: '. $title_color;
		}


		// Subtitle style
		$subtitle_style = array();

		$subtitle_google_fonts_data = strlen( $subtitle_google_fonts ) > 0 ? $google_fonts_obj->_vc_google_fonts_parse_attributes( $google_fonts_field_settings, $subtitle_google_fonts ) : '';

		if ( ( ! isset( $subtitle_use_theme_fonts ) || 'yes' !== $subtitle_use_theme_fonts ) && ! empty( $subtitle_google_fonts_data ) && isset( $subtitle_google_fonts_data['values'], $subtitle_google_fonts_data['values']['font_family'], $subtitle_google_fonts_data['values']['font_style'] ) ) {
			$subtitle_google_fonts_family = explode( ':', $subtitle_google_fonts_data['values']['font_family'] );
			$subtitle_style[] = 'font-family:' . $subtitle_google_fonts_family[0];
			$subtitle_google_fonts_styles = explode( ':', $subtitle_google_fonts_data['values']['font_style'] );
			$subtitle_style[] = 'font-weight:' . $subtitle_google_fonts_styles[1];
			$subtitle_style[] = 'font-style:' . $subtitle_google_fonts_styles[2];
		}

		if ( '' !== $subtitle_size ) {
			$subtitle_style[] = 'font-size: '. esc_attr($subtitle_size) .'px';
		}

		if ( '' !== $subtitle_lineheight ) {
			$subtitle_style[] = 'line-height: '. esc_attr($subtitle_lineheight);
		}

		if ( '' !== $subtitle_color ) {
			$subtitle_style[] = 'color: '. $subtitle_color;
		}

		// SubSubtitle style
		$subsubtitle_style = array();

		$subsubtitle_google_fonts_data = strlen( $subsubtitle_google_fonts ) > 0 ? $google_fonts_obj->_vc_google_fonts_parse_attributes( $google_fonts_field_settings, $subsubtitle_google_fonts ) : '';

		if ( ( ! isset( $subsubtitle_use_theme_fonts ) || 'yes' !== $subsubtitle_use_theme_fonts ) && ! empty( $subsubtitle_google_fonts_data ) && isset( $subsubtitle_google_fonts_data['values'], $subsubtitle_google_fonts_data['values']['font_family'], $subsubtitle_google_fonts_data['values']['font_style'] ) ) {
			$subsubtitle_google_fonts_family = explode( ':', $subsubtitle_google_fonts_data['values']['font_family'] );
			$subsubtitle_style[] = 'font-family:' . $subsubtitle_google_fonts_family[0];
			$subsubtitle_google_fonts_styles = explode( ':', $subsubtitle_google_fonts_data['values']['font_style'] );
			$subsubtitle_style[] = 'font-weight:' . $subsubtitle_google_fonts_styles[1];
			$subsubtitle_style[] = 'font-style:' . $subsubtitle_google_fonts_styles[2];
		}

		if ( '' !== $subsubtitle_size ) {
			$subsubtitle_style[] = 'font-size: '. esc_attr($subsubtitle_size) .'px';
		}

		if ( '' !== $subsubtitle_lineheight ) {
			$subsubtitle_style[] = 'line-height: '. esc_attr($subsubtitle_lineheight);
		}

		if ( '' !== $subsubtitle_color ) {
			$subsubtitle_style[] = 'color: '. $subsubtitle_color;
		}


		// Link
		$onclick = '';
		if ( ! empty( $link ) ) {
			$banner_url = vc_build_link( $link );
			$onclick = 'onclick="window.location.href=\''. esc_url( $banner_url['url'] ).'\'"';
		}

		$allowed_html = array(
			'a' => array(
				'href' => array(),
				'title' => array()
			),
			'span' => array(
				'class' => array()
			),
			'br' => array(),
			'em' => array(),
			'strong' => array(),
		);

		ob_start(); ?>

		<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" <?php echo '' . $onclick; ?>>
			<?php if ( ! empty($image) ): ?>
				<div class="banner-image">
					<?php
					    $img_id = preg_replace( '/[^\d]/', '', $image );
					    $image  = wpb_getImageBySize( array( 'attach_id' => $img_id ) );
					?>
					<img src="<?php echo esc_url( $image['p_img_large'][0] ); ?>" width="<?php echo esc_attr( $image['p_img_large'][1] ); ?>" height="<?php echo esc_attr( $image['p_img_large'][2] ); ?>" alt="<?php echo esc_attr( $title ); ?>" />
				</div>
			<?php endif; ?>

			<?php if ( !empty($title) || !empty($subtitle) || !empty($subsubtitle)) : ?>
		        <div class="banner-text <?php echo esc_attr( implode( ' ', $textwrap_class ) ); ?>">
		            <div class="banner-inner content-width-<?php echo esc_attr( $content_width ); ?>">
		                <div class="content">
							<?php if ( !empty($title) ) : ?>
								<div class="title">
									<p style="<?php echo esc_attr( implode( '; ', $title_style ) ); ?>"><?php echo wp_kses($title, $allowed_html); ?></p>
								</div>
							<?php endif; ?>

							<?php if ( !empty($subtitle) ) : ?>
								<div class="subtitle">
									<p style="<?php echo esc_attr( implode( '; ', $subtitle_style ) ); ?>"><?php echo wp_kses($subtitle, $allowed_html); ?></p>
								</div>
							<?php endif; ?>

							<?php if ( !empty($subsubtitle) ) : ?>
								<div class="subsubtitle">
									<p style="<?php echo esc_attr( implode( '; ', $subsubtitle_style ) ); ?>"><?php echo wp_kses($subsubtitle, $allowed_html); ?></p>
								</div>
							<?php endif; ?>

							<?php if( ! empty( $btn_text ) ) : ?>
								<div class="btn-wrapper">
									<a href="<?php echo esc_url($btn_link); ?>" class="btn btn-color-primary btn-style-default btn-size-default font-size-17 fw-400"><?php echo esc_html($btn_text); ?></a>
								</div>
							<?php endif; ?>
		                </div>
		            </div>
		        </div>

		    <?php endif; ?>
		</div>

		<?php
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}
