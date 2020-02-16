<?php $showAccount = adiva_get_option('show-account-box', 1); ?>
<div class="adiva-mobile-menu">
    <div class="search-block-wrapper db">
        <?php get_search_form(); ?>
    </div>
    <div class="menu-title flex between-xs"><?php esc_html_e( 'MENU', 'adiva' ) ?><i class="sl icon-close close-menu"></i></div>
    <?php
    if ( has_nav_menu('primary-menu') ) {
        $args = array(
            'theme_location'  => 'primary-menu',
            'container_class' => 'mobile-menu-wrapper',
            'menu_class'      => 'mobile-menu',
        );
        wp_nav_menu( $args );
    }
    ?>
    <div class="bottom-mobile-wrapper">
        <?php if ( adiva_woocommerce_activated() && isset($showAccount) && $showAccount == 1 ) : ?>
            <div class="header-block">
                <div class="btn-group">
                    <?php if ( is_user_logged_in() ) : ?>
                        <a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" class="dropdown-toggle"><i class="sl icon-user"></i><?php esc_html_e('Login/Register', 'adiva'); ?></a>
                    <?php else : ?>
                        <a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" class="dropdown-toggle"><i class="sl icon-user"></i><?php esc_html_e('My Account', 'adiva'); ?></a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
