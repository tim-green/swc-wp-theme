<?php
	$last_row = floor( ( count( $instance['features'] ) - 1 ) / $instance['per_row'] );

	$classes = array();
	if ( $instance['responsive'] ) $classes[] = 'sow-features-responsive';
	if ( is_rtl() ) $classes[] = 'rtl';
?>

<div class="sow-features-list <?php echo esc_attr( implode( ' ', $classes ) ) ?>">

	<?php foreach( $instance['features'] as $i => $feature ) : ?>

		<?php if ( $i % $instance['per_row'] == 0 && $i != 0 ) : ?>
			<div class="sow-features-clear"></div>
		<?php endif; ?>

		<?php
			// Attributes
			$features_classes ='sow-features-feature ';
			if ( floor( $i / $instance['per_row'] ) == $last_row ) $features_classes .= 'sow-features-feature-last-row ';
			if ( ! empty( $instance['publisher_features_align'] ) ) $features_classes .= $instance['publisher_features_align'];
		?>

		<div class="<?php echo esc_attr( $features_classes ) ?>" style="width: <?php echo round( 100 / $instance['per_row'], 3 ) ?>%">

			<div class="publisher-features-icon-container">

				<?php if ( ! empty( $feature['more_url'] ) && $instance['icon_link'] ) echo '<a href="' . sow_esc_url( $feature['more_url'] ) . '" ' . ( $instance['new_window'] ? 'target="_blank"' : '' ) . '>'; ?>

				<?php
					// Build Container Styles
					$container_styles = '';
					if ( $instance['container_shape'] == 'none' ) {
						$container_styles .= 'width: ' . intval( $instance['container_size'] ) . 'px; ';
					} else {
						$container_styles .= 'font-size: ' . intval( $instance['container_size'] ) . 'px; ';
						if ( ! empty( $feature['container_color'] ) ) {
							$container_styles .= 'color: ' . esc_attr( $feature['container_color'] ) . ';';
						}
					}
				?>

				<div class="sow-icon-container sow-container-<?php echo esc_attr( $instance['container_shape'] ) ?>" <?php if ( $container_styles ) echo 'style="' . $container_styles . '"'; ?>>
					<?php
						if ( ! empty( $feature['icon_image'] ) && 'none' == esc_attr( $instance['container_shape'] ) ) { ?>
							<div class="sow-image-no-container">
								<?php echo wp_get_attachment_image( $feature['icon_image'], 'full' ); ?>
							</div>
							<?php
						} elseif ( ! empty( $feature['icon_image'] ) ) {
							$attachment = wp_get_attachment_image_src( $feature['icon_image'] );
							if ( ! empty( $attachment ) ) {
								$icon_styles[] = 'background-image: url(' . sow_esc_url( $attachment[0] ) . ')';
								if ( ! empty( $instance['icon_size'] ) ) $icon_styles[] = 'font-size: ' . intval( $instance['icon_size'] ) . 'px'; ?>

								<div class="sow-icon-image" style="<?php echo implode('; ', $icon_styles) ?>"></div>
								<?php
							}
						} else {
							$icon_styles = array();
							if ( ! empty( $instance['icon_size'] ) ) $icon_styles[] = 'font-size: ' . intval( $instance['icon_size'] ) . 'px';
							if ( ! empty( $feature['icon_color'] ) ) $icon_styles[] = 'color: ' . $feature['icon_color'];

							echo siteorigin_widget_get_icon( $feature['icon'], $icon_styles );
						}
					?>
				</div>

				<?php if ( ! empty( $feature['more_url'] ) && $instance['icon_link'] ) echo '</a>'; ?>

			</div>

			<div class="textwidget">
				<?php if ( ! empty( $feature['title'] ) ) : ?>
					<h5>
						<?php if ( ! empty( $feature['more_url'] ) && $instance['title_link'] ) echo '<a href="' . sow_esc_url( $feature['more_url'] ) . '" ' . ( $instance['new_window'] ? 'target="_blank"' : '' ) . '>'; ?>
						<?php echo wp_kses_post( $feature['title'] ) ?>
						<?php if ( ! empty( $feature['more_url'] ) && $instance['title_link'] ) echo '</a>'; ?>
					</h5>
				<?php endif; ?>

				<?php if ( ! empty( $feature['text'] ) ) : ?>
					<p><?php echo wp_kses_post( $feature['text'] ) ?></p>
				<?php endif; ?>

				<?php if ( ! empty( $feature['more_text'] ) ) : ?>
					<p class="sow-more-text">
						<?php if ( ! empty( $feature['more_url'] ) ) echo '<a class="button" href="' . sow_esc_url( $feature['more_url'] ) . '" ' . ( $instance['new_window'] ? 'target="_blank"' : '' ) . '>'; ?>
						<?php echo wp_kses_post( $feature['more_text'] ) ?>
						<?php if ( ! empty( $feature['more_url'] ) ) echo '</a>'; ?>
					</p>
				<?php endif; ?>
			</div>
		</div>

	<?php endforeach; ?>

</div>
