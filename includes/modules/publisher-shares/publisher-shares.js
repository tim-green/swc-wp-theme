/**
 * Shares
 *
 * @version 1.0.0
 */

jQuery( document ).ready( function( $ ) {

	/* Share Network
	/*-----------------------------------------------*/

	$( '.publisher-post-shares' ).on( 'click', function(e) {
		var post_id = $( this ).data( 'post-id' );
		$( '#publisher-shares-dialog-' + post_id ).addClass( "active animated fadeIn" );
		$( '#publisher-shares-dialog-' + post_id ).find( '.shares-dialog-container' ).addClass( "animated zoomIn" );
		return false;
	} );

	$( '.publisher-shares-network div' ).on( 'click', function(e) {
		// Don't remove if clicking dialog content
		if ( $( this ).hasClass( 'shares-dialog-content' ) ) {
			e.stopPropagation();
		}

		// Remove
		if ( $( this ).parent().hasClass( 'active' ) ) {
			$( this ).removeClass( "animated zoomIn" );
			$( this ).parent().removeClass( "active animated fadeIn" );
			return false;
		}
	} );


	/* Share Icons
	/*-----------------------------------------------*/

	$( '.publisher-post-shares-icon' ).on( 'click', function(e) {

		// Update Share Count
		var share_to = $( this ).data( 'share-to' );
		var post_id = $( this ).data( 'post-id' );
		var post_url = $( this ).data( 'permalink' );

		jQuery.ajax( {
			type: "POST",
			url: publisher_shares_js_vars.ajaxurl,
			data: {
				'post_id': post_id,
				'social': share_to,
				'action': 'publisher-count-share',
			},
			cache: false,
			success: function( result ) {
				console.log(result);
				$( '.publisher-post-shares-count-' + post_id ).html( result );
			}
		} );

		e.preventDefault();

		// Open Window
		var $this = $( this );
		var url = $this.attr( 'href' );
		var width = $this.data( 'width' );
		var height = $this.data( 'height' );
		var leftPosition, topPosition;
		//Allow for borders.
		leftPosition = (window.screen.width / 2) - ((width / 2) + 10);
		//Allow for title and status bars.
		topPosition = (window.screen.height / 2) - ((height / 2) + 50);

		var windowFeatures = "status=no,height=" + height + ",width=" + width + ",resizable=yes,left=" + leftPosition + ",top=" + topPosition + ",screenX=" + leftPosition + ",screenY=" + topPosition + ",toolbar=no,menubar=no,scrollbars=no,location=no,directories=no";
		window.open( url,'sharer', windowFeatures );

	} );

} );