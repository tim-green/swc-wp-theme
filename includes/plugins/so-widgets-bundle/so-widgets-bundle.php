<?php
/**
 * Site Origin Widgets Bundle Compatibility File
 *
 * @package Publisher
 */

/*-----------------------------------------------------------------------------------*/
/*	Setup Site Origin Widgets Bundle Defaults
/*-----------------------------------------------------------------------------------*/

/**
 * Modify Site Origin Widgets
 */
require( get_template_directory() . '/includes/plugins/so-widgets-bundle/widgets/so-button-widget/so-button-widget.php' );
require( get_template_directory() . '/includes/plugins/so-widgets-bundle/widgets/so-cta-widget/so-cta-widget.php' );
require( get_template_directory() . '/includes/plugins/so-widgets-bundle/widgets/so-editor-widget/so-editor-widget.php' );
require( get_template_directory() . '/includes/plugins/so-widgets-bundle/widgets/so-features-widget/so-features-widget.php' );
require( get_template_directory() . '/includes/plugins/so-widgets-bundle/widgets/so-headline-widget/so-headline-widget.php' );
require( get_template_directory() . '/includes/plugins/so-widgets-bundle/widgets/so-image-widget/so-image-widget.php' );
require( get_template_directory() . '/includes/plugins/so-widgets-bundle/widgets/so-price-table-widget/so-price-table-widget.php' );
require( get_template_directory() . '/includes/plugins/so-widgets-bundle/widgets/so-social-media-buttons-widget/so-social-media-buttons-widget.php' );




/*-----------------------------------------------------------------------------------*/
/*	Add Theme Widgets
/*-----------------------------------------------------------------------------------*/

/**
 * Register Theme Widgets Folder
 */
function publisher_siteorigin_widgets_widget_folders( $folders ) {
	$folders[] = get_template_directory() . '/includes/plugins/so-widgets-bundle/publisher-widgets/';
	return $folders;
}
add_filter( 'siteorigin_widgets_widget_folders', 'publisher_siteorigin_widgets_widget_folders' );


/**
 * Add Theme Widgets Tabs
 */
function publisher_siteorigin_panels_widget_dialog_tabs( $tabs ) {
    $tabs[] = array(
        'title' => __( 'Publisher Widgets', 'publisher' ),
        'filter' => array(
            'groups' => array( 'publisher' )
        )
    );

    return $tabs;
}
add_filter( 'siteorigin_panels_widget_dialog_tabs', 'publisher_siteorigin_panels_widget_dialog_tabs', 20 );


/**
 * Add Theme Widgets Banner Images
 */
function publisher_siteorigin_widgets_widget_banner( $banner, $widget ) {

	if ( strpos( $widget['ID'], 'publisher' ) !== false ) {
		$banner = get_stylesheet_directory_uri() . '/includes/plugins/so-widgets-bundle/publisher-widgets/' . $widget['ID'] . '/assets/banner.gif';
	}

	return $banner;

}
add_filter( 'siteorigin_widgets_widget_banner', 'publisher_siteorigin_widgets_widget_banner', 10, 2 );




/*-----------------------------------------------------------------------------------*/
/*	Posts Loop Filters
/*-----------------------------------------------------------------------------------*/

/**
 * Exclude the following post types
 */
function publisher_theme_filter_posts_loop_exclude_post_type() {
	return array( 'ditty_news_ticker', 'forum', 'topic', 'reply', 'ml-slider', 'attachment', 'page' );
}
add_filter( 'publisher_ext_filter_posts_loop_exclude_post_type', 'publisher_theme_filter_posts_loop_exclude_post_type' );


/**
 * Exclude the following taxonomies
 */
function publisher_theme_filter_posts_loop_exclude_taxonomy() {
	return array( 'pa_color' );
}
add_filter( 'publisher_ext_filter_posts_loop_exclude_taxonomy', 'publisher_theme_filter_posts_loop_exclude_taxonomy' );




