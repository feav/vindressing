<?php
/**
* ------------------------------------------------------------------------------------------------
* Pricing tables shortcodes
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'adiva_shortcode_pricing_tables' ) ) {
	function adiva_shortcode_pricing_tables( $atts = array(), $content = null ) {
		$output = $autoplay = '';
		extract(shortcode_atts( array(
			'el_class' => ''
		), $atts ));

        $classes = array();
        if ( isset( $el_class ) && $el_class != '' ) {
            $classes[] = esc_attr($el_class);
        }

		ob_start(); ?>
			<div class="pricing-tables-wrapper">
				<div class="pricing-tables <?php echo implode(' ', $classes); ?>" >
					<?php echo do_shortcode( $content ); ?>
				</div>
			</div>
		<?php
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}

if( ! function_exists( 'adiva_shortcode_pricing_plan' ) ) {
	function adiva_shortcode_pricing_plan( $atts, $content ) {
		global $wpdb, $post;
		if( ! function_exists( 'wpb_getImageBySize' ) ) return;
		$output = '';
		extract(shortcode_atts( array(
			'name'          => '',
			'price_value'   => '',
			'price_suffix'  => 'per month',
			'currency'      => '',
			'features_list' => '',
			'label'         => '',
			'label_color'   => 'red',
			'link'          => '',
			'button_label'  => '',
			'style'         => 'default',
			'best_option'   => '',
			'el_class'      => ''
		), $atts ));

        $classes = array();

        if ( isset( $el_class ) && $el_class != '' ) {
            $classes[] = esc_attr($el_class);
        }

		if( isset( $label ) && ! empty( $label ) ) {
			$classes[] = 'price-with-label label-color-' . $label_color;
		}

		if( $best_option == 'yes' ) {
			$classes[] = 'actived';
		}

        if( isset($style) && $style != '' ) {
			$classes[] = 'price-style-' . esc_attr($style);
		}

		$features = explode(PHP_EOL, $features_list);

		ob_start(); ?>

			<div class="adiva-price-table <?php echo implode(' ', $classes); ?>">
				<div class="adiva-plan">
					<div class="adiva-plan-name">
						<span class="adiva-plan-title"><?php echo esc_html($name); ?></span>
					</div>
				</div>
				<div class="adiva-plan-inner">
					<?php if ( ! empty( $label ) ): ?>
						<div class="price-label"><span><?php echo esc_html($label); ?></span></div>
					<?php endif ?>
					<div class="adiva-plan-price">
						<?php if ( $currency ): ?>
							<span class="adiva-price-currency">
								<?php echo esc_html($currency); ?>
							</span>
						<?php endif ?>

						<?php if ( $price_value ): ?>
							<span class="adiva-price-value">
								<?php echo esc_html($price_value); ?>
							</span>
						<?php endif ?>

						<?php if ( $price_suffix ): ?>
							<span class="adiva-price-suffix">
								<?php echo esc_html($price_suffix); ?>
							</span>
						<?php endif ?>
					</div>
					<?php if ( !empty( $features[0] ) ): ?>
						<div class="adiva-plan-features">
							<?php foreach ($features as $value): ?>
								<div class="adiva-plan-feature">
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

	                                echo wp_kses($value, $allowed_html);
									?>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endif ?>
					<div class="adiva-plan-footer">
						<?php if ( $button_label ): ?>
							<a href="<?php echo esc_url( $link ); ?>" class="button price-plan-btn"><?php echo esc_html($button_label); ?></a>
						<?php endif ?>
					</div>
				</div>
			</div>

		<?php
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}
