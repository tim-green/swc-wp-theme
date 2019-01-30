<?php
/**
 * Custom template tags for Publisher
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Publisher
 */

/*-----------------------------------------------------------------------------------*/
/*	Classes Functions
/*-----------------------------------------------------------------------------------*/

/**
 * Adds custom classes to the array of body classes
 */
function publisher_body_class( $classes ) {

    /**
     * Posts Index
     */
    if ( publisher_is_index() ) {
        $classes[] = 'posts-index';
    }

    /**
     * No Active Sidebar
     */
    if ( ! publisher_has_sidebar() ) {
	    $classes[] = 'no-active-sidebar';
    }

    /**
     * Sidebar Alignment
     */
    if ( 'left' == get_option( 'publisher_options_sidebar_alignment' ) ) {
        $classes[] = 'sidebar-align-left';
    } elseif ( 'bottom' == get_option( 'publisher_options_sidebar_alignment' ) ) {
	   $classes[] = 'sidebar-align-bottom';
	}

    /**
     * Group Blog
     */
    if ( is_multi_author() ) {
        $classes[] = 'group-blog';
    }

	/**
     * Single Classes
     */
	if ( is_single() || is_page() && ! is_page_template( 'template-pagebuilder.php' ) ) {

		// Post Layout
		$post_layout_meta = get_post_meta( get_the_ID(), 'publisher_post_layout_meta', true );
		$post_layout_option = publisher_cpt_options() ? get_option( 'publisher_options_' . get_post_type() . '_post_layout' ) : get_option( 'publisher_options_post_layout' );
		$page_layout_option = get_option( 'publisher_options_page_layout' );
		$post_layout_select = is_page() ? $page_layout_option : $post_layout_option;
		$post_layout = $post_layout_meta ? $post_layout_meta : $post_layout_select;

		if ( $post_layout ) {
			$classes[] = 'entry-layout-' . $post_layout;
		}

		// Gallery
		if ( has_post_format( 'gallery' ) && get_post_gallery() ) {
			$classes[] = 'has-post-gallery';
		}

		// Hide Post Details
		if ( is_single() ) {
			$post_details_mod = publisher_cpt_options() ? get_theme_mod( 'publisher_options_' . get_post_type() . '_post_details' ) : get_theme_mod( 'publisher_options_post_details' );
			$post_details = $post_details_mod ? $post_details_mod : array();

			foreach ( $post_details as $post_detail ) {
				$classes[] = 'hide-' . $post_detail;
			}
		}

	}

	/**
     * Theme Customizer
     */
	if ( is_customize_preview() ) {
        $classes[] = 'theme-customizer';
    }

    return $classes;

}
add_filter( 'body_class', 'publisher_body_class' );


/**
 * Adds custom classes to the array of post classes
 */
function publisher_post_class( $classes ) {

	// Attachment
	if ( 'attachment' == get_post_type() && wp_attachment_is_image() ) {
		$classes[] = 'has-post-thumbnail';
	}

	// Post Format Icons
	if ( publisher_is_index() ) {
		$show_format_icon = publisher_cpt_options() ? get_option( 'publisher_options_' . get_post_type() . '_show_format_icon' ) : get_option( 'publisher_options_posts_show_format_icon' );

		if ( 'hide' == $show_format_icon ) {
			$classes[] = 'hide-post-format-icon';
		}
	}

	return $classes;

}
add_filter( 'post_class', 'publisher_post_class' );


/**
 * Get Header Classes
 */
if ( ! function_exists( 'publisher_header_classes' ) ):
function publisher_header_classes( $class = '' ) {

	$classes = array( $class );

    /**
     * Header Image
     */
	if ( get_header_image() ) {
		$classes[] = 'has-header-image';
	}

	// Output
	if ( ! empty( $classes ) ) {
		echo implode( ' ', apply_filters( 'publisher_filter_header_classes', $classes ) );
	}

}
endif; // publisher_header_classes


/**
 * Get Content Classes
 */
if ( ! function_exists( 'publisher_content_classes' ) ):
function publisher_content_classes( $class = '' ) {

	$classes = array( $class );

	// Output
	if ( ! empty( $classes ) ) {
		echo implode( ' ', apply_filters( 'publisher_filter_content_classes', $classes ) );
	}

}
endif; // publisher_content_classes


/**
 * Get Primary Classes
 */
if ( ! function_exists( 'publisher_primary_classes' ) ):
function publisher_primary_classes( $class = '' ) {

	$classes = array( $class );

	/**
     * Posts Page Layout
     */
    if ( publisher_is_index() && ! publisher_is_index( 'product' ) ) {

		$posts_page_layout = publisher_cpt_options() ? get_option( 'publisher_options_' . get_post_type() . '_posts_layout' ) : get_option( 'publisher_options_posts_layout' );
		$post_columns_number = publisher_cpt_options() ? get_option( 'publisher_options_' . get_post_type() . '_post_columns' ) : get_option( 'publisher_options_posts_post_columns' );

		// Posts Page Layout
	    if ( '' != $posts_page_layout ) {
			$classes[] = $posts_page_layout . '-layout';
		}

		// Posts Page Grid Columns
		if ( 'grid' == $posts_page_layout && '' != $post_columns_number ) {
			$classes[] = 'post-column-' . $post_columns_number;
		}

    }

	// Output
	if ( ! empty( $classes ) ) {
		echo implode( ' ', apply_filters( 'publisher_filter_primary_classes', $classes ) );
	}

}
endif; // publisher_primary_classes


/**
 * Get Footer Classes
 */
if ( ! function_exists( 'publisher_footer_classes' ) ):
function publisher_footer_classes( $class = '' ) {

	$classes = array( $class );

	/**
     * Footer Widget Columns
     */
    $footer_columns = get_option( 'publisher_options_footer_columns' );

    if ( $footer_columns ) {
	    $classes[] = 'footer-widgets-' . $footer_columns;
    }

	// Output
	if ( ! empty( $classes ) ) {
		echo implode( ' ', apply_filters( 'publisher_filter_footer_classes', $classes ) );
	}

}
endif; // publisher_footer_classes


/**
 * Get Featured Header Classes
 */
if ( ! function_exists( 'publisher_featured_header_classes' ) ):
function publisher_featured_header_classes( $class = '' ) {

	$classes = array( $class );

    /**
     * Featured Header Image
     */
	if ( publisher_get_featured_header_image() ) {
		$classes[] = 'has-featured-header-image';
	}

	/**
     * Front Page Shortcode
     */
	if ( is_front_page() && '' != get_option( 'publisher_options_front_page_shortcode' ) ) {
		$classes[] = 'has-front-page-shortcode';
	}

	/**
     * Singular Header Classes
     */
	if ( is_singular() ) {
		$page_id = ( 'page' == get_option( 'show_on_front' ) ? get_option( 'page_for_posts' ) : get_the_id() );
		$featured_preview = get_post_meta( $page_id, 'publisher_featured_preview_meta', true );

		// Featured Header Preview
		if ( $featured_preview ) {
			$classes[] = 'has-featured-header-preview';
		}
	}

	// Output
	if ( ! empty( $classes ) ) {
		echo implode( ' ', apply_filters( 'publisher_filter_featured_header_classes', $classes ) );
	}

}
endif; // publisher_featured_header_classes




/*-----------------------------------------------------------------------------------*/
/*	Getter Functions
/*-----------------------------------------------------------------------------------*/

/**
 * Get Archive Title
 */
function publisher_get_the_archive_title( $title ) {

	// Titles
	if ( is_front_page() ) {
		$title = get_option( 'publisher_options_front_page_title' );

	} elseif ( is_category() ) {
        $title = sprintf( single_cat_title( '', false ) );

    } elseif ( is_tag() ) {
        $title = sprintf( single_tag_title( '', false ) );

    } elseif ( is_author() ) {
	    $title = sprintf( '<span class="vcard">%1$s</span>', get_the_author() );

    } elseif ( is_year() ) {
        $title = sprintf( get_the_date( _x( 'Y', 'yearly archives date format', 'publisher' ) ) );

    } elseif ( is_month() ) {
        $title = sprintf( get_the_date( _x( 'F Y', 'monthly archives date format', 'publisher' ) ) );

    } elseif ( is_day() ) {
        $title = sprintf( get_the_date( _x( 'F j, Y', 'daily archives date format', 'publisher' ) ) );

    } elseif ( is_tax( 'post_format' ) ) {
	    if ( is_tax( 'post_format', 'post-format-aside' ) ) {
            $title = _x( 'Asides', 'post format archive title', 'publisher' );
        } elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
            $title = _x( 'Galleries', 'post format archive title', 'publisher' );
        } elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
            $title = _x( 'Images', 'post format archive title', 'publisher' );
        } elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
            $title = _x( 'Videos', 'post format archive title', 'publisher' );
        } elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
            $title = _x( 'Quotes', 'post format archive title', 'publisher' );
        } elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
            $title = _x( 'Links', 'post format archive title', 'publisher' );
        } elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
            $title = _x( 'Statuses', 'post format archive title', 'publisher' );
        } elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
            $title = _x( 'Audio', 'post format archive title', 'publisher' );
        } elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
            $title = _x( 'Chats', 'post format archive title', 'publisher' );
        }

    } elseif ( is_tax() ) {
	    // $tax = get_taxonomy( get_queried_object()->taxonomy );
        // $title = sprintf( __( '%1$s: %2$s', 'publisher' ), $tax->labels->singular_name, single_term_title( '', false ) );
        $title = sprintf( single_term_title( '', false ) );

    } elseif ( is_search() ) {
        $title = sprintf( get_search_query() );

	} elseif ( is_post_type_archive() ) {
		$title = publisher_cpt_options() ? get_option( 'publisher_options_' . get_post_type() . '_page_title' ) : post_type_archive_title( '', false );
		if ( empty( $title ) ) $title = post_type_archive_title( '', false );

    } elseif ( is_404() ) {
        $title = __( 'Not Found', 'publisher' );

    } else {
        $title = __( 'Archive', 'publisher' );

    }

	return $title;
}
add_filter( 'get_the_archive_title', 'publisher_get_the_archive_title' );


/**
 * Get Archive Description
 */
