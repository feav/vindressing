<?php

if ( ! function_exists('adiva_favicon') ) {
    function adiva_favicon() {
    	if ( ! ( function_exists( 'has_site_icon' ) && has_site_icon() ) ) {
    		$favicon_url = adiva_get_option('favicon', '');

    		if( isset($favicon_url['url']) && $favicon_url['url'] != '') : ?>
    			<link rel="shortcut icon" href="<?php echo esc_url( $favicon_url['url'] ); ?>"/>
    		<?php endif;
        }
    }
    add_action('wp_head', 'adiva_favicon');
}

/*---------------------------------
    Custom Login Logo
------------------------------------*/
if ( ! function_exists( 'adiva_login_logo' ) ) {
    function adiva_login_logo() {
        $login_logo = adiva_get_option('login-logo');

		if( !empty( $login_logo['url'] ) ) {
            $login_logo_url = $login_logo['url'];
        } else {
            $login_logo_url = ADIVA_URL . '/assets/images/logo.png';
        }

        echo '<style type="text/css"> h1 a { background: url(' . esc_url($login_logo_url) . ') center no-repeat !important; width:302px !important; height:67px !important; } </style>';
    }
}
add_action('login_head', 'adiva_login_logo');

if ( ! function_exists( 'adiva_page_title' ) ) {
    function adiva_page_title() {
        global $wp_query, $post;

        // Remove page title for dokan store list page
        if( function_exists( 'dokan_is_store_page' )  && dokan_is_store_page() ) {
        	return '';
        }

        $page_title     = true;
        $subtitle       = '';
        $heading_class  = '';
        $page_for_posts = get_option( 'page_for_posts' );
        $breadcrumbs    = adiva_get_option( 'breadcrumbs', 1 );

        // Get default styles from Options Panel
        $title_design = adiva_get_option( 'page-title-design', 'centered' );
        $title_size   = adiva_get_option( 'page-title-size', 'default' );
		$title_color  = adiva_get_option( 'page-title-color', 'dark' );

        $options = get_post_meta( get_the_ID(), '_custom_page_options', true );

        if ( isset( $options['breadcrumb'] ) ) {
        	$breadcrumbs = $options['breadcrumb'];
        }

        // Text color
        if ( isset( $options['pagehead-text-color'] ) && $options['pagehead-text-color'] != '' ) {
        	$title_color = $options['pagehead-text-color'];
        }

        $heading_class .= ' color-scheme-' . $title_color;
		$heading_class .= ' title-align-' . $title_design;
        $heading_class .= ' title-size-' . $title_size;

        if( isset( $options['pagehead'] ) && $options['pagehead'] != 1 ) $page_title = false;

        if( $title_design == 'disable' ) $page_title = false;
        if( ! $page_title && ! $breadcrumbs ) return;


        // Heading for pages
		if( is_singular( 'page' ) && ( ! $page_for_posts || ! is_page( $page_for_posts ) ) ):
			$title = get_the_title();

            if ( isset( $options['page-title'] ) && $options['page-title'] != '' ) {
                $title = $options['page-title'];
            }
            if ( isset( $options['page-subtitle'] ) && $options['page-subtitle'] != '' ) {
                $subtitle = $options['page-subtitle'];
            }

			?>
				<div class="page-heading<?php echo esc_attr($heading_class); ?>">
					<div class="container">
						<header class="entry-header">
							<?php if( $page_title ): ?><h1 class="entry-title"><?php echo esc_html( $title ); ?></h1><?php endif; ?>
                            <?php if( isset($subtitle) && $subtitle != '' ): ?><p><?php echo esc_html( $subtitle ); ?></p><?php endif; ?>
							<?php if( $breadcrumbs ) echo adiva_breadcrumb(); ?>
						</header><!-- .entry-header -->
					</div>
				</div>
			<?php
			return;
		endif;


        // Heading for blog and archives
        if( is_singular( 'post' ) || is_home() || is_search() || is_tag() || is_category() || is_date() || is_author() ):
            $title = ( ! empty( $page_for_posts ) ) ? get_the_title( $page_for_posts ) : esc_html__( 'Blog', 'adiva' );

            if( is_tag() ) {
                $title = esc_html__( 'Tag Archives: ', 'adiva')  . single_tag_title( '', false ) ;
            }

            if( is_category() ) {
                $title = '<span>' . single_cat_title( '', false ) . '</span>';
            }

            if( is_date() ) {
                if ( is_day() ) :
                    $title = esc_html__( 'Daily Archives: ', 'adiva') . get_the_date();
                elseif ( is_month() ) :
                    $title = esc_html__( 'Monthly Archives: ', 'adiva') . get_the_date( _x( 'F Y', 'monthly archives date format', 'adiva' ) );
                elseif ( is_year() ) :
                    $title = esc_html__( 'Yearly Archives: ', 'adiva') . get_the_date( _x( 'Y', 'yearly archives date format', 'adiva' ) );
                else :
                    $title = esc_html__( 'Archives', 'adiva' );
                endif;
            }

            if ( is_author() ) {
                the_post();
                $title = esc_html__( 'Posts by ', 'adiva' ) . '<span class="vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>';
                /*
                 * Since we called the_post() above, we need to
                 * rewind the loop back to the beginning that way
                 * we can run the loop properly, in full.
                 */
                rewind_posts();
            }

            if ( is_single() ) {
                $title = '';
            }

            if( is_search() ) {
                $title = esc_html__( 'Search Results for: ', 'adiva' ) . get_search_query();
            }

            ?>
                <div class="page-heading<?php echo esc_attr($heading_class); ?> title-blog">
                    <div class="container">
                        <header class="entry-header">
                            <?php if( $page_title ): ?><h1 class="entry-title"><?php echo '' . $title; ?></h1><?php endif; ?>
                            <?php if( $breadcrumbs && !is_search() ) echo adiva_breadcrumb(); ?>
                        </header><!-- .entry-header -->
                    </div>
                </div>
            <?php
            return;
        endif;


        // Heading for portfolio
		if( is_singular( 'portfolio' ) || is_post_type_archive( 'portfolio' ) || is_tax( 'portfolio-cat' ) ):
			$title = adiva_get_option('portfolio-title', 'Portfolio');

			if( is_tax( 'portfolio-cat' ) ) {
				$title = single_term_title( '', false );
			}

            if( is_singular( 'portfolio' ) ) {
				$title = get_the_title();
			}

            ?>
				<div class="page-heading<?php echo esc_attr( $heading_class ); ?> title-portfolio">
					<div class="container">
						<header class="entry-header">
							<?php if( $page_title ): ?>
                                <h1 class="entry-title"><?php echo esc_html( $title ); ?></h1>
                            <?php endif; ?>
							<?php if( $breadcrumbs ) echo adiva_breadcrumb(); ?>
						</header><!-- .entry-header -->
					</div>
				</div>
			<?php
			return;
		endif;


        // Page heading for shop page
		if( adiva_woocommerce_activated() && ( is_shop() || is_product_category() || is_product_tag() || is_singular( 'product' ) ) ) :
			if( is_product_category() ) {
                $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
                if ( $term ) {
                	$term_options = get_term_meta( $term->term_id, '_custom_product_cat_options', true );
                }
			}

            if ( is_product() || is_single() ) {
                $terms = get_the_terms( $post->ID, 'product_cat' );

                foreach ($terms as $term) {
                    $title = esc_attr( $term->name );
                }
            }
			?>
    			<?php if ( apply_filters( 'woocommerce_show_page_title', true ) && ! is_singular( 'product' ) ) : ?>
    				<div class="page-heading<?php echo esc_attr( $heading_class ); ?> title-shop">
    					<div class="container">
                            <header class="entry-header">
                                <h1 class="entry-title"><?php woocommerce_page_title(); ?></h1>
    							<?php if( $breadcrumbs ) echo adiva_breadcrumb(); ?>
    						</header><!-- .entry-header -->
    					</div>
    				</div>
    			<?php endif; ?>

                <?php if ( is_singular( 'product' ) ) : ?>
                    <div class="page-heading<?php echo esc_attr( $heading_class ); ?>">
    					<div class="container">
                            <header class="entry-header">
                                <h1 class="entry-title"><?php echo esc_html( $title ); ?></h1>
    							<?php if( $breadcrumbs ) echo woocommerce_breadcrumb(); ?>
    						</header><!-- .entry-header -->
    					</div>
    				</div>
                <?php endif; ?>

			<?php

			return;
		endif;

    }
}

