<?php
if ( ! class_exists( 'VC_Manager' ) ) return;

$list = array(
    'page',
    'product',
    'portfolio',
);
vc_set_default_editor_post_types( $list );

if( ! function_exists( 'adiva_vc_map_shortcodes' ) ) {
	function adiva_vc_map_shortcodes() {
        vc_remove_element( 'product' );
        vc_remove_element( 'products' );
    	vc_remove_element( 'recent_products' );
    	vc_remove_element( 'featured_products' );
    	vc_remove_element( 'product_category' );
    	vc_remove_element( 'sale_products' );
    	vc_remove_element( 'best_selling_products' );
    	vc_remove_element( 'top_rated_products' );
    	vc_remove_element( 'product_attribute' );
    }
    add_action( 'vc_before_init', 'adiva_vc_map_shortcodes' );
}

/**
 * Add icon stroke for vc
 *
 * @return array
 */

if ( !function_exists('adiva_vc_icon_stroke') ) {
    function adiva_vc_icon_stroke( $icons ) {
    	$stroke_icons = array(
    		array( 'pe-7s-album' => 'Album' ),
    		array( 'pe-7s-arc' => 'Arc' ),
    		array( 'pe-7s-back-2' => 'Back-2' ),
    		array( 'pe-7s-bandaid' => 'Bandaid' ),
    		array( 'pe-7s-car' => 'Car' ),
    		array( 'pe-7s-diamond' => 'Diamond' ),
    		array( 'pe-7s-door-lock' => 'Door-lock' ),
    		array( 'pe-7s-eyedropper' => 'Eyedropper' ),
    		array( 'pe-7s-female' => 'Female' ),
    		array( 'pe-7s-gym' => 'Gym' ),
    		array( 'pe-7s-hammer' => 'Hammer' ),
    		array( 'pe-7s-headphones' => 'Headphones' ),
    		array( 'pe-7s-helm' => 'Helm' ),
    		array( 'pe-7s-hourglass' => 'Hourglass' ),
    		array( 'pe-7s-leaf' => 'Leaf' ),
    		array( 'pe-7s-magic-wand' => 'Magic-wand' ),
    		array( 'pe-7s-male' => 'Male' ),
    		array( 'pe-7s-map-2' => 'Map-2' ),
    		array( 'pe-7s-next-2' => 'Next-2' ),
    		array( 'pe-7s-paint-bucket' => 'Paint-bucket' ),
    		array( 'pe-7s-pendrive' => 'Pendrive' ),
    		array( 'pe-7s-photo' => 'Photo' ),
    		array( 'pe-7s-piggy' => 'Piggy' ),
    		array( 'pe-7s-plugin' => 'Plugin' ),
    		array( 'pe-7s-refresh-2' => 'Refresh-2' ),
    		array( 'pe-7s-rocket' => 'Rocket' ),
    		array( 'pe-7s-settings' => 'Settings' ),
    		array( 'pe-7s-shield' => 'Shield' ),
    		array( 'pe-7s-smile' => 'Smile' ),
    		array( 'pe-7s-usb' => 'Usb' ),
    		array( 'pe-7s-vector' => 'Vector' ),
    		array( 'pe-7s-wine' => 'Wine' ),
    		array( 'pe-7s-cloud-upload' => 'Cloud-upload' ),
    		array( 'pe-7s-cash' => 'Cash' ),
    		array( 'pe-7s-close' => 'Close' ),
    		array( 'pe-7s-bluetooth' => 'Bluetooth' ),
    		array( 'pe-7s-cloud-download' => 'Cloud-download' ),
    		array( 'pe-7s-way' => 'Way' ),
    		array( 'pe-7s-close-circle' => 'Close-circle' ),
    		array( 'pe-7s-id' => 'Id' ),
    		array( 'pe-7s-angle-up' => 'Angle-up' ),
    		array( 'pe-7s-wristwatch' => 'Wristwatch' ),
    		array( 'pe-7s-angle-up-circle' => 'Angle-up-circle' ),
    		array( 'pe-7s-world' => 'World' ),
    		array( 'pe-7s-angle-right' => 'Angle-right' ),
    		array( 'pe-7s-volume' => 'Volume' ),
    		array( 'pe-7s-angle-right-circle' => 'Angle-right-circle' ),
    		array( 'pe-7s-users' => 'Users' ),
    		array( 'pe-7s-angle-left' => 'Angle-left' ),
    		array( 'pe-7s-user-female' => 'User-female' ),
    		array( 'pe-7s-angle-left-circle' => 'Angle-left-circle' ),
    		array( 'pe-7s-up-arrow' => 'Up-arrow' ),
    		array( 'pe-7s-angle-down' => 'Angle-down' ),
    		array( 'pe-7s-switch' => 'Switch' ),
    		array( 'pe-7s-angle-down-circle' => 'Angle-down-circle' ),
    		array( 'pe-7s-scissors' => 'Scissors' ),
    		array( 'pe-7s-wallet' => 'Wallet' ),
    		array( 'pe-7s-safe' => 'Safe' ),
    		array( 'pe-7s-volume2' => 'Volume2' ),
    		array( 'pe-7s-volume1' => 'Volume1' ),
    		array( 'pe-7s-voicemail' => 'Voicemail' ),
    		array( 'pe-7s-video' => 'Video' ),
    		array( 'pe-7s-user' => 'User' ),
    		array( 'pe-7s-upload' => 'Upload' ),
    		array( 'pe-7s-unlock' => 'Unlock' ),
    		array( 'pe-7s-umbrella' => 'Umbrella' ),
    		array( 'pe-7s-trash' => 'Trash' ),
    		array( 'pe-7s-tools' => 'Tools' ),
    		array( 'pe-7s-timer' => 'Timer' ),
    		array( 'pe-7s-ticket' => 'Ticket' ),
    		array( 'pe-7s-target' => 'Target' ),
    		array( 'pe-7s-sun' => 'Sun' ),
    		array( 'pe-7s-study' => 'Study' ),
    		array( 'pe-7s-stopwatch' => 'Stopwatch' ),
    		array( 'pe-7s-star' => 'Star' ),
    		array( 'pe-7s-speaker' => 'Speaker' ),
    		array( 'pe-7s-signal' => 'Signal' ),
    		array( 'pe-7s-shuffle' => 'Shuffle' ),
    		array( 'pe-7s-shopbag' => 'Shopbag' ),
    		array( 'pe-7s-share' => 'Share' ),
    		array( 'pe-7s-server' => 'Server' ),
    		array( 'pe-7s-search' => 'Search' ),
    		array( 'pe-7s-film' => 'Film' ),
    		array( 'pe-7s-science' => 'Science' ),
    		array( 'pe-7s-disk' => 'Disk' ),
    		array( 'pe-7s-ribbon' => 'Ribbon' ),
    		array( 'pe-7s-repeat' => 'Repeat' ),
    		array( 'pe-7s-refresh' => 'Refresh' ),
    		array( 'pe-7s-add-user' => 'Add-user' ),
    		array( 'pe-7s-refresh-cloud' => 'Refresh-cloud' ),
    		array( 'pe-7s-paperclip' => 'Paperclip' ),
    		array( 'pe-7s-radio' => 'Radio' ),
    		array( 'pe-7s-note2' => 'Note2' ),
    		array( 'pe-7s-print' => 'Print' ),
    		array( 'pe-7s-network' => 'Network' ),
    		array( 'pe-7s-prev' => 'Prev' ),
    		array( 'pe-7s-mute' => 'Mute' ),
    		array( 'pe-7s-power' => 'Power' ),
    		array( 'pe-7s-medal' => 'Medal' ),
    		array( 'pe-7s-portfolio' => 'Portfolio' ),
    		array( 'pe-7s-like2' => 'Like2' ),
    		array( 'pe-7s-plus' => 'Plus' ),
    		array( 'pe-7s-left-arrow' => 'Left-arrow' ),
    		array( 'pe-7s-play' => 'Play' ),
    		array( 'pe-7s-key' => 'Key' ),
    		array( 'pe-7s-plane' => 'Plane' ),
    		array( 'pe-7s-joy' => 'Joy' ),
    		array( 'pe-7s-photo-gallery' => 'Photo-gallery' ),
    		array( 'pe-7s-pin' => 'Pin' ),
    		array( 'pe-7s-phone' => 'Phone' ),
    		array( 'pe-7s-plug' => 'Plug' ),
    		array( 'pe-7s-pen' => 'Pen' ),
    		array( 'pe-7s-right-arrow' => 'Right-arrow' ),
    		array( 'pe-7s-paper-plane' => 'Paper-plane' ),
    		array( 'pe-7s-delete-user' => 'Delete-user' ),
    		array( 'pe-7s-paint' => 'Paint' ),
    		array( 'pe-7s-bottom-arrow' => 'Bottom-arrow' ),
    		array( 'pe-7s-notebook' => 'Notebook' ),
    		array( 'pe-7s-note' => 'Note' ),
    		array( 'pe-7s-next' => 'Next' ),
    		array( 'pe-7s-news-paper' => 'News-paper' ),
    		array( 'pe-7s-musiclist' => 'Musiclist' ),
    		array( 'pe-7s-music' => 'Music' ),
    		array( 'pe-7s-mouse' => 'Mouse' ),
    		array( 'pe-7s-more' => 'More' ),
    		array( 'pe-7s-moon' => 'Moon' ),
    		array( 'pe-7s-monitor' => 'Monitor' ),
    		array( 'pe-7s-micro' => 'Micro' ),
    		array( 'pe-7s-menu' => 'Menu' ),
    		array( 'pe-7s-map' => 'Map' ),
    		array( 'pe-7s-map-marker' => 'Map-marker' ),
    		array( 'pe-7s-mail' => 'Mail' ),
    		array( 'pe-7s-mail-open' => 'Mail-open' ),
    		array( 'pe-7s-mail-open-file' => 'Mail-open-file' ),
    		array( 'pe-7s-magnet' => 'Magnet' ),
    		array( 'pe-7s-loop' => 'Loop' ),
    		array( 'pe-7s-look' => 'Look' ),
    		array( 'pe-7s-lock' => 'Lock' ),
    		array( 'pe-7s-lintern' => 'Lintern' ),
    		array( 'pe-7s-link' => 'Link' ),
    		array( 'pe-7s-like' => 'Like' ),
    		array( 'pe-7s-light' => 'Light' ),
    		array( 'pe-7s-less' => 'Less' ),
    		array( 'pe-7s-keypad' => 'Keypad' ),
    		array( 'pe-7s-junk' => 'Junk' ),
    		array( 'pe-7s-info' => 'Info' ),
    		array( 'pe-7s-home' => 'Home' ),
    		array( 'pe-7s-help2' => 'Help2' ),
    		array( 'pe-7s-help1' => 'Help1' ),
    		array( 'pe-7s-graph3' => 'Graph3' ),
    		array( 'pe-7s-graph2' => 'Graph2' ),
    		array( 'pe-7s-graph1' => 'Graph1' ),
    		array( 'pe-7s-graph' => 'Graph' ),
    		array( 'pe-7s-global' => 'Global' ),
    		array( 'pe-7s-gleam' => 'Gleam' ),
    		array( 'pe-7s-glasses' => 'Glasses' ),
    		array( 'pe-7s-gift' => 'Gift' ),
    		array( 'pe-7s-folder' => 'Folder' ),
    		array( 'pe-7s-flag' => 'Flag' ),
    		array( 'pe-7s-filter' => 'Filter' ),
    		array( 'pe-7s-file' => 'File' ),
    		array( 'pe-7s-expand1' => 'Expand1' ),
    		array( 'pe-7s-exapnd2' => 'Exapnd2' ),
    		array( 'pe-7s-edit' => 'Edit' ),
    		array( 'pe-7s-drop' => 'Drop' ),
    		array( 'pe-7s-drawer' => 'Drawer' ),
    		array( 'pe-7s-download' => 'Download' ),
    		array( 'pe-7s-display2' => 'Display2' ),
    		array( 'pe-7s-display1' => 'Display1' ),
    		array( 'pe-7s-diskette' => 'Diskette' ),
    		array( 'pe-7s-date' => 'Date' ),
    		array( 'pe-7s-cup' => 'Cup' ),
    		array( 'pe-7s-culture' => 'Culture' ),
    		array( 'pe-7s-crop' => 'Crop' ),
    		array( 'pe-7s-credit' => 'Credit' ),
    		array( 'pe-7s-copy-file' => 'Copy-file' ),
    		array( 'pe-7s-config' => 'Config' ),
    		array( 'pe-7s-compass' => 'Compass' ),
    		array( 'pe-7s-comment' => 'Comment' ),
    		array( 'pe-7s-coffee' => 'Coffee' ),
    		array( 'pe-7s-cloud' => 'Cloud' ),
    		array( 'pe-7s-clock' => 'Clock' ),
    		array( 'pe-7s-check' => 'Check' ),
    		array( 'pe-7s-chat' => 'Chat' ),
    		array( 'pe-7s-cart' => 'Cart' ),
    		array( 'pe-7s-camera' => 'Camera' ),
    		array( 'pe-7s-call' => 'Call' ),
    		array( 'pe-7s-calculator' => 'Calculator' ),
    		array( 'pe-7s-browser' => 'Browser' ),
    		array( 'pe-7s-box2' => 'Box2' ),
    		array( 'pe-7s-box1' => 'Box1' ),
    		array( 'pe-7s-bookmarks' => 'Bookmarks' ),
    		array( 'pe-7s-bicycle' => 'Bicycle' ),
    		array( 'pe-7s-bell' => 'Bell' ),
    		array( 'pe-7s-battery' => 'Battery' ),
    		array( 'pe-7s-ball' => 'Ball' ),
    		array( 'pe-7s-back' => 'Back' ),
    		array( 'pe-7s-attention' => 'Attention' ),
    		array( 'pe-7s-anchor' => 'Anchor' ),
    		array( 'pe-7s-albums' => 'Albums' ),
    		array( 'pe-7s-alarm' => 'Alarm' ),
    		array( 'pe-7s-airplay' => 'Airplay' ),
    	);

    	return array_merge( $icons, $stroke_icons );
    }
    add_filter( 'vc_iconpicker-type-stroke', 'adiva_vc_icon_stroke' );
}



