<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<?php adiva_preloader(); ?>

	<div id="sidebar-open-overlay"></div>
	<?php if ( adiva_get_option('show-toggle-sidebar', 0) ) : ?>
		<div class="toggle-sidebar-widget toggleSidebar">
            <div class="closetoggleSidebar"></div>
            <div class="widget-area">
                <?php dynamic_sidebar( 'toggle-sidebar' ); ?>
            </div>
        </div>
    <?php endif; ?>

	<?php locate_template('template-parts/menu-sidebar-left.php', true);?>
	<?php locate_template('template-parts/menu-mobile.php', true);?>

	<div id="page" class="site">
		<?php adiva_promo_bar(); ?>
		<?php locate_template('template-parts/header/topbar.php', true); ?>
		<?php adiva_header(); ?>
		<?php adiva_page_title(); ?>
