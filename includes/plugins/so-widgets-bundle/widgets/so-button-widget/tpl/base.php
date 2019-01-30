<?php
$classes = array();
$classes[] = 'hero-link button';
?>
<div class="publisher-button ow-button-base ow-button-align-<?php echo esc_attr($instance['design']['align']) ?>">

	<?php
	$button_attributes = array(
		'class' => esc_attr( implode( ' ', $classes ) )
	);
	if ( ! empty( $instance['new_window'] ) ) $button_attributes['target'] = '_blank';
	if ( ! empty( $instance['url'] ) ) $button_attributes['href'] = sow_esc_url( $instance['url'] );
	if ( ! empty( $instance['attributes']['id'] ) ) $button_attributes['id'] = esc_attr( $instance['attributes']['id'] );
	if ( ! empty( $instance['attributes']['title'] ) ) $button_attributes['title'] = esc_attr( $instance['attributes']['title'] );
	if ( ! empty( $instance['attributes']['onclick'] ) ) $button_attributes['onclick'] = esc_attr( $instance['attributes']['onclick'] );
	?>

	<a <?php foreach( $button_attributes as $name => $val ) echo esc_attr( $name ) . '="' . esc_attr( $val ) . '" ' ?>>
		<span>
			<?php
				if ( ! empty( $instance['button_icon']['icon'] ) ) {
					$attachment = wp_get_attachment_image_src( $instance['button_icon']['icon'] );
					if ( ! empty( $attachment ) ) {
						$icon_styles[] = 'background-image: url(' . sow_esc_url($attachment[0]) . ')';
						?><div class="sow-icon-image" style="<?php echo implode( '; ', $icon_styles ) ?>"></div><?php
					}
				}
				else {
					$icon_styles = array();
					if ( ! empty( $instance['button_icon']['icon_color'] ) ) $icon_styles[] = 'color: ' . $instance['button_icon']['icon_color'];
					echo siteorigin_widget_get_icon( $instance['button_icon']['icon_selected'], $icon_styles );
				}
			?>

			<?php echo wp_kses_post( $instance['text'] ) ?>
		</span>
	</a>

</div>
