<?php
/**
 * Headline Widget
 *
 * @package Publisher
 */

/*-----------------------------------------------------------------------------------*/
/*	Headline Widget
/*-----------------------------------------------------------------------------------*/

/**
 * Modify Form
 */
function publisher_siteorigin_widgets_form_options_sow_headline( $form_options, $widget ) {

	if ( get_class( $widget ) != 'SiteOrigin_Widget_Headline_Widget' ) return $form_options;

	// Change default headline colors
	if ( ! empty( $form_options['headline']['fields']['color'] ) ) {
		$form_options['headline']['fields']['color']['default'] = false;
		$form_options['headline']['fields']['color']['state_handler'] = array(
			'publisher_headline_style[section_masthead]' => array( 'show' ),
			'publisher_headline_style[section_header]' => array( 'show' ),
			'publisher_headline_style[section_block]' => array( 'hide' ),
		);
	}

	// Change default headline alignment
	if ( ! empty( $form_options['headline']['fields']['align'] ) ) {
		$form_options['headline']['fields']['align']['default'] = 'left';
	}

	// Change default sub headline colors
	if ( ! empty( $form_options['sub_headline']['fields']['color'] ) ) {
		$form_options['sub_headline']['fields']['color']['default'] = false;
		$form_options['sub_headline']['fields']['color']['state_handler'] = array(
			'publisher_headline_style[section_masthead]' => array( 'show' ),
			'publisher_headline_style[section_header]' => array( 'show' ),
			'publisher_headline_style[section_block]' => array( 'hide' ),
		);
	}

	// Change default divider colors
	if ( ! empty( $form_options['divider']['fields']['color'] ) ) {
		$form_options['divider']['fields']['color']['default'] = false;
		$form_options['divider']['fields']['color']['state_handler'] = array(
			'publisher_headline_style[section_masthead]' => array( 'show' ),
			'publisher_headline_style[section_header]' => array( 'show' ),
			'publisher_headline_style[section_block]' => array( 'hide' ),
		);
	}

	// Change default divider option
	if ( ! empty( $form_options['divider']['fields']['style'] ) ) {
		$form_options['divider']['fields']['style']['default'] = 'none';
	}

	// Add divider state handler
	if ( ! empty( $form_options['divider'] ) ) {
		$form_options['divider']['state_handler'] = array(
			'publisher_headline_style[section_masthead]' => array( 'show' ),
			'publisher_headline_style[section_header]' => array( 'hide' ),
			'publisher_headline_style[section_block]' => array( 'hide' ),
		);
	}

	// Remove sub-headline aligns
	unset( $form_options['sub_headline']['fields']['align'] );

	// Add Headline Styles
	if ( empty( $form_options['publisher_headline_style'] ) ) {
		$form_options['publisher_headline_style'] = array(
			'type' => 'select',
			'label' => __( 'Headline Style', 'publisher' ),
			'default' => 'section_header',
			'state_emitter' => array(
				'callback' => 'select',
				'args' => array( 'publisher_headline_style' )
			),
			'options' => array(
				'section_masthead' => __( 'Section Masthead', 'publisher' ),
				'section_header' => __( 'Section Header', 'publisher' ),
				'section_block' => __( 'Section Block', 'publisher' ),
			)
		);
	}

	return $form_options;
}
add_filter( 'siteorigin_widgets_form_options_sow-headline', 'publisher_siteorigin_widgets_form_options_sow_headline', 10, 2 );


/**
 * LESS File Location
 */
function publisher_siteorigin_widgets_less_file_sow_headline( $filename, $instance, $widget ) {
	$filename = get_stylesheet_directory() . '/includes/plugins/so-widgets-bundle/widgets/so-headline-widget/styles/styles.less';
	return $filename;
}
add_filter( 'siteorigin_widgets_less_file_sow-headline', 'publisher_siteorigin_widgets_less_file_sow_headline', 10, 3 );


/**
 * Template File Location
 */
function publisher_siteorigin_widgets_template_file_sow_headline( $filename, $instance, $widget ){
	$filename = get_stylesheet_directory() . '/includes/plugins/so-widgets-bundle/widgets/so-headline-widget/tpl/base.php';
	return $filename;
}
add_filter( 'siteorigin_widgets_template_file_sow-headline', 'publisher_siteorigin_widgets_template_file_sow_headline', 10, 3 );
