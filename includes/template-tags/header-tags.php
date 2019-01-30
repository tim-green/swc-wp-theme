<?php
/**
 * Custom template tags specific to header.php
 *
 * @package Publisher
 * @since Publisher 1.0
 */

/*-----------------------------------------------------------------------------------*/
/*	Do Action - publisher_action_above_site_header
/*-----------------------------------------------------------------------------------*/

/**
 * Media Query
 *
 * @action publisher_action_above_site_header
 */
if ( ! function_exists( 'publisher_media_query' ) ) :
function publisher_media_query() {
	echo '<div id="media-query"></div>';
}
endif; // publisher_media_query
add_action( 'publisher_action_above_site_header', 'publisher_media_query', 10 );


/**
 * Call To Action
 */
if ( ! function_exists( 'publisher_call_to_action' ) ) :
function publisher_call_to_action() {
	$cta_text = sprintf( _x( '%s', 'CTA Translation Text', 'publisher' ), get_option( 'publisher_options_cta_text' ) );
	$cta_button_text = sprintf( _x( '%s', 'CTA Translation Button Text', 'publisher' ), get_option( 'publisher_options_cta_button_text' ) );
	$cta_button_url = get_option( 'publisher_options_cta_button_url' );
	$cta_image = get_theme_mod( 'publisher_options_cta_image' );
	$cta_image_style = get_option( 'publisher_options_cta_image_style', 'full-cover' );

	if ( $cta_text ) {
		printf( '<div id="call-to-action" class="%1$s" %2$s><a href="%3$s"><span class="container"><span class="cta-text">%4$s</span>%5$s</span></a></div><!-- #call-to-action -->',
			$cta_image ? $cta_image_style : 'cta-no-image',
			$cta_image ? 'style="background-image: url(\'' . esc_url( $cta_image ) . '\');"' : '',
			$cta_button_url ? esc_url( $cta_button_url ) : '',
			esc_attr( $cta_text ),
			! empty( $cta_button_text ) ? '<span class="cta-button">' . esc_attr( $cta_button_text ) . '</span>' : ''
		);
	}
}
endif; // publisher_call_to_action
add_action( 'publisher_action_above_site_header', 'publisher_call_to_action', 20 );


/**
 * Headband
 */
if ( ! function_exists( 'publisher_headband' ) ) :
function publisher_headband() {
	if ( has_nav_menu( 'publisher-header-left' ) || has_nav_menu( 'publisher-header-right' ) || get_option( 'publisher_options_news_ticker' ) ) { ?>

		<div id="headband" class="headband widget-area mini-widget-area">
			<div class="container">
				<?php if ( function_exists( 'ditty_news_ticker' ) && get_option( 'publisher_options_news_ticker' ) ) {
					echo do_shortcode( get_option( 'publisher_options_news_ticker' ) );
				} elseif ( has_nav_menu( 'publisher-header-left' ) ) { ?>
					<nav class="headband-navigation mini-widget-area left">
						<?php
							wp_nav_menu( array(
								'theme_location' => 'publisher-header-left',
								'link_before' => '<span class="title">',
								'link_after' => '</span>',
								'fallback_cb' => false
							) );
						?>
					</nav><!-- .headband-navigation -->
				<?php } ?>

				<?php if ( has_nav_menu( 'publisher-header-right' ) ) { ?>
					<nav class="headband-navigation mini-widget-area right">
						<?php
							wp_nav_menu( array(
								'theme_location' => 'publisher-header-right',
								'link_before' => '<span class="title">',
								'link_after' => '</span>',
								'fallback_cb' => false
							) );
						?>
					</nav><!-- .headband-navigation -->
				<?php } ?>
			</div><!-- .container -->
		</div><!-- .headband -->

	<?php
	}
}
endif; // publisher_headband
add_action( 'publisher_action_above_site_header', 'publisher_headband', 30 );




/*-----------------------------------------------------------------------------------*/
/*	Do Action - publisher_action_site_header
/*-----------------------------------------------------------------------------------*/

/**
 * Header
 *
 * @action publisher_action_site_header
 */
