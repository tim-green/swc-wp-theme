<?php
/**
 * Wordpress Popular Posts Compatibility File
 * See: https://wordpress.org/plugins/wordpress-popular-posts/
 *
 * @package Publisher
 */

/*-----------------------------------------------------------------------------------*/
/*	Setup Wordpress Popular Posts Defaults
/*-----------------------------------------------------------------------------------*/

/**
 * Add views option to customizers
 */
if ( ! function_exists( 'publisher_wordpress_popular_posts_add_view_count' ) ) :
function publisher_wordpress_popular_posts_add_view_count( $details ) {
	$details['views'] = __( 'View Count', 'publisher' );
	return $details;
}
endif; // publisher_wordpress_popular_posts_add_view_count
add_filter( 'publisher_filter_get_page_details_select', 'publisher_wordpress_popular_posts_add_view_count' );
add_filter( 'publisher_filter_get_post_details_select', 'publisher_wordpress_popular_posts_add_view_count' );
add_filter( 'publisher_ext_filter_posts_loop_orderby_args', 'publisher_wordpress_popular_posts_add_view_count' );


/**
 * Add views option to widgets
 */
if ( ! function_exists( 'publisher_wordpress_popular_posts_add_view_options' ) ) :
function publisher_wordpress_popular_posts_add_view_options( $details ) {
	$details['views'] = __( 'Total Views', 'publisher' );
	$details['avg'] = __( 'Average Daily Views', 'publisher' );
	$details['comments'] = __( 'Trending Comments', 'publisher' );
	return $details;
}
endif; // publisher_wordpress_popular_posts_add_view_count
add_filter( 'publisher_ext_filter_posts_loop_orderby_args', 'publisher_wordpress_popular_posts_add_view_options' );


/**
 * Add time ranges to widgets
 */
if ( ! function_exists( 'publisher_wordpress_popular_posts_add_time_ranges' ) ) :
function publisher_wordpress_popular_posts_add_time_ranges( $details ) {
	$details['daily'] = __( 'Last 24 Hours', 'publisher' );
	$details['weekly'] = __( 'Last 7 Days', 'publisher' );
	$details['monthly'] = __( 'Last 30 Days', 'publisher' );
	$details['all'] = __( 'All Time', 'publisher' );
	return $details;
}
endif; // publisher_wordpress_popular_posts_add_time_ranges
add_filter( 'publisher_ext_filter_posts_loop_time_range_args', 'publisher_wordpress_popular_posts_add_time_ranges' );




/*-----------------------------------------------------------------------------------*/
/*	Wordpress Popular Posts Tags
/*-----------------------------------------------------------------------------------*/

/**
 * Show View Count
 */
if ( ! function_exists( 'publisher_wordpress_popular_poosts_get_views' ) ) :
function publisher_wordpress_popular_poosts_get_views( $before = '', $after = '', $before_count = '', $after_count = '' ) {
	global $post;

	if ( wpp_get_views( $post->ID ) ) {
		$output = sprintf( '<span class="publisher-post-views" data-post-id="%1$s" title="%2$s">%3$s</span>',
			$post->ID,
			apply_filters( 'publisher_filter_views_text', __( 'Views', 'publisher' ) ),
			$before_count . publisher_number_prefixes( wpp_get_views( $post->ID ) ) . $after_count
		);

		return $before . $output . $after;
	}

	return false;
}
endif; // publisher_wordpress_popular_poosts_get_views




/*-----------------------------------------------------------------------------------*/
/*	Wordpress Popular Posts Classes
/*-----------------------------------------------------------------------------------*/

/**
 * Class Publisher_Popular_Posts_Data
 *
 * A class for retrieving an array of popular posts post data.
 *
 * @author 	Phil Smart
 * @edited	Davadrian Maramis
 *
 * @link	http://philsmart.me/wordpress-popular-posts-plugin-raw-data-array/
 * @license https://creativecommons.org/licenses/by/4.0/legalcode Creative Commons Attribution License
 */
class Publisher_Popular_Posts_Data
{
    /** @var array $items Property for storing the final data array */
    protected $items = array();

    /**
     * Constructor. This accepts either an integer or an array of query args suitable for the wpp_get_mostpopular()
     * function. If an int is passed, it is treated as the number of posts to get. If an array is passed, it is
     * treated as a plugin-specific query array.
     *
     * @param int|array $args
     */
    function __construct( $args = array() )
    {
        // Make sure the function is available
        if ( ! function_exists( 'wpp_get_mostpopular' ))
            return;

        // Catch the output of the function to prevent it from displaying and capture the data via the filter
        add_filter( 'wpp_custom_html', array( $this, '_get_posts_data' ), 10, 2 );

        // Ensure our arguments are of a suitable format
        if ( ! is_array( $args ) ) $args = array( $args );
        $wpp_get_mostpopular_args = implode( "&", $args );

        // Invoke the API method using our arguments array, and catch the output in the output buffer
        ob_start();
        wpp_get_mostpopular( $wpp_get_mostpopular_args );
        ob_get_clean();

        // Remove the filter so we don't affect other instances of the shortcode
        remove_filter( 'wpp_custom_html', array( $this, '_get_posts_data' ), 10, 2 );
    }

    /**
     * The hooked filter that intercepts the shortcode output and receives a list of posts.
     *
     * @param $data
     * @param $instance
     */
    public function _get_posts_data( $data, $instance )
    {
        $this->items = (array) $data;
    }

    /**
     * Public accessor for posts array.
     *
     * @return array
     */
    public function get_posts()
    {
        return $this->items;
    }
}
