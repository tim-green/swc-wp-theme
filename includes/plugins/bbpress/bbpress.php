<?php
/**
 * BBpress Compatibility File
 * See: https://bbpress.org/
 *
 * @package Publisher
 */

/*-----------------------------------------------------------------------------------*/
/*	Setup BBpress Defaults
/*-----------------------------------------------------------------------------------*/

/**
 * Create vertical list
 */
if ( ! function_exists( 'publisher_bbp_before_list_forums_parse_args' ) ) :
function publisher_bbp_before_list_forums_parse_args() {
	$args['separator'] = '<br>';
	return $args;
}
endif; // publisher_bbp_before_list_forums_parse_args
add_filter( 'bbp_before_list_forums_parse_args', 'publisher_bbp_before_list_forums_parse_args' );


/**
 * Remove breadcrumb from the forum archive page
 */
if ( ! function_exists( 'publisher_bbp_no_breadcrumb' ) ) :
function publisher_bbp_no_breadcrumb( $param ) {
	if ( bbp_is_forum_archive() ) {
		return true;
	}
}
endif; // publisher_bbp_no_breadcrumb
add_filter( 'bbp_no_breadcrumb', 'publisher_bbp_no_breadcrumb' );


/**
 * Modify breadcrumb seperator
 */
if ( ! function_exists( 'publisher_bbp_breadcrumb_separator' ) ) :
function publisher_bbp_breadcrumb_separator( $sep ) {
	return '<span class="sep">/</span>';
}
endif; // publisher_bbp_no_breadcrumb
add_filter( 'bbp_breadcrumb_separator', 'publisher_bbp_breadcrumb_separator' );


/**
 * Shorten freshness display
 */
if ( ! function_exists( 'publisher_bbp_short_freshness_time' ) ) :
function publisher_bbp_short_freshness_time( $output) {
	$output = preg_replace( '/, .*[^ago]/', ' ', $output );
	return $output;
}
endif; // publisher_bbp_short_freshness_time
add_filter( 'bbp_get_time_since', 'publisher_bbp_short_freshness_time' );
add_filter( 'bp_core_time_since', 'publisher_bbp_short_freshness_time');




/*-----------------------------------------------------------------------------------*/
/*	Publisher Functions
/*-----------------------------------------------------------------------------------*/

/**
 * Register widget area for bbpress
 */
function publisher_bbp_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Forum Sidebar', 'publisher' ),
		'id'            => 'sidebar-forum',
		'description'   => __( 'Appears in the forum content area when widgets are active.', 'publisher' ),
		'before_widget' => '<aside id="%1$s" class="sidebar-main widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );
}
add_action( 'widgets_init', 'publisher_bbp_widgets_init' );


/**
 * Theme Customizer Options
 */
