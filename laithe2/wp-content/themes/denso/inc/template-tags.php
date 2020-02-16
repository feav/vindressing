<?php
/**
 * Custom template tags for Denso
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package WordPress
 * @subpackage Denso
 * @since Denso 1.0
 */

if ( ! function_exists( 'denso_comment_nav' ) ) :
/**
 * Display navigation to next/previous comments when applicable.
 *
 * @since Denso 1.0
 */
function denso_comment_nav() {
	// Are there comments to navigate through?
	if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
	?>
	<nav class="navigation comment-navigation" role="navigation">
		<h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'denso' ); ?></h2>
		<div class="nav-links clearfix">
			<?php
				if ( $prev_link = get_previous_comments_link( esc_html__( 'Older Comments', 'denso' ) ) ) :
					printf( '<div class="nav-previous"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> %s</div>', $prev_link );
				endif;

				if ( $next_link = get_next_comments_link( esc_html__( 'Newer Comments', 'denso' ) ) ) :
					printf( '<div class="nav-next">%s <i class="fa fa-long-arrow-right" aria-hidden="true"></i></div>', $next_link );
				endif;
			?>
		</div><!-- .nav-links -->
	</nav><!-- .comment-navigation -->
	<?php
	endif;
}
endif;

if ( ! function_exists( 'denso_entry_meta' ) ) :
/**
 * Prints HTML with meta information for the categories, tags.
 *
 * @since Denso 1.0
 */
function denso_entry_meta() {
	if ( is_sticky() && is_home() && ! is_paged() ) {
		printf( '<span class="sticky-post">%s</span>', esc_html__( 'Featured', 'denso' ) );
	}

	$format = get_post_format();
	if ( current_theme_supports( 'post-formats', $format ) ) {
		printf( '<span class="entry-format">%1$s<a href="%2$s">%3$s</a></span>',
			sprintf( '<span class="screen-reader-text">%s </span>', _x( 'Format', 'Used before post format.', 'denso' ) ),
			esc_url( get_post_format_link( $format ) ),
			get_post_format_string( $format )
		);
	}

	if ( in_array( get_post_type(), array( 'post', 'attachment' ) ) ) {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			get_the_date(),
			esc_attr( get_the_modified_date( 'c' ) ),
			get_the_modified_date()
		);

		printf( '<span class="posted-on"><span class="screen-reader-text">%1$s </span><a href="%2$s" rel="bookmark">%3$s</a></span>',
			_x( 'Posted on', 'Used before publish date.', 'denso' ),
			esc_url( get_permalink() ),
			$time_string
		);
	}

	if ( 'post' == get_post_type() ) {
		if ( is_singular() || is_multi_author() ) {
			printf( '<span class="byline"><span class="author vcard"><span class="screen-reader-text">%1$s </span><a class="url fn n" href="%2$s">%3$s</a></span></span>',
				_x( 'Author', 'Used before post author name.', 'denso' ),
				esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
				get_the_author()
			);
		}

		$categories_list = get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'denso' ) );
		if ( $categories_list && denso_categorized_blog() ) {
			printf( '<span class="cat-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
				_x( 'Categories', 'Used before category names.', 'denso' ),
				$categories_list
			);
		}

		$tags_list = get_the_tag_list( '', _x( ', ', 'Used between list items, there is a space after the comma.', 'denso' ) );
		if ( $tags_list ) {
			printf( '<span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
				_x( 'Tags', 'Used before tag names.', 'denso' ),
				$tags_list
			);
		}
	}

	if ( is_attachment() && wp_attachment_is_image() ) {
		// Retrieve attachment metadata.
		$metadata = wp_get_attachment_metadata();

		printf( '<span class="full-size-link"><span class="screen-reader-text">%1$s </span><a href="%2$s">%3$s &times; %4$s</a></span>',
			_x( 'Full size', 'Used before full size attachment link.', 'denso' ),
			esc_url( wp_get_attachment_url() ),
			$metadata['width'],
			$metadata['height']
		);
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		/* translators: %s: post title */
		comments_popup_link( sprintf( esc_html__( 'Leave a comment<span class="screen-reader-text"> on %s</span>', 'denso' ), get_the_title() ) );
		echo '</span>';
	}
}
endif;

/**
 * Determine whether blog/site has more than one category.
 *
 * @since Denso 1.0
 *
 * @return bool True of there is more than one category, false otherwise.
 */
function denso_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'denso_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'denso_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so denso_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so denso_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in {@see denso_categorized_blog()}.
 *
 * @since Denso 1.0
 */
function denso_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'denso_categories' );
}
add_action( 'edit_category', 'denso_category_transient_flusher' );
add_action( 'save_post',     'denso_category_transient_flusher' );

