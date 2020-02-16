<div id="searchverlay"></div>
<header id="apus-header" class="site-header apus-header header-v1 hidden-sm hidden-xs" role="banner">
    <div class="header-main clearfix">
        <div class="container-fluid">
            <div class="row">
                <div class="header-top">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="logo-in-theme text-center">
                                <?php get_template_part( 'page-templates/parts/logo' ); ?>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="heading-right">
                                <div class="header-apus-search">
                                    <?php get_template_part( 'page-templates/parts/productsearchform' ); ?>
                                </div>
                            </div>
                        </div>
                        <?php if ( denso_get_config('header_contact_info') ) : ?>
                            <div class="col-md-3">
                                <div class="contact-header">
                                    <?php echo trim(denso_get_config('header_contact_info')); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="<?php echo (denso_get_config('keep_header') ? 'main-sticky-header-wrapper' : ''); ?>">
                    <div class="header-bottom clearfix <?php echo (denso_get_config('keep_header') ? 'main-sticky-header' : ''); ?>">
                        <?php get_template_part( 'page-templates/parts/vertical-menu' ); ?>
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
                        <div class="pull-right  header-setting">
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
                            <!-- user info --> 
                            <div class="pull-right dropdown my-account">
                                <!-- Setting -->
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true" role="button" aria-haspopup="true" data-delay="0" title="<?php esc_html_e('My Account', 'denso'); ?>">
                                    <i class="mn-icon-415"></i>
                                </a>
                                <div class="dropdown-menu">
                                    <?php if ( has_nav_menu( 'topmenu' ) ): ?>
                                        <nav class="apus-topmenu" role="navigation">
                                            <?php
                                                $args = array(
                                                    'theme_location'  => 'topmenu',
                                                    'menu_class'      => 'apus-menu-top',
                                                    'fallback_cb'     => '',
                                                    'menu_id'         => 'topmenu'
                                                );
                                                wp_nav_menu($args);
                                            ?>
                                        </nav>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <!-- shipping -->
                            <?php if ( denso_get_config('header_freeshipping') ) : ?>
                                <div class="pull-right shipping">
                                    <?php echo trim(denso_get_config('header_freeshipping')); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>