<?php
/**
 * ------------------------------------------------------------------------------------------------
 * Prepare CSS selectors for theme settions (colors, borders, typography etc.)
 * ------------------------------------------------------------------------------------------------
 */

if ( ! function_exists( 'adiva_custom_inline_css' ) ) {
    function adiva_custom_inline_css( $css = array() ) {
        if ( !class_exists ( 'ReduxFramework' ) ) return;

        // Color scheme
        $primary_color = adiva_get_option('primary-color', '#F86B73');
        $topbar_border_color = adiva_get_option('topbar-border-color', '');

        if ( isset($_GET['primary-color']) && $_GET['primary-color'] != '' ) {
            $primary_color = '#' . $_GET['primary-color'];

            $css[] = '
                .service-box .service-box-icon {
                    color: ' . esc_attr( $primary_color ) . ' !important;
                }
            ';
        }

        if ( isset($topbar_border_color) && $topbar_border_color != '' ) {
            $css[] = '
                .topbar {
                    border-color: ' . esc_attr( $topbar_border_color ) . ';
                }
            ';
        }

        if ( isset($primary_color) && $primary_color != '' ) {
            $css[] = '
                a:hover, a:focus, a:active,
                p a,
                .color,
                .result-wrapper .content-price ins
                .topbar.color-scheme-dark .topbar-menu li a:hover,
                .topbar.color-scheme-dark .topbar-menu li a:focus,
                .topbar.color-scheme-dark .dropdown-toggle:hover,
                .header-5 .bottom-menu li a:hover,
                .header-5 .social-list-icons li a:hover,
                .adiva-menu .dropdown-menu .column-heading:hover,
                .adiva-menu li a:hover,
                .adiva-menu li.current-menu-ancestor > a,
                .adiva-menu li.current-menu-item > a,
                #footer-wrapper a:hover,
                #footer-wrapper p a,
                #footer-wrapper .footer-bottom .copyright a,
                #footer-wrapper.color-scheme-light a:hover, #footer-wrapper.color-scheme-light time:hover,
                .not-found .entry-header:before,
                .blog-design-slider .blog-meta a:hover,
                .blog-design-slider .blog-meta i,
                .comments-area .reply a,
                .widget_ranged_price_filter .ranged-price-filter li.current,
                .widget_order_by_filter li.current,
                .widget_ranged_price_filter .ranged-price-filter li.current a,
                .widget_order_by_filter li.current a,
                .widget_categories ul li.current_page_item > a,
                .widget_pages ul li.current_page_item > a,
                .widget_archive ul li.current_page_item > a,
                .widget_nav_menu ul li.current_page_item > a,
                .widget_product_categories ul li.current_page_item > a,
                .product_list_widget > li ins,
                .special-filter .product-categories > li > a:hover,
                .special-filter .product-categories > li > a:focus,
                .special-filter .product-categories > li .count,
                .special-filter .product-categories > li.active > a,
                .product-box .product-cat a:hover,
                .product-box .price ins,
                .product-btn li .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistaddedbrowse.show a:before,
                .woocommerce-product-rating .woocommerce-review-link:hover,
                .product_meta li:before,
                .product_meta a:hover,
                .entry-summary .compare.button:hover,
                body.woocommerce-checkout .woocommerce > .woocommerce-info a,
                .title-color-primary .subtitle,
                .portfolio-filter > a.selected,
                .product-filter a.active,
                .jmsproduct-tab .nav-tabs > li.active > a,
                .jmsproduct-tab.design-tab-2 .nav-tabs > li.active > a,
                .adiva-price-table.price-style-alt .adiva-price-currency,
                .adiva-price-table.price-style-alt .adiva-price-value,
                .adiva-line-bullets .tp-bullet.selected .adiva-bullet-num,
                .custom-banner .btn-transparent-color,
                .custom-banner h3 span {
                    color: ' . esc_attr( $primary_color ) . ';
                }
            ';

            $css[] = '
                .btn.btn-color-primary,
                .button.btn-color-primary,
                button.btn-color-primary,
                .added_to_cart.btn-color-primary,
                input[type="submit"].btn-color-primary,
                input[type="button"].btn-color-primary,
                input[type="reset"].btn-color-primary,
                .btn-transparent:hover,
                .checkout-button,
                .coupon .button,
                .checkout_coupon .button,
                .actions .update-cart,
                #place_order,
                #customer_login .button,
                .owl-theme .owl-dots .owl-dot.active span,
                .owl-theme .owl-dots .owl-dot:hover span,
                #header-wishlist a span,
                #header-cart .cart-count,
                .topbar,
                .header-4 .main-navigation,
                .adiva-mobile-menu .menu-title,
                .contact-form-default .wpcf7-submit,
                .meta-post-categories .meta-post-categories-inner,
                .adiva-single-bottom .tags-list a:hover:after,
                .adiva-single-bottom .tags-list a:focus:after,
                .post-password-form input[type="submit"],
                .adiva-pagination .page-numbers li span:hover,
                .adiva-pagination .page-numbers li a:hover,
                .adiva-pagination .page-numbers li .current,
                .adiva-ajax-loadmore a:hover,
                .adiva-ajax-loadmore a:focus,
                .comment-form .submit,
                .page-links > span:not(.page-links-title),
                .adiva-entry-content .page-links > a,
                .adiva-entry-content .page-links > span:not(.page-links-title),
                .widget_calendar #wp-calendar tbody a,
                .tagcloud a:hover, .tagcloud a:focus,
                .widget_shopping_cart_content .buttons a:hover,
                .widget_price_filter .ui-slider .ui-slider-range,
                .badge,
                .product-btn li .button:hover,
                .product-btn li .button:focus,
                .product-btn li .yith-wcwl-add-to-wishlist:hover,
                .product-btn li .yith-wcwl-add-to-wishlist:focus,
                nav.woocommerce-pagination ul li a:focus,
                nav.woocommerce-pagination ul li a:hover,
                nav.woocommerce-pagination ul li span.current,
                .wc-single-video a:before,
                .single_add_to_cart_button,
                .woocommerce table.wishlist_table a.button,
                .woocommerce-MyAccount-content .button.view,
                input[type="submit"].dokan-btn,
                a.dokan-btn,
                .dokan-btn,
                input[type="submit"].dokan-btn:hover, input[type="submit"].dokan-btn:focus,
                a.dokan-btn:hover,
                a.dokan-btn:focus,
                .dokan-btn:hover,
                .dokan-btn:focus,
                .title-color-primary .subtitle.style-background,
                .portfolio-filter > a:before,
                .jmsproduct-tab .nav-tabs > li > a:after,
                .jmsproduct-tab.design-tab-2 .nav-tabs > li.active > a:after,
                .megamenu-widget-wrapper h3,
                .countdown-style-primary .adiva-countdown > span,
                .adiva-price-table .adiva-plan-footer > a,
                .adiva-price-table.price-style-default .adiva-plan-price,
                .spinner1 .bounce11,
                .spinner1 .bounce22,
                .spinner4 .bounce11,
                .spinner4 .bounce22,
                .spinner4 .bounce33,
                .spinner5,
                .spinner6 .dot11,
                .spinner6 .dot22,
                .product-list-info .product-btn .button-cart {
                    background-color: ' . esc_attr( $primary_color ) . ';
                }
            ';

            $css[] = '
                .tp-caption.btn-slider-primary,
                #rev_slider_65_1 .uranus .tp-bullet.selected .tp-bullet-inner,
                #rev_slider_65_1 .uranus .tp-bullet:hover .tp-bullet-inner,
                #rev_slider_65_1 .uranus .tp-bullet-inner,
                #rev_slider_68_1 .uranus .tp-bullet.selected .tp-bullet-inner,
                #rev_slider_68_1 .uranus .tp-bullet:hover .tp-bullet-inner,
                #rev_slider_68_1 .uranus .tp-bullet-inner,
                #rev_slider_69_1 .uranus .tp-bullet.selected .tp-bullet-inner,
                #rev_slider_69_1 .uranus .tp-bullet:hover .tp-bullet-inner,
                #rev_slider_69_1 .uranus .tp-bullet-inner,
                #slider-home-9 .uranus .tp-bullet.selected .tp-bullet-inner,
                #slider-home-9 .uranus .tp-bullet:hover .tp-bullet-inner,
                #slider-home-9 .uranus .tp-bullet-inner {
                    background-color: ' . esc_attr( $primary_color ) . ' !important;
                }
            ';

            $css[] = '
                #rev_slider_65_1 .uranus .tp-bullet-inner,
                #rev_slider_68_1 .uranus .tp-bullet-inner,
                #rev_slider_69_1 .uranus .tp-bullet-inner,
                #slider-home-9 .uranus .tp-bullet-inner {
                    opacity: 0.5;
                }
            ';

            $css[] = '
                .banner-1-5 .subsubtitle p,
                .banner-3-5 .subtitle p,
                .banner-5-5 .subtitle p,
                .banner-1-6 .subtitle p {
                    color: ' . esc_attr( $primary_color ) . ' !important;
                }
            ';

            $css[] = '
                #rev_slider_65_1 .uranus .tp-bullet.selected,
                #rev_slider_65_1 .uranus .tp-bullet:hover,
                #rev_slider_68_1 .uranus .tp-bullet.selected,
                #rev_slider_68_1 .uranus .tp-bullet:hover,
                #rev_slider_69_1 .uranus .tp-bullet.selected,
                #rev_slider_69_1 .uranus .tp-bullet:hover,
                #slider-home-9 .uranus .tp-bullet.selected,
                #slider-home-9 .uranus .tp-bullet:hover {
                    box-shadow: 0 0 0 2px ' . esc_attr( $primary_color ) . ' !important;
                }
            ';


            $css[] = '
                input[type="email"]:focus,
                input[type="date"]:focus,
                input[type="search"]:focus,
                input[type="number"]:focus,
                input[type="text"]:focus,
                input[type="tel"]:focus,
                input[type="url"]:focus,
                input[type="password"]:focus,
                textarea:focus,
                select:focus,
                .btn-transparent:hover,
                .newsletter-form input[type="email"]:focus,
                #newsletter-bottom .newsletter-form input[type="email"]:focus,
                .adiva-single-bottom .tags-list a:hover,
                .adiva-single-bottom .tags-list a:focus,
                .adiva-ajax-loadmore a:hover,
                .adiva-ajax-loadmore a:focus,
                .tagcloud a:hover, .tagcloud a:focus,
                .widget_shopping_cart_content .buttons a:hover,
                .product-btn li .button:hover,
                .product-btn li .button:focus,
                .entry-summary .attribute-wrap .imageswatch-variation.selected,
                .tabs-layout-tabs .wc-tabs > li.active,
                .testimonials-slider .owl-theme .owl-dots .owl-dot.active span,
                .adiva-price-table.actived .adiva-plan-inner,
                .adiva-line-bullets .tp-bullet.selected::after,
                .custom-banner .btn-transparent-color,
                .product-list-info .product-btn .button-cart,
                .product-list-info .product-btn .yith-wcwl-add-to-wishlist:hover {
                    border-color: ' . esc_attr( $primary_color ) . ';
                }
            ';
        }

        return preg_replace( '/\n|\t/i', '', implode( '', $css ) );
    }
}