function publisher_get_the_archive_description( $description, $before = '<p>', $after = '</p>' ) {
    global $wp_query;

    if ( is_front_page() ) {
        $homepage_description = get_option( 'publisher_options_front_page_desc' );
        $description = $homepage_description ? $before . $homepage_description . $after : '';

    } elseif ( is_home() ) {
    	$page_description = get_post_meta( get_option( 'page_for_posts' ), 'publisher_page_subtitles_meta', true );
		$description = $page_description ? $before . $page_description . $after : '';

	} elseif ( is_author() ) {
		$author_description = get_the_author_meta( 'description' );
		$author_description .= publisher_get_social_icons( 'author', '', '', '<div class="author-social">', '</div><!-- .author-social -->' );
		$description = $before . $author_description . $after;

    } elseif ( is_page() ) {
	    $page_description = get_post_meta( get_the_ID(), 'publisher_page_subtitles_meta', true );
	    $description = $page_description ? $before . $page_description . $after : '';

    } elseif ( is_search() ) {
        $results = $wp_query->found_posts;
        if ( $results == 0 ) {
            $search_description = __( 'No results found', 'publisher' );

        } elseif ( $results > 1 ) {
            $search_description = sprintf( __( '%s results found', 'publisher' ), $results );

        } else {
            $search_description = __( '1 result found', 'publisher' );

        }
        $description = $before . $search_description . $after;

	} elseif ( is_post_type_archive() ) {
		$page_description = publisher_cpt_options() ? get_option( 'publisher_options_' . get_post_type() . '_page_desc' ) : '';
		$description = $before . $page_description . $after;

    } elseif ( is_404() ) {
        $description = $before . __( 'Well, this is awkward', 'publisher' ) . $after;

    }

	return $description;
}
add_filter( 'get_the_archive_description', 'publisher_get_the_archive_description' );


/**
 * Get the date
 */
if ( ! function_exists( 'publisher_get_the_date' ) ):
function publisher_get_the_date( $before = '', $after = '', $type = '', $before_time = '', $after_time = '', $before_date = '', $after_date = '', $post_id = '' ) {

	// Type
	if ( 'comment' == $type ) {
		$time_ago = get_comment_time( 'U' );
		$time_date = get_comment_date();
		$time_full_date = get_comment_date( 'c' );
		$time_format = get_option( 'publisher_options_comment_time_format' );
		$time_mod = '';
	} else {
		$time_ago = get_the_time( 'U', $post_id );
		$time_date = get_the_date( '', $post_id );
		$time_full_date = get_the_date( 'c', $post_id );
		$time_format = get_option( 'publisher_options_time_format' );

		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_mod = sprintf( '<time class="updated" datetime="%1$s">%2$s</time>',
				esc_attr( get_the_modified_date( 'c' ) ),
				esc_html( get_the_modified_date() )
			);
		} else {
			$time_mod = '';
		}
	}

	if ( 'time' == $time_format ) {
		$time = $before_time . sprintf( __( '%s ago', 'publisher' ), human_time_diff( $time_ago, current_time( 'timestamp' ) ) ) . $after_time;
	} else {
		$time = $before_date . $time_date . $after_date;
	}

	return $before . '<time datetime="' . $time_full_date . '">' . $time . '</time>' . $time_mod . $after;

}
endif; // publisher_get_the_date


/**
 * Get the categories
 */
if ( ! function_exists( 'publisher_get_the_categories' ) ) :
function publisher_get_the_categories( $before = '', $after = '', $before_cat = '', $after_cat = '', $separator = '', $post_id = '' ) {

	if ( publisher_is_custom_post_type() ) {
		$cpt_cat_select = apply_filters( 'publisher_filter_get_the_category_select', get_option( 'publisher_options_' . get_post_type() . '_category_select' ) );
		$get_categories = ( '' != $cpt_cat_select ) ? get_the_terms( get_the_ID(), $cpt_cat_select ) : get_the_category( $post_id );
	} else {
		$get_categories = get_the_category( $post_id );
	}

	if ( '' != $get_categories ) {
		$categories = '';
		foreach( $get_categories as $get_category ) {
			$categories .= $before_cat;
			$categories .= sprintf(  '<a href="%1$s" title="%2$s" %5$s>%3$s</a>%4$s',
				publisher_is_custom_post_type() ? get_term_link( $get_category->term_id, $get_category->taxonomy ) : get_category_link( $get_category->term_id ),
				esc_attr( sprintf( __( 'View all posts in %s', 'publisher' ), $get_category->name ) ),
				publisher_is_custom_post_type() ? $get_category->name : $get_category->cat_name,
				$separator,
				publisher_get_taxonomy_color( 'class="tax-color" style="background-color: ', ';"', $get_category->term_id, $get_category->taxonomy )
			);
			$categories .= $after_cat;
		}

		if ( ! empty( $categories ) ) {
			return $before . $categories . $after;
		}
	}

}
endif; // publisher_get_the_categories


/**
 * Get the featured header image
 */
if ( ! function_exists( 'publisher_get_featured_header_image' ) ):
function publisher_get_featured_header_image() {
    global $post;
    $default_header = get_theme_mod( 'publisher_options_featured_header_default' );
	$bg_image_url = '';

    if ( is_front_page() ) {
        $bg_image_url = get_theme_mod( 'publisher_options_front_page_header_image' );

    } elseif ( is_home() ) {
        $get_bg_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( get_option( 'page_for_posts' ) ), 'full', false, '' );
        $bg_image_url = esc_url( $get_bg_image_url[0] );

	} elseif ( is_author() ) {
		$bg_image_url = get_the_author_meta( 'author_archive_image' );

    } elseif ( is_category() || is_tag() || is_tax() ) {
		$bg_image_url = get_tax_meta( get_queried_object()->term_id, 'publisher_tax_options_featured_image' );

	} elseif ( is_post_type_archive() ) {
		$bg_image_url = publisher_cpt_options() ? get_theme_mod( 'publisher_options_' . get_post_type() . '_header_image' ) : $default_header;

	} elseif ( is_single() && has_post_thumbnail() ) {
		$post_layout_meta = get_post_meta( get_the_ID(), 'publisher_post_layout_meta', true );
		$post_layout_option = publisher_cpt_options() ? get_option( 'publisher_options_' . get_post_type() . '_post_layout' ) : get_option( 'publisher_options_post_layout' );
		$post_layout = $post_layout_meta ? $post_layout_meta : $post_layout_option;

		switch( $post_layout ) {
			case 'cover' :
			case 'wide' :
				$get_bg_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full', false, '' );
				$bg_image_url = esc_url( $get_bg_image_url[0] );
				break;
			default :
				break;
		}

    } elseif ( is_page() && '' != get_the_post_thumbnail() ) {
	    $get_bg_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full', false, '' );
	    $bg_image_url = esc_url( $get_bg_image_url[0] );

    } else {
        $bg_image_url = $default_header;

    }

    // If nothing found, use the default header image
    if ( '' == $bg_image_url ) $bg_image_url = $default_header;

    return apply_filters( 'publisher_filter_header_image', $bg_image_url );
}
endif; // publisher_get_featured_header_image


/**
 * Get the post thumbnail
 */
if ( ! function_exists( 'publisher_get_the_post_thumbnail' ) ) :
function publisher_get_the_post_thumbnail( $image_size, $before = '', $after = '', $type = '', $post_id = '' ) {

	// Get The ID
	if ( '' == $post_id ) $post_id = get_the_ID();

	// Return the URL
	if ( 'url' == $type ) {
		if ( 'attachment' == get_post_type() && wp_attachment_is_image() ) {
			$featured_image_url = wp_get_attachment_url( $post_id );
		} else {
			$get_featured_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), $image_size );
			$featured_image_url = esc_url( $get_featured_image_url[0] );
		}

		return $before . $featured_image_url . $after;
    }

    // Get the featured image
    if ( is_singular() ) {
	    $featured_image = get_the_post_thumbnail( $post_id, $image_size );
    } else {
        $featured_image = sprintf( '<a href="%1$s" title="%2$s">%3$s</a>',
            get_the_permalink(),
            the_title_attribute( 'echo=0' ),
            get_the_post_thumbnail( get_the_ID(), $image_size )
        );
    }

    // Output
    return $before . apply_filters( 'publisher_filter_get_featured_image', $featured_image ) . $after;

}
endif; // publisher_get_the_post_thumbnail


/**
 * Get the thumbnail image size
 */
if ( ! function_exists( 'publisher_get_the_image_size' ) ) :
function publisher_get_the_image_size( $posts_layout = '', $post_columns = '', $post_order = '', $image_size = '' ) {

	if ( empty( $image_size ) ) {
		// Get posts layout if none specified
		if ( empty( $posts_layout ) ) {
			$posts_layout_option = get_option( 'publisher_options_posts_layout' );

			if ( publisher_is_index( 'post' ) ) {
				$posts_layout = $posts_layout_option;
			} else {
				$posts_layout = publisher_cpt_options() ? get_option( 'publisher_options_' . get_post_type() . '_posts_layout' ) : $posts_layout_option;
			}
		}

		// Determine image size
		switch( $posts_layout ) {
			case 'post-image':
				$image_size = ! publisher_has_sidebar() ? 'publisher-post-thumb-standard' : 'publisher-post-thumb-square';
				break;

			case 'related-posts':
				$image_size = 'publisher-post-thumb-medium';
				break;
			case 'listing-featured':
				$image_size = 'publisher-post-thumb-large';
				break;

			case 'listing':
				$image_size = 'thumbnail';
				break;

			case 'slider':
				if ( $post_columns == 1 ) {
					$image_size = 'full';
				} elseif ( $post_columns == 2 ) {
					$image_size = 'publisher-post-thumb-large';
				} elseif ( $post_columns == 3 ) {
					$image_size = 'publisher-post-thumb-large';
				} elseif ( $post_columns == 4 ) {
					if ( $post_order > 1 ) {
						$image_size = 'publisher-post-thumb-standard';
					} else {
						$image_size = 'publisher-post-thumb-large';
					}
				} elseif ( $post_columns == 5 ) {
					if ( $post_order > 1 ) {
						$image_size = 'publisher-post-thumb-standard';
					} else {
						$image_size = 'publisher-post-thumb-large';
					}
				}
				break;

			case 'cover':
			case 'mini-grid':
				if ( $post_columns == 1 ) {
					$image_size = 'publisher-post-thumb-large';
				} elseif ( $post_columns == 2 ) {
					$image_size = 'publisher-post-thumb-medium';
				} elseif ( $post_columns == 3 ) {
					$image_size = 'publisher-post-thumb-square';
				} elseif ( $post_columns > 3 ) {
					$image_size = 'publisher-post-thumb-small';
				} else {
					$image_size = 'publisher-post-thumb-standard';
				}
				break;

			case 'grid':
				if ( empty( $post_columns ) ) {
					$post_columns = publisher_cpt_options() ? get_option( 'publisher_options_' . get_post_type() . '_post_columns' ) : get_option( 'publisher_options_posts_post_columns' );
				}

				if ( $post_columns == 2 ) {
					$image_size = ! publisher_has_sidebar() ? 'publisher-post-thumb-medium' : 'publisher-post-thumb-small';
				} elseif ( $post_columns > 2 ) {
					$image_size = 'publisher-post-thumb-small';
				} else {
					$image_size = ! publisher_has_sidebar() ? 'publisher-post-thumb-large' : 'publisher-post-thumb-standard';
				}
				break;

			case 'list':
				$image_size = ! publisher_has_sidebar() ? 'publisher-post-thumb-large' : 'publisher-post-thumb-standard';
				break;

			case 'standard':
			default:
				$image_size = ! publisher_has_sidebar() ? 'publisher-post-thumb-large' : 'publisher-post-thumb-standard';
				break;
		}

		// Set full size if no image size
		if ( ! $image_size ) $image_size = 'full';

	}

	// Return image size
    return apply_filters( 'publisher_filter_get_featured_image_size', $image_size );

}
endif; // publisher_get_the_image_size


