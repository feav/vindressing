<?php
/**
* ------------------------------------------------------------------------------------------------
* Pricing tables elements map
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'adiva_vc_map_pricing_tables' ) ) {
	function adiva_vc_map_pricing_tables() {
		vc_map( array(
			'name'                    => esc_html__( 'Pricing tables', 'adiva-addons' ),
			'base'                    => 'pricing_tables',
			'as_parent'               => array( 'only' => 'pricing_plan' ),
			'content_element'         => true,
			'show_settings_on_create' => false,
            'icon'                    => 'jms-icon',
            'category'                => esc_html__( 'JMS Addons', 'adiva-addons' ),
			'description'             => esc_html__( 'Show your pricing plans', 'adiva-addons' ),
			'params'                  => array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Extra class name', 'adiva-addons' ),
					'param_name'  => 'el_class',
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'adiva-addons' )
				)
			),
		    'js_view' => 'VcColumnView'
		) );

		vc_map( array(
			'name'            => esc_html__( 'Price plan', 'adiva-addons' ),
			'base'            => 'pricing_plan',
			'as_child'        => array( 'only' => 'pricing_tables' ),
			'content_element' => true,
            'icon'            => 'jms-icon',
            'category'        => esc_html__( 'JMS Addons', 'adiva-addons' ),
			'params'          => array(
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Pricing plan name', 'adiva-addons' ),
					'param_name' => 'name',
					'value'      => ''
				),
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Price value', 'adiva-addons' ),
					'param_name' => 'price_value',
					'value'      => ''
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Price suffix', 'adiva-addons' ),
					'param_name'  => 'price_suffix',
					'value'       => 'per month',
					'description' => esc_html__( 'For example: per month', 'adiva-addons' )
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Price currency', 'adiva-addons' ),
					'param_name'  => 'currency',
					'value'       => '',
					'description' => esc_html__( 'For example: $', 'adiva-addons' )
				),
				array(
					'type'        => 'textarea',
					'heading'     => esc_html__( 'Featured list', 'adiva-addons' ),
					'param_name'  => 'features_list',
					'description' => esc_html__( 'Start each feature text from a new line', 'adiva-addons' )
				),
				array(
					'type'        => 'link',
					'heading'     => esc_html__( 'Button link', 'adiva-addons'),
					'param_name'  => 'link',
					'description' => esc_html__( 'Enter URL if you want this box to have a link.', 'adiva-addons' ),
				),
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Button label', 'adiva-addons' ),
					'param_name' => 'button_label',
					'value'      => '',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Label text', 'adiva-addons' ),
					'param_name'  => 'label',
					'value'       => '',
					'description' => esc_html__( 'For example: Best option!', 'adiva-addons' )
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Label color', 'adiva-addons' ),
					'param_name' => 'label_color',
					'value'      => array(
						''                              => '',
						esc_html__( 'Red', 'adiva-addons' )    => 'red',
						esc_html__( 'Green', 'adiva-addons' )  => 'green',
						esc_html__( 'Blue', 'adiva-addons' )   => 'blue',
						esc_html__( 'Yellow', 'adiva-addons' ) => 'yellow',
					)
				),
				array(
					'type'        => 'checkbox',
					'heading'     => esc_html__( 'Best option', 'adiva-addons' ),
					'param_name'  => 'best_option',
					'description' => esc_html__( 'If "YES" this table will be highlighted', 'adiva-addons' ),
					'value'       => array( esc_html__( 'Yes, please', 'adiva-addons' ) => 'yes' )
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Style', 'adiva-addons' ),
					'param_name' => 'style',
					'value'      => array(
						''                                 => '',
						esc_html__( 'Default', 'adiva-addons' )   => 'default',
						esc_html__( 'No background', 'adiva-addons' ) => 'no-background'
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
		// Necessary hooks for blog autocomplete fields
		add_filter( 'vc_autocomplete_pricing_plan_id_callback',	'vc_include_field_search', 10, 1 ); // Get suggestion(find). Must return an array
		add_filter( 'vc_autocomplete_pricing_plan_id_render', 'vc_include_field_render', 10, 1 ); // Render exact product. Must return an array (label,value)
	}
	add_action( 'vc_before_init', 'adiva_vc_map_pricing_tables' );
}
