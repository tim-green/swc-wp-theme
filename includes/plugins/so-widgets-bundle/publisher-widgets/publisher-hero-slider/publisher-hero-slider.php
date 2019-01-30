<?php
/*
Widget Name: Publisher Hero Slider
Description: A big hero slider with a few settings to make it your own.
Author: Davadrian Maramis
Author URI: http://themeforest.net/user/hypha/?ref=hypha
*/

/*-----------------------------------------------------------------------------------*/
/*	Widgets Class
/*-----------------------------------------------------------------------------------*/

/**
 * Hero Slider Class
 *
 * @package Publisher
 */
class Publisher_Hero_Slider_Widget extends SiteOrigin_Widget {

	protected $buttons = array();

	function __construct() {

		// Call the parent constructor with the required arguments.
		parent::__construct(
			'publisher-hero-slider', // The unique id for your widget.
			__( 'Publisher Hero Slider', 'publisher' ), // The name of the widget for display purposes.

			// The $widget_options array, which is passed through to WP_Widget.
			array(
				'description' => __( 'A big hero slider with a few settings to make it your own.', 'publisher' ),
				'panels_groups' => array( 'publisher' ),
				'panels_icon' => 'dashicons dashicons-welcome-widgets-menus',
				'panels_title' => false,
			),

			// The $widget_options array, which is passed through to WP_Widget.
			array(),

			// The $form_options array, which describes the form fields used to configure SiteOrigin widgets.
			array(
				'frames' => array(
					'type' => 'repeater',
					'label' => __('Hero frames', 'publisher'),
					'item_name' => __('Frame', 'publisher'),
					'item_label' => array(
						'selector' => "[id*='frames-title']",
						'update_event' => 'change',
						'value_method' => 'val'
					),

					'fields' => array(

						'content' => array(
							'type' => 'tinymce',
							'label' => __( 'Content', 'publisher' ),
						),

						'buttons' => array(
							'type' => 'repeater',
							'label' => __('Buttons', 'publisher'),
							'item_name' => __('Button', 'publisher'),
							'description' => __( 'Add [buttons] shortcode to the content to insert these buttons.', 'publisher' ),

							'item_label' => array(
								'selector' => "[id*='buttons-button-text']",
								'update_event' => 'change',
								'value_method' => 'val'
							),
							'fields' => array(
								'button' => array(
									'type' => 'widget',
									'class' => 'SiteOrigin_Widget_Button_Widget',
									'label' => __('Button', 'publisher'),
									'collapsible' => false,
								)
							)
						),

						'content_width' => array(
							'type' => 'slider',
							'label' => __( 'Content Width', 'publisher' ),
							'description' => __( 'Set the maximum percentage width of the content.', 'publisher' ),
							'max' => 100,
							'min' => 0,
							'default' => 100,
						),

						'content_alignment' => array(
							'type' => 'select',
							'label' => __( 'Content Alignment', 'publisher'),
							'default' => 'publisher-flush-left',
							'options' => array(
								'publisher-flush-left' => __( 'Left', 'publisher' ),
								'publisher-align-center' => __( 'Center', 'publisher' ),
								'publisher-float-right' => __( 'Right', 'publisher' ),
							)
						),

						'background' => array(
							'type' => 'section',
							'label' => __( 'Background', 'publisher' ),
							'description' => __( 'Set the background image and/or video for this frame.', 'publisher' ),
							'hide' => true,
							'fields' => array(
								'image' => array(
									'type' => 'media',
									'label' => __( 'Background image', 'publisher' ),
									'library' => 'image',
								),

								'position_y' => array(
									'label' => __( 'Background image vertical offset (%)', 'publisher' ),
									'type' => 'slider',
									'min' => -100,
									'max' => 100,
									'default' => 0,
								),

								'opacity' => array(
									'label' => __( 'Background image opacity', 'publisher' ),
									'type' => 'slider',
									'min' => 0,
									'max' => 100,
									'default' => 100,
								),

								'color' => array(
									'type' => 'color',
									'label' => __( 'Background color', 'publisher' ),
								),

								'videos' => array(
									'type' => 'repeater',
									'item_name' => __('Video', 'publisher'),
									'label' => __('Background videos', 'publisher'),
									'description' => __( 'Mobile devices will show the background image instead.', 'publisher' ),
									'item_label' => array(
										'selector' => "[id*='frames-background_videos-url']",
										'update_event' => 'change',
										'value_method' => 'val'
									),
									'fields' => $this->video_form_fields(),
								),
							)
						),

						'frame_options' => array(
							'type' => 'section',
							'label' => __( 'Additional Options', 'publisher' ),
							'description' => __( 'Set the options for this frame.', 'publisher' ),
							'hide' => true,
							'fields' => array(
								'animation' => array(
									'type' => 'select',
									'label' => __( 'Frame Animation', 'publisher' ),
									'description' => __( 'Set the animate in of this frame', 'publisher' ),
									'default' => 'fadeInDown',
									'options' => array(
										'none' => __( 'None', 'publisher' ),
										'bounceIn' => __( 'Bounce In', 'publisher' ),
										'bounceInDown' => __( 'Bounce In Down', 'publisher' ),
										'bounceInLeft' => __( 'Bounce In Left', 'publisher' ),
										'bounceInRight' => __( 'Bounce In Right', 'publisher' ),
										'bounceInUp' => __( 'Bounce In Up', 'publisher' ),
										'fadeIn' => __( 'Fade In', 'publisher' ),
										'fadeInDown' => __( 'Fade In Down', 'publisher' ),
										'fadeInDownBig' => __( 'Fade In Down Big', 'publisher' ),
										'fadeInLeft' => __( 'Fade In Left', 'publisher' ),
										'fadeInLeftBig' => __( 'Fade In Left Big', 'publisher' ),
										'fadeInRight' => __( 'Fade In Right', 'publisher' ),
										'fadeInRightBig' => __( 'Fade In Right Big', 'publisher' ),
										'fadeInUp' => __( 'Fade In Up', 'publisher' ),
										'fadeInUpBig' => __( 'Fade In Up Big', 'publisher' ),
										'flipInX' => __( 'Flip In X', 'publisher' ),
										'flipInY' => __( 'Flip In Y', 'publisher' ),
										'zoomIn' => __( 'Zoom In', 'publisher' ),
									)
								),
							),
						)
					),
				),

				// Post Type
				'slider_options' => array(
				    'type' => 'section',
				    'label' => __( 'Slider Options' , 'publisher' ),
				    'hide' => true,
				    'fields' => array(
					    'animate' => array(
					        'type' => 'checkbox',
					        'label' => __( 'Fade Slides', 'publisher' ),
					        'description' => __( 'Slider will fade between the slides. Default is slide.', 'publisher' ),
					        'default' => false
					    ),

					    'autoplay' => array(
					        'type' => 'checkbox',
					        'label' => __( 'Autoplay', 'publisher' ),
					        'description' => __( 'Automatically play the slider. Will pause on mouse over.', 'publisher' ),
					        'default' => false
					    ),

					    'autoheight' => array(
					        'type' => 'checkbox',
					        'label' => __( 'Autoheight', 'publisher' ),
					        'description' => __( 'Set the slider to auto adjust to the height of the current slide.', 'publisher' ),
					        'default' => false
					    ),

						'speed' => array(
					    	'type' => 'text',
					    	'label' => __( 'Slide Speed', 'publisher' ),
					    	'description' => __( 'Duration in milliseconds spent animating between slides. Default is 800.', 'publisher' )
					    ),

					    'timeout' => array(
					    	'type' => 'text',
					    	'label' => __( 'Slide Timeout', 'publisher' ),
					    	'description' => __( 'Duration in milliseconds spent on each slide if autoplay is enabled. Default is 5000.', 'publisher' )
					    ),

				    )
				),

				'design' => array(
					'type' => 'section',
					'label' => __('Design and Layout', 'publisher'),
					'fields' => array(

						'padding' => array(
							'type' => 'slider',
							'label' => __( 'Vertical Padding', 'publisher' ),
							'description' => __( 'The padding size for the top and bottom frame content.', 'publisher' ),
							'max' => 150,
							'min' => 0,
							'default' => 90,
						),

						'heading_size' => array(
							'type' => 'slider',
							'label' => __('Heading Size', 'publisher'),
							'max' => 72,
							'min' => 6,
							'default' => 62,
						),

						'text_size' => array(
							'type' => 'slider',
							'label' => __('Text Size', 'publisher'),
							'max' => 48,
							'min' => 6,
							'default' => 18,
						),

					)
				),
			)
		);
	}

