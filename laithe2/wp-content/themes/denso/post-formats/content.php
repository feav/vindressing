<?php
/**
 *
 * The default template for displaying content
 * @since 1.0
 * @version 1.2.0
 *
 */

if ( !is_single() ) {
	$item_style = denso_get_blog_item_style();
	get_template_part( 'post-formats/loop/'.$item_style.'/_item' );
} else {
	get_template_part( 'post-formats/single/_single' );
}