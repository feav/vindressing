<?php
defined( 'ABSPATH' ) or die( 'You cannot access this script directly' );

class Dummy_Import {

	private $_adiva_pages = array();

	public $response;

	private $_importer;

	private $_page;

	private $_process = array();

	public function __construct( $page, $process ) {

		$this->_page = $page;

		$this->_process = $process;

		$this->_adiva_pages = adiva_get_config( 'pages' );

		global $ajax_response;

		$this->response = $ajax_response;

		// Load importers API
		$this->_load_importers();
	}


	public function run_import() {
		if( ! $this->_is_valid_page( $this->_page ) ) {
			$this->response->send_fail_msg( 'Wrong page name ' . $this->_page );
		}

		// Import xml file
		if ( $this->_process_has('xml') ) {
			$this->_import_xml();
		}
		//  Set up home page
		if ( $this->_process_has('home') ) {
			$this->_set_home_page();
		}

		//  Reset + Import widgets
		if ( $this->_process_has('widgets') ) {
			$this->_reset_widgets();
			$this->_import_widgets();
		}

		// Import sliders
		if ( $this->_process_has('sliders') ) {
			$this->_import_sliders();
		}
		// Import options
		if ( $this->_process_has('options') ) {
			$this->_import_theme_options();
		}
	}

	public function sizes_array( $sizes ) {
		return array();
	}

	private function _import_xml() {
		$file = $this->_get_file_to_import( 'content.xml' );
		// Check if XML file exists
		if( ! $file ) {
			$this->response->send_fail_msg( "File doesn't exist <strong>" . $this->_version . "/content.xml</strong>");
		}

		try{
	    	ob_start();
	    	// Prevent generating of thumbnails for 8 sizes. Only original
	    	add_filter( 'intermediate_image_sizes', array( $this, 'sizes_array') );

			$this->_importer->fetch_attachments = true;

			// Run WP Importer for XML file
			$this->_importer->import( $file );
			$output = ob_get_contents();

			ob_end_clean();

			$this->response->add_msg( $output );

		} catch (Exception $e) {
			$this->response->send_fail_msg("Error while importing");
		}
	}


	private function _set_home_page() {

		if($this->_page == 'base') {
			$home_page_title = 'Home 1';
		} else {
			$home_page_title = $this->_adiva_pages[$this->_page]['title'];
		}
		$home_page = get_page_by_title( $home_page_title );
		if( ! is_null( $home_page )) {

			update_option( 'page_on_front', $home_page->ID );
			update_option( 'show_on_front', 'page' );

			$this->response->add_msg( 'Front page set to <strong>"' . $home_page_title . '"</strong>' );

		} else {
			$this->response->add_msg( 'Front page is not changed' );
		}
	}

	function _import_widgets() {
		if ( current_user_can( 'manage_options' ) ) {
			// Import widgets
			$widgets_file = $this->_get_file_to_import( 'widget_data.json' );

			if( $widgets_file ) {
				$widget_data = json_decode( $this->_get_local_file_content( $widgets_file ), true );
				$this->_update_widget_data( $widget_data );
				$this->response->add_msg( 'Widgets updated' );
			}
		}
	}

	function _update_widget_data( $widget_data ) {
		$json_data = $widget_data;
		$sidebar_data = $json_data[0];
		$widget_data = $json_data[1];

		foreach ( $widget_data as $widget_data_title => $widget_data_value ) {
			$widgets[ $widget_data_title ] = '';
			foreach( $widget_data_value as $widget_data_key => $widget_data_array ) {
				if( is_int( $widget_data_key ) ) {
					$widgets[$widget_data_title][$widget_data_key] = 'on';
				}
			}
		}
		unset($widgets[""]);

		foreach ( $sidebar_data as $title => $sidebar ) {
			$count = count( $sidebar );
			for ( $i = 0; $i < $count; $i++ ) {
				$widget = array( );
				$widget['type'] = trim( substr( $sidebar[$i], 0, strrpos( $sidebar[$i], '-' ) ) );
				$widget['type-index'] = trim( substr( $sidebar[$i], strrpos( $sidebar[$i], '-' ) + 1 ) );
				if ( !isset( $widgets[$widget['type']][$widget['type-index']] ) ) {
					unset( $sidebar_data[$title][$i] );
				}
			}
			$sidebar_data[$title] = array_values( $sidebar_data[$title] );
		}

		foreach ( $widgets as $widget_title => $widget_value ) {
			foreach ( $widget_value as $widget_key => $widget_value ) {
				$widgets[$widget_title][$widget_key] = $widget_data[$widget_title][$widget_key];
			}
		}

		$sidebar_data = array( array_filter( $sidebar_data ), $widgets );
		$this->_parse_widget_data( $sidebar_data );
	}

