<?php
/**
 * Button Widget
 *
 * @package Publisher
 */

/*-----------------------------------------------------------------------------------*/
/*	Button Widget
/*-----------------------------------------------------------------------------------*/

/**
 * Modify Form
 */
function publisher_siteorigin_widgets_form_options_sow_button( $form_options, $widget ) {

	if ( get_class( $widget ) != 'SiteOrigin_Widget_Button_Widget' ) return $form_options;

	// Add new theme
	if ( ! empty( $form_options['design']['fields']['theme']['options'] ) ) {
		$form_options['design']['fields']['theme']['options']['publisher'] = __( 'Publisher', 'publisher' );
	}

	// Change default for theme
	if ( ! empty( $form_options['design']['fields']['theme'] ) ) {
		$form_options['design']['fields']['theme']['default'] = 'publisher';
	}

	// Change default for align
	if ( ! empty( $form_options['design']['fields']['align'] ) ) {
		$form_options['design']['fields']['align']['default'] = 'left';
	}

	// Add state emitter
	if ( ! empty( $form_options['design']['fields']['theme']['options'] ) ) {
		$form_options['design']['fields']['theme']['state_emitter'] = array(
			'callback' => 'select',
			'args' => array( 'theme' )
		);
	}

	// Add state handler
	if ( ! empty( $form_options['design']['fields'] ) ) {
		$design_fields = array( 'hover', 'font_size', 'rounding', 'padding' );

		foreach( $design_fields as $design_field ) {
			$form_options['design']['fields'][$design_field]['state_handler'] = array(
				'theme[publisher]' => array('hide'),
				'_else[theme]' => array('show'),
			);
		}
	}

	return $form_options;
}
add_filter( 'siteorigin_widgets_form_options_sow-button', 'publisher_siteorigin_widgets_form_options_sow_button', 10, 2 );


/**
 * LESS File Location
 */
function publisher_siteorigin_widgets_less_file_sow_button( $filename, $instance, $widget ) {
	if ( ! empty( $instance['design']['theme'] ) && $instance['design']['theme'] == 'publisher' ) {
		$filename = get_stylesheet_directory() . '/includes/plugins/so-widgets-bundle/widgets/so-button-widget/styles/styles.less';
	}
	return $filename;
}
add_filter( 'siteorigin_widgets_less_file_sow-button', 'publisher_siteorigin_widgets_less_file_sow_button', 10, 3 );


/**
 * Template File Location
 */
function publisher_siteorigin_widgets_template_file_sow_button( $filename, $instance, $widget ){
	if ( ! empty( $instance['design']['theme'] ) && $instance['design']['theme'] == 'publisher' ) {
		$filename = get_stylesheet_directory() . '/includes/plugins/so-widgets-bundle/widgets/so-button-widget/tpl/base.php';
	}
	return $filename;
}
add_filter( 'siteorigin_widgets_template_file_sow-button', 'publisher_siteorigin_widgets_template_file_sow_button', 10, 3 );
