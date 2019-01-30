<?php
/**
 * The default template for no content found
 *
 * @package Publisher
 * @since Publisher 1.0
 */
?>

<article id="post-none" class="post-none hentry">
	<div class="article-wrap">

		<?php do_action( 'publisher_action_404_above_entry_wrap' ); ?>

		<div class="entry-wrap">
			<?php
				/**
				 * @hooked publisher_404_entry_header - 10
				 * @hooked publisher_404_entry_content - 20
				 * @hooked publisher_404_entry_widgets - 30
				 */
				do_action( 'publisher_action_404_entry_wrap' );
			?>
		</div><!-- .entry-wrap -->

		<?php do_action( 'publisher_action_404_below_entry_wrap' ); ?>

	</div><!-- .article-wrap -->
</article><!-- #post-<?php the_ID(); ?> -->
