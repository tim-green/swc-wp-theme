<?php
/**
 * Headline Widget
 *
 * @package Publisher
 */

if ( '' != $headline || '' != $sub_headline ) {
	$headline_style = $instance['publisher_headline_style'];

	// Build headline attributes
	$headline_attributes = array(
		'class' => esc_attr( 'publisher-headline' )
	);
	if ( ! empty( $headline_style ) ) $headline_attributes['class'] .= ' ' . esc_attr( $headline_style );
	if ( ! empty( $instance['headline']['align'] ) ) $headline_attributes['class'] .= ' ' . esc_attr( $instance['headline']['align'] );
	if ( ! empty( $sub_headline ) ) $headline_attributes['class'] .= ' has-description';

	// Output headline
	if ( 'section_masthead' != $headline_style ) {

		$headline_attributes['class'] .= ' section-header';
		$headline_attributes['class'] .= ' clearfix';
		?>

		<div <?php foreach( $headline_attributes as $name => $val) echo esc_attr( $name ) . '="' . esc_attr( $val ) . '" ' ?>>
			<?php if ( ! empty( $headline ) ) : ?>
				<h3 class="section-title"><?php echo esc_attr( $headline ) ?></h3>
			<?php endif; ?>

			<?php if ( ! empty( $sub_headline ) ) : ?>
				<div class="section-description"><?php echo esc_attr( $sub_headline ) ?></div>
			<?php endif; ?>
		</div><!-- .section-header -->
		<?php

	} else {

		if ( $has_divider ) $headline_attributes['class'] .= ' has-divider';
		if ( ! empty( $instance['divider']['weight'] ) ) $headline_attributes['class'] .= ' ' . esc_attr( $instance['divider']['weight'] );
		?>

		<div <?php foreach( $headline_attributes as $name => $val) echo esc_attr( $name ) . '="' . esc_attr( $val ) . '" ' ?>>
			<div class="wrap">

				<?php if ( ! empty( $headline ) ) : ?>
					<h1 class="sow-title"><?php echo esc_attr( $headline ) ?></h1>
				<?php endif; ?>

				<?php if ( $has_divider ) : ?>
					<div class="decoration">
						<div class="decoration-inside"></div>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $sub_headline ) ) : ?>
					<h3 class="sow-description"><?php echo esc_attr( $sub_headline ) ?></h3>
				<?php endif; ?>

			</div>
		</div>
		<?php
	}
}
