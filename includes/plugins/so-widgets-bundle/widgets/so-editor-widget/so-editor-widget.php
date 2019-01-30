<?php
/**
 * Editor Widget
 *
 * @package Publisher
 */

/*-----------------------------------------------------------------------------------*/
/*	Editor Widget
/*-----------------------------------------------------------------------------------*/

/**
 * Modify Form
 */
function publisher_siteorigin_widgets_form_options_sow_editor( $form_options, $widget ) {

	if ( get_class( $widget ) != 'SiteOrigin_Widget_Editor_Widget' ) return $form_options;

	// Add Boxed
	if ( empty( $form_options['publisher_boxed'] ) ) {
		$form_options['publisher_boxed'] = array(
			'type' => 'checkbox',
			'label' => __( 'Boxed', 'publisher' ),
		);
	}

	return $form_options;
}
add_filter( 'siteorigin_widgets_form_options_sow-editor', 'publisher_siteorigin_widgets_form_options_sow_editor', 10, 2 );


/**
 * Template File Location
 */
function publisher_siteorigin_widgets_template_file_sow_editor( $filename, $instance, $widget ){
	if ( ! empty( $instance['publisher_boxed'] ) && $instance['publisher_boxed'] != '' ) {
		$filename = get_stylesheet_directory() . '/includes/plugins/so-widgets-bundle/widgets/so-editor-widget/tpl/base.php';
	}
	return $filename;
}
add_filter( 'siteorigin_widgets_template_file_sow-editor', 'publisher_siteorigin_widgets_template_file_sow_editor', 10, 3 );
