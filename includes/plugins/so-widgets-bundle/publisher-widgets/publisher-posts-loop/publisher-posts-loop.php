<?php
/*
Widget Name: Publisher Posts Loop
Description: Display your posts in a variety of different ways.
Author: Davadrian Maramis
Author URI: http://themeforest.net/user/hypha/?ref=hypha
*/

/*-----------------------------------------------------------------------------------*/
/*	Widgets Class
/*-----------------------------------------------------------------------------------*/

/**
 * Posts Loop Class
 *
 * @package Publisher
 */
class Publisher_Posts_Loop_Widget extends SiteOrigin_Widget {

	function __construct() {

		// Call the parent constructor with the required arguments.
		parent::__construct(
			'publisher-posts-loop-widget', // The unique id for your widget.
			__( 'Publisher Posts Loop', 'publisher' ), // The name of the widget for display purposes.

			// The $widget_options array, which is passed through to WP_Widget.
			array(
				'description' => __( 'Display your posts in a variety of different ways.', 'publisher' ),
				'panels_groups' => array( 'publisher' ),
				'panels_icon' => 'dashicons dashicons-welcome-widgets-menus'
			),

			// The $widget_options array, which is passed through to WP_Widget.
			array(),

			// The $form_options array, which describes the form fields used to configure SiteOrigin widgets.
			array(

				// Section Header
				'post_loop_section_header' => array(
					'type' => 'widget',
					'label' => __( 'Section Header', 'publisher' ),
					'description' => __( 'Add a section header to this widget.', 'publisher' ),
					'class' => 'SiteOrigin_Widget_Headline_Widget',
					'hide' => true
				),

				// Build Posts Query
				'build_posts_query' => array(
			        'type' => 'repeater',
			        'label' => __( 'Build Posts Query' , 'publisher' ),
			        'description' => __( 'Build a posts query. The first posts query is your main query, all other posts queries will subtract the results of the first.', 'publisher' ),
			        'item_name'  => __( 'Posts Query', 'publisher' ),
			        'item_label' => array(
			            'selector'     => "[id*='post_query_title']",
			            'update_event' => 'change',
			            'value_method' => 'val'
			        ),
			        'fields' => array(

						// Post Query Name
						'post_query_title' => array(
			                'type' => 'text',
			                'label' => __( 'Query Name', 'publisher' ),
			                'description' => __( 'Optional name of this post query for reference. Not Displayed.', 'publisher' )
			            ),

						// Post Type
						'post_type' => array(
						    'type' 			=> 'select',
							'label' 		=> __( 'Post Type', 'publisher' ),
							'description' 	=> __( 'Select the post type.', 'publisher' ),
							'default' 		=> 'post',
							'multiple'		=> true,
							'options'		=> array()
						),

						// Taxonomies
						'taxonomies' => array(
						    'type' => 'section',
						    'label' => __( 'Taxonomy' , 'publisher' ),
						    'description' => __( 'Select the taxonomy.', 'publisher' ),
						    'hide' => false,
						    'default' => 'OR',
						    'fields' => array(
								'relation' => array(
							        'type' => 'select',
							        'description' => __( 'The logical relationship between each inner taxonomy array when there is more than one.', 'publisher' ),
							        'default' => 'default',
							        'options' => array(
								        'AND' => __( 'AND: Show if posts have ALL selected taxonomy terms', 'publisher' ),
							            'OR' => __( 'OR: Show if posts have at least ONE of the selected taxonomy terms', 'publisher' ),
							        )
							    ),
						    )
						),

						// Users
						'users' => array(
						    'type' 			=> 'select',
							'label' 		=> __( 'Users', 'publisher' ),
							'description' 	=> __( 'Select the users.', 'publisher' ),
							'default' 		=> '',
							'multiple'		=> true,
							'options'		=> array(
								'' => '---'
							)
						),

						// Order
						'order' => array(
					        'type' => 'select',
					        'label' => __( 'Order', 'publisher' ),
					        'description' => __( 'Select ascending or descending order.', 'publisher' ),
					        'default' => 'DESC',
					        'options' => array(
					            'ASC' => __( 'Ascending', 'publisher' ),
					            'DESC' => __( 'Descending', 'publisher' ),
					        )
					    ),

						// Orderby
						'orderby' => array(
					        'type' => 'select',
					        'label' => __( 'Order By', 'publisher' ),
					        'description' => __( 'Sort retrieved posts by parameter.', 'publisher' ),
					        'default' => 'date',
					        'state_emitter' => array(
					        	'callback' => 'select',
					        	'args' => array( 'orderby' )
					        ),
					        'options' => array(
					            'date' => __( 'Date', 'publisher' ),
					            'title' => __( 'Title', 'publisher' ),
					            'name' => __( 'Name', 'publisher' ),
					            'comment_count' => __( 'Comment Count', 'publisher' ),
					            'rand' => __( 'Random', 'publisher' ),
					            'publisher_likes_meta' => __( 'Most Likes', 'publisher' ),
					            'publisher_shares_meta' => __( 'Most Shares', 'publisher' ),
					        )
					    ),

					    // Time Range
						'time_range' => array(
					        'type' => 'select',
					        'label' => __( 'Time Range', 'publisher' ),
					        'description' => __( 'Select time range.', 'publisher' ),
					        'default' => 'all',
					        'state_handler' => array(
						        'orderby[date]' => array('hide'),
						        'orderby[title]' => array('hide'),
						        'orderby[name]' => array('hide'),
						        'orderby[rand]' => array('hide'),
						        'orderby[publisher_likes_meta]' => array('hide'),
						        'orderby[publisher_shares_meta]' => array('hide'),
						        'orderby[comment_count]' => array('hide'),
						    ),
						    'options' => array(),
						),

						// Time Published
						'time_published' => array(
					        'type' => 'checkbox',
					        'label' => __( 'Time Published', 'publisher' ),
					        'description' => __( 'Display only posts published within the selected Time Range.', 'publisher' ),
					        'default' => false,
					        'state_handler' => array(
						        'orderby[date]' => array('hide'),
						        'orderby[title]' => array('hide'),
						        'orderby[name]' => array('hide'),
						        'orderby[rand]' => array('hide'),
						        'orderby[publisher_likes_meta]' => array('hide'),
						        'orderby[publisher_shares_meta]' => array('hide'),
						        'orderby[comment_count]' => array('hide'),
						    ),
						),

					    // Sticky Posts
						'sticky' => array(
					        'type' => 'select',
					        'label' => __( 'Sticky Posts', 'publisher' ),
					        'description' => __( 'Set sticky posts behavior.', 'publisher' ),
					        'default' => 'ignore',
					        'options' => array(
						        'default' => __( 'Default', 'publisher' ),
					            'ignore' => __( 'Ignore', 'publisher' ),
					            'include' => __( 'Include', 'publisher' ),
					            'exclude' => __( 'Exclude', 'publisher' ),
					        )
					    ),

					    // Posts Per Page
					    'posts_per_page' => array(
					        'type' => 'text',
					        'label' => __( 'Posts Per Page', 'publisher' ),
					        'description' => __( 'Default is your Wordpress setting.', 'publisher' ),
					    ),

					    // Exclude IDs
					    'exclude_id' => array(
					        'type' => 'text',
					        'label' => __( 'Exclude IDs', 'publisher' ),
					        'description' => __( 'Post IDs to exclude from this query. Separate with a comma.', 'publisher' ),
					    ),

			        )
			    ),

				'posts_page_layout' => array(
			        'type' => 'select',
			        'label' => __( 'Posts Page Layout', 'publisher' ),
			        'description' => __( 'Set the posts page layout.', 'publisher' ),
			        'default' => 'standard',
			        'state_emitter' => array(
			        	'callback' => 'select',
			        	'args' => array( 'posts_page_layout' )
			        ),
			        'options' => array(
				        'standard' => __( 'Standard', 'publisher' ),
				        'list' => __( 'List', 'publisher' ),
				        'grid' => __( 'Grid', 'publisher' ),
				        'cover' => __( 'Cover', 'publisher' ),
				        'carousel' => __( 'Cover Carousel', 'publisher' ),
				        'listing-h' => __( 'Listing Horizontal', 'publisher' ),
				        'listing-v' => __( 'Listing Vertical', 'publisher' ),
				        'mini-grid' => __( 'Mini Grid', 'publisher' ),
				        'mini-carousel' => __( 'Mini Carousel', 'publisher' ),
				        'mini-listing' => __( 'Mini Listing', 'publisher' ),
				        'slider' => __( 'Post Slider', 'publisher' ),
			        )
			    ),

			    'post_columns' => array(
			        'type' => 'select',
			        'label' => __( 'Post Columns', 'publisher' ),
			        'description' => __( 'Set the number of columns.', 'publisher' ),
			        'default' => 2,
			        'state_handler' => array(
			        	'posts_page_layout[standard]' => array('hide'),
			        	'posts_page_layout[list]' => array('hide'),
			        	'posts_page_layout[grid]' => array('show'),
			        	'posts_page_layout[cover]' => array('show'),
			        	'posts_page_layout[carousel]' => array('show'),
			        	'posts_page_layout[listing-h]' => array('hide'),
			        	'posts_page_layout[listing-v]' => array('hide'),
			        	'posts_page_layout[mini-grid]' => array('show'),
			        	'posts_page_layout[mini-carousel]' => array('show'),
			        	'posts_page_layout[mini-listing]' => array('hide'),
			        	'posts_page_layout[slider]' => array('show'),
			        ),
			        'options' => array(
				        1 => __( '1', 'publisher' ),
				        2 => __( '2', 'publisher' ),
				        3 => __( '3', 'publisher' ),
				        4 => __( '4', 'publisher' ),
				        5 => __( '5', 'publisher' ),
			        )
			    ),

				'post_format_icon' => array(
			        'type' => 'select',
			        'label' => __( 'Post Format Icon', 'publisher' ),
			        'description' => __( 'Set the default post format icon display.', 'publisher' ),
			        'default' => 'show',
			        'state_handler' => array(
			        	'posts_page_layout[standard]' => array('show'),
			        	'posts_page_layout[list]' => array('show'),
			        	'posts_page_layout[grid]' => array('show'),
			        	'posts_page_layout[cover]' => array('hide'),
			        	'posts_page_layout[carousel]' => array('hide'),
			        	'posts_page_layout[listing-h]' => array('hide'),
			        	'posts_page_layout[listing-v]' => array('hide'),
			        	'posts_page_layout[mini-grid]' => array('hide'),
			        	'posts_page_layout[mini-carousel]' => array('hide'),
			        	'posts_page_layout[mini-listing]' => array('hide'),
			        	'posts_page_layout[slider]' => array('hide'),
			        ),
			        'options' => array(
				        'show' => __( 'Show', 'publisher' ),
			            'hide' => __( 'Hide', 'publisher' ),
			        )
			    ),

			    'post_content' => array(
			        'type' => 'select',
			        'label' => __( 'Show Content', 'publisher' ),
			        'description' => __( 'Display the post content.', 'publisher' ),
			        'default' => 'excerpt',
			        'state_handler' => array(
			        	'posts_page_layout[standard]' => array('show'),
			        	'posts_page_layout[list]' => array('show'),
			        	'posts_page_layout[grid]' => array('show'),
			        	'posts_page_layout[cover]' => array('hide'),
			        	'posts_page_layout[carousel]' => array('hide'),
			        	'posts_page_layout[listing-h]' => array('hide'),
			        	'posts_page_layout[listing-v]' => array('hide'),
			        	'posts_page_layout[mini-grid]' => array('hide'),
			        	'posts_page_layout[mini-carousel]' => array('hide'),
			        	'posts_page_layout[mini-listing]' => array('hide'),
			        	'posts_page_layout[slider]' => array('hide'),
			        ),
			        'options' => array(
				        'none' => __( 'None', 'publisher' ),
			            'excerpt' => __( 'Excerpt', 'publisher' ),
			            'full' => __( 'Full', 'publisher' )
			        )
			    ),

			    'image_size' => array(
					'type' => 'select',
					'label' => __( 'Image size', 'publisher' ),
					'description' => __( 'Select the image size for the post loop (if applicable).', 'publisher' ),
					'default' => 'default',
					'options' => array(
						'default' => __( 'Default', 'publisher' ),
						'full' => __( 'Full', 'publisher' ),
						'large' => __( 'Large', 'publisher' ),
						'medium' => __( 'Medium', 'publisher' ),
						'thumb' => __( 'Thumbnail', 'publisher' ),
					),
				),

			    'pagination' => array(
			        'type' => 'select',
			        'label' => __( 'Posts Pagination', 'publisher' ),
			        'description' => __( 'Select the posts pagination style. Only works on static pages.', 'publisher' ),
			        'default' => 'prev-next',
			        'state_emitter' => array(
			        	'callback' => 'select',
			        	'args' => array( 'pagination' )
			        ),
			        'options' => array(
			            'none' => __( 'None', 'publisher' ),
			            'posts-navigation' => __( 'Prev / Next', 'publisher' ),
			            'pagination' => __( 'Numeric', 'publisher' ),
			            'load-more' => __( 'Load More', 'publisher' ),
			            'header-prev-next' => __( 'Header Prev / Next', 'publisher' ),
			        )
			    ),

			    'ajax' => array(
			        'type' => 'checkbox',
			        'label' => __( 'Ajax Loading', 'publisher' ),
			        'description' => __( 'Load new posts without re-loading the page.', 'publisher' ),
			        'default' => false,
			        'state_handler' => array(
			        	'pagination[none]' => array('hide'),
			        	'_else[pagination]' => array('show'),
			        ),
			    ),

			    'history' => array(
			        'type' => 'checkbox',
			        'label' => __( 'Ajax Track History', 'publisher' ),
			        'description' => __( 'Change browser URL upon loading new posts.', 'publisher' ),
			        'default' => false,
			        'state_handler' => array(
			        	'pagination[none]' => array('hide'),
			        	'_else[pagination]' => array('show'),
			        ),
			    ),

			    'ajax_unique' => array(
			        'type' => 'text',
			        'label' => __( 'Add Unique Ajax ID (optional)', 'publisher' ),
			        'description' => __( 'Use only if having issues with multiple widgets being targeted when loading.', 'publisher' ),
			        'default' => '',
			        'state_handler' => array(
			        	'pagination[none]' => array('hide'),
			        	'_else[pagination]' => array('show'),
			        ),
			    ),

			    // Post Type
				'slider_options' => array(
				    'type' => 'section',
				    'label' => __( 'Slider Options' , 'publisher' ),
				    'hide' => true,
				    'state_handler' => array(
			        	'posts_page_layout[standard]' => array('hide'),
			        	'posts_page_layout[list]' => array('hide'),
			        	'posts_page_layout[grid]' => array('hide'),
			        	'posts_page_layout[cover]' => array('hide'),
			        	'posts_page_layout[carousel]' => array('show'),
			        	'posts_page_layout[listing-h]' => array('hide'),
			        	'posts_page_layout[listing-v]' => array('hide'),
			        	'posts_page_layout[mini-grid]' => array('hide'),
			        	'posts_page_layout[mini-carousel]' => array('show'),
			        	'posts_page_layout[mini-listing]' => array('hide'),
			        	'posts_page_layout[slider]' => array('show'),
			        ),
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
					    	'description' => __( 'Duration in milliseconds spent animating between slides. Default is 250.', 'publisher' )
					    ),

					    'timeout' => array(
					    	'type' => 'text',
					    	'label' => __( 'Slide Timeout', 'publisher' ),
					    	'description' => __( 'Duration in milliseconds spent on each slide if autoplay is enabled. Default is 5000.', 'publisher' )
					    ),

				    )
				),

			),

