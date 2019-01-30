<?php
/**
 * Shares
 *
 * Adds the post share functionality.
 *
 * @package Publisher
 */

/*-----------------------------------------------------------------------------------*/
/*	Define Defaults
/*-----------------------------------------------------------------------------------*/

if ( ! defined( 'PUBLISHER_SHARES_META_KEY' ) )
	define( 'PUBLISHER_SHARES_META_KEY', 'publisher_shares_meta' );




/*-----------------------------------------------------------------------------------*/
/*	Enqueue Scripts / Styles
/*-----------------------------------------------------------------------------------*/

/**
 * Enqueue scripts and styles
 */
if ( ! function_exists( 'publisher_shares_enqueue_scripts' ) ) :
function publisher_shares_enqueue_scripts() {

	/**
     * Shares Scripts
     */
	wp_enqueue_script( 'publisher-shares-scripts', get_template_directory_uri() . '/includes/modules/publisher-shares/publisher-shares.js', array( 'jquery' ), '1.0.0' , true );

	/**
     * Localize Scripts
     */
	wp_localize_script( 'publisher-shares-scripts', 'publisher_shares_js_vars', array(
		'ajaxurl' 				=> admin_url( 'admin-ajax.php' ),
		'is_caching_enabled' 	=> publisher_shares_caching_enabled(),
	) );

}
endif; // publisher_shares_enqueue_scripts
add_action( 'wp_enqueue_scripts', 'publisher_shares_enqueue_scripts' );




/*-----------------------------------------------------------------------------------*/
/*	Ajax Shares
/*-----------------------------------------------------------------------------------*/

/**
 * Count Share Number
 */
if ( ! function_exists( 'publisher_shares_count' ) ) :
function publisher_shares_count() {

	$post_id = false;
	$social = false;

	if ( isset( $_POST['post_id'] ) && ! empty( $_POST['post_id'] ) && intval( $_POST['post_id'] ) > 0 ) {
		$post_id = intval( $_POST[ 'post_id' ] );
	}

	if ( isset( $_POST['social'] ) && ! empty( $_POST['social'] ) ) {
		$social = $_POST[ 'social' ];

		if ( ! in_array( $social, array( 'facebook', 'twitter', 'pinterest', 'googleplus' ) ) ) {
			$social = false;
		}
	}

	if ( ! empty( $post_id ) && ! empty( $social ) ) {
		// Update total sharing counter
		$total_counter = intval( get_post_meta( $post_id, PUBLISHER_SHARES_META_KEY, true ) );
		if ( empty( $total_counter ) ) {
			$total_counter = 0;
		}
		$total_counter++;
		update_post_meta( $post_id, PUBLISHER_SHARES_META_KEY, $total_counter );

		// Update specific social sharing counter
		$social_meta_key = PUBLISHER_SHARES_META_KEY . '_' . $social;

		$social_counter = intval( get_post_meta( $post_id, $social_meta_key, true ) );

		if ( empty( $social_counter ) ) {
			$social_counter = 0;
		}

		$social_counter++;
		update_post_meta( $post_id, $social_meta_key, $social_counter );

		echo publisher_number_prefixes( $total_counter );

	} else {
		echo '';
	}

	exit;
}
endif; // publisher_shares_count
add_action( 'wp_ajax_publisher-count-share', 'publisher_shares_count' );
add_action( 'wp_ajax_nopriv_publisher-count-share', 'publisher_shares_count' );




/*-----------------------------------------------------------------------------------*/
/*	Helper Functions
/*-----------------------------------------------------------------------------------*/

/**
 * Check if caching is enabled
 */
if ( ! function_exists( 'publisher_shares_caching_enabled' ) ) :
function publisher_shares_caching_enabled() {
	return defined( 'WP_CACHE' ) && WP_CACHE;
}
endif; // publisher_shares_caching_enabled




/*-----------------------------------------------------------------------------------*/
/*	Share Network
/*-----------------------------------------------------------------------------------*/

/**
 * Share Network
 */
if ( ! function_exists( 'publisher_get_shares' ) ) :
function publisher_get_shares( $before = '', $after = '', $before_count = '', $after_count = '' ) {
	$total_counter = intval( get_post_meta( get_the_id(), PUBLISHER_SHARES_META_KEY, true ) );
	$post_id = get_the_id();

	$output = sprintf( '<a class="publisher-post-shares" href="#publisher-shares-dialog-' . $post_id . '" title="%1$s" data-post-id="' . $post_id . '">%2$s</a>',
		esc_attr( __( 'Shares', 'publisher' ) ),
		$before_count . '<span class="publisher-post-shares-count-' . $post_id . '">' . sprintf( __( '%s', 'publisher' ), publisher_number_prefixes( $total_counter ) ) . '</span>' . $after_count
	);

	return $before . $output . $after;
}
endif; // publisher_get_shares