/*-----------------------------------------------------------------------------------*/
/*	Template Tags
/*-----------------------------------------------------------------------------------*/

/**
 * Build Posts Query
 */
function publisher_build_posts_query( $posts_query ) {

	/**
	 * Build Advanced Posts Query Args
	 */
	foreach( $posts_query as $i => $basic_query ) :

		if ( $i == 0 ) :

			$main_args = publisher_build_posts_query_args( $basic_query );

		else :

			$secondary_args = publisher_build_posts_query_args( $basic_query );
			$secondary_query = new WP_Query( $secondary_args );
			$post_not_in = array();

			if ( $secondary_query->have_posts() ) :
				while ( $secondary_query->have_posts() ) : $secondary_query->the_post();
					if ( array_key_exists( 'post__not_in', $main_args ) ) {
						array_push( $main_args['post__not_in'], get_the_id() );
					} else {
						$main_args['post__not_in'][] = get_the_id();
					}
				endwhile;
				wp_reset_postdata();
			endif;

		endif;

	endforeach;

	// Compute difference between Post In and Post Not In if exists
	if ( array_key_exists( 'post__in', $main_args ) && array_key_exists( 'post__not_in', $main_args ) ) {
		$new_post_in_args = array_values( array_diff( $main_args['post__in'], $main_args['post__not_in'] ) );
		$new_post_out_args = array_values( array_diff( $main_args['post__not_in'], $main_args['post__in'] ) );

		// Use Post In if post in is not empty
		if ( ( ! empty( $new_post_in_args) && ! empty( $new_post_out_args ) || ( ! empty( $new_post_in_args) && empty( $new_post_out_args ) ) ) ) {
			$main_args['post__in'] = $new_post_in_args;
			unset( $main_args['post__not_in'] );
		} elseif ( empty( $new_post_in_args) && ! empty( $new_post_out_args ) ) {
			unset( $main_args['post__in'] );
			$main_args['post__not_in'] = $new_post_out_args;
		}
	}

	/**
	 * Return the posts query
	 */
	return $main_args;

}


/**
 * Build Posts Query Args
 */