if ( ! function_exists( 'denso_post_thumbnail' ) ) {
	function denso_post_thumbnail($thumbsize = '', $link = '') {
		
		$size_array = array('full', 'medium', 'large', 'thumbnail');
		$output = '';
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return $output;
		}
		$link = empty( $link ) ? get_permalink() : $link;
		if ( is_singular('post') ) {
			$output .= '<div class="post-thumbnail">';
				$output .= get_the_post_thumbnail( get_the_ID(), 'full');
			$output .= '</div>';
		} else {
			$output .= '<figure class="entry-thumb effect-v6">';
			$output .= '<a class="post-thumbnail" href="'.esc_url($link).'" aria-hidden="true">';
			
			if ( !empty($thumbsize) ) {
				if (class_exists('kc_tools') && !in_array( $thumbsize, $size_array ) ) {
					$image_id = get_post_thumbnail_id();
					$image_full_width = wp_get_attachment_image_src( $image_id, 'full' );
					$image_full = $image_full_width[0];
					$image_url 	= kc_tools::createImageSize( $image_full, $thumbsize );
					$output .= '<img src="'.esc_url($image_url).'" alt="">';
				} else {
					$output .= get_the_post_thumbnail( get_the_ID(), $thumbsize, array( 'alt' => get_the_title() ) );
				}
			} else {
				$output .= get_the_post_thumbnail( get_the_ID(), 'post-thumbnail', array( 'alt' => get_the_title() ) );
			}
			$output .= '</a>';
			$output .= '</figure>';
		}
		return $output;
	}
}

if ( ! function_exists( 'denso_post_categories' ) ) {
	function denso_post_categories( $post ) {
		$cat = wp_get_post_categories( $post->ID );
		$k   = count( $cat );
		foreach ( $cat as $c ) {
			$categories = get_category( $c );
			$k -= 1;
			if ( $k == 0 ) {
				echo '<a href="' . get_category_link( $categories->term_id ) . '" class="categories-name">' . $categories->name . '</a>';
			} else {
				echo '<a href="' . get_category_link( $categories->term_id ) . '" class="categories-name">' . $categories->name . ', </a>';
			}
		}
	}
}

if ( ! function_exists( 'denso_short_top_meta' ) ) {
	function denso_short_top_meta( $post ) {
		
		?>
		<span class="entry-date"><?php the_time( 'M d, Y' ); ?></span>
        <span class="author"><?php esc_html_e('/ By: ', 'denso'); the_author_posts_link(); ?></span>
		<?php
	}
}

if ( ! function_exists( 'denso_get_link_url' ) ) :
/**
 * Return the post URL.
 *
 * Falls back to the post permalink if no URL is found in the post.
 *
 * @since Denso 1.0
 *
 * @see get_url_in_content()
 *
 * @return string The Link format URL.
 */
function denso_get_link_url() {
	$has_url = get_url_in_content( get_the_content() );

	return $has_url ? $has_url : apply_filters( 'the_permalink', get_permalink() );
}
endif;

if ( ! function_exists( 'denso_excerpt_more' ) && ! is_admin() ) :
/**
 * Replaces "[...]" (appended to automatically generated excerpts) with ... and a 'Continue reading' link.
 *
 * @since Denso 1.0
 *
 * @return string 'Continue reading' link prepended with an ellipsis.
 */
function denso_excerpt_more( $more ) {
	$link = sprintf( '<br /><a href="%1$s" class="more-link">%2$s</a>',
		esc_url( get_permalink( get_the_ID() ) ),
		/* translators: %s: Name of current post */
		sprintf( esc_html__( 'Continue reading %s', 'denso' ), '<span class="screen-reader-text">' . get_the_title( get_the_ID() ) . '</span>' )
		);
	return ' &hellip; ' . $link;
}
add_filter( 'excerpt_more', 'denso_excerpt_more' );
endif;

if ( ! function_exists( 'denso_display_post_thumb' ) ) {
	function denso_display_post_thumb($thumbsize) {
		$post_format = get_post_format();
		$output = '';
		if ($post_format == 'gallery') {
	        $output = denso_post_gallery( get_the_content() );
	    } elseif ($post_format == 'audio' || $post_format == 'video') {
	        $media = denso_post_media( get_the_content() );
	        if ($media) {
	            $output = $media;
	        } elseif ( has_post_thumbnail() ) {
	            $output = denso_post_thumbnail($thumbsize);
	        }
	    } else {
	        if ( has_post_thumbnail() ) {
	            if ($post_format == 'link') {
	                $denso_format = denso_post_format_link_helper( get_the_content(), get_the_title() );
	                $denso_title = $denso_format['title'];
	                $denso_link = denso_get_link_attributes( $denso_title );

	                $output = denso_post_thumbnail($thumbsize, $denso_link);
	            } else {
	                $output = denso_post_thumbnail($thumbsize);
	            }
	        }
	    }
	    return $output;
	}
}