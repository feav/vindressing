<div id="searchverlay"></div>
<header id="apus-header" class="site-header apus-header header-v2 header-v5 header-v6 hidden-sm hidden-xs" role="banner">
    <div class="header-main clearfix">
        <div id="apus-topbar" class="apus-topbar">
            <div class="topbar-inner clearfix">
                <div class="container">
                    
                    <?php if ( denso_get_config('header_welcome') ) : ?>
                        <div class="pull-left welcome-topbar">
                            <?php echo trim(denso_get_config('header_welcome')); ?>
                        </div>
                    <?php endif; ?>
                    <?php if ( has_nav_menu( 'topmenu' ) ): ?>
                    <div class="pull-right wrapper-topmenu hidden-xs hidden-sm">
                        <nav class="apus-topmenu" role="navigation">
                                <?php
                                    $args = array(
                                        'theme_location'  => 'topmenu',
                                        'menu_class'      => 'apus-menu-top list-inline',
                                        'fallback_cb'     => '',
                                        'menu_id'         => 'topmenu'
                                    );
                                    wp_nav_menu($args);
                                ?>
                        </nav>                                                                     
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="header-menu clearfix">
            <div class="container">
                <div class="logo-in-theme pull-left">
                    <?php get_template_part( 'page-templates/parts/logo' ); ?>
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

                <?php if ( denso_get_config('header_contact_info') ) : ?>
                    <div class="pull-right contact-header">
                        <?php echo trim(denso_get_config('header_contact_info')); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="<?php echo (denso_get_config('keep_header') ? 'main-sticky-header-wrapper' : ''); ?>">
            <div class="header-inner clearfix <?php echo (denso_get_config('keep_header') ? 'main-sticky-header' : ''); ?>">
                <div class="container">
                    <?php get_template_part( 'page-templates/parts/vertical-menu' ); ?>

                    <div class="heading-right pull-left hidden-sm hidden-xs">
                        <div class="header-apus-search">
                            <?php get_template_part( 'page-templates/parts/productsearchform' ); ?>
                        </div>
                    </div>

                    <div class="pull-right  header-setting">
                        <!-- Cart -->
                        <?php if ( defined('DENSO_WOOCOMMERCE_ACTIVED') && DENSO_WOOCOMMERCE_ACTIVED ): ?>
                            <div class="pull-right">
                                <div class="top-cart hidden-xs">
                                    <?php get_template_part( 'woocommerce/cart/mini-cart-button' ); ?>
                                </div>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>