	function _parse_widget_data( $import_array ) {
		global $wp_registered_sidebars;
		$sidebars_data = $import_array[0];
		$widget_data = $import_array[1];
		$current_sidebars = get_option( 'sidebars_widgets' );
		$new_widgets = array( );

		foreach ( $sidebars_data as $import_sidebar => $import_widgets ) :

			foreach ( $import_widgets as $import_widget ) :
				//if the sidebar exists
				if ( isset( $wp_registered_sidebars[$import_sidebar] ) ) :
					$title = trim( substr( $import_widget, 0, strrpos( $import_widget, '-' ) ) );
					$index = trim( substr( $import_widget, strrpos( $import_widget, '-' ) + 1 ) );
					$current_widget_data = get_option( 'widget_' . $title );
					$new_widget_name = $this->_get_new_widget_name( $title, $index );
					$new_index = trim( substr( $new_widget_name, strrpos( $new_widget_name, '-' ) + 1 ) );

					if ( !empty( $new_widgets[ $title ] ) && is_array( $new_widgets[$title] ) ) {
						while ( array_key_exists( $new_index, $new_widgets[$title] ) ) {
							$new_index++;
						}
					}
					$current_sidebars[$import_sidebar][] = $title . '-' . $new_index;
					if ( array_key_exists( $title, $new_widgets ) ) {
						$new_widgets[$title][$new_index] = $widget_data[$title][$index];
						$multiwidget = $new_widgets[$title]['_multiwidget'];
						unset( $new_widgets[$title]['_multiwidget'] );
						$new_widgets[$title]['_multiwidget'] = $multiwidget;
					} else {
						$current_widget_data[$new_index] = $widget_data[$title][$index];
						$current_multiwidget = (isset($current_widget_data['_multiwidget']))?$current_widget_data['_multiwidget']:'';
						$new_multiwidget = isset($widget_data[$title]['_multiwidget']) ? $widget_data[$title]['_multiwidget'] : false;
						$multiwidget = ($current_multiwidget != $new_multiwidget) ? $current_multiwidget : 1;
						unset( $current_widget_data['_multiwidget'] );
						$current_widget_data['_multiwidget'] = $multiwidget;
						$new_widgets[$title] = $current_widget_data;
					}

				endif;
			endforeach;
		endforeach;

		if ( isset( $new_widgets ) && isset( $current_sidebars ) ) {
			update_option( 'sidebars_widgets', $current_sidebars );

			foreach ( $new_widgets as $title => $content )
				update_option( 'widget_' . $title, $content );

			return true;
		}

		return false;
	}

