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
   				// $old_post = 60*60*24*365; // This is a year
   				// if((date('U')-get_the_time('U')) > $old_post) {
    			// 	echo '<div class="swc-old-notice">This post was last updated over a year ago, therefore the contents of this post may be out of date.</div>';
    			// 	}
			
			
			// Define old post duration to one year
$time_defined_as_old = 60*60*24*365; 
 
// Check to see if a post is older than a year
if((date('U')-get_the_time('U')) > $time_defined_as_old) {
 
$lastmodified = get_the_modified_time('U');
$posted = get_the_time('U');
 
//check if the post was updated after being published
 if ($lastmodified > $posted) {
  
// Display last updated notice
      echo '<div class="swc-old-notice"><i class="fa-regular fa-circle-exclamation"></i> This article was last updated ' . human_time_diff($lastmodified,current_time('U')) . ' ago, therefore the contents of this post could be out of date.</div>';   
 
  } else { 
// Display last published notice 
echo '<div class="swc-old-notice"><i class="swc-fa fa-regular fa-circle-exclamation"></i> This article was published ' . human_time_diff($posted,current_time( 'U' )). ' ago, therefore the contents of this post may be out of date.</div>';
 
}
}

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
