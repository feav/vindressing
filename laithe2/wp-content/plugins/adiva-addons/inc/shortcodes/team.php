<?php

// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

/**
* ------------------------------------------------------------------------------------------------
* Team member shortcode
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'adiva_shortcode_team_member' )) {
	function adiva_shortcode_team_member($atts, $content = '') {
		$output = $title = $el_class = '';
		extract( shortcode_atts( array(
	        'align'              => 'left',
            'adiva_color_scheme' => 'dark',
	        'name'               => '',
	        'position'           => '',
	        'twitter'            => '',
	        'facebook'           => '',
	        'google_plus'        => '',
	        'skype'              => '',
	        'linkedin'           => '',
	        'instagram'          => '',
	        'image'              => '',
	        'img_size'           => '270x170',
			'style'              => 'default', // circle colored
			'size'               => 'default', // circle colored
			'form'               => 'circle',
			'layout'             => 'default',
			'el_class'           => ''
		), $atts ) );

		$el_class .= ' member-layout-' . esc_attr($layout);
		$el_class .= ' color-scheme-' . esc_attr($adiva_color_scheme);

		$img_id = preg_replace( '/[^\d]/', '', $image );

		$img = wpb_getImageBySize( array( 'attach_id' => $img_id, 'thumb_size' => $img_size, 'class' => 'team-member-avatar-image' ) );

		ob_start();
		?>
	    <div class="team-member text-<?php echo esc_attr( $align ); ?> <?php echo esc_attr( $el_class ); ?>">

		    <?php if( $img['p_img_large'][0] != '') : ?>
	            <div class="member-image-wrapper">
					<div class="member-image">
						<img src="<?php echo esc_url($img['p_img_large'][0]); ?>" alt="member">
					</div>
				</div>
		    <?php endif; ?>

	        <div class="member-details">
	            <?php if($name != '') : ?>
	                <h4 class="member-name"><?php echo esc_html($name); ?></h4>
	            <?php endif; ?>

				<?php if($position != '') : ?>
				    <span class="member-position"><?php echo esc_html($position); ?></span>
			    <?php endif; ?>

				<?php if ($linkedin != '' || $twitter != '' || $facebook != '' || $skype != '' || $google_plus != '' || $instagram != '') : ?>
		            <div class="member-social"><div class="adiva-social-icons flex icons-design-<?php echo esc_attr( $style ); ?> icons-size-<?php echo esc_attr( $size ); ?> social-form-<?php echo esc_attr( $form ); ?>">
		                <?php if ($facebook != '') :  ?>
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
		            </div></div>
		        <?php endif; ?>
	    	</div>
	    </div>

		<?php
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}
