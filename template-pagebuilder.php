<?php
/**
 * Template Name: Page Builder
 *
 * This page template is used for full control using Page Builder by Site Origin.
 * See: https://siteorigin.com/page-builder/
 *
 * @package Publisher
 * @since Publisher 1.0
 */

get_header(); ?>

	<main id="main" class="site-main">
		<?php
			while ( have_posts() ) : the_post();

				the_content();

			endwhile;
		?>
	</main><!-- #main -->

<?php get_footer(); ?>
