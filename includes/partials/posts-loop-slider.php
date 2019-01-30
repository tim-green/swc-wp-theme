<?php
/**
 * Slider Posts
 *
 * @package Publisher
 * @since Publisher 1.0
 */

if ( ! isset( $i ) ) $i == 0;
if ( ! isset( $post_columns ) ) $post_columns == 0;
?>

<article <?php post_class( 'publisher-ext cover slide-' . ( $i % $post_columns ) ); ?>>
	<div class="article-wrap">

		<?php
			// Featured Image
			$get_image_size = ( 'default' != $image_size ) ? $image_size : publisher_get_the_image_size( 'slider', $post_columns, ( $i % $post_columns ) );
			publisher_index_featured_image_background( $get_image_size );

			// Get the featured icon
			echo publisher_get_post_featured_icon( '<div class="post-flash-icon">', '</div>' );
		?>

		<?php echo ( 1 == $post_columns ) ? '<div class="container">' : ''; ?>

			<div class="entry-wrap">
				<header class="entry-header">
					<h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2><!-- .entry-title -->

					<ul class="entry-meta header">
						<?php
							// Category
							if ( publisher_has_category() ) {
								echo publisher_get_the_categories( '<li class="meta-category">', '</li>' );
							};

							// Author
							echo publisher_get_the_author( '<li class="meta-author">', '</li>', 27, get_the_ID() );

							// Date
							echo publisher_get_the_date( '<li class="meta-date">', '</li>', '', '<i class="fa fa-clock-o"></i>', '', '<i class="fa fa-calendar"></i>' );

							// Comments Link
							echo publisher_get_comments_link( '<li class="meta-comment-link">', '</li>', '<i class="fa fa-comments-o"></i>' );
						?>
					</ul><!-- .entry-meta -->

					<a class="entry-permalink" href="<?php the_permalink(); ?>"></a><!-- .entry-permalink -->
				</header><!-- .entry-header -->
			</div><!-- .entry-wrap -->

		<?php echo ( 1 == $post_columns ) ? '</div><!-- .container -->' : ''; ?>

	</div><!-- .article-wrap -->
</article><!-- .post-<?php the_ID(); ?> -->
