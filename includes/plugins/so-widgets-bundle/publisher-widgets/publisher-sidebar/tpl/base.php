<?php
/**
 * Sidebar Widget
 *
 * @package Publisher
 */

$sidebar = ! empty( $instance['sidebar'] ) ? $instance['sidebar'] : 'sidebar-main';
?>

<div class="secondary content-area" role="complementary">
	<div class="sidebar-widgets widget-area">
		<?php dynamic_sidebar( $sidebar ); ?>
	</div><!-- .sidebar-widgets -->
</div><!-- .secondary -->
