<?php
/**
 * Custom template tags specific to content-page.php
 *
 * @package Publisher
 */

/*-----------------------------------------------------------------------------------*/
/*	Do Action - publisher_action_page_above_entry_wrap
/*-----------------------------------------------------------------------------------*/

/**
 * Page Above Entry Wrap
 *
 * @action publisher_action_page_above_entry_wrap
 */
if ( ! function_exists( 'publisher_page_above_entry_wrap' ) ) :
function publisher_page_above_entry_wrap() {

	$featured_preview = get_post_meta( get_the_ID(), 'publisher_featured_preview_meta', true );

	if ( $featured_preview ) {

	} else {
		$page_layout_option = get_option( 'publisher_options_page_layout' );
		$post_layout_meta = get_post_meta( get_the_ID(), 'publisher_post_layout_meta', true );
		$post_layout = $post_layout_meta ? $post_layout_meta : $page_layout_option;

		switch( $post_layout ) {
			case 'cover' :
			case 'banner' :
				break;

			case 'wide' :
			default :
				publisher_page_featured_preview();
				break;

		};
	}

}
endif; // publisher_page_above_entry_wrap
add_action( 'publisher_action_page_above_entry_wrap', 'publisher_page_above_entry_wrap', 10 );




/*-----------------------------------------------------------------------------------*/
/*	Do Action - publisher_action_page_entry_wrap
/*-----------------------------------------------------------------------------------*/

/**
 * Page Entry Header
 *
 * @action publisher_action_page_entry_header
 */
if ( ! function_exists( 'publisher_page_entry_header' ) ) :
function publisher_page_entry_header() {

	$featured_preview = get_post_meta( get_the_ID(), 'publisher_featured_preview_meta', true );

	if ( $featured_preview || is_attachment() ) {
		publisher_page_entry_header_standard();

	} else {
		$page_layout_option = get_option( 'publisher_options_page_layout' );
		$post_layout_meta = get_post_meta( get_the_ID(), 'publisher_post_layout_meta', true );
		$post_layout = $post_layout_meta ? $post_layout_meta : $page_layout_option;

		switch( $post_layout ) {
			case 'standard' :
			case 'cover' :
			case 'banner' :
				publisher_page_entry_header_standard();
				break;

			case 'wide' :
			default :
				break;
		};
	};

}
endif; // publisher_page_entry_header
add_action( 'publisher_action_page_entry_wrap', 'publisher_page_entry_header', 10 );


/**
 * Page Entry Content
 *
 * @action publisher_action_page_entry_wrap
 */
if ( ! function_exists( 'publisher_page_entry_content' ) ) :
function publisher_page_entry_content() { ?>

	<div class="entry-content">
		<?php
			the_content();
			wp_link_pages( array(
				'next_or_number' => get_option( 'publisher_options_link_pages' ) ? get_option( 'publisher_options_link_pages' ) : 'next_total_pages'
			) );
		?>
	</div><!-- .entry-content -->

<?php
}
endif; // publisher_single_entry_content
add_action( 'publisher_action_page_entry_wrap', 'publisher_page_entry_content', 20 );




/*-----------------------------------------------------------------------------------*/
/*	Do Action - publisher_action_page_below_entry_wrap
/*-----------------------------------------------------------------------------------*/

/**
 * Page Below Entry Wrap
 *
 * @action publisher_action_page_below_entry_wrap
 */
if ( ! function_exists( 'publisher_page_below_entry_wrap' ) ) :
function publisher_page_below_entry_wrap() {

	$article_footer = apply_filters( 'publisher_filter_page_article_footer', true );
	if ( $article_footer ) {
		publisher_page_article_footer();
	}

}
endif; // publisher_page_below_entry_wrap
add_action( 'publisher_action_page_below_entry_wrap', 'publisher_page_below_entry_wrap', 10 );




/*-----------------------------------------------------------------------------------*/
/*	Template Tags
/*-----------------------------------------------------------------------------------*/

/**
 * Page Featured Preview
 */
