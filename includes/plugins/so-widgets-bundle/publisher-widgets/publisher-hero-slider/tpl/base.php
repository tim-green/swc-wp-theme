<?php
/**
 * Hero Slider
 *
 * @package Publisher
 */

$controls = $instance['controls'];
$frames = $instance['frames'];

// Slider Settings
$animate = $instance['slider_options']['animate'] ? 'fade' : 'slide';
$autoplay = $instance['slider_options']['autoplay'] ? 'true' : 'false';
$autoheight = $instance['slider_options']['autoheight'] ? 'true' : 'false';
$timeout = $instance['slider_options']['timeout'] ? intval( $instance['slider_options']['timeout'] ) : 5000;
$speed = $instance['slider_options']['speed'] ? intval( $instance['slider_options']['speed'] ) : 800;
$carousel_navigation = ( count( $frames ) > 1 ) ? 'true' : 'false';

if ( $frames ) { ?>

	<div class="publisher-hero-widget <?php if ( wp_is_mobile() ) echo 'is-mobile' ?>">

		<?php
			// Attributes
			if ( ! empty( $carousel_navigation ) ) $slider_attributes['data-navigation'] = $carousel_navigation;
			if ( ! empty( $animate ) ) $slider_attributes['data-animate'] = $animate;
			if ( ! empty( $speed ) ) $slider_attributes['data-speed'] = $speed;
			if ( ! empty( $autoplay ) ) $slider_attributes['data-autoplay'] = $autoplay;
			if ( ! empty( $timeout ) ) $slider_attributes['data-timeout'] = $timeout;
			if ( ! empty( $autoheight ) ) $slider_attributes['data-autoheight'] = $autoheight;
		?>

		<?php echo publisher_get_loader_icon(); ?>

		<div class="owl-carousel" <?php foreach( $slider_attributes as $key => $val ) echo esc_attr( $key ) . '="' . esc_attr( $val ) . '" ' ?>>

			<?php
				foreach( $frames as $i => $frame ) {

					// Featured Header Atts
					$featured_header_atts = array();
					if ( ! empty( $frame['background']['color'] ) ) $featured_header_atts['background-color'] = esc_attr( $frame['background']['color'] );

					// Featured Header Style
					if ( ! empty( $featured_header_atts ) ) {
						$featured_header_style = 'style="';
						foreach ( $featured_header_atts as $key => $value ) {
							$featured_header_style .= esc_attr( $key ) . ': ' . esc_attr( $value ) . ';';
						}
						$featured_header_style .= '"';
					}

					// Featured Header Classes
					$featured_header_class = 'site-featured-header';
					$bg_image = wp_get_attachment_image_src( $frame['background']['image'], 'full' );
					if ( $bg_image[0] ) $featured_header_class .= ' has-featured-header-image';
					if ( $frame['background']['videos'] ) $featured_header_class .= ' has-featured-header-video';

					// Featured Header Data
					$featured_header_data_atts = array(
						'data-slide' => intval( $i ),
						'data-animation' => $frame['frame_options']['animation'],
					);

					if ( ! empty( $featured_header_data_atts ) ) {
						$featured_header_data = '';
						foreach ( $featured_header_data_atts as $key => $value ) {
							$featured_header_data .= esc_attr( $key ) . '="' . esc_attr( $value ) . '" ';
						}
					};
					?>

					<div class="<?php echo esc_attr( $featured_header_class ) ?>" <?php echo $featured_header_style; echo $featured_header_data; ?>>
						<div class="wrap equalize">
							<div class="container">
								<?php
									$this->render_frame_background_image( $i, $frame );
									if ( function_exists( 'wp_is_mobile' ) && ! wp_is_mobile() ) {
										$this->render_frame_background_video( $i, $frame );
									}
									$this->render_frame_content( $i, $frame );
								?>

							</div><!-- .container -->
						</div><!-- .wrap -->
					</div><!-- .site-featured-header -->
					<?php
				}
			?>

		</div>

	</div><!-- .publisher-hero-widget -->
	<?php

}