if ( ! function_exists( 'publisher_site_header' ) ) :
function publisher_site_header() {

	// Get Header Options
	$header_style =  apply_filters( 'publisher_filter_site_header', 'publisher_header_style_1' );

	// Call the user function selected in the theme options: publisher_header_style_##
	if ( function_exists( $header_style ) ) {
		call_user_func( $header_style );
	} else {
		publisher_header_style_1();
	}

}
endif; // publisher_site_header
add_action( 'publisher_action_site_header', 'publisher_site_header', 10 );




/*-----------------------------------------------------------------------------------*/
/*	Do Action - publisher_action_above_site_content
/*-----------------------------------------------------------------------------------*/

/**
 * Featured Header
 *
 * @action publisher_action_above_site_content
 */
if ( ! function_exists( 'publisher_featured_header' ) ) :
function publisher_featured_header() {

	if ( is_page_template( 'template-pagebuilder.php' ) ) {
		$post_layout_type = 'pagebuilder';

	} elseif ( is_front_page() ) {
		$post_layout_type = 'front_page';

	} elseif ( is_attachment() ) {
		$post_layout_type = 'attachment';

	} elseif ( is_page() || is_single() ) {

		$post_layout_meta = get_post_meta( get_the_ID(), 'publisher_post_layout_meta', true );
		$post_layout_option = publisher_cpt_options() ? get_option( 'publisher_options_' . get_post_type() . '_post_layout' ) : get_option( 'publisher_options_post_layout', 'standard' );
		$page_layout_option = get_option( 'publisher_options_page_layout', 'wide' );
		$post_layout_select = is_page() ? $page_layout_option : $post_layout_option;
		$post_layout_type = $post_layout_meta ? $post_layout_meta : $post_layout_select;

	} elseif ( is_search() || is_archive() || is_404() ) {
		$post_layout_type = 'archive';

	} else {
		$post_layout_type = 'standard';

	}

	// Get Featured Header
	$featured_header = apply_filters( 'publisher_filter_featured_header', 'publisher_' . $post_layout_type . '_featured_header' );

	if ( function_exists( $featured_header ) ) {
		call_user_func( $featured_header );
	}

}
endif; // publisher_featured_header
add_action( 'publisher_action_above_site_content', 'publisher_featured_header', 10 );




/*-----------------------------------------------------------------------------------*/
/*	Header Style
/*-----------------------------------------------------------------------------------*/

/**
 * Header Style 1
 */
if ( ! function_exists( 'publisher_header_style_1' ) ) :
function publisher_header_style_1() { ?>

	<div class="site-header-content">

		<?php publisher_header_image() ?>

		<div class="site-header-content-wrap">
			<div class="container">

				<?php
					publisher_site_branding();
					publisher_header_extras();
				?>

			</div><!-- .container -->
		</div><!-- .site-header-content-wrap -->

		<div class="site-header-content-inside">
			<div class="site-navbar">
				<div class="container">
					<?php
						if ( has_nav_menu( 'publisher-menu-primary' ) ) {
							publisher_site_navbar();
						} else {
							publisher_site_navbar( '<nav id="site-navigation" class="main-navigation page-menu"><div class="mobile-menu"><div class="menu-toggle"></div></div>', '</nav>' );
						}
					?>
				</div><!-- .container -->
			</div><!-- .site-navbar -->
		</div><!-- .site-header-content-inside -->

	</div><!-- .site-header-content -->

<?php
}
endif; // publisher_header_style_1




/*-----------------------------------------------------------------------------------*/
/*	Featured Header
/*-----------------------------------------------------------------------------------*/

/**
 * Full Preview Featured Header
 */
if ( ! function_exists( 'publisher_full_preview_featured_header' ) ) :
function publisher_full_preview_featured_header() { ?>

	<div id="featured-header" class="<?php publisher_featured_header_classes( 'site-featured-header' ) ?>">
		<?php
			$featured_preview = get_post_meta( get_the_ID(), 'publisher_featured_preview_meta', true );

			printf( '<div class="featured-preview">%1$s</div>',
				! wp_oembed_get( $featured_preview ) ? do_shortcode( $featured_preview ) : wp_oembed_get( $featured_preview )
			);
		?>
	</div><!-- #featured-header -->

<?php
}
endif; // publisher_full_preview_featured_header


/**
 * Cover Featured Header
 */
