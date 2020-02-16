<?php if ( denso_get_config('show_searchform') ): ?>

	<div class="apus-search-form">
		<form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
			<?php if ( denso_get_config('search_type') != 'all' && denso_get_config('search_category') ): ?>
				<div class="select-category">
					<?php if ( denso_get_config('search_type') == 'product' ):
						$args = array(
						    'show_counts' => false,
						    'hierarchical' => true,
						    'show_uncategorized' => 0
						);
					?>
					    <?php wc_product_dropdown_categories( $args ); ?>

					<?php elseif ( denso_get_config('search_type') == 'post' ):
						$args = array(
							'show_option_all' => esc_html__( 'All categories', 'denso' ),
						    'show_counts' => false,
						    'hierarchical' => true,
						    'show_uncategorized' => 0,
						    'name' => 'category',
							'id' => 'search-category',
							'class' => 'postform dropdown_product_cat',
						);
					?>
						<?php wp_dropdown_categories( $args ); ?>
					<?php endif; ?>
			  	</div>
		  	<?php endif; ?>
		  	<div class="input-group">
		  		<input type="text" placeholder="<?php esc_html_e( 'Search', 'denso' ); ?>" name="s" class="apus-search form-control"/>
				<button class="close-search-form" type="button">
                    <i class="mn-icon-4"></i>
                </button>
		  	</div>
			<?php if ( denso_get_config('search_type') != 'all' ): ?>
				<input type="hidden" name="post_type" value="<?php echo denso_get_config('search_type'); ?>" class="post_type" />
			<?php endif; ?>
		</form>
	</div>
<?php endif; ?>