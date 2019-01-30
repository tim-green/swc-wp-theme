<?php
/**
 * Custom template tags specific to content.php
 *
 * @package Publisher
 */

/*-----------------------------------------------------------------------------------*/
/*	Do Action - publisher_action_index_above_entry_wrap
/*-----------------------------------------------------------------------------------*/

/**
 * Above Entry Wrap
 *
 * @action publisher_action_index_above_entry_wrap
 */
if ( ! function_exists( 'publisher_index_above_entry_wrap' ) ) :
function publisher_index_above_entry_wrap( $instance = '' ) {

	// Get Featured Preview
	publisher_index_featured_preview( $instance );

}
endif; // publisher_index_above_entry_wrap
add_action( 'publisher_action_index_above_entry_wrap', 'publisher_index_above_entry_wrap', 10 );




/*-----------------------------------------------------------------------------------*/
/*	Do Action - publisher_action_index_entry_wrap
/*-----------------------------------------------------------------------------------*/

/**
 * Entry Header
 *
 * @action publisher_action_index_entry_wrap
 */
if ( ! function_exists( 'publisher_index_entry_header' ) ) :
function publisher_index_entry_header() { ?>

	<header class="entry-header">
		<h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2><!-- .entry-title -->

		<ul class="entry-meta header">
			<?php
				// Category
				if ( publisher_has_category() ) {
					echo publisher_get_the_categories( '<li class="meta-category">', '</li>' );
				};

				// Date
				echo publisher_get_the_date( '<li class="meta-date">', '</li>', '', '<i class="fa fa-clock-o"></i>', '', '<i class="fa fa-calendar"></i>' );

				// Comments Link
				echo publisher_get_comments_link( '<li class="meta-comment-link">', '</li>', '<i class="fa fa-comments-o"></i>' );
			?>
		</ul><!-- .entry-meta -->
	</header><!-- .entry-header -->

<?php
}
endif; // publisher_index_entry_header
add_action( 'publisher_action_index_entry_wrap', 'publisher_index_entry_header', 10 );


/**
 * Entry Content
 *
 * @action publisher_action_index_entry_wrap
 */
if ( ! function_exists( 'publisher_index_entry_content' ) ) :
function publisher_index_entry_content( $instance = '' ) {

	// Show Content
	if ( isset( $instance['post_content'] ) && '' != $instance['post_content'] ) {
		$display_content = $instance['post_content'];
	} else {
		$display_content = publisher_cpt_options() && ! is_search() ? get_option( 'publisher_options_' . get_post_type() . '_show_content' ) : get_option( 'publisher_options_posts_show_content' );
	}
	?>

	<div class="entry-content">
		<?php
			if ( 'full' == $display_content ) {
				the_content();
			} elseif ( 'none' == $display_content ) {

			} else {
				echo publisher_get_the_excerpt( '<p>', '</p>' );
			}
		?>
	</div><!-- .entry-content -->

<?php
}
endif; // publisher_index_entry_content
add_action( 'publisher_action_index_entry_wrap', 'publisher_index_entry_content', 20 );




/*-----------------------------------------------------------------------------------*/
/*	Content Tags
/*-----------------------------------------------------------------------------------*/

/**
 * Featured Preview
 */
if ( ! function_exists( 'publisher_index_featured_preview' ) ) :
function publisher_index_featured_preview( $instance = '' ) {

	// Show Featured Preview
	$featured_preview = get_post_meta( get_the_ID(), 'publisher_featured_preview_meta', true );
	$index_featured_preview = get_post_meta( get_the_ID(), 'publisher_show_index_meta', true );

	if ( $featured_preview && $index_featured_preview ) {
		printf( '<div class="featured-preview">%1$s</div>',
			! wp_oembed_get( $featured_preview ) ? do_shortcode( $featured_preview ) : wp_oembed_get( $featured_preview )
		);
	} else {
		// Featured Image
		if ( '' != get_the_post_thumbnail() ) {

			// Get taxonomy color
			$taxonomy_color = '';
			if ( publisher_is_custom_post_type() ) {
				$cpt_cat_select = apply_filters( 'publisher_filter_get_the_category_select', get_option( 'publisher_options_' . get_post_type() . '_category_select' ) );
				$get_categories = ( '' != $cpt_cat_select ) ? get_the_terms( get_the_ID(), $cpt_cat_select ) : get_the_category( get_the_ID() );
			} else {
				$get_categories = get_the_category( get_the_ID() );
			}

			if ( '' != $get_categories ) {
				foreach( $get_categories as $get_category ) {
					$taxonomy_color = publisher_get_taxonomy_color( 'style="background-color: ', ';"', esc_attr( $get_category->term_id ), esc_attr( $get_category->taxonomy ) );

					if ( ! empty( $taxonomy_color ) )
						break;
				}
			}
			?>

			<div class="featured-preview" <?php echo $taxonomy_color ?>>
				<?php
					// Get posts thumb size
					if ( isset( $instance['image_size'] ) && 'default' != $instance['image_size'] ) {

						// Override if using Posts Loop Widget
						$image_size = $instance['image_size'];

					} else {
						if ( isset( $instance['posts_page_layout'] ) && '' != $instance['posts_page_layout'] ) {
							$image_size = publisher_get_the_image_size( $instance['posts_page_layout'], $instance['post_columns'] );
						} else {
							$image_size = publisher_get_the_image_size();
						}
					}

					// Get the featured image
					printf( '<div class="featured-image" style="%1$s">%2$s</div>',
						publisher_get_the_post_thumbnail( $image_size, 'background-image: url(\'', '\');', 'url' ),
						'<a href="' . get_the_permalink() . '" title="' . the_title_attribute( 'echo=0' ) . '"></a>'
					);

					// Get the icon
					echo publisher_get_post_format_icon( '<div class="post-format-icon">', '</div>', '', true );

					// Get the featured icon
					echo publisher_get_post_featured_icon( '<div class="post-flash-icon">', '</div>' );
				?>
			</div><!-- .featured-preview -->

		<?php
		}
	}

}
endif; // publisher_index_featured_preview


/**
 * Featured Image Background
 */
if ( ! function_exists( 'publisher_index_featured_image_background' ) ) :
function publisher_index_featured_image_background( $image_size = 'publisher-post-thumb-standard', $post_id = '' ) {

	if ( empty( $post_id ) ) $post_id = get_the_ID();

	if ( '' != get_the_post_thumbnail( $post_id ) || ( 'attachment' == get_post_type() && wp_attachment_is_image() ) ) {
		printf( '<div class="featured-image-wrap"><div class="featured-image-background" style="%1$s"><div class="overlay"></div></div></div>',
			publisher_get_the_post_thumbnail( $image_size, 'background-image: url(', ');', 'url', $post_id )
		);
	}

}
endif; // publisher_index_featured_image_background
