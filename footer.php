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
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-84692209-1', 'auto');
  ga('send', 'pageview');

</script>
</body>
</html>
