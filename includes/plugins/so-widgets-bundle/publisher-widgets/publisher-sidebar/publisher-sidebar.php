<?php
/*
Widget Name: Publisher Sidebar
Description: Bring in the sidebar to the Page Builder.
Author: Davadrian Maramis
Author URI: http://themeforest.net/user/hypha/?ref=hypha
*/

/*-----------------------------------------------------------------------------------*/
/*	Widgets Class
/*-----------------------------------------------------------------------------------*/

/**
 * Sidebar Class
 *
 * @package Publisher
 */
class Publisher_Sidebar_Widget extends SiteOrigin_Widget {

	function __construct() {

		// Call the parent constructor with the required arguments.
		parent::__construct(

			// The unique id for your widget.
			'publisher-sidebar',

			// The name of the widget for display purposes.
			__( 'Publisher Sidebar', 'publisher' ),

			// The $widget_options array, which is passed through to WP_Widget.
			array(
				'description' => __( 'Bring in the sidebar to the Page Builder.', 'publisher' ),
				'panels_groups' => array( 'publisher' ),
				'panels_icon' => 'dashicons dashicons-welcome-widgets-menus'
			),

			// The $widget_options array, which is passed through to WP_Widget.
			array(

			),

			// The $form_options array, which describes the form fields used to configure SiteOrigin widgets.
			array(
				'sidebar' => array(
					'type' => 'select',
					'default' => 'sidebar-main',
					'label' => __( 'Select Sidebar', 'publisher' ),
					'options' => array(

					),
				),
			),

			// The $base_folder path string.
			plugin_dir_path( __FILE__ ) . '../'
		);
	}

	function modify_form( $form ) {
		global $wp_registered_sidebars;
		if ( ! empty( $wp_registered_sidebars ) ) {
			foreach( $wp_registered_sidebars as $i => $s ) {
				$form['sidebar']['options'][$i] = $s['name'];
			}
		}

		return $form;
	}

	function get_template_name( $instance ) {
		return 'base';
	}

	function get_style_name( $instance ) {
		return '';
	}

}
siteorigin_widget_register( 'publisher-sidebar', __FILE__, 'Publisher_Sidebar_Widget', 15 );
