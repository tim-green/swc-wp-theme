<?php
/**
 * Cover Posts
 *
 * @package Publisher
 * @since Publisher 1.0
 */
?>

<article <?php post_class( 'publisher-ext cover' ); ?>>
	<div class="article-wrap">

		<?php
			// Featured Image
			$image_size = ( 'default' != $instance['image_size'] ) ? $instance['image_size'] : publisher_get_the_image_size( 'cover', intval( $instance['post_columns'] ) );
			publisher_index_featured_image_background( $image_size );

			// Get the featured icon
			echo publisher_get_post_featured_icon( '<div class="post-flash-icon">', '</div>' );
		?>

		<div class="entry-wrap">
			<header class="entry-header">
				<h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2><!-- .entry-title -->

				<ul class="entry-meta header">
					<?php
						// Category
						if ( publisher_has_category() ) {
							echo publisher_get_the_categories( '<li class="meta-category">', '</li>' );
						};

						// Date
						echo publisher_get_the_date( '<li class="meta-date">', '</li>', '', '<i class="fa fa-clock-o"></i>', '', '<i class="fa fa-calendar"></i>' );

						// Comments Link
						echo publisher_get_comments_link( '<li class="meta-comment-link">', '</li>', '<i class="fa fa-comments-o"></i>' );
					?>
				</ul><!-- .entry-meta -->

				<a class="entry-permalink" href="<?php the_permalink(); ?>"></a><!-- .entry-permalink -->
			</header><!-- .entry-header -->
		</div><!-- .entry-wrap -->

	</div><!-- .article-wrap -->
</article><!-- .post-<?php the_ID(); ?> -->