	function _get_new_widget_name( $widget_name, $widget_index ) {
		$current_sidebars = get_option( 'sidebars_widgets' );
		$all_widget_array = array( );
		foreach ( $current_sidebars as $sidebar => $widgets ) {
			if ( !empty( $widgets ) && is_array( $widgets ) && $sidebar != 'wp_inactive_widgets' ) {
				foreach ( $widgets as $widget ) {
					$all_widget_array[] = $widget;
				}
			}
		}
		while ( in_array( $widget_name . '-' . $widget_index, $all_widget_array ) ) {
			$widget_index++;
		}
		$new_widget_name = $widget_name . '-' . $widget_index;
		return $new_widget_name;
	}
	function _reset_widgets() {
		if ( current_user_can( 'manage_options' ) ) {
			ob_start();
			$sidebars_widgets = retrieve_widgets();
			foreach ($sidebars_widgets as $area => $widgets) {
				foreach ( $widgets as $key => $widget_id ) {
					$pieces = explode( '-', $widget_id );
					$multi_number = array_pop( $pieces );
					$id_base = implode( '-', $pieces );
					$widget = get_option( 'widget_' . $id_base );
					unset( $widget[$multi_number] );
					update_option( 'widget_' . $id_base, $widget );
					unset( $sidebars_widgets[$area][$key] );
				}
			}
			wp_set_sidebars_widgets( $sidebars_widgets );
			ob_clean();
			ob_end_clean();
			$this->response->add_msg( 'Successfully reset widgets' );
		}
	}

	private function _import_sliders() {
		if( ! class_exists('RevSlider') ) return;
		$this->_revolution_import( 'slider1.zip' );
		$this->_revolution_import( 'slider2.zip' );
		$this->_revolution_import( 'slider3.zip' );
	}

	private function _revolution_import( $filename ) {
		$file = $this->_get_file_to_import( $filename );
		if( ! $file ) return;
		$revapi = new RevSlider();
		ob_start();
		$slider_result = $revapi->importSliderFromPost(true, true, $file);
		ob_end_clean();
	}
	private function _import_theme_options() {
		if ( current_user_can( 'manage_options' ) ) {
			$options_file = $this->_get_file_to_import( 'theme_options.json' );
			if( $options_file ) {
				ob_start();
				$theme_options = json_decode($this->_get_local_file_content( $options_file ), true);
				ob_clean();
				ob_end_clean();
				try {
					update_option( 'adiva_option', $theme_options );
					$this->response->add_msg( 'theme options updated' );
				} catch (Exception $e) {
					$this->response->send_fail_msg("theme options update fail");
				}
			}
		}
	}
	private function _get_file_to_import( $filename ) {
		$file = $this->_get_page_folder() . $filename;
		// Check if ZIP file exists
		if( ! file_exists( $file ) ) {
			return false;
		}

		return $file;
	}

	private function _get_page_folder( $page = false ) {
		if( ! $page ) $page = $this->_page;

		return ADIVA_DUMMY. '/' . $this->_page . '/';
	}

	private function _get_local_file_content( $file ) {
		ob_start();
		include $file;
		$file_content = ob_get_contents();
		ob_end_clean();
		return $file_content;
	}


	private function _load_importers() {

		// Load Importer API
		require_once ABSPATH . 'wp-admin/includes/import.php';

		$importerError = false;

		//check if wp_importer, the base importer class is available, otherwise include it
		if ( !class_exists( 'WP_Importer' ) ) {
			$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
			if ( file_exists( $class_wp_importer ) )
				require_once($class_wp_importer);
			else
				$importerError = true;
		}

		$path = apply_filters('adiva_require', ADIVA_ADDONS_PATH . '/classes/wordpress-importer.php');

		if( file_exists( $path ) ) {
			require_once $path;
		} else {
			$this->response->send_fail_msg( 'wordpress-importer.php file doesn\'t exist' );
		}

		if($importerError !== false) {
			$this->response->send_fail_msg( "The Auto importing script could not be loaded. Please use the wordpress importer and import the XML file that is located in your themes folder manually." );
		}

		if(class_exists('WP_Importer') && class_exists('WP_Import')){

			$this->_importer = new WP_Import();

		} else {

			$this->response->send_fail_msg( 'Can\'t find WP_Importer or WP_Import class' );

		}

	}

	private function _is_valid_page( $page ) {
		if( in_array($page, array_keys( $this->_adiva_pages ) )) return true;
		return false;
	}

	private function _process_has( $process ) {
		return in_array($process, $this->_process);
	}
}
