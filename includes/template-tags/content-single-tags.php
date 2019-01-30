<?php
/**
 * Custom template tags specific to content-single.php
 *
 * @package Publisher
 */

/*-----------------------------------------------------------------------------------*/
/*	Do Action - publisher_action_single_above_entry_wrap
/*-----------------------------------------------------------------------------------*/

/**
 * Single Above Entry Wrap
 *
 * @action publisher_action_single_above_entry_wrap
 */
if ( ! function_exists( 'publisher_single_above_entry_wrap' ) ) :
function publisher_single_above_entry_wrap() {

	$post_layout_option = publisher_cpt_options() ? get_option( 'publisher_options_' . get_post_type() . '_post_layout' ) : get_option( 'publisher_options_post_layout' );
	$post_layout_meta = get_post_meta( get_the_ID(), 'publisher_post_layout_meta', true );
	$post_layout = $post_layout_meta ? $post_layout_meta : $post_layout_option;

	switch( $post_layout ) {
		case 'cover' :
		case 'banner' :
			break;

		case 'wide' :
		default :
			publisher_single_featured_preview();
			break;
	};

}
endif; // publisher_single_above_entry_wrap
add_action( 'publisher_action_single_above_entry_wrap', 'publisher_single_above_entry_wrap', 10 );




/*-----------------------------------------------------------------------------------*/
/*	Do Action - publisher_action_single_entry_wrap
/*-----------------------------------------------------------------------------------*/

/**
 * Single Entry Header
 *
 * @action publisher_action_single_entry_wrap
 */
if ( ! function_exists( 'publisher_single_entry_header' ) ) :
function publisher_single_entry_header() {

	$post_layout_option = publisher_cpt_options() ? get_option( 'publisher_options_' . get_post_type() . '_post_layout' ) : get_option( 'publisher_options_post_layout' );
	$post_layout_meta = get_post_meta( get_the_ID(), 'publisher_post_layout_meta', true );
	$post_layout = $post_layout_meta ? $post_layout_meta : $post_layout_option;

	switch( $post_layout ) {
		case 'wide' :
			break;

		default :
			publisher_single_entry_header_standard();
			break;
	};

}
endif; // publisher_single_entry_header
add_action( 'publisher_action_single_entry_wrap', 'publisher_single_entry_header', 10 );


/**
 * Single Entry Content
 *
 * @action publisher_action_single_entry_wrap
 */
