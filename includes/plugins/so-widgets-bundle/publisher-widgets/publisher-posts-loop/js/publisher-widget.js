jQuery( function( $ ) {

	"use strict";


	/* Ajax
	/*-----------------------------------------------*/

	// Asynch
	$( '.publisher-posts-loop-widget[data-ajax="true"]' ).on( 'click', '.navigation a', function(e) {

		// Variables
		var $post_widget = $( this ).parents( '.publisher-posts-loop-widget[data-ajax="true"]' );
		var $id = $post_widget.attr( 'data-id' );
		var $layout = $post_widget.attr( 'data-layout' );
		var $browser_history = $post_widget.attr( 'data-history' );
		var $post_widget_id = ' .publisher-posts-loop-widget[data-id="' + $id + '"]';
		var $href = $( this ).attr( 'href' );

		$post_widget.addClass( 'paged' );
		$post_widget.addClass( 'loading' );
		$( this ).addClass( 'loading' );

		// Load Content
		if ( $post_widget.attr( "data-pagination" ) == 'load-more' ) {
			loadMorePostWidget( $href, $post_widget, $post_widget_id, $layout, $browser_history );
		} else {
			loadPostWidget( $href, $post_widget, $post_widget_id, $layout, $browser_history );
		}

		return false;

	} );

	// Load Post Widget
	function loadPostWidget( $href, $post_widget, $post_widget_id, $layout, $browser_history ) {
		$post_widget.load( $href + $post_widget_id + ' .posts-index' , function() {

			$post_widget.removeClass( 'loading' );

			$( 'body' ).animate( {
				scrollTop: $post_widget.offset().top - 125 }, 700
			);

			// Reload Grid
			if ( 'grid' == $layout ) {
				var $post_bricks = $( $post_widget_id ).find( '.grid-layout .site-main, .cover-layout .site-main' );
				$post_bricks.imagesLoaded( function() {
					$post_bricks.masonry( {
						itemSelector: '.hentry',
						gutterWidth: 0,
						isResizable: true,
						isRTL: publisher_scripts_js_vars.is_rtl
					} );
					$( $post_widget_id + " .hentry" ).fadeTo( "slow", 1 );
				} );
			}

			// Reload Carousel
			if ( 'carousel' == $layout || 'mini-carousel' == $layout || 'slider' == $layout ) {
				var $carousel = $( $post_widget_id ).find( '.owl-carousel' );
				buildOwlCarousel( $carousel );
			}

			// Update Libraries
			run_hoverIntent();

			if ( 'true' == $browser_history ) {
				history.pushState( {}, '', $href );
			}

		} );
	};

	// Load More Post Widget
	function loadMorePostWidget( $href, $post_widget, $post_widget_id, $layout, $browser_history ) {
		$.get( $href, function( data ) {

			$post_widget.removeClass( 'loading' );

			// Get articles
			if ( 'listing-h' == $layout || 'listing-v' == $layout ) {
				$( data ).find( $post_widget_id + ' .posts-index .listing-posts' ).appendTo( $post_widget_id + ' .posts-index .site-main' );
			} else if ( 'carousel' == $layout || 'mini-carousel' == $layout || 'slider' == $layout ) {
				$( data ).find( $post_widget_id + ' .posts-index .owl-carousel' ).appendTo( $post_widget_id + ' .posts-index .site-main' );
			} else {
				$( data ).find( $post_widget_id + ' .posts-index article' ).appendTo( $post_widget_id + ' .posts-index .site-main' );
			}

			// Change navigation link
			$( $post_widget_id + ' .navigation' ).load( $href + ' ' + $post_widget_id + ' .navigation-wrap' );

			// Reload Grid
			if ( 'grid' == $layout ) {
				var $post_bricks = $( $post_widget_id ).find( '.grid-layout .site-main, .cover-layout .site-main' );
				$post_bricks.imagesLoaded( function() {
					$post_bricks.masonry( "reloadItems" );
					$post_bricks.masonry();
					$( $post_widget_id + " .hentry" ).fadeTo( "slow", 1 );
				} );
			}

			// Reload Carousel
			if ( 'carousel' == $layout || 'mini-carousel' == $layout || 'slider' == $layout ) {
				var $carousel = $( $post_widget_id ).find( '.owl-carousel' );
				buildOwlCarousel( $carousel );
			}

			// Update Libraries
			run_hoverIntent();

			if ( 'true' == $browser_history ) {
				history.pushState( {}, '', $href );
			}

		} );
	};


	/* Masonry
	/*-----------------------------------------------*/

	// Grid Layouts
	$( '.publisher-posts-loop-widget[data-layout="grid"]' ).each( function() {
		var $post_widget = $( this );
		var $post_bricks = $post_widget.find( '.site-main' );
		var $parents = $post_widget.find( '.publisher-widget-site-content' );

		$post_bricks.imagesLoaded( function() {
			$post_bricks.masonry( {
				itemSelector: '.hentry',
				gutterWidth: 0,
				isResizable: true,
				isRTL: publisher_scripts_js_vars.is_rtl
			} );

			initialize_load_items( $parents );
			$post_widget.find( '.hentry' ).fadeTo( "slow", 1 );
		} );

	} );


	/* Owl Carousel
	/*-----------------------------------------------*/

	// Build Owl Carousel
	function buildOwlCarousel( $carousel ) {
		var $parents 		= $carousel.parents( '.site-main' );
		var $items 			= $carousel.attr( 'data-items' );
		var $mobile_items 	= $carousel.attr( 'data-mobile-items' );
		var $tablet_items 	= $carousel.attr( 'data-tablet-items' );
		var $margin 		= $carousel.attr( 'data-margin' );
		var $nav_buttons 	= $carousel.attr( 'data-navigation' ) == 'true' ? true : false;
		var $autoplay 		= $carousel.attr( "data-autoplay" ) == 'true' ? true : false;
		var $autoheight 	= $carousel.attr( "data-autoheight" ) == 'true' ? true : false;
		var $animation 		= $carousel.attr( "data-animation" ) == 'fade' ? 'fadeOut' : false;
		var $timeout		= $carousel.attr( 'data-timeout' );
		var $speed			= $carousel.attr( 'data-speed' );
		var $direction 		= publisher_scripts_js_vars.is_rtl ? true : false;

		$carousel.imagesLoaded( function () {
			$carousel.owlCarousel( {
				nav : $nav_buttons,
				navText : '',
				dots : false,
				margin : parseInt( $margin ),
				loop : $nav_buttons,
				center : false,
				afterInit : initialize_load_items( $parents ),
				animateOut: $animation,
				autoplay: $autoplay,
				autoplayTimeout: parseInt( $timeout ),
				autoplayHoverPause: true,
				autoHeight: $autoheight,
				smartSpeed: parseInt( $speed ),
				rtl: $direction,
				responsive : {
					0 : {
						items : parseInt( $mobile_items ),
						mouseDrag : true
					},
					783 : {
						items : parseInt( $tablet_items ),
						mouseDrag : true
					},
					960 : {
						items : parseInt( $items ),
						mouseDrag : false
					}
				}
			} );

			// Update libraries
			run_hoverIntent();
			equalCarouselHeight( $carousel );

			// Callbacks
			$carousel.on( 'changed.owl.carousel', function(e) {
				//equalCarouselHeight( $carousel );
			} );

		} );
	};


	// Carousel Layout
	$( '.publisher-posts-loop-widget .owl-carousel' ).each( function() {
		var $carousel = $( this );
		buildOwlCarousel( $carousel );
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
	function run_hoverIntent() {
		$( ".publisher-posts-loop-widget .featured-preview, .publisher-posts-loop-widget .cover.has-post-thumbnail .article-wrap, .posts-index .format-image.hentry:not(.publisher-ext) .article-wrap" ).hoverIntent( {
			over: hover_mouseover,
			out: hover_mouseout,
			interval: 25,
			timeout: 100
		} );
	}
	run_hoverIntent();

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
		$( $carousel ).find( '.equalize' ).matchHeight();
	}

	// Match Heights
	$( '.publisher-posts-loop-widget .mini-grid-layout' ).each( function() {
		var $carousel = $( this );
		equalCarouselHeight( $carousel );
	} );

} );