/**
 * Get the post thumbnail link
 */
if ( ! function_exists( 'publisher_get_the_post_thumbnail_link' ) ) :
function publisher_get_the_post_thumbnail_link( $before = '', $after = '', $icon = '<i class="fa fa-search-plus"></i>' ) {

	// Get the featured image link
    $get_featured_image_link = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full', false, '' );

    $featured_image_link = sprintf( '<a class="featured-image-link" href="%1$s" title="%2$s">%3$s</a>',
        esc_url( $get_featured_image_link[0] ),
        __( 'View Image', 'publisher' ),
        $icon
    );

    // Output
    if ( ! empty( $get_featured_image_link ) ) {
    	return $before . apply_filters( 'publisher_filter_get_featured_image_link', $featured_image_link ) . $after;
    }
}
endif; // publisher_get_the_post_thumbnail_link


/**
 * Get the featured post gallery
 */
if ( ! function_exists( 'publisher_get_post_gallery' ) ) :
function publisher_get_post_gallery( $gallery_args = array() ) {

	if ( get_post_gallery() ) {

		$gallery = get_post_gallery( get_the_ID(), false );
		$get_post_gallery = '';
		$get_post_gallery_pager = '';

		// Gallery Args
		$gallery_args = wp_parse_args( $gallery_args, array(
			'before' => '',
			'after' => '',
			'loader' => false,
			'pager' => false,
			'slider_open' => '<div class="owl-carousel">',
			'slider_close' => '</div><!-- .owl-carousel -->'
		) );

		// Get the loader icon
		if ( $gallery_args['loader'] ) {
			$get_post_gallery .= publisher_get_loader_icon();
		}

		// Get the gallery
		$i = 0;
		$get_post_gallery .= $gallery_args['slider_open'];
			/* Loop through all the image and output them one by one */
			foreach( $gallery['src'] as $item ) {
				$image_object = wp_prepare_attachment_for_js( publisher_get_attachment_id_from_url( $item ) );

				$get_post_gallery .= sprintf( '<figure class="gallery-item gallery-item-%1$s gallery-order-%2$s" data-dot="%3$s">',
					$image_object['id'],
					$i,
					$gallery_args['pager'] ? '<img src=\'' . esc_url( $image_object['sizes']['thumbnail']['url'] ) . '\' alt=\'pager-' . $image_object['id'] .'\' />' : '<span></span>'
				);

				$get_post_gallery .= sprintf( '<a class="gallery-item-link" href="%1$s"><img src="%1$s" width="%2$s" height="%3$s" alt="%4$s"/></a>',
					esc_url( $image_object['url'] ),
					intval( $image_object['width'] ),
					intval( $image_object['height'] ),
					esc_attr( $image_object['title'] )
				);

				if ( '' != $image_object['caption'] ) {
					$get_post_gallery .= sprintf( '<figcaption class="wp-caption-text gallery-caption">%1$s</figcaption>', $image_object['caption'] );
				}

				$get_post_gallery .= '</figure>';

				// Get the pager
				if ( $gallery_args['pager'] ) {
					$get_post_gallery_pager .= sprintf( '<a href="%1$s"><img src="%2$s" /></a>',
						'#' . $i,
						esc_url( $image_object['sizes']['thumbnail']['url'] )
					);
				}

				$i++;
			}
		$get_post_gallery .= $gallery_args['slider_close'];

		// Output Featured Post Gallery
		return $gallery_args['before'] . $get_post_gallery . $gallery_args['after'];

	}

}
endif; // publisher_get_post_gallery


/**
 * Get the post format icon
 */
if ( ! function_exists( 'publisher_get_post_format_icon' ) ):
function publisher_get_post_format_icon( $before = '', $after = '', $type = '', $link = false ) {

	if ( empty( $type ) ) {
		$type = get_post_format();

		// Set if is a sticky post
		if ( $type == false && is_sticky() ) $type = 'sticky';
	}

	// Get the icon type
	switch ( $type ) {
		case 'audio':
			$icon = '<i class="fa fa-music"></i>';
			break;
		case 'video':
			$icon = '<i class="fa fa-play"></i>';
			break;
		case 'gallery':
			$icon = '<i class="fa fa-camera"></i>';
			break;
		case 'link':
			$icon = '<i class="fa fa-link"></i>';
			break;
		case 'quote':
			$icon = '<i class="fa fa-quote-left"></i>';
			break;
		default:
			$icon = false;
			break;
	}

    /**
     * Filter the icon
     */
    $icon = apply_filters( 'publisher_filter_get_post_format_icon', $icon, $type );

	if ( ! empty( $link ) && ! empty( $icon ) ) {
		$icon = '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $icon . '</a>';
	}

    if ( ! empty( $icon ) ) {
        return $before . $icon . $after;
    };

}
endif; // publisher_get_post_format_icon


/**
 * Get the post featured icon
 */
if ( ! function_exists( 'publisher_get_post_featured_icon' ) ):
function publisher_get_post_featured_icon( $before = '', $after = '' ) {

	$icon = apply_filters( 'publisher_filter_get_post_flash_icon', '<i class="fa fa-star"></i>' );

	if ( is_sticky() ) {
		$icon = '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $icon . '</a>';
		return $before . $icon . $after;
	}

	// Get the featured terms
	$get_featured_terms = get_theme_mod( 'publisher_options_featured_terms' );
	$featured_terms = $get_featured_terms ? $get_featured_terms : '';

	if ( $featured_terms ) {
		// loop through the terms
		foreach( $featured_terms as $featured_term ) {
			list( $term_id, $taxonomy ) = explode( "_", $featured_term, 2 );

			if ( has_term( $term_id, $taxonomy ) ) {
				$icon = '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $icon . '</a>';
				return $before . $icon . $after;
				break;
			}
		}
	}

	return false;

}
endif; // publisher_get_post_featured_icon


/**
 * Get the author
 */
if ( ! function_exists( 'publisher_get_the_author' ) ):
function publisher_get_the_author( $before = '', $after = '', $avatar_size = 27, $post_id = '' ) {

	$author_id = ( '' != $post_id ) ? get_post_field( 'post_author', $post_id ) : get_the_author_meta( 'ID' );

	$author = sprintf( '<a href="%1$s">%2$s<span>%3$s</span></a>',
		esc_url( get_author_posts_url( $author_id ) ),
		get_avatar( get_the_author_meta( 'user_email', $author_id ), apply_filters( 'publisher_filter_author_avatar_size', $avatar_size ) ),
		esc_html( get_the_author_meta( 'display_name', $author_id ) )
	);

	return $before . $author . $after;

}
endif; // publisher_get_the_author


/**
 * Get the excerpt
 *
 * @param mixed $type Use "manual" to show manual excerpt.
 */
if ( ! function_exists( 'publisher_get_the_excerpt' ) ):
function publisher_get_the_excerpt( $before = '', $after = '', $type = '', $num_words = '', $more = '&hellip;', $link = false ) {

	global $post;

	// Set Number of Words
	if ( empty( $num_words ) ) $num_words = publisher_excerpt_length();

	// If link is true
	if ( ! empty( $link ) )	$more = '<a class="more-link-custom" href="' . esc_url( get_permalink( $post->ID ) ) . '">' . $more . '</a>';

	if ( 'custom' == $type ) {
		if ( $post->post_excerpt ) {
			$excerpt = wp_trim_words( get_the_excerpt(), $num_words, $more );
		}
	} elseif( 'manual' == $type ) {
		$excerpt = wp_trim_words( get_the_content(), $num_words, $more );
	} else {
		$excerpt = get_the_excerpt();
	}

	// Output
	if ( ! empty( $excerpt ) ) {
		return $before . $excerpt . $after;
	}

}
endif; // publisher_get_the_excerpt


/**
 * Get the comments link
 */
if ( ! function_exists( 'publisher_get_comments_link' ) ) :
function publisher_get_comments_link( $before = '', $after = '', $before_count = '', $after_count = '', $post_id = '' ) {

	$num_comments = get_comments_number( $post_id ); // get_comments_number returns only a numeric value

	if ( $num_comments && comments_open( $post_id ) ) {
		if ( $num_comments == 0 ) {
			$comments = '';
		} elseif ( $num_comments > 1 ) {
			$comments = sprintf( '%1$s', $num_comments );
		} else {
			$comments = sprintf( '%1$s', $num_comments );
		};

		$get_comments = '<a href="' . get_comments_link( $post_id ) .'">' . $before_count . $comments . $after_count . '</a>';
	} else {
		$get_comments = '';
	};

	if ( ! empty( $get_comments ) ) {
		return $before . $get_comments . $after;
	}

}
endif; // publisher_get_comments_link


/**
 * Get the read time
 */
if ( ! function_exists( 'publisher_get_read_time' ) ):
function publisher_get_read_time( $before = '', $after = '', $before_time = '', $after_time = '' ) {
	global $post;
	// Get Word Count
    $content = get_post_field( 'post_content', $post->ID );
    $word_count = str_word_count( strip_tags( $content ) );
    $read_time = $before_time . ceil( $word_count / 250 ) . $after_time;

    return $before . sprintf( __( '%s Min Read', 'publisher' ), $read_time ) . $after;
}
endif; // publisher_get_read_time


/**
 * Get the social icons
 *
 * @param string $type Use 'author' for profile contact info
 * @param string $before Optional. Content to prepend before links. Default empty.
 * @param string $after  Optional. Content to append after links. Default empty.
 * @param string $before_social Optional. Content to prepend before social. Default empty.
 * @param string $after_social  Optional. Content to append after social. Default empty.
 */
