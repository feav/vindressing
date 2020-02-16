<?php if ( has_nav_menu( 'vertical_menu' ) ) : ?>
    <div class="pull-left">
        <aside class="widget widget_apus_vertical_menu">
            <h2 class="widget-title">
                <span class="desktop-icon"><?php esc_html_e('Shop By Department', 'denso'); ?><i class="mn-icon-161" ></i></span>
                <span class="tablet-icon"><i class="fa fa-bars" aria-hidden="true"></i></span>
            </h2>
            <aside class="widget-vertical-menu">
                <?php
                $args = array(
                    'theme_location' => 'vertical_menu',
                    'container_class' => 'collapse navbar-collapse navbar-ex1-collapse apus-vertical-menu menu-left',
                    'menu_class' => 'nav navbar-nav navbar-vertical-mega',
                    'fallback_cb' => '',
                    'walker' => new Denso_Nav_Menu()
                );
                wp_nav_menu($args);
                ?>
            </aside>
        </aside>
    </div>
<?php endif; ?>