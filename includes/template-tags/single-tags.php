<?php
/**
 * Custom template tags specific to single.php
 *
 * @package Publisher
 */

/*-----------------------------------------------------------------------------------*/
/*	Do Action - publisher_action_single_above_primary
/*-----------------------------------------------------------------------------------*/

/**
 * Single Breadcrumbs
 *
 * @action publisher_action_single_above_primary
 */
if ( ! function_exists( 'publisher_single_above_primary' ) ) :
function publisher_single_above_primary() {

	// Breadcrumbs
	if ( 'hide' != get_option( 'publisher_options_breadcrumbs' ) ) {
		publisher_breadcrumbs();
	}

	// Post Layout
	$post_layout_option = publisher_cpt_options() ? get_option( 'publisher_options_' . get_post_type() . '_post_layout' ) : get_option( 'publisher_options_post_layout' );
	$post_layout_meta = get_post_meta( get_the_ID(), 'publisher_post_layout_meta', true );
	$post_layout = $post_layout_meta ? $post_layout_meta : $post_layout_option;

	switch( $post_layout ) {
		case 'banner' :
			publisher_single_featured_preview();
			break;

		case 'wide' :
		default :
			break;
	};

}
endif; // publisher_single_breadcrumbs
add_action( 'publisher_action_single_above_primary', 'publisher_single_above_primary', 10 );




/*-----------------------------------------------------------------------------------*/
/*	Do Action - publisher_action_single_loop
/*-----------------------------------------------------------------------------------*/

/**
 * Single Content
 *
 * @action publisher_action_single_loop
 */
if ( ! function_exists( 'publisher_single_content' ) ) :
function publisher_single_content() {
	$template = apply_filters( 'publisher_filter_single_content_part', 'single' );
	get_template_part( 'includes/partials/content', $template );
}
endif; // publisher_single_content
add_action( 'publisher_action_single_loop', 'publisher_single_content', 10 );


/**
 * Single Post Navigation
 *
 * @action publisher_action_single_loop
 */
if ( ! function_exists( 'publisher_single_post_navigation' ) ) :
function publisher_single_post_navigation() {

	// Get Pagination Style
	$post_pagination = publisher_cpt_options() ? get_option( 'publisher_options_' . get_post_type() . 'post_pagination' ) : get_option( 'publisher_options_post_pagination' );

	switch( $post_pagination ) {
		case 'prev-next' :
			publisher_single_prev_next_pagination();
			break;

		case 'next-up' :
			publisher_single_next_up_pagination();
			break;

		case 'post-image' :
			publisher_single_posts_image_pagination();
			break;

		case 'none' :
			break;

		default :
			publisher_single_prev_next_pagination();
			break;
	}

}
endif; // publisher_single_post_nav
add_action( 'publisher_action_single_loop', 'publisher_single_post_navigation', 20 );


/**
 * Single Related Posts
 *
 * @action publisher_action_single_loop
 */
if ( ! function_exists( 'publisher_single_related_posts' ) ) :
function publisher_single_related_posts() {

	$post_details_mod = publisher_cpt_options() ? get_theme_mod( 'publisher_options_' . get_post_type() . '_post_details' ) : get_theme_mod( 'publisher_options_post_details' );
	$post_details = $post_details_mod ? $post_details_mod : array();

	$hide_related_posts = in_array( 'related-posts' , $post_details );

	if ( ! $hide_related_posts ) {

		// Get Related Posts
		$related_posts_query = new WP_Query( publisher_get_related_posts() );

		if ( $related_posts_query->have_posts() ) :

			// Related Posts text
			$related_posts_options = publisher_cpt_options() ? get_option( 'publisher_options_' . get_post_type() . '_related_posts' ) : get_option( 'publisher_options_post_related_posts' );
			$related_posts_text = $related_posts_options ? $related_posts_options : __( 'Related Posts', 'publisher' );
			?>

			<div id="related-posts" class="related-posts-area posts-index">
				<h2 class="related-posts-header section-title"><?php echo esc_attr( $related_posts_text ) ?></h2>

				<div class="related-posts-wrap clearfix">
					<?php
						while ( $related_posts_query->have_posts() ) : $related_posts_query->the_post();
							get_template_part( 'includes/partials/posts-loop-related-posts' );
						endwhile;
					?>
				</div><!-- .related-posts-wrap -->
			</div><!-- #related-posts -->

			<?php
		endif;

		// Reset Post Data
		wp_reset_postdata();
	}

}
endif; // publisher_single_related_posts
add_action( 'publisher_action_single_loop', 'publisher_single_related_posts', 30 );


/**
 * Single Comments
 *
 * @action publisher_action_single_loop
 */
if ( ! function_exists( 'publisher_single_comments_template' ) ) :
function publisher_single_comments_template() {

	// If comments are open or we have at least one comment, load up the comment template
	if ( comments_open() || get_comments_number() ) :
		comments_template();
	endif;

}
endif; // publisher_single_comments_template
add_action( 'publisher_action_single_loop', 'publisher_single_comments_template', 40 );




/*-----------------------------------------------------------------------------------*/
/*	Single Tags
/*-----------------------------------------------------------------------------------*/

/**
 * Prev/Next Posts Pagination
 */
