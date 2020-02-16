<?php

add_action('init', 'denso_kingcomposer_init');
function denso_kingcomposer_init() {
    if ( function_exists( 'kc_add_icon' ) ) {
    	$css_folder = denso_get_css_folder();
		$min = denso_get_asset_min();
        kc_add_icon( $css_folder . '/font-monia'.$min.'.css' );
    }
 
}