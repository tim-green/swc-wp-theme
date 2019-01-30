<?php
/**
 * Custom template tags specific to content-image.php
 *
 * @package Publisher
 */

/*-----------------------------------------------------------------------------------*/
/*	Do Action - publisher_action_index_image_entry_wrap
/*-----------------------------------------------------------------------------------*/

/**
 * Image Entry Header
 *
 * @action publisher_action_index_image_entry_wrap
 */
if ( ! function_exists( 'publisher_index_image_entry_header' ) ) :
function publisher_index_image_entry_header() { ?>

	<header class="entry-header">
		<h2 class="entry-title">
			<?php
				// Get categories
				if ( publisher_is_custom_post_type() ) {
					$cpt_cat_select = apply_filters( 'publisher_filter_get_the_category_select', get_option( 'publisher_options_' . get_post_type() . '_category_select' ) );
					$get_categories = ( '' != $cpt_cat_select ) ? get_the_terms( get_the_ID(), $cpt_cat_select ) : get_the_category();
				} else {
					$get_categories = get_the_category();
				}

				// Get taxonomy color
				$taxonomy_color = '';
				if ( '' != $get_categories ) {
					foreach( $get_categories as $get_category ) {
						$taxonomy_color = publisher_get_taxonomy_color( 'class="tax-color" style="background-color: ', ';"', $get_category->term_id, $get_category->taxonomy );

						if ( ! empty( $taxonomy_color ) )
							break;
					}
				}

				// Output title with tax color
				printf( '<a href="%1$s" %2$s rel="bookmark">%3$s</a>',
					get_the_permalink(),
					$taxonomy_color,
					get_the_title()
				);
			?>
		</h2><!-- .entry-title -->
	</header><!-- .entry-header -->

<?php
}
endif; // publisher_index_image_entry_header
add_action( 'publisher_action_index_image_entry_wrap', 'publisher_index_image_entry_header', 10 );


/**
 * Image Entry Content
 *
 * @action publisher_action_index_image_entry_wrap
 */
if ( ! function_exists( 'publisher_index_image_entry_content' ) ) :
function publisher_index_image_entry_content() { ?>

	<div class="entry-content">
		<?php
			// Prepare the featured image
			if ( '' != get_the_post_thumbnail() ) {
				$image_url = publisher_get_the_post_thumbnail( 'publisher-post-thumb-large', '', '', 'url' );
				$image_object = wp_prepare_attachment_for_js( publisher_get_attachment_id_from_url( $image_url ) );
			} else {
				// Grab the first image in the post
				$image_url = publisher_get_element( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', get_the_content() , 1, 0 );
				$image_object = wp_prepare_attachment_for_js( publisher_get_attachment_id_from_url( $image_url ) );
			};

			// Show the featured image
			if ( $image_url ) {
				printf( '<a href="%1$s" title="%2$s"><img src="%1$s"/></a>',
					esc_url( $image_url ),
					the_title_attribute( 'echo=0' )
				);
			};

			// Show Caption
			if ( '' != get_the_post_thumbnail() ) {
				// Grab the featured image
				$image_url = publisher_get_the_post_thumbnail( 'full', '', '', 'url' );
				$image_object = wp_prepare_attachment_for_js( publisher_get_attachment_id_from_url( $image_url ) );
				$caption = get_post( get_post_thumbnail_id() ) -> post_excerpt;
			} else {
				// Grab the first image in the post
				$image_url = publisher_get_element( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', get_the_content() , 1, 0 );
				$image_object = wp_prepare_attachment_for_js( publisher_get_attachment_id_from_url( $image_url ) );
				$caption = $image_object['caption'];
			};

			if ( $image_url && $caption ) {
				echo '<div class="caption"><p>' . $caption . '</p></div>';
			};
		?>
	</div><!-- .entry-content -->

<?php
}
endif; // publisher_index_image_entry_content
add_action( 'publisher_action_index_image_entry_wrap', 'publisher_index_image_entry_content', 20 );