if ( ! function_exists( 'publisher_get_social_icons' ) ):
function publisher_get_social_icons( $type = '', $before = '', $after = '', $before_social = '', $after_social = '' ) {
    $output = '';
    $socials = array(
        'twitter' 		=> 'fa-twitter',
        'facebook' 		=> 'fa-facebook',
        'instagram' 	=> 'fa-instagram',
        'tumblr' 		=> 'fa-tumblr',
        'dribbble' 		=> 'fa-dribbble',
        'flickr' 		=> 'fa-flickr',
        'pinterest' 	=> 'fa-pinterest',
        'googleplus' 	=> 'fa-google-plus',
        'vimeo' 		=> 'fa-play',
        'youtube' 		=> 'fa-youtube',
        'soundcloud'	=> 'fa-soundcloud',
        'linkedin' 		=> 'fa-linkedin',
        'github' 		=> 'fa-github',
        'rss' 			=> 'fa-rss-square',
        'public_email' 	=> 'fa-envelope-o'
    );

    // Display social links
    foreach ( $socials as $social => $class ) {
        switch ( $type ) {
    		default:
        		$social_option = get_the_author_meta( $social );
        		break;
        }

        // Output social icons
        if ( $social_option ) {
            if ( $social == 'public_email' ) {
                $social_link = 'mailto:' . antispambot( $social_option );
            } else {
                $social_link = esc_url( $social_option );
            };
            $output .= $before;
            $output .= '<a href="' . esc_url( $social_link ) . '" class="' . esc_attr( $social ) . '-icon" title="' . esc_attr( ucfirst( $social ) ) . '">';
            $output .= '<i class="fa ' . $class . '"></i>';
            $output .= '</a>';
            $output .= $after;
        };
    };

	// Output
	if ( ! empty( $output ) ) {
    	return $before_social . $output . $after_social;
    }
}
endif; // publisher_get_social_icons


/**
 * Get Related Posts
 */
if ( ! function_exists( 'publisher_get_related_posts' ) ) :
function publisher_get_related_posts( $count = 3, $post_id = null ) {

	// Set post ID
	if ( empty( $post_id ) ) {
		$post_id = get_the_ID();
	}

	// Related Post Args
	$args = array(
		'post_type' => get_post_type(),
		'post__not_in' => array( $post_id ),
		'posts_per_page' => $count,
		'ignore_sticky_posts' => 1,
		'meta_key' => '_thumbnail_id'
	);

	// Set Taxonomy
	$tag_select = publisher_is_custom_post_type() ? get_option( 'publisher_options_' . get_post_type() . '_tag_select' ) : 'post_tag';
	$cat_select = publisher_is_custom_post_type() ? get_option( 'publisher_options_' . get_post_type() . '_category_select' ) : 'category';

	if ( ! empty( $tag_select ) ) {
		$taxonomies = array( $tag_select );
	} elseif ( ! empty( $cat_select ) ) {
		$taxonomies = array( $cat_select );
	} else {
		$taxonomies = get_object_taxonomies( get_post_type() );
		if ( ! array_filter( $taxonomies ) ) return false;

		// Setup args for multiple taxonomies
		$args['tax_query']['relation'] = 'OR';
	}

	// Build Terms
	foreach ( $taxonomies as $taxonomy ) {

		$terms = get_the_terms( get_the_ID(), $taxonomy );

		// If nothing found, return false
		if ( ! $terms || is_wp_error( $terms ) ) return false;

		foreach ( $terms as $term ) {

			// Taxonomoy
			$args['tax_query'][$term->taxonomy]['taxonomy'] = $taxonomy;

			// Field
			$args['tax_query'][$term->taxonomy]['field'] = 'term_id';

			// Terms
			$args['tax_query'][$term->taxonomy]['terms'][] = $term->term_id;

		}

	}

	// Return Args
	return apply_filters( 'publisher_filter_get_related_posts_args', $args );

}
endif; // publisher_get_related_posts


/**
 * Get the sidebar
 */
if ( ! function_exists( 'publisher_get_sidebar' ) ):
function publisher_get_sidebar() {
	return apply_filters( 'publisher_filter_sidebar', 'sidebar-main' );
}
endif; // publisher_get_sidebar


/**
 * Get the loader icon
 */
if ( ! function_exists( 'publisher_get_loader_icon' ) ):
function publisher_get_loader_icon() {
	$loader_text = apply_filters( 'publisher_loader_icon_text', __( 'Loading', 'publisher' ) );
	return '<div class="loader-icon"><div class="loader">' . $loader_text . '</div></div>';
}
endif; // publisher_get_loader_icon


/**
 * Get the attachment ID from URL
 *
 * Will eventually be replaced by attachment_url_to_postid.
 * Currently though, that function is still a bit wonky.
 */
if ( ! function_exists( 'publisher_get_attachment_id_from_url' ) ):
function publisher_get_attachment_id_from_url( $attachment_url = '' ) {

	global $wpdb;
	$attachment_id = false;

	// If there is no url, return.
	if ( '' == $attachment_url )
		return;

	// Get the upload directory paths
	$upload_dir_paths = wp_upload_dir();

	// Make sure the upload path base directory exists in the attachment URL, to verify that we're working with a media library image
	if ( false !== strpos( $attachment_url, $upload_dir_paths['baseurl'] ) ) {

		// If this is the URL of an auto-generated thumbnail, get the URL of the original image
		$attachment_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url );

		// Remove the upload path base directory from the attachment URL
		$attachment_url = str_replace( $upload_dir_paths['baseurl'] . '/', '', $attachment_url );

		// Finally, run a custom database query to get the attachment ID from the modified attachment URL
		$attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $attachment_url ) );

	}

	return $attachment_id;
}
endif; // publisher_get_attachment_id_from_url


/**
 * Get a specific element
 */
if ( ! function_exists( 'publisher_get_element' ) ):
function publisher_get_element( $pattern, $subject, $order, $suborder ) {
	global $post;
	ob_start();
	ob_end_clean();
	preg_match_all( $pattern, $subject, $matches );

	if ( isset( $matches[ $order ][ $suborder ] ) ) {
		$output = $matches[ $order ][ $suborder ];
	} else {
		$output = '';
	}

	return $output;
}
endif; // publisher_get_element


/**
 * Get taxonomy color
 */
if ( ! function_exists( 'publisher_get_taxonomy_color' ) ) :
function publisher_get_taxonomy_color( $before = '', $after = '', $term_id = '', $taxonomy = '' ) {
	// Term ID
	if ( empty( $term_id ) ) {
		if ( is_tax() || is_archive() ) {
			$term_id = get_queried_object()->term_id;
		} else {
			$category = get_the_category();

			// Don't show if more than 1 category
			if ( count( $category ) > 1 ) {
				$term_id = '';
			} else {
				$term_id = isset( $category[0] ) ? $category[0]->term_id : '';
			};
		}
	}

	// Get taxonomy color if selected
	$taxonomy_image_color = get_theme_mod( 'publisher_options_taxonomy_image_color' );
	if ( empty( $taxonomy_image_color ) ) $taxonomy_image_color = array();

	$taxonomy_pages = array_merge( array( 'category', 'post_tag', 'product_cat', 'product_tag' ), array_filter( $taxonomy_image_color ) );
	$taxonomy_color = in_array( $taxonomy, $taxonomy_pages ) ? get_tax_meta( $term_id, 'publisher_tax_options_color' ) : '';

	if ( ! empty( $taxonomy_color ) ) {
		return $before . $taxonomy_color . $after;
	}
}
endif; // publisher_get_taxonomy_color




/*-----------------------------------------------------------------------------------*/
/*	Print Functions
/*-----------------------------------------------------------------------------------*/

/**
 * Show the breadcrumbs
 */
if ( ! function_exists( 'publisher_breadcrumbs' ) ):
function publisher_breadcrumbs() {
	global $post;

	$breadcrumbs = '';
	$home_link = '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . apply_filters( 'publisher_breadcrumbs_home_text', __( 'Home', 'publisher' ) ) . '</a><span class="sep">/</span></li>';
	$sep = apply_filters( 'publisher_breadcrumbs_sep', '<span class="sep">/</span>' );

	if ( is_attachment() ) :
		$breadcrumbs .= sprintf( '<li>%1$s<span class="sep">/</span></li> <li class="current">%2$s</li>',
			__( 'Media', 'publisher' ),
			single_post_title( '', false )
		);

	elseif ( is_single() ) :

		if ( publisher_cpt_options() ) {

			// Archive Home Link
			$cpt_default_archive = get_theme_mod( 'publisher_options_' . get_post_type() . '_post_default_archive' );
			$cpt_archive_home_id = get_option( 'publisher_options_' . get_post_type() . '_post_archive_home' );

			if ( $cpt_default_archive ) {
				$obj = get_post_type_object( get_post_type() );
				$button_url = get_post_type_archive_link( get_post_type() );
				$title = $obj->label;

				$breadcrumbs .= sprintf( '<li><a href="%1$s">%2$s</a><span class="sep">/</span></li>',
					esc_url( $button_url ),
					$title
				);
			} elseif ( '' != $cpt_archive_home_id ) {
				$button_url = get_permalink( $cpt_archive_home_id );

				$breadcrumbs .= sprintf( '<li><a href="%1$s">%2$s</a><span class="sep">/</span></li>',
					esc_url( $button_url ),
					get_the_title( $cpt_archive_home_id )
				);
			};

			// Category Select
			$cpt_cat_select = get_option( 'publisher_options_' . get_post_type() . '_category_select' );
			if ( '' != $cpt_cat_select ) {
				$breadcrumbs .= get_the_term_list( get_the_ID(), $cpt_cat_select, '<li>', $sep . '</li> <li>', '<span class="sep">/</span></li>' );
			};

		} else {

			if ( '' != get_the_category_list() ) {
				$breadcrumbs .= sprintf( '<li>%1$s<span class="sep">/</span></li>',
					get_the_category_list( $sep . '</li> <li>' )
				);
			}

		}

		$breadcrumbs .= sprintf( '<li class="current">%1$s</li>',
			single_post_title( '', false )
		);

	elseif ( is_page() ) :
		$ancestors = '';
		if ( $post->post_parent ) {
			$get_post_ancestors = get_post_ancestors( $post->ID );
			foreach ( array_reverse( $get_post_ancestors ) as $ancestor ) {
				$ancestors .= '<li><a href="' . esc_url( get_permalink( $ancestor ) ) . '" title="' . get_the_title( $ancestor ) . '">' . get_the_title( $ancestor ) . '</a>' . $sep . '</li>';
			}
		}
		$breadcrumbs .= sprintf( '%1$s<li class="current">%2$s</li>',
			$ancestors,
			get_the_title()
		);

	endif;

	/**
	 * Filter the breadcrumbs
	 */
	$breadcrumbs = apply_filters( 'publisher_breadcrumbs', $breadcrumbs );

	if ( '' != $breadcrumbs ) :
	?>
		<nav class="breadcrumbs-navigation clearfix">
			<h1 class="screen-reader-text"><?php _e( 'Breadcrumbs navigation', 'publisher' ); ?></h1>
			<div class="breadcrumbs">
				<?php
					printf( '<ul class="breadcrumbs-menu clearfix">%1$s %2$s</ul>',
						$home_link,
						$breadcrumbs
					);
				?>
			</div><!-- .nav-links -->
		</nav><!-- .breadcrumbs-navigation -->
	<?php
	endif;
}
endif; // publisher_breadcrumbs


