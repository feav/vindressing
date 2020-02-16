<?php
/*
* [ Theme Functions. ] - - - - - - - - - - - - - - - - - - - -
*/
require ADIVA_PATH . '/inc/functions/theme.php';
require ADIVA_PATH . '/inc/functions/helpers.php';
if ( class_exists( 'VC_Manager' ) ) require ADIVA_PATH . '/inc/functions/vc_functions.php';

/*
* [ WooCommerce Customizer. ] - - - - - - - - - - - - - - - - - - - -
*/
if ( adiva_woocommerce_activated() ) require ADIVA_PATH . '/inc/functions/woocommerce.php';

/*
* [ Widget ] - - - - - - - - - - - - - - - - - - - -
*/
foreach (glob( ADIVA_PATH . '/inc/widgets/*.php' ) as $filename) {
    include_once $filename;
}

/*
* [ Theme Options. ] - - - - - - - - - - - - - - - - - - - -
*/
if ( class_exists ( 'ReduxFramework' ) ) {
    require ADIVA_PATH . '/inc/admin/theme-options.php';
}
require ADIVA_PATH . '/inc/admin/tgm-functions.php';
require ADIVA_PATH . '/inc/selectors.php';
