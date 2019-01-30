<?php
/**
 * Ditty News Ticker Compatibility File
 * See: http://dittynewsticker.com/
 *
 * @package Publisher
 */

/*-----------------------------------------------------------------------------------*/
/*	Setup Ditty News Ticker Defaults
/*-----------------------------------------------------------------------------------*/

/**
 * Theme Customizer Options
 */
function publisher_ditty_customize_register( $wp_customize ) {

	$wp_customize->add_section( 'publisher_section_news_ticker', array(
		'title'				=> __( 'News Ticker', 'publisher' ),
		'panel'				=> 'publisher_panel_theme_section'
	) );

	// News Ticker
	$wp_customize->add_setting( 'publisher_options_news_ticker', array(
		'type'				=> 'option',
		'sanitize_callback' => 'publisher_sanitize_text',
	) );

	$wp_customize->add_control( 'publisher_options_news_ticker', array(
		'label'        		=> __( 'News Ticker', 'publisher' ),
		'description'  		=> __( 'Insert your Ditty News Ticker Shortcode to show the news ticker in place of the Header Left Menu.', 'publisher' ),
		'section'      		=> 'publisher_section_news_ticker',
		'settings'     		=> 'publisher_options_news_ticker',
		'type'         		=> 'text',
	) );

}
add_action( 'customize_register', 'publisher_ditty_customize_register' );


/**
 * Add span class around ticker title
 */
if ( ! function_exists( 'publisher_mtphr_dnt_ticker_title' ) ) :
function publisher_mtphr_dnt_ticker_title( $title ) {

	return '<span class="title">' . $title . '</span>';

}
endif; // publisher_mtphr_dnt_ticker_title
add_filter( 'mtphr_dnt_ticker_title', 'publisher_mtphr_dnt_ticker_title' );
