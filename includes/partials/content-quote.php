<?php
/**
 * The template for displaying posts in the Quote post format
 *
 * @package Publisher
 * @since Publisher 1.0
 */
?>

<article <?php post_class(); ?>>
	<div class="article-wrap">

		<?php
			/**
			 * @hooked publisher_index_quote_above_entry_wrap - 10
			 */
			do_action( 'publisher_action_index_quote_above_entry_wrap' );
		?>

		<div class="entry-wrap">
			<?php
				/**
				 * @hooked publisher_index_quote_entry_header - 10
				 * @hooked publisher_index_quote_entry_content - 20
				 */
				do_action( 'publisher_action_index_quote_entry_wrap' );
			?>
		</div><!-- .entry-wrap -->

		<?php do_action( 'publisher_action_index_quote_below_entry_wrap' ); ?>

	</div><!-- .article-wrap -->
</article><!-- .post-<?php the_ID(); ?> -->
