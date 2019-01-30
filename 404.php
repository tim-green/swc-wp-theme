<?php
/**
 * The template for displaying 404 pages (Not Found)
 *
 * @package Publisher
 * @since Publisher 1.0
 */

get_header(); ?>

	<?php do_action( 'publisher_action_404_above_primary' ); ?>

	<div id="primary" class="<?php publisher_primary_classes( 'primary content-area' ) ?>">

		<?php do_action( 'publisher_action_404_above_site_main' ); ?>

		<main id="main" class="site-main">

			<?php get_template_part( 'includes/partials/content', 'none' ); ?>

		</main><!-- #main -->

		<?php do_action( 'publisher_action_404_below_site_main' ); ?>

	</div><!-- #primary -->

	<?php do_action( 'publisher_action_404_below_primary' ); ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
