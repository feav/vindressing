<div id="searchverlay"></div>
<header id="apus-header" class="site-header apus-header header-v4 hidden-sm hidden-xs" role="banner">
    <div class="header-main">
        <div class="header-menu">
            <div class="container-inner clearfix">
                <?php if ( denso_get_config('header_contact_info') ) : ?>
                    <div class="pull-left contact-header">
                        <?php echo trim(denso_get_config('header_contact_info')); ?>
                    </div>
                <?php endif; ?>
                <div class="pull-right">
                    <?php if( !is_user_logged_in() ){ ?>
                        <div class="login-topbar">
                            <a class="login" href="<?php echo esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ); ?>" title="<?php esc_html_e('Login','denso'); ?>"><?php esc_html_e('Login /', 'denso'); ?></a>
                            <a class="register" href="<?php echo esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ); ?>" title="<?php esc_html_e('Sign Up','denso'); ?>"><?php esc_html_e('Register', 'denso'); ?></a>
                        </div>
                    <?php } else { ?>
                        <?php if ( has_nav_menu( 'topmenu' ) ): ?>
                            <div class="wrapper-topmenu">
                                <div class="dropdown">
                                    <a href="#" data-toggle="dropdown" aria-expanded="true" role="button" aria-haspopup="true" data-delay="0">
                                        <?php esc_html_e( 'My Account', 'denso' ); ?><span class="caret"></span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <?php
                                            $args = array(
                                                'theme_location' => 'topmenu',
                                                'container_class' => 'collapse navbar-collapse',
                                                'menu_class' => 'nav navbar-nav',
                                                'fallback_cb' => '',
                                                'menu_id' => 'topmenu-menu',
                                                'walker' => new Denso_Nav_Menu()
                                            );
                                            wp_nav_menu($args);
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="<?php echo (denso_get_config('keep_header') ? 'main-sticky-header-wrapper' : ''); ?>">
            <div class="p-relative header-inner clearfix <?php echo (denso_get_config('keep_header') ? 'main-sticky-header' : ''); ?>">
                <div class="container-inner">
                    <div class="logo-in-theme pull-left">
                        <?php get_template_part( 'page-templates/parts/logo-white' ); ?>
                    </div>
                    <?php if ( has_nav_menu( 'primary' ) ) : ?>
                        <div class="main-menu  pull-left">
                            <nav 
                             data-duration="400" class="hidden-xs hidden-sm apus-megamenu slide animate navbar" role="navigation">
                            <?php   $args = array(
                                    'theme_location' => 'primary',
                                    'container_class' => 'collapse navbar-collapse',
                                    'menu_class' => 'nav navbar-nav megamenu',
                                    'fallback_cb' => '',
                                    'menu_id' => 'primary-menu',
                                    'walker' => new Denso_Nav_Menu()
                                );
                                wp_nav_menu($args);
                            ?>
                            </nav>
                        </div>
                    <?php endif; ?>

                    <div class="pull-right header-setting">
                        <!-- Cart -->
                        <?php if ( defined('DENSO_WOOCOMMERCE_ACTIVED') && DENSO_WOOCOMMERCE_ACTIVED ): ?>
                            <div class="pull-right">
                                <div class="top-cart hidden-xs">
                                    <?php get_template_part( 'woocommerce/cart/mini-cart-button' ); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <!-- Wishlist -->
                        <?php if ( defined('DENSO_WOOCOMMERCE_WISHLIST_ACTIVED') && DENSO_WOOCOMMERCE_WISHLIST_ACTIVED && class_exists( 'YITH_WCWL' ) ):
                            $wishlist_url = YITH_WCWL()->get_wishlist_url();
                        ?>
                            <div class="pull-right">
                                <a class="wishlist-icon" href="<?php echo esc_url($wishlist_url);?>" title="<?php esc_html_e( 'View Your Wishlist', 'denso' ); ?>"><i class="mn-icon-1246"></i></a>
                            </div>
                        <?php endif; ?>

                        <!-- Compere -->
                        <?php if ( defined('DENSO_WOOCOMMERCE_COMPARE_ACTIVED') && DENSO_WOOCOMMERCE_COMPARE_ACTIVED ): ?>
                            <div class="pull-right">
                                <!-- Setting -->
                                <a href="<?php echo esc_url(get_home_url()); ?>?action=yith-woocompare-view-table&iframe=1" class="view-popup-compare" title="<?php esc_html_e( 'View Your Compare', 'denso' ); ?>">
                                    <i class="mn-icon-1013"></i>
                                    <?php
                                    $nb_compare = 0;
                                    if ( class_exists('YITH_Woocompare_Frontend') ) {
                                        if ( isset($_COOKIE['yith_woocompare_list']) ) {
                                            $nb_compare = count(json_decode( $_COOKIE['yith_woocompare_list'] ));
                                        }
                                    }
                                    ?>
                                    <span><?php echo trim($nb_compare); ?></span>
                                </a>
                            </div>
                        <?php endif; ?>
                        <button type="button" class="button-show-search button-setting"><i class="mn-icon-52"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="header-search">
        <div class="header-apus-search">
            <div class="container">
                <?php get_template_part( 'page-templates/parts/productsearchform' ); ?>
                <button class="close-search-form" type="button">
                    <i class="mn-icon-4"></i>
                </button>
            </div>
        </div>
    </div>
</header>