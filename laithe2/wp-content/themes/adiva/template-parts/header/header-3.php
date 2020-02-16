<?php
    $showLanguage       = adiva_get_option('show-language-box', 0);
    $showCurrency       = adiva_get_option('show-currency-box', 0);
    $showAccount        = adiva_get_option('show-account-box', 1);
    $showSearchForm     = adiva_get_option('show-search-form', 1);
    $showWishlistButton = adiva_get_option('show-wishlist-button', 1);
    $showCartButton     = adiva_get_option('show-cart-button', 1);
    $showToggleSidebar  = adiva_get_option('show-toggle-sidebar', 0);
    $catalog_mode       = adiva_get_option('catalog-mode', 0);

    if ( isset($_GET['toggle-sidebar']) && $_GET['toggle-sidebar'] != '' ) {
        $showToggleSidebar = $_GET['toggle-sidebar'];
    }

    if ( isset($_GET['catalog-mode']) && $_GET['catalog-mode'] == 1 ) {
        $catalog_mode = 1;
    }
?>
<div class="container">
    <div class="wrap-header">
        <div class="header-position hidden-lg hidden-md menu-toggle">
            <div class="header-block">
                <div class="menu-button">
                    <i class="icon-menu"></i>
                </div>
            </div>
        </div>
        <!-- menu-toggle -->
        <div class="header-position hidden-sm hidden-xs header-left">
            <div class="header-block">
                <div class="menu-toggle dib">
                    <i class="icon-menu"></i>
                    <span><?php esc_html_e('Menu', 'adiva'); ?></span>
                </div>
            </div>
            <div class="header-block">
                <div class="btn-group box-hover" id="header-search">
                    <a href="javascript:void(0)" class="dropdown-toggle">
                        <i class="sl icon-magnifier"></i>
                    </a>
                    <div class="dropdown-menu">
                        <?php
                        if ( class_exists('JmsAjaxSearch_Widget_NoCats') ) {
                            the_widget('JmsAjaxSearch_Widget_NoCats');
                        } else {
                            get_search_form();
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- header-logo -->
        <div class="header-position header-logo header-center">
            <div class="header-block">
                <?php adiva_logo(); ?>
            </div>
        </div>
        <!-- header-logo -->
        <div class="header-position header-right header-action">
            <?php if ( adiva_woocommerce_activated() && $showAccount == 1 ) : ?>
                <div class="header-block hidden-sm hidden-xs">
                    <?php echo adiva_my_account(); ?>
                </div>
            <?php endif; ?>
            <?php if ( $showWishlistButton && adiva_woocommerce_activated() && class_exists( 'YITH_WCWL' ) ) : ?>
                <div class="header-block hidden-sm hidden-xs">
                    <?php adiva_wishlist(); ?>
                </div>
            <?php endif; ?>
            <?php if ( adiva_woocommerce_activated() && $showCartButton && !$catalog_mode ) : ?>
                <div class="header-block">
                    <?php adiva_header_cart(); ?>
                </div>
            <?php endif; ?>
            <?php if ( $showToggleSidebar ) : ?>
                <div class="header-block hidden-sm hidden-xs">
                    <div class="menu-toggle dib">
                        <i class="icon-menu"></i>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <!-- header-action -->
    </div>
</div>
