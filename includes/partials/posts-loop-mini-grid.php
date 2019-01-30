<?php
/**
 * Mini Grid Posts
 *
 * @package Publisher
 * @since Publisher 1.0
 */
?>

<article <?php post_class( 'publisher-ext mini-grid' ); ?>>
	<div class="article-wrap">

		<div class="featured-preview">
			<ul class="entry-meta header">
				<?php
					// Category
					if ( publisher_has_category() ) {
						echo publisher_get_the_categories( '<li class="meta-category">', '</li>' );
					};
				?>
			</ul><!-- .entry-meta -->

			<?php
				// Featured Image
				if ( '' != get_the_post_thumbnail() ) {
					// Get the featured image
					$image_size = ( 'default' != $instance['image_size'] ) ? $instance['image_size'] : publisher_get_the_image_size( 'mini-grid', intval( $instance['post_columns'] ) );

					printf( '<div class="featured-image" style="%1$s">%2$s</div>',
						publisher_get_the_post_thumbnail( $image_size, 'background-image: url(\'', '\');', 'url' ),
						'<a href="' . get_the_permalink() . '" title="' . the_title_attribute( 'echo=0' ) . '"></a>'
					);
				}
			?>
		</div><!-- .featured-preview -->

		<div class="entry-wrap equalize">
			<header class="entry-header">
				<h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2><!-- .entry-title -->
			</header><!-- .entry-header -->
		</div><!-- .entry-wrap -->

	</div><!-- .article-wrap -->
</article><!-- .post-<?php the_ID(); ?> -->
