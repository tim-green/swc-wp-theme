<?php
/**
 * Custom template tags specific to content-link.php
 *
 * @package Publisher
 */

/*-----------------------------------------------------------------------------------*/
/*	Do Action - publisher_action_index_link_above_entry_wrap
/*-----------------------------------------------------------------------------------*/

/**
 * Link Above Entry Wrap
 *
 * @action publisher_action_index_link_above_entry_wrap
 */
if ( ! function_exists( 'publisher_index_link_above_entry_wrap' ) ) :
function publisher_index_link_above_entry_wrap() {
	$image_size = publisher_get_the_image_size( 'standard' );
	publisher_index_featured_image_background( $image_size );
}
endif; // publisher_index_link_above_entry_wrap
add_action( 'publisher_action_index_link_above_entry_wrap', 'publisher_index_link_above_entry_wrap', 10 );




/*-----------------------------------------------------------------------------------*/
/*	Do Action - publisher_action_index_link_entry_wrap
/*-----------------------------------------------------------------------------------*/

/**
 * Link Entry Header
 *
 * @action publisher_action_index_link_entry_wrap
 */
if ( ! function_exists( 'publisher_index_link_entry_header' ) ) :
function publisher_index_link_entry_header( $link ) {

	$permalink = $link ? $link : get_the_permalink();
	?>

	<header class="entry-header">
		<h2 class="entry-title">
			<?php
				// Link Icon
				echo publisher_get_post_format_icon( '', '', '', true );
			?>

			<a href="<?php echo esc_url( $permalink ) ?>" rel="bookmark"><?php the_title(); ?></a>
		</h2><!-- .entry-title -->

		<ul class="entry-meta header">
			<?php
				// Date
				echo publisher_get_the_date( '<li class="meta-date">', '</li>', '', '<i class="fa fa-clock-o"></i>', '', '<i class="fa fa-calendar"></i>' );
			?>
		</ul><!-- .entry-meta -->
	</header><!-- .entry-header -->

<?php
}
endif; // publisher_index_link_entry_header
add_action( 'publisher_action_index_link_entry_wrap', 'publisher_index_link_entry_header', 10 );


/**
 * Link Entry Content
 *
 * @action publisher_action_index_link_entry_wrap
 */
if ( ! function_exists( 'publisher_index_link_entry_content' ) ) :
function publisher_index_link_entry_content( $link ) {

	if ( ! empty( $link ) ) { ?>
		<div class="entry-content">
			<?php
				printf( '<a class="post-link" href="%1$s" title="%2$s" rel="bookmark">%1$s</a>',
					esc_url( $link ),
					get_the_title()
				);
			?>
		</div><!-- .entry-content -->
		<?php
	}

}
endif; // publisher_index_link_entry_content
add_action( 'publisher_action_index_link_entry_wrap', 'publisher_index_link_entry_content', 20 );
