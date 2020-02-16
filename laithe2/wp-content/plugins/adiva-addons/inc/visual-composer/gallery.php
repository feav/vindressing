<?php
/**
* ------------------------------------------------------------------------------------------------
* Images gallery element map
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'adiva_vc_map_gallery' ) ) {
	function adiva_vc_map_gallery() {
		vc_map( array(
			'name'        => esc_html__( 'Images gallery', 'adiva-addons' ),
			'base'        => 'adiva_addons_gallery',
			'description' => esc_html__( 'Images grid/carousel', 'adiva-addons' ),
            'category'    => esc_html__( 'JMS Addons', 'adiva-addons' ),
            'icon'        => 'jms-icon',
			'params'      => array(
				array(
					'type'        => 'attach_images',
					'heading'     => esc_html__( 'Images', 'adiva-addons' ),
					'param_name'  => 'images',
					'admin_label' => true,
					'value'       => '',
					'description' => esc_html__( 'Select images from media library.', 'adiva-addons' )
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Image size', 'adiva-addons' ),
					'param_name'  => 'img_size',
					'description' => esc_html__( 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "full" size.', 'adiva-addons' )
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'View', 'adiva-addons' ),
					'value'       => 4,
					'param_name'  => 'gallery_type',
					'save_always' => true,
					'value'       => array(
						esc_html__( 'Default grid', 'adiva-addons' ) => 'grid',
						esc_html__( 'Masonry grid', 'adiva-addons' ) => 'masonry',
					)
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Space between images', 'adiva-addons' ),
					'param_name' => 'spacing',
					'std'        => '10',
					'value'      => array(
						0, 5, 10, 15, 20, 25, 30, 35, 40
					),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Columns', 'adiva-addons' ),
					'value'       => 3,
					'param_name'  => 'columns',
					'save_always' => true,
					'description' => esc_html__( 'How much columns grid', 'adiva-addons' ),
					'value' => array(
						esc_html__('1 column', 'adiva-addons') => 1,
						esc_html__('2 columns', 'adiva-addons') => 2,
						esc_html__('3 columns', 'adiva-addons') => 3,
						esc_html__('4 columns', 'adiva-addons') => 4,
						esc_html__('6 columns', 'adiva-addons') => 6,
					),
					'dependency' => array(
						'element' => 'gallery_type',
						'value'   => array( 'grid', 'masonry' ),
					),
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'On click action', 'adiva-addons' ),
					'param_name' => 'on_click',
					'value'      => array(
						''                                   => '',
						esc_html__( 'Lightbox', 'adiva-addons' )    => 'lightbox',
						esc_html__( 'None', 'adiva-addons' )        => 'none'
					)
				),
				array(
					'type'        => 'checkbox',
					'heading'     => esc_html__( 'Open in new tab', 'adiva-addons' ),
					'save_always' => true,
					'param_name'  => 'target_blank',
					'value'       => array( esc_html__( 'Yes, please', 'adiva-addons' ) => 'yes' ),
					'default'     => 'yes',
					'dependency' => array(
						'element' => 'on_click',
						'value'   => array( 'links' ),
					),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Extra class name', 'adiva-addons' ),
					'param_name'  => 'el_class',
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'adiva-addons' )
				),
			)
		) );
	}
	add_action( 'vc_before_init', 'adiva_vc_map_gallery' );
}