function publisher_build_posts_query_args( $basic_query ) {

	// Default Args
	$args = apply_filters( 'publisher_ext_filter_build_posts_post_status', array(
		'post_status' => 'publish',
	) );

	// Variables
	$post_types = $basic_query['post_type'];
	$taxonomies = $basic_query['taxonomies'];
	$users = $basic_query['users'];
	$order = $basic_query['order'];
	$orderby = $basic_query['orderby'];
	$sticky = $basic_query['sticky'];
	$posts_per_page = $basic_query['posts_per_page'];
	$exclude_id = $basic_query['exclude_id'];

	// Post Type
	$args['post_type'] = ! empty( $post_types ) ? $post_types : 'post';

	// Taxonomies
	$tax_queries = array();
	foreach( $taxonomies as $taxonomy => $tax_value ) {

		// Taxonomy Terms
		foreach( $tax_value as $tax_term_key => $tax_term_id ) {

			// Set taxonomy operator
			if ( 'operator' == $tax_term_key ) {
				$operator = $tax_term_id;
				continue;
			}

			// Set taxonomy terms
			if ( 'terms' == $tax_term_key && ! empty( $tax_term_id ) ) {
				$args['tax_query'][$taxonomy]['terms'] = $tax_term_id;
			}
		}

		// Create Tax Query
		if ( ! empty( $args['tax_query'][$taxonomy]['terms'] ) ) {
			$args['tax_query'][$taxonomy]['taxonomy'] = $taxonomy;
			$args['tax_query'][$taxonomy]['field'] = 'term_id';
			$args['tax_query'][$taxonomy]['operator'] = $operator;
		}

	}

	// Taxonomy Relation
	if ( count( $args['tax_query'] ) > 1 ) {
		$args['tax_query']['relation'] = 'AND';
	};

	// Users
	if ( ! empty( $users ) ) {
		$args['author'] = ! is_array( $users ) ? $users : implode( ',', $users );
	}

	// Order
	if ( ! empty( $order ) && 'ASC' == $order ) {
		$args['order'] = $order;
	}

	// Order By
	if ( ! empty( $orderby ) ) {

		// Wordpress Popular Posts Plugin
		if ( class_exists('Publisher_Popular_Posts_Data') && in_array( $orderby, array( 'views', 'avg', 'comments' ) ) ) {

			// Build Popular Posts Args
			$popular_posts_args = array();
			$time_range = $basic_query['time_range'];
			$time_published = $basic_query['time_published'];

			// Post Type
			if ( ! empty( $post_types ) ) {
				$popular_posts_args['post_type'] = is_array( $post_types ) ? 'post_type="' . implode( ',', $post_types ) . '"' : 'post_type="' . $post_types . '"';
			}

			// Order By
			if ( ! empty( $orderby ) ) {
				if ( 'comment_count' == $orderby ) $orderby = 'comments';
				$popular_posts_args['order_by'] = 'order_by="' . $orderby . '"';
			}

			// Time Range
			if ( ! empty( $time_range ) ) {
				$popular_posts_args['time_range'] = 'range="' . $time_range . '"';
			}

			// Time Published
			if ( ! empty( $time_published ) ) {
				$popular_posts_args['time_published'] = 'freshness=1';
			}

			// Limits
			$popular_posts_args['limit'] = 'limit=-1"';

			// Get Posts
			$popular_posts_data = new Publisher_Popular_Posts_Data( $popular_posts_args );
			$get_popular_posts = $popular_posts_data->get_posts();

			foreach( $get_popular_posts as $popular_post ) {
				if ( array_key_exists( 'post__in', $args ) ) {
					array_push( $args['post__in'], $popular_post->id );
				} else {
					$args['post__in'][] = $popular_post->id;
				}
			}

			// Order
			if ( ! empty( $order ) && 'ASC' == $order ) {
				$args['post__in'] = array_reverse( $args['post__in'] );
			}

			// Order by post in
			if ( ! empty( $args['post__in'] ) ) {
				// Order by Post In order
				$args['orderby'] = 'post__in';
			}

		} elseif ( ! in_array( $orderby, array( 'date', 'title', 'name', 'comment_count', 'rand' ) ) ) {
			$args['orderby'] = 'meta_value_num';
			$args['meta_key'] = $orderby;
		} else {
			$args['orderby'] = $orderby;
		}

	}

	// Sticky Posts
	if ( ! empty( $sticky ) ) {
		$sticky_posts = get_option( 'sticky_posts' );

		switch ( $sticky ) {
			case 'ignore' :
				$args['ignore_sticky_posts'] = true;
				break;
			case 'include' :
				if ( array_key_exists( 'post__in', $args ) ) {
					array_push( $args['post__in'], $sticky_posts );
				} else {
					$args['post__in'] = $sticky_posts;
				}
				break;
			case 'exclude' :
				$args['post__not_in'] = $sticky_posts;
				$args['ignore_sticky_posts'] = true;
				break;
			default :
				break;
		}
	}

	// Posts Per Page
	$args['posts_per_page'] = ! empty( $posts_per_page ) ? intval( $posts_per_page ) : get_option( 'posts_per_page' );

	// Exclude IDs
	if ( ! empty( $exclude_id ) ) {

		$post_not_in_ids = explode( ",", $exclude_id );

		// Build Post Not In Args
		foreach ( $post_not_in_ids as $post_not_in_id ) {
			if ( array_key_exists( 'post__not_in', $args ) ) {
				array_push( $args['post__not_in'], intval( $post_not_in_id ) );
			} else {
				$args['post__not_in'][] = intval( $post_not_in_id );
			}
		}

	}

	// Compute difference between Post In and Post Not In if exists
	if ( array_key_exists( 'post__in', $args ) && array_key_exists( 'post__not_in', $args ) ) {
		$new_post_in_args = array_values( array_diff( $args['post__in'], $args['post__not_in'] ) );
		$new_post_out_args = array_values( array_diff( $args['post__not_in'], $args['post__in'] ) );

		// Use Post In if post in is not empty
		if ( ( ! empty( $new_post_in_args) && ! empty( $new_post_out_args ) || ( ! empty( $new_post_in_args) && empty( $new_post_out_args ) ) ) ) {
			$args['post__in'] = $new_post_in_args;
			unset( $args['post__not_in'] );
		} elseif ( empty( $new_post_in_args) && ! empty( $new_post_out_args ) ) {
			unset( $args['post__in'] );
			$args['post__not_in'] = $new_post_out_args;
		}
	}

	/**
	 * Return the posts query args
	 */
	return $args;

}




