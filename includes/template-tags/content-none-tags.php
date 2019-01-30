<?php
/**
 * Custom template tags specific to content-none.php
 *
 * @package Publisher
 */

/*-----------------------------------------------------------------------------------*/
/*	Do Action - publisher_action_404_entry_wrap
/*-----------------------------------------------------------------------------------*/

/**
 * 404 Entry Header
 *
 * @action publisher_action_404_entry_wrap
 */
if ( ! function_exists( 'publisher_404_entry_header' ) ) :
function publisher_404_entry_header() { ?>

	<header class="entry-header">
		<h1 class="entry-title"><?php _e( 'Sorry, Nothing Been Found', 'publisher' ); ?></h1><!-- .entry-title -->
	</header><!-- .entry-header -->

<?php
}
endif; // publisher_404_entry_header
add_action( 'publisher_action_404_entry_wrap', 'publisher_404_entry_header', 10 );


/**
 * 404 Entry Content
 *
 * @action publisher_action_404_entry_wrap
 */
if ( ! function_exists( 'publisher_404_entry_content' ) ) :
function publisher_404_entry_content() { ?>

	<div class="entry-content">
		<?php
			if ( is_home() && current_user_can( 'publish_posts' ) ) :
				$content = sprintf( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'publisher' ),
					admin_url( 'post-new.php' )
				);

			elseif ( is_search() ) :
				$content = __( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'publisher' );

			else :
				$content = __( 'It looks like nothing was found at this location. Try using the navigation menu or the search box to find what you were looking for.', 'publisher' );

			endif;

			// Output the content
			printf( '<p>%s</p>', $content );
		?>
	</div><!-- .entry-content -->

<?php
}
endif; // publisher_404_entry_content
add_action( 'publisher_action_404_entry_wrap', 'publisher_404_entry_content', 20 );


/**
 * 404 Entry Widgets
 *
 * @action publisher_action_404_entry_wrap
 */
if ( ! function_exists( 'publisher_404_entry_widgets' ) ) :
function publisher_404_entry_widgets() {

	if ( ! is_home() ) : ?>
		<div id="no-results-widgets" class="no-results-widgets widget-area">
			<?php the_widget( 'WP_Widget_Search' ); ?>

			<h2><?php echo('Below are some areas that could be useful');?></h2>
			<?php the_widget( 'WP_Widget_Recent_Posts' ); ?>

			<?php if ( publisher_has_categorized_blog() ) : // Only show the widget if site has multiple categories. ?>
				<div class="widget widget_categories">
					<h2 class="widgettitle"><?php _e( 'Most Used Categories', 'publisher' ); ?></h2>
					<ul>
					<?php
						wp_list_categories( array(
							'orderby'    => 'count',
							'order'      => 'DESC',
							'show_count' => 1,
							'title_li'   => '',
							'number'     => 10,
						) );
					?>
					</ul>
				</div>
			<?php endif; ?>

			<?php the_widget( 'WP_Widget_Archives', 'dropdown=1' ); ?>

			<?php
				if ( get_tags() ) {
					the_widget( 'WP_Widget_Tag_Cloud' );
				}
			?>
		</div><!-- .no-results-content -->
		<?php
	endif;

}
endif; // publisher_404_entry_widgets
add_action( 'publisher_action_404_entry_wrap', 'publisher_404_entry_widgets', 30 );