/**
 * Show the tags
 */
if ( ! function_exists( 'publisher_the_tags' ) ) :
function publisher_the_tags( $before = '', $sep = '', $after = '' ) {

	$activate_options = apply_filters( 'publisher_filter_the_tags', false );

	if ( publisher_cpt_options() || $activate_options ) {
		$cpt_tag_select = get_option( 'publisher_options_' . get_post_type() . '_tag_select' );
		if ( '' != $cpt_tag_select ) {
			echo get_the_term_list( get_the_ID(), $cpt_tag_select, $before, $sep, $after );
		};
	} else {
		the_tags( $before, $sep, $after );
	}

}
endif; // publisher_the_tags


/**
 * Custom comment callback
 */
if ( ! function_exists( 'publisher_get_comment' ) ) :
function publisher_get_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment; ?>

	<li id="li-comment-<?php comment_ID() ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'comment-item-has-children' ); ?>>

		<article id="comment-<?php comment_ID(); ?>" class="comment-body">
			<header class="comment-meta clearfix">
				<div class="comment-author vcard">
					<?php echo get_avatar( $comment->comment_author_email, $args['avatar_size'] ); ?>

					<?php
						// Get author post archive if comment is by author
						$post = get_post( get_the_ID() );
						if ( $comment->user_id === $post->post_author ) {
							printf( '<a class="comment-author-icon" href="%1$s">%2$s</a>',
								esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
								'<i class="fa fa-pencil-square-o"></i>'
							);
						}
					?>

					<?php
						printf( __( '<cite class="fn">%1$s</cite>&nbsp;<span class="says">says</span>', 'publisher' ),
								get_comment_author_link()
						);
					?>
				</div><!-- .comment-author -->

				<div class="comment-metadata">
					<a class="comment-time" href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
						<?php
							printf( __( '%1$s <span class="at-time">at %2$s</span>', 'publisher' ),
								publisher_get_the_date( '<i class="fa fa-clock-o"></i>', '', 'comment' ),
								get_comment_time()
							);
						?>
					</a>
					<?php edit_comment_link( __( '(Edit)', 'publisher' ), '  ', '' ); ?>
				</div><!-- .comment-metadata -->

			</header><!-- .comment-meta -->

			<div class="comment-content">
				<?php comment_text() ?>

				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'publisher' ); ?></em>
				<?php endif; ?>
			</div><!-- .comment-content -->

			<?php
				comment_reply_link( array_merge(
					$args, array(
						'depth' => $depth,
						'max_depth' => $args['max_depth'],
						'before' => '<div class="reply">',
						'after' => '</div><!-- .reply -->'
					)
				) );
			?>

		</article><!-- .comment-body -->

	<?php
	/* </li> closes in comments.php */

}
endif; // publisher_get_comment




/*-----------------------------------------------------------------------------------*/
/*	Checker Functions
/*-----------------------------------------------------------------------------------*/

/**
 * Returns true if on a posts index page
 *
 * @param mixed $types	Post Type
 * @return bool
 */
if ( ! function_exists( 'publisher_is_index' ) ) :
function publisher_is_index( $types = '' ) {
    global $wp_query;

    // Convert the type to an array
    if ( is_array( $types ) ) {
        foreach ( $types as $type ) {
            $post_types[] = $type;
        }
    } else {
        $post_types[] = $types;
    }

	// Check for posts index
	foreach ( $post_types as $post_type ) {

		if ( 'post' == $post_type || 'default' == $post_type ) {
			$index[] = ( is_home() || is_archive() || ( is_search() && 0 !== $wp_query->found_posts ) ) && ! is_post_type_archive() && ! is_tax() ? true : false;

		} elseif( '' != $post_type ) {
			$taxonomies = get_taxonomies( array( 'public' => true ), 'objects' );
			$post_type_tax = array();
			foreach ( $taxonomies as $taxonomy ) {
				foreach ( $taxonomy->object_type as $type ) {
					if ( $type == $post_type ) {
						$post_type_tax[] = $taxonomy->name;
					} else {
						continue;
					}
				}
			}

			$index[] = is_post_type_archive( $post_type ) || is_tax( $post_type_tax ) ? true : false;

		} else {
			// Every post index including a search with results
			$index[] = ( is_home() || is_archive() || ( is_search() && 0 !== $wp_query->found_posts ) || is_post_type_archive() ) ? true : false;

		}

    }

    $retval = in_array( true, $index, true ) ? true : false;

    return (bool) apply_filters( 'publisher_filter_is_index', $retval );

}
endif; // publisher_is_index


/**
 * Returns true if has a main sidebar
 */
if ( ! function_exists( 'publisher_has_sidebar' ) ) :
function publisher_has_sidebar() {
	$retval = true;
	$sidebar_layout = '';

	// If no active sidebar, return false
	if ( is_page_template( 'template-pagebuilder.php' ) || ! is_active_sidebar( publisher_get_sidebar() ) ) {
		return false;
	}

	// Sidebar Layout
    if ( is_post_type_archive() ) {
        $sidebar_layout = publisher_cpt_options() ? get_option( 'publisher_options_' . get_post_type() . '_posts_sidebar_display' ) : get_option( 'publisher_options_posts_sidebar_display' );

    } elseif ( is_tax() ) {
	    $sidebar_layout = publisher_cpt_options() ? get_option( 'publisher_options_' . get_post_type() . '_posts_sidebar_display' ) : get_option( 'publisher_options_posts_sidebar_display' );

	} elseif ( is_singular() ) {
		$sidebar_layout_meta = get_post_meta( get_the_ID(), 'publisher_sidebar_layout_meta', true );
		$page_sidebar_layout = get_option( 'publisher_options_page_sidebar_display' );
		$post_sidebar_layout = publisher_cpt_options() ? get_option( 'publisher_options_' . get_post_type() . '_post_sidebar_display' ) : get_option( 'publisher_options_post_sidebar_display' );
		$sidebar_layout_setting = is_page() ? $page_sidebar_layout : $post_sidebar_layout;
		$sidebar_layout = $sidebar_layout_meta ? $sidebar_layout_meta : $sidebar_layout_setting;

	} else {
        $sidebar_layout = get_option( 'publisher_options_posts_sidebar_display' );

    }

	// If set to hide, return false
	if ( 'hide' == $sidebar_layout ) {
		return false;
	}

	return (bool) apply_filters( 'publisher_filter_has_sidebar', $retval );

}
endif; // publisher_has_sidebar


/**
 * Returns true if is a custom post type
 */
if ( ! function_exists( 'publisher_is_custom_post_type' ) ) :
function publisher_is_custom_post_type( $post_type_check = '' ) {

	$post_types = get_post_types( array( 'public' => true, '_builtin' => false ), 'names', 'and' );

	if ( '' == $post_type_check ) $post_type_check = get_post_type();

	foreach ( $post_types as $post_type ) {
		if ( $post_type_check == $post_type ) {
			return true;
		} else {
			continue;
		}
	}

	return false;

}
endif; // publisher_is_custom_post_type


/**
 * Returns true if custom post type options are active
 */
if ( ! function_exists( 'publisher_cpt_options' ) ) :
function publisher_cpt_options() {
	$cpt_options = get_theme_mod( 'publisher_options_cpt_options' );

	if ( ! empty( $cpt_options ) ) {
		$activate_options = apply_filters( 'publisher_filter_cpt_options', in_array( get_post_type(), array_filter( $cpt_options ) ) );
		if ( publisher_is_custom_post_type() && $activate_options ) {
			return true;
		}
	}

	return false;
}
endif; // publisher_cpt_options


/**
 * Returns true if has taxonomy / category
 */
if ( ! function_exists( 'publisher_has_category' ) ) :
function publisher_has_category( $category = '', $post_id = '' ) {

	$activate_options = apply_filters( 'publisher_filter_has_category_activate', false );

	if ( ! empty( $activate_options ) ) {
		return true;
	} elseif ( publisher_cpt_options() ) {
		$cpt_cat_select = get_option( 'publisher_options_' . get_post_type() . '_category_select' );
		return ( '' != $cpt_cat_select ) ? true : false;
	} else {
		return has_category( $category, $post_id );
	}

	return false;

}
endif; // publisher_has_category


/**
 * Returns true if a blog has more than 1 category
 */
if ( ! function_exists( 'publisher_has_categorized_blog' ) ) :
function publisher_has_categorized_blog() {
    if ( false === ( $all_the_cool_cats = get_transient( 'publisher_categories' ) ) ) {
        // Create an array of all the categories that are attached to posts.
        $all_the_cool_cats = get_categories( array(
            'fields'     => 'ids',
            'hide_empty' => 1,

            // We only need to know if there is more than one category.
            'number'     => 2,
        ) );

        // Count the number of categories that are attached to the posts.
        $all_the_cool_cats = count( $all_the_cool_cats );

        set_transient( 'publisher_categories', $all_the_cool_cats );
    }

	return ( $all_the_cool_cats > 1 ) ? true : false;

}
endif; // publisher_has_categorized_blog


/**
 * Returns true if Jetpack is active and/or specified module is active
 *
 * @param string $module jetpack module name
 * @return true false
 */
if ( ! function_exists( 'publisher_get_jetpack' ) ):
function publisher_get_jetpack( $module = '' ) {
    if ( '' == $module ) {
        return class_exists( 'Jetpack' ) ? true : false;
    } else {
        $jetpack_active_modules = get_option( 'jetpack_active_modules' );
        if ( class_exists( 'Jetpack', false ) && $jetpack_active_modules && in_array( $module, $jetpack_active_modules ) ) {
            return true;
        } else {
            return false;
        }
    }
}
endif; // publisher_get_jetpack




/*-----------------------------------------------------------------------------------*/
/*	Theme Modifier Functions
/*-----------------------------------------------------------------------------------*/

/**
 * Extend WP Head
 */
function publisher_extend_head() { ?>

	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

    <script>document.documentElement.className = document.documentElement.className.replace("no-js","js");</script>

<?php
}
add_action( 'wp_head', 'publisher_extend_head', 0 );


