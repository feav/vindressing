<?php
/**
* ------------------------------------------------------------------------------------------------
* Adiva megamenu shortcode
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'adiva_vc_map_megamenu' ) ) {
	function adiva_vc_map_megamenu() {
        vc_map(
            array(
                'name'        => esc_html__( 'Mega Menu Widget', 'adiva-addons' ),
                'base'        => 'adiva_megamenu',
                'description' => esc_html__( 'Categories mega menu widget', 'adiva-addons' ),
                'category'    => esc_html__( 'JMS Addons', 'adiva-addons' ),
                'icon'        => 'jms-icon',
                'params'      => array(
                    array(
                        'type'       => 'textfield',
                        'heading'    => esc_html__( 'Title', 'adiva-addons' ),
                        'param_name' => 'title',
                        'admin_label' => true,
                    ),
                    array(
                        'type'       => 'dropdown',
                        'heading'    => esc_html__( 'Choose Menu', 'adiva-addons' ),
                        'param_name' => 'nav_menu',
                        'value'      => adiva_get_menus_array(),
                        'admin_label' => true,
                    ),
                    array(
                        'type'       => 'colorpicker',
                        'heading'    => esc_html__( 'Title Color', 'adiva-addons' ),
                        'param_name' => 'color',
                        'admin_label' => true,
                    ),
                    vc_map_add_css_animation(),
                    array(
                        'param_name'  => 'el_class',
                        'heading'     => esc_html__( 'Extra class name', 'adiva-addons' ),
                        'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'adiva-addons' ),
                        'type'        => 'textfield',
                        'admin_label' => false,
                    ),
                    array(
                        'type'        	=> 'css_editor',
                        'heading'     	=> esc_html__( 'Css', 'adiva-addons' ),
                        'param_name'  	=> 'css',
                        'group'       	=> esc_html__( 'Design options', 'adiva-addons' ),
                        'admin_label' 	=> false,
                    ),
                ),
            )
        );

	}
	add_action( 'vc_before_init', 'adiva_vc_map_megamenu' );
}

// Get menu array()
if( ! function_exists( 'adiva_get_menus_array') ) {
	function adiva_get_menus_array() {
		$adiva_menus = wp_get_nav_menus();
		$adiva_menu_dropdown = array();

		foreach ( $adiva_menus as $menu ) {
			$adiva_menu_dropdown[$menu->term_id] = $menu->name;
		}

		return $adiva_menu_dropdown;
	}
}
