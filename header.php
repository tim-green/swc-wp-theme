<?php
/**
 * The Header
 *
 * Displays all of the <head> section and opens up the content.
 *
 * @package Publisher
 * @since Publisher 1.0
 */
?>

<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>

<head><?php wp_head(); ?></head>

<body <?php body_class(); ?>>

<div id="page" class="hfeed site">

	<?php
		/**
		* @hooked publisher_media_query - 10
		* @hooked publisher_call_to_action - 20
		* @hooked publisher_headband - 30
		*/
		do_action( 'publisher_action_above_site_header' );
	?>

	<div id="header" class="<?php publisher_header_classes( 'site-header' ) ?>" role="banner">

		<?php
			/**
			 * @hooked publisher_site_header - 10
			 */
			do_action( 'publisher_action_site_header' );
		?>

	</div><!-- #header -->

	<?php
		/**
		 * @hooked publisher_featured_header - 10
		 */
		do_action( 'publisher_action_above_site_content' );
	?>

	<div id="content" class="<?php publisher_content_classes( 'site-content' ) ?>">

		<?php do_action( 'publisher_action_above_content_area_container' ); ?>

		<div class="container">

			<?php do_action( 'publisher_action_above_content_area' ); ?>
