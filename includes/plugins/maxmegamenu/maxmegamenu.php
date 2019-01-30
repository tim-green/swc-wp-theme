<?php
/**
 * Max Mega Menu Compatibility File
 * See: https://maxmegamenu.com/
 *
 * @package Publisher
 */

/*-----------------------------------------------------------------------------------*/
/*	Setup Max Mega Menu Defaults
/*-----------------------------------------------------------------------------------*/

/**
 * Change location of Max Mega Menu styles
 */
function publisher_megamenu_scss_locations( $locations ) {

	// Push theme customizations to end
	array_push( $locations, trailingslashit( get_template_directory() ) . '/includes/plugins/maxmegamenu/megamenu.scss' );
	return $locations;

}
add_filter( 'megamenu_scss_locations', 'publisher_megamenu_scss_locations' );


/**
 * Add taxonomy color and descriptions to Mega Menu Walker
 */
add_filter( 'megamenu_walker_nav_menu_start_el', 'publisher_walker_nav_menu_start_el', 10, 5 );


/**
 * Modify li items
 */
function publisher_megamenu_nav_menu_css_class( $classes, $item, $args ) {

	// Remove font awesome from li classes
	foreach ( $classes as $i => $class ) {
		if ( substr( $class, 0, 3 ) == 'fa-' ) {
			unset( $classes[$i] );
		}
	}

	return $classes;
}
add_filter( 'megamenu_nav_menu_css_class', 'publisher_megamenu_nav_menu_css_class', 10, 3 );


/**
 * Modify menu attributes
 */
function publisher_megamenu_nav_menu_link_attributes( $atts, $item, $args ) {

	// Add font awesome icon to title class
	foreach ( $item->classes as $i => $class ) {
		if ( substr( $class, 0, 3 ) == 'fa-' && 'disabled' == $item->megamenu_settings['icon'] ) {
			$atts['class'] = 'menu-icon ' . $class;
		}
	}

	return $atts;

}
add_filter( 'megamenu_nav_menu_link_attributes', 'publisher_megamenu_nav_menu_link_attributes', 10, 3 );


/**
 * Add taxonomy color and descriptions to primary menu walker
 */
function publisher_megamenu_walker_nav_menu_start_el( $item_output, $item, $depth, $args ) {

	// Add Toggle Button for Hover
    if ( 'publisher-menu-primary' == $args->theme_location && ! empty( $item->classes ) && in_array( 'menu-item-has-children', $item->classes ) ) {
        $item_output .= '<span class="mega-toggle-hover-button"></span>';
    }

    return $item_output;

}
add_filter( 'megamenu_walker_nav_menu_start_el', 'publisher_megamenu_walker_nav_menu_start_el', 10, 5 );


/**
 * Wrap title
 */
function publisher_megamenu_the_title( $item_title, $item_id ) {
	return '<span class="title">' . $item_title . '</span>';
}
add_filter( 'megamenu_the_title', 'publisher_megamenu_the_title', 10, 2 );


/**
 * Modify Javascript settings
 */
function publisher_megamenu_javascript_localisation( $array ) {
	$array['timeout'] = 100;
	$array['interval'] = 25;
	return $array;
}
add_filter( 'megamenu_javascript_localisation', 'publisher_megamenu_javascript_localisation', 10, 1 );


/**
 * Default theme settings
 */
