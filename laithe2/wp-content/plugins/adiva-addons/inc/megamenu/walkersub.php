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
 * Mega menu custom walker for sub menu.
 */
class Adiva_Megamenu_Walkersub extends Walker_Nav_Menu {
	var $is_not_insert_first = true;
	var $order_column = 0;

	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		// Get data menu item
		$jsondata = Adiva_Megamenu::get_data();
		$data = isset( $jsondata[ $args->menu ][ $item->ID ] ) ? $jsondata[ $args->menu ][ $item->ID ] : NULL;

		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		$class_names = $value = '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;
		if(isset($data['align']) && $data['align']) {
			$classes[] = 'menu-align-'.$data['align'];
		}

		if( $item->sub_level == 1 ) {
			/* Parse get row percent */
			$row_layouts   = isset( $jsondata[ $args->menu ][ $item->menu_item_parent ]['submenu_layout'] ) ? $jsondata[ $args->menu ][ $item->menu_item_parent ]['submenu_layout'] : '';
			$row_layouts = explode( '-', $row_layouts );
			$row_layout = isset( $row_layouts[ $this->order_column ] ) ? $row_layouts[ $this->order_column ] : '12';
			$pre_layout = isset( $row_layouts[ (int)$this->order_column - 1 ] ) ? $row_layouts[ (int)$this->order_column - 1 ] : '12';
			if( $this->is_not_insert_first ) {
				if($row_layout[0] =='[') {
					$output .= '<div class="row">';
					$this->row_open = true;
					$row_layout = substr($row_layout, 1, 2);
				}	
				$output .= '<ul class="mega-nav col-sm-'.$row_layout.'">';
				$this->is_not_insert_first = false;
			} else {
				$output .= '</ul>';
				if($pre_layout[strlen($pre_layout) -1] == ']') {
					$output .= '</div>';
					$this->row_open = false;
				}				
				if($row_layout[0] == '[') {
					$output .= '<div class="row">';
					$this->row_open = true;
					$row_layout = substr($row_layout, 1, 2);
				}
				if($row_layout[strlen($row_layout) -1] == ']') {					
					$row_layout = substr($row_layout, 0, 1);
				}
				$output .= '<ul class="mega-nav col-sm-'.$row_layout.'">';
			}

			$this->order_column++;
		} else if( $item->sub_level == 0 ) {
			$this->order_column = 0;
		}

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$output .= $indent . '<li ' . $value . $class_names . '>';

		// Set tag a
		$item_output = ( isset( $args->before ) ? $args->before : '' );

		if ( ! ( $item->sub_level == 1 && !isset( $data['show_title'] ) && $data['show_title'] == 0 ) ) {

			$atts = array();
			$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
			$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
			$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
			$atts['href']   = ! empty( $item->url )        ? $item->url        : '';

			$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

			$atts['class'] = isset( $atts['class'] ) ? $atts['class'] . ' menu-item-link' : 'menu-item-link';


			if( $this->has_children ){
				$atts['class'] .= ' has-children';
			}

			if( $item->sub_level == 1 && $data['column_heading'] == 1){
				$atts['class'] .= ' column-heading';
			}

			$attributes = '';
			foreach ( $atts as $attr => $value ) {
				if ( ! empty( $value ) ) {
					$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}
			if($data['show_title']) {
				$item_output .= '<a ' . $attributes . ' >'
				. ( (  ! empty( $data['icon_class'] ) && $data['icon_class'] ) ? '<i class="menu-icon ' . esc_attr( $data['icon_class'] ) . '"></i>' : NULL )
				. '<span class="menu_title">' . $item->title . '</span>'
				. ( $this->has_children ? '<i class="fa fa-angle-down mm-has-children"></i>' : NULL )
				.'</a>' ;
			}
		}

		// Insert element
		if ( $item->sub_level == 1 && isset( $data['element_type'] ) && $data['element_type'] ) {
			$element_content = NULL;
			if ( $data['element_type'] == 'html' ) {
				$element_content = $data['html_data'];
			}

			$item_output .= $element_content ? '<div class="content-element ' . $data['element_type'] . '">' . $element_content . '</div>' : NULL;
		}

		$item_output .= ( isset( $args->after ) ? $args->after : '' );

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

}
