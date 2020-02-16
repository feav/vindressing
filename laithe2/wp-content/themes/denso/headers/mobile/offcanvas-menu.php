<div id="apus-mobile-menu" class="apus-offcanvas hidden-lg hidden-md"> 
    <div class="apus-offcanvas-body">
        <?php get_template_part( 'page-templates/parts/productsearchform' ); ?>
        <?php if ( has_nav_menu( 'primary' ) ) : ?>
            <nav class="navbar navbar-offcanvas navbar-static" role="navigation">
                <?php
                    $args = array(
                        'theme_location' => 'primary',
                        'container_class' => 'navbar-collapse navbar-offcanvas-collapse',
                        'menu_class' => 'nav navbar-nav',
                        'fallback_cb' => '',
                        'menu_id' => 'main-mobile-menu',
                        'walker' => new Denso_Mobile_Menu()
                    );
                    wp_nav_menu($args);
                ?>
            </nav>
        <?php endif; ?>
        <?php if ( has_nav_menu( 'vertical_menu' ) ) : ?>
            <h2 class="vertical-title">
                <?php esc_html_e('Shop By Department', 'denso'); ?>
            </h2>
            <nav class="navbar navbar-offcanvas navbar-static" role="navigation">
                <?php
                    $args = array(
                        'theme_location' => 'vertical_menu',
                        'container_class' => 'navbar-collapse navbar-offcanvas-collapse vertical',
                        'menu_class' => 'nav navbar-nav',
                        'fallback_cb' => '',
                        'menu_id' => 'main-mobile-menu-vertical',
                        'walker' => new Denso_Mobile_Menu()
                    );
                    wp_nav_menu($args);
                ?>
            </nav>
        <?php endif; ?>
        <?php if ( has_nav_menu( 'topmenu' ) ) { ?>
            <h3 class="setting"><i class="fa fa-cog" aria-hidden="true"></i> <?php esc_html_e( 'Setting', 'denso' ); ?></h3>
                <?php
                    $args = array(
                        'theme_location'  => 'topmenu',
                        'container_class' => '',
                        'menu_class'      => 'menu-topbar'
                    );
                    wp_nav_menu($args);
                ?>
        <?php } ?>
    </div>
</div>