/*-----------------------------------------------------------------------------------*/
/*	Setup Pagebuilder Defaults
/*-----------------------------------------------------------------------------------*/

/**
 * Set default settings
 */
function publisher_siteorigin_panels_settings_defaults( $settings_defaults ) {
	$settings_defaults['mobile-width'] = 782;
	$settings_defaults['margin-bottom'] = 52;
	$settings_defaults['margin-sides'] = 26;
	return $settings_defaults;
}
add_filter( 'siteorigin_panels_settings_defaults', 'publisher_siteorigin_panels_settings_defaults' );


/**
 * Add Row Options to Pagebuilder
 */
function publisher_siteorigin_panels_row_style_fields( $fields ) {

	$fields['publisher_sidebar_class'] = array(
		'name' => __( 'Add Sidebar Classes', 'publisher' ),
		'type' => 'checkbox',
		'group' => 'attributes',
		'description' => __( 'Adds the sidebar classes to this row.', 'publisher' ),
		'priority' => 15,
	);

	$fields['featured-row'] = array(
		'name'        => __( 'First Row', 'publisher' ),
		'type'        => 'checkbox',
		'group'       => 'layout',
		'description' => __( 'Removes the gap under the menu.', 'publisher' ),
		'priority'    => 14,
	);

	$fields['last-row'] = array(
		'name'        => __( 'Last Row', 'publisher' ),
		'type'        => 'checkbox',
		'group'       => 'layout',
		'description' => __( 'Aligns the bottom row to the footer.', 'publisher' ),
		'priority'    => 18,
	);


	return $fields;
}
add_filter( 'siteorigin_panels_row_style_fields', 'publisher_siteorigin_panels_row_style_fields' );


/**
 * Add Settings to Rows
 */
function publisher_siteorigin_panels_row_style_attributes( $attributes, $args ) {

	if ( ! empty( $args['publisher_sidebar_class'] ) ) {
		array_push( $attributes['class'], 'widget-area' );
		array_push( $attributes['class'], 'sidebar-widgets' );
	}

    if ( ! empty( $args['featured-row'] ) ) {
        array_push( $attributes['class'], 'publisher-ext-row-featured' );
    }

    if ( ! empty( $args['last-row'] ) ) {
        array_push( $attributes['class'], 'publisher-ext-row-last' );
    }

    return $attributes;
}
add_filter( 'siteorigin_panels_row_style_attributes', 'publisher_siteorigin_panels_row_style_attributes', 10, 2 );


/**
 * Add Cell Options to Pagebuilder
 */
