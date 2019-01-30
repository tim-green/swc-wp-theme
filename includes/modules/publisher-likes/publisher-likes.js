/**
 * Likes
 *
 * @version 1.0.0
 */

;( function( $, window, document, undefined ) {

	"use strict";

	$( document ).ready( function ($) {

		var $post_likes = $( '.publisher-post-likes' );

		// When Likes is clicked
		$post_likes.on( 'click', function(e) {
			var link = $(this);
			var post_count_wrap = link.find( '.likes-count' );
			var post_count = link.find( '.likes-count span' );

			// If already liked, return false
			if ( link.hasClass( 'liked' ) ) return false;

			// Add likes and add liked message
			var id = post_count_wrap.data( 'post-id' );

			$.post( publisher_likes_js_vars.ajaxurl, { action:'publisher-add-like', likes_id:id }, function( data ) {
				post_count.html( data ).attr( 'title', publisher_likes_js_vars.liked_message );
				link.addClass( 'liked' );
			} );

			return false;
		} );

		// If caching is enabled
		if ( publisher_likes_js_vars.is_caching_enabled ) {
			// Get like button when caching engine is enabled
			var post_likes_ids = [];
			$post_likes.each( function(){
				post_likes_ids.push( $(this).data( 'post-id' ) );
			} );

			$.post( publisher_likes_js_vars.ajaxurl, { action:'publisher-get-likes', post_ids:post_likes_ids }, function(data){
				$.each( data, function( id, liked_button_html ) {
					$( '[id='+id+']' ).html( liked_button_html );
				} )
			}, 'json' );
		}

	} );

})( jQuery, window , document );
