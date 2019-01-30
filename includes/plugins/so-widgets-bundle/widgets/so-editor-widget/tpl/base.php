<?php if ( ! empty( $instance['title'] ) ) echo $args['before_title'] . esc_html( $instance['title'] ) . $args['after_title'] ?>

<div class="siteorigin-widget-tinymce textwidget hentry">
	<div class="article-wrap">
		<div class="entry-wrap">
			<div class="entry-content">
				<?php echo esc_attr( $text ) ?>
			</div><!-- .entry-content -->
		</div><!-- .entry-wrap -->
	</div><!-- .article-wrap -->
</div>