			// The $base_folder path string.
			plugin_dir_path( __FILE__ ) . '../'
		);

	}

	// Modify the form
	function modify_form( $form ) {

		// Post Type
		$post_types = get_post_types( array( 'public' => true ), 'objects' );
		$exclude_post_types = apply_filters( 'publisher_ext_filter_posts_loop_exclude_post_type', array() );
		foreach ( $post_types as $post_type ) :

			// Continue if post type is blacklisted
			if ( in_array( $post_type->name, $exclude_post_types ) ) continue;

			// Build Posts Query: Post Type
			$form['build_posts_query']['fields']['post_type']['options'][$post_type->name] = $post_type->label;

		endforeach;

		// Taxonomies
		$taxonomies = get_taxonomies( array( 'public' => true ), 'objects' );
		$exclude_taxonomy = apply_filters( 'publisher_ext_filter_posts_loop_exclude_taxonomy', array() );
		foreach ( $taxonomies as $taxonomy ) :

			// Continue if taxonomy only has excluded post types
			$result = array_diff( $taxonomy->object_type, $exclude_post_types );
			if ( empty( $result) ) continue;

			// Continue if taxonomy is blacklisted
			if ( in_array( $taxonomy->name, $exclude_taxonomy ) ) continue;

			$tax_terms = get_terms( $taxonomy->name );

			// Continue if no terms found for post type taxonomy
			// if ( empty( $tax_terms) ) continue;

			// Build Posts Query: Taxonomies
			$form['build_posts_query']['fields']['taxonomies']['fields'][$taxonomy->name] = array(
				'type' 	=> 'section',
				'label' => $taxonomy->labels->name,
				'hide' => true,
				'fields' => array(
					'operator' => array(
						'type' => 'select',
						'default' => 'IN',
						'options' => array(
							'IN' => __( 'IN: Must have at least ONE of the selected terms', 'publisher' ),
							'NOT IN' => __( 'NOT IN: Must NOT be in selected terms', 'publisher' ),
							'AND' => __( 'AND: Must have ALL selected terms', 'publisher' ),
						)
					),
					// Terms
					'terms' => array(
					    'type' 			=> 'select',
						'description' 	=> __( 'Select the terms.', 'publisher' ),
						'default' 		=> '',
						'multiple'		=> true,
						'options'		=> array(
							'' => '---'
						)
					),
				)
			);

			// Populate Terms
			$tax_terms = get_terms( $taxonomy->name );
			foreach ( $tax_terms as $tax_term ) {
				$form['build_posts_query']['fields']['taxonomies']['fields'][$taxonomy->name]['fields']['terms']['options'][$tax_term->term_id] = $tax_term->name;
			}

		endforeach;

		// Users
		$users_args = apply_filters( 'publisher_ext_filter_posts_loop_users_args', array() );
		$users = get_users( $users_args );
		foreach ( $users as $user ):

			// Build Posts Query: Users
			$form['build_posts_query']['fields']['users']['options'][$user->ID] = $user->display_name;

		endforeach;

		// Order By
		$orderby_args = apply_filters( 'publisher_ext_filter_posts_loop_orderby_args', array() );
		foreach ( $orderby_args as $orderby_key => $orderby_value ):

			// Build Posts Query: Order By
			$form['build_posts_query']['fields']['orderby']['options'][$orderby_key] = $orderby_value;

		endforeach;

		// Time Range
		$time_range_args = apply_filters( 'publisher_ext_filter_posts_loop_time_range_args', array() );
		if ( ! empty( $time_range_args ) ) {

			// Add state handlers if Wordpress Popular Posts is active
			if ( function_exists( 'publisher_wordpress_popular_posts_add_view_options' ) ) {
				$form['build_posts_query']['fields']['time_range']['state_handler']['orderby[avg]'] = array('show');
				$form['build_posts_query']['fields']['time_range']['state_handler']['orderby[views]'] = array('show');
				$form['build_posts_query']['fields']['time_range']['state_handler']['orderby[comments]'] = array('show');

				$form['build_posts_query']['fields']['time_published']['state_handler']['orderby[avg]'] = array('show');
				$form['build_posts_query']['fields']['time_published']['state_handler']['orderby[views]'] = array('show');
				$form['build_posts_query']['fields']['time_published']['state_handler']['orderby[comments]'] = array('show');
			}

			foreach ( $time_range_args as $time_range_key => $time_range_value ):

				// Build Posts Query: Time Range
				$form['build_posts_query']['fields']['time_range']['options'][$time_range_key] = $time_range_value;

			endforeach;

		}

		// Image Size
		global $_wp_additional_image_sizes;
		if ( ! empty( $_wp_additional_image_sizes ) ) {
			foreach( $_wp_additional_image_sizes as $i => $s ) {
				$form['image_size']['options'][$i] = $i;
			}
		}

		return $form;
	}

	function initialize() {

		// Frontend Scripts/Styles
		$frontend_scripts = array();
		$frontend_styles = array();

		// Posts Loop Scripts
		$frontend_scripts[] = array(
			'publisher-posts-loop-js', get_template_directory_uri() . '/includes/plugins/so-widgets-bundle/publisher-widgets/publisher-posts-loop/js/publisher-widget.js', array( 'jquery' ), '1.0'
		);

		// History
		$frontend_scripts[] = array(
			'publisher-history-js', get_template_directory_uri() . '/includes/js/jquery.history.js', array( 'jquery' ), '1.0'
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

siteorigin_widget_register( 'publisher-posts-loop-widget', __FILE__, 'Publisher_Posts_Loop_Widget', 15 );