function publisher_bbp_customize_register( $wp_customize ) {

	/*-----------------------------------------------------------------------------------*/
	/*	Forum
	/*-----------------------------------------------------------------------------------*/

	$wp_customize->add_section( 'publisher_section_forum', array(
		'title'				=> __( 'Forum', 'publisher' ),
		'panel'				=> 'publisher_panel_theme_section'
	) );

	// Forum Archive Page Title
	$wp_customize->add_setting( 'publisher_options_forum_page_title', array(
		'type'				=> 'option',
		'sanitize_callback' => 'publisher_sanitize_text'
	) );

	$wp_customize->add_control( 'publisher_options_forum_page_title', array(
		'label'        		=> __( 'Forum Archive Page Title', 'publisher' ),
		'section'      		=> 'publisher_section_forum',
		'settings'     		=> 'publisher_options_forum_page_title',
		'type'         		=> 'text',
		'description'  		=> __( 'Title for the Forum archive page header', 'publisher' ),
	) );

	// Forum Archive Page Description
	$wp_customize->add_setting( 'publisher_options_forum_page_desc', array(
		'type'				=> 'option',
		'sanitize_callback' => 'publisher_sanitize_text'
	) );

	$wp_customize->add_control( 'publisher_options_forum_page_desc', array(
		'label'        		=> __( 'Forum Archive Page Description', 'publisher' ),
		'section'      		=> 'publisher_section_forum',
		'settings'     		=> 'publisher_options_forum_page_desc',
		'type'         		=> 'textarea',
		'description'  		=> __( 'Description for the Forum archive page header', 'publisher' ),
	) );

	// Forum Sidebar
	$wp_customize->add_setting( 'publisher_options_forum_sidebar_display', array(
		'default'			=> 'show',
		'type'				=> 'option',
		'sanitize_callback' => 'publisher_sanitize_show_hide'
	) );

	$wp_customize->add_control( 'publisher_options_forum_sidebar_display', array(
		'label'    			=> __( 'Forum Sidebar', 'publisher' ),
		'section'  			=> 'publisher_section_forum',
		'settings' 			=> 'publisher_options_forum_sidebar_display',
		'type'     			=> 'select',
		'description'  		=> __( 'Set the default forum sidebar layout', 'publisher' ),
		'choices'  			=> array(
			'show' => __( 'Show', 'publisher' ),
			'hide' => __( 'Hide', 'publisher' ),
		),
	) );

	// Forum User Sidebar
	$wp_customize->add_setting( 'publisher_options_forum_user_sidebar_display', array(
		'default'			=> 'show',
		'type'				=> 'option',
		'sanitize_callback' => 'publisher_sanitize_show_hide'
	) );

	$wp_customize->add_control( 'publisher_options_forum_user_sidebar_display', array(
		'label'    			=> __( 'Forum User Sidebar', 'publisher' ),
		'section'  			=> 'publisher_section_forum',
		'settings' 			=> 'publisher_options_forum_user_sidebar_display',
		'type'     			=> 'select',
		'description'  		=> __( 'Set the default forum user sidebar layout', 'publisher' ),
		'choices'  			=> array(
			'show' => __( 'Show', 'publisher' ),
			'hide' => __( 'Hide', 'publisher' ),
		),
	) );

	// Forum Archive Image
	$wp_customize->add_setting( 'publisher_options_forum_featured_header_image', array(
		'sanitize_callback' => 'publisher_sanitize_image'
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'publisher_options_forum_featured_header_image', array(
		'label'     		=> __( 'Forum Archive Image', 'publisher' ),
		'section'       	=> 'publisher_section_forum',
		'settings'      	=> 'publisher_options_forum_featured_header_image',
	) ) );

}
add_action( 'customize_register', 'publisher_bbp_customize_register' );


/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 */
function publisher_bbp_wp_title( $title, $sep ) {

	if ( is_bbpress() ) {

		if ( is_feed() ) {
			return $title;
		}

		global $page, $paged;

		// Add the blog name
		$title .= get_bloginfo( 'name', 'display' );

		// Add the blog description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) ) {
			$title .= " $sep $site_description";
		}

		// Add a page number if necessary:
		if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
			$title .= " $sep " . sprintf( __( 'Page %s', 'publisher' ), max( $paged, $page ) );
		}

	}

	return $title;

}
add_filter( 'wp_title', 'publisher_bbp_wp_title', 10, 2 );


/**
 * Adds custom classes to the array of body classes.
 */
function publisher_bbp_body_class( $classes ) {

    if ( is_bbpress() ) {

		$sidebar_layout = '';
		if ( bbp_is_single_user() ) {
			$sidebar_layout = get_option( 'publisher_options_forum_user_sidebar_display' );
		} else {
			$sidebar_layout = get_option( 'publisher_options_forum_sidebar_display' );
		}

		if ( 'hide' == $sidebar_layout ) {
			$classes[] = 'no-active-sidebar';
		}

	}

    return $classes;

}
add_filter( 'body_class', 'publisher_bbp_body_class' );


/**
 * BBpress Archive Title
 */
if ( ! function_exists( 'publisher_bbp_get_the_archive_title' ) ) :
function publisher_bbp_get_the_archive_title( $title ) {

	if ( bbp_is_forum_archive() ) {
		$forum_title = get_option( 'publisher_options_forum_page_title' );
		$title = $forum_title ? $forum_title : $title;
	}

	return $title;

}
endif; // publisher_bbp_get_the_archive_title
add_filter( 'get_the_archive_title', 'publisher_bbp_get_the_archive_title' );


/**
 * BBpress Archive Description
 */
if ( ! function_exists( 'publisher_bbp_get_the_archive_description' ) ) :
function publisher_bbp_get_the_archive_description( $description ) {

	if ( bbp_is_forum_archive() ) {
		$forum_desc = get_option( 'publisher_options_forum_page_desc' );
		$description = $forum_desc ? $forum_desc : $description;
	}

	return $description;

}
endif; // publisher_bbp_get_the_archive_description
add_filter( 'get_the_archive_description', 'publisher_bbp_get_the_archive_description' );


