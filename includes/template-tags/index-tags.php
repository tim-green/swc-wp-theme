<?php
/**
 * Custom template tags specific to index.php
 *
 * @package Publisher
 */

/*-----------------------------------------------------------------------------------*/
/*	Do Action - publisher_action_index_above_site_main
/*-----------------------------------------------------------------------------------*/

/**
 * Loader Icon
 *
 * @action publisher_action_index_above_site_main
 */
if ( ! function_exists( 'publisher_loader_icon' ) ) :
function publisher_loader_icon() {

	$posts_layout_option = publisher_cpt_options() ? get_option( 'publisher_options_' . get_post_type() . '_posts_layout' ) : get_option( 'publisher_options_posts_layout' );

	if ( 'grid' == $posts_layout_option ) {
		echo publisher_get_loader_icon();
	}

}
endif; // publisher_loader_icon
add_action( 'publisher_action_index_above_site_main', 'publisher_loader_icon');
//add_action( 'publisher_action_index_above_site_main', 'publisher_loader_icon', 10 );



/*-----------------------------------------------------------------------------------*/
/*	Do Action - publisher_action_index_loop
/*-----------------------------------------------------------------------------------*/

/**
 * Index Loop
 *
 * @action publisher_action_index_loop
 */
if ( ! function_exists( 'publisher_index_loop' ) ) :
function publisher_index_loop() {
	/**
	 * Include the Post-Format or Post-Type specific template for the content.
	 * If you want to override this in a child theme, then include a file
	 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
	 */
	if ( publisher_is_custom_post_type() ) :
		if ( get_post_format() && ! in_array( get_post_format(), array( 'gallery', 'audio', 'video' ) ) ) {
			get_template_part( 'includes/partials/content-' . get_post_format(), get_post_type() );
		} else {
			get_template_part( 'includes/partials/content', get_post_type() );
		}
	else :
		get_template_part( 'includes/partials/content', get_post_format() );
	endif;
}
endif; // publisher_index_loop
add_action( 'publisher_action_index_loop', 'publisher_index_loop', 10 );




/*-----------------------------------------------------------------------------------*/
/*	Do Action - publisher_action_index_below_site_main
/*-----------------------------------------------------------------------------------*/

/**
 * Posts Navigation
 *
 * @action publisher_action_index_below_site_main
 */
if ( ! function_exists( 'publisher_the_posts_navigation' ) ) :
function publisher_the_posts_navigation() {

	$pagination = publisher_cpt_options() ? get_option( 'publisher_options_' . get_post_type() . '_pagination' ) : get_option( 'publisher_options_posts_pagination' );

	if ( 'numeric' == $pagination ) {
		if ( is_rtl() ) {
			$post_pagination_args = array(
				'prev_text' => _x( '<i class="fa fa-angle-right"></i>', 'RTL: Numeric Previous post', 'publisher' ),
				'next_text' => _x( '<i class="fa fa-angle-left"></i>', 'RTL: Numeric Next post', 'publisher' ),
			);
		} else {
			$post_pagination_args = array(
				'prev_text' => __( '<i class="fa fa-angle-left"></i>', 'publisher' ),
				'next_text' => __( '<i class="fa fa-angle-right"></i>', 'publisher' ),
			);
		}
		the_posts_pagination( apply_filters( 'publisher_filter_the_posts_pagination_args', $post_pagination_args ) );
	} else {
		if ( is_rtl() ) {
			$posts_navigation_args = array(
				'prev_text' => _x( '<span class="meta-nav">&rarr;</span> Older posts', 'RTL: Older posts', 'publisher' ),
				'next_text' => _x( 'Newer posts <span class="meta-nav">&larr;</span>', 'RTL: Newer posts', 'publisher' ),
			);
		} else {
			$posts_navigation_args = array(
				'prev_text' => __( '<span class="meta-nav">&larr;</span> Older posts', 'publisher' ),
				'next_text' => __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'publisher' ),
			);
		}
		the_posts_navigation( apply_filters( 'publisher_filter_the_posts_navigation_args', $posts_navigation_args ) );
	}

}
endif; // publisher_the_posts_navigation
add_action( 'publisher_action_index_below_site_main', 'publisher_the_posts_navigation', 10 );
