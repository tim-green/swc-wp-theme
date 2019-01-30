<?php
/**
 * The template for displaying page content
 *
 * @package Publisher
 * @since Publisher 1.0
 */
?>

<article id="page-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="article-wrap">

		<?php
			/**
			 * @hooked publisher_page_above_entry_wrap - 10
			 */
			do_action( 'publisher_action_page_above_entry_wrap' );
		?>

		<div class="entry-wrap">
			<?php
				/**
				 * @hooked publisher_page_entry_header - 10
				 * @hooked publisher_page_entry_content - 20
				 */
				do_action( 'publisher_action_page_entry_wrap' );
			?>
		</div><!-- .entry-wrap -->

		<?php
			/**
			 * @hooked publisher_page_below_entry_wrap - 10
			 */
			 do_action( 'publisher_action_page_below_entry_wrap' );
		?>

	</div><!-- .article-wrap -->
</article><!-- #page-<?php the_ID(); ?> -->
