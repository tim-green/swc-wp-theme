<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Publisher
 * @since Publisher 1.0
 */

get_header(); ?>

	<?php
		/**
		 * @hooked publisher_page_above_primary - 10
		 */
		do_action( 'publisher_action_page_above_primary' );
	?>

	<div id="primary" class="<?php publisher_primary_classes( 'primary content-area' ) ?>">

		<?php do_action( 'publisher_action_page_above_site_main' ); ?>

		<main id="main" class="site-main">

			<?php
				while ( have_posts() ) : the_post();

					/**
					 * @hooked publisher_page_content - 10
					 * @hooked publisher_page_comments_template - 20
					 */
					do_action( 'publisher_action_page_loop' );

				endwhile;
			?>

		</main><!-- #main -->

		<?php do_action( 'publisher_action_page_below_site_main' ); ?>

	</div><!-- #primary -->

	<?php do_action( 'publisher_action_page_below_primary' ); ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