/**
 * Exclude pages from search
 */
function publisher_exclude_pages_from_search() {
	global $wp_post_types;
	$wp_post_types['page']->exclude_from_search = true;
}
add_action( 'init', 'publisher_exclude_pages_from_search' );


/**
 * Set default page layout
 */
function publisher_default_options_page_layout( $value ) {
	if ( is_page() && ! in_array( $value , array( 'standard', 'cover', 'wide', 'banner' ) ) ) {
		return 'wide';
	}
	return $value;
}
add_filter( 'option_publisher_options_page_layout', 'publisher_default_options_page_layout' );


/**
 * Modify the comment form default fields
 */
function publisher_comment_form_default_fields( $fields ) {
	$commenter = wp_get_current_commenter();
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );
	$class_req = ( $req ? " required" : '' );
	$label_req = ( $req ? " *" : '' );

	// Label texts
	$placeholder_author = __( 'Your Name', 'publisher' );
	$placeholder_email = __( 'Your Email', 'publisher' );
	$placeholder_url = __( 'Website', 'publisher' );


	$fields =  array(
		'author' =>
			'<p class="comment-form-author' . $class_req . '">' .
			'<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
			'" placeholder="' . $placeholder_author . $label_req . '" size="30"' . $aria_req . ' /></p>',

		'email' =>
			'<p class="comment-form-email' . $class_req . '">' .
			'<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .
			'" placeholder="' . $placeholder_email . $label_req . '" size="30"' . $aria_req . ' /></p>',

		'url' =>
			'<p class="comment-form-url">' .
			'<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) .
			'" placeholder="' . $placeholder_url . '" size="30" /></p>',
	);

	return $fields;
}
add_filter( 'comment_form_default_fields', 'publisher_comment_form_default_fields' );


/**
 * Set article footer behavior
 */
function publisher_default_page_article_footer( $article_footer ) {
	if ( is_page_template( 'template-pagebuilder.php' ) || is_page_template( 'template-pagebuilder-full.php' ) ) {
		return false;
	}
	return $article_footer;
}
add_filter( 'publisher_filter_page_article_footer', 'publisher_default_page_article_footer' );


/**
 * Add taxonomy color and descriptions to primary menu walker
 */
function publisher_walker_nav_menu_start_el( $item_output, $item, $depth, $args ) {

	// Add Descriptions as last item *in* link ($input_output ends with "</a>{$args->after}")
    if ( ! empty( $item->description ) && $args->theme_location == 'publisher-menu-primary' ) {
        $item_output = substr( $item_output, 0, -strlen( "</a>{$args->after}" ) ) . sprintf( '<span class="description">%s</span >', esc_html( $item->description) ) . "</a>{$args->after}";
    }

    // Add menu item border color
	if ( $depth == 0 ) {
		$tax_color = get_option( 'publisher_options_menus_taxonomy_color', 'show' );
		$border_color = '';

		// Add taxonomy color
		if ( $item->type == 'taxonomy' && 'hide' != $tax_color ) {
			$term_color = get_tax_meta( $item->object_id, 'publisher_tax_options_color' );
			if ( ! empty( $term_color ) ) {
				$border_color = '<span class="tax-color" style="background-color: ' . $term_color . ';"></span>';
			}
		}

		// Manually add border color
		foreach ( $item->classes as $i => $class ) {
			if ( substr( $class, 0, 6 ) == 'color-' ) {
				$hex_color = str_replace( 'color-', '#', $class );
				$border_color = '<span class="tax-color" style="background-color: ' . $hex_color . ';"></span>';
				break;
			}
		}

		// Add menu item border color
		if ( ! empty( $border_color ) ) {
			//$item_output .= $border_color;
			$item_output = substr( $item_output, 0, -strlen( "</a>{$args->after}" ) ) . $border_color . "</a>{$args->after}";
		}
	}

    return $item_output;

}
add_filter( 'walker_nav_menu_start_el', 'publisher_walker_nav_menu_start_el', 10, 5 );


/**
 * Modify li items
 */
function publisher_nav_menu_css_class( $classes, $item, $args, $depth ) {

	// Remove font awesome from li classes
	foreach ( $classes as $i => $class ) {
		if ( substr( $class, 0, 3 ) == 'fa-' ) {
			unset( $classes[$i] );
		}
	}

	return $classes;
}
add_filter( 'nav_menu_css_class', 'publisher_nav_menu_css_class', 10, 4 );


/**
 * Modify menu attributes
 */
function publisher_nav_menu_link_attributes( $atts, $item, $args, $depth ) {

	// Add font awesome icon to title class
	foreach ( $item->classes as $i => $class ) {
		if ( substr( $class, 0, 3 ) == 'fa-' ) {
			$atts['class'] = 'menu-icon ' . $class;
		}
	}

	return $atts;

}
add_filter( 'nav_menu_link_attributes', 'publisher_nav_menu_link_attributes', 10, 4 );


/**
 * Add additional items after main menu items
 */
if ( ! function_exists( 'publisher_nav_menu_items' ) ) :
function publisher_nav_menu_items( $items, $args ) {

	if ( $args->theme_location == 'publisher-menu-primary' ) {
		// Get navigation bar options
		$additional_items = get_option( 'publisher_options_menus_additional_items' );

		if ( 'hide' != $additional_items ) {
			ob_start(); // open buffer contents ?>

			<li class="menu-item additional-menu-items clearfix">
				<ul>
					<?php

						/**
						 * @hooked publisher_additional_menu_items_search - 10
						 */
						do_action( 'publisher_action_additional_menu_items' );
					?>
				</ul>
			</li>

			<?php
			$items = ob_get_clean() . $items; // Get current buffer contents and delete current output buffer
		}
	}

	return $items;

}
endif; // publisher_nav_menu_items
add_filter( 'wp_nav_menu_items', 'publisher_nav_menu_items', 10, 2 );


/**
 * Add search menu item to additional menu items
 */
if ( ! function_exists( 'publisher_additional_menu_items_search' ) ) :
function publisher_additional_menu_items_search() {
	$search_bar = get_option( 'publisher_options_menus_search' );

	if ( 'hide' != $search_bar ) {
		printf( '<li class="menu-item menu-item-search search-toggle"><a href="#" class="icon"><i class="fa fa-search"></i><i class="fa fa-times"></i></a>%s</li>',
			get_search_form( false )
		);
	}

}
endif; // publisher_nav_menu_items
add_action( 'publisher_action_additional_menu_items', 'publisher_additional_menu_items_search', 10 );


/**
 * Add CPT Metaboxes to Appearance Menus
 */
function publisher_appearance_menu_add_metabox() {

	/**
	 * Add Custom Post Archive to Appearance Menus
	 */
	add_meta_box( 'publisher-metabox-nav-menu-posttype', __( 'Custom Post Archives', 'publisher' ), 'publisher_metabox_menu_posttype_archive', 'nav-menus', 'side', 'default' );

}
add_action( 'admin_head-nav-menus.php', 'publisher_appearance_menu_add_metabox' );


/**
 * Grab all custom post type archives
 */
function publisher_metabox_menu_posttype_archive() {

	$post_types = get_post_types( array( 'show_in_nav_menus' => true, 'has_archive' => true ), 'object' );

	if ( $post_types ) :
	    $items = array();
	    $loop_index = 999999;

	    foreach ( $post_types as $post_type ) {
	        $item = new stdClass();
	        $loop_index++;

	        $item->object_id 		= $loop_index;
	        $item->db_id 			= 0;
	        $item->object 			= 'post_type_' . $post_type->query_var;
	        $item->menu_item_parent = 0;
	        $item->type 			= 'custom';
	        $item->title 			= $post_type->labels->name;
	        $item->url 				= get_post_type_archive_link( $post_type->name );
	        $item->target 			= '';
	        $item->attr_title 		= '';
	        $item->classes 			= array();
	        $item->xfn 				= '';

	        $items[] = $item;
	    }

	    $walker = new Walker_Nav_Menu_Checklist( array() );

	    echo '<div id="posttype-archive" class="posttypediv">';
	    echo '<div id="tabs-panel-posttype-archive" class="tabs-panel tabs-panel-active">';
	    echo '<ul id="posttype-archive-checklist" class="categorychecklist form-no-clear">';
	    echo walk_nav_menu_tree( array_map('wp_setup_nav_menu_item', $items ), 0, ( object ) array( 'walker' => $walker ) );
	    echo '</ul>';
	    echo '</div>';
	    echo '</div>';

	    echo '<p class="button-controls">';
	    echo '<span class="add-to-menu">';
	    echo '<input type="submit"' . disabled( 1, 0 ) . ' class="button-secondary submit-add-to-menu right" value="' . __( 'Add to Menu', 'publisher' ) . '" name="add-posttype-archive-menu-item" id="submit-posttype-archive" />';
	    echo '<span class="spinner"></span>';
	    echo '</span>';
	    echo '</p>';

	endif;
}


/**
 * Hide selected tags from showing in main listings
 */
function publisher_get_the_terms( $terms ) {
    // This filter is only appropriate on the front-end.
    if ( is_admin() ) {
        return $terms;
    }

    // list the unwanted terms ids
	$hide_terms_options = get_theme_mod( 'publisher_options_hide_terms' );
	$blacklist_terms = $hide_terms_options ? $hide_terms_options : array();

	// loop through the terms and unset them
	foreach( $terms as $order => $term ) {
		//only remove term if it's ID is in the array
		if ( in_array( $term->term_id, $blacklist_terms ) ) {
			unset( $terms[$order] );
		}
	}

	return $terms;
}
add_filter( 'get_the_terms', 'publisher_get_the_terms', 999 );


/**
 * Remove first gallery shortcode of relevant posts
 */
function publisher_strip_shortcode_first_gallery( $content ) {
	global $page;

	$featured_preview = get_post_meta( get_the_ID(), 'publisher_featured_preview_meta', true );

	/**
	 * If is a gallery AND doesn't have a featured preview set,
	 * remove the first gallery shortcode
	 */
	if ( ( has_post_format( 'gallery' ) && ! $featured_preview ) && 1 == $page ) {
		$pattern = get_shortcode_regex();
		preg_match_all( '/'. $pattern .'/s', $content, $matches, PREG_SET_ORDER );
		if ( ! empty( $matches ) ) {
			foreach ( $matches as $shortcode ) {
				if ( 'gallery' === $shortcode[2] ) {
					$pos = strpos( $content, $shortcode[0] );
					if ( $pos !== false )
						return substr_replace( $content, '', $pos, strlen($shortcode[0]) );
				}
			}
		}
	}
	return $content;
}
add_filter( 'the_content', 'publisher_strip_shortcode_first_gallery' );