/**
 * Share Network Dialog
 */
if ( ! function_exists( 'publisher_get_shares_network_dialog' ) ) :
function publisher_get_shares_network_dialog() { ?>
	<div id="publisher-shares-dialog-<?php the_ID(); ?>" class="publisher-shares-network">
		<div class="shares-dialog-container">
			<div class="shares-dialog-content">
				<span class="shares-header"><?php echo apply_filters( 'publisher_filter_get_shares_network_dialog_shares_header', __( 'Share', 'publisher' ) ); ?></span>
				<h2 class="shares-title"><?php echo get_the_title(); ?></h2>

				<?php
					echo publisher_get_share_icons( '<ul class="publisher-post-share-list">', '</ul>', array(
						'facebook' => array(
							'content' 	=> '<i class="fa fa-facebook"></i>',
							'title' 	=> __( 'Share to Facebook', 'publisher' ),
							'width' 	=> 500,
							'height' 	=> 300,
							'before' 	=> '<li>',
							'after' 	=> '</li>'
						),

						'twitter' => array(
							'content' 	=> '<i class="fa fa-twitter"></i>',
							'title' 	=> __( 'Share to Twitter', 'publisher' ),
							'width' 	=> 500,
							'height' 	=> 300,
							'before' 	=> '<li>',
							'after' 	=> '</li>'
						),

						'pinterest' => array(
							'content' 	=> '<i class="fa fa-pinterest"></i>',
							'title' 	=> __( 'Share to Pinterest', 'publisher' ),
							'width' 	=> 750,
							'height' 	=> 300,
							'before' 	=> '<li>',
							'after' 	=> '</li>'
						),

						'googleplus' => array(
							'content' 	=> '<i class="fa fa-google-plus"></i>',
							'title' 	=> __( 'Share to Google+', 'publisher' ),
							'width' 	=> 500,
							'height' 	=> 475,
							'before' 	=> '<li>',
							'after' 	=> '</li>'
						),
					) );
				?>
			</div>
		</div>
	</div><!-- .publisher-shares-network -->
<?php
}
endif;
add_action( 'publisher_action_single_below_article_footer', 'publisher_get_shares_network_dialog', 10 );
add_action( 'publisher_action_page_below_article_footer', 'publisher_get_shares_network_dialog', 10 );
add_action( 'woocommerce_after_single_product_summary', 'publisher_get_shares_network_dialog', 3 );




/*-----------------------------------------------------------------------------------*/
/*	Share Icons
/*-----------------------------------------------------------------------------------*/

/**
 * Share Icons
 *
 * @param string $before 			Before Shares
 * @param string $after 			After Shares
 * @param string $before_count 		Before the likes count
 * @param string $after_count 		After the likes count
 */
if ( ! function_exists( 'publisher_get_share_icons' ) ) :
function publisher_get_share_icons( $before = '', $after = '', $networks = '' ) {
	$post_id = get_the_id();
	$post_url = urlencode( get_permalink() );
	$post_title = urlencode( get_the_title() );

	// Get Networks
	$networks = apply_filters( 'publisher_filter_network_settings', $networks );

	// Output Networks
	$output = '';
	foreach ( $networks as $network => $setting ) {

		// Get share links
		switch( $network ) {
			case 'facebook':
				$share_url = sprintf( 'http://www.facebook.com/sharer.php?u=%s', $post_url );
				break;
			case 'twitter':
				$share_url = sprintf( 'http://twitter.com/home?status=%s', $post_title . '%20-%20' . $post_url );
				break;
			case 'pinterest':
				$thumbnail_url = '';
				if ( '' != get_the_post_thumbnail() ) {
					$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_id() ), 'full' );
					$thumbnail_url = $thumbnail[0];
				}
				$share_url = sprintf( 'http://pinterest.com/pin/create/button/?url=%s&media=%s&description=%s',
					$post_url,
					$thumbnail_url,
					$post_title
				);
				break;
			case 'googleplus':
				$share_url = sprintf( 'http://plus.google.com/share?url=%s', $post_url );
				break;
			default:
				$share_url = isset( $setting['share_url'] ) ? $setting['share_url'] : '';
				break;
		};

		$output .= isset( $setting['before'] ) ? $setting['before'] : '';
		$output .= sprintf ( '<a class="publisher-post-shares-icon %1$s" title="%2$s" href="%3$s" data-post-id="%4$s" data-share-to="%1$s" data-width="%5$s" data-height="%6$s">%7$s</a> ',
			$network,
			esc_attr( $setting['title'] ),
			esc_url( $share_url ),
			esc_attr( $post_id ),
			$setting['width'],
			$setting['height'],
			$setting['content']
		);
		$output .= isset( $setting['after'] ) ? $setting['after'] : '';
	}
	return $before . $output . $after;
}
endif; // publisher_get_share_icons
