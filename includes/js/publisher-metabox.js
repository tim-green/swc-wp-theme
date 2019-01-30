/**
 * Metabox Scripts
 *
 * @version 1.0.0
 */
jQuery( document ).ready( function( $ ) {

	"use strict";


	/* Color Picker
	/*-----------------------------------------------*/

	$( '.tax-color-picker' ).wpColorPicker();


	/* Category Metabox
	/*-----------------------------------------------*/

	// Open Media Library
	$( '.button-upload' ).on( 'click', function(e) {
		e.preventDefault();

		// Set parent according to add/edit mode
		var $parent = $(this).closest('.form-field'),
			$input 	= $parent.find('input[type="text"]').eq(0),
			$img 	= $parent.find('img').eq(0),
			frame 	= false;

		if ( frame ) {
			frame.open();
			return;
		}

		frame = wp.media();

		// Register Event
		frame.on( "select", function() {
			// Grab the selected attachment
			var attachment = frame.state().get("selection").first();

			// Show the full URL for the file
			$input.val( attachment.attributes.url );

			// Show the image
			$img.attr( 'src', attachment.attributes.sizes['thumbnail'].url );
			$parent.find( '.button-remove' ).css( 'display', 'inline-block' );

			// Close the media frame
			frame.close();
		});

		// Show media frame
		frame.open();
	});

	// Remove Button
	$( '.button-remove' ).on( 'click', function(e) {
		e.preventDefault();

		var $parent = $(this).closest('.form-field'),
			$input 	= $parent.find('input'),
			$img 	= $parent.find('img');

		// Clear
		$input.val('');
		$img.attr('src', '' );
		$(this).css( 'display', 'none' );
	});

});
