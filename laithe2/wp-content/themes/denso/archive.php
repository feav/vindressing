<?php
get_header();
$sidebar_configs = denso_get_blog_layout_configs();

$columns = denso_get_config('blog_columns', 1);
$bscol = floor( 12 / $columns );
$_count  = 0;
denso_render_breadcrumbs();
?>
<section id="main-container" class="main-content  <?php echo apply_filters('denso_blog_content_class', 'container');?> inner">
	<div class="layout-blog">
		<?php
			$main_content_class = '';
			if ( isset($sidebar_configs['left']) || !isset($sidebar_configs['right']) ) {
				$main_content_class .= 'pull-right';
			}
		?>
		<div class="row <?php echo esc_attr($sidebar_class); ?>">
			
			<div id="main-content" class="col-sm-12 <?php echo esc_attr($sidebar_configs['main']['class']); ?> <?php echo esc_attr($main_content_class); ?>">
				<main id="main" class="site-main" role="main">

				<?php if ( have_posts() ) : ?>

					<header class="page-header hidden">
						<?php
							the_archive_title( '<h1 class="page-title">', '</h1>' );
							the_archive_description( '<div class="taxonomy-description">', '</div>' );
						?>
					</header><!-- .page-header -->

					<?php
					$layout = denso_get_blogs_layout_type();
					get_template_part( 'post-formats/layouts/'.$layout );

					// Previous/next page navigation.
					denso_paging_nav();

				// If no content, include the "No posts found" template.
				else :
					get_template_part( 'post-formats/content', 'none' );

				endif;
				?>

				</main><!-- .site-main -->
			</div><!-- .content-area -->
			
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
	</div>
</section>
<?php get_footer(); ?>