<?php
/**
 * The template for displaying posts in the Link post format
 *
 * @package Publisher
 * @since Publisher 1.0
 */

// Grab the first link in the post
$link = publisher_get_element( '/href\s*=\s*[\"\']([^\"\']+)/', get_the_content() , 1, 0 );
?>

<article <?php post_class(); ?>>
	<div class="article-wrap">

		<?php
			/**
			 * @hooked publisher_index_link_above_entry_wrap - 10
			 */
			do_action( 'publisher_action_index_link_above_entry_wrap' );
		?>

		<div class="entry-wrap">
			<?php
				/**
				 * @hooked publisher_index_link_entry_header - 10
				 * @hooked publisher_index_link_entry_content - 20
				 */
				do_action( 'publisher_action_index_link_entry_wrap', $link );
			?>
		</div><!-- .entry-wrap -->

		<?php do_action( 'publisher_action_index_link_below_entry_wrap' ); ?>

	</div><!-- .article-wrap -->
</article><!-- .post-<?php the_ID(); ?> -->
