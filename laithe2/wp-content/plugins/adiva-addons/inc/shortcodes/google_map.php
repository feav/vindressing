<?php
if( ! function_exists( 'adiva_shortcode_google_map' ) ) {
	function adiva_shortcode_google_map( $atts, $content ) {
		$output = '';
		extract(shortcode_atts( array(
			'lat'                => 45.9,
			'lon'                => 10.9,
			'style_json'         => '',
			'zoom'               => 15,
			'height'             => 400,
			'scroll'             => 'no',
			'content_vertical'   => 'top',
			'content_horizontal' => 'left',
			'content_width'      => 300,
			'google_key'         => adiva_get_option( 'google_map_api_key' ),
			'el_class'           => ''
		), $atts ));

		wp_enqueue_script( 'maplace' );
		wp_enqueue_script( 'google.map.api', 'https://maps.google.com/maps/api/js?key=' . $google_key . '', array(), '', false );


		$el_class .= ' content-vertical-' . esc_attr($content_vertical);
		$el_class .= ' content-horizontal-' . esc_attr($content_horizontal);

		$id = rand(100,999);

		ob_start();

		?>

			<?php if ( ! empty( $content ) ): ?>
				<div class="google-map-container <?php echo esc_attr( $el_class ); ?> map-container-with-content" style="height:<?php echo esc_attr( $height ); ?>px;">

					<div class="adiva-google-map-wrapper">
						<div class="adiva-google-map with-content google-map-<?php echo esc_attr( $id ); ?>"></div>
					</div>
					<div class="adiva-google-map-content-wrap">
						<div class="adiva-google-map-content" style="max-width: <?php echo esc_attr( $content_width ); ?>px;">
							<?php echo do_shortcode( $content ); ?>
						</div>
					</div>

				</div>
			<?php else: ?>

				<div class="google-map-container <?php echo esc_attr( $el_class );?>"  style="height:<?php echo esc_attr( $height ); ?>px;">

					<div class="adiva-google-map-wrapper">
						<div class="adiva-google-map without-content google-map-<?php echo esc_attr( $id ); ?>"></div>
					</div>

				</div>

			<?php endif ?>
		<?php
		wp_add_inline_script( 'adiva-theme', adiva_google_map_init_js( $atts, $id ), 'after' );
		$output = ob_get_contents();
		ob_end_clean();

		return $output;


	}
}

if( ! function_exists( 'adiva_google_map_init_js' ) ) {
	function adiva_google_map_init_js( $atts, $id ) {
		$output = '';
		extract(shortcode_atts( array(
			'lat'        => 45.9,
			'lon'        => 10.9,
			'style_json' => '',
			'zoom'       => 15,
			'scroll'     => 'no',
		), $atts ));
		ob_start();
		?>
			jQuery(document).ready(function() {
				new Maplace({
					locations: [
					    {
							lat: <?php echo esc_js( $lat ); ?>,
							lon: <?php echo esc_js( $lon ); ?>,
					        icon: '<?php echo ADIVA_URL . '/assets/images/google-icon.png';  ?>' ,
					        animation: google.maps.Animation.DROP
					    }
					],
					controls_on_map: false,
				    map_div: '.google-map-<?php echo esc_js( $id ); ?>',
				    start: 1,
				    map_options: {
				        zoom: <?php echo esc_js( $zoom ); ?>,
				        scrollwheel: <?php echo ($scroll == 'yes') ? 'true' : 'false'; ?>
				    },
				    <?php if(isset($style_json) && $style_json != ''): ?>
				    styles: {
				        '<?php esc_html_e('Custom style', 'adiva-addons') ?>': <?php echo rawurldecode( adiva_decompress($style_json, true) ); ?>
				    }
				    <?php endif; ?>
				}).Load();

			});
		<?php
		return ob_get_clean();
	}
}
