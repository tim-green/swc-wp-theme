<?php
/**
 * The template for displaying all single posts
 *
 * @package Publisher
 * @since Publisher 1.0
 */

get_header(); ?>

	<?php
		/**
		 * @hooked publisher_single_above_primary - 10
		 */
		do_action( 'publisher_action_single_above_primary' );
	?>

	<div id="primary" class="<?php publisher_primary_classes( 'primary content-area' ) ?>">

		<?php do_action( 'publisher_action_single_above_site_main' ); ?>

		<main id="main" class="site-main">

			<?php
				while ( have_posts() ) : the_post();

					/**
					 * @hooked publisher_single_content - 10
					 * @hooked publisher_single_post_navigation - 20
					 * @hooked publisher_single_related_posts - 30
					 * @hooked publisher_single_comments_template - 40
					 */
					do_action( 'publisher_action_single_loop' );

				endwhile;
			?>

		</main><!-- #main -->

		<?php do_action( 'publisher_action_single_below_site_main' ); ?>

	</div><!-- #primary -->

	<?php do_action( 'publisher_action_single_below_primary' ); ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