if ( ! function_exists( 'publisher_single_prev_next_pagination' ) ) :
function publisher_single_prev_next_pagination() {

	if ( is_rtl() ) {
		$post_navigation_args = array(
			'prev_text' => _x( '<span class="meta-nav-title"><span>&rarr; Previous Post</span>%title</span>', 'RTL: Previous post', 'publisher' ),
			'next_text' => _x( '<span class="meta-nav-title"><span>Next Post &larr;</span>%title</span>', 'RTL: Next post', 'publisher' ),
		);
	} else {
		$post_navigation_args = array(
			'prev_text' => _x( '<span class="meta-nav-title"><span>&larr; Previous Post</span>%title</span>', 'Previous post', 'publisher' ),
			'next_text' => _x( '<span class="meta-nav-title"><span>Next Post &rarr;</span>%title</span>', 'Next post', 'publisher' ),
		);
	}

	the_post_navigation( apply_filters( 'publisher_filter_the_post_navigation_args', $post_navigation_args ) );

}
endif; // publisher_single_prev_next_pagination


/**
 * Posts Image Pagination
 */
if ( ! function_exists( 'publisher_single_posts_image_pagination' ) ) :
function publisher_single_posts_image_pagination() {

	$prev_post = get_previous_post();
	$next_post = get_next_post(); ?>

	<nav class="post-navigation post-image">
		<h2 class="screen-reader-text"><?php _e( 'Post navigation', 'publisher' ); ?></h2>

		<?php
			if ( ! empty( $prev_post ) ) {
				$prev_post_text =  apply_filters( 'publisher_single_posts_image_pagination_prev_text', __( 'Previous Post', 'publisher' ) );
				publisher_single_cover_pagination( $prev_post, 'cover nav-previous', $prev_post_text );
			};

			if ( ! empty( $next_post ) ) {
				$next_post_text =  apply_filters( 'publisher_single_posts_image_pagination_next_text', __( 'Next Post', 'publisher' ) );
				publisher_single_cover_pagination( $next_post, 'cover nav-next', $next_post_text );
			};
		?>
	</nav>

<?php
}
endif; // publisher_single_posts_image_pagination


/**
 * Posts Next Up Pagination
 */
if ( ! function_exists( 'publisher_single_next_up_pagination' ) ) :
function publisher_single_next_up_pagination() {

	$next_post = get_next_post();

	if ( ! empty( $next_post ) ) { ?>
		<nav class="post-navigation post-image next-up">
			<h2 class="screen-reader-text"><?php _e( 'Post navigation', 'publisher' ); ?></h2>

			<?php
				if ( ! empty( $next_post ) ) {
					$next_post_text =  apply_filters( 'publisher_single_posts_next_up_pagination_text', __( 'Next Up', 'publisher' ) );
					publisher_single_cover_pagination( $next_post, 'cover nav-next', $next_post_text );
				};
			?>
		</nav>
		<?php
	}

}
endif; // publisher_single_next_up_pagination


/**
 * Posts Cover Pagination
 */
if ( ! function_exists( 'publisher_single_cover_pagination' ) ) :
function publisher_single_cover_pagination( $post_object, $classes, $text = '' ) { ?>

	<article <?php post_class( $classes, $post_object->ID ); ?>>

		<div class="article-wrap">

			<?php
				// Featured Preview
				$image_size = publisher_get_the_image_size( 'post-image' );
				publisher_index_featured_image_background( $image_size, $post_object->ID );
			?>

			<div class="entry-wrap">
				<header class="entry-header">
					<?php if ( $text ) { ?>
						<a class="pagination-link" href="<?php echo esc_url( get_permalink( $post_object->ID ) ); ?>" rel="bookmark">
							<span class="meta-nav-title"><span><?php echo esc_attr( $text ); ?></span></span>
						</a>
					<?php } ?>

					<h2 class="entry-title"><a href="<?php echo get_permalink( $post_object->ID ); ?>" rel="bookmark"><?php echo esc_attr( $post_object->post_title ); ?></a></h2><!-- .entry-title -->

					<ul class="entry-meta header">
						<?php
							// Category
							if ( publisher_has_category( '', $post_object->ID ) ) {
								echo publisher_get_the_categories( '<li class="meta-category">', '</li>', '', '', '', $post_object->ID );
							};

							// Author
							$post_pagination = get_option( 'publisher_options_post_pagination' );
							if ( 'next-up' == $post_pagination ) {
								echo publisher_get_the_author( '<li class="meta-author">', '</li>', 27, $post_object->ID );
							};

							// Date
							echo publisher_get_the_date( '<li class="meta-date">', '</li>', '', '<i class="fa fa-clock-o"></i>', '', '<i class="fa fa-calendar"></i>', '', $post_object->ID );

							// Comments Link
							echo publisher_get_comments_link( '<li class="meta-comment-link">', '</li>', '<i class="fa fa-comments-o"></i>', '', $post_object->ID );
						?>
					</ul><!-- .entry-meta -->

					<a class="entry-permalink" href="<?php echo get_permalink( $post_object->ID ); ?>"></a><!-- .entry-permalink -->
				</header><!-- .entry-header -->

			</div><!-- .entry-wrap -->

		</div><!-- .article-wrap -->
	</article><!-- .post-<?php echo esc_attr( $post_object->ID ); ?> -->

<?php
}
endif; // publisher_single_cover_pagination
