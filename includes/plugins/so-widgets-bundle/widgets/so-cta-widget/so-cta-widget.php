<?php
/**
 * Call To Action Widget
 *
 * @package Publisher
 */

/*-----------------------------------------------------------------------------------*/
/*	Call To Action Widget
/*-----------------------------------------------------------------------------------*/

/**
 * Modify Form
 */
function publisher_siteorigin_widgets_form_options_sow_cta( $form_options, $widget ) {

	if ( get_class( $widget ) != 'SiteOrigin_Widget_Cta_widget' ) return $form_options;

	// Remove Padding
	if ( ! empty( $form_options['design']['fields'] ) ) {
		$form_options['design']['fields']['publisher_boxed'] = array(
			'type' => 'checkbox',
			'label' => __( 'No Box', 'publisher' ),
		);
	}

	// Change Text
	if ( ! empty( $form_options['design']['fields']['button_align'] ) ) {
		$form_options['design']['fields']['button_align']['label'] = __( 'Alignment', 'publisher' );
	}

	// Add Center Option
	if ( ! empty( $form_options['design']['fields']['button_align'] ) ) {
		$form_options['design']['fields']['button_align']['options']['center'] = __( 'Center', 'publisher' );
	}

	return $form_options;
}
add_filter( 'siteorigin_widgets_form_options_sow-cta', 'publisher_siteorigin_widgets_form_options_sow_cta', 10, 2 );


/**
 * Modify Template File
 */
function publisher_siteorigin_widgets_template_html_sow_cta( $template_html, $instance, $widget ) {

	$classes = array( 'publisher-cta-widget' );
	if ( ! empty( $instance['design']['button_align'] ) ) $classes[] = $instance['design']['button_align'];
	if ( ! empty( $instance['design']['publisher_boxed'] ) ) $classes[] = 'no-box';

	$cta_attributes = array(
		'class' => esc_attr( implode( ' ', $classes ) )
	);

	$start_template_html = '<div class="' . $cta_attributes['class'] . '">';
	$end_template_html = '</div>';

	return $start_template_html . $template_html . $end_template_html;
}
add_filter( 'siteorigin_widgets_template_html_sow-cta', 'publisher_siteorigin_widgets_template_html_sow_cta', 10, 3 );
