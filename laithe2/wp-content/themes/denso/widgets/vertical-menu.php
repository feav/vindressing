<?php
extract( $args );
extract( $instance );

if ( $title ) {
    echo ($before_title)  . '<span class="desktop-icon">'. trim( $title ) . '<i class="mn-icon-161" ></i></span><span class="tablet-icon"><i class="fa fa-bars" aria-hidden="true"></i>
</span>' . $after_title;
}
$nav_menu = ( $nav_menu !='' ) ? wp_get_nav_menu_object( $nav_menu ) : false;
if ( $nav_menu && class_exists('Denso_Nav_Menu') ) {
	$position_class = ($position=='left') ? 'menu-left' : 'menu-right';
	$args = array(
	    'menu' => $nav_menu,
	    'container_class' => 'collapse navbar-collapse navbar-ex1-collapse apus-vertical-menu '.$position_class,
	    'menu_class' => 'nav navbar-nav navbar-vertical-mega',
	    'fallback_cb' => '',
	    'walker' => new Denso_Nav_Menu()
	);
	?>
	<aside class="widget-vertical-menu">
	    <?php wp_nav_menu($args); ?>
	</aside>
<?php } ?>