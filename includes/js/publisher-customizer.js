/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {

	"use strict";

	// Site Title
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );

	// Tagline
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );

	// Hide Site Tagline
	wp.customize( 'publisher_options_hide_tagline', function( value ) {
		value.bind( function( to ) {
			if ( to ) {
				$( '.site-description' ).hide();
			} else {
				$( '.site-description' ).show();
			}
		} );
	} );

	// Footer Tagline
	wp.customize( 'publisher_options_footer_tagline', function( value ) {
		value.bind( function( to ) {
			$( '.footnote .site-info' ).text( to );
		} );
	} );

	// Header Image Opacity
	wp.customize( 'publisher_options_header_image_opacity', function( value ) {
		value.bind( function( to ) {
			$( '.site-header-image' ).css( 'opacity', to );
		} );
	} );

	// Footer Image Opacity
	wp.customize( 'publisher_options_footer_image_opacity', function( value ) {
		value.bind( function( to ) {
			$( '.site-footer-image' ).css( 'opacity', to );
		} );
	} );

} )( jQuery );
