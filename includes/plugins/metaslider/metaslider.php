<?php
/**
 * Meta Slider Compatibility File
 * See: https://www.metaslider.com/
 *
 * @package Publisher
 */

/*-----------------------------------------------------------------------------------*/
/*	Setup Meta Slider Defaults
/*-----------------------------------------------------------------------------------*/

/**
 * Add in the Publisher
 *
 * @param $themes
 * @param $current
 * @return string
 */
function publisher_metaslider_get_available_themes( $themes, $current ) {
	$themes .= "<option value='publisher' class='option flex' " . selected( 'publisher', $current, false ) . ">" . __( 'Publisher', 'publisher' ) . "</option>";
	return $themes;
}
add_filter( 'metaslider_get_available_themes', 'publisher_metaslider_get_available_themes', 5, 2 );


/**
 * Change the HTML for the front page slider
 *
 * @param $html
 * @param $slide
 * @param $settings
 *
 * @return string The new HTML
 */
function publisher_metaslider_image_flex_slider_markup($html, $slide, $settings){

	if ( ! empty( $settings['theme'] ) && 'publisher' == $settings['theme'] ) {

		if ( ! empty( $slide['caption'] ) && function_exists( 'filter_var' ) && filter_var( $slide['caption'], FILTER_VALIDATE_URL ) !== false ) {

			$html = sprintf( "<img src='%s' class='msDefaultImage' width='%d' height='%d' />", esc_url( $slide['thumb'] ), intval( $settings['width'] ), intval( $settings['height'] ) );

			if ( strlen( $slide['url'] ) ) {
				$html = '<a href="' . esc_url( $slide['url'] ) . '" target="' . esc_attr( $slide['target'] ) . '">' . $html . '</a>';
			}

			$caption = '<div class="content">';
			if ( strlen( $slide['url'] ) ) $caption .= '<a href="' . esc_url( $slide['url'] ) . '" target="' . esc_attr( $slide['target'] ) . '">';
			$caption .= sprintf( '<img src="%s" width="%d" height="%d" />', esc_url( $slide['caption'] ), intval( $settings['width'] ), intval( $settings['height'] ) );
			if ( strlen( $slide['url'] ) ) $caption .= '</a>';
			$caption .= '</div>';

			$html = $caption . $html;

			$thumb = isset( $slide['data-thumb'] ) && strlen( $slide['data-thumb'] ) ? " data-thumb=\"{$slide['data-thumb']}\"" : "";

			$html = '<li style="display: none;"' . $thumb . ' class="publisher-slide-with-image">' . $html . '</li>';

		} elseif( ! empty( $slide['caption'] ) ) {

			// Add Image
			$html = sprintf( "<img src='%s' class='msDefaultImage' width='%d' height='%d' />", esc_url( $slide['thumb'] ), intval( $settings['width'] ), intval( $settings['height'] ) );

			// Add Caption
			if ( strlen( $slide['caption'] ) ) {
				$html .= '<div class="caption-wrap">';
				$html .= '<div class="caption">';
				$html .= '<div class="caption-inside">';
				$html .= '<div class="overlay"></div>';
				$html .= '<div class="container">';
				$html .= sprintf( '<header class="hero-header">%1$s%2$s</header>',
					$slide['title'] ? '<h1 class="hero-title">' . $slide['title'] . '</h1>' : '',
					$slide['caption'] ? '<div class="hero-description">' . $slide['caption'] . '</div>' : ''
				);
				$html .= '</div>';
				$html .= '</div>';
				$html .= '</div>';
				$html .= '</div>';
			}

			if ( strlen( $slide['url'] ) ) {
				$html = '<a href="' . esc_url( $slide['url'] ) . '" target="' . esc_attr( $slide['target'] ) . '">' . $html . '</a>';
			}

			$thumb = isset( $slide['data-thumb'] ) && strlen( $slide['data-thumb'] ) ? " data-thumb=\"{$slide['data-thumb']}\"" : "";

			$html = '<li style="display: none;"' . $thumb . ' class="publisher-slide-hero has-featured-header-image">' . $html . '</li>';

		}

	}

	return $html;

}
add_filter( 'metaslider_image_flex_slider_markup', 'publisher_metaslider_image_flex_slider_markup', 10, 3 );


/**
 * Change default slideshow size
 */
function publisher_metaslider_default_parameters( $params ) {
	$params['width'] = 1198;
	$params['height'] = 420;
	return $params;
}
add_filter( 'metaslider_default_parameters', 'publisher_metaslider_default_parameters', 10, 1 );


/**
 * Filter metaslider settings when this theme is selected
 *
 * @param $settings
 */
function publisher_metaslider_slide_width( $settings ){
	if ( ! empty( $settings['theme'] ) && 'publisher' == $settings['theme'] ) {
		$settings['width'] = 1198;
	}

	return $settings;
}
add_filter( 'sanitize_post_meta_ml-slider_settings', 'publisher_metaslider_slide_width' );


/**
 * Filter this theme flex slider settings
 *
 * @param $settings
 */
function publisher_metaslider_flex_slider_parameters( $options, $slider_id, $settings ) {
	if ( ! empty( $settings['theme'] ) && 'publisher' == $settings['theme'] ) {
		$options['start'][] = "$('.site-featured-header .metaslider').fadeTo( 'slow', 1 );";
	}
	return $options;
}
add_filter('metaslider_flex_slider_parameters', 'publisher_metaslider_flex_slider_parameters', 10, 3);