// **********************************************************************//
// Pre Loader
// **********************************************************************//
if ( !function_exists('adiva_preloader') ) {
	function adiva_preloader() {
		$loader          = adiva_get_option('site-loader', 0);
		$preloader_style = adiva_get_option('site-loader-style', 5);

		?>
		<?php if ( isset($loader) && $loader == 1 ) : ?>
			<div class="preloader">
				<div class="spinner<?php echo esc_attr( $preloader_style ); ?>">
					<div class="dot11"></div>
					<div class="dot22"></div>
				    <div class="bounce11"></div>
				    <div class="bounce22"></div>
				    <div class="bounce33"></div>
				</div>
			</div>
		<?php endif; ?>
		<?php
	}
}

if( ! function_exists( 'adiva_get_static_blocks_array' ) ) {
	function adiva_get_static_blocks_array() {
		$args = array( 'posts_per_page' => 50, 'post_type' => 'cms_block' );
		$blocks_posts = get_posts( $args );
		$array = array();
		foreach ( $blocks_posts as $post ) :
			setup_postdata( $post );
			$array[$post->post_title] = $post->ID;
		endforeach;
		wp_reset_postdata();
		return $array;
	}
}

/**
 * Adiva promobar
 */
 if ( ! function_exists( 'adiva_promo_bar' ) ) {
    function adiva_promo_bar() {
 	   $promo_bar            = adiva_get_option('promo-bar', 0);
       $promo_bar_text       = adiva_get_option('promo-bar-text', '');

       if ( ! $promo_bar ) return;
 	   ?>

       <div class="adiva-promo-bar">
           <div class="container">
                <?php
                    if ( isset($promo_bar_text) && $promo_bar_text != '' ) {
                        $allowed_html = array(
                            'a' => array(
                                'href' => array(),
                                'title' => array()
                            ),
                            'br' => array(),
                            'em' => array(),
                            'strong' => array(),
                        );

                        echo wp_kses($promo_bar_text, $allowed_html);
                    }

                ?>
           </div>
       </div>
 	   <?php
    }
 }


/**
 * Language Dropdown
 */

if ( ! function_exists( 'adiva_language' )  ) {
	function adiva_language() {

        $language_name_1 = adiva_get_option( 'language-name-1' );
        $language_name_2 = adiva_get_option( 'language-name-2' );
        $language_name_3 = adiva_get_option( 'language-name-3' );
        $language_link_1 = adiva_get_option( 'language-link-1' );
        $language_link_2 = adiva_get_option( 'language-link-2' );
        $language_link_3 = adiva_get_option( 'language-link-3' );

		$output = '';
        if ( $language_link_1 ||  $language_link_2 || $language_link_3 ) {

    		$output .= '<div class="btn-group compact-hidden box-hover">';
                if ( $language_link_1 ) {
                    $output .= '<a href="javascript:void(0)" class="dropdown-toggle">'. esc_html( $language_name_1 ) .'<i class="fa fa-angle-down"></i></a>';
                }

                $output .= '<div class="dropdown-menu">';
    				$output .= '<ul>';
                        if ( $language_link_1 ) {
                            $output .= '<li><a href="' . esc_url( $language_link_1 ) . '">'. esc_html( $language_name_1 ) .'</a></li>';
                        }
                        if ( $language_link_2 ) {
                            $output .= '<li><a href="' . esc_url( $language_link_2 ) . '">'. esc_html( $language_name_2 ) .'</a></li>';
                        }
                        if ( $language_link_3 ) {
                            $output .= '<li><a href="' . esc_url( $language_link_3 ) . '">'. esc_html( $language_name_3 ) .'</a></li>';
                        }
    				$output .= '</ul>';
    			$output .= '</div>';

    		$output .= '</div>';

        }

		return apply_filters( 'adiva_language', $output );
	}
}

