<?php
/**
 * The Template for displaying all single posts.
 *
 * @package dokan
 * @package dokan - 2014 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$store_user   = dokan()->vendor->get( get_query_var( 'author' ) );
$store_info   = $store_user->get_shop_info();
$map_location = $store_user->get_location();
$smart_sidebar   = adiva_get_option( 'smart-sidebar', 0 );

$dokan_layout = dokan_get_option( 'enable_theme_store_sidebar', 'dokan_general', 'off' );
if ( $dokan_layout == 'off' ) {
    $sidebar_class = '';
	$content_class = 'col-md-12 col-sm-12 col-xs-12';
} else {
    $sidebar_class = 'col-md-3 col-sm-4 col-xs-12';
	$content_class = 'col-md-9 col-sm-8 col-xs-12';
}

if ( isset($smart_sidebar) && $smart_sidebar == 1 ) {
	$sidebar_class .= ' smart-sidebar';
}


get_header( 'shop' );
do_action( ' woocommerce_before_main_content' );
?>
    <div class="container pt_60 pb_60">
        <div class="row left-sidebar">
            <div id="main-content" class="with-sidebar <?php echo esc_attr($content_class); ?>">
                <div id="dokan-primary" class="dokan-single-store">
                    <div id="dokan-content" class="store-page-wrap woocommerce" role="main">

                        <?php dokan_get_template_part( 'store-header' ); ?>

                        <?php do_action( 'dokan_store_profile_frame_after', $store_user->data, $store_info ); ?>

                        <?php if ( have_posts() ) { ?>

                            <div class="seller-items">

                                <?php woocommerce_product_loop_start(); ?>

                                    <?php while ( have_posts() ) : the_post(); ?>

                                        <?php wc_get_template_part( 'content', 'product' ); ?>

                                    <?php endwhile; // end of the loop. ?>

                                <?php woocommerce_product_loop_end(); ?>

                            </div>

                            <?php dokan_content_nav( 'nav-below' ); ?>

                        <?php } else { ?>

                            <p class="dokan-info"><?php esc_html_e( 'No products were found of this vendor!', 'adiva' ); ?></p>

                        <?php } ?>
                    </div>

                </div><!-- .dokan-single-store -->
            </div>
            <?php if ( $dokan_layout != 'off' ) : ?>
                <div id="main-sidebar" class="dokan-store-sidebar <?php echo esc_attr($sidebar_class); ?>">
                    <?php
                    if ( ! dynamic_sidebar( 'sidebar-store' ) ) {
                        $args = array(
                            'before_widget' => '<aside class="widget">',
                            'after_widget'  => '</aside>',
                            'before_title'  => '<h3 class="widgettitle">',
                            'after_title'   => '</h3>',
                        );

                        if ( class_exists( 'Dokan_Store_Location' ) ) {
                            the_widget( 'Dokan_Store_Category_Menu', array( 'title' => __( 'Store Category', 'adiva' ) ), $args );

                            if ( dokan_get_option( 'store_map', 'dokan_general', 'on' ) == 'on'  && !empty( $map_location ) ) {
                                the_widget( 'Dokan_Store_Location', array( 'title' => __( 'Store Location', 'adiva' ) ), $args );
                            }

                            if ( dokan_get_option( 'contact_seller', 'dokan_general', 'on' ) == 'on' ) {
                                the_widget( 'Dokan_Store_Contact_Form', array( 'title' => __( 'Contact Vendor', 'adiva' ) ), $args );
                            }
                        }

                    }
                    ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
<?php do_action( 'woocommerce_after_main_content' ); ?>
<?php get_footer( 'shop' ); ?>
