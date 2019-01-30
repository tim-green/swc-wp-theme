<?php
/**
 * Likes
 *
 * Adds the post like functionality.
 *
 * @package Publisher
 */

/*-----------------------------------------------------------------------------------*/
/*	Enqueue Scripts / Styles
/*-----------------------------------------------------------------------------------*/

/**
 * Enqueue scripts and styles
 */
function publisher_likes_enqueue_scripts() {

	/**
     * Likes Scripts
     */
	wp_enqueue_script( 'publisher-likes-scripts', get_template_directory_uri() . '/includes/modules/publisher-likes/publisher-likes.js', array( 'jquery' ), '1.0.0', true );

	/**
     * Localize Scripts
     */
	wp_localize_script( 'publisher-likes-scripts', 'publisher_likes_js_vars', array(
		'ajaxurl' 				=> admin_url( 'admin-ajax.php' ),
		'liked_message' 		=> __( 'You already like this', 'publisher' ),
		'is_caching_enabled' 	=> ( ! false && publisher_likes_caching_enabled() ),
	) );

}
add_action( 'wp_enqueue_scripts', 'publisher_likes_enqueue_scripts' );



/*-----------------------------------------------------------------------------------*/
/*	Add Likes Meta
/*-----------------------------------------------------------------------------------*/

/**
 * Add post meta likes
 */
if ( ! function_exists( 'publisher_likes_publish_post' ) ) :
function publisher_likes_publish_post( $post_id ) {
	if ( ! is_numeric( $post_id ) )
		return;
	add_post_meta( $post_id, 'publisher_likes_meta', '0', true );
}
endif; // publisher_likes_publish_post
add_action( 'publish_post', 'publisher_likes_publish_post' );




/*-----------------------------------------------------------------------------------*/
/*	Ajax Likes
/*-----------------------------------------------------------------------------------*/

/**
 * Show likes amount
 */
if ( ! function_exists( 'publisher_ajax_get_likes' ) ) :
function publisher_ajax_get_likes() {
	$result = array();

	if ( isset( $_POST['post_ids'] ) && ! empty( $_POST['post_ids'] ) ) {
		foreach ( $_POST['post_ids'] as $html_post_id ) {
			$post_id = intval( $_POST['likes_id'] );
			$result[ $html_post_id ] = publisher_get_like_count( $post_id );
		}
	}

	echo json_encode( $result );

	exit;
}
endif; // publisher_ajax_get_likes
add_action( 'wp_ajax_publisher-get-likes', 'publisher_ajax_get_likes' );
add_action( 'wp_ajax_nopriv_publisher-get-likes', 'publisher_ajax_get_likes' );


/**
 * Update likes count
 */
if ( ! function_exists( 'publisher_ajax_add_like' ) ) :
function publisher_ajax_add_like() {
	if ( isset( $_POST['likes_id'] ) ) {
		$post_id = intval( $_POST['likes_id'] );

		if ( ! is_int( $post_id ) || isset( $_COOKIE[ 'publisher_likes_meta'. $post_id ] ) && publisher_get_like_count( $post_id ) > 0 )
			exit;

		$likes = get_post_meta( $post_id, 'publisher_likes_meta', true );

		if ( ! $likes ) {
			$likes = 0;
			add_post_meta( $post_id, 'publisher_likes_meta', $likes, true );
		}

		update_post_meta( $post_id, 'publisher_likes_meta', intval( $likes + 1 ), $likes );

		// Set Cookie
		$timeout = 604800;
		setcookie( 'publisher_likes_meta' . $post_id, $post_id, time() + $timeout, '/' );
	}

	echo publisher_get_like_count( $post_id );

	exit;
}
endif; // publisher_ajax_add_like
add_action( 'wp_ajax_publisher-add-like', 'publisher_ajax_add_like' );
add_action( 'wp_ajax_nopriv_publisher-add-like', 'publisher_ajax_add_like' );




/*-----------------------------------------------------------------------------------*/
/*	Helper Functions
/*-----------------------------------------------------------------------------------*/

/**
 * Check if caching is enabled
 */
if ( ! function_exists( 'publisher_likes_caching_enabled' ) ) :
function publisher_likes_caching_enabled() {
	return defined( 'WP_CACHE' ) && WP_CACHE;
}
endif; // publisher_likes_caching_enabled




/*-----------------------------------------------------------------------------------*/
/*	Likes
/*-----------------------------------------------------------------------------------*/

/**
 * Get the likes count
 */
if ( ! function_exists( 'publisher_get_like_count' ) ) :
function publisher_get_like_count( $post_id ) {
	$likes_count = number_format_i18n( intval( get_post_meta( $post_id, 'publisher_likes_meta', true ) ) );
	return $likes_count;
}
endif; // publisher_get_like_count


/**
 * Show Likes
 *
 * @param string $before 			Before Likes
 * @param string $after 			After Likes
 * @param string $before_count 		Before the likes count
 * @param string $after_count 		After the likes count
 */
if ( ! function_exists( 'publisher_get_likes' ) ) :
function publisher_get_likes( $before = '', $after = '', $before_count = '', $after_count = '' ) {
	global $post;

	$classes = '';
	if ( isset( $_COOKIE['publisher_likes_meta' . $post->ID] ) && publisher_get_like_count( $post->ID ) > 0 ) {
		$classes .= 'liked';
	}

	$output = sprintf( '<span class="publisher-post-likes %1$s"><a class="likes-count" href="#" id="publisher-post-likes-id-%2$s" data-post-id="%2$s" title="%3$s">%4$s</a></span>',
		$classes,
		$post->ID,
		apply_filters( 'publisher_filter_likes_text', __( 'I like this', 'publisher' ) ),
		$before_count . '<span>' . publisher_get_like_count( $post->ID ) . '</span>' . $after_count
	);

	return $before . $output . $after;
}
endif; // publisher_get_likes