/* 	Render Footer Social Icons
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'adiva_social_icons' ) ) {
	function adiva_social_icons() {
		$facebook    = adiva_get_option( 'facebook' );
        $googleplus  = adiva_get_option( 'twitter' );
        $twitter     = adiva_get_option( 'google-plus' );
        $pinterest   = adiva_get_option( 'pinterest' );
        $instagram   = adiva_get_option( 'instagram' );
        $vimeo       = adiva_get_option( 'vimeo' );
        $youtube     = adiva_get_option( 'youtube' );
        $dribbble    = adiva_get_option( 'dribbble' );
        $tumblr      = adiva_get_option( 'tumblr' );
        $linkedin    = adiva_get_option( 'linkedin' );
        $flickr      = adiva_get_option( 'flickr' );
        $github      = adiva_get_option( 'github' );
        $lastfm      = adiva_get_option( 'lastfm' );
        $paypal      = adiva_get_option( 'paypal' );
        $wordpress   = adiva_get_option( 'wordpress' );
        $skype       = adiva_get_option( 'skype' );
        $yahoo       = adiva_get_option( 'yahoo' );
        $reddit      = adiva_get_option( 'reddit' );
        $deviantart  = adiva_get_option( 'deviantart' );
        $steam       = adiva_get_option( 'steam' );
        $foursquare  = adiva_get_option( 'foursquare' );
        $behance     = adiva_get_option( 'behance' );
        $blogger     = adiva_get_option( 'blogger' );
        $xing        = adiva_get_option( 'xing' );
        $stumbleupon = adiva_get_option( 'stumbleupon' );
        ?>
        <ul class="social-list-icons">
            <?php if ( ! empty($facebook) ) : ?>
                <li><a href="<?php echo esc_url($facebook); ?>"><i class="fa fa-facebook"></i></a></li>
            <?php endif; ?>

            <?php if ( ! empty($googleplus) ) : ?>
                <li><a href="<?php echo esc_url($googleplus); ?>"><i class="fa fa-google-plus"></i></a></li>
            <?php endif; ?>

            <?php if ( ! empty($twitter) ) : ?>
                <li><a href="<?php echo esc_url($twitter); ?>"><i class="fa fa-twitter"></i></a></li>
            <?php endif; ?>

            <?php if ( ! empty($pinterest) ) : ?>
                <li><a href="<?php echo esc_url($pinterest); ?>"><i class="fa fa-pinterest"></i></a></li>
            <?php endif; ?>

            <?php if ( ! empty($instagram) ) : ?>
                <li><a href="<?php echo esc_url($instagram); ?>"><i class="fa fa-instagram"></i></a></li>
            <?php endif; ?>

            <?php if ( ! empty($vimeo) ) : ?>
                <li><a href="<?php echo esc_url($vimeo); ?>"><i class="fa fa-vimeo"></i></a></li>
            <?php endif; ?>

            <?php if ( ! empty($youtube) ) : ?>
                <li><a href="<?php echo esc_url($youtube); ?>"><i class="fa fa-youtube"></i></a></li>
            <?php endif; ?>

            <?php if ( ! empty($dribbble) ) : ?>
                <li><a href="<?php echo esc_url($dribbble); ?>"><i class="fa fa-dribbble"></i></a></li>
            <?php endif; ?>

            <?php if ( ! empty($tumblr) ) : ?>
                <li><a href="<?php echo esc_url($tumblr); ?>"><i class="fa fa-tumblr"></i></a></li>
            <?php endif; ?>

            <?php if ( ! empty($linkedin) ) : ?>
                <li><a href="<?php echo esc_url($linkedin); ?>"><i class="fa fa-linkedin"></i></a></li>
            <?php endif; ?>

            <?php if ( ! empty($flickr) ) : ?>
                <li><a href="<?php echo esc_url($flickr); ?>"><i class="fa fa-flickr"></i></a></li>
            <?php endif; ?>

            <?php if ( ! empty($github) ) : ?>
                <li><a href="<?php echo esc_url($github); ?>"><i class="fa fa-github"></i></a></li>
            <?php endif; ?>

            <?php if ( ! empty($lastfm) ) : ?>
                <li><a href="<?php echo esc_url($lastfm); ?>"><i class="fa fa-lastfm"></i></a></li>
            <?php endif; ?>

            <?php if ( ! empty($paypal) ) : ?>
                <li><a href="<?php echo esc_url($paypal); ?>"><i class="fa fa-paypal"></i></a></li>
            <?php endif; ?>

            <?php if ( ! empty($wordpress) ) : ?>
                <li><a href="<?php echo esc_url($wordpress); ?>"><i class="fa fa-wordpress"></i></a></li>
            <?php endif; ?>

            <?php if ( ! empty($skype) ) : ?>
                <li><a href="<?php echo esc_url($skype); ?>"><i class="fa fa-skype"></i></a></li>
            <?php endif; ?>

            <?php if ( ! empty($yahoo) ) : ?>
                <li><a href="<?php echo esc_url($yahoo); ?>"><i class="fa fa-yahoo"></i></a></li>
            <?php endif; ?>

            <?php if ( ! empty($reddit) ) : ?>
                <li><a href="<?php echo esc_url($reddit); ?>"><i class="fa fa-reddit"></i></a></li>
            <?php endif; ?>

            <?php if ( ! empty($deviantart) ) : ?>
                <li><a href="<?php echo esc_url($deviantart); ?>"><i class="fa fa-deviantart"></i></a></li>
            <?php endif; ?>

            <?php if ( ! empty($steam) ) : ?>
                <li><a href="<?php echo esc_url($steam); ?>"><i class="fa fa-steam"></i></a></li>
            <?php endif; ?>

            <?php if ( ! empty($foursquare) ) : ?>
                <li><a href="<?php echo esc_url($foursquare); ?>"><i class="fa fa-foursquare"></i></a></li>
            <?php endif; ?>

            <?php if ( ! empty($behance) ) : ?>
                <li><a href="<?php echo esc_url($behance); ?>"><i class="fa fa-behance"></i></a></li>
            <?php endif; ?>

            <?php if ( ! empty($xing) ) : ?>
                <li><a href="<?php echo esc_url($xing); ?>"><i class="fa fa-xing"></i></a></li>
            <?php endif; ?>

            <?php if ( ! empty($stumbleupon) ) : ?>
                <li><a href="<?php echo esc_url($stumbleupon); ?>"><i class="fa fa-stumbleupon"></i></a></li>
            <?php endif; ?>

        </ul>
        <?php
	}
}

// -- Render Header Layout
if ( ! function_exists( 'adiva_header' ) ) {
	function adiva_header() {
        global $post;

        // Get page options
        $options = get_post_meta( get_the_ID(), '_custom_page_options', true );

        $stickyHeader      = adiva_get_option('sticky-header', 0);
        $header_design 	   = adiva_get_option('header-layout', 1);
		$header_text_color = adiva_get_option('header-text-color', 'dark');
		$header_fullwidth  = adiva_get_option('header-fullwidth', 0);

        if ( isset( $options['page-header'] ) && $options['page-header'] != '' ) {
            $header_design = $options['page-header'];
        }

        if ( isset( $options['page-header'] ) && $options['page-header'] == '7' ) {
            $header_design = '5';
        } elseif ( isset( $options['page-header'] ) && $options['page-header'] == '8' ) {
            $header_design = '1';
        }

		if ( isset( $options['header-text-color'] ) && $options['header-text-color'] != '' ) {
            $header_text_color = $options['header-text-color'];
        }

		if ( isset( $options['header-fullwidth'] ) && $options['header-fullwidth'] != '' ) {
            $header_fullwidth = $options['header-fullwidth'];
        }

		// HEADER CLASS ARRAY
		$header_class = array();

		if ( isset( $header_design ) && $header_design != '' ) {
			$header_classes[] = 'header-' . $header_design;
		}

		if ( isset($header_text_color) && $header_text_color != '' ) {
			$header_classes[] = 'color-scheme-' . $header_text_color;
		}

		if ( isset($header_fullwidth) && $header_fullwidth == 1 ) {
			$header_classes[] = 'header-fullwidth';
		}

		if ( isset( $options['header-overlap'] ) && $options['header-overlap'] == 1 ) {
	        $header_classes[] = 'header-overlap';
	    }
		?>

        <?php if ( isset($stickyHeader) && $stickyHeader == 1 && $header_design != 5 ) get_template_part('template-parts/header/header', 'sticky'); ?>
		<header class="header-wrapper <?php echo implode(' ', $header_classes); ?>">
			<?php get_template_part( 'template-parts/header/header', $header_design ); ?>
        </header>
		<?php
	}
}

/* 	Header Logo
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'adiva_logo' ) ){
	function adiva_logo(){?>
		<?php $header_logo = adiva_get_option('header-logo');
		if( !empty( $header_logo['url'] ) ) : ?>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                <img src="<?php echo esc_url( $header_logo['url'] );?>" alt="<?php bloginfo( 'name' ); ?>">
            </a>
		<?php else:?>
		    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                <img src="<?php echo ADIVA_URL . '/assets/images/logo.png'; ?>" alt="<?php bloginfo( 'name' ); ?>">
            </a>
		<?php endif;?>
		<?php
	}
}

/**
 * Create a breadcrumb menu.
 *
 * @return string
 */