if ( ! function_exists( 'publisher_cover_featured_header' ) ) :
function publisher_cover_featured_header() {

	$image = publisher_get_featured_header_image();
	$featured_preview = get_post_meta( get_the_ID(), 'publisher_featured_preview_meta', true );

	if ( '' != $image || '' != $featured_preview || '' != get_post_gallery() ) { ?>

		<div id="featured-header" class="<?php publisher_featured_header_classes( 'site-featured-header' ) ?>">
			<div class="container">
				<?php
					if ( $featured_preview ) {
						// Featured Preview
						printf( '<div class="featured-preview">%1$s</div>',
							! wp_oembed_get( $featured_preview ) ? do_shortcode( $featured_preview ) : wp_oembed_get( $featured_preview )
						);

					} else {
						// Featured Gallery
						if ( has_post_format( 'gallery' ) && get_post_gallery() ) {

							echo publisher_get_post_gallery( array(
								'before' => '<div class="featured-preview owl-gallery">',
								'after' => '</div><!-- .featured-preview -->',
								'loader' => false,
								'pager' => true
							) );

						} else {
							// Featured Image
							printf( '<div class="site-featured-header-image" style="%1$s"></div><!-- .site-featured-header-image -->',
								'background-image: url( '. $image . ' );'
							);

						}

					}
				?>
			</div><!-- .container -->
		</div><!-- #featured-header -->

	<?php
	}

}
endif; // publisher_cover_featured_header


/**
 * Wide Featured Header
 */
if ( ! function_exists( 'publisher_wide_featured_header' ) ) :
function publisher_wide_featured_header() { ?>

	<div id="featured-header" class="<?php publisher_featured_header_classes( 'site-featured-header' ) ?>">
		<?php
			// Featured Gallery
			if ( has_post_format( 'gallery' ) && get_post_gallery() ) { ?>

				<div class="featured-preview owl-gallery">
					<?php echo publisher_get_loader_icon(); ?>

					<div class="owl-carousel">
						<?php
							$i = 0;
							$gallery = get_post_gallery( get_the_ID(), false );
							foreach( $gallery['src'] as $item ) {
								$image_object = wp_prepare_attachment_for_js( publisher_get_attachment_id_from_url( $item ) );

								printf( '<div id="gallery-%1$s" class="site-featured-header-image gallery-item-%2$s" style="%3$s" data-dot=""><div class="overlay"></div></div>',
									$image_object['id'],
									$i,
									'background-image: url( '. $image_object['url'] . ' );'
								);
								$i++;
							}
						?>
					</div><!-- .owl-carousel -->
				</div><!-- .featured-preview -->
				<?php

			} else {
				// Featured Image
				$image = publisher_get_featured_header_image();

				if ( '' != $image ) {
					printf( '<div class="site-featured-header-image" style="%1$s"><div class="overlay"></div></div><!-- .site-featured-header-image -->',
						'background-image: url( '. $image . ' );'
					);
				}

			}
		?>

		<div class="container">
			<header class="hero-header">
				<h1 class="hero-title"><?php the_title(); ?></h1><!-- .hero-title -->

				<?php
					// Subtitles
					if ( '' != get_the_archive_description() ) {
						printf( '<div class="hero-description">%1$s</div>', get_the_archive_description() );
					}
				?>

				<?php if ( is_single() ) { ?>
					<ul class="entry-meta hero">
						<?php
							// Category
							if ( publisher_has_category() ) {
								echo publisher_get_the_categories( '<li class="meta-category">', '</li>' );
							};

							// Author
							echo publisher_get_the_author( '<li class="meta-author">', '</li>', 27, get_the_id() );

							// Date
							echo publisher_get_the_date( '<li class="meta-date">', '</li>', '', '<i class="fa fa-clock-o"></i>', '', '<i class="fa fa-calendar"></i>' );

							// Comments Link
							echo publisher_get_comments_link( '<li class="meta-comment-link">', '</li>', '<i class="fa fa-comments-o"></i>' );
						?>
					</ul><!-- .entry-meta -->
				<?php } ?>

			</header><!-- .hero-header -->
		</div><!-- .container -->
	</div><!-- #featured-header -->

<?php
}
endif; // publisher_wide_featured_header


/**
 * Archive Featured Header
 */
