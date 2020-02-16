<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @since   1.0.0
 * @package Adiva
 */

get_header();
?>

<div class="page-content">
    <div class="error-404 container tc">
		<h1><?php esc_html_e( '404', 'adiva' ); ?></h1>
        <div class="sub-title"><?php esc_html_e( 'Sorry, but the page you are looking for does not exist', 'adiva' ); ?></div>
		<div class="return-home tc">
			<a href="<?php echo esc_url( home_url('/') ); ?>" class="button btn-color-black"><?php esc_html_e('Back to homepage', 'adiva'); ?></a>
		</div>
    </div>
</div><!-- page-content -->

<?php get_footer();
