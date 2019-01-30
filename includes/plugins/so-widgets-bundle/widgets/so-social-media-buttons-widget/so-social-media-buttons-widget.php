<?php
/**
 * Social Media Widget
 *
 * @package Publisher
 */

/*-----------------------------------------------------------------------------------*/
/*	Social Media Widget
/*-----------------------------------------------------------------------------------*/

/**
 * Modify Form
 */
function publisher_siteorigin_widgets_form_options_sow_social_media_buttons( $form_options, $widget ) {

	if ( get_class( $widget ) != 'SiteOrigin_Widget_SocialMediaButtons_Widget' ) return $form_options;

	// Add new theme
	if ( ! empty( $form_options['design']['fields']['theme']['options'] ) ) {
		$form_options['design']['fields']['theme']['options']['publisher'] = __( 'Publisher', 'publisher' );
	}

	// Change default for theme
	if ( ! empty( $form_options['design']['fields']['theme'] ) ) {
		$form_options['design']['fields']['theme']['default'] = 'flat';
	}

	// Add state emitter
	if ( ! empty( $form_options['design']['fields']['theme']['options'] ) ) {
		$form_options['design']['fields']['theme']['state_emitter'] = array(
			'callback' => 'select',
			'args' => array( 'theme' )
		);
	}

	// Add Social Icon Text
	if ( ! empty( $form_options['networks']['fields'] ) ) {
		$form_options['networks']['fields']['social_text'] = array(
			'type' => 'text',
			'label' => __( 'Title', 'publisher' ),
		);
	}

	// Add state handler
	if ( ! empty( $form_options['networks']['fields'] ) ) {
		$form_options['networks']['fields']['social_text']['state_handler'] = array(
			'theme[publisher]' => array('show'),
			'theme[atom]' => array('hide'),
			'theme[flat]' => array('hide'),
			'theme[wire]' => array('hide'),
		);
	}

	// Add state handler
	if ( ! empty( $form_options['design']['fields'] ) ) {
		$design_fields = array( 'hover', 'icon_size', 'rounding', 'padding', 'align', 'margin' );

		foreach( $design_fields as $design_field ) {
			$form_options['design']['fields'][$design_field]['state_handler'] = array(
				'theme[publisher]' => array('hide'),
				'theme[atom]' => array('show'),
				'theme[flat]' => array('show'),
				'theme[wire]' => array('show'),
			);
		}
	}

	// Add Icon Alignment
	if ( empty( $form_options['design']['fields']['publisher_social_icon_alignment'] ) ) {
		$form_options['design']['fields']['publisher_social_icon_alignment'] = array(
			'label' => __( 'Icon Alignment', 'publisher' ),
			'description' => __( 'The alignment of the icons.', 'publisher' ),
			'type' => 'select',
			'default' => 'horizontal',
			'options' => array(
				'vertical' => 'Vertical',
				'horizontal' => 'Horizontal'
			),
			'state_handler' => array(
				'theme[publisher]' => array('show'),
				'theme[atom]' => array('hide'),
				'theme[flat]' => array('hide'),
				'theme[wire]' => array('hide'),
			)
		);
	}

	// Add Icon Columns
	if ( empty( $form_options['design']['fields']['publisher_social_icon_columns'] ) ) {
		$form_options['design']['fields']['publisher_social_icon_columns'] = array(
			'label' => __( 'Icon Columns', 'publisher' ),
			'description' => __( 'The number of icons to put in one row.', 'publisher' ),
			'type' => 'slider',
			'min' => 1,
			'max' => 5,
			'default' => 2,
			'state_handler' => array(
				'theme[publisher]' => array('show'),
				'theme[atom]' => array('hide'),
				'theme[flat]' => array('hide'),
				'theme[wire]' => array('hide'),
			)
		);
	}

	// Add Widget Title
	if ( empty( $form_options['publisher_social_widget_title'] ) ) {
		$form_options['publisher_social_widget_title'] = array(
			'type' => 'text',
			'label' => __( 'Widget Title', 'publisher' )
		);
	}

	return $form_options;
}
add_filter( 'siteorigin_widgets_form_options_sow-social-media-buttons', 'publisher_siteorigin_widgets_form_options_sow_social_media_buttons', 10, 2 );


/**
 * LESS File Location
 */
function publisher_siteorigin_widgets_less_file_sow_social_media_buttons( $filename, $instance, $widget ) {
	if ( ! empty( $instance['design']['theme'] ) && $instance['design']['theme'] == 'publisher' ) {
		$filename = get_stylesheet_directory() . '/includes/plugins/so-widgets-bundle/widgets/so-social-media-buttons-widget/styles/styles.less';
	}
	return $filename;
}
add_filter( 'siteorigin_widgets_less_file_sow-social-media-buttons', 'publisher_siteorigin_widgets_less_file_sow_social_media_buttons', 10, 3 );


/**
 * Template File Location
 */
function publisher_siteorigin_widgets_template_file_sow_social_media_buttons( $filename, $instance, $widget ){
	if ( ! empty( $instance['design']['theme'] ) && $instance['design']['theme'] == 'publisher' ) {
		$filename = get_stylesheet_directory() . '/includes/plugins/so-widgets-bundle/widgets/so-social-media-buttons-widget/tpl/base.php';
	}
	return $filename;
}
add_filter( 'siteorigin_widgets_template_file_sow-social-media-buttons', 'publisher_siteorigin_widgets_template_file_sow_social_media_buttons', 10, 3 );

/**
 * Modify Template File
 */
function publisher_siteorigin_widgets_template_html_sow_social_media_buttons( $template_html, $instance, $widget ) {

	// Add widget title
	if ( $instance['publisher_social_widget_title'] ) {
		return sprintf( '<h2 class="widget-title">%s</h2>', $instance['publisher_social_widget_title'] ) . $template_html;
	}

	return $template_html;
}
add_filter('siteorigin_widgets_template_html_sow-social-media-buttons', 'publisher_siteorigin_widgets_template_html_sow_social_media_buttons', 10, 3 );
