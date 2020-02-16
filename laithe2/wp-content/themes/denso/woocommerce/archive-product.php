<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$is_vendor_shop = false;
if (class_exists('WCV_Vendors')) {
    $is_vendor_shop = urldecode( get_query_var( 'vendor_shop' ) );
}


get_header();
$sidebar_configs = denso_get_woocommerce_layout_configs();

if ( is_product_category() && denso_get_config('show_category_image_title') && !$is_vendor_shop ) {
	$term = get_queried_object();
	$thumbnail_id  			= get_woocommerce_term_meta( $term->term_id, 'thumbnail_id', true  );
	if ( $thumbnail_id ) {
		$image = wp_get_attachment_image_src( $thumbnail_id, 'full'  );
		$image = $image[0];
	}
	$style = '';
	if ( isset($image) && $image ) {
		$image = str_replace( ' ', '%20', $image );
		$style = 'style="background-image:url('.esc_url($image).')"';
	}
	?>
	<div class="product-category-header" <?php echo trim($style); ?>>
	    <h1 class="page-title"><?php woocommerce_page_title(); ?></h1>
	</div>
	<?php
}

if (!$is_vendor_shop) :
?>

	<?php do_action( 'denso_woo_template_main_before' ); ?>
	<div class="wrapper-shop <?php echo (denso_get_config('product_control_bar_position') == 'top')?'fill-top':''; ?>">
		<section id="main-container" class="<?php echo apply_filters('denso_woocommerce_content_class', 'container');?>">
			<?php
				if ( !is_singular('product') && denso_get_config('product_control_bar_position') == 'top' ) {
					do_action( 'denso_before_products');
				}
				$main_content_class = '';
				if ( isset($sidebar_configs['left']) || !isset($sidebar_configs['right']) ) {
					$main_content_class .= 'pull-right';
				}

			?>
			<div class="row">
				
				<div id="main-content" class="archive-shop <?php echo esc_attr($sidebar_configs['main']['class']); ?> <?php echo esc_attr($main_content_class); ?>">
					<div id="primary" class="content-area">
						<div id="content" class="site-content" role="main">
							
							<?php wc_get_template_part( 'content', 'archive-product' ); ?>

						</div><!-- #content -->
					</div><!-- #primary -->
				</div><!-- #main-content -->
				
				<?php if ( isset($sidebar_configs['left']) ) : ?>
					<div class="<?php echo esc_attr($sidebar_configs['left']['class']) ;?>">
					  	<aside class="sidebar sidebar-left" itemscope="itemscope" itemtype="http://schema.org/WPSideBar">
					   		<?php if ( is_active_sidebar( $sidebar_configs['left']['sidebar'] ) ): ?>
						   		<?php dynamic_sidebar( $sidebar_configs['left']['sidebar'] ); ?>
						   	<?php endif; ?>
					  	</aside>
					</div>
				<?php endif; ?>

				<?php if ( isset($sidebar_configs['right']) ) : ?>
					<div class="<?php echo esc_attr($sidebar_configs['right']['class']) ;?>">
					  	<aside class="sidebar sidebar-right" itemscope="itemscope" itemtype="http://schema.org/WPSideBar">
					   		<?php if ( is_active_sidebar( $sidebar_configs['right']['sidebar'] ) ): ?>
						   		<?php dynamic_sidebar( $sidebar_configs['right']['sidebar'] ); ?>
						   	<?php endif; ?>
					  	</aside>
					</div>
				<?php endif; ?>
			</div>
		</section>
	</div>
<?php
else:
?>
	<div class="wrapper-shop">
		<section id="main-container">
			<div class="row">
				<div id="main-content" class="archive-shop col-xs-12 ">

					<div id="primary" class="content-area">
						<div id="content" class="site-content" role="main">
							<?php
							/**
							 * woocommerce_before_main_content hook
							 *
							 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
							 * @hooked woocommerce_breadcrumb - 20
							 */
							do_action( 'woocommerce_before_main_content' ); ?>
							
							<?php wc_get_template_part( 'content', 'vendor-product' ); ?>

						</div><!-- #content -->
					</div><!-- #primary -->
				</div><!-- #main-content -->
			</div>
		</section>
	</div>
<?php
endif;
get_footer();