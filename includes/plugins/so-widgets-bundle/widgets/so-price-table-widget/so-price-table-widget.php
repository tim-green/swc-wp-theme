<?php
/**
 * Price Table Widget
 *
 * @package Publisher
 */

/*-----------------------------------------------------------------------------------*/
/*	Price Table Widget
/*-----------------------------------------------------------------------------------*/

/**
 * Modify Form
 */
function publisher_siteorigin_widgets_form_options_sow_price_table( $form_options, $widget ) {

	if ( get_class( $widget ) != 'SiteOrigin_Widget_PriceTable_Widget' ) return $form_options;

	// Add new theme
	if ( ! empty( $form_options['theme']['options'] ) ) {
		$form_options['theme']['options']['publisher'] = __( 'Publisher', 'publisher' );
	}

	return $form_options;
}
add_filter( 'siteorigin_widgets_form_options_sow-price-table', 'publisher_siteorigin_widgets_form_options_sow_price_table', 10, 2 );


/**
 * LESS File Location
 */
function publisher_siteorigin_widgets_less_file_sow_price_table( $filename, $instance, $widget ) {
	if ( ! empty( $instance['theme'] ) && $instance['theme'] == 'publisher' ) {
		$filename = get_stylesheet_directory() . '/includes/plugins/so-widgets-bundle/widgets/so-price-table-widget/styles/styles.less';
	}
	return $filename;
}
add_filter( 'siteorigin_widgets_less_file_sow-price-table', 'publisher_siteorigin_widgets_less_file_sow_price_table', 10, 3 );


/**
 * Template File Location
 */
function publisher_siteorigin_widgets_template_file_sow_price_table( $filename, $instance, $widget ){
	if ( ! empty( $instance['theme'] ) && $instance['theme'] == 'publisher' ) {
		$filename = get_stylesheet_directory() . '/includes/plugins/so-widgets-bundle/widgets/so-price-table-widget/tpl/base.php';
	}
	return $filename;
}
add_filter( 'siteorigin_widgets_template_file_sow-price-table', 'publisher_siteorigin_widgets_template_file_sow_price_table', 10, 3 );