/**
 * Add icon linearicons for vc
 *
 * @return array
 */

if ( ! function_exists('adiva_vc_icon_linearicons') ) {
    function adiva_vc_icon_linearicons( $icons ) {
    	$linear_icons = array(
    		array( 'lnr lnr-home' => 'home' ),
    		array( 'lnr lnr-apartment' => '' ),
    		array( 'lnr lnr-pencil' => '' ),
    		array( 'lnr lnr-magic-wand' => '' ),
    		array( 'lnr lnr-lighter' => '' ),
    		array( 'lnr lnr-poop' => ''),
    		array( 'lnr lnr-sun' => ''),
    		array( 'lnr lnr-moon' => ''),
    		array( 'lnr lnr-cloud' => ''),
    		array( 'lnr lnr-cloud-upload' => ''),
    		array( 'lnr lnr-cloud-download' => ''),
    		array( 'lnr lnr-cloud-sync' => ''),
    		array( 'lnr lnr-cloud-check' => ''),
            array( 'lnr lnr-database' => ''),
    		array( 'lnr lnr-lock' => ''),
            array( 'lnr lnr-cog' => ''),
    		array( 'lnr lnr-trash' => ''),
            array( 'lnr lnr-dice' => ''),
    		array( 'lnr lnr-heart' => ''),
            array( 'lnr lnr-star' => ''),
    		array( 'lnr lnr-star-half' => ''),
            array( 'lnr lnr-star-empty' => ''),
    		array( 'lnr lnr-flag' => ''),
            array( 'lnr lnr-envelope' => ''),
    		array( 'lnr lnr-paperclip' => ''),
            array( 'lnr lnr-inbox' => ''),
    		array( 'lnr lnr-eye' => ''),
            array( 'lnr lnr-printer' => ''),
    		array( 'lnr lnr-file-empty' => ''),
            array( 'lnr lnr-file-add' => ''),
    		array( 'lnr lnr-enter' => ''),
            array( 'lnr lnr-exit' => ''),
    		array( 'lnr lnr-graduation-hat' => ''),
            array( 'lnr lnr-license' => ''),
    		array( 'lnr lnr-music-note' => ''),
            array( 'lnr lnr-film-play' => ''),
            array( 'lnr lnr-camera-video' => ''),
    		array( 'lnr lnr-camera' => ''),
            array( 'lnr lnr-picture' => ''),
    		array( 'lnr lnr-book' => ''),
            array( 'lnr lnr-bookmark' => ''),
    		array( 'lnr lnr-user' => ''),
            array( 'lnr lnr-users' => ''),
            array( 'lnr lnr-shirt' => ''),
    		array( 'lnr lnr-store' => ''),
            array( 'lnr lnr-cart' => ''),
    		array( 'lnr lnr-tag' => ''),
            array( 'lnr lnr-phone-handset' => ''),
    		array( 'lnr lnr-phone' => ''),
            array( 'lnr lnr-pushpin' => ''),
            array( 'lnr lnr-volume' => ''),
    		array( 'lnr lnr-volume-low' => ''),
            array( 'lnr lnr-volume-medium' => ''),
    		array( 'lnr lnr-volume-high' => ''),
            array( 'lnr lnr-bullhorn' => ''),
    		array( 'lnr lnr-alarm' => ''),
            array( 'lnr lnr-mustache' => ''),
    		array( 'lnr lnr-neutral' => ''),
            array( 'lnr lnr-sad' => ''),
    		array( 'lnr lnr-smile' => ''),
            array( 'lnr lnr-earth' => ''),
    		array( 'lnr lnr-select' => ''),
            array( 'lnr lnr-bicycle' => ''),
    		array( 'lnr lnr-wheelchair' => ''),
            array( 'lnr lnr-train' => ''),
    		array( 'lnr lnr-car' => ''),
            array( 'lnr lnr-bus' => ''),
    		array( 'lnr lnr-briefcase' => ''),
            array( 'lnr lnr-rocket' => ''),
    		array( 'lnr lnr-paw' => ''),
            array( 'lnr lnr-leaf' => ''),
    		array( 'lnr lnr-coffee-cup' => ''),
            array( 'lnr lnr-dinner' => ''),
    		array( 'lnr lnr-linearicons' => ''),
            array( 'lnr lnr-diamond' => ''),
    		array( 'lnr lnr-gift' => ''),
            array( 'lnr lnr-chart-bars' => ''),
    		array( 'lnr lnr-pie-chart' => ''),
            array( 'lnr lnr-construction' => ''),
    		array( 'lnr lnr-heart-pulse' => ''),
            array( 'lnr lnr-bubble' => ''),
    		array( 'lnr lnr-power-switch' => ''),
            array( 'lnr lnr-laptop-phone' => ''),
    		array( 'lnr lnr-tablet' => ''),
            array( 'lnr lnr-laptop' => ''),
    		array( 'lnr lnr-smartphone' => ''),
            array( 'lnr lnr-screen' => ''),
    		array( 'lnr lnr-spell-check' => ''),
            array( 'lnr lnr-keyboard' => ''),
    		array( 'lnr lnr-calendar-full' => ''),
            array( 'lnr lnr-map-marker' => ''),
    		array( 'lnr lnr-map' => ''),
            array( 'lnr lnr-location' => ''),
    		array( 'lnr lnr-arrow-right' => ''),
            array( 'lnr lnr-arrow-left' => ''),
    		array( 'lnr lnr-arrow-down' => ''),
            array( 'lnr lnr-arrow-up' => ''),
    		array( 'lnr lnr-chevron-right' => ''),
            array( 'lnr lnr-chevron-left' => ''),
            array( 'lnr lnr-chevron-up' => ''),
    		array( 'lnr lnr-chevron-down' => ''),
            array( 'lnr lnr-list' => ''),
    		array( 'lnr lnr-menu' => ''),
            array( 'lnr lnr-cross' => ''),
            array( 'lnr lnr-magnifier' => ''),
    		array( 'lnr lnr-thumbs-down' => ''),
            array( 'lnr lnr-thumbs-up' => ''),
    		array( 'lnr lnr-unlink' => ''),
            array( 'lnr lnr-link' => ''),
            array( 'lnr lnr-code' => ''),
    		array( 'lnr lnr-bug' => ''),
            array( 'lnr lnr-exit-up' => ''),
    		array( 'lnr lnr-enter-down' => ''),
            array( 'lnr lnr-upload' => ''),
            array( 'lnr lnr-download' => ''),
    		array( 'lnr lnr-mic' => ''),
            array( 'lnr lnr-hourglass' => ''),
    		array( 'lnr lnr-history' => ''),
            array( 'lnr lnr-clock' => ''),
            array( 'lnr lnr-undo' => ''),
    		array( 'lnr lnr-redo' => ''),
            array( 'lnr lnr-sync' => ''),
            array( 'lnr lnr-arrow-right-circle' => ''),
            array( 'lnr lnr-chevron-up-circle' => ''),
            array( 'lnr lnr-chevron-down-circle' => ''),
            array( 'lnr lnr-chevron-left-circle' => ''),
            array( 'lnr lnr-chevron-right-circle' => ''),
            array( 'lnr lnr-crop' => ''),
            array( 'lnr lnr-arrow-left-circle' => ''),
            array( 'lnr lnr-arrow-down-circle' => ''),
            array( 'lnr lnr-arrow-up-circle' => ''),
            array( 'lnr lnr-circle-minus' => ''),
            array( 'lnr lnr-plus-circle' => ''),
            array( 'lnr lnr-cross-circle' => ''),
            array( 'lnr lnr-checkmark-circle' => ''),
            array( 'lnr lnr-menu-circle' => ''),
            array( 'lnr lnr-question-circle' => ''),
            array( 'lnr lnr-warning' => ''),
            array( 'lnr lnr-move' => ''),
            array( 'lnr lnr-italic' => ''),
            array( 'lnr lnr-bold' => ''),
            array( 'lnr lnr-text-size' => ''),
            array( 'lnr lnr-text-format-remove' => ''),
            array( 'lnr lnr-text-format' => ''),
            array( 'lnr lnr-funnel' => ''),
            array( 'lnr lnr-layers' => ''),
            array( 'lnr lnr-frame-contract' => ''),
            array( 'lnr lnr-frame-expand' => ''),
    		array( 'lnr lnr-underline' => ''),
            array( 'lnr lnr-strikethrough' => ''),
            array( 'lnr lnr-highlight' => ''),
            array( 'lnr lnr-text-align-left' => ''),
            array( 'lnr lnr-text-align-center' => ''),
            array( 'lnr lnr-text-align-right' => ''),
            array( 'lnr lnr-text-align-justify' => ''),
            array( 'lnr lnr-line-spacing' => ''),
            array( 'lnr lnr-indent-increase' => ''),
            array( 'lnr lnr-indent-decrease' => ''),
            array( 'lnr lnr-pilcrow' => ''),
            array( 'lnr lnr-direction-ltr' => ''),
            array( 'lnr lnr-direction-rtl' => ''),
            array( 'lnr lnr-page-break' => ''),
            array( 'lnr lnr-sort-alpha-asc' => ''),
            array( 'lnr lnr-sort-amount-asc' => ''),
            array( 'lnr lnr-hand' => ''),
            array( 'lnr lnr-pointer-up' => ''),
            array( 'lnr lnr-pointer-right' => ''),
            array( 'lnr lnr-pointer-down' => ''),
            array( 'lnr lnr-pointer-left' => ''),
    	);

    	return array_merge( $icons, $linear_icons );
    }
    add_filter( 'vc_iconpicker-type-linearicons', 'adiva_vc_icon_linearicons' );
}

