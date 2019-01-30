<?php
/**
 * Custom template tags specific to footer.php
 *
 * @package Publisher
 */

/*-----------------------------------------------------------------------------------*/
/*	Do Action - publisher_action_site_footer
/*-----------------------------------------------------------------------------------*/

/**
 * Footer
 *
 * @action publisher_action_site_footer
 */
if ( ! function_exists( 'publisher_site_footer' ) ) :
function publisher_site_footer() {

	$footer_style = apply_filters( 'publisher_filter_footer_style', 'publisher_footer_style_1' );

	if ( function_exists( $footer_style ) ) {
		call_user_func( $footer_style );
	} else {
		publisher_footer_style_1();
	}

}
endif; // publisher_site_footer
add_action( 'publisher_action_site_footer', 'publisher_site_footer', 10 );




/*-----------------------------------------------------------------------------------*/
/*	Footer Styles
/*-----------------------------------------------------------------------------------*/

/**
 * Footer Style 1
 */
if ( ! function_exists( 'publisher_footer_style_1' ) ) :
function publisher_footer_style_1() { ?>

	<div class="site-footer-content">
		<?php
			publisher_footer_image();
			publisher_footer_widgets();
		?>
	</div><!-- .site-footer-content -->

	<?php publisher_footnote(); ?>

<?php
}
endif; // publisher_footer_style_1




/*-----------------------------------------------------------------------------------*/
/*	Template Tags
/*-----------------------------------------------------------------------------------*/

/**
 * Footnote
 */
if ( ! function_exists( 'publisher_footnote' ) ):
function publisher_footnote() { ?>

	<div id="footnote" class="footnote">
		<div class="container">
			<?php
				publisher_footer_navigation();
				publisher_footnote_text();
			?>
		</div><!-- .container -->
	</div><!-- .footnote -->

<?php
}
endif; // publisher_footnote


/**
 * Footer Image
 */
if ( ! function_exists( 'publisher_footer_image' ) ):
function publisher_footer_image() {
    $footer_image = get_theme_mod( 'publisher_options_footer_image' );
	$footer_opacity = esc_attr( get_theme_mod( 'publisher_options_footer_image_opacity', '1' ) );

	if ( ! empty( $footer_image ) ) {
		printf( '<div class="site-footer-image site-background-image" style="background-image: url(%1$s); opacity: %2$s;"></div>',
			$footer_image,
			$footer_opacity
		);
	}
}
endif; // publisher_footer_image


/**
 * Footer Widgets
 */
if ( ! function_exists( 'publisher_footer_widgets' ) ) :
function publisher_footer_widgets() {

	if ( is_active_sidebar( 'sidebar-footer' ) ) { ?>
		<div class="site-footer-widgets">
			<div class="container">
				<div id="footer-widgets" class="footer-widgets widget-area" role="complementary">
					<?php dynamic_sidebar( 'sidebar-footer' ); ?>
				</div><!-- #footer-widgets -->
			</div><!-- .container -->
		</div><!-- .site-footer-widgets -->
	<?php
	}

}
endif; // publisher_footer_widgets


/**
 * Footer Navigation
 */
if ( ! function_exists( 'publisher_footer_navigation' ) ) :
function publisher_footer_navigation() {
	if ( has_nav_menu( 'publisher-menu-footer' ) ) { ?>
		<nav class="footer-navigation mini-widget-area">
			<?php
				wp_nav_menu( array(
					'theme_location' => 'publisher-menu-footer',
					'link_before' => '<span class="title">',
					'link_after' => '</span>',
					'fallback_cb' => false
				) );
			?>
		</nav><!-- .footer-navigation -->
	<?php }
}
endif; // publisher_footer_navigation


/**
 * Footnote Text
 */
if ( ! function_exists( 'publisher_footnote_text' ) ) :
function publisher_footnote_text() { ?>
	<div class="site-info">
		<?php
			if ( get_option( 'publisher_options_footer_tagline' ) ) {
				echo get_option( 'publisher_options_footer_tagline' );
			} else {
				printf( '<span class="copyright">&copy;</span> %1$s <a href="%2$s">%3$s</a><span class="sep"> | </span>%4$s',
					date( 'Y' ),
					esc_url( home_url( '/' ) ),
					get_bloginfo( 'name' ),
					get_bloginfo( 'description' )
				);
			}
		?>
	</div><!-- .site-info -->
<?php
}
endif; // publisher_footnote_text




/*-----------------------------------------------------------------------------------*/
/*	Do Action - publisher_action_above_site_footer
/*-----------------------------------------------------------------------------------*/

/**
 * Above Site Footer
 *
 * @action publisher_action_above_site_footer
 */
if ( ! function_exists( 'publisher_above_site_footer' ) ) :
function publisher_above_site_footer() {

	$above_footer_style = apply_filters( 'publisher_filter_above_site_footer', 'publisher_above_footer_style_1' );

	if ( function_exists( $above_footer_style ) ) {
		call_user_func( $above_footer_style );
	} else {
		publisher_above_footer_style_1();
	}

}
endif; // publisher_site_footer
add_action( 'publisher_action_above_site_footer', 'publisher_above_site_footer', 10 );




/*-----------------------------------------------------------------------------------*/
/*	Above Footer Styles
/*-----------------------------------------------------------------------------------*/

/**
 * Post Row
 * Look into for version 2.0. Adds ability to attach page content to footer.
 */
if ( ! function_exists( 'publisher_above_footer_style_1' ) ):
function publisher_above_footer_style_1() {

	$footer_cap_id = get_theme_mod( 'publisher_options_footer_cap' );

	if ( $footer_cap_id ) {

		$page_query = new WP_Query( array( 'post_type' => 'page', 'post__in' => array( $footer_cap_id ) ) );

		if ( $page_query->have_posts() ) : ?>

			<div id="footer-cap">
				<div class="container">
					<?php
						while ( $page_query->have_posts() ) : $page_query->the_post();
							the_content();
						endwhile; wp_reset_postdata();
					?>
				</div><!-- .container -->
			</div><!-- #footer-cap -->
			<?php

		endif;
	}

}
endif; // publisher_above_footer_style_1
