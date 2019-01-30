<?php
/*
Widget Name: Publisher Products Loop
Description: Display your products. Requires Woocommerce plugin.
Author: Davadrian Maramis
Author URI: http://themeforest.net/user/hypha/?ref=hypha
*/

/*-----------------------------------------------------------------------------------*/
/*	Widgets Class
/*-----------------------------------------------------------------------------------*/

/**
 * Products Loop Class
 *
 * @package Publisher
 */
class Publisher_Products_Loop_Widget extends SiteOrigin_Widget {

	function __construct() {

		// Call the parent constructor with the required arguments.
		parent::__construct(
			'publisher-products-loop', // The unique id for your widget.
			__( 'Publisher Products Loop', 'publisher' ), // The name of the widget for display purposes.

			// The $widget_options array, which is passed through to WP_Widget.
			array(
				'description' => __( 'Display your products. Requires Woocommerce plugin.', 'publisher' ),
				'panels_groups' => array( 'publisher' ),
				'panels_icon' => 'dashicons dashicons-welcome-widgets-menus'
			),

			// The $widget_options array, which is passed through to WP_Widget.
			array(),

			// The $form_options array, which describes the form fields used to configure SiteOrigin widgets.
			array(
				'section_header' => array(
					'type' => 'widget',
					'label' => __( 'Section Header', 'publisher' ),
					'description' => __( 'Add a section header to this widget.', 'publisher' ),
					'class' => 'SiteOrigin_Widget_Headline_Widget',
					'hide' => true
				),

				'section_type' => array(
			        'type' => 'select',
			        'label' => __( 'Section Type', 'publisher' ),
			        'description' => __( 'Set the type of items to show.', 'publisher' ),
			        'default' => 'recent_products',
			        'state_emitter' => array(
			        	'callback' => 'select',
			        	'args' => array( 'section_type' )
			        ),
			        'options' => array(
			            'recent_products' => __( 'Recent Products', 'publisher' ),
			            'featured_products' => __( 'Featured Products', 'publisher' ),
			            'sale_products' => __( 'Sale Products', 'publisher' ),
			            'best_selling_products' => __( 'Best Selling Products', 'publisher' ),
			            'top_rated_products' => __( 'Top Rated Products', 'publisher' ),
			            'product' => __( 'Single Product', 'publisher' ),
			            'product_page' => __( 'Product Page', 'publisher' ),
			            'product_categories' => __( 'Product Categories', 'publisher' ),
			        ),
			        'priority' => 10
			    ),

			    'terms' => array(
				    'type' => 'select',
					'label'	=> __( 'Select Categories', 'publisher' ),
					'description' => __( 'Select the product categories to show.', 'publisher' ),
					'multiple' => true,
					'state_handler' => array(
						'section_type[product_category]' => array('show'),
			        	'_else[section_type]' => array('hide'),
					),
					'options' => array()
				),

				'product_id' => array(
				    'type' => 'text',
					'label'	=> __( 'Insert ID', 'publisher' ),
					'description' => __( 'Insert the applicable product/category ID (if needed).', 'publisher' ),
					'state_handler' => array(
						'section_type[product]' => array('show'),
						'section_type[product_page]' => array('show'),
						'section_type[product_categories]' => array('show'),
			        	'_else[section_type]' => array('hide'),
					),
				),

				'parent' => array(
			        'type' => 'checkbox',
			        'label' => __( 'Display only top level categories.', 'publisher' ),
			        'default' => false,
			        'state_handler' => array(
						'section_type[product_categories]' => array('show'),
			        	'_else[section_type]' => array('hide'),
					),
			    ),

			    'section_layout' => array(
			        'type' => 'select',
			        'label' => __( 'Section Layout', 'publisher' ),
			        'description' => __( 'Show the section as a slider or grid.', 'publisher' ),
			        'default' => 'grid',
			        'options' => array(
			            'carousel' => __( 'Slider', 'publisher' ),
			            'grid' => __( 'Grid', 'publisher' ),
			        ),
			        'priority' => 15,
			        'state_handler' => array(
						'section_type[product]' => array('hide'),
						'section_type[product_page]' => array('hide'),
			        	'_else[section_type]' => array('show'),
					),
			    ),

			    'number_of_columns' => array(
			        'type' => 'select',
			        'label' => __( 'Number of Columns', 'publisher' ),
			        'description' => __( 'The number of columns to show.', 'publisher' ),
			        'default' => 3,
			        'options' => array(
				        1 => __( '1', 'publisher' ),
				        2 => __( '2', 'publisher' ),
			            3 => __( '3', 'publisher' ),
			            4 => __( '4', 'publisher' ),
			            5 => __( '5', 'publisher' ),
			        ),
			        'priority' => 20,
			        'state_handler' => array(
						'section_type[product_page]' => array('hide'),
			        	'_else[section_type]' => array('show'),
					),
			    ),

				'order' => array(
				    'type' => 'select',
				    'label' => __( 'Order', 'publisher' ),
				    'description' => __( 'Select ascending or descending order.', 'publisher' ),
				    'default' => 'DESC',
				    'options' => array(
				        'ASC' => __( 'Ascending', 'publisher' ),
				        'DESC' => __( 'Descending', 'publisher' ),
				    ),
				    'priority' => 25,
				    'state_handler' => array(
						'section_type[product]' => array('hide'),
						'section_type[product_page]' => array('hide'),
			        	'_else[section_type]' => array('show'),
					),
				),

				'orderby' => array(
					'type' => 'select',
					'label' => __( 'Order By', 'publisher' ),
					'description' => __( 'Sort retrieved products by parameter, if applicable.', 'publisher' ),
					'default' => 'date',
					'options' => array(
						'date' => __( 'Date', 'publisher' ),
						'title' => __( 'Title', 'publisher' ),
						'name' => __( 'Name', 'publisher' ),
						'comment_count' => __( 'Review Count', 'publisher' ),
						'rand' => __( 'Random', 'publisher' ),
					),
					'priority' => 30,
					'state_handler' => array(
						'section_type[product]' => array('hide'),
						'section_type[product_page]' => array('hide'),
			        	'_else[section_type]' => array('show'),
					),
				),

				'number_of_items' => array(
				    'type' => 'text',
				    'label' => __( 'Products Per Page', 'publisher' ),
				    'description' => __( 'Insert a number of items to show. The default is your Wordpress setting.', 'publisher' ),
				    'priority' => 35,
				    'state_handler' => array(
						'section_type[product]' => array('hide'),
						'section_type[product_page]' => array('hide'),
			        	'_else[section_type]' => array('show'),
					),
				),
			),

			// The $base_folder path string.
			plugin_dir_path( __FILE__ ) . '../'
		);

	}

	// Modify the form
	function modify_form( $form ) {

		// Populate Terms
		$tax_terms = get_terms( 'product_cat' );

		if ( ! empty( $tax_terms ) && ! is_wp_error( $tax_terms ) ) {
			$form['section_type']['options']['product_category'] = __( 'Product Category', 'publisher' );

			foreach ( $tax_terms as $tax_term ) {
				$form['terms']['options'][$tax_term->slug] = $tax_term->name;
			};
		}

		return $form;
	}

	function initialize() {

		// Frontend Scripts/Styles
		$frontend_scripts = array();
		$frontend_styles = array();

		// Products Loop Scripts
		$frontend_scripts[] = array(
			'publisher-products-loop-js', get_template_directory_uri() . '/includes/plugins/so-widgets-bundle/publisher-widgets/publisher-products-loop/js/publisher-widget.js', array( 'jquery' ), '1.0'
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
		return '';
	}

}

siteorigin_widget_register( 'publisher-products-loop', __FILE__, 'Publisher_Products_Loop_Widget', 15 );