function publisher_siteorigin_panels_widget_style_fields( $fields ) {

	$fields['publisher_sidebar_class'] = array(
		'name' => __( 'Add Sidebar Classes', 'publisher' ),
		'type' => 'checkbox',
		'group' => 'attributes',
		'description' => __( 'Adds the sidebar classes to this widget.', 'publisher' ),
		'priority' => 15,
	);

	$fields['publisher_margin_bottom'] = array(
		'name' => __( 'Bottom Margin', 'publisher' ),
		'type' => 'measurement',
		'group' => 'layout',
		'description' => __( 'Space below the widget.', 'publisher' ),
		'priority' => 15,
	);

	$fields['publisher_padding_block'] = array(
		'name' => __( 'Padding Block', 'publisher' ),
		'type' => 'text',
		'group' => 'layout',
		'description' => __( 'Enter the number of how many predefined space blocks you want around the widget.', 'publisher' ),
		'priority' => 20,
	);

	$fields['publisher_margin_block'] = array(
		'name' => __( 'Bottom Margin Block', 'publisher' ),
		'type' => 'text',
		'group' => 'layout',
		'description' => __( 'Enter the number of how many predefined space blocks you want below the widget.', 'publisher' ),
		'priority' => 25,
	);

	return $fields;
}
add_filter( 'siteorigin_panels_widget_style_fields', 'publisher_siteorigin_panels_widget_style_fields' );


/**
 * Add Settings to Widgets
 */
function publisher_siteorigin_panels_widget_style_attributes( $attributes, $args ) {

	// Adds sidebar classes
	if ( ! empty( $args['publisher_sidebar_class'] ) ) {
		array_push( $attributes['class'], 'secondary' );
		array_push( $attributes['class'], 'widget-area' );
		array_push( $attributes['class'], 'sidebar-widgets' );
	}

    return $attributes;
}
add_filter( 'siteorigin_panels_widget_style_attributes', 'publisher_siteorigin_panels_widget_style_attributes', 10, 2 );


/**
 * Modify CSS
 */
function publisher_siteorigin_panels_css_object( $css, $panels_data, $post_id ) {

	$row_number = 0; // Pagebuilder Row
	$panel_index = 0; // Widget Order

    foreach ( $panels_data['widgets'] as $gi => $widget ) {

	    $ri = $widget['panels_info']['grid'];
	    $ci = $widget['panels_info']['cell'];

		// If row increases, reset panel index
		if ( $ri != $row_number ) {
			$row_number++;
			$panel_index = 0;
		}

		// Widget Panel ID
		$sub_selector = '#panel-' . $post_id . '-' . $ri . '-' . $ci . '-' . $panel_index;

		// Add margin bottom
		if ( ! empty( $widget['panels_info']['style']['publisher_margin_bottom'] ) ) {
			$css_attributes = array(
				'margin-bottom' => $widget['panels_info']['style']['publisher_margin_bottom']
			);
			$css->add_cell_css( $post_id, $ri, $ci, $sub_selector, $css_attributes, 1920, true );
    	} elseif ( isset( $widget['panels_info']['style']['publisher_margin_block'] ) && '' != $widget['panels_info']['style']['publisher_margin_block'] ) {
	    	$margin_block = $widget['panels_info']['style']['publisher_margin_block'];

			$css_attributes = array(
				'margin-bottom' => ( apply_filters( 'publisher_filter_pagebuilder_margin_block', 1.625 ) * intval( $margin_block ) ) . 'em'
			);
			$css->add_cell_css( $post_id, $ri, $ci, $sub_selector, $css_attributes, 1920, true );
    	}

    	// Add padding block
		if ( ! empty( $widget['panels_info']['style']['padding'] ) ) {

    	} elseif ( isset( $widget['panels_info']['style']['publisher_padding_block'] ) && '' != $widget['panels_info']['style']['publisher_padding_block'] ) {
	    	$padding_block = $widget['panels_info']['style']['publisher_padding_block'];
			$blocks = explode( " ", $padding_block );

			$output = '';
			foreach ( $blocks as $block ) {
				$output .= ( apply_filters( 'publisher_filter_pagebuilder_padding_block', 1.625 ) * intval( $block ) ) . 'em ';
			}

			$css_attributes = array(
				'padding' => $output
			);
			$css->add_cell_css( $post_id, $ri, $ci, $sub_selector, $css_attributes, 1920, true );
    	}

		$panel_index++;

    };

	// Output CSS
    return $css;

}
add_filter( 'siteorigin_panels_css_object', 'publisher_siteorigin_panels_css_object', 10, 3 );