if ( ! function_exists( 'publisher_archive_featured_header' ) ) :
function publisher_archive_featured_header() { ?>

	<div id="featured-header" class="<?php publisher_featured_header_classes( 'site-featured-header' ) ?>">
		<?php
			// Featured Image
			$image = publisher_get_featured_header_image();

			if ( '' != $image ) {
				printf( '<div class="site-featured-header-image" style="%1$s"><div class="overlay"></div></div><!-- .site-featured-header-image -->',
					'background-image: url( '. $image . ' );'
				);
			}
		?>

		<div class="container">
			<header class="hero-header">
				<?php
					// Archive Title
					if ( '' != get_the_archive_title() ) {
						echo '<h1 class="hero-title">' . get_the_archive_title() . '</h1>';
					}

					// Archive Description
					if ( '' != get_the_archive_description() ) {
						echo '<div class="hero-description">' . get_the_archive_description() . '</div>';
					}
				?>
			</header><!-- .hero-header -->

			<?php
				// Archive Author Avatar
				if ( is_author() ) {
					$author_card_image = get_the_author_meta( 'author_card_image' );

					if ( '' != $author_card_image ) {
						$thumbnail = wp_get_attachment_image_src( attachment_url_to_postid( $author_card_image ), 'thumbnail' );

						printf( '<div class="author-avatar"><img src="%1$s" class="avatar" /></div>',
							esc_url( $thumbnail[0] )
						);
					} else {
						printf( '<div class="author-avatar">%1$s</div>',
							get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'publisher_filter_author_bio_avatar_size', 120 ) )
						);
					}
				}
			?>
		</div><!-- .container -->
	</div><!-- #featured-header -->

	<?php
		// Archive Child Category Links
		if ( is_category() || is_tag() || is_tax() ) {

			$term_id = get_queried_object()->term_id;
			$taxonomy_name = get_queried_object()->taxonomy;

			if ( '' != $term_id && '' != $taxonomy_name ) {
				$termchildren = get_term_children( $term_id, $taxonomy_name );

				if ( $termchildren ) { ?>
					<nav id="archive-navigation" class="archive-navigation clearfix">
						<h1 class="screen-reader-text"><?php _e( 'Archive navigation', 'publisher' ); ?></h1>

						<div class="container">
							<ul class="sub-categories clearfix">
								<?php
									foreach ( $termchildren as $child ) {
										$term = get_term_by( 'id', $child, $taxonomy_name );
										printf( '<li><a href="%1$s">%2$s</a></li>',
											get_term_link( $child, $taxonomy_name ),
											$term->name
										);
									}
								?>
							</ul>
						</div><!-- .container -->
					</nav><!-- .archive-navigation -->
					<?php
				}
			}

		}
	?>

<?php
}
endif; // publisher_archive_featured_header


/**
 * Front Page Featured Header
 */
if ( ! function_exists( 'publisher_front_page_featured_header' ) ) :
function publisher_front_page_featured_header() {

	$front_page_button_text = get_option( 'publisher_options_front_page_button_text' );
	$front_page_button_url = esc_url( get_option( 'publisher_options_front_page_button_url' ) );
	$front_page_shortcode = get_option( 'publisher_options_front_page_shortcode' );

	if ( '' != get_the_archive_title() || '' != get_the_archive_description() || $front_page_button_text || $front_page_shortcode ) { ?>

		<div id="featured-header" class="<?php publisher_featured_header_classes( 'site-featured-header' ) ?>">
			<?php
				// Featured Image
				$image = publisher_get_featured_header_image();
				$keep_header = get_theme_mod( 'publisher_options_front_page_keep_header' );

				if ( '' != $image && ( empty( $front_page_shortcode ) || ( '' != $front_page_shortcode && '' != $keep_header ) ) ) {
					printf( '<div class="site-featured-header-image" style="%1$s"><div class="overlay"></div></div><!-- .site-featured-header-image -->',
						'background-image: url( '. $image . ' );'
					);
				}
			?>

			<div class="container">
				<?php
					if ( $front_page_shortcode ) {
						echo do_shortcode( $front_page_shortcode );

					} else { ?>
						<header class="hero-header">
							<?php
								// Front Page Title
								if ( '' != get_the_archive_title() ) {
									echo '<h1 class="hero-title">' . get_the_archive_title() . '</h1>';
								}

								// Front Page Description
								if ( '' != get_the_archive_description() ) {
									echo '<div class="hero-description">' . get_the_archive_description() . '</div>';
								}

								// Front Page Button
								if ( $front_page_button_text ) {
									printf( '<a class="hero-link button" href="%1$s">%2$s</a>',
										$front_page_button_url ? $front_page_button_url : '#',
										$front_page_button_text
									);
								}
							?>
						</header><!-- .hero-header -->
						<?php
					}
				?>
			</div><!-- .container -->
		</div><!-- #featured-header -->

	<?php
	}

}
endif; // publisher_front_page_featured_header




