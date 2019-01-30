<div class="social-media-button-container publisher <?php echo esc_attr( $instance['design']['publisher_social_icon_alignment'] ) ?>">
	<?php foreach( $networks as $network ) :
		$classes = array();
		if( !empty($instance['design']['hover']) ) $classes[] = 'ow-button-hover';
		$classes[] = "sow-social-media-button-" . sanitize_html_class( $network['name'] );
		$classes[] = "sow-social-media-button";
		$button_attributes = array(
			'class' => esc_attr(implode(' ', $classes))
		);
		if(!empty($instance['design']['new_window'])) $button_attributes['target'] = '_blank';
		if ( ! empty( $network['url'] ) ) $button_attributes['href'] = sow_esc_url( $network['url'] );
		?>

		<div class="social-icon-container <?php echo 'publisher-column-' . intval( $instance['design']['publisher_social_icon_columns'] ) ?>">
			<a <?php foreach($button_attributes as $name => $val) echo esc_attr( $name ) . '="' . esc_attr( $val ) . '" ' ?>>
				<span class="social-text-container">
					<!-- premium-<?php echo esc_attr( $network['name'] ) ?> -->
					<?php echo siteorigin_widget_get_icon( $network['icon_name'] ); ?>
					<!-- endpremium -->

					<?php echo esc_attr( $network['social_text'] ) ? '<span class="social-text">' . esc_attr( $network['social_text'] ) . '</span>' : '' ?>
				</span>
			</a>
		</div>
	<?php endforeach; ?>
</div>
