<?php
/**
* ------------------------------------------------------------------------------------------------
* Adiva google maps shortcode
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'adiva_vc_map_google_map' ) ) {
	function adiva_vc_map_google_map() {
        vc_map( array(
			'name'              => esc_html__( 'Google Map', 'adiva-addons' ),
			'base'              => 'adiva_google_map',
			'as_parent'         => array('except' => 'testimonial'),
			'content_element'   => true,
		    'js_view'           => 'VcColumnView',
            'category'          => esc_html__( 'JMS Addons', 'adiva-addons' ),
            'icon'              => 'jms-icon',
			'params'            => array(
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Latitude (required)', 'adiva-addons' ),
					'param_name' => 'lat',
					'description' => wp_kses( __('You can use <a href="http://universimmedia.pagesperso-orange.fr/geo/loc.htm" target="_blank">this service</a> to get coordinates of your location.', 'adiva-addons'), array(
                        'a' => array(
                            'href' => array(),
                            'target' => array()
                        )
                    ) )
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Longitude (required)', 'adiva-addons' ),
					'param_name' => 'lon'
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Google API key (required)', 'adiva-addons' ),
					'param_name' => 'google_key',
					'description' => wp_kses( __('Obtain API key <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">here</a> to use our Google Map VC element.', 'adiva-addons'), array(
                        'a' => array(
                            'href' => array(),
                            'target' => array()
                        )
                    ) )
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Zoom', 'adiva-addons' ),
					'param_name' => 'zoom',
					'description' => esc_html__('Zoom level when focus the marker 0 - 19', 'adiva-addons'),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Height', 'adiva-addons' ),
					'param_name' => 'height',
					'description' => esc_html__('Default: 400', 'adiva-addons')
				),
				array(
					'type' => 'textarea_raw_html',
					'heading' => esc_html__( 'Styles (JSON)', 'adiva-addons' ),
					'param_name' => 'style_json',
					'description' => wp_kses( __('Styled maps allow you to customize the presentation of the standard Google base maps, changing the visual display of such elements as roads, parks, and built-up areas.<br>
You can find more Google Maps styles on the website: <a target="_blank" href="http://snazzymaps.com/">Snazzy Maps</a>Just copy JSON code and paste it here.', 'adiva-addons'), array(
                        'a' => array(
                            'href' => array(),
                            'target' => array()
                        )
                    ) )
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Zoom with mouse wheel', 'adiva-addons' ),
					'param_name' => 'scroll',
					'value' => array(
						'' => '',
						esc_html__( 'Yes', 'adiva-addons' ) => 'yes',
						esc_html__( 'No', 'adiva-addons' ) => 'no',
					),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Content on the map vertical position', 'adiva-addons' ),
					'param_name' => 'content_vertical',
					'value' => array(
						'' => '',
						esc_html__( 'Top', 'adiva-addons' ) => 'top',
						esc_html__( 'Middle', 'adiva-addons' ) => 'middle',
						esc_html__( 'Bottom', 'adiva-addons' ) => 'bottom',
					),
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Content on the map horizontal position', 'adiva-addons' ),
					'param_name' => 'content_horizontal',
					'value'      => array(
						'' => '',
						esc_html__( 'Left', 'adiva-addons' ) => 'left',
						esc_html__( 'Center', 'adiva-addons' ) => 'center',
						esc_html__( 'Right', 'adiva-addons' ) => 'right',
					),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Content width', 'adiva-addons' ),
					'param_name'  => 'content_width',
					'description' => esc_html__('Default: 300', 'adiva-addons')
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Extra class name', 'adiva-addons' ),
					'param_name'  => 'el_class',
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'adiva-addons' )
				)
			),
		));
	}
	add_action( 'vc_before_init', 'adiva_vc_map_google_map' );
}
