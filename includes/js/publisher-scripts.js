/**
 * Main Scripts
 *
 * @version 1.0.0
 */


jQuery( document ).ready( function( $ ) {

	"use strict";


	/* Categories
	/*-----------------------------------------------*/

	// Add Class to Category Parent
	$( "li.cat-item:has(ul.children)" ).addClass( "cat-item-has-children" );




	/* Fitvids
	/*-----------------------------------------------*/

	function run_fitvid() {
		$( ".hentry, .site-featured-header, .featured-preview, .sow-video-wrapper" ).fitVids();
	}
	run_fitvid();




	/* Navigation
	/*-----------------------------------------------*/

	// Search Toggle
	$( ".search-toggle a" ).on( 'click', function(e) {
		e.preventDefault();
		$( "body" ).toggleClass( "active-search" );
		$( ".search-toggle .fa-search, .search-toggle .fa-times" ).toggle();
		$( ".menu-item-search .search-form .search-field" ).focus();
		return false;
	} );

	// Menu Toggle
	$( ".menu-toggle" ).on( 'click', function(e) {
		e.preventDefault();
		$( "body" ).toggleClass( "active-menu" );
		return false;
	} );

	// Toggle sub menus on mobile
	$( ".menu" ).find( "li.menu-item-has-children" ).on( 'click', function(e) {
		$( this ).toggleClass( "active-sub-menu" );
		return false;
	} );

	// Don't fire sub menu toggle if a user is trying to click the link
	$( ".menu-item-has-children a" ).on( 'click', function(e) {
		e.stopPropagation();
		return true;
	} );




	/* Max Mega Menu
	/*-----------------------------------------------*/

	// Remove toggle class
	$( ".mega-toggle-hover-button" ).on( 'click', function(e) {
		if ( $( this ).parent().hasClass( 'mega-toggle-on' ) ) {
			$( this ).parent().removeClass( 'mega-toggle-on' );
		} else {
			$( this ).parent().addClass( 'mega-toggle-on' );
		}
		$( this ).parent().unbind( 'mouseenter mouseleave' );
		return false;
	} );




	/* Masonry
	/*-----------------------------------------------*/

	// Footer Widgets
	var $footer_bricks = $( "#footer-widgets" );

	$footer_bricks.imagesLoaded( function() {
		$footer_bricks.masonry( {
			itemSelector: 'aside',
			gutterWidth: 0,
			isResizable: true,
			isRTL: publisher_scripts_js_vars.is_rtl,
		} );
	});


	// Posts Index Grid Layout
	if ( publisher_scripts_js_vars.load_masonry ) {

		var $bricks = $( ".posts-index .grid-layout #main" );

		$bricks.imagesLoaded( function() {
			$bricks.masonry( {
				itemSelector: '.hentry',
				gutterWidth: 0,
				isResizable: true,
				isRTL: publisher_scripts_js_vars.is_rtl,
			} );

			// Fade posts in after images are ready (prevents jumping and re-rendering)
			$( ".primary.grid-layout .loader-icon" ).hide();
			$( ".primary.grid-layout .hentry" ).fadeTo( "fast", 1 );
		} );
	}


	// WooCommerce Products
	var $shop_bricks = $( ".woocommerce ul.products" ).not( '.owl-carousel.products, #footer-widgets .products, .publisher-products-loop-widget[data-type="product"] .products' );

	if ( $shop_bricks.length ) {
		$( $shop_bricks ).each( function() {
			var $parents = $( this ).parents( ".woocommerce" );

			$shop_bricks.imagesLoaded( function() {
				$shop_bricks.masonry( {
					itemSelector: 'li.product',
					gutterWidth: 0,
					isResizable: true,
					isRTL: publisher_scripts_js_vars.is_rtl,
					//itemSelector: 'li.hentry, .publisher-products-loop-widget .products li.product',
					//stamp: '.publisher-shop-main li.product-category'
				} );

				// Fade posts in after images are ready (prevents jumping and re-rendering)
				initialize_load_items( $parents );
			} );
		} );

	} else {
		$( ".woocommerce.loader-icon" ).hide();
	}




	/* Owl Carousel
	/*-----------------------------------------------*/

	// Owl Carousel for the galleries
	$( ".featured-preview.owl-gallery .owl-carousel" ).not( ".entry-layout-cover.has-post-gallery .featured-preview.owl-gallery .owl-carousel" ).each( function() {
		var $carousel 	= $( this ); // .owl-carousel
		var $parents 	= $( this ).parents( ".featured-preview" );
		var $direction 	= publisher_scripts_js_vars.is_rtl ? true : false;

		$carousel.imagesLoaded( function () {
			$carousel.owlCarousel({
				loop : true,
			    items: 1,
			    autoHeight: true,
			    nav : true,
				navText : '',
			    dots: true,
			    dotsData: true,
			    animateOut: 'fadeOut',
			    rtl: $direction,
			    afterInit : initialize_load_items( $parents ),
			} );
		} );

	} );

	// Owl Carousel for the Cover galleries
	$( ".entry-layout-cover.has-post-gallery .featured-preview.owl-gallery .owl-carousel" ).each( function() {
		var $carousel = $( this ); // .owl-carousel
		var $parents = $( this ).parents( ".featured-preview" );
		var $direction 	= publisher_scripts_js_vars.is_rtl ? true : false;

		$carousel.imagesLoaded( function () {
			$carousel.owlCarousel( {
				loop : true,
				nav : true,
				navText : '',
				items : 1,
				dots: true,
			    dotsData: true,
				center : true,
				autoWidth: true,
				margin : 5,
				rtl: $direction,
				afterInit : initialize_load_items( $parents ),
			} ).owlCarousel('update');
		} );

	} );




	/* Hover Intent
	/*-----------------------------------------------*/

	// Hover Targets
	function run_hoverIntent() {
		$( ".featured-preview, .main-navigation, .post-navigation.post-image .article-wrap, .posts-index .format-image.hentry .article-wrap, .comment-form .logged-in-as" ).hoverIntent( {
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




	/* Loading Items
	/*-----------------------------------------------*/

	// Hide the loader icon and fade in the items
	function initialize_load_items( $parents ) {
		$parents.addClass( "loaded" );

		$parents.find( ".products" ).fadeTo( "slow", 1 );
		$parents.find( ".loader-icon" ).hide();
	}
	


	/* Infinite Scroll
	/*-----------------------------------------------*/

	$( document.body ).on( "post-load", function () {
		$( 'span' ).remove( ".infinite-loader" );

		if ( publisher_scripts_js_vars.load_masonry ) {
			$bricks.imagesLoaded( function() {
				$bricks.masonry( "reload" );
				$( ".hentry" ).fadeTo( "slow", 1 );
			});
		}

		// Update libraries
		run_hoverIntent();
	} );

} );
