<?php
/**
* ------------------------------------------------------------------------------------------------
* Countdown timer element map
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'adiva_vc_map_countdown_timer' ) ) {
	function adiva_vc_map_countdown_timer() {
		vc_map( array(
			'name'        => esc_html__( 'Countdown timer', 'adiva-addons' ),
			'base'        => 'adiva_countdown',
            'icon'        => 'jms-icon',
            'category'    => esc_html__( 'JMS Addons', 'adiva-addons' ),
            'description' => esc_html__( 'Shows countdown timer', 'adiva-addons' ),
			'params'      => array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Date', 'adiva-addons' ),
					'param_name'  => 'date',
					'description' => esc_html__( 'Final date in the format Y/m/d. For example 2018/03/12', 'adiva-addons' )
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Color Scheme', 'adiva-addons' ),
					'param_name' => 'color_scheme',
					'value'      => array(
						esc_html__( 'Choose', 'adiva-addons' ) => '',
						esc_html__( 'Light', 'adiva-addons' )  => 'light',
						esc_html__( 'Dark', 'adiva-addons' )   => 'dark',
					),
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Size', 'adiva-addons' ),
					'param_name' => 'size',
					'value'      => array(
						''                                   => '',
						esc_html__( 'Small', 'adiva-addons' )       => 'small',
						esc_html__( 'Medium', 'adiva-addons' )      => 'medium',
						esc_html__( 'Large', 'adiva-addons' )       => 'large',
						esc_html__( 'Extra Large', 'adiva-addons' ) => 'xlarge',
					)
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Align', 'adiva-addons' ),
					'param_name' => 'align',
					'value'      => array(
						'' => '',
						esc_html__( 'left', 'adiva-addons' )   => 'left',
						esc_html__( 'center', 'adiva-addons' ) => 'center',
						esc_html__( 'right', 'adiva-addons' )  => 'right',
					)
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Style', 'adiva-addons' ),
					'param_name' => 'style',
					'value'      => array(
						'' => '',
						esc_html__( 'Standard', 'adiva-addons' )      => 'standard',
						esc_html__( 'Transparent', 'adiva-addons' )   => 'transparent',
						esc_html__( 'Primary color', 'adiva-addons' ) => 'primary',
					)
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Extra class name', 'adiva-addons' ),
					'param_name'  => 'el_class',
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'adiva-addons' )
				)
			)
		) );
	}
	add_action( 'vc_before_init', 'adiva_vc_map_countdown_timer' );
}
