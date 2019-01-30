<?php
/**
 * The template for displaying posts in the Image post format
 *
 * @package Publisher
 * @since Publisher 1.0
 */
?>

<article <?php post_class(); ?>>
	<div class="article-wrap">

		<?php do_action( 'publisher_action_index_image_above_entry_wrap' ); ?>

		<div class="entry-wrap">
			<?php
				/**
				 * @hooked publisher_index_image_entry_header - 10
				 * @hooked publisher_index_image_entry_content - 20
				 */
				do_action( 'publisher_action_index_image_entry_wrap' );
			?>
		</div><!-- .entry-wrap -->

		<?php do_action( 'publisher_action_index_image_below_entry_wrap' ); ?>

	</div><!-- .article-wrap -->
</article><!-- .post-<?php the_ID(); ?> -->
