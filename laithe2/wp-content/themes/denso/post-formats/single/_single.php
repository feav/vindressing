<?php
$post_format = get_post_format();
global $post;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <?php if ( $post_format == 'gallery' ) {
        $gallery = denso_post_gallery( get_the_content(), array( 'size' => 'full' ) );
    ?>
        <div class="text-center entry-thumb <?php echo  (empty($gallery) ? 'no-thumb' : ''); ?>">
            <?php echo trim($gallery); ?>
        </div>
    <?php } elseif( $post_format == 'link' ) {
            $denso_format = denso_post_format_link_helper( get_the_content(), get_the_title() );
            $denso_title = $denso_format['title'];
            $denso_link = denso_get_link_attributes( $denso_title );
            $thumb = denso_post_thumbnail('', $denso_link);
            echo trim($thumb);
        } else { ?>
        <div class="ext-center entry-thumb <?php echo  (!has_post_thumbnail() ? 'no-thumb' : ''); ?>">
            <?php
                $thumb = denso_post_thumbnail();
                echo trim($thumb);
            ?>
        </div>
    <?php } ?>
	<div class="detail-content">
        <div class="entry-head text-center">
            <div class="info-left">
                <?php if (get_the_title()) { ?>
                    <h4 class="entry-title">
                        <?php the_title(); ?>
                    </h4>
                <?php } ?>
            </div>
            <div class="meta">
                <span class="author">
                    <?php echo get_avatar( get_the_author_meta( 'user_email' ),32 ); ?>
                    <?php
                        printf( '<span class="post-author">%1$s<a href="%2$s">%3$s</a></span>',
                            _x( '', 'Used before post author name.', 'denso' ),
                            esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
                            get_the_author()
                        );
                    ?> 
                    - 
                    <span class="entry-date"><?php the_time( 'M d ,Y' ); ?></span>
                </span>
                <span>
                    <i class="mn-icon-286"></i>
                    <?php comments_number( '0 Comment', '1 Comment', '% Comments' ); ?>.
                </span>
                <span>
                    <i class="mn-icon-352"></i>
                    <?php esc_html_e('in ', 'denso'); denso_post_categories($post); ?>
                </span>
            </div>
        </div>

    	<div class="single-info info-bottom">
    		<?php
                if ( $post_format == 'gallery' ) {
                    $gallery_filter = denso_gallery_from_content( get_the_content() );
                    echo trim($gallery_filter['filtered_content']);
                } else {
            ?>
                    <div class="entry-description"><?php the_content(); ?></div><!-- /entry-content -->
            <?php } ?>
    		<?php
    		wp_link_pages( array(
    			'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'denso' ) . '</span>',
    			'after'       => '</div>',
    			'link_before' => '<span>',
    			'link_after'  => '</span>',
    			'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'denso' ) . ' </span>%',
    			'separator'   => '',
    		) );
    		?>
    		<div class="tag-social clearfix ">
                <div class="pull-left">
                    <?php denso_post_tags(); ?>
                </div>
    			
    			<div class="pull-right social-share">
                    
                   <?php if( denso_get_config('show_blog_social_share', true) ) {
                        get_template_part( 'page-templates/parts/sharebox' );
                    } ?>         
                </div>
    		</div>
    	</div>
    </div>
</article>