function publisher_megamenu_themes( $themes ) {
	$themes['default']['font_size'] 								= '16px';
	$themes['default']['arrow_up'] 									= 'dash-f343';
	$themes['default']['arrow_down'] 								= 'dash-f347';
	$themes['default']['container_background_from'] 				= '#474747';
	$themes['default']['container_background_to'] 					= '#292929';
	$themes['default']['menu_item_background_hover_from'] 			= '#000';
	$themes['default']['menu_item_background_hover_to'] 			= '#000';
	$themes['default']['menu_item_link_height']						= 'inherit';
	$themes['default']['menu_item_link_font_size']					= '0.75em';
	$themes['default']['menu_item_link_weight']						= 'bold';
	$themes['default']['menu_item_link_color']						= '#f2f2f2';
	$themes['default']['menu_item_link_text_transform']				= 'uppercase';
	$themes['default']['menu_item_link_weight_hover']				= 'bold';
	$themes['default']['line_height']								= '1.625';
	$themes['default']['menu_item_link_padding_left']				= '1.25em';
	$themes['default']['menu_item_link_padding_right']				= '1.25em';
	$themes['default']['menu_item_link_padding_top']				= '.925em';
	$themes['default']['menu_item_link_padding_bottom']				= '.925em';
	$themes['default']['flyout_width']								= '15em';
	$themes['default']['flyout_menu_background_from']				= '#000';
	$themes['default']['flyout_menu_background_to']					= '#000';
	$themes['default']['flyout_background_from']					= '#000';
	$themes['default']['flyout_background_to']						= '#000';
	$themes['default']['flyout_background_hover_from']				= '#292929';
	$themes['default']['flyout_background_hover_to']				= '#292929';
	$themes['default']['shadow']									= 'on';
	$themes['default']['shadow_horizontal']							= '0px';
	$themes['default']['shadow_vertical']							= '5px';
	$themes['default']['shadow_blur']								= '5px';
	$themes['default']['shadow_spread']								= '0px';
	$themes['default']['shadow_color']								= 'rgba(0, 0, 0, 0.25)';
	$themes['default']['flyout_link_color']							= '#f2f2f2';
	$themes['default']['flyout_link_color_hover']					= '#f2f2f2';
	$themes['default']['flyout_link_size']							= '0.75em';
	$themes['default']['flyout_link_weight']						= 'bold';
	$themes['default']['flyout_link_text_transform']				= 'uppercase';
	$themes['default']['flyout_link_padding_left']					= '1.25em';
	$themes['default']['flyout_link_padding_right']					= '1.25em';
	$themes['default']['flyout_link_padding_top']					= '.925em';
	$themes['default']['flyout_link_padding_bottom']				= '.925em';
	$themes['default']['flyout_link_height']						= '1.625em';
	$themes['default']['flyout_link_weight_hover']					= 'bold';
	$themes['default']['panel_background_from']						= '#000';
	$themes['default']['panel_background_to']						= '#000';
	$themes['default']['panel_header_color']						= '#f2f2f2';
	$themes['default']['panel_header_font_size']					= '0.75em';
	$themes['default']['panel_header_padding_top']					= '0px';
	$themes['default']['panel_header_padding_right']				= '0px';
	$themes['default']['panel_header_padding_bottom']				= '0px';
	$themes['default']['panel_header_padding_left']					= '0px';
	$themes['default']['panel_third_level_font_size']				= '0.75em';
	$themes['default']['panel_third_level_font_color']				= '#7a7a7a';
	$themes['default']['panel_third_level_font_weight']				= 'bold';
	$themes['default']['panel_third_level_text_transform']			= 'uppercase';
	$themes['default']['panel_third_level_font_color_hover']		= '#f2f2f2';
	$themes['default']['panel_third_level_font_weight_hover']		= 'bold';
	$themes['default']['panel_third_level_background_hover_from']	= '#292929';
	$themes['default']['panel_third_level_background_hover_to']		= '#292929';
	$themes['default']['panel_third_level_padding_left']			= '1.25em';
	$themes['default']['panel_third_level_padding_right']			= '1.25em';
	$themes['default']['panel_third_level_padding_top']				= '.525em';
	$themes['default']['panel_third_level_padding_bottom']			= '.525em';
	$themes['default']['panel_third_level_background_hover_to']		= '#292929';
	$themes['default']['panel_second_level_padding_left']			= '1.25em';
	$themes['default']['panel_second_level_padding_right']			= '1.25em';
	$themes['default']['panel_second_level_padding_top']			= '.925em';
	$themes['default']['panel_second_level_padding_bottom']			= '.925em';
	$themes['default']['panel_second_level_background_hover_from']	= '#292929';
	$themes['default']['panel_second_level_background_hover_to']	= '#292929';
	$themes['default']['panel_padding_left']						= '0.250em';
	$themes['default']['panel_padding_right']						= '0.250em';
	$themes['default']['panel_padding_top']							= '0.250em';
	$themes['default']['panel_padding_bottom']						= '0.250em';
	$themes['default']['panel_widget_padding_left']					= '1%';
	$themes['default']['panel_widget_padding_right']				= '1%';
	$themes['default']['panel_widget_padding_top']					= '1%';
	$themes['default']['panel_widget_padding_bottom']				= '1%';
	$themes['default']['panel_font_size']							= '1em';
	$themes['default']['panel_font_color']							= '#7a7a7a';
	$themes['default']['transitions']								= 'on';
	$themes['default']['panel_header_margin_bottom']				= '.925em';
	$themes['default']['menu_item_divider']							= 'on';
	$themes['default']['menu_item_divider_color']					= 'rgb(0, 0, 0)';
	$themes['default']['menu_item_divider_glow_opacity']			= '0.1';
	$themes['default']['responsive_breakpoint']						= '1080px';
	$themes['default']['resets']									= 'off';
	return $themes;
}
add_filter( 'megamenu_themes', 'publisher_megamenu_themes', 10 );
