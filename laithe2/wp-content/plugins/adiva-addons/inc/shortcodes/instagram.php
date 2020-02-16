<?php
/**
 * Instagram shortcode.
 */

// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'adiva_shortcode_instagram' ) ) {
	function adiva_shortcode_instagram( $atts, $content = null ) {
		$output = $gutter_style = $rounded = $user_id = $access_token = $limit = $columns = $gutter = $slider = $items_desktop = $items_small_desktop = $items_tablet = $items_mobile = $items_small_mobile = $navigation = $pagination = $autoplay = $loop = $el_class = $css = '';

		extract( shortcode_atts( array(
			'user_id'             => '',
			'access_token'        => '',
			'limit'               => 12,
			'columns'             => 2,
			'gutter'              => '30',
			'slider'              => 'no',
			'rounded'			  => '',
			'items_desktop'       => '6',
            'items_small_desktop' => '4',
            'items_tablet'        => '3',
            'items_mobile'        => '2',
            'items_small_mobile'  => '2',
            'navigation'          => 'yes',
            'pagination'          => 'no',
            'autoplay'            => 'no',
            'loop'                => 'no',
			'el_class'            => '',
			'css' => '',
		), $atts ) );

		$classes_wrap = array('instagram-pic-wrapper pr');

		if ( ! empty( $rounded ) ) {
			$classes_wrap[] = 'instagram-rounded';
		}

		if ( ! empty( $el_class ) ) {
			$classes_wrap[] = esc_attr($el_class);
		}

		if ( ! empty( $css ) ) {
			$classes_wrap[] = vc_shortcode_custom_css_class( $css, ' ' );
		}

		// class inner
		$classes = array( 'instagram-wrap clearfix');

		$attr = array();

		$rd_number = rand();

		if ( isset($slider) && $slider == 'yes' ) {
			if ( ! empty( $items_desktop ) ) {
				$attr_slider[] = '"itemDesktop": "' . intval($items_desktop) . '"';
			}

			if ( ! empty( $items_small_desktop ) ) {
				$attr_slider[] = '"itemSmallDesktop": "' . intval($items_small_desktop) . '"';
			}

			if ( ! empty( $items_tablet ) ) {
				$attr_slider[] = '"itemTablet": "' . intval($items_tablet) . '"';
			}

			if ( ! empty( $items_mobile ) ) {
				$attr_slider[] = '"itemMobile": "' . intval($items_mobile) . '"';
			}

			if ( ! empty( $items_small_mobile ) ) {
				$attr_slider[] = '"itemSmallMobile": "' . intval($items_small_mobile) . '"';
			}

			if ( ! empty( $gutter ) ) {
				$attr_slider[] = '"margin": ' . esc_attr($gutter);
			}

			if ( ! empty( $navigation ) ) {
				$attr_slider[] = '"navigation": true';
			} else {
				$attr_slider[] = '"navigation": false';
			}

			if ( ! empty( $pagination ) ) {
				$attr_slider[] = '"pagination": true';
			} else {
				$attr_slider[] = '"pagination": false';
			}

			if ( ! empty( $autoplay ) ) {
				$attr_slider[] = '"autoplay": true';
			} else {
				$attr_slider[] = '"autoplay": false';
			}

			if ( ! empty( $loop ) ) {
				$attr_slider[] = '"loop": true';
			} else {
				$attr_slider[] = '"loop": false';
			}

			if ( ! empty( $attr_slider ) ) {
				$dataCarousel[] = 'data-carousel=\'{"selector": ".instagram-carousel-'. intval($rd_number) .'", ' . esc_attr( implode( ', ', $attr_slider ) ) . '}\'';
			}

			$slider_wrap_classes[] = 'instagram-carousel-'. intval($rd_number) .' owl-theme owl-carousel';
		}

		if ( $gutter == '0' ) {
			$classes[] = 'no-space';
		}

		if ( isset($gutter) && $gutter != '' ) {
			$classes[] = 'layout-spacing-' . esc_attr($gutter);
		}

		if ( isset($columns) && $columns != '' ) {
			$classes[] = 'columns-' . esc_attr($columns);
		}

		ob_start();
		?>
		<div class="<?php echo esc_attr( implode( ' ', $classes_wrap ) ); ?>">
			<?php if ( ! empty( $content ) ) : ?>
				<div class="instagram-content">
					<div class="instagram-content-inner">
						<?php
							echo '' . $content;
						?>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( isset($slider) && $slider == 'yes' ) : ?>
				<div class="<?php echo esc_attr(implode( ' ', $slider_wrap_classes )); ?>" <?php echo implode( ' ', $dataCarousel ); ?>>
			<?php else : ?>
				<div class="<?php echo esc_attr(implode( ' ', $classes )); ?>">
			<?php endif; ?>

			<?php
				 if ( ! empty( $user_id ) && ! empty( $access_token ) ) {
					$api      = 'https://api.instagram.com/v1/users/' . $user_id . '/media/recent/?access_token=' . $access_token . '&count=' . esc_attr( $limit );
					$getphoto = wp_remote_get( $api );

					if ( ! is_wp_error( $getphoto ) ) {
						$photos   = json_decode( $getphoto['body'] );

						if ( $photos->meta->code !== 200 ) {
							echo '<p>'. esc_html__('Incorrect user ID specified.', 'adiva-addons') .'</p>';
						}

						$items_as_objects = $photos->data;
						$items = array();
						foreach ( $items_as_objects as $item_object ) {
							$items[] = array(
								'link'     => $item_object->link,
								'src'      => $item_object->images->standard_resolution->url,
								'comments' => $item_object->comments->count,
								'like'     => $item_object->likes->count
							 );
						}

						if ( isset( $items ) ) {
							foreach ( $items as $item ) {
								$link     = $item['link'];
								$image    = $item['src'];
								$comments = $item['comments'];
								$like     = $item['like'];

								if ( isset($slider) && $slider == 'yes' ) : ?>
									<div class="item">
								<?php else : ?>
									<div class="item mb_<?php echo esc_attr( $gutter ); ?>">
								<?php endif; ?>
	                                <div class="instagram-picture pr oh">
	    								<a href="<?php echo esc_url( $link ); ?>" target="_blank"></a>
										<img src="<?php echo esc_url( $image ); ?>" alt="Instagram" />
	    								<div class="hover-mask">
	    									<span class="instagram-like"><i class="fa fa-heart-o mr_5"></i><?php echo esc_html($like); ?></span>
	    									<span class="instagram-comment"><i class="fa fa-comments-o mr_5"></i><?php echo esc_html($comments); ?></span>
	    								</div>
	                                </div>
								</div>
								<?php
							}
						}
					}
				}
				?>
			</div>
		</div>

		<?php
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}
