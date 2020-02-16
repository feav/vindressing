<?php
/*
 * Plugin Name: Jms Mega Menu
 * Plugin URI:  http://www.joommasters.com
 * Description: Mega Menu for WordPress.
 * Version:     1.0
 * Author:      Joommasters
 * Author URI:  http://www.joommasters.com
 * License:     GPL-2.0+
 * Copyright:   2016 Joommasters (http://www.joommasters.com)
 */

/**
 * Mega menu custom walker.
 */
class Adiva_Megamenu_Walker extends Walker_Nav_Menu {
	private $style   = '';
	private $is_mega = false;

	/**
	 * Starts the list before the elements are added.
	 *
	 * @see Walker::start_lvl()
	 * @since 3.0.0
	 * @param string  $output Passed by reference. Used to append additional content.
	 * @param int     $depth  Depth of menu item. Used for padding.
	 * @param array   $args   An array of arguments. @see wp_nav_menu()
	 */
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		if ( $depth == 0 ) {
			if ( $this->is_mega ) {
				$output .= '';
			} else {
				$output .= '<ul class="sub-menu" ' . $this->style . '>';
			}
		} else if ( $this->is_mega ) {
			$output .= '';
		} else {
			$output .= '<ul class="sub-menu">';
		}
	}

	/**
	 * Ends the list of after the elements are added.
	 *
	 * @see Walker::end_lvl()
	 * @since 3.0.0
	 * @param string  $output Passed by reference. Used to append additional content.
	 * @param int     $depth  Depth of menu item. Used for padding.
	 * @param array   $args   An array of arguments. @see wp_nav_menu()
	 */
	function end_lvl( &$output, $depth = 0, $args = array() ) {
		if ( $depth == 0 ) {
			if ( $this->is_mega ) {
				$output .= '';
			} else {
				$output .= '</ul>';
			}
		} else if ( $this->is_mega ) {
			$output .= '';
		} else {
			$output .= '</ul>';
		}
	}
	/**
	 * Starting build menu element
	 *
	 * @param string  $output       Passed by reference. Used to append additional content.
	 * @param object  $item         Menu item data object.
	 * @param int     $depth        Depth of menu item. Used for padding.
	 * @param int     $current_page Menu item ID.
	 * @param object  $args
	 */
	function start_el( &$output, $item, $depth = 0, $args = array(), $current_object_id = 0 ) {

		$el_styles = array();

		$menu_id = isset( $args->menu->term_id ) ? $args->menu->term_id : $args->menu;

		$data = Adiva_Megamenu::get_data();
		$data = isset( $data[ $menu_id ][ $item->ID ] ) ? $data[ $menu_id ][ $item->ID ] : array();
		$data['level'] = $depth;

		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		if( isset( $args->is_mobile ) && $args->is_mobile ) {
			$atts['class'] = isset( $atts['class'] ) ? $atts['class'] . ' menu-item-link' : 'menu-item-link';

			$attributes = '';
			foreach ( $atts as $attr => $value ) {
				if ( ! empty( $value ) ) {
					$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}

			$item_output =
				'<div class="item-link-outer"><a ' . $attributes . '><span class="menu_title">' . esc_html( $item->title ) . '</span></a>' . ( $this->has_children ? '<i class="has-children-mobile fa fa-angle-down"></i>' : '' ) . '</div>';
		} else {
			$atts['class'] = isset( $atts['class'] ) ? $atts['class'] . ' menu-item-link menu-align-' . esc_attr( $data['align'] ) : 'menu-item-link';
			if(isset($data['show_logo']) && $data['show_logo'] == '1') $atts['class'] .= ' jms-logo';
			$attributes = '';
			foreach ( $atts as $attr => $value ) {
				if ( ! empty( $value ) ) {
					$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}
			if(isset($data['show_logo']) && $data['show_logo'] == '1') {
				global $jms_option;
				if (!empty($jms_option['logo_image']['url']))
					$item_output =
					'<a href="'. home_url( '/' ) .'"><img src="'.$jms_option['logo_image']['url'].'" alt="'.get_bloginfo( 'name' ).'"></a>';
				else
					$item_output =
					'<a ' . $attributes . ' ><span class="jms-logo-text">'.get_bloginfo( 'name' ).'</span></a>';
			} else {
				$item_output =
					'<a ' . $attributes . ' >' .
						( ( ! empty( $data['icon_class'] )  ) ? '<i class="menu-icon ' . esc_attr( $data['icon_class'] ) . '"></i>' : NULL ) .
						'<span class="menu_title">' . esc_attr( $item->title ) . '</span>' .
					'</a>';
			}
		}

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;

		$style_inline = array();
		if ( $depth == 0 ) {
			$id_menu = isset( $args->menu->term_id ) ? $args->menu->term_id : $args->menu;

			$count_menu_items = count( self::get_menu_items( $id_menu, $item->ID, 1 ) );
			if(!empty($data['submenu_class'])) {
				$classes[] = $data['submenu_class'];
			}
			if( empty( $data['align'] ) ) {
				$data['align'] = 'left';
			}
			$classes[] = 'menu-align-'.$data['align'];
			if ( isset($data['mega']) && $data['mega'] == 1 && $count_menu_items ) {
				$this->is_mega = true;
				$classes[] = 'mega';
				$data_width = '';
				if ( $data['width_type'] == 'fixed' && (int) $data['width'] ) {
					$style_width = 'style="width:'.$data['width'].'px;"';
				} else {
					$data_width = 'data-width="full"';
					$classes[] = 'mega-full';
					$style_width = 'style="width:100%;"';
				}

				$item_output .= '<div ' . $data_width . '  class="dropdown-menu mega-dropdown-menu '.( ($data['width_type'] == 'fullwidth' ) ? esc_attr( $data['width_type'] )  : NULL ).'" '.$style_width.'><div class="mega-dropdown-inner">';
				if (isset($data['mega_type']) && $data['mega_type'] == 'tab') {
					$submenu_items_elment = self::tabmenu( $id_menu, $item->ID );
					if ( $submenu_items_elment ) {
						$item_output .= $submenu_items_elment;
					}
				} elseif (isset($data['mega_type']) && $data['mega_type'] == 'accordion') {
					$submenu_items_elment = self::accordionmenu( $id_menu, $item->ID );
					if ( $submenu_items_elment ) {
						$item_output .= $submenu_items_elment;
					}
				} else 	{
					$submenu_items_elment = self::submenu( $id_menu, $item->ID );
					if ( $submenu_items_elment ) {
						$item_output .= $submenu_items_elment . '</ul>';
						$_submenu_cols = explode( '-', $data['submenu_layout'] );
						if($data['submenu_layout'][strlen($data['submenu_layout']) - 1] == ']') $item_output .= '</div>';
					}
				}
				$item_output .= '</div></div>';
			} else {
				$classes[] = 'menu-default';
				$this->is_mega = false;
			}
		} else {
			if(isset($data['column_heading']) &&  $data['column_heading'] == 1 ) {
				$classes[] = 'column-heading';
			}
		}

		$classes[] = 'menu-item-lv' . absint( $depth );

		// Generate class and style attribute
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$el_styles = $el_styles ? ' style="' . esc_attr( join( ';', $el_styles ) ) . '"' : '';


		if ( $depth != 0 && $this->is_mega ) {
			$output .= '';
			$item_output = '';
		} else {
 			$output .= '<li ' . $class_names . '>';
 		}

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	/**
	 * Ends the list of after the elements are added.
	 *
	 * @see Walker::end_lvl()
	 * @since 3.0.0
	 * @param string  $output Passed by reference. Used to append additional content.
	 * @param int     $depth  Depth of menu item. Used for padding.
	 * @param array   $args   An array of arguments. @see wp_nav_menu()
	 */
	function end_el( &$output, $item, $depth = 0, $args = array() ) {
		if ( $depth != 0 && $this->is_mega ) {
			$output .= '';
		} else {
 			$output .= '</li>';
 		}
	}
	/**
	 * Get menu items.
	 *
	 * @param   mixed    $menu
	 * @param   integer  $parent_id
	 * @param   integer  $depth
	 *
	 * @return  array
	 */
	public static function get_menu_items( $menu, $parent_id = 0, $depth = 1 ) {
		// Get all nav menu items.
		$menu_items = wp_get_nav_menu_items( $menu );
		$extracted_items = array();

		if ( $menu_items ) {
			$parents_set = array();

			foreach ( $menu_items as $item ) {
				if ( ! $parent_id ) {
					if ( $depth == 1 ) {
						// Get only the 1st level items.
						if ( ! $item->menu_item_parent ) {
							array_push( $extracted_items, $item );
						}
					}
				} else {
					// Get all sub menu items.
					if ( $item->menu_item_parent == $parent_id || in_array( $item->menu_item_parent, $parents_set ) ) {
						if ( $item->menu_item_parent == $parent_id ) {
							$parents_set[0] = $parent_id;
						}

						// Push current item id to parents list
						// used for calculating menuitem level
						// and get children menu items without recursiving.
						$sub_level = array_search( $item->menu_item_parent, $parents_set );
						$parents_set[ $sub_level + 1 ] = $item->ID;

						// Set level for current menu item.
						$item->sub_level = $sub_level + 1;

						// Place current item in the list.
						if ( $sub_level < $depth ) {
							array_push( $extracted_items, $item );
						}
					}
				}
			}
		}

		return $extracted_items;
	}
	public static function submenu( $menu_type, $menu_id ) {
		// Get all menu items.
		$menu_items = self::get_menu_items( $menu_type, $menu_id, 99 );

		// Prepare nav menu arguments.
		$args = array(
			'menu'        => $menu_type,
			'container'   => false,
			'menu_class'  => 'menu',
			'echo'        => true,
			'items_wrap'  => '<ul class="%2$s">%3$s</ul>',
			'count_items' => count( $menu_items ),
		);

		// Get mega menu data.
		$data = Adiva_Megamenu::get_data();
		$data = $data[ $menu_type ][ $menu_id ];

		$submenu_items_elment = self::submenu_child( $menu_items, 0, ( object ) $args );

		return $submenu_items_elment;
	}

	/**
	 * Process sub menu items.
	 *
	 * @return  mixed
	 */
	public static function submenu_child() {
		$args   = func_get_args();
		$walker = new Adiva_Megamenu_Walkersub;

		return call_user_func_array( array( &$walker, 'walk' ), $args );
	}

	public static function tabmenu( $menu_type, $menu_id ) {
		// Get all menu items.
		$menu_items = self::get_menu_items( $menu_type, $menu_id, 99 );

		// Prepare nav menu arguments.
		$args = array(
			'menu'        => $menu_type,
			'container'   => false,
			'menu_class'  => 'menu',
			'echo'        => true,
			'items_wrap'  => '<ul class="%2$s">%3$s</ul>',
			'count_items' => count( $menu_items ),
		);

		// Get mega menu data.
		$data = Adiva_Megamenu::get_data();
		$data = $data[ $menu_type ];
		$tab_titles = '<div class="jms-tab"><ul class="nav nav-tabs" role="tablist">';
		$tab_content = '<div class="tab-content">';
		$_index = 0;
		foreach($menu_items as $menu_item) {
			$tab_titles .= '<li '.(($_index == 0) ? 'class="active"' : null).'><a class="button" data-toggle="tab" href="#menu-tab-'.$menu_item->ID.'">'. ( (  ! empty( $data[$menu_item->ID]['icon_class'] ) && $data[$menu_item->ID]['icon_class'] ) ? '<i class="menu-icon ' . esc_attr( $data[$menu_item->ID]['icon_class'] ) . '"></i>' : NULL ).$menu_item->title.'</a></li>';
			$element_content = '';
			if ( $data[$menu_item->ID]['element_type'] == 'html' ) {
				$element_content = $data[$menu_item->ID]['html_data'];
			}
			$tab_content .= '<div role="tabpanel" class="tab-pane '.(($_index == 0) ? 'active' : null).'" id="menu-tab-'.$menu_item->ID.'">'.$element_content.'</div>';
			$_index++;
		}
		$tab_titles .= '</ul></div>';
		$tab_content .= '</div>';
		return $tab_titles.$tab_content;
	}

	public static function accordionmenu( $menu_type, $menu_id ) {
		// Get all menu items.
		$menu_items = self::get_menu_items( $menu_type, $menu_id, 99 );

		// Prepare nav menu arguments.
		$args = array(
			'menu'        => $menu_type,
			'container'   => false,
			'menu_class'  => 'menu',
			'echo'        => true,
			'items_wrap'  => '<ul class="%2$s">%3$s</ul>',
			'count_items' => count( $menu_items ),
		);

		// Get mega menu data.
		$data = Adiva_Megamenu::get_data();
		$data = $data[ $menu_type ];
		$accordion_content = '<div class="panel-group">';
		$_index = 0;
		foreach($menu_items as $menu_item) {
			$element_content = '';
			if ( $data[$menu_item->ID]['element_type'] == 'html' ) {
				$element_content = $data[$menu_item->ID]['html_data'];
			}
			$accordion_content .= '<div class="panel panel-default">
				<div class="panel-heading"><h4 class="panel-title">
					<a data-toggle="collapse" href="#menu-collapse-'.$menu_item->ID.'">'. ( (  ! empty( $data[$menu_item->ID]['icon_class'] ) && $data[$menu_item->ID]['icon_class'] ) ? '<i class="menu-icon ' . esc_attr( $data[$menu_item->ID]['icon_class'] ) . '"></i>' : NULL ).$menu_item->title.'</a>
				</h4>
			</div>';
			$accordion_content .= '<div id="menu-collapse-'.$menu_item->ID.'" class="panel-collapse collapse '.(($_index == 0) ? 'in' : null).'">
				<div class="panel-body">'.$element_content.'</div></div></div>';
			$_index++;
		}
		$accordion_content .= '</div>';
		return $accordion_content;
	}
}
