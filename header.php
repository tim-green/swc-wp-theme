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
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
<script type="text/javascript">
// <!-- Start Obs.js Code -->
/*! Obs.js 0.2.1 | (c) Harry Roberts, csswizardry.com | MIT */
;(()=>{const e=document.currentScript;if((!e||e.src||e.type&&"module"===e.type.toLowerCase())&&!1===/^(localhost|127\.0\.0\.1|::1)$/.test(location.hostname))return void console.warn("[Obs.js] Skipping: must be an inline, classic <script> in <head>.",e?e.src?"src="+e.src:"type="+e.type:"type=module");const t=document.documentElement,{connection:i}=navigator;window.obs=window.obs||{};const a=!0===(window.obs&&window.obs.config||{}).observeChanges,o=()=>{const e=window.obs||{},i="number"==typeof e.downlinkBucket?e.downlinkBucket:null;e.connectionCapability="low"===e.rttCategory&&null!=i&&i>=8?"strong":"high"===e.rttCategory||null!=i&&i<=5?"weak":"moderate";const a=!0===e.dataSaver||!0===e.batteryLow||!0===e.batteryCritical;e.conservationPreference=a?"conserve":"neutral";const o="weak"===e.connectionCapability||!0===e.dataSaver||!0===e.batteryCritical;e.deliveryMode="strong"!==e.connectionCapability||o||a?o?"lite":"cautious":"rich",e.canShowRichMedia="lite"!==e.deliveryMode,e.shouldAvoidRichMedia="lite"===e.deliveryMode,["strong","moderate","weak"].forEach(e=>{t.classList.remove(`has-connection-capability-${e}`)}),t.classList.add(`has-connection-capability-${e.connectionCapability}`),["conserve","neutral"].forEach(e=>{t.classList.remove(`has-conservation-preference-${e}`)}),t.classList.add(`has-conservation-preference-${e.conservationPreference}`),["rich","cautious","lite"].forEach(e=>{t.classList.remove(`has-delivery-mode-${e}`)}),t.classList.add(`has-delivery-mode-${e.deliveryMode}`)},n=()=>{if(!i)return;const{saveData:e,rtt:a,downlink:n}=i;window.obs.dataSaver=!!e,t.classList.toggle("has-data-saver",!!e);const s=(e=>Number.isFinite(e)?25*Math.ceil(e/25):null)(a);null!=s&&(window.obs.rttBucket=s);const r=(e=>Number.isFinite(e)?e<75?"low":e<=275?"medium":"high":null)(a);r&&(window.obs.rttCategory=r,["low","medium","high"].forEach(e=>t.classList.remove(`has-latency-${e}`)),t.classList.add(`has-latency-${r}`));const c=(l=n,Number.isFinite(l)?Math.ceil(l):null);var l;if(null!=c){window.obs.downlinkBucket=c;const e=c<=5?"low":c>=8?"high":"medium";window.obs.downlinkCategory=e,["low","medium","high"].forEach(e=>t.classList.remove(`has-bandwidth-${e}`)),t.classList.add(`has-bandwidth-${e}`)}"downlinkMax"in i&&(window.obs.downlinkMax=i.downlinkMax),o()};n(),a&&i&&"function"==typeof i.addEventListener&&i.addEventListener("change",n);const s=e=>{if(!e)return;const{level:i,charging:a}=e,n=Number.isFinite(i)?i<=.05:null;window.obs.batteryCritical=n;const s=Number.isFinite(i)?i<=.2:null;window.obs.batteryLow=s,["critical","low"].forEach(e=>t.classList.remove(`has-battery-${e}`)),s&&t.classList.add("has-battery-low"),n&&t.classList.add("has-battery-critical");const r=!!a;window.obs.batteryCharging=r,t.classList.toggle("has-battery-charging",r),o()};if("getBattery"in navigator&&navigator.getBattery().then(e=>{s(e),a&&"function"==typeof e.addEventListener&&(e.addEventListener("levelchange",()=>s(e)),e.addEventListener("chargingchange",()=>s(e)))}).catch(()=>{}),"deviceMemory"in navigator){const e=Number(navigator.deviceMemory),i=Number.isFinite(e)?e:null;window.obs.ramBucket=i;const a=(r=i,Number.isFinite(r)?r<=1?"very-low":r<=2?"low":r<=4?"medium":"high":null);a&&(window.obs.ramCategory=a,["very-low","low","medium","high"].forEach(e=>t.classList.remove(`has-ram-${e}`)),t.classList.add(`has-ram-${a}`))}var r;if("hardwareConcurrency"in navigator){const e=Number(navigator.hardwareConcurrency),i=Number.isFinite(e)?e:null;window.obs.cpuBucket=i;const a=(e=>Number.isFinite(e)?e<=2?"low":e<=5?"medium":"high":null)(i);a&&(window.obs.cpuCategory=a,["low","medium","high"].forEach(e=>t.classList.remove(`has-cpu-${e}`)),t.classList.add(`has-cpu-${a}`))}(()=>{const e=window.obs||{},i=e.ramCategory,a=e.cpuCategory;let o="moderate";"high"!==i&&"medium"!==i||"high"!==a?("very-low"===i||"low"===i||"low"===a)&&(o="weak"):o="strong",e.deviceCapability=o,["strong","moderate","weak"].forEach(e=>{t.classList.remove(`has-device-capability-${e}`)}),t.classList.add(`has-device-capability-${o}`)})()})();
//# sourceURL=obs.inline.js

// <!-- Ends Obs.js Code -->

// <!-- Start Meta Pixel Code -->
!function(f,b,e,v,n,t,s)   {if(f.fbq)return;n=f.fbq=function(){n.callMethod?   n.callMethod.apply(n,arguments):n.queue.push(arguments)};   if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';   n.queue=[];t=b.createElement(e);t.async=!0;   t.src=v;s=b.getElementsByTagName(e)[0];   s.parentNode.insertBefore(t,s)}(window, document,'script',   'https://connect.facebook.net/en_US/fbevents.js');   fbq('init', '317067237220408');   fbq('track', 'PageView');
// <!-- End Meta Pixel Code -->

</script>
<noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=317067237220408&ev=PageView&noscript=1"/></noscript>
<!-- End Meta Pixel Code -->

<!-- Start Ketch -->
<script>!function(){window.semaphore=window.semaphore||[],window.ketch=function(){window.semaphore.push(arguments)};var e=new URLSearchParams(document.location.search),n=document.createElement("script");n.type="text/javascript", n.src="https://global.ketchcdn.com/web/v2/config/somewhat_creative/website_smart_tag/boot.js", n.defer=n.async=!0,document.getElementsByTagName("head")[0].appendChild(n)}();</script>
<!-- End Ketch -->

<!-- Start Peasy -->
<script src="https://cdn.peasy.so/peasy.js" data-website-id="01jqmj7j492mkam0meer5yy7kf" async></script>
<!-- End Peasy -->
</head>

<body <?php body_class(); ?>>
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
