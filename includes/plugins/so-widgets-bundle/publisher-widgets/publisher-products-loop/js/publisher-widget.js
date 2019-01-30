jQuery( function( $ ) {

	"use strict";


	/* Owl Carousel
	/*-----------------------------------------------*/

	// Carousel Layout
	$( '.publisher-products-loop-widget[data-layout="carousel"] .owl-carousel' ).each( function() {
		var $carousel = $( this ); // .owl-carousel
		var $parents = $( this ).parents( '.publisher-products-loop-widget[data-layout="carousel"]' );
		var $items = $carousel.attr( 'data-columns' ) ? $carousel.attr( 'data-columns' ) : 4;
		var $direction = publisher_scripts_js_vars.is_rtl ? true : false;

		$carousel.owlCarousel( {
			nav : true,
			navText : '',
			dots : false,
			margin : 16,
			loop : true,
			center : false,
			nestedItemSelector : 'product',
			rtl: $direction,
			afterInit : initialize_load_items( $parents ),
			responsive : {
				0 : {
					items : 1,
					mouseDrag : true
				},
				601 : {
					items : 2,
					mouseDrag : true
				},
				783 : {
					items : 3,
					mouseDrag : true
				},
				960 : {
					items : $items,
					mouseDrag : false
				}
			}
		} );

		// Callbacks
		$carousel.on( 'changed.owl.carousel', function(e) {
			//run_hoverIntent( $parents );
			//equalCarouselHeight( $carousel );
		} );

	} );


	/* Loading Items
	/*-----------------------------------------------*/

	// Hide the loader icon and add loaded class
	function initialize_load_items( $parents ) {
		$parents.addClass( "loaded" );
		$parents.children( ".loader-icon" ).hide();
	}


	/* Hover Intent
	/*-----------------------------------------------*/

	// Hover Targets
	function run_hoverIntent( $parents ) {
		$( $parents ).find( '.featured-preview' ).hoverIntent( {
			over: hover_mouseover,
			out: hover_mouseout,
			interval: 25,
			timeout: 100
		} );
	}

	// Match Heights
	$( '.publisher-products-loop-widget[data-layout="carousel"]' ).each( function() {
		var $parents = $( this );
		run_hoverIntent( $parents );
	} );

	// Hover Functions
	function hover_mouseover() {
		$( this ).addClass( "active" );
	};

	function hover_mouseout() {
		$( this ).removeClass( "active" );
	};


	/* Match Height
	/*-----------------------------------------------*/

	// Equalize Carousel Heights
	function equalCarouselHeight( $carousel ) {
		$( $carousel ).find( '.entry-header' ).matchHeight();
	}

	// Match Heights
	$( '.publisher-products-loop-widget[data-layout="carousel"] .owl-carousel' ).each( function() {
		var $carousel = $( this );
		equalCarouselHeight( $carousel );
	} );

} );