if ( ! function_exists( 'publisher_page_featured_preview' ) ) :
function publisher_page_featured_preview() {

	$featured_preview = get_post_meta( get_the_ID(), 'publisher_featured_preview_meta', true );

	// Show featured preview
	if ( $featured_preview ) {
		printf( '<div class="featured-preview">%1$s</div>',
			! wp_oembed_get( $featured_preview ) ? do_shortcode( $featured_preview ) : wp_oembed_get( $featured_preview )
		);

	} else {
		$page_layout_option = get_option( 'publisher_options_page_layout' );
		$post_layout_meta = get_post_meta( get_the_ID(), 'publisher_post_layout_meta', true );
		$post_layout = $post_layout_meta ? $post_layout_meta : $page_layout_option;

		// Show featured image
		switch ( $post_layout ) {
			case 'standard' :
			case 'cover' :
			case 'banner' :
				// Featured Image
				if ( '' != get_the_post_thumbnail() ) {
					printf( '<div class="featured-preview">%1$s%2$s</div><!-- .featured-preview -->',
						publisher_get_the_post_thumbnail( 'publisher-post-thumb-large', '<figure class="featured-image">', '</figure>' ),
						publisher_get_the_post_thumbnail_link()
					);
				}
				break;
			case 'wide' :
			default :
				break;
		}
	}

}
endif; // publisher_page_featured_preview


/**
 * Page Entry Header
 */
if ( ! function_exists( 'publisher_page_entry_header_standard' ) ) :
function publisher_page_entry_header_standard() { ?>

	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1><!-- .entry-title -->

		<?php
			// Subtitles
			if ( '' != get_the_archive_description() ) {
				printf( '<div class="hero-description">%1$s</div>', get_the_archive_description() );
			}
		?>
	</header><!-- .entry-header -->

<?php
}
endif; // publisher_page_entry_header_standard


/**
 * Article Footer
 */
if ( ! function_exists( 'publisher_page_article_footer' ) ) :
function publisher_page_article_footer() {
	$page_details_mod = apply_filters( 'publisher_filter_single_page_details', get_theme_mod( 'publisher_options_page_details' ) );
	$page_details = $page_details_mod ? $page_details_mod : array();

	$hide_read_time = in_array( 'read-time' , $page_details );
	$hide_views = in_array( 'views' , $page_details );
	$hide_likes = in_array( 'likes' , $page_details );
	$hide_shares = in_array( 'shares' , $page_details );
	?>

	<?php do_action( 'publisher_action_page_above_article_footer' ) ?>

	<div class="article-footer">
		<?php if ( $hide_read_time && $hide_views && $hide_likes && $hide_shares ) { } else { ?>
			<ul class="entry-meta footer">
				<?php
					// Read Time
					if ( ! $hide_read_time ) {
						echo publisher_get_read_time( '<li class="meta-read-time">', '</li>', '<i class="fa fa-bookmark"></i>' );
					}

					// View Count via Wordpress Popular Posts
					if ( function_exists( 'publisher_wordpress_popular_poosts_get_views' ) && ! $hide_views ) {
						echo publisher_wordpress_popular_poosts_get_views( '<li class="meta-post-views">', '</li>', '<i class="fa fa-eye"></i><span class="publisher-views-count">', '</span>' );
					};

					// Pluggable: Likes
					if ( function_exists( 'publisher_get_likes' ) && ! $hide_likes ) {
						echo publisher_get_likes( '<li class="meta-post-likes">', '</li>', '<i class="fa fa-heart"></i>' );
					};

					// Pluggable: Shares
					if ( function_exists( 'publisher_get_shares' ) && ! $hide_shares ) {
						echo publisher_get_shares( '<li class="meta-post-shares">', '</li>', '<i class="fa fa-share"></i>' );
					};
				?>
			</ul><!-- .entry-meta -->
		<?php } ?>
	</div><!-- .article-footer -->

	<?php
		/**
		 * @hooked publisher_get_shares_network_dialog - 10
		 */
		do_action( 'publisher_action_page_below_article_footer' );
	?>

<?php
}
endif; // publisher_page_article_footer
