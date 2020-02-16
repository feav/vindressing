<?php
/**
* ------------------------------------------------------------------------------------------------
* Section divider element map
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'adiva_vc_map_row_divider' ) ) {
	function adiva_vc_map_row_divider() {
		vc_map( array(
			'name'     => esc_html__( 'Section divider', 'adiva-addons'),
			'base'     => 'adiva_divider',
            'icon'     => 'jms-icon',
            'category' => esc_html__( 'JMS Addons', 'adiva-addons' ),
			'params'   => array(
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Position', 'adiva-addons' ),
					'param_name' => 'position',
                    'admin_label' => true,
					'value'      => array(
						esc_html__( 'Top', 'adiva-addons' )    => 'top',
						esc_html__( 'Bottom', 'adiva-addons' ) => 'bottom'
					)
				),
				array(
					'type'       => 'checkbox',
					'heading'    => esc_html__( 'Overlap', 'adiva-addons' ),
					'param_name' => 'content_overlap',
					'value'      => array( esc_html__( 'Enable', 'adiva-addons' ) => 'enable' )
				),
				array(
					'type'       => 'colorpicker',
					'heading'    => esc_html__( 'Color', 'adiva-addons' ),
					'param_name' => 'color',
                    'admin_label' => true,
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Style', 'adiva-addons' ),
					'param_name' => 'style',
                    'admin_label' => true,
					'value'      => array(
						esc_html__( 'Waves Small', 'adiva-addons' )    => 'waves-small',
						esc_html__( 'Waves Wide', 'adiva-addons' )     => 'waves-wide',
                        esc_html__( 'Circle', 'adiva-addons' )         => 'circle',
                        esc_html__( 'Triangle', 'adiva-addons' )       => 'triangle',
						esc_html__( 'Clouds', 'adiva-addons' )         => 'clouds',
					)
				),
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Custom height', 'adiva-addons' ),
					'param_name' => 'custom_height',
					'dependency' => array(
						'element' => 'style',
						'value'   => array( 'curved-line', 'diagonal-right', 'half-circle', 'diagonal-left' )
					),
					'description' => esc_html__( 'Enter divider height (Note: CSS measurement units allowed).', 'adiva-addons' )
				),

				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Extra class name', 'adiva-addons' ),
					'param_name'  => 'el_class',
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'adiva-addons' )
				),
			),
		) );
	}
	add_action( 'vc_before_init', 'adiva_vc_map_row_divider' );
}