/*-----------------------------------------------------------------------------------*/
/*	Template Tags
/*-----------------------------------------------------------------------------------*/

/**
 * Site Branding
 */
if ( ! function_exists( 'publisher_site_branding' ) ) :
function publisher_site_branding() { ?>
	<header id="masthead" class="nameplate">
		<?php if ( get_theme_mod( 'publisher_options_logo' ) ) {
			$publisher_logo = sprintf( _x( '%s', 'Logo Image: http image link to the site logo', 'publisher' ), get_theme_mod( 'publisher_options_logo' ) );
			?>

			<h1 class="logo-image">
				<a href="<?php echo home_url( '/' ); ?>">
					<img src="<?php echo esc_url( $publisher_logo );?>" alt="<?php the_title_attribute(); ?>" />
					<h1 class="site-title"><?php bloginfo( 'name' ); ?></h1>
				</a>
			</h1>
		<?php } else { ?>
			<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
		<?php } ?>

		<?php if ( ! get_theme_mod( 'publisher_options_hide_tagline' ) ) { ?>
			<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
		<?php } ?>
	</header><!-- .nameplate -->
<?php
}
endif; // publisher_site_branding


/**
 * Header Extras
 */
if ( ! function_exists( 'publisher_header_extras' ) ) :
function publisher_header_extras() {

	$leaderboard_image = get_theme_mod( 'publisher_options_leaderboard_image' );

	if ( $leaderboard_image ) {
		$leaderboard_title = get_option( 'publisher_options_leaderboard_title' );
		$leaderboard_url = get_option( 'publisher_options_leaderboard_url' );

		printf( '<div id="header-extras" class="header-extras"><a class="leaderboard-banner" href="%1$s"><img src="%2$s" title="%3$s" alt="%3$s"/></a></div><!-- .header-extras -->',
			$leaderboard_url ? esc_url( $leaderboard_url ) : '',
			esc_url( $leaderboard_image ),
			$leaderboard_title ? esc_attr( $leaderboard_title ) : __( 'Leaderboard', 'publisher' )
		);
	}

}
endif; // publisher_header_extras


/**
 * Header Image
 */
if ( ! function_exists( 'publisher_header_image' ) ):
function publisher_header_image() {
    $header_image = get_header_image();
	$header_opacity = esc_attr( get_theme_mod( 'publisher_options_header_image_opacity', '1' ) );

	if ( ! empty( $header_image ) ) {
		printf( '<div class="site-header-image site-background-image" style="background-image: url(%1$s); opacity: %2$s;"></div>',
			$header_image,
			$header_opacity
		);
	}
}
endif; // publisher_header_image


/**
 * Site Navbar
 */
if ( ! function_exists( 'publisher_site_navbar' ) ) :
function publisher_site_navbar( $before = '', $after = '' ) {
	echo $before;
	wp_nav_menu( apply_filters( 'publisher_filter_wp_nav_menu_args', array(
		'theme_location' 	=> 'publisher-menu-primary',
		'container'			=> 'nav',
		'container_class' 	=> 'main-navigation',
		'container_id' 		=> 'site-navigation',
		'menu_class' 		=> 'menu clearfix',
		'link_before'     	=> '<span class="title">',
		'link_after'      	=> '</span>',
		'items_wrap' 		=> '<div class="mobile-menu"><div class="menu-toggle"></div></div><ul id="%1$s" class="%2$s">%3$s</ul>'
	) ) );
	echo $after;
}
endif; // publisher_site_navbar
