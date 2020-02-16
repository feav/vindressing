<?php
/**
 * Portfolio custom post type.
 */

// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

class Adiva_Addons_Portfolio {
	/**
	 * Construct function.
	 *
	 * @return  void
	 */
	function __construct() {
		add_action( 'init', array( __CLASS__, 'portfolio_init' ) );
	}

	/**
	 * Register a portfolio post type.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_post_type
	 */
	public static function portfolio_init() {
		$labels = array(
			'name'                => _x( 'Portfolio', 'Post Type General Name', 'adiva-addons' ),
			'singular_name'       => _x( 'Portfolio', 'Post Type Singular Name', 'adiva-addons' ),
			'menu_name'           => __( 'Portfolio', 'adiva-addons' ),
			'parent_item_colon'   => __( 'Parent Item:', 'adiva-addons' ),
			'all_items'           => __( 'All Items', 'adiva-addons' ),
			'view_item'           => __( 'View Item', 'adiva-addons' ),
			'add_new_item'        => __( 'Add New Item', 'adiva-addons' ),
			'add_new'             => __( 'Add New', 'adiva-addons' ),
			'edit_item'           => __( 'Edit Item', 'adiva-addons' ),
			'update_item'         => __( 'Update Item', 'adiva-addons' ),
			'search_items'        => __( 'Search Item', 'adiva-addons' ),
			'not_found'           => __( 'Not found', 'adiva-addons' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'adiva-addons' ),
		);

		register_post_type( 'portfolio',
			array(
				'label'               => __( 'portfolio', 'adiva-addons' ),
				'labels'              => $labels,
				'supports'            => array( 'title', 'editor', 'thumbnail' ),
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => true,
				'show_in_admin_bar'   => true,
				'menu_position'       => 28,
				'menu_icon'           => 'dashicons-images-alt2',
				'can_export'          => true,
				'has_archive'         => true,
				'exclude_from_search' => true,
				'publicly_queryable'  => true,
				'rewrite'             => array('slug' => 'portfolio'),
				'capability_type'     => 'page',
			)
 		);


		/**
		 * Create a taxonomy category for portfolio
		 *
		 * @uses  Inserts new taxonomy object into the list
		 * @uses  Adds query vars
		 *
		 * @param string  Name of taxonomy object
		 * @param array|string  Name of the object type for the taxonomy object.
		 * @param array|string  Taxonomy arguments
		 * @return null|WP_Error WP_Error if errors, otherwise null.
		 */

		$cat_labels = array(
			'name'					=> _x( 'Portfolio Categories', 'Taxonomy plural name', 'adiva-addons' ),
			'singular_name'			=> _x( 'Portfolio Category', 'Taxonomy singular name', 'adiva-addons' ),
			'search_items'			=> __( 'Search Categories', 'adiva-addons' ),
			'popular_items'			=> __( 'Popular Portfolio Categories', 'adiva-addons' ),
			'all_items'				=> __( 'All Portfolio Categories', 'adiva-addons' ),
			'parent_item'			=> __( 'Parent Category', 'adiva-addons' ),
			'parent_item_colon'		=> __( 'Parent Category', 'adiva-addons' ),
			'edit_item'				=> __( 'Edit Category', 'adiva-addons' ),
			'update_item'			=> __( 'Update Category', 'adiva-addons' ),
			'add_new_item'			=> __( 'Add New Category', 'adiva-addons' ),
			'new_item_name'			=> __( 'New Category', 'adiva-addons' ),
			'add_or_remove_items'	=> __( 'Add or remove Categories', 'adiva-addons' ),
			'choose_from_most_used'	=> __( 'Choose from most used text-domain', 'adiva-addons' ),
			'menu_name'				=> __( 'Categories', 'adiva-addons' ),
		);

		register_taxonomy( 'portfolio-cat', array( 'portfolio' ),
			array(
				'labels'            => $cat_labels,
				'public'            => true,
				'show_in_nav_menus' => true,
				'show_admin_column' => false,
				'hierarchical'      => true,
				'show_tagcloud'     => true,
				'show_ui'           => true,
				'query_var'         => true,
				'rewrite'           => true,
				'query_var'         => true,
				'capabilities'      => array(),
			)
		);

		// Register portfolio tag
		register_taxonomy( 'portfolio-tag',
			'portfolio',
			array(
				'hierarchical'          => false,
				'show_ui'               => true,
				'show_admin_column'     => true,
				'update_count_callback' => '_update_post_term_count',
				'query_var'             => true,
				'rewrite'               => array( 'slug' => 'portfolio-tag' ),
				'labels'                => array(
					'name'                       => __( 'Tags', 'adiva-addons' ),
					'singular_name'              => __( 'Tag', 'adiva-addons' ),
					'search_items'               => __( 'Search Tags', 'adiva-addons' ),
					'popular_items'              => __( 'Popular Tags', 'adiva-addons' ),
					'all_items'                  => __( 'All Tags', 'adiva-addons' ),
					'parent_item'                => null,
					'parent_item_colon'          => null,
					'edit_item'                  => __( 'Edit Tag', 'adiva-addons' ),
					'update_item'                => __( 'Update Tag', 'adiva-addons' ),
					'add_new_item'               => __( 'Add New Tag', 'adiva-addons' ),
					'new_item_name'              => __( 'New Tag Name', 'adiva-addons' ),
					'separate_items_with_commas' => __( 'Separate writers with commas', 'adiva-addons' ),
					'add_or_remove_items'        => __( 'Add or remove writers', 'adiva-addons' ),
					'choose_from_most_used'      => __( 'Choose from the most used writers', 'adiva-addons' ),
					'not_found'                  => __( 'No writers found.', 'adiva-addons' ),
					'menu_name'                  => __( 'Tags', 'adiva-addons' ),
				),
			)
		);

	}

}
$portfolio = new Adiva_Addons_Portfolio;
