<?php
/**
 * Primary Sidebar
 *
 * @package Publisher
 * @since Publisher 1.0
 */

if ( publisher_has_sidebar() ) { ?>

	<div id="secondary" class="secondary content-area" role="complementary">
		<div id="sidebar-widgets" class="sidebar-widgets widget-area">
			<?php do_action( 'publisher_before_sidebar' ); ?>

			<?php dynamic_sidebar( publisher_get_sidebar() ); ?>

			<?php do_action( 'publisher_after_sidebar' ); ?>
		</div><!-- #sidebar-widgets -->
	</div><!-- #secondary -->

<?php
}