	function video_form_fields() {
		return array(
			'file' => array(
				'type' => 'media',
				'library' => 'video',
				'label' => __('Video file', 'publisher'),
			),

			'url' => array(
				'type' => 'text',
				'sanitize' => 'url',
				'label' => __('Video URL', 'publisher'),
				'optional' => 'true',
				'description' => __('An external URL of the video. Overrides video file.', 'publisher')
			),

			'format' => array(
				'type' => 'select',
				'label' => __('Video format', 'publisher'),
				'options' => array(
					'video/mp4' => 'MP4',
					'video/webm' => 'WebM',
					'video/ogg' => 'Ogg',
				),
			),

			'height' => array(
				'type' => 'number',
				'label' => __( 'Maximum height', 'publisher' )
			),

		);
	}

	/**
	 * The less variables to control the design of the slider
	 *
	 * @param $instance
	 *
	 * @return array
	 */
	function get_less_variables( $instance ) {
		$less = array();

		// Hero specific design
		if ( ! empty( $instance['design'] ) ) {
			$less['slide_padding'] = intval( $instance['design']['padding'] );
			$less['heading_size'] = intval( $instance['design']['heading_size'] );
			$less['text_size'] = intval( $instance['design']['text_size'] );
		}

		return $less;
	}

	function initialize() {

		// Include Button Widget
		if ( ! class_exists( 'SiteOrigin_Widget_Button_Widget' ) ) {
			include plugin_dir_path( SOW_BUNDLE_BASE_FILE ) . 'widgets/so-button-widget/so-button-widget.php';
			siteorigin_widget_register( 'button', plugin_dir_path( SOW_BUNDLE_BASE_FILE ) . 'widgets/so-button-widget/so-button-widget.php' );
		}

		// Frontend Scripts/Styles
		$frontend_scripts = array();
		$frontend_styles = array();

		// Hero Slider Scripts
		$frontend_scripts[] = array(
			'publisher-hero-slider-js', get_template_directory_uri() . '/includes/plugins/so-widgets-bundle/publisher-widgets/publisher-hero-slider/js/publisher-widget.js', array( 'jquery' ), '1.0'
		);

		// Owl Carousel Script
		$frontend_scripts[] = array(
			'publisher-owl-js', get_template_directory_uri() . '/includes/js/owl.carousel.js', array( 'jquery' ), '2.4.4', true
		);

		// Owl Carousel Styles
		$frontend_styles[] = array(
			'publisher-owl-style', get_template_directory_uri() . '/includes/css/owl.carousel.css', array(), '2.4.4', 'screen'
		);

		// Match Height
		$frontend_scripts[] = array(
			'publisher-match-height-js', get_template_directory_uri() . '/includes/js/jquery.matchHeight.js', array(), '0.6.0'
		);

		/**
		 * Register scripts and styles
		 */
		$this->register_frontend_scripts( $frontend_scripts );
		$this->register_frontend_styles( $frontend_styles );

	}

