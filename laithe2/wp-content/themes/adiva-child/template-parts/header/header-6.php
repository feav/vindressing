<?php
    $showAccount        = adiva_get_option('show-account-box', 1);
    $showSearchForm     = adiva_get_option('show-search-form', 1);
    $showWishlistButton = adiva_get_option('show-wishlist-button', 1);
    $showCartButton     = adiva_get_option('show-cart-button', 1);
    $showToggleSidebar  = adiva_get_option('show-toggle-sidebar', 0);
    $catalog_mode       = adiva_get_option('catalog-mode', 0);
    $header_menu_align  = adiva_get_option('header-menu-align', 'center');
    $header_extra_text  = adiva_get_option('header-extra-text', '');

    global $post;

    $options = get_post_meta( get_the_ID(), '_custom_page_options', true );

    if(isset($options['open-categories']) && $options['open-categories'] == 1) {
        $opened = true;
    }

    if ( isset($_GET['menu_align']) && $_GET['menu_align'] != '' ) {
        $header_menu_align = $_GET['menu_align'];
    }

    if ( isset($_GET['toggle-sidebar']) && $_GET['toggle-sidebar'] != '' ) {
        $showToggleSidebar = $_GET['toggle-sidebar'];
    }

    if ( isset($_GET['catalog-mode']) && $_GET['catalog-mode'] == 1 ) {
        $catalog_mode = 1;
    }
?>
<div class="container">
    <div class="header-top wrap-header">
        <div class="header-position hidden-lg hidden-md menu-toggle">
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
        <div class="header-position hidden-sm hidden-xs header-search">
            <div class="header-block">
                <?php
                if ( class_exists('JmsAjaxSearch_Widget') ) {
                    the_widget('JmsAjaxSearch_Widget');
                } else {
                    get_search_form();
                }
                ?>
            </div>
        </div>
        <!-- header-search -->
        <div class="header-position header-action text-right">
			<?php if ( adiva_woocommerce_activated() && $showAccount == 1 ) : ?>
                <div class="header-block hidden-sm hidden-xs">
   
<a href="https://vindressing.fr/store-manager/products-manage/" style="padding: 5px 10px;;display: block;;float: none;;background: #17a2b8;border-bottom-color: #17a2b8;color: #ffffff;">
<span class="">Vendre</span></a>
</div>
 <?php if ( !is_user_logged_in() ) : ?>
  <div class="header-block hidden-sm hidden-xs">
  
<a href="<?php echo get_permalink( wc_get_page_id( 'myaccount' ) ); ?>" style="padding: 5px 10px;;display: block;;float: none;;background: #17a2b8;border-bottom-color: #17a2b8;color: #ffffff;">
<span class=""><?php echo esc_html__( 'Login', 'adiva' ); ?></span></a>
</div>
<?php endif; ?>
            <?php endif; ?>
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
<div class="header-bottom hidden-sm hidden-xs">
    <div class="container">
        <div class="main-menu hidden-sm hidden-xs">
            <div class="header-position header-vertical-menu hidden-sm hidden-xs">
                <div class="header-vertical-menu-inner pr<?php if( isset($opened) && $opened == 1 ) echo ' opened-menu'; ?>">
                    <h2 class="label-categories">
                        <span class="menu-hamburg"></span>
                        <?php esc_html_e('Categories', 'adiva'); ?>
                    </h2>
                    <?php if ( has_nav_menu('category-menu') ) : ?>
                        <?php
                            if ( class_exists('Adiva_Megamenu_Walker') ) {
                                $menu = array(
                                    'theme_location'  => 'category-menu',
                                    'container_class' => 'vertical-category-menu-wrapper',
                                    'menu_class'      => 'adiva-menu vertical-category-menu',
                                    'walker'          => new Adiva_Megamenu_Walker,
                                );
                            } else {
                                $menu = array(
                                    'theme_location'  => 'category-menu',
                                    'container_class' => 'vertical-category-menu-wrapper',
                                    'menu_class'      => 'adiva-menu vertical-category-menu',
                                );
                            }
                            wp_nav_menu( $menu );
                        ?>
                    <?php else : ?>
                        <div class="vertical-category-menu-wrapper">
                            <ul class="adiva-menu vertical-category-menu">
                                <li><a href="<?php echo esc_url(home_url( '/' )) . 'wp-admin/nav-menus.php?action=locations'; ?>"><?php esc_html_e( 'Select or create a menu', 'adiva' ) ?></a></li>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="header-position main-navigation hidden-sm hidden-xs">
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
            <?php if ( isset($header_extra_text) && $header_extra_text != '' ) : ?>
                <div class="header-position header-extra-text">
                    <div class="header-block">
                        <?php
                        $allowed_html = array(
                			'a' => array(
                				'href' => array(),
                				'title' => array(),
                                'class' => array(),
                			),
                			'br' => array(),
                			'em' => array(),
                			'strong' => array(),
                		);

                        echo wp_kses($header_extra_text, $allowed_html);
                        ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
	 <div class="header-block">
	<?php if ( adiva_woocommerce_activated() && $showAccount == 1 ) : ?>
                <div class="header-block col-sm-6 col-xs-6 hidden-md hidden-lg text-center">
   
<a href="https://vindressing.fr/store-manager/products-manage/" style="padding: 5px 10px;;display: block;;float: none;;background: #17a2b8;border-bottom-color: #17a2b8;color: #ffffff;">
<span class="">Vendre</span></a>
</div>
 <?php if ( !is_user_logged_in() ) : ?>
  <div class="header-block col-sm-6 col-xs-6 hidden-md hidden-lg text-center">
  
<a href="<?php echo get_permalink( wc_get_page_id( 'myaccount' ) ); ?>" style="padding: 5px 10px;;display: block;;float: none;;background: #17a2b8;border-bottom-color: #17a2b8;color: #ffffff;">
<span class=""><?php echo esc_html__( 'Login', 'adiva' ); ?></span></a>
</div>
<?php endif; ?>
            <?php endif; ?>
</div>
</div>
