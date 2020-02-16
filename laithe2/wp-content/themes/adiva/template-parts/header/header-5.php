<?php
    $showAccount        = adiva_get_option( 'show-account-box', 1 );
    $showLanguage       = adiva_get_option( 'show-language-box', 1 );
    $showCurrency       = adiva_get_option( 'show-currency-box', 1 );
    $showSearchForm     = adiva_get_option( 'show-search-form', 1 );
    $showWishlistButton = adiva_get_option( 'show-wishlist-button', 1 );
    $showCartButton     = adiva_get_option( 'show-cart-button', 1 );
    $catalog_mode       = adiva_get_option( 'catalog-mode', 0 );

    if ( isset($_GET['catalog-mode']) && $_GET['catalog-mode'] == 1 ) {
        $catalog_mode = 1;
    }
?>
<div class="wrap-header hidden-sm hidden-xs">
    <div class="top-header">
        <div class="header-inner">
            <div class="header-position header-logo">
                <div class="header-block">
                    <?php adiva_logo(); ?>
                </div>
            </div>
            <!-- header-logo -->
            <div class="header-position header-action pt_70">
                <?php if ( $showSearchForm ) : ?>
                    <div class="header-block hidden-xs">
                        <div class="btn-group" id="header-search">
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
                <?php endif; ?>
                <?php if ( adiva_woocommerce_activated() && $showAccount == 1 ) : ?>
                    <div class="header-block hidden-xs">
                        <?php echo adiva_my_account(); ?>
                    </div>
                <?php endif; ?>
                <?php if ( $showWishlistButton && adiva_woocommerce_activated() && class_exists( 'YITH_WCWL' ) ) : ?>
                    <div class="header-block hidden-xs">
                        <?php adiva_wishlist(); ?>
                    </div>
                <?php endif; ?>
                <?php if ( adiva_woocommerce_activated() && $showCartButton && !$catalog_mode ) : ?>
                    <div class="header-block">
                        <?php adiva_header_cart(); ?>
                    </div>
                <?php endif; ?>
            </div>
            <!-- header-action -->
        </div>
    </div>
    <div class="main-navigation">
        <div class="header-block">
            <?php if ( has_nav_menu('primary-menu') ) : ?>
                <?php
                    if ( class_exists('Adiva_Megamenu_Walker') ) {
                        $menu = array(
                            'theme_location'  => 'primary-menu',
                            'container_class' => 'vertical-menu-wrapper',
                            'menu_class'      => 'adiva-menu vertical-menu',
                            'walker'          => new Adiva_Megamenu_Walker,
                        );
                    } else {
                        $menu = array(
                            'theme_location'  => 'primary-menu',
                            'container_class' => 'vertical-menu-wrapper',
                            'menu_class'      => 'adiva-menu vertical-menu',
                        );
                    }

                    wp_nav_menu( $menu );
                ?>
            <?php else : ?>
                <div class="vertical-menu-wrapper">
                    <ul class="adiva-menu vertical-menu">
                        <li><a href="<?php echo esc_url(home_url( '/' )) . 'wp-admin/nav-menus.php?action=locations'; ?>"><?php esc_html_e( 'Create a menu', 'adiva' ) ?></a></li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="vertical_menu_social">
        <?php if ( has_nav_menu('bottom-menu') ) : ?>
            <?php
                $bottom_menu = array(
                    'theme_location'  => 'bottom-menu',
                    'container_class' => 'bottom-menu-wrapper',
                    'menu_class'      => 'bottom-menu',
                    'depth'           => 1
                );
                wp_nav_menu( $bottom_menu );
            ?>
        <?php endif; ?>
        <?php adiva_social_icons(); ?>
    </div>
</div>

<div class="container hidden-lg hidden-md">
    <div class="wrap-header bottom">
        <div class="header-position menu-toggle">
            <div class="header-block">
                <div class="menu-button">
                    <i class="icon-menu"></i>
                </div>
            </div>
        </div>
        <!-- menu-toggle -->
        <div class="header-position header-logo">
            <div class="header-block">
                <?php adiva_logo(); ?>
            </div>
        </div>
        <!-- header-logo -->
        <div class="header-position header-action">
            <?php if ( adiva_woocommerce_activated() && $showCartButton && !$catalog_mode ) : ?>
                <div class="header-block">
                    <?php adiva_header_cart(); ?>
                </div>
            <?php endif; ?>
        </div>
        <!-- header-action -->
    </div>
</div>
