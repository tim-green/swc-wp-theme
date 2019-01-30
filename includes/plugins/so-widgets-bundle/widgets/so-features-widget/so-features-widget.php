<?php
/**
 * Features Widget
 *
 * @package Publisher
 */

/*-----------------------------------------------------------------------------------*/
/*	Features Widget
/*-----------------------------------------------------------------------------------*/

/**
 * Modify Form
 */
function publisher_siteorigin_widgets_form_options_sow_features( $form_options, $widget ) {

	if ( get_class( $widget ) != 'SiteOrigin_Widget_Features_Widget' ) return $form_options;

	// Change default container color
	if ( ! empty( $form_options['features']['fields']['container_color'] ) ) {
		$form_options['features']['fields']['container_color']['default'] = false;
	}

	// Change default icon color
	if ( ! empty( $form_options['features']['fields']['icon_color'] ) ) {
		$form_options['features']['fields']['icon_color']['default'] = false;
	}

	// Add new container shape
	if ( ! empty( $form_options['container_shape']['options'] ) ) {
		$form_options['container_shape']['options']['none'] = __( 'None', 'publisher' );
	}

	// Add container description
	if ( empty( $form_options['container_size']['description'] ) ) {
		$form_options['container_size']['description'] = __( 'Container size should not be less than the icon size.', 'publisher' );
	}

	// Change default icon size
	if ( ! empty( $form_options['icon_size'] ) ) {
		$form_options['icon_size']['default'] = 48;
	}

	// Add Style
	if ( empty( $form_options['publisher_features_align'] ) ) {
		$form_options['publisher_features_align'] = array(
			'type' => 'select',
			'label' => __( 'Alignment Style', 'publisher' ),
			'default' => 'publisher-flush-left',
			'options' => array(
				'publisher-flush-left' => __( 'Left', 'publisher' ),
				'publisher-flush-center' => __( 'Center', 'publisher' ),
				'publisher-flush-right' => __( 'Right', 'publisher' ),
			)
		);
	}

	return $form_options;
}
add_filter( 'siteorigin_widgets_form_options_sow-features', 'publisher_siteorigin_widgets_form_options_sow_features', 10, 2 );


/**
 * Template File Location
 */
function publisher_siteorigin_widgets_template_file_sow_features( $filename, $instance, $widget ){
	$filename = get_stylesheet_directory() . '/includes/plugins/so-widgets-bundle/widgets/so-features-widget/tpl/base.php';
	return $filename;
}
add_filter( 'siteorigin_widgets_template_file_sow-features', 'publisher_siteorigin_widgets_template_file_sow_features', 10, 3 );