/**
 * Enqueue stylesheets and scripts in admin.
 *
 * @return  void
 */

if ( ! function_exists('adiva_vc_enqueue_scripts') ) {
    function adiva_vc_enqueue_scripts() {
    	if ( ! function_exists( 'vc_editor_post_types' ) ) {
    		return;
    	}

    	// Get post type enabled for editing with Visual Composer.
    	$types = vc_editor_post_types();

    	// Check if current post type is enabled
    	global $post;

    	if ( isset( $post->post_type ) && in_array( $post->post_type, $types ) ) {
    		wp_enqueue_style( 'font-stroke', get_template_directory_uri() . '/assets/3rd-party/font-stroke/css/pe-icon-7-stroke.css' );
            wp_enqueue_style( 'linearicons', get_template_directory_uri() . '/assets/3rd-party/linearicons/style.css', array(), '1.0.0' );
    	}

    }
    add_action( 'admin_enqueue_scripts', 'adiva_vc_enqueue_scripts' );
}

if(class_exists('WPBakeryShortCodesContainer')){
    class WPBakeryShortCode_adiva_google_map extends WPBakeryShortCodesContainer {

    }
}

if(class_exists('WPBakeryShortCodesContainer')){
    class WPBakeryShortCode_testimonials extends WPBakeryShortCodesContainer {

    }
}

