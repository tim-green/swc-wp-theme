<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme and one
 * of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query,
 * e.g., it puts together the home page when no home.php file exists.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Publisher
 * @since Publisher 1.0
 */

get_header(); ?>

	<?php do_action( 'publisher_action_index_above_primary' ); ?>

	<div id="primary" class="<?php publisher_primary_classes( 'primary content-area' ) ?>">

		<?php
			/**
			 * @hooked publisher_loader_icon - 10
			 */
			do_action( 'publisher_action_index_above_site_main' );
		?>

		<main id="main" class="site-main">

			<?php
				if ( have_posts() ) :

					while ( have_posts() ) : the_post();

						/**
						 * @hooked publisher_index_loop - 10
						 */
						do_action( 'publisher_action_index_loop' );

					endwhile;

				else :

					get_template_part( 'includes/partials/content', 'none' );

				endif;
			?>

		</main><!-- #main -->

		<?php
			/**
			 * @hooked publisher_the_posts_navigation - 10
			 */
			do_action( 'publisher_action_index_below_site_main' );
		?>

	</div><!-- #primary -->

	<?php do_action( 'publisher_action_index_below_primary' ); ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