	function get_template_name( $instance ) {
		return 'base';
	}

	function get_style_name( $instance ) {
		return 'styles';
	}




	/*-----------------------------------------------------------------------------------*/
	/*	Build Frames
	/*-----------------------------------------------------------------------------------*/

	/**
	 * Render the frame background image
	 */
	function render_frame_background_image( $i, $frame ) {

		if ( ! empty( $frame['background']['image'] ) ) {

			$bg_image = wp_get_attachment_image_src( $frame['background']['image'], 'full' );
			$bg_image_opacity = $frame['background']['opacity'];
			$bg_image_position = $frame['background']['position_y'];

			// Background Image Atts
			$bg_image_atts = array();
			if ( $bg_image[0] ) $bg_image_atts['background-image'] = 'url( '. esc_url( $bg_image[0] ) . ' )';
			if ( ! empty( $bg_image_opacity ) && 100 != $bg_image_opacity ) $bg_image_atts['opacity'] = esc_attr( $bg_image_opacity / 100 );
			if ( ! empty( $bg_image_position ) && 0 != $bg_image_position ) $bg_image_atts['background-position-y'] = esc_attr( $bg_image_position . '%' );

			// Background Image Style
			$bg_image_style = '';
			if ( ! empty( $bg_image_atts ) ) {
				$bg_image_style .= 'style="';
				foreach ( $bg_image_atts as $key => $value ) {
					$bg_image_style .= esc_attr( $key ) . ': ' . esc_attr( $value ) . ';';
				}
				$bg_image_style .= '"';
			}

			// Background Image Classes
			$bg_class = 'site-featured-header-image';
			?>

			<div class="<?php echo esc_attr( $bg_class ) ?>" <?php echo $bg_image_style ?>>
				<div class="overlay"></div>
			</div><!-- .site-featured-header-image -->

			<?php

		}

		return;

	}


