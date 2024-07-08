<?php
/**
 * The Header
 *
 * Displays all of the <head> section and opens up the content.
 *
 * @package Publisher
 * @since Publisher 1.0
 */
?>

<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>

<head><?php wp_head(); ?>
<script type="text/javascript">
// <!-- Start Heap Code -->
  window.heap=window.heap||[],heap.load=function(e,t){window.heap.appid=e,window.heap.config=t=t||{};var r=document.createElement("script");r.type="text/javascript",r.async=!0,r.src="https://cdn.heapanalytics.com/js/heap-"+e+".js";var a=document.getElementsByTagName("script")[0];a.parentNode.insertBefore(r,a);for(var n=function(e){return function(){heap.push([e].concat(Array.prototype.slice.call(arguments,0)))}},p=["addEventProperties","addUserProperties","clearEventProperties","identify","resetIdentity","removeEventProperty","setEventProperties","track","unsetEventProperty"],o=0;o<p.length;o++)heap[p[o]]=n(p[o])};
  heap.load("1170307049");
// <!-- End Heap Code -->

// <!-- Start Meta Pixel Code -->
!function(f,b,e,v,n,t,s)   {if(f.fbq)return;n=f.fbq=function(){n.callMethod?   n.callMethod.apply(n,arguments):n.queue.push(arguments)};   if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';   n.queue=[];t=b.createElement(e);t.async=!0;   t.src=v;s=b.getElementsByTagName(e)[0];   s.parentNode.insertBefore(t,s)}(window, document,'script',   'https://connect.facebook.net/en_US/fbevents.js');   fbq('init', '317067237220408');   fbq('track', 'PageView');
// <!-- End Meta Pixel Code -->

// <!-- Start Segment Code -->
  !function(){var analytics=window.analytics=window.analytics||[];if(!analytics.initialize)if(analytics.invoked)window.console&&console.error&&console.error("Segment snippet included twice.");else{analytics.invoked=!0;analytics.methods=["trackSubmit","trackClick","trackLink","trackForm","pageview","identify","reset","group","track","ready","alias","debug","page","once","off","on","addSourceMiddleware","addIntegrationMiddleware","setAnonymousId","addDestinationMiddleware"];analytics.factory=function(e){return function(){var t=Array.prototype.slice.call(arguments);t.unshift(e);analytics.push(t);return analytics}};for(var e=0;e<analytics.methods.length;e++){var key=analytics.methods[e];analytics[key]=analytics.factory(key)}analytics.load=function(key,e){var t=document.createElement("script");t.type="text/javascript";t.async=!0;t.src="https://cdn.segment.com/analytics.js/v1/" + key + "/analytics.min.js";var n=document.getElementsByTagName("script")[0];n.parentNode.insertBefore(t,n);analytics._loadOptions=e};analytics._writeKey="c5jU1zLPgiKqmtjET7Uu0VAFPmGS6E5e";;analytics.SNIPPET_VERSION="4.15.3";
  analytics.load("c5jU1zLPgiKqmtjET7Uu0VAFPmGS6E5e");
  analytics.page();
  }}();
// <!-- End  Segment Code -->
</script>
<noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=317067237220408&ev=PageView&noscript=1"/></noscript>
<!-- End Meta Pixel Code -->
<!-- gads -->
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-7556856852527309" crossorigin="anonymous"></script>

</head>

<body <?php body_class(); ?>>
<script data-host="https://microanalytics.io" data-dnt="false" src="https://microanalytics.io/js/script.js" id="ZwSg9rf6GA" async defer></script>

<div id="page" class="hfeed site">

	<?php
		/**
		* @hooked publisher_media_query - 10
		* @hooked publisher_call_to_action - 20
		* @hooked publisher_headband - 30
		*/
		do_action( 'publisher_action_above_site_header' );
	?>

	<div id="header" class="<?php publisher_header_classes( 'site-header' ) ?>" role="banner">

		<?php
			/**
			 * @hooked publisher_site_header - 10
			 */
			do_action( 'publisher_action_site_header' );
		?>

	</div><!-- #header -->

	<?php
		/**
		 * @hooked publisher_featured_header - 10
		 */
		do_action( 'publisher_action_above_site_content' );
	?>

	<div id="content" class="<?php publisher_content_classes( 'site-content' ) ?>">

		<?php do_action( 'publisher_action_above_content_area_container' ); ?>

		<div class="container">

			<?php do_action( 'publisher_action_above_content_area' ); ?>
