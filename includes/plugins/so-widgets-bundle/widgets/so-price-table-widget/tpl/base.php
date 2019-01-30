<?php if ( ! empty( $instance['title'] ) ) echo $args['before_title'] . esc_html( $instance['title'] ) . $args['after_title'] ?>

<div class="ow-pt-columns-publisher <?php echo is_rtl() ? 'rtl' : ''; ?>">

	<?php foreach( $instance['columns'] as $i => $column ) : ?>
		<div class="ow-pt-column <?php echo esc_attr( $this->get_column_classes( $column, $i, $instance['columns'] ) ) ?>" style="width: <?php echo round( 100 / count( $instance['columns'] ), 3 ) ?>%">
			<h3 class="ow-pt-title">
				<?php echo esc_html( $column['title'] ) ?>
				<?php if ( ! empty( $column['subtitle'] ) ) : ?>
					<span class="ow-pt-subtitle"><?php echo esc_html( $column['subtitle'] ) ?></span>
				<?php endif; ?>
			</h3>

			<div class="ow-pt-details">
				<div class="ow-pt-price"><?php echo esc_html( $column['price'] ) ?></div>
				<div class="ow-pt-per"><?php echo esc_html( $column['per'] ) ?></div>
			</div>

			<?php if ( ! empty( $column['image'] ) ) : ?>
				<div class="ow-pt-image">
					<?php $this->column_image( $column['image'] ) ?>
				</div>
			<?php endif; ?>

			<div class="ow-pt-features">
				<?php foreach( $column['features'] as $i => $feature ) : ?>
					<div class="ow-pt-feature ow-pt-feature-<?php echo esc_attr( $i ) % 2 == 0 ? 'even' : 'odd' ?>">

						<?php
							if ( ! empty( $feature['icon_new'] ) ) {
								$icon_styles = array();
								if ( ! empty( $feature['icon_color'] ) ) $icon_styles[] = 'color: ' . $feature['icon_color'];
								echo siteorigin_widget_get_icon( $feature['icon_new'], $icon_styles );
							}
						?>

						<p data-tooltip-text="<?php echo esc_attr( $feature['hover'] ) ?>">
							<?php echo wp_kses_post( $feature['text'] ) ?>
						</p>
					</div>
				<?php endforeach; ?>
			</div>

			<?php if ( $column['button'] != '' ) { ?>
				<div class="ow-pt-button">
					<a href='<?php echo sow_esc_url( $column['url'] ) ?>' class="ow-pt-link button" <?php if ( ! empty( $instance['button_new_window'] ) ) echo 'target="_blank"' ?>><?php echo esc_html( $column['button'] ) ?></a>
				</div>
			<?php } ?>
		</div>
	<?php endforeach; ?>

</div>
