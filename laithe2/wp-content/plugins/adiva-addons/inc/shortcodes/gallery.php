<?php
if( ! function_exists( 'adiva_shortcode_gallery' ) ) {
    function adiva_shortcode_gallery( $atts, $content = null ) {
        $output = $size = $spacing_padding = $spacing_margin = '';

		extract( shortcode_atts( array(
			'images'         	  => '',
            'img_size'            => 'full',
            'gallery_type'        => 'grid',
            'on_click'            => 'lightbox',
            'spacing'             => '10',
            'columns'             => '3',
			'css_animation'       => '',
			'el_class'            => '',
			'css' 				  => '',
		), $atts ) );

		$classes = array( 'adiva-gallery' );

        if ( ! empty( $gallery_type ) ) {
            $classes[] = 'gallery-design-' . esc_attr( $gallery_type );
        }

        if( 'lightbox' === $on_click ) {
            wp_enqueue_script( 'prettyphoto' );
    		wp_enqueue_style( 'prettyphoto' );
		}


        if ( ! empty( $el_class ) ) {
            $classes[] = esc_attr( $el_class );
        }

        if ( ! empty( $css ) ) {
            $classes[] = vc_shortcode_custom_css_class( $css, ' ' );
        }

        if ( '' !== $css_animation ) {
            wp_enqueue_script( 'waypoints' );
            $classes[] = 'wpb_animate_when_almost_visible wpb_' . esc_attr($css_animation) . ' wpb_start_animation ' . esc_attr($css_animation);
        }

        $images = explode(',', $images);

        if (isset($img_size) && $img_size != '') {
            $size = $img_size;
        }

        ob_start(); ?>
        <div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
            <div class="gallery-wrapper columns-<?php echo esc_attr( $columns ); ?> layout-spacing-<?php echo esc_attr( $spacing ); ?>">
                <?php if ( count($images) > 0 ): ?>
                    <?php $i=0; foreach ($images as $img_id):
                        $i++;
                        $attachment = get_post( $img_id );
                        $title = trim( strip_tags( $attachment->post_title ) );
                        $img = wpb_getImageBySize( array( 'attach_id' => $img_id, 'thumb_size' => $img_size, 'class' => 'adiva-gallery-image image-' . $i ) );
                        $link = $img['p_img_large']['0'];
                        if( 'links' === $on_click ) {
                            $link = (isset( $custom_links[$i-1] ) ? $custom_links[$i-1] : '' );
                        }
                        ?>
                        <div class="item tc mb_<?php echo esc_attr( $spacing ); ?>">
                            <?php if ( $on_click != 'none' ): ?>
                            <a href="<?php echo esc_url( $link ); ?>" class="prettyphoto" data-rel="prettyPhoto[rel-<?php echo get_the_ID(); ?>]" data-index="<?php echo esc_attr( $i ); ?>" data-width="<?php echo esc_attr( $img['p_img_large']['1'] ); ?>" data-height="<?php echo esc_attr( $img['p_img_large']['2'] ); ?>">
                            <?php endif ?>
                                <?php echo wp_kses( $img['thumbnail'], array( 'img' => array('class' => true,'width' => true,'height' => true,'src' => true,'alt' => true) ) );?>
                            <?php if ( $on_click != 'none' ): ?>
                            </a>
                            <?php endif ?>
                        </div>
                    <?php endforeach ?>
                <?php endif ?>

        	</div>
        </div>
        <?php
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
    }
}
