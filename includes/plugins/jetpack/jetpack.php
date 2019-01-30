<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package Publisher
 */

/*-----------------------------------------------------------------------------------*/
/*	Setup Jetpack Defaults
/*-----------------------------------------------------------------------------------*/

/**
 * Setup theme support for Jetpack modules
 */
if ( ! function_exists( 'publisher_jetpack_setup' ) ) :
function publisher_jetpack_setup() {

	/**
	 * Add Infinite Scroll.
	 * See: http://jetpack.me/support/infinite-scroll/
	 */
	add_theme_support( 'infinite-scroll', array(
		'container' 		=> 'main',
		'footer'    		=> false,
		'wrapper'   		=> false,
		'render'    		=> 'publisher_render_infinite_posts'
	) );

	/**
	 * Add Custom Content Types
	 * See: http://en.support.wordpress.com/portfolios/
	 */
	add_theme_support( 'jetpack-portfolio' );

}
endif; // publisher_jetpack_setup
add_action( 'after_setup_theme', 'publisher_jetpack_setup' );


/**
 * Modify Post Type Support
 */
if ( ! function_exists( 'publisher_jetpack_init' ) ) :
function publisher_jetpack_init() {

	// Add Post Format support to portfolio
	add_post_type_support( 'jetpack-portfolio', 'post-formats' );

	// Add Excerpts to portfolio
	add_post_type_support( 'jetpack-portfolio', 'excerpt' );

}
endif; // publisher_jetpack_init
add_action( 'init', 'publisher_jetpack_init', 10 );




/*-----------------------------------------------------------------------------------*/
/*	Publisher Functions
/*-----------------------------------------------------------------------------------*/

/**
 * Register widget area
 */
if ( ! function_exists( 'publisher_jetpack_widgets_init' ) ) :
function publisher_jetpack_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Portfolio Sidebar', 'publisher' ),
		'id'            => 'sidebar-portfolio',
		'description'   => __( 'Appears in the portfolio content area when widgets are active.', 'publisher' ),
		'before_widget' => '<aside id="%1$s" class="sidebar-main widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );
}
endif; // publisher_jetpack_widgets_init
add_action( 'widgets_init', 'publisher_jetpack_widgets_init' );


/**
 * Use portfolio sidebar
 */
if ( ! function_exists( 'publisher_jetpack_use_shop_sidebar' ) ) :
function publisher_jetpack_use_shop_sidebar( $sidebar ) {

	if ( is_post_type_archive( 'jetpack-portfolio' ) || is_tax( array( 'jetpack-portfolio-type', 'jetpack-portfolio-tag' ) ) || is_singular( 'jetpack-portfolio' ) ) {
		return 'sidebar-portfolio';
	}

	return $sidebar;

}
endif; // publisher_jetpack_use_shop_sidebar
add_filter( 'publisher_filter_sidebar', 'publisher_jetpack_use_shop_sidebar' );




/*-----------------------------------------------------------------------------------*/
/*	Jetpack Infinite Scroll Functions
/*-----------------------------------------------------------------------------------*/

/**
 * Render infinite posts by using template parts
 */
if ( ! function_exists( 'publisher_render_infinite_posts' ) ):
function publisher_render_infinite_posts() {
	while( have_posts() ) {
		the_post();
		if ( 'jetpack-portfolio' == get_post_type() ) :
			get_template_part( 'includes/partials/content', 'portfolio' );
		else :
			get_template_part( 'includes/partials/content', get_post_format() );
		endif;
	}
}
endif; // publisher_render_infinite_posts


/**
 * Infinite Scroll load more posts text
 */
if ( ! function_exists( 'publisher_infinite_scroll_js_settings' ) ):
function publisher_infinite_scroll_js_settings( $settings ) {
	$settings['text'] = esc_js( __( 'Load More', 'publisher' ) );
	return $settings;
}
endif; // publisher_infinite_scroll_js_settings
add_filter( 'infinite_scroll_js_settings', 'publisher_infinite_scroll_js_settings' );


/*
 * Setup infinite scroll for specific pages only
 */
if ( ! function_exists( 'publisher_infinite_scroll_archive_supported' ) ):
function publisher_infinite_scroll_archive_supported( $supported ) {
	// Don't show on the shop
	$supported = ( is_home() || is_archive() || is_search() ) && ! publisher_is_index( 'product' );
	return $supported;
}
endif; // publisher_infinite_scroll_archive_supported
add_filter( 'infinite_scroll_archive_supported' , 'publisher_infinite_scroll_archive_supported' );