// Replace Wbc_Inner_Item with your base name from mapping for nested element
if(class_exists('WPBakeryShortCode')){
    class WPBakeryShortCode_testimonial extends WPBakeryShortCode {

    }
}

// A must for container functionality, replace Wbc_Item with your base name from mapping for parent container
if( class_exists( 'WPBakeryShortCodesContainer' ) ){
    class WPBakeryShortCode_pricing_tables extends WPBakeryShortCodesContainer {

    }
}

// Replace Wbc_Inner_Item with your base name from mapping for nested element
if( class_exists( 'WPBakeryShortCode' ) ){
    class WPBakeryShortCode_pricing_plan extends WPBakeryShortCode {

    }
}


//For param: ID default value filter
add_filter( 'vc_form_fields_render_field_adiva_products_id_param_value', 'productIdDefaultValue', 10, 4 );

/**
 * Suggester for autocomplete by id/name/title/sku
 * @since 4.4
 *
 * @param $query
 *
 * @return array - id's from products with title/sku.
 */

if ( !function_exists('productIdAutocompleteSuggester') ) {
    function productIdAutocompleteSuggester( $query ) {
        global $wpdb;
        $product_id = (int) $query;
        $post_meta_infos = $wpdb->get_results( $wpdb->prepare( "SELECT a.ID AS id, a.post_title AS title, b.meta_value AS sku
                    FROM {$wpdb->posts} AS a
                    LEFT JOIN ( SELECT meta_value, post_id  FROM {$wpdb->postmeta} WHERE `meta_key` = '_sku' ) AS b ON b.post_id = a.ID
                    WHERE a.post_type = 'product' AND ( a.ID = '%d' OR b.meta_value LIKE '%%%s%%' OR a.post_title LIKE '%%%s%%' )", $product_id > 0 ? $product_id : - 1, stripslashes( $query ), stripslashes( $query ) ), ARRAY_A );

        $results = array();
        if ( is_array( $post_meta_infos ) && ! empty( $post_meta_infos ) ) {
            foreach ( $post_meta_infos as $value ) {
                $data = array();
                $data['value'] = $value['id'];
                $data['label'] = __( 'Id', 'adiva' ) . ': ' . $value['id'] . ( ( strlen( $value['title'] ) > 0 ) ? ' - ' . __( 'Title', 'adiva' ) . ': ' . $value['title'] : '' ) . ( ( strlen( $value['sku'] ) > 0 ) ? ' - ' . __( 'Sku', 'adiva' ) . ': ' . $value['sku'] : '' );
                $results[] = $data;
            }
        }

        return $results;
    }
    add_filter( 'vc_autocomplete_adiva_products_id_callback', 'productIdAutocompleteSuggester', 10, 1 );
}

/**
 * Find product by id
 * @since 4.4
 *
 * @param $query
 *
 * @return bool|array
 */

if ( ! function_exists('productIdAutocompleteRender') ) {
    function productIdAutocompleteRender( $query ) {
        $query = trim( $query['value'] ); // get value from requested
        if ( ! empty( $query ) ) {
            // get product
            $product_object = wc_get_product( (int) $query );
            if ( is_object( $product_object ) ) {
                $product_sku = $product_object->get_sku();
                $product_title = $product_object->get_title();
                $product_id = $product_object->get_id();

                $product_sku_display = '';
                if ( ! empty( $product_sku ) ) {
                    $product_sku_display = ' - ' . __( 'Sku', 'adiva' ) . ': ' . $product_sku;
                }

                $product_title_display = '';
                if ( ! empty( $product_title ) ) {
                    $product_title_display = ' - ' . __( 'Title', 'adiva' ) . ': ' . $product_title;
                }

                $product_id_display = __( 'Id', 'adiva' ) . ': ' . $product_id;

                $data = array();
                $data['value'] = $product_id;
                $data['label'] = $product_id_display . $product_title_display . $product_sku_display;

                return ! empty( $data ) ? $data : false;
            }

            return false;
        }

        return false;
    }
    add_filter( 'vc_autocomplete_adiva_products_id_render', 'productIdAutocompleteRender', 10, 1 );
}

/**
 * ------------------------------------------------------------------------------------------------
 * Add options to columns
 * ------------------------------------------------------------------------------------------------
 */

if( ! function_exists( 'adiva_update_vc_column' ) ) {
    function adiva_update_vc_column() {
        if( !function_exists('vc_map') ) return;
        vc_add_param( 'vc_column', array(
            'type'        => 'checkbox',
            'group'       => esc_html__( 'Adiva Extras', 'adiva' ),
            'heading'     => esc_html__( 'Enable sticky column', 'adiva' ),
            'description' => esc_html__( 'Also enable equal columns height for the parent row to make it work', 'adiva' ),
            'param_name'  => 'adiva_sticky_column'
        ) );
    }
    add_action( 'init', 'adiva_update_vc_column');
}


if( ! function_exists( 'adiva_vc_extra_classes' ) ) {

	if( defined( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG' ) ) {
		add_filter( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'adiva_vc_extra_classes', 30, 3 );
	}

	function adiva_vc_extra_classes( $class, $base, $atts ) {
		if( ! empty( $atts['adiva_sticky_column'] ) ) {
			$class .= ' adiva-sticky-column';
		}

		return $class;
	}

}
