<?php
add_shortcode( 'adiva_social_icons', 'adiva_shortcode_social_icons' );
add_shortcode( 'adiva_google_map', 'adiva_shortcode_google_map' );
add_shortcode( 'adiva_button', 'adiva_shortcode_buttons' );
add_shortcode( 'adiva_addons_title', 'adiva_shortcode_title' );
add_shortcode( 'adiva_addons_banner', 'adiva_shortcode_banner' );
add_shortcode( 'adiva_addons_service', 'adiva_shortcode_service' );
add_shortcode( 'team_member', 'adiva_shortcode_team_member' );
add_shortcode( 'adiva_addons_instagram', 'adiva_shortcode_instagram' );
add_shortcode( 'testimonials', 'adiva_shortcode_testimonials' );
add_shortcode( 'testimonial', 'adiva_shortcode_testimonial' );
add_shortcode( 'adiva_blog_carousel', 'adiva_shortcode_blog_carousel' );
add_shortcode( 'adiva_brand_carousel', 'adiva_shortcode_brand_carousel' );
add_shortcode( 'adiva_megamenu', 'adiva_shortcode_megamenu' );
add_shortcode( 'adiva_addons_gallery', 'adiva_shortcode_gallery' );
add_shortcode( 'adiva_countdown', 'adiva_shortcode_countdown_timer' );
add_shortcode( 'pricing_tables', 'adiva_shortcode_pricing_tables' );
add_shortcode( 'pricing_plan', 'adiva_shortcode_pricing_plan' );
add_shortcode( 'adiva_divider', 'adiva_shortcode_section_divider' );
// Product Carousel
if( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
    add_shortcode( 'adiva_addons_product_tab', 'adiva_shortcode_product_tab' );
    add_shortcode( 'adiva_products', 'adiva_shortcode_products' );
}