/*-----------------------------------------------------------------------------------*/
/*	Template Modifier Functions
/*-----------------------------------------------------------------------------------*/

/**
 * Modify the excerpt length
 */
function publisher_excerpt_length( $length = '' ) {
	return ( '' != get_option( 'publisher_options_excerpt_length' ) ) ? intval( get_option( 'publisher_options_excerpt_length' ) ) : 35;
}
add_filter( 'excerpt_length', 'publisher_excerpt_length', 999 );


/**
 * Modify the read more link
 */
function publisher_the_content_more_link( $more_link, $more_link_text ) {

	$read_more_text = publisher_cpt_options() ? get_option( 'publisher_options_' . get_post_type() . '_read_more' ) : get_option( 'publisher_options_posts_read_more' );
	$read_more = $read_more_text ? $read_more_text : __( 'Read More', 'publisher' );

	return str_replace( $more_link_text, $read_more, $more_link );

}
add_filter( 'the_content_more_link', 'publisher_the_content_more_link', 10, 2 );


/**
 * Modify the excerpt read more link
 */
function publisher_excerpt_more( $more ) {
	global $post;

	$read_more_text = publisher_cpt_options() ? get_option( 'publisher_options_' . get_post_type() . '_read_more' ) : get_option( 'publisher_options_posts_read_more' );

	if ( '' != $read_more_text ) {
		$read_more = '&hellip;<span class="moretag"><a class="more-link" href="' . esc_url( get_permalink( $post->ID ) ) . '">' . $read_more_text . '</a></span>';
	} else {
		$read_more = '&hellip;';
	}

    return $read_more;
}
add_filter( 'excerpt_more', 'publisher_excerpt_more' );


/**
 * Modify the wp link pages args
 */
function publisher_wp_link_pages_args( $args ) {

	if ( $args['next_or_number'] == 'next_and_number' ) {

		global $page, $numpages, $multipage, $more, $pagenow;

		$args['before'] = '<p class="page-links next-and-number">';
		$args['after'] = '</p>';
		$args['next_or_number'] = 'number';
		if ( is_rtl() ) {
			$args['nextpagelink'] = '<span class="next-link button">&larr;</span>';
			$args['previouspagelink'] = '<span class="prev-link button">&rarr;</span>';
		} else {
			$args['nextpagelink'] = '<span class="next-link button">&rarr;</span>';
			$args['previouspagelink'] = '<span class="prev-link button">&larr;</span>';
		}
		$args['pagelink'] = '<span class="button">%</span>';

		$prev = '';
		$next = '';
		if ( $multipage ) {
			if ( $more ) {
				$i = $page - 1;
				if ( $i && $more ) {
					$prev .= _wp_link_page( $i );
					$prev .= $args['link_before']. $args['previouspagelink'] . $args['link_after'] . '</a>';
				}
				$i = $page + 1;
				if ( $i <= $numpages && $more ) {
					$next .= _wp_link_page( $i );
					$next .= $args['link_before']. $args['nextpagelink'] . $args['link_after'] . '</a>';
				}
			}
		}
		$args['before'] = $args['before'] . $prev;
		$args['after'] = $next . $args['after'];

	} elseif ( $args['next_or_number'] == 'next_total_pages' ) {
		global $page, $numpages, $multipage, $more, $pagenow;

		$args['before'] = '<p class="page-links next-total-pages">';
		$args['after'] = '</p>';
		$args['next_or_number'] = 'next';
		if ( is_rtl() ) {
			$args['nextpagelink'] = '<span class="next-link button">&larr;</span>';
			$args['previouspagelink'] = '<span class="prev-link button">&rarr;</span>';
		} else {
			$args['nextpagelink'] = '<span class="next-link button">&rarr;</span>';
			$args['previouspagelink'] = '<span class="prev-link button">&larr;</span>';
		}

		if ( $multipage ) {
			$total_pages_display = sprintf( '<span class="total-pages">' . __( 'Page %1$s of %2$s', 'publisher' ) . '</span>', $page, $numpages );

			if ( $page == 1 ) {
				$args['before'] = $args['before'] . $total_pages_display;
			} elseif ( $page == $numpages ) {
				$args['after'] = $total_pages_display . $args['after'];
			} else {
				$args['separator'] .= $total_pages_display;
			}
		}
	}

	return $args;

}
add_filter( 'wp_link_pages_args', 'publisher_wp_link_pages_args' );


/**
 * Show full size image on attachment pages
 */
function publisher_prepend_attachment( $p ) {
	$post = get_post();

	if ( 0 === strpos( $post->post_mime_type, 'video' ) ) {

	} elseif ( 0 === strpos( $post->post_mime_type, 'audio' ) ) {

	} else {
		$p = '<p class="attachment">';
		// show the full sized image representation of the attachment if available, and link to the raw file
		$p .= wp_get_attachment_link( 0, 'full', false );
		$p .= '</p>';
	}

	// Show excerpt
	if ( $post->post_excerpt ) {
		$p .= sprintf( '<p class="wp-caption-text">%s</p>', $post->post_excerpt );
	}

	return $p;
}
add_filter( 'prepend_attachment', 'publisher_prepend_attachment' );


/**
 * Add social media to author contact methods
 */
function publisher_user_contactmethods( $contactmethods ) {
	$contactmethods['twitter'] = __( 'Twitter', 'publisher' );
	$contactmethods['facebook'] = __( 'Facebook', 'publisher' );
	$contactmethods['instagram'] = __( 'Instagram', 'publisher' );
	$contactmethods['tumblr'] = __( 'Tumblr', 'publisher' );
    $contactmethods['dribbble'] = __( 'Dribbble', 'publisher' );
    $contactmethods['flickr'] = __( 'Flickr', 'publisher' );
    $contactmethods['pinterest'] = __( 'Pinterest', 'publisher' );
    $contactmethods['googleplus'] = __( 'Google+', 'publisher' );
    $contactmethods['vimeo'] = __( 'Vimeo', 'publisher' );
    $contactmethods['youtube'] = __( 'YouTube', 'publisher' );
    $contactmethods['soundcloud'] = __( 'Soundcloud', 'publisher' );
    $contactmethods['linkedin'] = __( 'LinkedIn', 'publisher' );
    $contactmethods['github'] = __( 'GitHub', 'publisher' );
    $contactmethods['rss'] = __( 'RSS', 'publisher' );
    $contactmethods['public_email'] = __( 'Public Email', 'publisher' );
    return $contactmethods;
}
add_filter( 'user_contactmethods', 'publisher_user_contactmethods', 10, 1 );


/**
 * Add custom fields to author profile
 */
function publisher_user_add_custom_fields( $user ) {

	if ( is_admin() ) { ?>

		<h3><?php _e( 'Profile Images', 'publisher' ); ?></h3>

		<table class="form-table profile-images">
			<tbody>

				<?php
					$custom_fields_metas = array( 'author_card_image', 'author_archive_image' );

					foreach( $custom_fields_metas as $custom_field_meta ) {

						// Title
						switch( $custom_field_meta ) {
							case 'author_card_image' :
								$title = __( 'Author Card', 'publisher' );
								$description = __( 'Image for the author card. If none, will use the Gravatar image instead.', 'publisher' );
								break;
							case 'author_archive_image' :
								$title = __( 'Author Archive', 'publisher' );
								$description = __( 'Image for the author archive featured header. If none, will use the default featured header image instead.', 'publisher' );
								break;
						};

						// Meta
						$meta = esc_attr( get_the_author_meta( $custom_field_meta, $user->ID ) );

						if ( ! empty( $meta ) ) {
							$thumb_id 		= attachment_url_to_postid( $meta );
							$attachment 	= wp_get_attachment_image_src( $thumb_id, 'thumbnail' );
							$image 			= $attachment[0];
							$remove_styles 	= 'display: inline-block;';
						} else {
							$image 			= '';
							$remove_styles 	= 'display: none;';
						}; ?>

						<tr class="form-field">
							<th>
								<label for="image"><?php echo esc_attr( $title ); ?></label>
							</th>

							<td>
								<img class="user-preview-image" src="<?php echo esc_url( $image ); ?>" style="width: 60px; height: 60px;">

								<input type="text" name="<?php echo esc_attr( $custom_field_meta ); ?>" id="<?php echo esc_attr( $custom_field_meta ); ?>" value="<?php echo esc_attr( $meta ) ?>" style="display: none;" />
								<button class="button button-upload" style="margin-right: 3px;"><?php _e( 'Upload/Add Image', 'publisher' ); ?></button>
								<button class="button button-remove" style="<?php echo esc_attr( $remove_styles ); ?>"><?php _e( 'Remove Image', 'publisher' ); ?></button>

								<p class="description"><?php echo esc_attr( $description ); ?></p>
							</td>
						</tr>

					<?php
					}
				?>

			</tbody>
		</table>

	<?php
	}
}
add_action( 'show_user_profile', 'publisher_user_add_custom_fields' );
add_action( 'edit_user_profile', 'publisher_user_add_custom_fields' );


/**
 * Add image upload to author contact methods
 */
function publisher_user_add_custom_fields_save( $user_id ) {

	if ( ! current_user_can( 'edit_user', $user_id ) ) {
		return false;
	}

	// Author Card Image
	update_user_meta( $user_id, 'author_card_image', $_POST[ 'author_card_image' ] );

	// Author Archive Image
	update_user_meta( $user_id, 'author_archive_image', $_POST[ 'author_archive_image' ] );

}
add_action( 'personal_options_update', 'publisher_user_add_custom_fields_save' );
add_action( 'edit_user_profile_update', 'publisher_user_add_custom_fields_save' );


/**
 * Add Next Page Button to the text editor
 */
function publisher_mce_buttons( $buttons ) {
    // Insert the new item without overwriting an existing button
    array_splice( $buttons, 15, 0, 'wp_page' );
    return $buttons;
}
add_filter( 'mce_buttons', 'publisher_mce_buttons' );


/**
 * Add editor style select button to the text editor
 */
function publisher_mce_buttons_2( $buttons ) {
  	array_unshift( $buttons, 'styleselect' );
  	return $buttons;
}
add_filter( 'mce_buttons_2', 'publisher_mce_buttons_2' );


/**
 * Add editor style options to the text editor
 */