/**
 * BBpress use forum featured header image
 */
if ( ! function_exists( 'publisher_bbp_use_forum_header_image' ) ) :
function publisher_bbp_use_forum_header_image( $bg_image_url ) {

	if ( bbp_is_forum_archive() ) {
		$forum_header_image = get_theme_mod( 'publisher_options_forum_featured_header_image' );
		$bg_image_url = $forum_header_image ? $forum_header_image : $bg_image_url;
	}

	return $bg_image_url;

}
endif; // publisher_bbp_use_forum_header_image
add_filter( 'publisher_filter_header_image', 'publisher_bbp_use_forum_header_image' );


/**
 * BBpress show forum sidebar
 */
if ( ! function_exists( 'publisher_bbp_show_sidebar' ) ) :
function publisher_bbp_show_sidebar( $retval ) {

	if ( is_bbpress() ) {
		$sidebar_layout = '';
		if ( bbp_is_single_user() ) {
			$sidebar_layout = get_option( 'publisher_options_forum_user_sidebar_display' );
		} else {
			$sidebar_layout = get_option( 'publisher_options_forum_sidebar_display' );
		}

		if ( 'hide' == $sidebar_layout ) {
			return false;
		}
	}

	return $retval;

}
endif; // publisher_bbp_show_sidebar
add_filter( 'publisher_filter_show_sidebar', 'publisher_bbp_show_sidebar' );


/**
 * BBpress use forum sidebar
 */
if ( ! function_exists( 'publisher_bbp_use_forum_sidebar' ) ) :
function publisher_bbp_use_forum_sidebar( $sidebar ) {

	if ( is_bbpress() ) {
		return 'sidebar-forum';
	}

	return $sidebar;

}
endif; // publisher_bbp_use_forum_sidebar
add_filter( 'publisher_filter_sidebar', 'publisher_bbp_use_forum_sidebar' );


/**
 * Show forum sidebar
 */
if ( ! function_exists( 'publisher_bbp_has_sidebar' ) ) :
function publisher_bbp_has_sidebar( $retval ) {

	if ( is_bbpress() ) {
		$sidebar_layout = '';
		if ( bbp_is_single_user() ) {
			$sidebar_layout = get_option( 'publisher_options_forum_user_sidebar_display' );
		} else {
			$sidebar_layout = get_option( 'publisher_options_forum_sidebar_display' );
		}

		if ( 'hide' == $sidebar_layout ) {
			return false;
		}
	}

	return $retval;

}
endif; // publisher_woocommerce_has_sidebar
add_filter( 'publisher_filter_has_sidebar', 'publisher_bbp_has_sidebar' );


/**
 * Set default posts layout
 */
function publisher_bbp_set_default_posts_layout( $value ) {
	if ( is_bbpress() ) {
		$value = '';
	}
	return $value;
}
add_filter( 'option_publisher_options_posts_layout', 'publisher_bbp_set_default_posts_layout' );




/*-----------------------------------------------------------------------------------*/
/*	Do Action - publisher_action_page_entry_wrap
/*-----------------------------------------------------------------------------------*/

/**
 * BBpress Entry Content
 *
 * @action publisher_action_bbpress_entry
 */
if ( ! function_exists( 'publisher_bbpress_entry_content' ) ) :
function publisher_bbpress_entry_content() { ?>

	<div class="entry-content">
		<?php the_content(); ?>
	</div><!-- .entry-content -->

<?php
}
endif; // publisher_bbpress_entry_content
add_action( 'publisher_action_bbpress_entry', 'publisher_bbpress_entry_content', 10 );




/*-----------------------------------------------------------------------------------*/
/*	Do Action - publisher_action_bbpress_above_primary
/*-----------------------------------------------------------------------------------*/

/**
 * Site Hero Header
 *
 * @action publisher_action_bbpress_above_site_main
 */
if ( ! function_exists( 'publisher_site_hero_header' ) ) :
function publisher_site_hero_header() {

	if ( ! bbp_is_forum_archive() ) { ?>
		<header class="hero-header bbpress-header">
			<div class="container">
				<h1 class="hero-title"><?php the_title(); ?></h1><!-- .hero-title -->

				<?php bbp_breadcrumb(); ?>
			</div>
		</header><!-- .hero-header -->
	<?php
	}

}
endif; // publisher_site_hero_header
add_action( 'publisher_action_bbpress_above_primary', 'publisher_site_hero_header', 10 );
