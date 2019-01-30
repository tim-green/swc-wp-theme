<?php
/**
 * Custom template tags specific to content-quote.php
 *
 * @package Publisher
 */

/*-----------------------------------------------------------------------------------*/
/*	Do Action - publisher_action_index_quote_above_entry_wrap
/*-----------------------------------------------------------------------------------*/

/**
 * Quote Above Entry Wrap
 *
 * @action publisher_action_index_quote_above_entry_wrap
 */
if ( ! function_exists( 'publisher_index_quote_above_entry_wrap' ) ) :
function publisher_index_quote_above_entry_wrap() {
	$image_size = publisher_get_the_image_size( 'standard' );
	publisher_index_featured_image_background( $image_size );
}
endif; // publisher_index_link_above_entry_wrap
add_action( 'publisher_action_index_quote_above_entry_wrap', 'publisher_index_quote_above_entry_wrap', 10 );




/*-----------------------------------------------------------------------------------*/
/*	Do Action - publisher_action_index_quote_entry_wrap
/*-----------------------------------------------------------------------------------*/

/**
 * Quote Entry Header
 *
 * @action publisher_action_index_image_entry_wrap
 */
if ( ! function_exists( 'publisher_index_quote_entry_header' ) ) :
function publisher_index_quote_entry_header() { ?>

	<header class="entry-header">
		<h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2><!-- .entry-title -->

		<ul class="entry-meta header">
			<?php
				// Date
				echo publisher_get_the_date( '<li class="meta-date">', '</li>', '', '<i class="fa fa-clock-o"></i>', '', '<i class="fa fa-calendar"></i>' );

				// Quote Icon
				echo publisher_get_post_format_icon( '<li class="meta-quote-info">', '</li>', '', true );
			?>
		</ul><!-- .entry-meta -->
	</header><!-- .entry-header -->

<?php
}
endif; // publisher_index_quote_entry_header
add_action( 'publisher_action_index_quote_entry_wrap', 'publisher_index_quote_entry_header', 10 );


/**
 * Quote Entry Content
 *
 * @action publisher_action_index_quote_entry_wrap
 */
if ( ! function_exists( 'publisher_index_quote_entry_content' ) ) :
function publisher_index_quote_entry_content() { ?>

	<div class="entry-content">
		<?php
			// Grab the first blockquote in the post
			$blockquote = publisher_get_element( '~<blockquote>(.*?)</blockquote>~is', get_the_content() , 0, 0 );

			if ( $blockquote ) {
				echo $blockquote;
			} else {
				echo '<blockquote>' . get_the_excerpt() . '</blockquote>';
			};
		?>
	</div><!-- .entry-content -->

<?php
}
endif; // publisher_index_quote_entry_content
add_action( 'publisher_action_index_quote_entry_wrap', 'publisher_index_quote_entry_content', 20 );