function publisher_tiny_mce_before_init( $settings ){
    $settings['theme_advanced_blockformats'] = 'p,a,div,span,h1,h2,h3,h4,h5,h6,tr,';

    $style_formats = array(
        array(
            'title' 	=> 'Dropcap',
            'inline'   	=> 'span',
            'classes' 	=> 'dropcap'
        ),
        array(
            'title' 	=> 'Introduction',
            'block'   	=> 'p',
            'classes' 	=> 'lead'
        ),
        array(
            'title' 	=> 'Pull Right',
            'inline'   	=> 'span',
            'classes' 	=> 'pull-right'
        ),
        array(
            'title' 	=> 'Pull Left',
            'inline'   	=> 'span',
            'classes' 	=> 'pull-left'
        ),
        array(
            'title' 	=> 'Highlight',
            'inline'   	=> 'mark'
        ),
        array(
            'title' 	=> 'Alert',
            'block'   	=> 'p',
            'classes'	=> 'alert primary'
        ),
        array(
            'title' 	=> 'Success',
            'block'   	=> 'p',
            'classes'	=> 'alert success'
        ),
        array(
            'title' 	=> 'Info',
            'block'   	=> 'p',
            'classes'	=> 'alert info'
        ),
        array(
            'title' 	=> 'Warning',
            'block'   	=> 'p',
            'classes'	=> 'alert warning'
        ),
        array(
            'title' 	=> 'Danger',
            'block'   	=> 'p',
            'classes'	=> 'alert danger'
        ),
        array(
            'title' 	=> 'Cite',
            'inline'   	=> 'cite'
        ),
        array(
            'title' 	=> 'Code',
            'inline'   	=> 'code'
        ),
        array(
            'title' 	=> 'Keyboard',
            'inline'   	=> 'kbd'
        ),
    );

    $settings['style_formats'] = json_encode( $style_formats );
    return $settings;
}
add_filter( 'tiny_mce_before_init', 'publisher_tiny_mce_before_init' );


/**
 * Register taxonomies for attachments
 */
function publisher_extend_init() {

	// Add Taxonomy to Attachments
	register_taxonomy_for_object_type( 'category', 'attachment' );
	register_taxonomy_for_object_type( 'post_tag', 'attachment' );

}
add_action( 'init' , 'publisher_extend_init' );


/**
 * Flush out transients
 */
function publisher_category_transient_flusher() {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    // Like, beat it. Dig?
    delete_transient( 'publisher_categories' );
}
add_action( 'edit_category', 'publisher_category_transient_flusher' );
add_action( 'save_post', 'publisher_category_transient_flusher' );


/**
 * Add shortcodes to Text Widgets
 */
add_filter( 'widget_text', 'do_shortcode' );




/*-----------------------------------------------------------------------------------*/
/*	Helper Functions
/*-----------------------------------------------------------------------------------*/

/**
 * Adjust Percent Colors
 */
if ( ! function_exists( 'publisher_color_adjust' ) ) :
function publisher_color_adjust( $color_code, $modifier = 0, $method = '', $return = '' ) {

	// Convert Color Code to RGB
	if ( is_array( $color_code ) ) {
		// Set RGB variables
        $var_R = $color_code[0];
        $var_G = $color_code[1];
        $var_B = $color_code[2];
    } elseif ( preg_match( "/#/", $color_code ) ) {
        // Convert HEX to RGB variables
		$hex = str_replace( "#","", $color_code );
		$var_R = ( strlen( $hex ) == 3 ) ? hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) ) : hexdec( substr( $hex, 0, 2 ) );
		$var_G = ( strlen( $hex ) == 3 ) ? hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) ) : hexdec( substr( $hex, 2, 2 ) );
		$var_B = ( strlen( $hex ) == 3 ) ? hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) ) : hexdec( substr( $hex, 4, 2 ) );
    } else {
	    return;
    }

	// Convert the RGB values to percentages
	$perc_R = ( $var_R / 255 );
	$perc_G = ( $var_G / 255 );
	$perc_B = ( $var_B / 255 );

	// Calculate the maximum value of R,G,B
	// the minimum value, and the difference of the two (chroma)
	$maxRGB = max( $perc_R, $perc_G, $perc_B );
	$minRGB = min( $perc_R, $perc_G, $perc_B );
	$chroma = $maxRGB - $minRGB;


	// Calculate Hue, Saturation, Luminance (Brightness)
	if ( $chroma == 0 ) {

		// If hueless (equal parts RGB make black, white, or grays),
		// set Hue and Saturation to 0
		$H = 0;
		$S = 0;

	} else {

		// Calculate Hue component on the "chromacity plane", which is represented
		// as a 2D hexagon, divided into six 60-degree sectors. We calculate
		// the bisecting angle as a value 0 <= x < 6, that represents which
		// portion of which sector the line falls on.
		$del_R = ( ( ( $maxRGB - $perc_R ) / 6 ) + ( $chroma / 2 ) ) / $chroma;
		$del_G = ( ( ( $maxRGB - $perc_G ) / 6 ) + ( $chroma / 2 ) ) / $chroma;
		$del_B = ( ( ( $maxRGB - $perc_B ) / 6 ) + ( $chroma / 2 ) ) / $chroma;

		if ( $perc_R == $maxRGB )
			$H = $del_B - $del_G;
		elseif ( $perc_G == $maxRGB )
			$H = ( 1 / 3 ) + $del_R - $del_B;
		elseif ( $perc_B == $maxRGB )
			$H = ( 2 / 3 ) + $del_G - $del_R;

		if ( $H < 0 ) $H++;
		if ( $H > 1 ) $H--;

		// Saturation is chroma divided by Value (or Brightness)
		$S = ( $chroma / $maxRGB );

	}

	// Set Luminance (Brightness)
	$L = $maxRGB;

	// Calcuate method
	if ( is_array( $modifier ) ) {
		$modifier = wp_parse_args( $modifier, array(
			'h' => 0,
			's' => 0,
			'l' => 0,
		) );

		$H = max( 0, min( 1, $H + ( $modifier['h'] / 100 ) ) );
		$S = max( 0, min( 1, $S + ( $modifier['s'] / 100 ) ) );
		$L = max( 0, min( 1, $L + ( $modifier['l'] / 100 ) ) );

	} elseif ( 'saturation' == $method ) {
		if ( $S == 0 ) {
			// If hueless, calculate luminance instead
			$L = max( 0, min( 1, $L - ( $modifier / 100 ) ) );
		} else {
			// Calculate Saturation with percent
			$S = max( 0, min( 1, $S + ( $modifier / 100 ) ) );
		}
	} else {
		$L = max( 0, min( 1, $L + ( $modifier / 100 ) ) );
	}

	// Convert HSL back to RGB
	if ( $S == 0 ) {

		$R = $G = $B = ( $L * 255 );

	} else {

		$var_H = $H * 6;
		$var_i = floor( $var_H );
		$var_1 = $L * ( 1 - $S );
		$var_2 = $L * ( 1 - $S * ( $var_H - $var_i ) );
		$var_3 = $L * ( 1 - $S * ( 1 - ( $var_H - $var_i ) ) );

		if ( $var_i == 0 ) {
			$perc_R = $L;
			$perc_G = $var_3;
			$perc_B = $var_1;
		} elseif ( $var_i == 1 ) {
			$perc_R = $var_2;
			$perc_G = $L;
			$perc_B = $var_1 ;
		} elseif ( $var_i == 2 ) {
			$perc_R = $var_1;
			$perc_G = $L;
			$perc_B = $var_3;
		} elseif ( $var_i == 3 ) {
			$perc_R = $var_1;
			$perc_G = $var_2;
			$perc_B = $L;
		} elseif ( $var_i == 4 ) {
			$perc_R = $var_3;
			$perc_G = $var_1;
			$perc_B = $L;
		} else {
			$perc_R = $L;
			$perc_G = $var_1;
			$perc_B = $var_2;
		}

		// Convert the RGB precent values
		$R = $perc_R * 255;
		$G = $perc_G * 255;
		$B = $perc_B * 255;

	}

	// Return color code type
	if ( 'rgb' == $return ) {
		return array(
			"r" => round( max( 0, min( 255, $R ) ) ),
			"g" => round( max( 0, min( 255, $G ) ) ),
			"b" => round( max( 0, min( 255, $B ) ) )
		);
	} else {
		return "#"
			. str_pad( dechex( max( 0, min( 255, $R ) ) ), 2, "0", STR_PAD_LEFT )
			. str_pad( dechex( max( 0, min( 255, $G ) ) ), 2, "0", STR_PAD_LEFT )
			. str_pad( dechex( max( 0, min( 255, $B ) ) ), 2, "0", STR_PAD_LEFT );
	}

}
endif; // publisher_color_adjust


/**
 * Calculate shade difference
 */
if ( ! function_exists( 'publisher_calculate_color' ) ) :
function publisher_calculate_color( $cwith, $ccolor ) {
	$hex = str_replace( "#","", $cwith );
	$r = ( strlen( $hex ) == 3 ) ? hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) ) : hexdec( substr( $hex, 0, 2 ) );
	$g = ( strlen( $hex ) == 3 ) ? hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) ) : hexdec( substr( $hex, 2, 2 ) );
	$b = ( strlen( $hex ) == 3 ) ? hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) ) : hexdec( substr( $hex, 4, 2 ) );

	$hex2 = str_replace( "#","", $ccolor );
	$r2 = ( strlen( $hex2 ) == 3 ) ? hexdec( substr( $hex2, 0, 1 ) . substr( $hex2, 0, 1 ) ) : hexdec( substr( $hex2, 0, 2 ) );
	$g2 = ( strlen( $hex2 ) == 3 ) ? hexdec( substr( $hex2, 1, 1 ) . substr( $hex2, 1, 1 ) ) : hexdec( substr( $hex2, 2, 2 ) );
	$b2 = ( strlen( $hex2 ) == 3 ) ? hexdec( substr( $hex2, 2, 1 ) . substr( $hex2, 2, 1 ) ) : hexdec( substr( $hex2, 4, 2 ) );

	$p1 = ($r / 255) * 100;
	$p2 = ($g / 255) * 100;
	$p3 = ($b / 255) * 100;

	$perc1 = round(($p1 + $p2 + $p3) / 3);

	$p1 = ($r2 / 255) * 100;
	$p2 = ($g2 / 255) * 100;
	$p3 = ($b2 / 255) * 100;

	$perc2 = round(($p1 + $p2 + $p3) / 3);

	return $perc1 - $perc2;
}
endif; // publisher_calculate_color


/**
 * Rounding numbers
 */
if ( ! function_exists( 'publisher_number_prefixes' ) ) :
function publisher_number_prefixes( $number ) {
	if ( $number > 1000000000 )
		return round( ( $number / 1000000000 ), 1 ) . 'B';
	elseif ( $number > 1000000 )
		return round( ( $number / 1000000 ), 1 ) . 'M';
	elseif ( $number > 1000 )
		return round( ( $number / 1000 ), 1 ) . 'k';
	else
		return number_format( intval( $number ) );
}
endif;
