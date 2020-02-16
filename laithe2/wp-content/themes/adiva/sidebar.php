<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Adiva
 * @since 1.0.0
 * @version 1.0.0
 */

if ( ! is_active_sidebar( 'sidebar-store' ) ) {
	return;
}
?>

<div id="main-sidebar" class="dokan-store-sidebar col-md-3 col-sm-4 col-xs-12">
	<?php dynamic_sidebar( 'sidebar-store' ); ?>
</aside><!-- #secondary -->
