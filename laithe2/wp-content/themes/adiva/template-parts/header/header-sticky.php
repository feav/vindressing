<?php
    $showAccount        = adiva_get_option('show-account-box', 1);
    $showSearchForm     = adiva_get_option('show-search-form', 1);
    $showWishlistButton = adiva_get_option('show-wishlist-button', 1);
    $showCartButton     = adiva_get_option('show-cart-button', 1);
    $showToggleSidebar  = adiva_get_option('show-toggle-sidebar', 0);
    $catalog_mode       = adiva_get_option('catalog-mode', 0);
    $header_menu_align  = adiva_get_option('header-menu-align', 'center');
?>
<div class="header-sticky">
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
            <div class="header-position header-left header-logo">
                <div class="header-block">
                    <?php adiva_logo(); ?>
                </div>
            </div>
            <!-- header-logo -->
            <div class="header-position header-center hidden-sm hidden-xs main-navigation">
                <div class="header-block">
                    <?php if ( has_nav_menu('primary-menu') ) : ?>
                        <?php
                            if ( class_exists('Adiva_Megamenu_Walker') ) {
                                $menu = array(
                                    'theme_location'  => 'primary-menu',
                                    'container_class' => 'primary-menu-wrapper',
                                    'menu_class'      => 'adiva-menu primary-menu menu-' . esc_attr($header_menu_align),
                                    'walker'          => new Adiva_Megamenu_Walker,
                                );
                            } else {
                                $menu = array(
                                    'theme_location'  => 'primary-menu',
                                    'container_class' => 'primary-menu-wrapper',
                                    'menu_class'      => 'adiva-menu primary-menu menu-' . esc_attr($header_menu_align),
                                );
                            }

                            wp_nav_menu( $menu );
                        ?>
                    <?php else : ?>
                        <div class="primary-menu-wrapper">
                            <ul class="adiva-menu primary-menu menu-<?php echo esc_attr($header_menu_align); ?>">
                                <li><a href="<?php echo esc_url(home_url( '/' )) . 'wp-admin/nav-menus.php?action=locations'; ?>"><?php esc_html_e( 'Select or create a menu', 'adiva' ) ?></a></li>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <!-- main-navigation -->
            <div class="header-position header-right header-action">
                <?php if ( adiva_woocommerce_activated() && $showAccount == 1 ) : ?>
                    <div class="header-block hidden-sm hidden-xs">
                        <?php echo adiva_my_account(); ?>
                    </div>
                <?php endif; ?>
                <?php if ( $showSearchForm ) : ?>
                    <div class="header-block hidden-sm hidden-xs">
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
</div>
