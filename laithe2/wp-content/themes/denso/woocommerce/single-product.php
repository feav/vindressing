<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
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
 * @version     1.6.4
 */
get_header();
?>

<?php do_action( 'denso_woo_template_main_before' ); ?>
<div class="wrapper-shop">
	<section id="main-container" class="container-fluid no-padding">
		
		<div id="main-content" class="archive-shop">
			<div id="primary" class="content-area">
				<div id="content" class="site-content" role="main">
					
					<?php while ( have_posts() ) : the_post();
		                wc_get_template_part( 'content', 'single-product' );
		            endwhile; ?>

				</div><!-- #content -->
			</div><!-- #primary -->
		</div><!-- #main-content -->
	</section>
</div>
<?php
get_footer();