if ( ! function_exists( 'adiva_breadcrumb' ) ) {
	function adiva_breadcrumb() {
		// Settings
		$sep   = '/';

		// Get the query & post information
		global $post, $wp_query;

		// Get post category
		$category = get_the_category();

		// Get product category
		$product_cat = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );

		if ( $product_cat ) {
			$tax_title = $product_cat->name;
		}

		$output = '';

		// Build the breadcrums
		$output .= '<div class="breadcrumb">';

		// Do not display on the homepage
		if ( ! is_front_page() ) {

			if ( ( function_exists( 'is_shop' ) && is_shop() ) || ( function_exists( 'is_product' ) && is_product() ) || function_exists( 'is_product_category' ) && is_product_category() || function_exists( 'is_product_tag' ) && is_product_tag() ) {
				do_action('adiva_woocommerce_breadcrumb');
			} elseif ( is_home() ) {

				// Home page
				$output .= '<a href="' . esc_url( get_home_url() ) . '">' . esc_html__( 'Home', 'adiva' ) . '</a>';
				$output .= $sep;
				$output .= esc_html__( 'Blog', 'adiva' );
			} elseif ( is_post_type_archive() ) {
				$post_type = get_post_type_object( get_post_type() );
				$output .= '<a href="' . esc_url( get_home_url() ) . '">' . esc_html__( 'Home', 'adiva' ) . '</a>';
				$output .= ' ' . $sep . ' ';
				$output .= $post_type->labels->singular_name;
			} elseif ( is_tax() ) {
				$term = $GLOBALS['wp_query']->get_queried_object();
				$output .= '<a href="' . esc_url( get_home_url() ) . '">' . esc_html__( 'Home', 'adiva' ) . '</a>';
				$output .= ' ' . $sep . ' ';
				$output .= $term->name;
			} elseif ( is_single() ) {
				$output .= '<a href="' . esc_url( get_home_url() ) . '">' . esc_html__( 'Home', 'adiva' ) . '</a>';
				$output .= ' ' . $sep . ' ';
				// Single post (Only display the first category)
				if ( ! empty( $category ) ) {
					$output .= '<a href="' . esc_url( get_category_link( $category[0]->term_id ) ) . '">' . $category[0]->cat_name . '</a>';
					$output .= ' ' . $sep . ' ';
				}
				$output .= get_the_title();

			} elseif ( is_category() ) {
				$output .= '<a href="' . esc_url( get_home_url() ) . '">' . esc_html__( 'Home', 'adiva' ) . '</a>';
				$output .= ' ' . $sep . ' ';
				$thisCat = get_category( get_query_var( 'cat' ), false );
				if ( $thisCat->parent != 0 ) echo get_category_parents( $thisCat->parent, TRUE, ' ' );

				// Category page
				$output .= single_cat_title( '', false );

			} elseif ( is_page() ) {
				$output .= '<a href="' . esc_url( get_home_url() ) . '">' . esc_html__( 'Home', 'adiva' ) . '</a>';
				$output .= ' ' . $sep . ' ';

				// Standard page
				if ( $post->post_parent ) {

					// If child page, get parents
					$anc = get_post_ancestors( $post->ID );

					// Get parents in the right order
					$anc = array_reverse($anc);

					// Parent page loop
					foreach ( $anc as $ancestor ) {
						$parents = '<a href="' . esc_url( get_permalink( $ancestor ) ) . '">' . get_the_title( $ancestor ) . '</a>';
						$parents .= ' ' . $sep . ' ';
					}

					// Display parent pages
					$output .= $parents;

					// Current page
					$output .= get_the_title();

				} else {

					// Just display current page if not parents
					$output .= get_the_title();

				}

			} elseif ( is_tag() ) {

				// Tag page

				// Get tag information
				$term_id  = get_query_var( 'tag_id' );
				$taxonomy = 'post_tag';
				$args     = 'include=' . $term_id;
				$terms    = get_terms( $taxonomy, $args );

				$output .= '<a href="' . esc_url( get_home_url() ) . '">' . esc_html__( 'Home', 'adiva' ) . '</a>';
				$output .= ' ' . $sep . ' ';
				// Display the tag name
				$output .= $terms[0]->name;

			} elseif ( is_day() ) {
				$output .= '<a href="' . esc_url( get_home_url() ) . '">' . esc_html__( 'Home', 'adiva' ) . '</a>';
				$output .= ' ' . $sep . ' ';
				// Day archive

				// Year link
				$output .= '<a href="' . esc_url( get_year_link( get_the_time( 'Y' ) ) ) . '">' . get_the_time( 'Y' ) . esc_html__( ' Archives', 'adiva' ) . '</a>';
				$output .= ' ' . $sep . ' ';

				// Month link
				$output .= '<a href="' . esc_url( get_month_link( get_the_time('Y'), get_the_time( 'm' ) ) ) . '">' . get_the_time( 'M' ) . esc_html__( ' Archives', 'adiva' ) . '</a';
				$output .= ' ' . $sep . ' ';

				// Day display
				$output .= get_the_time('jS') . ' ' . get_the_time('M') . esc_html__( ' Archives', 'adiva' );

			} elseif ( is_month() ) {
				$output .= '<a href="' . esc_url( get_home_url() ) . '">' . esc_html__( 'Home', 'adiva' ) . '</a>';
				$output .= ' ' . $sep . ' ';
				// Month Archive

				// Year link
				$output .= '<a href="' . esc_url( get_year_link( get_the_time( 'Y' ) ) ) . '">' . get_the_time( 'Y' ) . esc_html__( ' Archives', 'adiva' ) . '</a>';
				$output .= ' ' . $sep . ' ';

				// Month display
				$output .= get_the_time( 'M' ) . esc_html__( ' Archives', 'adiva' );

			} elseif ( is_year() ) {
				$output .= '<a href="' . esc_url( get_home_url() ) . '">' . esc_html__( 'Home', 'adiva' ) . '</a>';
				$output .= ' ' . $sep . ' ';
				// Display year archive
				$output .= get_the_time('Y') . esc_html__( 'Archives', 'adiva' );

			} elseif ( is_author() ) {
				$output .= '<a href="' . esc_url( get_home_url() ) . '">' . esc_html__( 'Home', 'adiva' ) . '</a>';
				$output .= ' ' . $sep . ' ';
				// Auhor archive

				// Get the author information
				global $author;
				$userdata = get_userdata( $author );

				// Display author name
				$output .= esc_html__( 'Author: ', 'adiva' ) . $userdata->display_name;

			} elseif ( get_query_var('paged') ) {
				$output .= '<a href="' . esc_url( get_home_url() ) . '">' . esc_html__( 'Home', 'adiva' ) . '</a>';
				$output .= ' ' . $sep . ' ';
				// Paginated archives
				$output .= esc_html__( 'Page', 'adiva' ) . ' ' . get_query_var( 'paged' );

			} elseif ( is_search() ) {
				$output .= '<a href="' . esc_url( get_home_url() ) . '">' . esc_html__( 'Home', 'adiva' ) . '</a>';
				$output .= ' ' . $sep . ' ';
				// Search results page
				$output .= esc_html__( 'Search results for: ' . get_search_query(), 'adiva' );

			} elseif ( is_404() ) {

				// 404 page
				$output .= '<a href="' . esc_url( get_home_url() ) . '">' . esc_html__( 'Home', 'adiva' ) . '</a>';
				$output .= ' ' . $sep . ' ';
				$output .= esc_html__( 'Error 404', 'adiva' );
			}

		} else  {
			$output .= '<a href="' . esc_url( get_home_url() ) . '">' . esc_html__( 'Home', 'adiva' ) . '</a>';
			$output .= ' ' . $sep . ' ';
			$output .= esc_html__( 'Front Page', 'adiva' );
		}

		$output .= '</div>';

		return apply_filters( 'adiva_breadcrumb', $output );
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Get post image
 * ------------------------------------------------------------------------------------------------
 */

if ( ! function_exists( 'adiva_get_post_thumbnail' ) ) {
	function adiva_get_post_thumbnail( $size = 'medium', $attach_id = false ) {
		global $post, $adiva_loop;

		if ( has_post_thumbnail() ) {

			if( function_exists( 'wpb_getImageBySize' ) ) {
				if( ! $attach_id ) $attach_id = get_post_thumbnail_id();

				if( ! empty( $adiva_loop['img_size'] ) ) $size = $adiva_loop['img_size'];

				$img = wpb_getImageBySize( array( 'attach_id' => $attach_id, 'thumb_size' => $size, 'class' => 'attachment-large wp-post-image' ) );
				$img = $img['thumbnail'];

			} else {
				$img = get_the_post_thumbnail( $post->ID, $size );
			}

			return $img;
		}
	}
}


if ( ! function_exists ('adiva_post_thumbnail')  ) {
    function adiva_post_thumbnail() {
        if ( has_post_thumbnail() ) {
            ?>
            <div class="post-thumbnail mb_25">
                <a href="<?php esc_url( the_permalink() ); ?>" rel="bookmark" title="<?php the_title(); ?>">
                    <?php the_post_thumbnail( 'post-thumbnail', array( 'alt' => get_the_title() ) ); ?>
                </a>
            </div>
            <?php
        }
    }
}

/**
 * Prints post date.
 *
 * @return string
 */
if( ! function_exists( 'adiva_post_date' ) ) {
	function adiva_post_date() {
		$has_title = get_the_title() != '';

		$attr = '';

        if( ! $has_title && ! is_single() ) {
			$attr = ' onclick="window.location=\''. get_the_permalink() .'\';"';
		}
		?>
			<div class="post-date"<?php echo ($attr); ?>>
				<span class="post-date-day">
					<?php echo get_the_time('d') ?>
				</span>
				<span class="post-date-month">
					<?php echo get_the_time('M') ?>
				</span>
			</div>
		<?php
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Display meta information for a specific post
 * ------------------------------------------------------------------------------------------------
 */
if( ! function_exists( 'adiva_post_meta' )) {
	function adiva_post_meta() {
        $show_date       = adiva_get_option('show-date', 0);
        $show_author     = adiva_get_option('show-author', 1);
        $show_comments   = adiva_get_option('show-comment', 1);
        $show_categories = adiva_get_option('show-category', 1);

		?>
		<ul class="entry-meta-list">
			<?php if( get_post_type() === 'post' ): ?>

				<?php if( is_sticky() ): ?>
					<li class="meta-featured-post"><?php esc_html_e( 'Featured', 'adiva' ) ?></li>
				<?php endif; ?>

                <?php if ( get_the_category_list( ', ' ) && $show_categories ) : ?>
                    <li class="meta-categories"><?php echo get_the_category_list( ', ' ); ?></li>
                <?php endif ?>

				<?php // Author ?>
				<?php if ($show_author == 1): ?>
					<li class="meta-author">
						<?php esc_html_e('By', 'adiva'); ?>
						<?php echo get_avatar( get_the_author_meta( 'ID' ), 32 ); ?>
						<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author"><?php echo get_the_author(); ?></a>
					</li>
				<?php endif ?>
				<?php // Date ?>
				<?php if( $show_date == 1): ?>
                    <li class="meta-date">
                        <span class="time updated"><?php echo get_the_date(); ?></span>
                    </li>
                <?php endif; ?>
				<?php // Comments ?>
				<?php if( $show_comments && comments_open() ): ?>
					<li class="meta-comment">
						<?php
							$comment_link_template = '<span class="comments-count">%s %s</span>';
						 ?>
						<?php comments_popup_link(
							sprintf( $comment_link_template, '0', esc_html__( 'comments', 'adiva' ) ),
							sprintf( $comment_link_template, '1', esc_html__( 'comment', 'adiva' ) ),
							sprintf( $comment_link_template, '%', esc_html__( 'comments', 'adiva' ) )
						); ?>
					</li>
				<?php endif; ?>
			<?php endif; ?>
		</ul>
		<?php
	}
}


// **********************************************************************//
// ! Get exceprt from post content
// **********************************************************************//

if( ! function_exists( 'adiva_excerpt_from_content' ) ) {
	function adiva_excerpt_from_content($post_content, $limit, $shortcodes = '') {
		// Strip shortcodes and HTML tags
		if ( empty( $shortcodes )) {
			$post_content = preg_replace("/\[caption(.*)\[\/caption\]/i", '', $post_content);
			$post_content = preg_replace('`\[[^\]]*\]`','',$post_content);
		}

		$post_content = stripslashes( wp_filter_nohtml_kses( $post_content ) );

		if ( adiva_get_option( 'blog-words-or-letters' ) == 'letter' ) {
			$excerpt = mb_substr( $post_content, 0, $limit );
			if ( mb_strlen( $excerpt ) >= $limit ) {
				$excerpt .= '...';
			}
		} else{
			$limit++;
			$excerpt = explode(' ', $post_content, $limit);
			if ( count( $excerpt) >= $limit ) {
				array_pop( $excerpt );
				$excerpt = implode( " ", $excerpt ) . '...';
			} else {
				$excerpt = implode( " ", $excerpt );
			}
		}

		$excerpt = strip_tags( $excerpt );

		if ( trim( $excerpt ) == '...' ) {
			return '';
		}

		return $excerpt;
	}
}

/**
 * Get post content
 *
 * @since 1.0.0
 */
if( ! function_exists( 'adiva_get_content' ) ) {
	function adiva_get_content( $btn = true ) {
		global $post;

        if ( ! empty( $post->post_excerpt ) ) {
            the_excerpt();
        } else {
	        $excerpt_length = apply_filters( 'adiva_get_excerpt_length', adiva_get_option( 'blog-excerpt-length' ) );
	        echo adiva_excerpt_from_content( $post->post_content, $excerpt_length );
        }

        if( $btn ) {
        	echo '<p class="read-more-section"><a class="btn-read-more more-link" href="' . get_permalink() . '">' . esc_html__('Continue reading', 'adiva') . '</a></p>';
        }

	}
}

/**
 *
 * Limit Post Excerpt Length
 *
 */
if ( ! function_exists('adiva_post_excerpt') ) {
	function adiva_post_excerpt( $limit ) {
	  	$excerpt = explode(' ', get_the_excerpt(), $limit);

	  	if ( count($excerpt) >= $limit ) {
	    	array_pop( $excerpt );
	    	$excerpt = implode(" ",$excerpt).'...';
	  	} else {
	    	$excerpt = implode(" ",$excerpt);
	  	}

	  	$excerpt = preg_replace('`[[^]]*]`','',$excerpt);

	  	echo esc_html($excerpt);
	}
}

/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @return string
 */
if ( ! function_exists( 'adiva_post_pagination' ) ) {
	function adiva_post_pagination( $nav_query = false ) {

		global $wp_query, $wp_rewrite;

		// Don't print empty markup if there's only one page.
		if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
			return;
		}

		// Prepare variables
		$query        = $nav_query ? $nav_query : $wp_query;
		$max          = $query->max_num_pages;
		$current_page = max( 1, get_query_var( 'paged' ) );
		$big          = 999999;
		?>
		<div class="adiva-pagination">
			<?php
				echo '' . paginate_links(
					array(
						'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
						'format'    => '?paged=%#%',
						'current'   => $current_page,
						'total'     => $max,
						'type'      => 'list',
						'prev_text' => '<i class="fa fa-angle-left"></i>',
						'next_text' => '<i class="fa fa-angle-right"></i>',
					)
				) . ' ';
			?>
		</div><!-- .page-nav -->
		<?php
	}
}

/**
 * Render post categories.
 */
if ( ! function_exists( 'adiva_post_categories' ) ) {
    function adiva_post_categories() {
        $categories_list = get_the_category_list( ' ' );
		if ( $categories_list ) {
            echo apply_filters( 'adiva_post_categories', '<div class="post-category mb_20">' . $categories_list . '</div>' );
		}
    }
}

// **********************************************************************//
// ! Get page ID by it's template name
// **********************************************************************//
if( ! function_exists( 'adiva_template_id' ) ) {
	function adiva_template_id( $tpl = '' ) {
		$pages = get_pages(array(
			'meta_key' => '_wp_page_template',
			'meta_value' => $tpl
		));
		foreach($pages as $page){
			return $page->ID;
		}
	}
}


/**
 * Next and previous article
 */

if ( ! function_exists('adiva_post_navigation')) {
	function adiva_post_navigation() {

		$next_post = get_next_post();
		$prev_post = get_previous_post();

        $archive_url = '';

        if( get_post_type() == 'post' ) {
            $archive_url = get_post_type_archive_link( 'posts' );
        } else if( get_post_type() == 'portfolio' ) {
            $archive_url = get_post_type_archive_link( 'portfolio' );
        }
        ?>

        <div class="post-navigation flex between-xs middle-xs">
            <?php if (!empty($prev_post)) : ?>
                <div class="post-prev-post">
                    <div class="post-next-prev-content pr pl_40">
                        <a href="<?php echo esc_url(get_permalink($prev_post->ID)); ?>">
                        <span class="db label-text"><?php esc_html_e('Newer', 'adiva'); ?></span>
                        <div><?php echo get_the_title( $prev_post->ID ); ?></div>
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ( $archive_url && 'page' == get_option( 'show_on_front' ) ): ?>
                <div class="back-to-archive">
                    <a href="<?php echo esc_url( $archive_url ); ?>"></a>
                </div>
            <?php endif ?>

            <?php if (!empty($next_post)) : ?>
                <div class="post-next-post tr">
                    <div class="post-next-prev-content pr pr_40">
                        <a href="<?php echo esc_url(get_permalink($next_post->ID)); ?>">
                            <span class="db label-text"><?php esc_html_e('Older', 'adiva'); ?></span>
                            <div><?php echo get_the_title( $next_post->ID ); ?></div>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
		<?php
	}
}

/**
 * Render related post based on post tags.
 */
if ( ! function_exists( 'adiva_related_posts' ) ) {
	function adiva_related_posts() {
		global $post;

		// Get post's tags
		$taxs = wp_get_post_tags( get_the_ID() );

		if ( $taxs ) {
			// Get id for all tags
			$tag_ids = array();

            foreach( $taxs as $individual_tax ) $tax_ids[] = $individual_tax->term_id;

            // Build arguments to query for related posts
			$args = array(
				'tag__in'               => $tax_ids,
				'post__not_in'          => array( $post->ID ),
				'showposts'             => 6,
				'ignore_sticky_posts'   => 1
			);

			// Get related post
			$related = new wp_query( $args );
            ?>

            <div class="adiva-related-posts mt_50">
                <div class="addon-title">
				    <h3 class="title_2"><?php echo esc_html__('RELATED POSTS', 'adiva'); ?></h3>
                </div>
				<div class="related-posts-carousel owl-carousel owl-theme" data-carousel='{"selector": ".related-posts-carousel", "itemDesktop": "3", "itemSmallDesktop": "3", "itemTablet": "2", "itemMobile": "2", "itemSmallMobile": "1", "margin": 40, "navigation": true, "pagination": false, "autoplay": false, "loop": false}'>
                    <?php
					while ( $related->have_posts() ) :
						$related->the_post(); ?>

						<div class="item">
                            <article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-post-loop blog-design-slider blog-style-flat' ); ?>>
    							<div class="article-inner">
    								<?php if ( has_post_thumbnail() && ! post_password_required() && ! is_attachment() ) : ?>
    									<header class="entry-header pr">
    										<figure class="entry-thumbnail">
    											<div class="post-img-wrap">
    												<a href="<?php echo esc_url( get_permalink() ); ?>">
    													<?php echo adiva_get_post_thumbnail( '500x400' );
    													?>
    												</a>
    											</div>
    										</figure>
    									</header><!-- .entry-header -->
    								<?php endif; ?>

    						        <div class="article-body-container">
						                <ul class="blog-meta">
						                    <li><i class="fa fa-calendar-o"></i><span class="time updated"><?php echo get_the_date(); ?></span></li>
						                </ul>

    						            <h3 class="entry-title">
    						                <a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark"><?php the_title(); ?></a>
    						            </h3>
    						        </div>


    							</div>
    						</article><!-- #post-# -->

						</div>
					<?php endwhile; wp_reset_postdata(); ?>
				</div>
			</div>
            <?php
		} //endif $taxs
	}
}

/**
 * Render related post based on post tags.
 */
if ( ! function_exists( 'adiva_related_portfolio' ) ) {
	function adiva_related_portfolio() {
        global $post;

        $portfolio_style = adiva_get_option( 'portfolio-style', 'default' );

        // Portfolio style
        $classes = array( 'item pr' );
        if ( isset($portfolio_style) && $portfolio_style != '' ) {
            $classes[] = 'portfolio-' . $portfolio_style;
        }

		// Get the portfolio tags.
		$tags = get_the_terms( $post, 'portfolio-tag' );

		if ( $tags ) {
			$tag_ids = array();

			foreach ( $tags as $tag ) {
				$tag_ids[] = $tag->term_id;
			}

			$args = array(
				'post_type'      => 'portfolio',
				'post__not_in'   => array( $post->ID ),
				'posts_per_page' => -1,
				'tax_query'      => array(
					array(
						'taxonomy' => 'portfolio-tag',
						'field'    => 'id',
						'terms'    => $tag_ids,
					),
				)
			);

			// Get portfolio category
			$categories = wp_get_post_terms( get_the_ID(), 'portfolio-cat' );

			$related = new WP_Query( $args );
            ?>
            <div class="adiva-related-portfolio mt_70">
                <div class="addon-title">
				    <h3 class="title_2"><?php esc_html_e('RELATED PORTFOLIO', 'adiva'); ?></h3>
                </div>
				<div class="related-portfolio-carousel owl-carousel owl-theme" data-carousel='{"selector": ".related-portfolio-carousel", "itemDesktop": "3", "itemSmallDesktop": "3", "itemTablet": "2", "itemMobile": "1", "itemSmallMobile": "1", "margin": 40, "navigation": true, "pagination": false, "autoplay": false, "loop": false}'>
                    <?php while ( $related->have_posts() ) : $related->the_post(); ?>
                        <article id="portfolio-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>
    						<div class="portfolio-item pr">
    							<?php if ( has_post_thumbnail() ) : ?>
    								<div class="portfolio-thumbnail pr oh">
    									<a href="<?php echo esc_url( get_permalink() ); ?>">
    										<?php the_post_thumbnail('adiva-portfolio-square'); ?>
    									</a>
    									<a href="<?php echo esc_url( wp_get_attachment_url( get_post_thumbnail_id($post->ID) ) ); ?>" data-rel="mfp[gallery]" class="enlarge"><i class="sl icon-size-fullscreen"></i></a>
    								</div>
    							<?php endif; ?>
    							<div class="portfolio-content">
    								<?php $categories = wp_get_post_terms( get_the_ID(), 'portfolio-cat' ); ?>
    								<?php if ( $categories ) : ?>
    									<div class="portfolio-category"><?php echo get_the_term_list( $post->ID, 'portfolio-cat', '', ', ' ); ?></div>
    								<?php endif; ?>
    								<h4 class="portfolio-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
    							</div>
    						</div>
    					</article>
					<?php endwhile; wp_reset_postdata(); ?>
				</div>
			</div>
            <?php
		} //endif $tags
	}
}

/**
 * Custom function to use to open and display each comment
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'adiva_comments_list' ) ) {
	function adiva_comments_list( $comment, $args, $depth ) {
	// Globalize comment object
		$GLOBALS['comment'] = $comment;

		switch ( $comment->comment_type ) :

			case 'pingback'  :
			case 'trackback' :
				?>
				<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
					<p>
						<?php
							echo esc_html__( 'Pingback:', 'adiva' );
							comment_author_link();
							edit_comment_link( esc_html__( 'Edit', 'adiva' ), '<span class="edit-link">', '</span>' );
						?>
					</p>
				<?php
			break;

			default :
				global $post;
				?>
				<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
                    <article id="comment-<?php comment_ID(); ?>" class="comment_container">
                    	<?php echo get_avatar( $comment, 60 ); ?>

						<div class="comment-text">
							<?php if ( '0' == $comment->comment_approved ) : ?>
								<p class="comment-awaiting-moderation"><?php echo esc_html__( 'Your comment is awaiting moderation.', 'adiva' ); ?></p>
							<?php endif; ?>

							<?php
								printf(
								'<h5 class="comment-author"><span>%1$s</span></h5>',
									get_comment_author_link(),
									( $comment->user_id == $post->post_author ) ? '<span class="author-post">' . esc_html__( 'Post author', 'adiva' ) . '</span>' : ''
								);
							?>
							<div>
								<?php comment_text(); ?>
							</div>


							<div class="flex">
								<?php
									printf(
										'<time class="grow">%3$s</time>',
										esc_url( get_comment_link( $comment->comment_ID ) ),
										get_comment_time( 'c' ),
										sprintf( wp_kses_post( '%1$s at %2$s', 'adiva' ), get_comment_date(), get_comment_time() )
									);
								?>
								<?php
									edit_comment_link( wp_kses_post( '<span><i class="icon-pencil mr_5"></i>' . esc_html__( 'Edit', 'adiva' ) . '</span>', 'adiva' ) );
									comment_reply_link(
										array_merge(
											$args,
											array(
												'reply_text' => wp_kses_post( '<span class="ml__10"><i class="icon-pencil mr_5"></i>' . esc_html__( 'Reply', 'adiva' ) . '</span>', 'adiva' ),
												'depth'      => $depth,
												'max_depth'  => $args['max_depth'],
											)
										)
									);
								?>
							</div><!-- .action-link -->
						</div><!-- .comment-content -->
					</article><!-- #comment- -->
				<?php
			break;

		endswitch;
	}
}
