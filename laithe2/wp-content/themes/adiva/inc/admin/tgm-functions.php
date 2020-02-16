<?php
// Require the TGM_Plugin_Activation class.
include ADIVA_PATH . '/inc/plugins/class-tgm-plugin-activation.php';

if ( ! function_exists( 'adiva_register_required_plugins' ) ) {
    function adiva_register_required_plugins() {
        $plugins = array(
            array(
	            'name'               => esc_html__('WPBakery Page Builder', 'adiva'),
	            'slug'               => 'js_composer',
	            'source'             => get_template_directory() . '/inc/plugins/js_composer.zip',
				'image_url'          => get_template_directory_uri() . '/inc/plugins/images/visual_composer.png',
	            'required'           => true,
	            'version'            => '5.6',
	            'force_activation'   => false,
	            'force_deactivation' => false,
	            'external_url'       => '',
                'plg_class'          => 'Vc_Manager',
                'plg_func'           => '',
	        ),
            array(
	            'name'               => esc_html__('Revolution Slider', 'adiva'),
	            'slug'               => 'revslider',
	            'source'             => get_template_directory() . '/inc/plugins/revslider.zip',
				'image_url'          => get_template_directory_uri() . '/inc/plugins/images/rev_slider.jpg',
	            'required'           => true,
	            'version'            => '5.4.8.1',
	            'force_activation'   => false,
	            'force_deactivation' => false,
	            'external_url'       => '',
                'plg_class'          => 'RevSlider',
                'plg_func'           => '',
	        ),
			array(
    			'name'           => esc_html__('Woocommerce', 'adiva'),
    			'slug'           => 'woocommerce',
                'image_url'      => get_template_directory_uri() . '/inc/plugins/images/woocommerce.jpg',
    			'required'       => true,
                'plg_class'      => 'WooCommerce',
                'plg_func'       => '',
            ),
            array(
	            'name'               => esc_html__('Adiva Addons', 'adiva'),
	            'slug'               => 'adiva-addons',
	            'source'             => get_template_directory() . '/inc/plugins/adiva-addons.zip',
                'image_url'          => get_template_directory_uri() . '/inc/plugins/images/jms_plugin.jpg',
	            'required'           => true,
                'version'            => '1.5',
	            'force_activation'   => false,
	            'force_deactivation' => false,
	            'external_url'       => '',
                'plg_func'           => 'adiva_addons_load_textdomain',
                'plg_class'          => '',
	        ),
            array(
	            'name'               => esc_html__('Jms Currency', 'adiva'),
	            'slug'               => 'jmscurrency',
	            'source'             => get_template_directory() . '/inc/plugins/jmscurrency.zip',
                'image_url'          => get_template_directory_uri() . '/inc/plugins/images/jms_plugin.jpg',
	            'required'           => true,
                'version'            => '1.1',
	            'force_activation'   => false,
	            'force_deactivation' => false,
	            'external_url'       => '',
                'plg_func'           => '',
                'plg_class'          => 'Jms_Currency',
	        ),
            array(
	            'name'               => esc_html__('Jms Ajax Search', 'adiva'),
	            'slug'               => 'jmsajaxsearch',
	            'source'             => get_template_directory() . '/inc/plugins/jmsajaxsearch.zip',
                'image_url'          => get_template_directory_uri() . '/inc/plugins/images/jms_plugin.jpg',
	            'required'           => false,
                'version'            => '1.1',
	            'force_activation'   => false,
	            'force_deactivation' => false,
	            'external_url'       => '',
                'plg_func'           => '',
                'plg_class'          => 'JmsAjaxSearch_Widget_NoCats',
	        ),
            array(
	            'name'               => esc_html__('WooCommerce Recently Bought', 'adiva'),
	            'slug'               => 'woorebought',
	            'source'             => get_template_directory() . '/inc/plugins/woorebought.zip',
                'image_url'          => get_template_directory_uri() . '/inc/plugins/images/jms_plugin.jpg',
	            'required'           => false,
                'version'            => '1.1',
	            'force_activation'   => false,
	            'force_deactivation' => false,
	            'external_url'       => '',
                'plg_func'           => '',
                'plg_class'          => 'WooReBought_Admin',
	        ),
            array(
	            'name'               => esc_html__('Jms Image Swatch', 'adiva'),
	            'slug'               => 'jms-imageswatch',
	            'source'             => get_template_directory() . '/inc/plugins/jms-imageswatch.zip',
                'image_url'          => get_template_directory_uri() . '/inc/plugins/images/jms_plugin.jpg',
	            'required'           => false,
                'version'            => '1.0.0',
	            'force_activation'   => false,
	            'force_deactivation' => false,
	            'external_url'       => '',
                'plg_func'           => 'jms_imageswatch_admin_menu',
                'plg_class'          => '',
	        ),
	        array(
    			'name'           => esc_html__('YITH WooCommerce Wishlist', 'adiva'),
    			'slug'           => 'yith-woocommerce-wishlist',
                'image_url'      => get_template_directory_uri() . '/inc/plugins/images/yith_wishlist.jpg',
    			'required'       => true,
                'plg_class'      => 'YITH_WCWL',
                'plg_func'       => '',
    		),
            array(
    			'name'           => esc_html__('YITH WooCommerce Compare', 'adiva'),
    			'slug'           => 'yith-woocommerce-compare',
                'image_url'      => get_template_directory_uri() . '/inc/plugins/images/yith_compare.jpg',
    			'required'       => true,
                'plg_class'      => 'YITH_WOOCOMPARE',
                'plg_func'       => '',
    		),
	        array(
    			'name'           => esc_html__('Contact Form 7', 'adiva'),
    			'slug'           => 'contact-form-7',
                'image_url'      => get_template_directory_uri() . '/inc/plugins/images/contact_form_7.jpg',
    			'required'       => true,
                'plg_class'       => 'WPCF7',
                'plg_func'       => '',
            ),
	        array(
    			'name'           => esc_html__('MailChimp for WordPress', 'adiva'),
    			'slug'           => 'mailchimp-for-wp',
                'image_url'      => get_template_directory_uri() . '/inc/plugins/images/mail-chimp.jpg',
    			'required'       => true,
                'plg_class'      => 'MC4WP_Admin',
                'plg_func'       => '',
    		),
			array(
    			'name'           => esc_html__('WP GDPR Compliance', 'adiva'),
    			'slug'           => 'wp-gdpr-compliance',
                'image_url'      => get_template_directory_uri() . '/inc/plugins/images/gdpr-compliance.jpg',
    			'required'       => false,
                'plg_class'      => 'WPGDPRC',
                'plg_func'       => '',
    		)

    	);

        $config = array(
	        'default_path' => '',                      // Default absolute path to pre-packaged plugins.
	        'menu'         => 'tgmpa-install-plugins', // Menu slug.
	        'has_notices'  => true,                    // Show admin notices or not.
	        'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
	        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
	        'is_automatic' => true,                   // Automatically activate plugins after installation or not.
	        'message'      => '',                      // Message to output right before the plugins table.
	        'strings'      => array(
	            'page_title'                      => esc_html__( 'Install Required Plugins', 'adiva' ),
	            'menu_title'                      => esc_html__( 'Install Plugins', 'adiva' ),
	            'installing'                      => 'Installing Plugin: %s', // %s = plugin name.
	            'oops'                            => esc_html__( 'Something went wrong with the plugin API.', 'adiva' ),
	            'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'adiva' ), // %1$s = plugin name(s).
	            'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'adiva' ), // %1$s = plugin name(s).
	            'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'adiva' ), // %1$s = plugin name(s).
	            'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'adiva' ), // %1$s = plugin name(s).
	            'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'adiva' ), // %1$s = plugin name(s).
	            'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'adiva' ), // %1$s = plugin name(s).
	            'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'adiva' ), // %1$s = plugin name(s).
	            'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'adiva' ), // %1$s = plugin name(s).
	            'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'adiva' ),
	            'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins', 'adiva' ),
	            'return'                          => esc_html__( 'Return to Required Plugins Installer', 'adiva' ),
	            'plugin_activated'                => esc_html__( 'Plugin activated successfully.', 'adiva' ),
	            'complete'                        => esc_html__( 'All plugins installed and activated successfully.', 'adiva' ),
	            'nag_type'                        => 'updated' // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
	        )
	    );

        tgmpa( $plugins, $config );

    }
    add_action('tgmpa_register', 'adiva_register_required_plugins');
}
