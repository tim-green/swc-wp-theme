<?php
/**
 * Listing Posts
 *
 * @package Publisher
 * @since Publisher 1.0
 */
?>

<article <?php post_class( 'publisher-ext listing' ); ?>>
	<div class="article-wrap">

		<?php
			// Featured Image
			if ( '' != get_the_post_thumbnail() ) { ?>
				<div class="featured-preview">
					<?php
						// Get the featured image
						$image_size = ( 'default' != $instance['image_size'] ) ? $instance['image_size'] : publisher_get_the_image_size( 'listing' );

						printf( '<div class="featured-image" style="%1$s">%2$s</div>',
							publisher_get_the_post_thumbnail( $image_size, 'background-image: url(\'', '\');', 'url' ),
							'<a href="' . get_the_permalink() . '" title="' . the_title_attribute( 'echo=0' ) . '"></a>'
						);
					?>
				</div><!-- .featured-preview -->
				<?php
			}
		?>

		<div class="entry-wrap">
			<header class="entry-header">
				<h3 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h3><!-- .entry-title -->

				<?php // if ( 'mini-listing' == $instance['posts_page_layout'] ) { ?>
					<ul class="entry-meta header">
						<?php
							// Date
							echo publisher_get_the_date( '<li class="meta-date">', '</li>', '', '<i class="fa fa-clock-o"></i>', '', '<i class="fa fa-calendar"></i>' );

							// Comments Link
							echo publisher_get_comments_link( '<li class="meta-comment-link">', '</li>', '<i class="fa fa-comments-o"></i>' );
						?>
					</ul><!-- .entry-meta -->
				<?php // } ?>

			</header><!-- .entry-header -->

			<?php
				/*
				<div class="entry-content">
					<?php
						if ( 'mini-listing' != $instance['posts_page_layout'] ) {
							echo publisher_get_the_excerpt( '<p>', '</p>', 'manual', 15, '...' );
						}
					?>
				</div><!-- .entry-content -->
				*/
			?>
		</div><!-- .entry-wrap -->

	</div><!-- .article-wrap -->
</article><!-- .post-<?php the_ID(); ?> -->
