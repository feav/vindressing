    <div class="clearfix"></div>

    <?php
        $show_footer      = adiva_get_option('show-footer', 1);
        $show_copyright   = adiva_get_option('show-copyright', 1);
        $footer_column    = adiva_get_option('footer-column', 4);
        $footer_color     = adiva_get_option('footer-color', 'dark');
        $copyright        = adiva_get_option('footer-copyright', '2018 ADIVA Store. All Rights Reserved.');
        $copyright_layout = adiva_get_option('copyright-layout', 'three-columns');
        $payment_url      = adiva_get_option('footer-payment', '');

        global $post;
        // Get page options
        $options = get_post_meta( get_the_ID(), '_custom_page_options', true );

        if ( isset( $options['disable-footer'] ) && $options['disable-footer'] == 1 ) $show_footer = 0;
        if ( isset( $options['disable-copyright'] ) && $options['disable-copyright'] == 1 ) $show_copyright = 0;

        // Footer Column
        $footer_class = '';
        if ( $footer_column == 4) {
            $footer_class = 'footer-position col-lg-3 col-md-3 col-sm-6 col-xs-12 mb_40';
        } elseif ( $footer_column == 3) {
            $footer_class = 'footer-position col-lg-4 col-md-4 col-sm-4 col-xs-12 mb_40';
        } elseif ( $footer_column == 2) {
            $footer_class = 'footer-position col-lg-6 col-md-6 col-sm-6 col-xs-12 mb_40';
        } elseif ( $footer_column == 1) {
            $footer_class = 'footer-position col-lg-12 col-md-12 col-sm-12 col-xs-12 mb_40';
        }

        if ( isset( $_GET['show-footer'] ) && $_GET['footer'] == 1 ) {
            $show_footer = 1;
        }

        $payment_img = '';
        if ( isset($payment_url['url']) && $payment_url['url'] != '' ) {
            $payment_img = $payment_url['url'];
        }
    ?>

    <footer id="footer-wrapper" class="color-scheme-<?php echo esc_attr( $footer_color ); ?>">
        <?php if( $show_footer && ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) || is_active_sidebar( 'footer-4' ) ) ) : ?>
            <div class="footer-top pt_80 pb_25">
                <div class="footer-container container">
                    <div class="footer-row row">
                        <?php
                        if ( isset($footer_column) ) {
                            for ( $i = 1, $n = $footer_column; $i <= $n; $i++ ) {
                                ?>
                                <div class="<?php echo esc_attr( $footer_class ); ?>">
                					<?php dynamic_sidebar("footer-'. $i '.");?>
                				</div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if(isset( $show_copyright ) && $show_copyright) : ?>
            <div class="footer-bottom footer-container container">
                <div class="footer-row row">
                    <?php if ( isset($copyright) && $copyright != '' ) : ?>
                        <?php if ( isset($copyright_layout) && $copyright_layout = 'three-columns' ): ?>
                            <div class="footer-position col-lg-4 col-md-4 col-sm-12 col-xs-12 copyright">
                        <?php else : ?>
                            <div class="footer-position col-lg-12 col-md-12 col-sm-12 col-xs-12 copyright tc">
                        <?php endif; ?>
                            <?php
                                $allowed_html = array(
                                    'a' => array(
                                        'href' => array(),
                                        'title' => array()
                                    ),
                                    'br' => array(),
                                    'em' => array(),
                                    'strong' => array(),
                                );

                                echo wp_kses($copyright, $allowed_html);
                            ?>
        				</div>
                    <?php endif; ?>
                    <?php if ( isset($payment_img) && $payment_img != '' ) : ?>
                        <?php if ( isset($copyright_layout) && $copyright_layout = 'three-columns' ): ?>
                            <div class="footer-position col-lg-4 col-md-4 col-sm-12 col-xs-12 payment-logo tc">
                        <?php else : ?>
                            <div class="footer-position col-lg-12 col-md-12 col-sm-12 col-xs-12 payment-logo tc">
                        <?php endif; ?>
                            <div class="footer-block">
                                <img src="<?php echo esc_url( $payment_img ); ?>" alt="<?php esc_attr_e('payment', 'adiva'); ?>">
        					</div>
        				</div>
                    <?php endif; ?>
                    <?php if ( has_nav_menu('footer-menu') ) : ?>
                        <?php if ( isset($copyright_layout) && $copyright_layout = 'three-columns' ): ?>
                            <div class="footer-position col-lg-4 col-md-4 col-sm-12 col-xs-12 tr">
                        <?php else : ?>
                            <div class="footer-position col-lg-12 col-md-12 col-sm-12 col-xs-12 tc">
                        <?php endif; ?>
                            <?php
                                $menu = array(
                                    'theme_location'  => 'footer-menu',
                                    'container_class' => 'footer-menu-wrapper',
                                    'menu_class'      => 'footer-menu',
                                    'depth'          => 1,
                                );
                                wp_nav_menu( $menu );
                            ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </footer>

    <?php
		$cart_style = adiva_get_option('wc-add-to-cart-style', 'alert');

		if( isset($_GET['cart_design']) && $_GET['cart_design'] != '' ) {
			$cart_style = $_GET['cart_design'];
		}

		if ( class_exists( 'WooCommerce' ) && isset($cart_style) && $cart_style != '' ) : ?>
		    <div class="cartSidebarWrap">
				<div class="cart_wrap_content">
					<div class="cart-sidebar-header flex between-xs">
						<div class="cart-sidebar-title">
							<?php esc_html_e( 'Shopping cart', 'adiva' ); ?>
						</div>
						<div class="close-cart"><i class="sl icon-close"></i></div>
					</div>
			        <div class="widget_shopping_cart_content"></div>
				</div>
		    </div>
    	<?php endif; ?>

</div><!-- #page -->

<button id="backtop"><i class="sl icon-arrow-up"></i></button>

<?php wp_footer(); ?>
</body>
</html>
