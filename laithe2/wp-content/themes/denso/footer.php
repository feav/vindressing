<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the "site-content" div and all content after.
 *
 * @package WordPress
 * @subpackage Denso
 * @since Denso 1.0
 */

$footer = apply_filters( 'denso_get_footer_layout', 'default' );

?>

	</div><!-- .site-content -->

	<footer id="apus-footer" class="apus-footer" role="contentinfo">
		<?php if ( !empty($footer) ): ?>
			<?php denso_display_footer_builder($footer); ?>
		<?php else: ?>
			
			<!--==============================powered=====================================-->
			
			<div class="apus-copyright">
				<div class="container">
					<div class="copyright-content">
						<div class="text-copyright pull-left">
						<?php
							$allowed_html_array = array('strong' => array(),'a' => array('href' => array()));
							echo wp_kses(__('Copyright &copy; 2017 - Denso. All Rights Reserved. <br/> Powered by <a href="//apusthemes.com">ApusThemes</a>', 'denso'), $allowed_html_array);
						?>

						</div>
						<?php if ( has_nav_menu( 'footer-menu' ) ): ?>
		                <div class="pull-right">
		                    <nav class="apus-topmenu" role="navigation">
		                            <?php
		                                $args = array(
		                                    'theme_location'  => 'footer-menu',
		                                    'menu_class'      => 'menu',
		                                    'fallback_cb'     => '',
		                                    'menu_id'         => 'footer-menu'
		                                );
		                                wp_nav_menu($args);
		                            ?>
		                    </nav>                                                                     
		                </div>
		                <?php endif; ?>
						
					</div>
				</div>
			</div>
		
		<?php endif; ?>
		
	</footer><!-- .site-footer -->
	<?php
	if ( denso_get_config('back_to_top') ) { ?>
		<a href="#" id="back-to-top">
			<i class="fa fa-angle-up"></i>
		</a>
	<?php
	}
	?>

</div><!-- .site -->

<?php wp_footer(); ?>
</body>
</html>