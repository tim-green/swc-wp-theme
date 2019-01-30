<?php
/**
 * The default template for displaying content
 *
 * @package Publisher
 * @since Publisher 1.0
 */

// Setup Post Loop Widget settings
if ( isset( $instance ) ) {
	$post_classes = ( 'hide' == $instance['post_format_icon'] ) ? 'hide-post-format-icon' : '';
} else {
	$instance = '';
	$post_classes = '';
}
?>

<article <?php post_class( $post_classes ); ?>>
	<div class="article-wrap">

		<?php
			/**
			 * @hooked publisher_index_above_entry_wrap - 10
			 */
			do_action( 'publisher_action_index_above_entry_wrap', $instance );
		?>

		<div class="entry-wrap">
			<?php
				/**
				 * @hooked publisher_index_entry_header - 10
				 * @hooked publisher_index_entry_content - 20
				 */
				do_action( 'publisher_action_index_entry_wrap', $instance );
			?>
		</div><!-- .entry-wrap -->

		<?php do_action( 'publisher_action_index_below_entry_wrap', $instance ); ?>

	</div><!-- .article-wrap -->
</article><!-- .post-<?php the_ID(); ?> -->
