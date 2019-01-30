jQuery( document ).ready( function( $ ) {

	/* Owl Carousel
	/*-----------------------------------------------*/

	$( ".publisher-hero-widget .owl-carousel" ).each( function() {
		var $carousel 		= $( this ); // .owl-carousel
		var $parents 		= $( this ).parents( ".publisher-hero-widget" );
		var $nav_buttons 	= $carousel.attr( "data-navigation" ) == 'true' ? true : false;
		var $autoplay 		= $carousel.attr( "data-autoplay" ) == 'true' ? true : false;
		var $autoheight 	= $carousel.attr( "data-autoheight" ) == 'true' ? true : false;
		var $speed			= $carousel.attr( 'data-speed' ) ? parseInt( $carousel.attr( 'data-speed' ) ) : 800;
		var $timeout 		= $carousel.attr( 'data-timeout' ) ? parseInt( $carousel.attr( 'data-timeout' ) ) : 5000;
		var $animation 		= $carousel.attr( "data-animate" ) == 'fade' ? 'fadeOut' : false;
		var $direction 		= publisher_scripts_js_vars.is_rtl ? true : false;
		var $mobile 		= publisher_scripts_js_vars.wp_is_mobile ? true : false;
		var $items 			= 1;

		$carousel.imagesLoaded( function () {
			$carousel.owlCarousel( {
				nav : $nav_buttons,
				navText : '',
				margin : 0,
				loop : $nav_buttons,
				dots: false,
				center : false,
				mouseDrag : false,
				afterInit : initialize_load_items( $parents ),
				animateOut: $animation,
				items: 1,
				autoplay: $autoplay,
				autoplayTimeout: $timeout,
				autoplayHoverPause: true,
				smartSpeed: $speed,
				rtl: $direction,
				autoHeight: $autoheight,
			} );

			// Autoplay each video
			$( 'video' ).each( function() {
				$( this ).get(0).play();
			} );

			// Update libraries
			if ( ! $autoheight ) {
				equalHeight();
			}

			// Callbacks
			$carousel.on( 'changed.owl.carousel', function(e) {

				// If screen size is tablet or mobile
				if ( $mobile || $("#media-query").css( "max-width" ) == "782px" ) {
					// Don't do anything

				} else {
					// Destroy all animations
					$carousel.find( '.site-featured-header .hero-content.animated' ).each( function() {
						$get_animation = $( this ).parents( ".site-featured-header" ).attr( "data-animation" );
						$carousel.find( '.site-featured-header .hero-content' ).removeClass( 'animated ' + $get_animation );
					} );

					// Reinit animations
					idx = e.item.index;
					$animation = $carousel.find( '.site-featured-header' ).eq(idx).attr( "data-animation" );
					$carousel.find( '.site-featured-header .hero-content' ).eq(idx).addClass( 'animated ' + $animation );
				}

			} );

		} );

	} );


	/* Loading Items
	/*-----------------------------------------------*/

	// Hide the loader icon and fade in the items
	function initialize_load_items( $parents ) {
		$parents.addClass( "loaded" );
		$parents.find( ".loader-icon" ).hide();
	}


	/* Match Height
	/*-----------------------------------------------*/

	// Equalize column heights
	function equalHeight() {
		$( ".publisher-hero-widget .equalize" ).matchHeight();
	}

} );
