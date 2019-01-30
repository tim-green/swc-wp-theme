<?php
/**
 * The template for displaying bbpress forums
 *
 * @package Publisher
 * @since Publisher 1.0
 */

get_header(); ?>

	<?php do_action( 'publisher_action_bbpress_above_primary' ); ?>

	<div id="primary" class="<?php publisher_primary_classes( 'primary content-area' ) ?>">

		<?php do_action( 'publisher_action_bbpress_above_site_main' ); ?>

		<main id="main" class="site-main">

			<?php while ( have_posts() ) : the_post(); ?>

				<article id="page-<?php the_ID(); ?>" <?php post_class(); ?>>

					<?php
						/**
						 * @hooked publisher_bbpress_entry_content - 10
						 */
						do_action( 'publisher_action_bbpress_entry' );
					?>

				</article><!-- #page-<?php the_ID(); ?> -->

			<?php endwhile; ?>

		</main><!-- #main -->

		<?php do_action( 'publisher_action_bbpress_below_site_main' ); ?>

	</div><!-- #primary -->

	<?php do_action( 'publisher_action_bbpress_below_primary' ); ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