if ( ! function_exists( 'publisher_single_entry_content' ) ) :
function publisher_single_entry_content() { ?>

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
add_action( 'publisher_action_single_entry_wrap', 'publisher_single_entry_content', 20 );


/**
 * Single Entry Footer
 *
 * @action publisher_action_single_entry_wrap
 */
if ( ! function_exists( 'publisher_single_entry_footer' ) ) :
function publisher_single_entry_footer() {
	$post_details_mod = publisher_cpt_options() ? get_theme_mod( 'publisher_options_' . get_post_type() . '_post_details' ) : get_theme_mod( 'publisher_options_post_details' );
	$post_details = $post_details_mod ? $post_details_mod : array();

	$hide_tags = in_array( 'tag-list' , $post_details );

	if ( ! $hide_tags ) { ?>
		<div class="entry-footer">
			<?php
				// Tags
				publisher_the_tags( '<div class="entry-meta tags"><i class="fa fa-tags"></i>', '', '</div>' );
			?>
		</div><!-- .entry-footer -->
		<?php
	}
}
endif; // publisher_single_entry_footer
add_action( 'publisher_action_single_entry_wrap', 'publisher_single_entry_footer', 30 );




/*-----------------------------------------------------------------------------------*/
/*	Do Action - publisher_action_single_below_entry_wrap
/*-----------------------------------------------------------------------------------*/

/**
 * Article Footer
 *
 * @action publisher_action_single_below_entry_wrap
 */
if ( ! function_exists( 'publisher_single_article_footer' ) ) :
function publisher_single_article_footer() {
	$post_details_mod = publisher_cpt_options() ? get_theme_mod( 'publisher_options_' . get_post_type() . '_post_details' ) : get_theme_mod( 'publisher_options_post_details' );
	$post_details = $post_details_mod ? $post_details_mod : array();

	$hide_author_card = in_array( 'author-card' , $post_details );
	$hide_read_time = in_array( 'read-time' , $post_details );
	$hide_views = in_array( 'views' , $post_details );
	$hide_likes = in_array( 'likes' , $post_details );
	$hide_shares = in_array( 'shares' , $post_details );
	?>

	<?php do_action( 'publisher_action_single_above_article_footer' ) ?>

	<div class="article-footer">
		<?php
			// Author Card
			if ( ! $hide_author_card ) {
				publisher_single_author_card();
			}
		?>

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
		do_action( 'publisher_action_single_below_article_footer' );
	?>

<?php
}
endif; // publisher_single_footer_meta
add_action( 'publisher_action_single_below_entry_wrap', 'publisher_single_article_footer', 10 );




/*-----------------------------------------------------------------------------------*/
/*	Template Tags
/*-----------------------------------------------------------------------------------*/

/**
 * Single Featured Preview
 */
if ( ! function_exists( 'publisher_single_featured_preview' ) ) :
function publisher_single_featured_preview() {

	$featured_preview = get_post_meta( get_the_ID(), 'publisher_featured_preview_meta', true );

	// Show featured preview
	if ( $featured_preview ) {
		printf( '<div class="featured-preview">%1$s</div>',
			! wp_oembed_get( $featured_preview ) ? do_shortcode( $featured_preview ) : wp_oembed_get( $featured_preview )
		);

	} else {
		$post_layout_option = publisher_cpt_options() ? get_option( 'publisher_options_' . get_post_type() . '_post_layout' ) : get_option( 'publisher_options_post_layout' );
		$post_layout_meta = get_post_meta( get_the_ID(), 'publisher_post_layout_meta', true );
		$post_layout = $post_layout_meta ? $post_layout_meta : $post_layout_option;

		// Show either the gallery or featured image
		switch ( $post_layout ) {
			case 'wide' :
				break;
			default :
				if ( has_post_format( 'gallery' ) && get_post_gallery() ) {
					echo publisher_get_post_gallery( array(
						'before' => '<div class="featured-preview owl-gallery">',
						'after' => '</div><!-- .featured-preview -->',
						'loader' => false,
						'pager' => true
					) );
				} else {
					// Featured Image
					if ( '' != get_the_post_thumbnail() ) {
						printf( '<div class="featured-preview">%1$s%2$s</div><!-- .featured-preview -->',
							publisher_get_the_post_thumbnail( 'publisher-post-thumb-large', '<figure class="featured-image">', '</figure>' ),
							publisher_get_the_post_thumbnail_link()
						);
					}
				}
				break;
		}
	}

}
endif; // publisher_single_featured_preview


/**
 * Single Entry Header
 */
if ( ! function_exists( 'publisher_single_entry_header_standard' ) ) :
function publisher_single_entry_header_standard() { ?>

	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1><!-- .entry-title -->

		<ul class="entry-meta header">
			<?php
				// Category
				if ( publisher_has_category() ) {
					echo publisher_get_the_categories( '<li class="meta-category">', '</li>' );
				};

				// Author
				// if ( '' != get_the_author_meta( 'description' ) || '' != publisher_get_social_icons( 'author' ) ) { } else {
					echo publisher_get_the_author( '<li class="meta-author">', '</li>', 27, get_the_ID() );
				// };

				// Date
				echo publisher_get_the_date( '<li class="meta-date">', '</li>', '', '<i class="fa fa-clock-o"></i>', '', '<i class="fa fa-calendar"></i>' );

				// Comments Link
				echo publisher_get_comments_link( '<li class="meta-comment-link">', '</li>', '<i class="fa fa-comments-o"></i>' );
			?>
		</ul><!-- .entry-meta -->
	</header><!-- .entry-header -->

<?php
}
endif; // publisher_single_entry_header_standard


/**
 * Author Card
 */
if ( ! function_exists( 'publisher_single_author_card' ) ) :
function publisher_single_author_card() {

	if ( '' != get_the_author_meta( 'description' ) || '' != publisher_get_social_icons( 'author' ) ) { ?>

		<div class="author-info">

			<div class="author-avatar">
				<a class="author-archive" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
					<?php
						$author_card_image = get_the_author_meta( 'author_card_image' );

						if ( '' != $author_card_image ) {
							$thumbnail = wp_get_attachment_image_src( attachment_url_to_postid( $author_card_image ), 'thumbnail' );

							printf( '<img src="%1$s" class="avatar" />',
								esc_url( $thumbnail[0] )
							);
						} else {
							echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'publisher_filter_author_bio_avatar_size', 100 ) );
						}
					?>
				</a>
			</div><!-- .author-avatar -->

			<div class="author-profile">
				<h2 class="author-title vcard author"><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a></h2><!-- .author-title -->

				<?php if ( get_the_author_meta( 'description' ) ) { ?>
					<p class="author-description">
						<?php the_author_meta( 'description' ); ?>
					</p><!-- .author-description -->
				<?php } ?>

				<?php if ( publisher_get_social_icons( 'author' ) ) { ?>
					<div class="author-social">
						<?php echo publisher_get_social_icons( 'author' ); ?>
					</div><!-- .author-social -->
				<?php } ?>
			</div><!-- .author-profile -->

		</div><!-- .author-info -->

	<?php
	}
}
endif; // publisher_single_author_card
