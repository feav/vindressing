<?php

if ( !function_exists( 'denso_product_metaboxes' ) ) {
	function denso_product_metaboxes(array $metaboxes) {
		$prefix = 'apus_product_';
	    $fields = array(
	    	array(
				'name' => esc_html__( 'Review Video', 'denso' ),
				'id'   => $prefix.'review_video',
				'type' => 'text',
				'description' => esc_html__( 'You can enter a video youtube or vimeo', 'denso' ),
			),
			array(
				'name' => esc_html__( 'Features', 'denso' ),
				'id'   => $prefix.'features',
				'type' => 'wysiwyg'
			),
    	);
		
	    $metaboxes[$prefix . 'display_setting'] = array(
			'id'                        => $prefix . 'display_setting',
			'title'                     => esc_html__( 'More Information', 'denso' ),
			'object_types'              => array( 'product' ),
			'context'                   => 'normal',
			'priority'                  => 'low',
			'show_names'                => true,
			'fields'                    => $fields
		);

	    return $metaboxes;
	}
}
add_filter( 'cmb2_meta_boxes', 'denso_product_metaboxes' );
