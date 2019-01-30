<?php
/**
 * The template for displaying single post content
 *
 * @package Publisher
 * @since Publisher 1.0
 */
?>

<article <?php post_class(); ?>>
	<div class="article-wrap">

		<?php
			/**
			 * @hooked publisher_single_above_entry_wrap - 10
			 */
			do_action( 'publisher_action_single_above_entry_wrap' );
		?>

		<div class="entry-wrap">
			<?php
				/**
				 * @hooked publisher_single_entry_header - 10
				 * @hooked publisher_single_entry_content - 20
				 * @hooked publisher_single_entry_footer - 30
				 */
				do_action( 'publisher_action_single_entry_wrap' );
			?>
		</div><!-- .entry-wrap -->

		<?php
			/**
			 * @hooked publisher_single_article_footer - 10
			 */
			 do_action( 'publisher_action_single_below_entry_wrap' );
		?>

	</div><!-- .article-wrap -->
</article><!-- .post-<?php the_ID(); ?> -->
