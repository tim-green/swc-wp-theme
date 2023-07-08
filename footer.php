<?php
/**
 * The Footer
 *
 * Contains footer content and the closing of the #page div elements.
 *
 * @package Publisher
 * @since Publisher 1.0
 */
?>

			<?php do_action( 'publisher_action_below_content_area' ); ?>

		</div><!-- .container -->

		<?php do_action( 'publisher_action_below_content_area_container' ); ?>

    <!-- scroll to top -->
	</div><!-- #content -->

	<?php do_action( 'publisher_action_above_site_footer' ); ?>

	<div id="footer" class="<?php publisher_footer_classes( 'site-footer' ) ?>">

		<?php
			/**
			 * @hooked publisher_site_footer - 10
			 */
			do_action( 'publisher_action_site_footer' );
		?>

	</div><!-- #footer -->

	<?php do_action( 'publisher_action_below_site_footer' ); ?>

</div><!-- #page -->

<?php wp_footer(); ?>
<!-- Hotjar Tracking Code -->
<script>
    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:294102,hjsv:5};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'//static.hotjar.com/c/hotjar-','.js?sv=');
</script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-84692209-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-84692209-1');
</script>
</script>
</body>
</html>