	/**
	 * Render the frame content
	 */
	function render_frame_content( $i, $frame ){

		// Frame Atts
		$frame_atts = array();
		if ( ! empty( $frame['content_width'] ) ) $frame_atts['width'] = $frame['content_width'] . '%';

		// Frame Style
		$frame_style = '';
		if ( ! empty( $frame_atts ) ) {
			$frame_style .= 'style="';
			foreach ( $frame_atts as $key => $value ) {
				$frame_style .= esc_attr( $key ) . ': ' . esc_attr( $value ) . ';';
			}
			$frame_style .= '"';
		}

		$frame_class = 'hero-content';
		if ( ! empty( $frame['content_alignment'] ) ) $frame_class .= ' ' . $frame['content_alignment'];
		?>

		<div class="<?php echo esc_attr( $frame_class ) ?>" <?php echo $frame_style ?>>
			<?php
				if ( ! empty( $frame['content'] ) ) {
					echo $this->process_content( $frame['content'], $frame );
				}
			?>
		</div>
		<?php

	}


	/**
	 * Process the content. Most importantly add the buttons by replacing [buttons] in the content
	 */
	function process_content( $content, $frame ) {
		ob_start();
		foreach( $frame['buttons'] as $button ) {
			$this->sub_widget( 'SiteOrigin_Widget_Button_Widget', array(), $button['button'] );
		}
		$button_code = ob_get_clean();

		// Add in the button code
		$content = preg_replace( '/<p *([^>]*)> *\[ *buttons *\] *<\/p>/i', '<div class="publisher-hero-buttons" $1>' . $button_code . '</div>', wp_kses_post( $content ) );

		return $content;
	}


	/**
	 * Render the frame background video
	 */
	function render_frame_background_video( $i, $frame ) {

		$videos = $frame['background']['videos'];

		if ( ! empty( $videos ) ) {

			$video_element = '<video class="site-featured-header-video" data-id="' . esc_attr( $i ) . '" autoplay loop muted>';

			foreach( $videos as $video) {

				if ( empty( $video['file'] ) && empty ( $video['url'] ) ) continue;

				if ( empty( $video['url'] ) ) {
					$video_file = wp_get_attachment_url( $video['file'] );
					$video_element .= '<source src="' . sow_esc_url( $video_file ) . '" type="' . esc_attr( $video['format'] ) . '">';
				} else {
					$args = '';
					if ( ! empty( $video['height'] ) ) {
						$args['height'] = $video['height'];
					}

					echo wp_oembed_get( $video['url'], $args );
				}
			}

			$video_element .= '</video>';

			echo $video_element;
		}

		return;
	}

}
siteorigin_widget_register( 'publisher-hero-slider', __FILE__, 'Publisher_Hero_Slider_Widget', 15 );
