<?php
/**
 * Image Widget
 *
 * @package Publisher
 */

/*-----------------------------------------------------------------------------------*/
/*	Image Widget
/*-----------------------------------------------------------------------------------*/

/**
 * Modify Form
 */
function publisher_siteorigin_widgets_form_options_sow_image( $form_options, $widget ) {

	if ( get_class( $widget ) != 'SiteOrigin_Widget_Image_Widget' ) return $form_options;

	// Add Alignment
	if ( empty( $form_options['publisher_image_align'] ) ) {
		$form_options['publisher_image_align'] = array(
			'type' => 'select',
			'label' => __( 'Alignment', 'publisher' ),
			'default' => 'center',
			'options' => array(
				'left' => __( 'Left', 'publisher' ),
				'center' => __( 'Center', 'publisher' ),
				'right' => __( 'Right', 'publisher' ),
			)
		);
	}

	// Add Header Text
	if ( empty( $form_options['publisher_image_text'] ) ) {
		$form_options['publisher_image_text'] = array(
			'type' => 'text',
			'label' => __( 'Image Header Text (Optional)', 'publisher' ),
		);
	}

	return $form_options;
}
add_filter( 'siteorigin_widgets_form_options_sow-image', 'publisher_siteorigin_widgets_form_options_sow_image', 10, 2 );


/**
 * Template File Location
 */
function publisher_siteorigin_widgets_template_file_sow_image( $filename, $instance, $widget ){
	$filename = get_stylesheet_directory() . '/includes/plugins/so-widgets-bundle/widgets/so-image-widget/tpl/base.php';
	return $filename;
}
add_filter( 'siteorigin_widgets_template_file_sow-image', 'publisher_siteorigin_widgets_template_file_sow_image', 10, 3 );
