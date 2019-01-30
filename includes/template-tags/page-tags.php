<?php
/**
 * Custom template tags specific to content-page-tags.php
 *
 * @package Publisher
 */

/*-----------------------------------------------------------------------------------*/
/*	Do Action - publisher_action_page_above_primary
/*-----------------------------------------------------------------------------------*/

/**
 * Page Breadcrumbs
 *
 * @action publisher_action_page_above_primary
 */
if ( ! function_exists( 'publisher_page_above_primary' ) ) :
function publisher_page_above_primary() {

	// Breadcrumbs
	if ( 'hide' != get_option( 'publisher_options_breadcrumbs' ) ) {
		publisher_breadcrumbs();
	}

	// Page Layout
	$page_layout_option = get_option( 'publisher_options_page_layout' );
	$post_layout_meta = get_post_meta( get_the_ID(), 'publisher_post_layout_meta', true );
	$post_layout = $post_layout_meta ? $post_layout_meta : $page_layout_option;

	switch( $post_layout ) {
		case 'banner' :
			publisher_page_featured_preview();
			break;

		case 'wide' :
		default :
			break;
	};

}
endif; // publisher_page_above_primary
add_action( 'publisher_action_page_above_primary', 'publisher_page_above_primary', 10 );




/*-----------------------------------------------------------------------------------*/
/*	Do Action - publisher_action_index_loop
/*-----------------------------------------------------------------------------------*/

/**
 * Page Loop
 *
 * @action publisher_action_page_loop
 */
if ( ! function_exists( 'publisher_page_content' ) ) :
function publisher_page_content() {
	$template = apply_filters( 'publisher_filter_page_content_part', 'page' );
	get_template_part( 'includes/partials/content', $template );
}
endif; // publisher_page_content
add_action( 'publisher_action_page_loop', 'publisher_page_content', 10 );


/**
 * Page Comments
 *
 * @action publisher_action_page_loop
 */
if ( ! function_exists( 'publisher_page_comments_template' ) ) :
function publisher_page_comments_template() {

	// If comments are open or we have at least one comment, load up the comment template
	if ( comments_open() || get_comments_number() ) :
		comments_template();
	endif;

}
endif; // publisher_page_comments_template
add_action( 'publisher_action_page_loop', 'publisher_page_comments_template', 20 );
