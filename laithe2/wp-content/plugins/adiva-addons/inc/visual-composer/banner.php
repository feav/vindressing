<?php
/**
* ------------------------------------------------------------------------------------------------
* Adiva banner shortcode
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'adiva_vc_map_banner' ) ) {
	function adiva_vc_map_banner() {
        $fontsize = array (
			esc_html__('15px', 'adiva-addons')  => '15',
			esc_html__('16px', 'adiva-addons')  => '16',
            esc_html__('17px', 'adiva-addons')  => '17',
			esc_html__('18px', 'adiva-addons')  => '18',
			esc_html__('19px', 'adiva-addons')  => '19',
            esc_html__('20px', 'adiva-addons')  => '20',
            esc_html__('21px', 'adiva-addons')  => '21',
			esc_html__('22px', 'adiva-addons')  => '22',
			esc_html__('23px', 'adiva-addons')  => '23',
			esc_html__('24px', 'adiva-addons')  => '24',
			esc_html__('25px', 'adiva-addons')  => '25',
			esc_html__('26px', 'adiva-addons')  => '26',
			esc_html__('27px', 'adiva-addons')  => '27',
			esc_html__('28px', 'adiva-addons')  => '28',
            esc_html__('29px', 'adiva-addons')  => '29',
            esc_html__('30px', 'adiva-addons')  => '30',
			esc_html__('31px', 'adiva-addons')  => '31',
			esc_html__('32px', 'adiva-addons')  => '32',
			esc_html__('33px', 'adiva-addons')  => '33',
			esc_html__('34px', 'adiva-addons')  => '34',
			esc_html__('35px', 'adiva-addons')  => '35',
            esc_html__('36px', 'adiva-addons')  => '36',
			esc_html__('37px', 'adiva-addons')  => '37',
			esc_html__('38px', 'adiva-addons')  => '38',
			esc_html__('39px', 'adiva-addons')  => '39',
            esc_html__('40px', 'adiva-addons')  => '40',
            esc_html__('46px', 'adiva-addons')  => '46',
            esc_html__('59px', 'adiva-addons')  => '48',
            esc_html__('60px', 'adiva-addons')  => '60',
        );

        vc_map(
            array(
                'name'     => esc_html__( 'Image and Text', 'adiva-addons' ),
                'base'     => 'adiva_addons_banner',
                'icon'     => 'jms-icon',
                'category' => esc_html__( 'JMS Addons', 'adiva-addons' ),
                'params'   => array(
                    array(
                        'param_name'  => 'image',
                        'heading'     => esc_html__( 'Image', 'adiva-addons' ),
                        'type'        => 'attach_image',
                        'admin_label' => true,
                    ),
                    array(
                        'param_name' => 'title',
                        'heading'    => esc_html__( 'Title', 'adiva-addons' ),
                        'type'       => 'textarea',
                    ),
                    array(
                        'param_name' => 'subtitle',
                        'heading'    => esc_html__( 'Subtitle', 'adiva-addons' ),
                        'type'       => 'textarea',
                    ),
                    array(
                        'param_name' => 'subsubtitle',
                        'heading'    => esc_html__( 'Sub-Subtitle', 'adiva-addons' ),
                        'type'       => 'textarea',
                    ),
                    array(
                        'param_name' => 'link',
                        'heading'    => esc_html__( 'Link to', 'adiva-addons' ),
                        'type'       => 'vc_link',
                    ),
                    array(
                        'param_name'  => 'hover_effect',
                        'heading'     => esc_html__( 'Hover Effect', 'adiva-addons' ),
                        'type'        => 'dropdown',
                        'description' => esc_html__( 'Set beautiful hover effects for your banner.', 'adiva-addons' ),
                        'save_always' => true,
                        'value'       => array(
                            esc_html__( 'Zoom image', 'adiva-addons' )   => 'zoom',
                            esc_html__( 'Parallax', 'adiva-addons' )     => 'parallax',
                            esc_html__( 'Background', 'adiva-addons' )   => 'background',
                            esc_html__( 'Bordered', 'adiva-addons' )     => 'border',
                            esc_html__( 'Zoom reverse', 'adiva-addons' ) => 'zoom-reverse',
                            esc_html__( 'Disable', 'adiva-addons' )      => 'none',
                        ),
                    ),
                    array(
                        'type'       => 'dropdown',
                        'heading'    => esc_html__( 'Content style', 'adiva-addons' ),
                        'param_name' => 'banner_style',
                        'value'      => array(
                            esc_html__( 'Default', 'adiva-addons' )                => '',
                            esc_html__( 'Color mask', 'adiva-addons' )             => 'mask',
                            esc_html__( 'Mask with shadow', 'adiva-addons' )       => 'shadow',
                            esc_html__( 'Bordered', 'adiva-addons' )               => 'border',
                            esc_html__( 'Rectangular background', 'adiva-addons' ) => 'background',
                        ),
                        'description' => esc_html__( 'You can use some of our predefined styles for your banner content.', 'adiva-addons' )
                    ),
                    array(
                        'type'       => 'dropdown',
                        'heading'    => esc_html__( 'Banner content width', 'adiva-addons' ),
                        'param_name' => 'content_width',
                        'value'      => array(
                            '100%' => '100',
                            '90%' => '90',
                            '80%' => '80',
                            '70%' => '70',
                            '60%' => '60',
                            '50%' => '50',
                            '40%' => '40',
                            '30%' => '30',
                            '20%' => '20',
                            '10%' => '10',
                        ),
                    ),
                    vc_map_add_css_animation(),
                    array(
                        'param_name'  => 'el_class',
                        'heading'     => esc_html__( 'Extra class name', 'adiva-addons' ),
                        'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'adiva-addons' ),
                        'type' 	      => 'textfield',
                    ),
                    // Title style
                    array(
                        'param_name'  => 'title_size',
                        'heading'     => esc_html__('Title Font Size', 'adiva-addons'),
                        'type'        => 'dropdown',
                        'save_always' => true,
                        'group'       => esc_html__( 'Title Style', 'adiva-addons' ),
                        'description' => esc_html__('Select the font size of the title.', 'adiva-addons') ,
						'save_always' => true,
                        'value'       => $fontsize
                    ),
                    array(
                        'param_name'  => 'title_lineheight',
                        'heading'     => esc_html__('Line height', 'adiva-addons'),
                        'type'        => 'textfield',
                        'group'       => esc_html__( 'Title Style', 'adiva-addons' ),
                        'value'       => ''
                    ),
                    array(
                        'param_name'  => 'title_color',
                        'heading'     => esc_html__('Title Color', 'adiva-addons'),
                        'type'        => 'colorpicker',
                        'group'       => esc_html__( 'Title Style', 'adiva-addons' ),
                    ),
                    array(
                        'param_name' => 'title_use_theme_fonts',
                        'type'       => 'checkbox',
                        'heading'    => esc_html__( 'Use theme default font family?', 'adiva-addons' ),
                        'group'      => esc_html__( 'Title Style', 'adiva-addons' ),
                        'std'        => 'yes',
                        'value'      => array(
                            esc_html__( 'Yes', 'adiva-addons' ) => 'yes'
                        ),
                        'description' => esc_html__( 'Use font family from the theme.', 'adiva-addons' ),
                    ),
                    array(
                        'param_name' => 'title_google_fonts',
                        'type'       => 'google_fonts',
                        'value'      => 'font_family:Abril%20Fatface%3Aregular|font_style:400%20regular%3A400%3Anormal',
                        'group'       => esc_html__( 'Title Style', 'adiva-addons' ),
                        'settings'   => array(
                            'fields' => array(
                                'font_family_description' => esc_html__( 'Select font family.', 'adiva-addons' ),
                                'font_style_description'  => esc_html__( 'Select font styling.', 'adiva-addons' ),
                            ),
                        ),
                        'dependency' => array(
                            'element'            => 'title_use_theme_fonts',
                            'value_not_equal_to' => 'yes',
                        ),
                    ),
                    // Subtitle style
                    array(
                        'param_name'  => 'subtitle_size',
                        'heading'     => esc_html__('Subtitle Font Size', 'adiva-addons'),
                        'type'        => 'dropdown',
                        'group'       => esc_html__( 'Subtitle Style', 'adiva-addons' ),
                        'description' => esc_html__('Select the font size of the subtitle.', 'adiva-addons') ,
                        'value'       => $fontsize
                    ),
                    array(
                        'param_name'  => 'subtitle_lineheight',
                        'heading'     => esc_html__('Line height', 'adiva-addons'),
                        'type'        => 'textfield',
                        'group'       => esc_html__( 'Subtitle Style', 'adiva-addons' ),
                        'value'       => ''
                    ),
                    array(
                        'param_name'  => 'subtitle_color',
                        'heading'     => esc_html__('Subtitle Color', 'adiva-addons'),
                        'type'        => 'colorpicker',
                        'group'      => esc_html__( 'Subtitle Style', 'adiva-addons' ),
                    ),
                    array(
                        'param_name' => 'subtitle_use_theme_fonts',
                        'type'       => 'checkbox',
                        'heading'    => esc_html__( 'Use theme default font family?', 'adiva-addons' ),
                        'group'      => esc_html__( 'Subtitle Style', 'adiva-addons' ),
                        'std'        => 'yes',
                        'value'      => array(
                            esc_html__( 'Yes', 'adiva-addons' ) => 'yes'
                        ),
                        'description' => esc_html__( 'Use font family from the theme.', 'adiva-addons' ),
                    ),
                    array(
                        'param_name' => 'subtitle_google_fonts',
                        'type'       => 'google_fonts',
                        'value'      => 'font_family:Abril%20Fatface%3Aregular|font_style:400%20regular%3A400%3Anormal',
                        'group'      => esc_html__( 'Subtitle Style', 'adiva-addons' ),
                        'settings'   => array(
                            'fields' => array(
                                'font_family_description' => esc_html__( 'Select font family.', 'adiva-addons' ),
                                'font_style_description'  => esc_html__( 'Select font styling.', 'adiva-addons' ),
                            ),
                        ),
                        'dependency' => array(
                            'element'            => 'subtitle_use_theme_fonts',
                            'value_not_equal_to' => 'yes',
                        ),
                    ),
                    // SubSubtitle style
                    array(
                        'param_name'  => 'subsubtitle_size',
                        'heading'     => esc_html__('Sub-Subtitle Font Size', 'adiva-addons'),
                        'type'        => 'dropdown',
                        'group'       => esc_html__( 'Sub-Subtitle Style', 'adiva-addons' ),
                        'description' => esc_html__('Select the font size of the sub-subtitle.', 'adiva-addons') ,
                        'value'       => $fontsize
                    ),
                    array(
                        'param_name'  => 'subsubtitle_lineheight',
                        'heading'     => esc_html__('Line height', 'adiva-addons'),
                        'type'        => 'textfield',
                        'group'       => esc_html__( 'Sub-Subtitle Style', 'adiva-addons' ),
                        'value'       => ''
                    ),
                    array(
                        'param_name'  => 'subsubtitle_color',
                        'heading'     => esc_html__('Sub-Subtitle Color', 'adiva-addons'),
                        'type'        => 'colorpicker',
                        'group'      => esc_html__( 'Sub-Subtitle Style', 'adiva-addons' ),
                    ),
                    array(
                        'param_name' => 'subsubtitle_use_theme_fonts',
                        'type'       => 'checkbox',
                        'heading'    => esc_html__( 'Use theme default font family?', 'adiva-addons' ),
                        'group'      => esc_html__( 'Sub-Subtitle Style', 'adiva-addons' ),
                        'std'        => 'yes',
                        'value'      => array(
                            esc_html__( 'Yes', 'adiva-addons' ) => 'yes'
                        ),
                        'description' => esc_html__( 'Use font family from the theme.', 'adiva-addons' ),
                    ),
                    array(
                        'param_name' => 'subsubtitle_google_fonts',
                        'type'       => 'google_fonts',
                        'value'      => 'font_family:Abril%20Fatface%3Aregular|font_style:400%20regular%3A400%3Anormal',
                        'group'      => esc_html__( 'Sub-Subtitle Style', 'adiva-addons' ),
                        'settings'   => array(
                            'fields' => array(
                                'font_family_description' => esc_html__( 'Select font family.', 'adiva-addons' ),
                                'font_style_description'  => esc_html__( 'Select font styling.', 'adiva-addons' ),
                            ),
                        ),
                        'dependency' => array(
                            'element'            => 'subsubtitle_use_theme_fonts',
                            'value_not_equal_to' => 'yes',
                        ),
                    ),
                    //Buttons
                    array(
                        'type'       => 'textfield',
                        'heading'    => esc_html__( 'Button text', 'adiva-addons' ),
                        'param_name' => 'btn_text',
                        'group'      => esc_html__( 'Buttons', 'adiva-addons' ),
                    ),
                    array(
                        'type'       => 'textfield',
                        'heading'    => esc_html__( 'Button link', 'adiva-addons' ),
                        'param_name' => 'btn_link',
                        'group'      => esc_html__( 'Buttons', 'adiva-addons' ),
                    ),
                    // Box
                    array(
                        'param_name'  => 'box_align',
                        'heading'     => esc_html__('Text Align', 'adiva-addons'),
                        'type'        => 'dropdown',
                        'group'      => esc_html__( 'Positioning', 'adiva-addons' ),
                        'std'		  => 'tl',
                        'value'       => array(
                            esc_html__( 'Left', 'adiva-addons' )   => '',
                            esc_html__( 'Center', 'adiva-addons' ) => 'tc',
                            esc_html__( 'Right', 'adiva-addons' )  => 'tr',
                        )
                    ),
                    array(
                        'type'       => 'dropdown',
                        'heading'    => esc_html__( 'Content vertical alignment', 'adiva-addons' ),
                        'param_name' => 'vertical_alignment',
                        'group'      => esc_html__( 'Positioning', 'adiva-addons' ),
                        'value'      => array(
                            esc_html__( 'Top', 'adiva-addons' )    => '',
                            esc_html__( 'Middle', 'adiva-addons' ) => 'middle',
                            esc_html__( 'Bottom', 'adiva-addons' ) => 'bottom'
                        )
                    ),
                    array(
                        'type'        	=> 'css_editor',
                        'heading'     	=> esc_html__( 'Css', 'adiva-addons' ),
                        'param_name'  	=> 'css',
                        'group'       	=> esc_html__( 'Design options', 'adiva-addons' ),
                        'admin_label' 	=> false,
                    ),
                )
            )
        );
    }
    add_action( 'vc_before_init', 'adiva_vc_map_banner' );
}
