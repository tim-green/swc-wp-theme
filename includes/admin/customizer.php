<?php
/**
 * Theme Customizer
 *
 * @package Publisher
 * @since Publisher 1.0
 */

/*-----------------------------------------------------------------------------------*/
/*	Theme Options
/*-----------------------------------------------------------------------------------*/

/**
 * Theme Customizer Options
 */
function publisher_customize_register( $wp_customize ) {

	// Add postMessage Support
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'background_color' )->transport = 'postMessage';


	// Default Sections
	$wp_customize->get_section( 'title_tagline' )->priority = 0;
	$wp_customize->get_section( 'title_tagline' )->panel = 'publisher_panel_theme_options';
	$wp_customize->get_section( 'title_tagline' )->title = __( 'General Settings', 'publisher' );
	$wp_customize->get_section( 'static_front_page' )->panel = 'publisher_panel_theme_options';
	$wp_customize->get_section( 'colors' )->panel = 'publisher_panel_theme_colors';
	$wp_customize->get_section( 'colors' )->title = __( 'General', 'publisher' );
	$wp_customize->get_section( 'header_image' )->panel = 'publisher_panel_theme_images';
	$wp_customize->get_section( 'background_image' )->panel = 'publisher_panel_theme_images';


	// Add Class - Custom Descriptions
	if ( class_exists( 'WP_Customize_Control' ) ) :
	class Custom_Text_Control extends WP_Customize_Control {
		public $type  = 'customtext';
		public $extra = '';
		public function render_content() {
		?>
		<label>
			<?php if ( ! empty( $this->label ) ) { ?>
				<span class="customize-control-title" style="font-size: 14px; line-height: 24px"><?php echo esc_html( $this->label ); ?></span>
			<?php } ?>
			<p class="description"><?php echo esc_html( $this->extra ); ?></p>
		</label>
		<?php
		}
	}
	endif;


	// Add Class - Multi-Select Field
	if ( class_exists( 'WP_Customize_Control' ) ) :
    class Custom_Multi_Select_Control extends WP_Customize_Control {
	    public $type = 'multiple-select';
	    public $extra = '5';

	    public function render_content() {
	    if ( empty( $this->choices ) )
	        return;
	    ?>
	    <label>
	    	<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
	    	<p class="description"><?php echo esc_html( $this->description ); ?></p>
	    	<select <?php $this->link(); ?> multiple="multiple" size="<?php echo esc_html( $this->extra ); ?>" style="width: 100%;">
		    	<?php
			    	foreach ( $this->choices as $value => $label ) {
				    	if ( is_array( $this->value() ) ) {
					    	$selected = ( in_array( $value, $this->value() ) ) ? selected( 1, 1, false ) : '';
				    	} else {
					    	$selected = '';
				    	}

				    	echo '<option value="' . esc_attr( $value ) . '"' . $selected . '>' . $label . '</option>';
				    }
				?>
			</select>
		</label>
		<?php
		}
    }
	endif;




	/*-----------------------------------------------------------------------------------*/
	/*	Site Title & Tagline -> General Settings
	/*-----------------------------------------------------------------------------------*/

	// Hide Site Tagline
	$wp_customize->add_setting( 'publisher_options_hide_tagline', array(
		'default' 			=> 0,
		'sanitize_callback' => 'publisher_sanitize_checkbox',
		'transport'         => 'postMessage'
	) );

	$wp_customize->add_control( 'publisher_options_hide_tagline', array(
		'type'    			=> 'checkbox',
		'label'   			=> __( 'Hide Site Tagline', 'publisher' ),
		'section' 			=> 'title_tagline',
	) );

	// Logo Upload
	$wp_customize->add_setting( 'publisher_options_logo', array(
		'sanitize_callback' => 'publisher_sanitize_image',
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'publisher_options_logo', array(
		'label'    			=> __( 'Logo Upload', 'publisher' ),
		'section'  			=> 'title_tagline',
		'settings' 			=> 'publisher_options_logo'
	) ) );

	// Retina Logo
	$wp_customize->add_setting( 'publisher_options_retina_logo', array(
		'default' 			=> 0,
		'sanitize_callback' => 'publisher_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'publisher_options_retina_logo', array(
		'type'    			=> 'checkbox',
		'label'   			=> __( 'Retina-Ready Logo', 'publisher' ),
		'section' 			=> 'title_tagline',
		'description'  		=> __( 'Ensures sharp rendering on retina displays. Logo should be 2x the desired size.', 'publisher' )
	) );

	// Sidebar Alignment
	$wp_customize->add_setting( 'publisher_options_sidebar_alignment', array(
		'default'      		=> 'right',
		'type'         		=> 'option',
		'sanitize_callback' => 'publisher_sanitize_sidebar_alignment',
	) );

	$wp_customize->add_control( 'publisher_options_sidebar_alignment', array(
		'settings'     		=> 'publisher_options_sidebar_alignment',
		'label'        		=> __( 'Sidebar Alignment', 'publisher' ),
		'description'  		=> __( 'Set the position of the main sidebar.', 'publisher' ),
		'section'      		=> 'title_tagline',
		'type'         		=> 'select',
		'choices'     		=> array(
			'left'   => __( 'Left', 'publisher' ),
			'right'  => __( 'Right', 'publisher' ),
		),
	) );

	// Time Format
	$wp_customize->add_setting( 'publisher_options_time_format', array(
		'default'      		=> 'date',
		'type'         		=> 'option',
		'sanitize_callback' => 'publisher_sanitize_time_format',
	) );

	$wp_customize->add_control( 'publisher_options_time_format', array(
		'settings'     		=> 'publisher_options_time_format',
		'label'        		=> __( 'Time Format', 'publisher' ),
		'section'      		=> 'title_tagline',
		'type'         		=> 'select',
		'description'  		=> __( 'Set the time format for all posts.', 'publisher' ),
		'choices'      		=> array(
			'date' => __( 'Date', 'publisher' ),
			'time' => __( 'Time Ago', 'publisher' ),
		),
	) );




	/*-----------------------------------------------------------------------------------*/
	/*	Theme Options
	/*-----------------------------------------------------------------------------------*/

	$wp_customize->add_panel( 'publisher_panel_theme_options', array(
		'title'				=> __( 'Publisher Options', 'publisher' ),
		'priority'			=> 1
	) );


	/**
	/*	Front Page
	/*-------------------------------------------------*/

	// Front Page Title
	$wp_customize->add_setting( 'publisher_options_front_page_title', array(
		'type'				=> 'option',
		'sanitize_callback' => 'publisher_sanitize_text',
	) );

	$wp_customize->add_control( 'publisher_options_front_page_title', array(
		'label'        		=> __( 'Front Page Title', 'publisher' ),
		'section'      		=> 'static_front_page',
		'settings'     		=> 'publisher_options_front_page_title',
		'type'         		=> 'text',
		'description'  		=> __( 'Title for the front page header.', 'publisher' ),
	) );

	// Front Page Description
	$wp_customize->add_setting( 'publisher_options_front_page_desc', array(
		'type'				=> 'option',
		'sanitize_callback' => 'publisher_sanitize_text',
	) );

	$wp_customize->add_control( 'publisher_options_front_page_desc', array(
		'label'        		=> __( 'Front Page Description', 'publisher' ),
		'section'      		=> 'static_front_page',
		'settings'     		=> 'publisher_options_front_page_desc',
		'type'         		=> 'textarea',
		'description'  		=> __( 'Description for the front page header.', 'publisher' ),
	) );

	// Front Page Button Text
	$wp_customize->add_setting( 'publisher_options_front_page_button_text', array(
		'type'				=> 'option',
		'sanitize_callback' => 'publisher_sanitize_text',
	) );

	$wp_customize->add_control( 'publisher_options_front_page_button_text', array(
		'label'        		=> __( 'Front Page Button Text', 'publisher' ),
		'section'      		=> 'static_front_page',
		'settings'     		=> 'publisher_options_front_page_button_text',
		'type'         		=> 'text',
		'description'  		=> __( 'Text for the front page button.', 'publisher' ),
	) );

	// Front Page Button URL
	$wp_customize->add_setting( 'publisher_options_front_page_button_url', array(
		'type'				=> 'option',
		'sanitize_callback' => 'publisher_sanitize_text',
	) );

	$wp_customize->add_control( 'publisher_options_front_page_button_url', array(
		'label'        		=> __( 'Front Page Button URL', 'publisher' ),
		'section'      		=> 'static_front_page',
		'settings'     		=> 'publisher_options_front_page_button_url',
		'type'         		=> 'text',
		'description'  		=> __( 'URL for the front page button.', 'publisher' ),
	) );

	// Front Page Header Image
	$wp_customize->add_setting( 'publisher_options_front_page_header_image', array(
		'sanitize_callback' => 'publisher_sanitize_image',
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'publisher_options_front_page_header_image', array(
		'label'     		=> __( 'Front Page Header Image', 'publisher' ),
		'section'       	=> 'static_front_page',
		'settings'      	=> 'publisher_options_front_page_header_image',
		'description'  		=> __( 'Custom image for the front page header. If not set, will use the default featured header image instead.', 'publisher' ),
	) ) );

	// Front Page Shortcode
	$wp_customize->add_setting( 'publisher_options_front_page_shortcode', array(
		'type'				=> 'option',
		'sanitize_callback' => 'publisher_sanitize_text',
	) );

	$wp_customize->add_control( 'publisher_options_front_page_shortcode', array(
		'label'        		=> __( 'Front Page Shortcode', 'publisher' ),
		'section'      		=> 'static_front_page',
		'settings'     		=> 'publisher_options_front_page_shortcode',
		'type'         		=> 'text',
		'description'  		=> __( 'Insert a shortcode to show in the front page header. This will override all other front page settings.', 'publisher' ),
	) );

	// Keep Header Image
	$wp_customize->add_setting( 'publisher_options_front_page_keep_header', array(
		'default' 			=> 0,
		'sanitize_callback' => 'publisher_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'publisher_options_front_page_keep_header', array(
		'type'    			=> 'checkbox',
		'label'   			=> __( 'Keep Header Image', 'publisher' ),
		'section' 			=> 'static_front_page',
	) );


	/**
	/*	Navigation
	/*-------------------------------------------------*/

	$wp_customize->add_section( 'publisher_section_navigation', array(
		'title'				=> __( 'Navigation', 'publisher' ),
		'panel'				=> 'publisher_panel_theme_options'
	) );

	// Search
	$wp_customize->add_setting( 'publisher_options_menus_search', array(
		'default'			=> 'show',
		'type'				=> 'option',
		'sanitize_callback' => 'publisher_sanitize_show_hide',
	) );

	$wp_customize->add_control( 'publisher_options_menus_search', array(
		'label'    			=> __( 'Menu Search Bar', 'publisher' ),
		'section'  			=> 'publisher_section_navigation',
		'settings' 			=> 'publisher_options_menus_search',
		'type'     			=> 'select',
		'description'  		=> __( 'Show search in the menu bar.', 'publisher' ),
		'choices'			=> array(
			'show' => __( 'Show', 'publisher' ),
			'hide' => __( 'Hide', 'publisher' ),
		),
	) );

	// Taxonomy Color
	$wp_customize->add_setting( 'publisher_options_menus_taxonomy_color', array(
		'default'			=> 'show',
		'type'				=> 'option',
		'sanitize_callback' => 'publisher_sanitize_show_hide',
	) );

	$wp_customize->add_control( 'publisher_options_menus_taxonomy_color', array(
		'label'    			=> __( 'Menu Taxonomy Color', 'publisher' ),
		'section'  			=> 'publisher_section_navigation',
		'settings' 			=> 'publisher_options_menus_taxonomy_color',
		'type'     			=> 'select',
		'description'  		=> __( 'Show taxonomy color in the menu bar.', 'publisher' ),
		'choices'			=> array(
			'show' => __( 'Show', 'publisher' ),
			'hide' => __( 'Hide', 'publisher' ),
		),
	) );

	// Posts Pagination
	$wp_customize->add_setting( 'publisher_options_posts_pagination', array(
		'default'      		=> 'prev-next',
		'type'         		=> 'option',
		'sanitize_callback' => 'publisher_sanitize_posts_pagination',
	) );

	$wp_customize->add_control( 'publisher_options_posts_pagination', array(
		'settings'     		=> 'publisher_options_posts_pagination',
		'label'        		=> __( 'Posts Page Pagination', 'publisher' ),
		'section'      		=> 'publisher_section_navigation',
		'type'         		=> 'select',
		'description'  		=> __( 'Select the posts pagination style.', 'publisher' ),
		'choices'      		=> array(
			'prev-next' => __( 'Prev / Next', 'publisher' ),
			'numeric'	=> __( 'Numeric', 'publisher' ),
		),
	) );

	// Breadcrumbs
	$wp_customize->add_setting( 'publisher_options_breadcrumbs', array(
		'default'      		=> 'show',
		'type'         		=> 'option',
		'sanitize_callback' => 'publisher_sanitize_show_hide',
	) );

	$wp_customize->add_control( 'publisher_options_breadcrumbs', array(
		'settings'     		=> 'publisher_options_breadcrumbs',
		'label'        		=> __( 'Posts Single Breadcrumbs', 'publisher' ),
		'section'      		=> 'publisher_section_navigation',
		'type'         		=> 'select',
		'description'  		=> __( 'Show or hide the breadcrumbs.', 'publisher' ),
		'choices'      		=> array(
			'show' => __( 'Show', 'publisher' ),
			'hide' => __( 'Hide', 'publisher' ),
		),
	) );

	// Page Links Display
	$wp_customize->add_setting( 'publisher_options_link_pages', array(
		'default'      		=> 'next_total_pages',
		'type'         		=> 'option',
		'sanitize_callback' => 'publisher_sanitize_link_pages',
	) );

	$wp_customize->add_control( 'publisher_options_link_pages', array(
		'settings'     		=> 'publisher_options_link_pages',
		'label'        		=> __( 'Page Links Display', 'publisher' ),
		'section'      		=> 'publisher_section_navigation',
		'type'         		=> 'select',
		'description'  		=> __( 'Select the page links style.', 'publisher' ),
		'choices'      		=> array(
			'next_total_pages' => __( 'Page X of Y', 'publisher' ),
			'next_and_number' => __( 'Numeric', 'publisher' ),
		),
	) );


	/**
	/*	Pages
	/*-------------------------------------------------*/

	$wp_customize->add_section( 'publisher_section_pages', array(
		'title'				=> __( 'Pages', 'publisher' ),
		'description'  		=> __( 'The default settings for every published page.', 'publisher' ),
		'panel'				=> 'publisher_panel_theme_options'
	) );

	// Page Layout
	$wp_customize->add_setting( 'publisher_options_page_layout', array(
		'default'			=> 'wide',
		'type'				=> 'option',
		'sanitize_callback' => 'publisher_sanitize_page_layout',
	) );

	$wp_customize->add_control( 'publisher_options_page_layout', array(
		'label'    			=> __( 'Page Layout', 'publisher' ),
		'section'  			=> 'publisher_section_pages',
		'settings' 			=> 'publisher_options_page_layout',
		'type'     			=> 'select',
		'description'  		=> __( 'Set the default page layout.', 'publisher' ),
		'choices'  			=> array(
			'standard'	 => __( 'Standard', 'publisher' ),
			'cover' 	 => __( 'Cover', 'publisher' ),
			'wide' 		 => __( 'Wide', 'publisher' ),
			'banner' 	 => __( 'Banner', 'publisher' )
		),
	) );

	// Page Sidebar
	$wp_customize->add_setting( 'publisher_options_page_sidebar_display', array(
		'default'			=> 'show',
		'type'				=> 'option',
		'sanitize_callback' => 'publisher_sanitize_show_hide',
	) );

	$wp_customize->add_control( 'publisher_options_page_sidebar_display', array(
		'label'    			=> __( 'Page Sidebar', 'publisher' ),
		'section'  			=> 'publisher_section_pages',
		'settings' 			=> 'publisher_options_page_sidebar_display',
		'type'     			=> 'select',
		'description'  		=> __( 'Set the default page sidebar layout.', 'publisher' ),
		'choices'  			=> array(
			'show' => __( 'Show', 'publisher' ),
			'hide' => __( 'Hide', 'publisher' ),
		),
	) );

	// Page Details
	$wp_customize->add_setting( 'publisher_options_page_details', array(
		'capability'   		=> 'edit_theme_options',
		'sanitize_callback' => 'publisher_sanitize_page_details',
	) );

	$wp_customize->add_control( new Custom_Multi_Select_Control( $wp_customize, 'publisher_options_page_details', array(
		'label'				=> __( 'Page Details', 'publisher' ),
		'section'      		=> 'publisher_section_pages',
		'settings'     		=> 'publisher_options_page_details',
		'type'     			=> 'multiple-select',
		'description'  		=> __( 'Hide the following page details.', 'publisher' ),
		'choices'			=> get_page_details_select(),
	) )	);


	/**
	/*	Posts Page
	/*-------------------------------------------------*/

	$wp_customize->add_section( 'publisher_section_posts_page', array(
		'title'				=> __( 'Posts Page', 'publisher' ),
		'description'  		=> __( 'The default settings for every posts page, including archives and search results.', 'publisher' ),
		'panel'				=> 'publisher_panel_theme_options'
	) );

	// Layout
	$wp_customize->add_setting( 'publisher_options_posts_layout', array(
		'default'			=> 'standard',
		'type'				=> 'option',
		'sanitize_callback' => 'publisher_sanitize_posts_layout',
	) );

	$wp_customize->add_control( 'publisher_options_posts_layout', array(
		'label'    			=> __( 'Posts Page Layout', 'publisher' ),
		'section'  			=> 'publisher_section_posts_page',
		'settings' 			=> 'publisher_options_posts_layout',
		'type'     			=> 'select',
		'description'  		=> __( 'Set the default posts page layout.', 'publisher' ),
		'choices'  			=> array(
			'standard' => __( 'Standard', 'publisher' ),
			'list' 	   => __( 'List', 'publisher' ),
			'grid'	   => __( 'Grid', 'publisher' ),
		),
	) );

	// Posts Page Columns
	$wp_customize->add_setting( 'publisher_options_posts_post_columns', array(
		'default'			=> 2,
		'type'				=> 'option',
		'sanitize_callback' => 'publisher_sanitize_integer',
	) );

	$wp_customize->add_control( 'publisher_options_posts_post_columns', array(
		'label'    			=> __( 'Posts Page Columns', 'publisher' ),
		'section'  			=> 'publisher_section_posts_page',
		'settings' 			=> 'publisher_options_posts_post_columns',
		'type'     			=> 'select',
		'description'  		=> __( 'Set the number of columns to show.', 'publisher' ),
		'choices'  			=> array(
			1 => __( '1', 'publisher' ),
			2 => __( '2', 'publisher' ),
			3 => __( '3', 'publisher' ),
			4 => __( '4', 'publisher' ),
			5 => __( '5', 'publisher' ),
		),
		'active_callback' 	=> 'publisher_options_post_layout'
	) );

	// Posts Page Sidebar
	$wp_customize->add_setting( 'publisher_options_posts_sidebar_display', array(
		'default'			=> 'show',
		'type'				=> 'option',
		'sanitize_callback' => 'publisher_sanitize_show_hide',
	) );

	$wp_customize->add_control( 'publisher_options_posts_sidebar_display', array(
		'label'    			=> __( 'Posts Page Sidebar', 'publisher' ),
		'section'  			=> 'publisher_section_posts_page',
		'settings' 			=> 'publisher_options_posts_sidebar_display',
		'type'     			=> 'select',
		'description'  		=> __( 'Set the default posts page sidebar layout.', 'publisher' ),
		'choices'  			=> array(
			'show' => __( 'Show', 'publisher' ),
			'hide' => __( 'Hide', 'publisher' ),
		),
	) );

	// Post Format Icons
	$wp_customize->add_setting( 'publisher_options_posts_show_format_icon', array(
		'default'			=> 'show',
		'type'				=> 'option',
		'sanitize_callback' => 'publisher_sanitize_show_hide',
	) );

	$wp_customize->add_control( 'publisher_options_posts_show_format_icon', array(
		'label'    			=> __( 'Post Format Icon', 'publisher' ),
		'section'  			=> 'publisher_section_posts_page',
		'settings' 			=> 'publisher_options_posts_show_format_icon',
		'type'     			=> 'select',
		'description'  		=> __( 'Set the default post format icon display.', 'publisher' ),
		'choices'  			=> array(
			'show' => __( 'Show', 'publisher' ),
			'hide' => __( 'Hide', 'publisher' ),
		),
	) );

	// Show Content
	$wp_customize->add_setting( 'publisher_options_posts_show_content', array(
		'default'			=> 'excerpt',
		'type'				=> 'option',
		'sanitize_callback' => 'publisher_sanitize_posts_show_content',
	) );

	$wp_customize->add_control( 'publisher_options_posts_show_content', array(
		'label'    			=> __( 'Show Content', 'publisher' ),
		'section'  			=> 'publisher_section_posts_page',
		'settings' 			=> 'publisher_options_posts_show_content',
		'type'     			=> 'select',
		'description'  		=> __( 'Display the post content.', 'publisher' ),
		'choices'  			=> array(
			'none' 	  => __( 'None', 'publisher' ),
			'excerpt' => __( 'Excerpt', 'publisher' ),
			'full' 	  => __( 'Full', 'publisher' )
		),
	) );

	// Read More
	$wp_customize->add_setting( 'publisher_options_posts_read_more', array(
		'type'				=> 'option',
		'sanitize_callback' => 'publisher_sanitize_text',
	) );

	$wp_customize->add_control( 'publisher_options_posts_read_more', array(
		'label'        		=> __( 'Read More', 'publisher' ),
		'section'      		=> 'publisher_section_posts_page',
		'settings'     		=> 'publisher_options_posts_read_more',
		'type'         		=> 'text',
		'description'  		=> __( 'Text for the read more link.', 'publisher' ),
	) );


	/**
	/*	Posts Single
	/*-------------------------------------------------*/

	$wp_customize->add_section( 'publisher_section_posts_single', array(
		'title'				=> __( 'Posts Single', 'publisher' ),
		'description'  		=> __( 'The default settings for the single post page, including custom post types.', 'publisher' ),
		'panel'				=> 'publisher_panel_theme_options'
	) );

	// Post Layout
	$wp_customize->add_setting( 'publisher_options_post_layout', array(
		'default'			=> 'standard',
		'type'				=> 'option',
		'sanitize_callback' => 'publisher_sanitize_post_layout',
	) );

	$wp_customize->add_control( 'publisher_options_post_layout', array(
		'label'    			=> __( 'Post Layout', 'publisher' ),
		'section'  			=> 'publisher_section_posts_single',
		'settings' 			=> 'publisher_options_post_layout',
		'type'     			=> 'select',
		'description'  		=> __( 'Set the default post layout', 'publisher' ),
		'choices'  			=> array(
			'standard'	 => __( 'Standard', 'publisher' ),
			'cover' 	 => __( 'Cover', 'publisher' ),
			'wide' 		 => __( 'Wide', 'publisher' ),
			'banner' 	 => __( 'Banner', 'publisher' )
		),
	) );

	// Post Sidebar
	$wp_customize->add_setting( 'publisher_options_post_sidebar_display', array(
		'default'			=> 'show',
		'type'				=> 'option',
		'sanitize_callback' => 'publisher_sanitize_show_hide',
	) );

	$wp_customize->add_control( 'publisher_options_post_sidebar_display', array(
		'label'    			=> __( 'Post Sidebar', 'publisher' ),
		'section'  			=> 'publisher_section_posts_single',
		'settings' 			=> 'publisher_options_post_sidebar_display',
		'type'     			=> 'select',
		'description'  		=> __( 'Set the default post sidebar layout', 'publisher' ),
		'choices'  			=> array(
			'show' => __( 'Show', 'publisher' ),
			'hide' => __( 'Hide', 'publisher' ),
		),
	) );

	// Post Pagination
	$wp_customize->add_setting( 'publisher_options_post_pagination', array(
		'default'      		=> 'prev-next',
		'type'         		=> 'option',
		'sanitize_callback' => 'publisher_sanitize_post_pagination',
	) );

	$wp_customize->add_control( 'publisher_options_post_pagination', array(
		'settings'     		=> 'publisher_options_post_pagination',
		'label'        		=> __( 'Post Pagination', 'publisher' ),
		'section'      		=> 'publisher_section_posts_single',
		'type'         		=> 'select',
		'description'  		=> __( 'Select the post pagination style', 'publisher' ),
		'choices'      		=> array(
			'none' 	 	 => __( 'None', 'publisher' ),
			'prev-next'  => __( 'Prev / Next', 'publisher' ),
			'post-image' => __( 'Post Image', 'publisher' ),
			'next-up' 	 => __( 'Next Up', 'publisher' ),
		),
	) );

	// Related Posts
	$wp_customize->add_setting( 'publisher_options_post_related_posts', array(
		'type'				=> 'option',
		'sanitize_callback' => 'publisher_sanitize_text',
	) );

	$wp_customize->add_control( 'publisher_options_post_related_posts', array(
		'label'        		=> __( 'Related Posts', 'publisher' ),
		'section'      		=> 'publisher_section_posts_single',
		'settings'     		=> 'publisher_options_post_related_posts',
		'type'         		=> 'text',
		'description'  		=> __( 'Text for the related posts section.', 'publisher' ),
	) );

	// Post Details
	$wp_customize->add_setting( 'publisher_options_post_details', array(
		'capability'   		=> 'edit_theme_options',
		'sanitize_callback' => 'publisher_sanitize_post_details',
	) );

	$wp_customize->add_control( new Custom_Multi_Select_Control( $wp_customize, 'publisher_options_post_details', array(
		'label'        		=> __( 'Post Details', 'publisher' ),
		'section'      		=> 'publisher_section_posts_single',
		'settings'     		=> 'publisher_options_post_details',
		'type'     			=> 'multiple-select',
		'description'  		=> __( 'Hide the following post details', 'publisher' ),
		'choices'			=> get_post_details_select(),
		'extra'				=> 6
	) )	);


	/**
	/*	Comments
	/*-------------------------------------------------*/

	$wp_customize->add_section( 'publisher_section_comments', array(
		'title'				=> __( 'Comments', 'publisher' ),
		'description'		=> __( 'The following options affect the comments section of posts/pages.', 'publisher' ),
		'panel'				=> 'publisher_panel_theme_options'
	) );

	// Time Format
	$wp_customize->add_setting( 'publisher_options_comment_time_format', array(
		'default'      		=> 'time',
		'type'         		=> 'option',
		'sanitize_callback' => 'publisher_sanitize_comment_time_format',
	) );

	$wp_customize->add_control( 'publisher_options_comment_time_format', array(
		'settings'     		=> 'publisher_options_comment_time_format',
		'label'        		=> __( 'Time Format', 'publisher' ),
		'section'      		=> 'publisher_section_comments',
		'type'         		=> 'select',
		'description'  		=> __( 'Select the comment time format.', 'publisher' ),
		'choices'      		=> array(
			'date' => __( 'Date', 'publisher' ),
			'time' => __( 'Time Ago', 'publisher' ),
		),
	) );

	// Comments Pagination
	$wp_customize->add_setting( 'publisher_options_comment_pagination', array(
		'default'      		=> 'numeric',
		'type'         		=> 'option',
		'sanitize_callback' => 'publisher_sanitize_comment_pagination',
	) );

	$wp_customize->add_control( 'publisher_options_comment_pagination', array(
		'settings'     		=> 'publisher_options_comment_pagination',
		'label'        		=> __( 'Pagination Style', 'publisher' ),
		'section'      		=> 'publisher_section_comments',
		'type'         		=> 'select',
		'description'  		=> __( 'Select the comment pagination format.', 'publisher' ),
		'choices'      		=> array(
			'prev-next' => __( 'Prev / Next', 'publisher' ),
			'numeric'	=> __( 'Numeric', 'publisher' ),
		),
	) );


	/**
	/*	Footer
	/*-------------------------------------------------*/

	$wp_customize->add_section( 'publisher_section_footer', array(
		'title'				=> __( 'Footer', 'publisher' ),
		'panel'				=> 'publisher_panel_theme_options'
	) );

	// Footer Cap
	$wp_customize->add_setting( 'publisher_options_footer_cap', array(
		'default'           => '',
		'sanitize_callback' => 'publisher_sanitize_integer',
	) );

	$wp_customize->add_control( 'publisher_options_footer_cap', array(
		'type'     			=> 'dropdown-pages',
		'label'    			=> __( 'Footer Cap', 'publisher' ),
		'description'  		=> __( 'Sets the page content to show above the footer.', 'publisher' ),
		'settings' 			=> 'publisher_options_footer_cap',
		'section'  			=> 'publisher_section_footer',
	) );

	// Footer Columns
	$wp_customize->add_setting( 'publisher_options_footer_columns', array(
		'default'			=> 3,
		'type'				=> 'option',
		'sanitize_callback' => 'publisher_sanitize_integer',
	) );

	$wp_customize->add_control( 'publisher_options_footer_columns', array(
		'label'    			=> __( 'Footer Columns', 'publisher' ),
		'description'  		=> __( 'Sets the number of columns in the footer.', 'publisher' ),
		'section'  			=> 'publisher_section_footer',
		'settings' 			=> 'publisher_options_footer_columns',
		'type'     			=> 'select',
		'choices'  			=> array(
			0 => __( '0', 'publisher' ),
			1 => __( '1', 'publisher' ),
			2 => __( '2', 'publisher' ),
			3 => __( '3', 'publisher' ),
			4 => __( '4', 'publisher' ),
			5 => __( '5', 'publisher' ),
		),
	) );

	// Footer Tagline
	$wp_customize->add_setting( 'publisher_options_footer_tagline', array(
		'type'				=> 'option',
		'sanitize_callback' => 'publisher_sanitize_text',
		'transport'         => 'postMessage'
	) );

	$wp_customize->add_control( 'publisher_options_footer_tagline', array(
		'label'        		=> __( 'Footer Tagline', 'publisher' ),
		'description'  		=> __( 'Custom tagline for the footer.', 'publisher' ),
		'section'      		=> 'publisher_section_footer',
		'settings'     		=> 'publisher_options_footer_tagline',
		'type'         		=> 'text',
	) );


	/**
	/*	Advanced
	/*-------------------------------------------------*/

	$wp_customize->add_section( 'publisher_section_advanced', array(
		'title'				=> __( 'Advanced', 'publisher' ),
		'panel'				=> 'publisher_panel_theme_options'
	) );

	// Excerpt Length
	$wp_customize->add_setting( 'publisher_options_excerpt_length', array(
		'type'				=> 'option',
		'sanitize_callback' => 'publisher_sanitize_text',
	) );

	$wp_customize->add_control( 'publisher_options_excerpt_length', array(
		'label'        		=> __( 'Excerpt Length', 'publisher' ),
		'section'      		=> 'publisher_section_advanced',
		'settings'     		=> 'publisher_options_excerpt_length',
		'type'         		=> 'text',
		'description'  		=> __( 'Number of words to show in the excerpt. Default is 35.', 'publisher' ),
	) );

	// Featured Terms
	$wp_customize->add_setting( 'publisher_options_featured_terms', array(
		'capability'   		=> 'edit_theme_options',
		'sanitize_callback' => 'publisher_sanitize_featured_terms',
	) );

	$wp_customize->add_control( new Custom_Multi_Select_Control( $wp_customize, 'publisher_options_featured_terms', array(
		'label'        		=> __( 'Featured Terms', 'publisher' ),
		'section'      		=> 'publisher_section_advanced',
		'settings'     		=> 'publisher_options_featured_terms',
		'type'     			=> 'multiple-select',
		'description'  		=> __( 'Select the terms to display a featured icon.', 'publisher' ),
		'choices'			=> apply_filters( 'publisher_filter_options_featured_terms', get_terms_select( '', true ) ),
		'extra'				=> 10
	) )	);

	// Taxonomy Image and Color
	$wp_customize->add_setting( 'publisher_options_taxonomy_image_color', array(
		'capability'   		=> 'edit_theme_options',
		'sanitize_callback' => 'publisher_sanitize_taxonomy_image_color',
	) );

	$wp_customize->add_control( new Custom_Multi_Select_Control( $wp_customize, 'publisher_options_taxonomy_image_color', array(
		'label'        		=> __( 'Taxonomy Image and Color', 'publisher' ),
		'section'      		=> 'publisher_section_advanced',
		'settings'     		=> 'publisher_options_taxonomy_image_color',
		'type'     			=> 'multiple-select',
		'description'  		=> __( 'Select taxonomies to enable a custom header image and color selection.', 'publisher' ),
		'choices'			=> apply_filters( 'publisher_filter_options_taxonomy_image_color', get_taxonomy_select() ),
		'extra'				=> 8
	) )	);

	// Custom Post Type Options
	$wp_customize->add_setting( 'publisher_options_cpt_options', array(
		'capability'   		=> 'edit_theme_options',
		'sanitize_callback' => 'publisher_sanitize_cpt_options',
	) );

	$wp_customize->add_control( new Custom_Multi_Select_Control( $wp_customize, 'publisher_options_cpt_options', array(
		'label'        		=> __( 'Custom Post Type Options', 'publisher' ),
		'section'      		=> 'publisher_section_advanced',
		'settings'     		=> 'publisher_options_cpt_options',
		'type'     			=> 'multiple-select',
		'description'  		=> __( 'Select post types to enable customized options. You must refresh this page in order to see the new options.', 'publisher' ),
		'choices'			=> apply_filters( 'publisher_filter_options_taxonomy_image_color', get_post_type_select( false ) ),
		'extra'				=> 6
	) )	);

	// Hide Terms
	$wp_customize->add_setting( 'publisher_options_hide_terms', array(
		'capability'   		=> 'edit_theme_options',
		'sanitize_callback' => 'publisher_sanitize_hide_terms',
	) );

	$wp_customize->add_control( new Custom_Multi_Select_Control( $wp_customize, 'publisher_options_hide_terms', array(
		'label'        		=> __( 'Hide Terms', 'publisher' ),
		'section'      		=> 'publisher_section_advanced',
		'settings'     		=> 'publisher_options_hide_terms',
		'type'     			=> 'multiple-select',
		'description'  		=> __( 'Hide the selected terms from appearing in main listings. Only hides from displaying.', 'publisher' ),
		'choices'			=> apply_filters( 'publisher_filter_options_hide_terms', get_terms_select() ),
		'extra'				=> 10
	) )	);




	/*-----------------------------------------------------------------------------------*/
	/*	Sections
	/*-----------------------------------------------------------------------------------*/

	$wp_customize->add_panel( 'publisher_panel_theme_section', array(
		'title'				=> __( 'Sections', 'publisher' ),
		'priority'			=> 1
	) );


	/**
	/*	Call To Action
	/*-------------------------------------------------*/

 	$wp_customize->add_section( 'publisher_section_call_to_action', array(
	    'title'				=> __( 'Call To Action', 'publisher' ),
		'panel'				=> 'publisher_panel_theme_section'
	) );

	// Call To Action Text
	$wp_customize->add_setting( 'publisher_options_cta_text', array(
		'type'				=> 'option',
		'sanitize_callback' => 'publisher_sanitize_text',
	) );

	$wp_customize->add_control( 'publisher_options_cta_text', array(
		'label'        		=> __( 'CTA Text', 'publisher' ),
		'section'      		=> 'publisher_section_call_to_action',
		'settings'     		=> 'publisher_options_cta_text',
		'type'         		=> 'text',
		'description'  		=> __( 'Text for the call to action', 'publisher' ),
	) );

	// Call To Action Text
	$wp_customize->add_setting( 'publisher_options_cta_button_text', array(
		'type'				=> 'option',
		'sanitize_callback' => 'publisher_sanitize_text',
	) );

	$wp_customize->add_control( 'publisher_options_cta_button_text', array(
		'label'        		=> __( 'CTA Button Text', 'publisher' ),
		'section'      		=> 'publisher_section_call_to_action',
		'settings'     		=> 'publisher_options_cta_button_text',
		'type'         		=> 'text',
		'description'  		=> __( 'Text for the call to action button', 'publisher' ),
	) );

	// Call To Action URL
	$wp_customize->add_setting( 'publisher_options_cta_button_url', array(
		'sanitize_callback' => 'publisher_sanitize_text',
		'type'				=> 'option',
	) );

	$wp_customize->add_control( 'publisher_options_cta_button_url', array(
		'label'        		=> __( 'CTA URL', 'publisher' ),
		'section'      		=> 'publisher_section_call_to_action',
		'settings'     		=> 'publisher_options_cta_button_url',
		'type'         		=> 'text',
		'description'  		=> __( 'Link for the call to action', 'publisher' ),
	) );

	// Call To Action Color
	$wp_customize->add_setting( 'publisher_options_cta_color', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_cta_color', array(
		'label'    			=> __( 'CTA Text Color', 'publisher' ),
		'section'  			=> 'publisher_section_call_to_action',
		'settings' 			=> 'publisher_options_cta_color',
	) ) );

	// Call To Action Background Color
	$wp_customize->add_setting( 'publisher_options_cta_bg_color', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_cta_bg_color', array(
		'label'    			=> __( 'CTA Background Color', 'publisher' ),
		'section'  			=> 'publisher_section_call_to_action',
		'settings' 			=> 'publisher_options_cta_bg_color',
	) ) );

	// Call To Action Background Image
	$wp_customize->add_setting( 'publisher_options_cta_image', array(
		'sanitize_callback' => 'publisher_sanitize_image',
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'publisher_options_cta_image', array(
		'label'     		=> __( 'CTA Background Image', 'publisher' ),
		'section'       	=> 'publisher_section_call_to_action',
		'settings'      	=> 'publisher_options_cta_image',
	) ) );

	// Call To Action Background Image Style
	$wp_customize->add_setting( 'publisher_options_cta_image_style', array(
		'default'			=> 'full-cover',
		'type'				=> 'option',
		'sanitize_callback' => 'publisher_sanitize_cta_image_style',
	) );

	$wp_customize->add_control( 'publisher_options_cta_image_style', array(
		'label'    			=> __( 'CTA Background Image Style', 'publisher' ),
		'section'  			=> 'publisher_section_call_to_action',
		'settings' 			=> 'publisher_options_cta_image_style',
		'type'     			=> 'select',
		'choices'  			=> array(
			'full-cover' => __( 'Full Cover', 'publisher' ),
			'tiled' 	 => __( 'Tiled', 'publisher' ),
		),
	) );


	/**
	/*	Leaderboard
	/*-------------------------------------------------*/

	$wp_customize->add_section( 'publisher_section_leaderboard', array(
		'title'				=> __( 'Leaderboard', 'publisher' ),
		'panel'				=> 'publisher_panel_theme_section'
	) );

	// Leaderboard Image
	$wp_customize->add_setting( 'publisher_options_leaderboard_image', array(
		'sanitize_callback' => 'publisher_sanitize_image',
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'publisher_options_leaderboard_image', array(
		'label'    			=> __( 'Leaderboard Image', 'publisher' ),
		'section'  			=> 'publisher_section_leaderboard',
		'settings' 			=> 'publisher_options_leaderboard_image',
	) ) );

	// Leaderboard Title
	$wp_customize->add_setting( 'publisher_options_leaderboard_title', array(
		'type'				=> 'option',
		'sanitize_callback' => 'publisher_sanitize_text',
	) );

	$wp_customize->add_control( 'publisher_options_leaderboard_title', array(
		'label'        		=> __( 'Leaderboard Title', 'publisher' ),
		'description'  		=> __( 'Title for the leaderboard image. Not displayed.', 'publisher' ),
		'section'      		=> 'publisher_section_leaderboard',
		'settings'     		=> 'publisher_options_leaderboard_title',
		'type'         		=> 'text',
	) );

	// Leaderboard URL
	$wp_customize->add_setting( 'publisher_options_leaderboard_url', array(
		'type'				=> 'option',
		'sanitize_callback' => 'publisher_sanitize_text',
	) );

	$wp_customize->add_control( 'publisher_options_leaderboard_url', array(
		'label'        		=> __( 'Leaderboard URL', 'publisher' ),
		'description'  		=> __( 'URL for the leaderboard image.', 'publisher' ),
		'section'      		=> 'publisher_section_leaderboard',
		'settings'     		=> 'publisher_options_leaderboard_url',
		'type'         		=> 'text',
	) );




	/*-----------------------------------------------------------------------------------*/
	/*	Custom Post Types
	/*-----------------------------------------------------------------------------------*/

	$cpt_options = get_theme_mod( 'publisher_options_cpt_options' );

	if ( ! empty( $cpt_options ) ) {

		foreach ( array_filter( $cpt_options ) as $cpt_type ) {

			$post_type = get_post_type_object( $cpt_type );

			$wp_customize->add_panel( 'publisher_panel_' . $post_type->name, array(
				'title'          	=> sprintf( __( 'CPT : %s', 'publisher' ), $post_type->label ),
				'priority'			=> 3
			) );


			/**
			/*	Custom Post Type Page
			/*-------------------------------------------------*/

			$wp_customize->add_section( 'publisher_section_' . $post_type->name . '_page', array(
				'title'				=> sprintf( __( '%s Page', 'publisher' ), $post_type->labels->name ),
				'panel'				=> 'publisher_panel_' . $post_type->name
			) );

			// CPT Page Title
			$wp_customize->add_setting( 'publisher_options_' . $post_type->name . '_page_title', array(
				'type'				=> 'option',
				'sanitize_callback' => 'publisher_sanitize_text'
			) );

			$wp_customize->add_control( 'publisher_options_' . $post_type->name . '_page_title', array(
				'label'        => sprintf( __( '%s Page Title', 'publisher' ), $post_type->labels->name ),
				'section'      => 'publisher_section_' . $post_type->name . '_page',
				'settings'     => 'publisher_options_' . $post_type->name . '_page_title',
				'type'         => 'text',
				'description'  => sprintf( __( 'Title for the %s page header', 'publisher' ), $post_type->labels->name ),
			) );

			// CPT Page Description
			$wp_customize->add_setting( 'publisher_options_' . $post_type->name . '_page_desc', array(
				'type'				=> 'option',
				'sanitize_callback' => 'publisher_sanitize_text'
			) );

			$wp_customize->add_control( 'publisher_options_' . $post_type->name . '_page_desc', array(
				'label'        => sprintf( __( '%s Page Description', 'publisher' ), $post_type->labels->name ),
				'section'      => 'publisher_section_' . $post_type->name . '_page',
				'settings'     => 'publisher_options_' . $post_type->name . '_page_desc',
				'type'         => 'textarea',
				'description'  => sprintf( __( 'Description for the %s page header', 'publisher' ), $post_type->labels->name ),
			) );

			// CPT Header Image
			$wp_customize->add_setting( 'publisher_options_' . $post_type->name . '_header_image', array(
				'sanitize_callback' => 'publisher_sanitize_image'
			) );

			$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'publisher_options_' . $post_type->name . '_header_image', array(
				'label'     	=> sprintf( __( '%s Page Header Image', 'publisher' ), $post_type->labels->name ),
				'section'       => 'publisher_section_' . $post_type->name . '_page',
				'settings'      => 'publisher_options_' . $post_type->name . '_header_image',
				'description'  	=> __( 'The default image for the featured header section.', 'publisher' ),
			) ) );

			// CPT Layout
			$wp_customize->add_setting( 'publisher_options_' . $post_type->name . '_posts_layout', array(
				'default'			=> 'standard',
				'type'				=> 'option',
				'sanitize_callback' => 'publisher_sanitize_posts_layout'
			) );

			$wp_customize->add_control( 'publisher_options_' . $post_type->name . '_posts_layout', array(
				'label'    			=> sprintf( __( '%s Page layout', 'publisher' ), $post_type->label ),
				'section'  			=> 'publisher_section_' . $post_type->name . '_page',
				'settings' 			=> 'publisher_options_' . $post_type->name . '_posts_layout',
				'type'     			=> 'select',
				'description'  		=> sprintf( __( 'Set the default %s page layout', 'publisher' ), $post_type->label ),
				'choices'  			=> array(
					'standard' => __( 'Standard', 'publisher' ),
					'list' 	   => __( 'List', 'publisher' ),
					'grid'	   => __( 'Grid', 'publisher' ),
				),
			) );

			// CPT Post Columns
			$wp_customize->add_setting( 'publisher_options_' . $post_type->name . '_post_columns', array(
				'default'			=> 2,
				'type'				=> 'option',
				'sanitize_callback' => 'publisher_sanitize_integer'
			) );

			$wp_customize->add_control( 'publisher_options_' . $post_type->name . '_post_columns', array(
				'label'    			=> __( 'Post Columns', 'publisher' ),
				'section'  			=> 'publisher_section_' . $post_type->name . '_page',
				'settings' 			=> 'publisher_options_' . $post_type->name . '_post_columns',
				'type'     			=> 'select',
				'description'  		=> __( 'Applicable only if using the Grid layout', 'publisher' ),
				'choices'  			=> array(
					1 => __( '1', 'publisher' ),
					2 => __( '2', 'publisher' ),
					3 => __( '3', 'publisher' ),
					4 => __( '4', 'publisher' ),
					5 => __( '5', 'publisher' )
				),
			) );

			// CPT Sidebar
			$wp_customize->add_setting( 'publisher_options_' . $post_type->name . '_posts_sidebar_display', array(
				'default'			=> 'show',
				'type'				=> 'option',
				'sanitize_callback' => 'publisher_sanitize_show_hide'
			) );

			$wp_customize->add_control( 'publisher_options_' . $post_type->name . '_posts_sidebar_display', array(
				'label'    			=> sprintf( __( '%s Page Sidebar', 'publisher' ), $post_type->label ),
				'section'  			=> 'publisher_section_' . $post_type->name . '_page',
				'settings' 			=> 'publisher_options_' . $post_type->name . '_posts_sidebar_display',
				'type'     			=> 'select',
				'description'  		=> sprintf( __( 'Set the default %s page sidebar layout', 'publisher' ), $post_type->label ),
				'choices'  			=> array(
					'show' => __( 'Show', 'publisher' ),
					'hide' => __( 'Hide', 'publisher' ),
				),
			) );

			// CPT Post Format Icons
			$wp_customize->add_setting( 'publisher_options_' . $post_type->name . '_show_format_icon', array(
				'default'			=> 'show',
				'type'				=> 'option',
				'sanitize_callback' => 'publisher_sanitize_show_hide',
			) );

			$wp_customize->add_control( 'publisher_options_' . $post_type->name . '_show_format_icon', array(
				'label'    			=> __( 'Post Format Icon', 'publisher' ),
				'section'  			=> 'publisher_section_' . $post_type->name . '_page',
				'settings' 			=> 'publisher_options_' . $post_type->name . '_show_format_icon',
				'type'     			=> 'select',
				'description'  		=> __( 'Set the default post format icon display.', 'publisher' ),
				'choices'  			=> array(
					'show' => __( 'Show', 'publisher' ),
					'hide' => __( 'Hide', 'publisher' ),
				),
			) );

			// CPT Show Content
			$wp_customize->add_setting( 'publisher_options_' . $post_type->name . '_show_content', array(
				'default'			=> 'excerpt',
				'type'				=> 'option',
				'sanitize_callback' => 'publisher_sanitize_posts_show_content'
			) );

			$wp_customize->add_control( 'publisher_options_' . $post_type->name . '_show_content', array(
				'label'    			=> __( 'Show Content', 'publisher' ),
				'section'  			=> 'publisher_section_' . $post_type->name . '_page',
				'settings' 			=> 'publisher_options_' . $post_type->name . '_show_content',
				'type'     			=> 'select',
				'description'  		=> __( 'Display the post content.', 'publisher' ),
				'choices'  			=> array(
					'none' 	  => __( 'None', 'publisher' ),
					'excerpt' => __( 'Excerpt', 'publisher' ),
					'full' 	  => __( 'Full', 'publisher' )
				),
			) );

			// CPT Read More
			$wp_customize->add_setting( 'publisher_options_' . $post_type->name . '_read_more', array(
				'type'				=> 'option',
				'sanitize_callback' => 'publisher_sanitize_text',
			) );

			$wp_customize->add_control( 'publisher_options_' . $post_type->name . '_read_more', array(
				'label'        		=> __( 'Read More', 'publisher' ),
				'section'  			=> 'publisher_section_' . $post_type->name . '_page',
				'settings'     		=> 'publisher_options_' . $post_type->name . '_read_more',
				'type'         		=> 'text',
				'description'  		=> __( 'Text for the read more link', 'publisher' ),
			) );

			// CPT Posts Pagination
			$wp_customize->add_setting( 'publisher_options_' . $post_type->name . '_pagination', array(
				'default'      		=> 'prev-next',
				'type'         		=> 'option',
				'sanitize_callback' => 'publisher_sanitize_posts_pagination',
			) );

			$wp_customize->add_control( 'publisher_options_' . $post_type->name . '_pagination', array(
				'settings'     		=> 'publisher_options_' . $post_type->name . '_pagination',
				'label'        		=> sprintf( __( '%s Page Pagination', 'publisher' ), $post_type->labels->name ),
				'section'      		=> 'publisher_section_' . $post_type->name . '_page',
				'type'         		=> 'select',
				'description'  		=> __( 'Select the posts pagination style.', 'publisher' ),
				'choices'      		=> array(
					'prev-next' => __( 'Prev / Next', 'publisher' ),
					'numeric'	=> __( 'Numeric', 'publisher' ),
				),
			) );


			/**
			/*	Custom Post Type Single
			/*-------------------------------------------------*/

			$wp_customize->add_section( 'publisher_section_' . $post_type->name . '_single', array(
				'title'				=> sprintf( __( '%s Single', 'publisher' ), $post_type->labels->name ),
				'panel'				=> 'publisher_panel_' . $post_type->name
			) );

			// CPT Layout
			$wp_customize->add_setting( 'publisher_options_' . $post_type->name . '_post_layout', array(
				'default'			=> 'standard',
				'type'				=> 'option',
				'sanitize_callback' => 'publisher_sanitize_post_layout',
			) );

			$wp_customize->add_control( 'publisher_options_' . $post_type->name . '_post_layout', array(
				'label'    			=> sprintf( __( '%s Layout', 'publisher' ), $post_type->labels->name ),
				'section'  			=> 'publisher_section_' . $post_type->name . '_single',
				'settings' 			=> 'publisher_options_' . $post_type->name . '_post_layout',
				'type'     			=> 'select',
				'description'  		=> sprintf( __( 'Set the default %s single layout', 'publisher' ), $post_type->label ),
				'choices'  			=> array(
					'standard'	 => __( 'Standard', 'publisher' ),
					'cover' 	 => __( 'Cover', 'publisher' ),
					'wide' 		 => __( 'Wide', 'publisher' ),
					'banner' 	 => __( 'Banner', 'publisher' )
				),
			) );

			// CPT Archive Home
			$wp_customize->add_setting( 'publisher_options_' . $post_type->name . '_post_archive_home', array(
				'default'			=> '',
				'type'				=> 'option',
				'sanitize_callback' => 'publisher_sanitize_integer',
			) );

			$wp_customize->add_control( 'publisher_options_' . $post_type->name . '_post_archive_home', array(
				'label'    			=> sprintf( __( '%s Archive Home', 'publisher' ), $post_type->label ),
				'section'  			=> 'publisher_section_' . $post_type->name . '_single',
				'settings' 			=> 'publisher_options_' . $post_type->name . '_post_archive_home',
				'type'     			=> 'dropdown-pages',
			) );

			// CPT Default Archive
			$wp_customize->add_setting( 'publisher_options_' . $post_type->name . '_post_default_archive', array(
				'default' 			=> 0,
				'sanitize_callback' => 'publisher_sanitize_checkbox',
			) );

			$wp_customize->add_control( 'publisher_options_' . $post_type->name . '_post_default_archive', array(
				'type'    			=> 'checkbox',
				'label'   			=> __( 'Use Default Archive', 'publisher' ),
				'description'		=> sprintf( __( 'Set a custom archive link page, or use the default one. If set, will display in the breadcrumbs.', 'publisher' ), $post_type->label ),
				'section'  			=> 'publisher_section_' . $post_type->name . '_single',
			) );

			// CPT Sidebar
			$wp_customize->add_setting( 'publisher_options_' . $post_type->name . '_post_sidebar_display', array(
				'default'			=> 'show',
				'type'				=> 'option',
				'sanitize_callback' => 'publisher_sanitize_show_hide',
			) );

			$wp_customize->add_control( 'publisher_options_' . $post_type->name . '_post_sidebar_display', array(
				'label'    			=> sprintf( __( '%s Sidebar', 'publisher' ), $post_type->label ),
				'section'  			=> 'publisher_section_' . $post_type->name . '_single',
				'settings' 			=> 'publisher_options_' . $post_type->name . '_post_sidebar_display',
				'type'     			=> 'select',
				'description'		=> sprintf( __( 'Set the default %s single sidebar layout', 'publisher' ), $post_type->label ),
				'choices'  			=> array(
					'show' => __( 'Show', 'publisher' ),
					'hide' => __( 'Hide', 'publisher' ),
				),
			) );

			// CPT Pagination
			$wp_customize->add_setting( 'publisher_options_' . $post_type->name . 'post_pagination', array(
				'default'      		=> 'prev-next',
				'type'         		=> 'option',
				'sanitize_callback' => 'publisher_sanitize_post_pagination',
			) );

			$wp_customize->add_control( 'publisher_options_' . $post_type->name . 'post_pagination', array(
				'settings'     		=> 'publisher_options_' . $post_type->name . 'post_pagination',
				'label'        		=> sprintf( __( '%s Pagination', 'publisher' ), $post_type->label ),
				'section'  			=> 'publisher_section_' . $post_type->name . '_single',
				'type'         		=> 'select',
				'description'  		=> sprintf( __( 'Select the %s single pagination style', 'publisher' ), $post_type->label ),
				'choices'      		=> array(
					'none' 	 	 => __( 'None', 'publisher' ),
					'prev-next'  => __( 'Prev / Next', 'publisher' ),
					'post-image' => __( 'Post Image', 'publisher' ),
					'next-up' 	 => __( 'Next Up', 'publisher' ),
				),
			) );

			// CPT Related Posts
			$wp_customize->add_setting( 'publisher_options_' . $post_type->name . '_related_posts', array(
				'type'				=> 'option',
				'sanitize_callback' => 'publisher_sanitize_text',
			) );

			$wp_customize->add_control( 'publisher_options_' . $post_type->name . '_related_posts', array(
				'label'        		=> sprintf( __( 'Related %s', 'publisher' ), $post_type->label ),
				'section'  			=> 'publisher_section_' . $post_type->name . '_single',
				'settings'     		=> 'publisher_options_' . $post_type->name . '_related_posts',
				'type'         		=> 'text',
				'description'  		=> sprintf( __( 'Text for the related %s section', 'publisher' ), $post_type->label ),
			) );

			// CPT Post Details
			$wp_customize->add_setting( 'publisher_options_' . $post_type->name . '_post_details', array(
				'capability'   		=> 'edit_theme_options',
				'sanitize_callback' => 'publisher_sanitize_post_details',
			) );

			$wp_customize->add_control( new Custom_Multi_Select_Control( $wp_customize, 'publisher_options_' . $post_type->name . '_post_details', array(
				'label'        		=> sprintf( __( '%s Details', 'publisher' ), $post_type->label ),
				'section'  			=> 'publisher_section_' . $post_type->name . '_single',
				'settings'     		=> 'publisher_options_' . $post_type->name . '_post_details',
				'type'     			=> 'multiple-select',
				'description'  		=> sprintf( __( 'Hide the following %s details', 'publisher' ), $post_type->label ),
				'choices'			=> get_post_details_select(),
				'extra'				=> 6
			) )	);


			/**
			/*	Custom Post Type Extras
			/*-------------------------------------------------*/

			$wp_customize->add_section( 'publisher_section_' . $post_type->name . '_options', array(
				'title'				=> __( 'Extras', 'publisher' ),
				'panel'				=> 'publisher_panel_' . $post_type->name
			) );

			// CPT Category Select
			$wp_customize->add_setting( 'publisher_options_' . $post_type->name . '_category_select', array(
				'default'      		=> '',
				'type'				=> 'option',
				'sanitize_callback' => 'publisher_sanitize_empty',
			) );

			$wp_customize->add_control( 'publisher_options_' . $post_type->name . '_category_select', array(
				'label'        		=> __( 'Category Select', 'publisher' ),
				'section'      		=> 'publisher_section_' . $post_type->name . '_options',
				'settings'     		=> 'publisher_options_' . $post_type->name . '_category_select',
				'type'     			=> 'select',
				'description'  		=> __( 'Select the term to replace the category', 'publisher' ),
				'choices'			=> get_cpt_terms_select( $post_type->name ),
			) );

			// CPT Tag Select
			$wp_customize->add_setting( 'publisher_options_' . $post_type->name . '_tag_select', array(
				'default'      		=> '',
				'type'				=> 'option',
				'sanitize_callback' => 'publisher_sanitize_empty',
			) );

			$wp_customize->add_control( 'publisher_options_' . $post_type->name . '_tag_select', array(
				'label'        		=> __( 'Tag Select', 'publisher' ),
				'section'      		=> 'publisher_section_' . $post_type->name . '_options',
				'settings'     		=> 'publisher_options_' . $post_type->name . '_tag_select',
				'type'     			=> 'select',
				'description'  		=> __( 'Select the term to replace the tag', 'publisher' ),
				'choices'			=> get_cpt_terms_select( $post_type->name ),
			) );

		}

	}




	/*-----------------------------------------------------------------------------------*/
	/*	Images
	/*-----------------------------------------------------------------------------------*/

	$wp_customize->add_panel( 'publisher_panel_theme_images', array(
		'title'				=> __( 'Images', 'publisher' ),
		'priority'			=> 5
	) );


	/**
	/*	Header Image
	/*-------------------------------------------------*/

	// Header Image Opacity
	$wp_customize->add_setting( 'publisher_options_header_image_opacity', array(
		'default'           => '1',
		'type'              => 'theme_mod',
		'sanitize_callback' => 'publisher_sanitize_decimal',
		'transport'         => 'postMessage'
	) );

	$wp_customize->add_control( 'publisher_options_header_image_opacity', array(
		'type'        		=> 'range',
		'section'     		=> 'header_image',
		'label'       		=> __( 'Header Image Opacity', 'publisher' ),
		'description' 		=> 'Change the opacity of your header image.',
		'priority'    		=> 10,
		'input_attrs' 		=> array(
			'min'   => 0,
			'max'   => 1,
			'step'  => .1,
			'style' => 'width: 100%',
		),
	) );


	/**
	/*	Featured Header
	/*-------------------------------------------------*/

	$wp_customize->add_section( 'publisher_section_featured_header', array(
	    'title'          	=> __( 'Featured Header', 'publisher' ),
	    'description'  		=> __( 'The default image for the featured header section.', 'publisher' ),
	    'panel'			 	=> 'publisher_panel_theme_images'
	) );

	// Default Image
	$wp_customize->add_setting( 'publisher_options_featured_header_default', array(
		'sanitize_callback' => 'publisher_sanitize_image'
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'publisher_options_featured_header_default', array(
		'label'     		=> __( 'Default Image', 'publisher' ),
		'section'       	=> 'publisher_section_featured_header',
		'settings'      	=> 'publisher_options_featured_header_default',
	) ) );


	/**
	/*	Footer Image
	/*-------------------------------------------------*/

	$wp_customize->add_section( 'publisher_section_footer_image', array(
		'title'				=> __( 'Footer Image', 'publisher' ),
		'panel'				=> 'publisher_panel_theme_images'
	) );

	// Footer Background Image
	$wp_customize->add_setting( 'publisher_options_footer_image', array(
		'sanitize_callback' => 'publisher_sanitize_image',
		'transport' 		=> 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'publisher_options_footer_image', array(
		'label'    			=> __( 'Footer Image', 'publisher' ),
		'section'  			=> 'publisher_section_footer_image',
		'settings' 			=> 'publisher_options_footer_image',
	) ) );

	// Footer Image Opacity
	$wp_customize->add_setting( 'publisher_options_footer_image_opacity', array(
		'default'           => '.1',
		'type'              => 'theme_mod',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'publisher_sanitize_decimal',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'publisher_options_footer_image_opacity', array(
		'type'        		=> 'range',
		'section'     		=> 'publisher_section_footer_image',
		'label'       		=> __( 'Footer Image Opacity', 'publisher' ),
		'input_attrs'		=> array(
			'min'   => 0,
			'max'   => 1,
			'step'  => .1,
			'style' => 'width: 100%',
		),
	) );




	/*-----------------------------------------------------------------------------------*/
	/*	Colors
	/*-----------------------------------------------------------------------------------*/

	$wp_customize->add_panel( 'publisher_panel_theme_colors', array(
		'title'				=> __( 'Colors', 'publisher' ),
		'priority'			=> 5
	) );


	/**
	/*	General
	/*-------------------------------------------------*/

	// Accent
	$wp_customize->add_setting( 'publisher_options_colors_content_accent', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_content_accent', array(
		'label'    			=> __( 'Accent', 'publisher' ),
		'section'  			=> 'colors',
		'settings' 			=> 'publisher_options_colors_content_accent',
		'description' 		=> __( 'Accent color for the theme', 'publisher' )
	) ) );


	/**
	/*	Navigation
	/*-------------------------------------------------*/

	$wp_customize->add_section( 'publisher_section_colors_navigation', array(
	    'title'          	=> __( 'Navigation', 'publisher' ),
	    'description' 		=> __( 'The following sets the colors of the default primary menu', 'publisher' ),
	    'panel'			 	=> 'publisher_panel_theme_colors'
	) );

	// Base Color
	$wp_customize->add_setting( 'publisher_options_colors_navigation_base', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_navigation_base', array(
		'label'    			=> __( 'Base Color', 'publisher' ),
		'description' 		=> __( 'Color for the menu background', 'publisher' ),
		'section'  			=> 'publisher_section_colors_navigation',
		'settings' 			=> 'publisher_options_colors_navigation_base',
	) ) );

	// Menu Item
	$wp_customize->add_setting( 'publisher_options_colors_navigation_menu_item', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_navigation_menu_item', array(
		'label'    			=> __( 'Menu Item', 'publisher' ),
		'description' 		=> __( 'Color for the first level menu items', 'publisher' ),
		'section'  			=> 'publisher_section_colors_navigation',
		'settings' 			=> 'publisher_options_colors_navigation_menu_item',
	) ) );

	// Menu Item Active
	$wp_customize->add_setting( 'publisher_options_colors_navigation_menu_item_active', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_navigation_menu_item_active', array(
		'label'    			=> __( 'Menu Item Active', 'publisher' ),
		'description' 		=> __( 'Color for the active menu items', 'publisher' ),
		'section'  			=> 'publisher_section_colors_navigation',
		'settings' 			=> 'publisher_options_colors_navigation_menu_item_active',
	) ) );

	// Menu Description
	$wp_customize->add_setting( 'publisher_options_colors_navigation_menu_desc', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_navigation_menu_desc', array(
		'label'    			=> __( 'Menu Description', 'publisher' ),
		'description' 		=> __( 'Color for the first level menu descriptions', 'publisher' ),
		'section'  			=> 'publisher_section_colors_navigation',
		'settings' 			=> 'publisher_options_colors_navigation_menu_desc',
	) ) );

	// Menu Description
	$wp_customize->add_setting( 'publisher_options_colors_navigation_menu_desc_active', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_navigation_menu_desc_active', array(
		'label'    			=> __( 'Menu Description Active', 'publisher' ),
		'description' 		=> __( 'Color for the active menu descriptions', 'publisher' ),
		'section'  			=> 'publisher_section_colors_navigation',
		'settings' 			=> 'publisher_options_colors_navigation_menu_desc_active',
	) ) );

	// Submenu Base Color
	$wp_customize->add_setting( 'publisher_options_colors_navigation_submenu_base', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_navigation_submenu_base', array(
		'label'    			=> __( 'Submenu Base Color', 'publisher' ),
		'description' 		=> __( 'Color for the submenu background', 'publisher' ),
		'section'  			=> 'publisher_section_colors_navigation',
		'settings' 			=> 'publisher_options_colors_navigation_submenu_base',
	) ) );


	/**
	/*	Header
	/*-------------------------------------------------*/

	$wp_customize->add_section( 'publisher_section_colors_header', array(
	    'title'          	=> __( 'Header', 'publisher' ),
	    'panel'			 	=> 'publisher_panel_theme_colors'
	) );

	// Base Color
	$wp_customize->add_setting( 'publisher_options_colors_header_base', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_header_base', array(
		'label'    			=> __( 'Base Color', 'publisher' ),
		'description' 		=> __( 'Color for the header background', 'publisher' ),
		'section'  			=> 'publisher_section_colors_header',
		'settings' 			=> 'publisher_options_colors_header_base',
	) ) );

	// Site Title
	$wp_customize->add_setting( 'publisher_options_colors_site_title', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_site_title', array(
		'label'    			=> __( 'Site Title', 'publisher' ),
		'description' 		=> __( 'Color for the site title', 'publisher' ),
		'section'  			=> 'publisher_section_colors_header',
		'settings' 			=> 'publisher_options_colors_site_title',
	) ) );

	// Site Description
	$wp_customize->add_setting( 'publisher_options_colors_site_description', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_site_description', array(
		'label'    			=> __( 'Site Description', 'publisher' ),
		'description' 		=> __( 'Color for the site description', 'publisher' ),
		'section'  			=> 'publisher_section_colors_header',
		'settings' 			=> 'publisher_options_colors_site_description',
	) ) );

	// Headband
	$wp_customize->add_setting( 'publisher_options_colors_header_headband', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_header_headband', array(
		'label'    			=> __( 'Headband', 'publisher' ),
		'description' 		=> __( 'Color for the headband', 'publisher' ),
		'section'  			=> 'publisher_section_colors_header',
		'settings' 			=> 'publisher_options_colors_header_headband',
	) ) );

	// Headband Title
	$wp_customize->add_setting( 'publisher_options_colors_header_headband_title', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_header_headband_title', array(
		'label'    			=> __( 'Headband Title', 'publisher' ),
		'description' 		=> __( 'Color for the headband titles', 'publisher' ),
		'section'  			=> 'publisher_section_colors_header',
		'settings' 			=> 'publisher_options_colors_header_headband_title',
	) ) );

	// Headband Link
	$wp_customize->add_setting( 'publisher_options_colors_header_headband_link', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_header_headband_link', array(
		'label'    			=> __( 'Headband Link', 'publisher' ),
		'description' 		=> __( 'Color for the headband links', 'publisher' ),
		'section'  			=> 'publisher_section_colors_header',
		'settings' 			=> 'publisher_options_colors_header_headband_link',
	) ) );


	/**
	/*	Featured Header
	/*-------------------------------------------------*/

	$wp_customize->add_section( 'publisher_section_colors_featured_header', array(
	    'title'          	=> __( 'Featured Header', 'publisher' ),
	    'panel'			 	=> 'publisher_panel_theme_colors'
	) );

	// Base Color
	$wp_customize->add_setting( 'publisher_options_colors_featured_header_base', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_featured_header_base', array(
		'label'    			=> __( 'Base Color', 'publisher' ),
		'description' 		=> __( 'Color for the featured header base', 'publisher' ),
		'section'  			=> 'publisher_section_colors_featured_header',
		'settings' 			=> 'publisher_options_colors_featured_header_base',
	) ) );

	// Titles Color
	$wp_customize->add_setting( 'publisher_options_colors_featured_header_titles', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_featured_header_titles', array(
		'label'    			=> __( 'Titles Color', 'publisher' ),
		'description' 		=> __( 'Color for the featured header titles without an image', 'publisher' ),
		'section'  			=> 'publisher_section_colors_featured_header',
		'settings' 			=> 'publisher_options_colors_featured_header_titles',
	) ) );

	// Text Color
	$wp_customize->add_setting( 'publisher_options_colors_featured_header_text', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_featured_header_text', array(
		'label'    			=> __( 'Text Color', 'publisher' ),
		'description' 		=> __( 'Color for the featured header text without an image', 'publisher' ),
		'section'  			=> 'publisher_section_colors_featured_header',
		'settings' 			=> 'publisher_options_colors_featured_header_text',
	) ) );

	// Button Base
	$wp_customize->add_setting( 'publisher_options_colors_featured_header_button_base', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_featured_header_button_base', array(
		'label'    			=> __( 'Button Base', 'publisher' ),
		'description' 		=> __( 'Color for the buttons base', 'publisher' ),
		'section'  			=> 'publisher_section_colors_featured_header',
		'settings' 			=> 'publisher_options_colors_featured_header_button_base',
	) ) );

	// Button Text
	$wp_customize->add_setting( 'publisher_options_colors_featured_header_button_text', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_featured_header_button_text', array(
		'label'    			=> __( 'Button Text', 'publisher' ),
		'description' 		=> __( 'Color for the buttons text', 'publisher' ),
		'section'  			=> 'publisher_section_colors_featured_header',
		'settings' 			=> 'publisher_options_colors_featured_header_button_text',
	) ) );

	// Button Base Active
	$wp_customize->add_setting( 'publisher_options_colors_featured_header_button_base_active', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_featured_header_button_base_active', array(
		'label'    			=> __( 'Button Base Active', 'publisher' ),
		'description' 		=> __( 'Color for the button base when active or hovered', 'publisher' ),
		'section'  			=> 'publisher_section_colors_featured_header',
		'settings' 			=> 'publisher_options_colors_featured_header_button_base_active',
	) ) );

	// Button Text Active
	$wp_customize->add_setting( 'publisher_options_colors_featured_header_button_text_active', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_featured_header_button_text_active', array(
		'label'    			=> __( 'Button Text Active', 'publisher' ),
		'description' 		=> __( 'Color for the button text when active or hovered', 'publisher' ),
		'section'  			=> 'publisher_section_colors_featured_header',
		'settings' 			=> 'publisher_options_colors_featured_header_button_text_active',
	) ) );


	/**
	/*	Content
	/*-------------------------------------------------*/

	$wp_customize->add_section( 'publisher_section_colors_content', array(
	    'title'          	=> __( 'Content', 'publisher' ),
	    'panel'			 	=> 'publisher_panel_theme_colors'
	) );

	// Primary Headings
	$wp_customize->add_setting( 'publisher_options_colors_content_headings', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_content_headings', array(
		'label'    			=> __( 'Primary Headings', 'publisher' ),
		'section'  			=> 'publisher_section_colors_content',
		'settings' 			=> 'publisher_options_colors_content_headings',
		'description' 		=> __( 'Color for the main headings, blockquotes', 'publisher' )
	) ) );

	// Primary Text
	$wp_customize->add_setting( 'publisher_options_colors_content_text', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_content_text', array(
		'label'    			=> __( 'Primary Text', 'publisher' ),
		'section'  			=> 'publisher_section_colors_content',
		'settings' 			=> 'publisher_options_colors_content_text',
		'description' 		=> __( 'Color for the main text', 'publisher' )
	) ) );

	// Secondary Headings
	$wp_customize->add_setting( 'publisher_options_colors_content_secondary_headings', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_content_secondary_headings', array(
		'label'    			=> __( 'Secondary Headings', 'publisher' ),
		'section'  			=> 'publisher_section_colors_content',
		'settings' 			=> 'publisher_options_colors_content_secondary_headings',
		'description' 		=> __( 'Color for the secondary headings', 'publisher' )
	) ) );

	// Secondary Text
	$wp_customize->add_setting( 'publisher_options_colors_content_secondary_text', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_content_secondary_text', array(
		'label'    			=> __( 'Secondary Text', 'publisher' ),
		'section'  			=> 'publisher_section_colors_content',
		'settings' 			=> 'publisher_options_colors_content_secondary_text',
		'description' 		=> __( 'Color for the secondary text', 'publisher' )
	) ) );

	// Content Titles
	$wp_customize->add_setting( 'publisher_options_colors_content_titles', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_content_titles', array(
		'label'    			=> __( 'Content Titles', 'publisher' ),
		'section'  			=> 'publisher_section_colors_content',
		'settings' 			=> 'publisher_options_colors_content_titles',
		'description' 		=> __( 'Color for the content titles', 'publisher' )
	) ) );

	// Content Box Base
	$wp_customize->add_setting( 'publisher_options_colors_content_box_base', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_content_box_base', array(
		'label'    			=> __( 'Content Box Base', 'publisher' ),
		'description' 		=> __( 'Color for the content box base', 'publisher' ),
		'section'  			=> 'publisher_section_colors_content',
		'settings' 			=> 'publisher_options_colors_content_box_base',
	) ) );


	/**
	/*	Content Elements
	/*-------------------------------------------------*/

	$wp_customize->add_section( 'publisher_section_colors_content_elements', array(
	    'title'          	=> __( 'Content Elements', 'publisher' ),
	    'panel'			 	=> 'publisher_panel_theme_colors'
	) );

	// Category Base
	$wp_customize->add_setting( 'publisher_options_colors_content_category_base', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_content_category_base', array(
		'label'    			=> __( 'Category Base', 'publisher' ),
		'description' 		=> __( 'Color for the category base', 'publisher' ),
		'section'  			=> 'publisher_section_colors_content_elements',
		'settings' 			=> 'publisher_options_colors_content_category_base',
	) ) );

	// Category Text
	$wp_customize->add_setting( 'publisher_options_colors_content_category_text', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_content_category_text', array(
		'label'    			=> __( 'Category Text', 'publisher' ),
		'description' 		=> __( 'Color for the category text', 'publisher' ),
		'section'  			=> 'publisher_section_colors_content_elements',
		'settings' 			=> 'publisher_options_colors_content_category_text',
	) ) );

	// Icon Base
	$wp_customize->add_setting( 'publisher_options_colors_content_icon_base', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_content_icon_base', array(
		'label'    			=> __( 'Icon Base', 'publisher' ),
		'description' 		=> __( 'Color for the icon base, gallery icon, shop status', 'publisher' ),
		'section'  			=> 'publisher_section_colors_content_elements',
		'settings' 			=> 'publisher_options_colors_content_icon_base',
	) ) );

	// Button Base
	$wp_customize->add_setting( 'publisher_options_colors_content_button_base', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_content_button_base', array(
		'label'    			=> __( 'Button Base', 'publisher' ),
		'description' 		=> __( 'Color for the buttons base, read more, submit', 'publisher' ),
		'section'  			=> 'publisher_section_colors_content_elements',
		'settings' 			=> 'publisher_options_colors_content_button_base',
	) ) );

	// Button Text
	$wp_customize->add_setting( 'publisher_options_colors_content_button_base_text', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_content_button_base_text', array(
		'label'    			=> __( 'Button Base Text', 'publisher' ),
		'description' 		=> __( 'Color for the buttons text', 'publisher' ),
		'section'  			=> 'publisher_section_colors_content_elements',
		'settings' 			=> 'publisher_options_colors_content_button_base_text',
	) ) );

	// Button Base Active
	$wp_customize->add_setting( 'publisher_options_colors_content_button_base_active', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_content_button_base_active', array(
		'label'    			=> __( 'Button Base Active', 'publisher' ),
		'description' 		=> __( 'Color for the button base when active or hovered', 'publisher' ),
		'section'  			=> 'publisher_section_colors_content_elements',
		'settings' 			=> 'publisher_options_colors_content_button_base_active',
	) ) );

	// Button Text Active
	$wp_customize->add_setting( 'publisher_options_colors_content_button_text_active', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_content_button_text_active', array(
		'label'    			=> __( 'Button Text Active', 'publisher' ),
		'description' 		=> __( 'Color for the button text when active or hovered', 'publisher' ),
		'section'  			=> 'publisher_section_colors_content_elements',
		'settings' 			=> 'publisher_options_colors_content_button_text_active',
	) ) );


	/**
	/*	Content Secondary
	/*-------------------------------------------------*/

	$wp_customize->add_section( 'publisher_section_colors_secondary', array(
	    'title'          	=> __( 'Content Secondary', 'publisher' ),
	    'description' 		=> __( 'The following mainly sets the colors of the elements in-between content boxes', 'publisher' ),
	    'panel'			 	=> 'publisher_panel_theme_colors'
	) );

	// Section Titles
	$wp_customize->add_setting( 'publisher_options_colors_secondary_titles', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_secondary_titles', array(
		'label'    			=> __( 'Section Titles', 'publisher' ),
		'description' 		=> __( 'Color for the titles dividing sections', 'publisher' ),
		'section'  			=> 'publisher_section_colors_secondary',
		'settings' 			=> 'publisher_options_colors_secondary_titles',
	) ) );

	// Section Text
	$wp_customize->add_setting( 'publisher_options_colors_secondary_text', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_secondary_text', array(
		'label'    			=> __( 'Section Text', 'publisher' ),
		'description' 		=> __( 'Color for the text between sections', 'publisher' ),
		'section'  			=> 'publisher_section_colors_secondary',
		'settings' 			=> 'publisher_options_colors_secondary_text',
	) ) );

	// Button Base
	$wp_customize->add_setting( 'publisher_options_colors_secondary_button_base', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_secondary_button_base', array(
		'label'    			=> __( 'Button Base', 'publisher' ),
		'description' 		=> __( 'Color for the secondary button base, navigation', 'publisher' ),
		'section'  			=> 'publisher_section_colors_secondary',
		'settings' 			=> 'publisher_options_colors_secondary_button_base',
	) ) );

	// Button Text
	$wp_customize->add_setting( 'publisher_options_colors_secondary_button_text', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_secondary_button_text', array(
		'label'    			=> __( 'Button Text', 'publisher' ),
		'description' 		=> __( 'Color for the secondary button text, navigation text', 'publisher' ),
		'section'  			=> 'publisher_section_colors_secondary',
		'settings' 			=> 'publisher_options_colors_secondary_button_text',
	) ) );

	// Button Base Active
	$wp_customize->add_setting( 'publisher_options_colors_secondary_button_base_active', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_secondary_button_base_active', array(
		'label'    			=> __( 'Button Base Active', 'publisher' ),
		'description' 		=> __( 'Color for the button base when active or hovered', 'publisher' ),
		'section'  			=> 'publisher_section_colors_secondary',
		'settings' 			=> 'publisher_options_colors_secondary_button_base_active',
	) ) );

	// Button Text Active
	$wp_customize->add_setting( 'publisher_options_colors_secondary_button_text_active', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_secondary_button_text_active', array(
		'label'    			=> __( 'Button Text Active', 'publisher' ),
		'description' 		=> __( 'Color for the button text when active or hovered', 'publisher' ),
		'section'  			=> 'publisher_section_colors_secondary',
		'settings' 			=> 'publisher_options_colors_secondary_button_text_active',
	) ) );


	/**
	/*	Sidebar
	/*-------------------------------------------------*/

	$wp_customize->add_section( 'publisher_section_colors_sidebar', array(
	    'title'          	=> __( 'Sidebar', 'publisher' ),
	    'panel'			 	=> 'publisher_panel_theme_colors'
	) );

	// Sidebar Header Base
	$wp_customize->add_setting( 'publisher_options_colors_sidebar_header_base', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_sidebar_header_base', array(
		'label'    			=> __( 'Header Base', 'publisher' ),
		'description' 		=> __( 'Color for the sidebar header base, article footer', 'publisher' ),
		'section'  			=> 'publisher_section_colors_sidebar',
		'settings' 			=> 'publisher_options_colors_sidebar_header_base',
	) ) );

	// Sidebar Header Titles
	$wp_customize->add_setting( 'publisher_options_colors_sidebar_header_titles', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_sidebar_header_titles', array(
		'label'    			=> __( 'Header Titles', 'publisher' ),
		'description' 		=> __( 'Color for the sidebar header titles', 'publisher' ),
		'section'  			=> 'publisher_section_colors_sidebar',
		'settings' 			=> 'publisher_options_colors_sidebar_header_titles',
	) ) );

	// Sidebar Header Text
	$wp_customize->add_setting( 'publisher_options_colors_sidebar_header_text', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_sidebar_header_text', array(
		'label'    			=> __( 'Header Text', 'publisher' ),
		'description' 		=> __( 'Color for the sidebar header text, article footer text', 'publisher' ),
		'section'  			=> 'publisher_section_colors_sidebar',
		'settings' 			=> 'publisher_options_colors_sidebar_header_text',
	) ) );

	// Sidebar Accent
	$wp_customize->add_setting( 'publisher_options_colors_sidebar_accent', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_sidebar_accent', array(
		'label'    			=> __( 'Sidebar Accent', 'publisher' ),
		'description' 		=> __( 'Accent color for the sidebar', 'publisher' ),
		'section'  			=> 'publisher_section_colors_sidebar',
		'settings' 			=> 'publisher_options_colors_sidebar_accent',
	) ) );

	// Sidebar Text
	$wp_customize->add_setting( 'publisher_options_colors_sidebar_text', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_sidebar_text', array(
		'label'    			=> __( 'Sidebar Text', 'publisher' ),
		'description' 		=> __( 'Color for the sidebar text', 'publisher' ),
		'section'  			=> 'publisher_section_colors_sidebar',
		'settings' 			=> 'publisher_options_colors_sidebar_text',
	) ) );

	// Sidebar Box Base
	$wp_customize->add_setting( 'publisher_options_colors_sidebar_box_base', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_sidebar_box_base', array(
		'label'    			=> __( 'Sidebar Box Base', 'publisher' ),
		'description' 		=> __( 'Color for the sidebar box background', 'publisher' ),
		'section'  			=> 'publisher_section_colors_sidebar',
		'settings' 			=> 'publisher_options_colors_sidebar_box_base',
	) ) );

	// Button Base
	$wp_customize->add_setting( 'publisher_options_colors_sidebar_button_base', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_sidebar_button_base', array(
		'label'    			=> __( 'Button Base', 'publisher' ),
		'description' 		=> __( 'Color for the buttons base, tags base', 'publisher' ),
		'section'  			=> 'publisher_section_colors_sidebar',
		'settings' 			=> 'publisher_options_colors_sidebar_button_base',
	) ) );

	// Button Text
	$wp_customize->add_setting( 'publisher_options_colors_sidebar_button_text', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_sidebar_button_text', array(
		'label'    			=> __( 'Button Base Text', 'publisher' ),
		'description' 		=> __( 'Color for the buttons text, tags text', 'publisher' ),
		'section'  			=> 'publisher_section_colors_sidebar',
		'settings' 			=> 'publisher_options_colors_sidebar_button_text',
	) ) );

	// Button Base Active
	$wp_customize->add_setting( 'publisher_options_colors_sidebar_button_base_active', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_sidebar_button_base_active', array(
		'label'    			=> __( 'Button Base Active', 'publisher' ),
		'description' 		=> __( 'Color for the button base when active or hovered', 'publisher' ),
		'section'  			=> 'publisher_section_colors_sidebar',
		'settings' 			=> 'publisher_options_colors_sidebar_button_base_active',
	) ) );

	// Button Text Active
	$wp_customize->add_setting( 'publisher_options_colors_sidebar_button_text_active', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_sidebar_button_text_active', array(
		'label'    			=> __( 'Button Text Active', 'publisher' ),
		'description' 		=> __( 'Color for the button text when active or hovered', 'publisher' ),
		'section'  			=> 'publisher_section_colors_sidebar',
		'settings' 			=> 'publisher_options_colors_sidebar_button_text_active',
	) ) );


	/**
	/*	Footer
	/*-------------------------------------------------*/

	$wp_customize->add_section( 'publisher_section_colors_footer', array(
	    'title'          	=> __( 'Footer', 'publisher' ),
	    'panel'			 	=> 'publisher_panel_theme_colors'
	) );

	// Accent
	$wp_customize->add_setting( 'publisher_options_colors_footer_accent', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_footer_accent', array(
		'label'    			=> __( 'Footer Accent', 'publisher' ),
		'description' 		=> __( 'Accent color for the footer', 'publisher' ),
		'section'  			=> 'publisher_section_colors_footer',
		'settings' 			=> 'publisher_options_colors_footer_accent',
	) ) );

	// Base Color
	$wp_customize->add_setting( 'publisher_options_colors_footer_base', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_footer_base', array(
		'label'    			=> __( 'Footer Base Color', 'publisher' ),
		'description' 		=> __( 'Color for the footer background, table rows, footnote', 'publisher' ),
		'section'  			=> 'publisher_section_colors_footer',
		'settings' 			=> 'publisher_options_colors_footer_base',
	) ) );

	// Footer Titles
	$wp_customize->add_setting( 'publisher_options_colors_footer_titles', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_footer_titles', array(
		'label'    			=> __( 'Footer Titles', 'publisher' ),
		'description' 		=> __( 'Color for the footer titles', 'publisher' ),
		'section'  			=> 'publisher_section_colors_footer',
		'settings' 			=> 'publisher_options_colors_footer_titles',
	) ) );

	// Footer Text
	$wp_customize->add_setting( 'publisher_options_colors_footer_text', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_footer_text', array(
		'label'    			=> __( 'Footer Text', 'publisher' ),
		'description' 		=> __( 'Color for the footer text', 'publisher' ),
		'section'  			=> 'publisher_section_colors_footer',
		'settings' 			=> 'publisher_options_colors_footer_text',
	) ) );

	// Button Base
	$wp_customize->add_setting( 'publisher_options_colors_footer_button_base', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_footer_button_base', array(
		'label'    			=> __( 'Button Base', 'publisher' ),
		'description' 		=> __( 'Color for the footer buttons, tags', 'publisher' ),
		'section'  			=> 'publisher_section_colors_footer',
		'settings' 			=> 'publisher_options_colors_footer_button_base',
	) ) );

	// Button Text
	$wp_customize->add_setting( 'publisher_options_colors_footer_button_text', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_footer_button_text', array(
		'label'    			=> __( 'Button Text', 'publisher' ),
		'description' 		=> __( 'Color for the footer button text, tag text', 'publisher' ),
		'section'  			=> 'publisher_section_colors_footer',
		'settings' 			=> 'publisher_options_colors_footer_button_text',
	) ) );

	// Button Base Active
	$wp_customize->add_setting( 'publisher_options_colors_footer_button_base_active', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_footer_button_base_active', array(
		'label'    			=> __( 'Button Base Active', 'publisher' ),
		'description' 		=> __( 'Color for the button base when active or hovered', 'publisher' ),
		'section'  			=> 'publisher_section_colors_footer',
		'settings' 			=> 'publisher_options_colors_footer_button_base_active',
	) ) );

	// Button Text Active
	$wp_customize->add_setting( 'publisher_options_colors_footer_button_text_active', array(
		'sanitize_callback' => 'publisher_sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'publisher_options_colors_footer_button_text_active', array(
		'label'    			=> __( 'Button Text Active', 'publisher' ),
		'description' 		=> __( 'Color for the button text when active or hovered', 'publisher' ),
		'section'  			=> 'publisher_section_colors_footer',
		'settings' 			=> 'publisher_options_colors_footer_button_text_active',
	) ) );

}
add_action( 'customize_register', 'publisher_customize_register' );


/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously
 */
function publisher_customize_preview_js() {

	/**
	 * Theme Customizer Preview
	 */
	wp_enqueue_script( 'publisher-customizer-js', get_template_directory_uri() . '/includes/js/publisher-customizer.js', array( 'customize-preview' ), '1.2', true );

}
add_action( 'customize_preview_init', 'publisher_customize_preview_js' );




/*-----------------------------------------------------------------------------------*/
/*	Customizer Tags
/*-----------------------------------------------------------------------------------*/

/**
 * Get all the post types
 */
function get_post_type_select( $builtin = true ) {

	$post_types = get_post_types( array( 'public' => true, '_builtin' => $builtin ), 'objects' );
	$results = array( '' => '---' );

	if ( $post_types ) {
		foreach ( $post_types as $post_type ) {
			if ( in_array( $post_type->name, publisher_remove_from_cpt_loop() ) ) continue;
			$results[$post_type->name] = $post_type->label;
		}
	}

	return $results;

}


/**
 * Get all the taxonomy types
 */
function get_taxonomy_select() {

	$taxonomies = get_taxonomies( array( 'public'   => true, '_builtin' => false ), 'objects' );
	$results = array( '' => '---' );

	if ( $taxonomies ) {
		foreach ( $taxonomies as $taxonomy ) {
			if ( in_array( $taxonomy->name, publisher_remove_from_taxonomy_loop() ) ) continue;
			$results[$taxonomy->name] = $taxonomy->label;
		}
	}

	return $results;

}


/**
 * Get all the terms
 */
function get_terms_select( $taxonomy = '', $select = false ) {

	if ( ! empty( $taxonomy ) ) {
		$taxonomies = is_array( $taxonomy ) ? $taxonomy : array( $taxonomy );
	} else {
		$taxonomies = get_taxonomies( array( 'public' => true ) );
	}

	$results = array( '' => '---' );

	if ( $taxonomies ) {
		foreach ( $taxonomies as $taxonomy ) {
			$tax_terms = get_terms( $taxonomy );

			foreach ( $tax_terms as $tax_term ) {
				$value = $select ? $tax_term->term_id . '_' . $tax_term->taxonomy : $tax_term->term_id;
				$results[$value] = $tax_term->taxonomy . ': ' . $tax_term->name;
			}
		}
	}

	return $results;

}


/**
 * Get all the cpt terms
 */
function get_cpt_terms_select( $post_type = '' ) {

	$taxonomies = get_object_taxonomies( $post_type, 'objects' );
	$results = array( '' => '---' );

	if ( $taxonomies ) {
		foreach ( $taxonomies as $taxonomy ) {
			$results[$taxonomy->name] = $taxonomy->label;
		}
	}

	return $results;

}


/**
 * Get page details
 */
function get_page_details_select() {
	$details = array(
		''			=> __( '---', 'publisher' ),
		'read-time'	=> __( 'Read Time', 'publisher' ),
		'likes'		=> __( 'Likes', 'publisher' ),
		'shares'	=> __( 'Shares', 'publisher' ),
	);

	return apply_filters( 'publisher_filter_get_page_details_select', $details );
}


/**
 * Get post details
 */
function get_post_details_select() {
	$details = array(
		''	  				=> __( '---', 'publisher' ),
		'category'	  		=> __( 'Category', 'publisher' ),
		'author'	  		=> __( 'Author', 'publisher' ),
		'date'				=> __( 'Date', 'publisher' ),
		'comments'			=> __( 'Comments', 'publisher' ),
		'tag-list'	  		=> __( 'Tag List', 'publisher' ),
		'author-card' 		=> __( 'Author Card', 'publisher' ),
		'read-time'	  		=> __( 'Read Time', 'publisher' ),
		'likes'		  		=> __( 'Likes', 'publisher' ),
		'shares'	  		=> __( 'Shares', 'publisher' ),
		'related-posts'		=> __( 'Related Posts', 'publisher' ),
	);

	return apply_filters( 'publisher_filter_get_post_details_select', $details );
}


/**
 * Active Callback : Post Layout
 */
function publisher_options_post_layout( $control ) {
	$option = $control->manager->get_setting( 'publisher_options_posts_layout' );
	return in_array( $option->value(), array( 'grid', 'cover' ) );
}


/**
 * Remove from custom post loop
 */
function publisher_remove_from_cpt_loop() {
	$remove_post_types = array( 'ditty_news_ticker', 'forum', 'topic', 'reply', 'product', 'ml-slider' );
	return apply_filters( 'publisher_filter_remove_from_cpt_loop', $remove_post_types );
}


/**
 * Remove from custom taxonomy loop
 */
function publisher_remove_from_taxonomy_loop() {
	$remove_tax_types = array( 'product_cat', 'product_tag' );
	return apply_filters( 'publisher_filter_remove_from_taxonomy_loop', $remove_tax_types );
}




/*-----------------------------------------------------------------------------------*/
/*	Sanitization
/*-----------------------------------------------------------------------------------*/

/**
 * Sanitize text
 */
function publisher_sanitize_text( $input ) {
	return wp_kses_post( force_balance_tags( $input ) );
}


/**
 * Sanitize checkbox
 */
function publisher_sanitize_checkbox( $input ) {
	if ( $input == 1 ) {
		return 1;
	} else {
		return '';
	}
}


/**
 * Sanitize integer
 */
function publisher_sanitize_integer( $input ) {
	if ( is_numeric( $input ) ) {
		return intval( $input );
	}
}


/**
 * Sanitize decimal
 */
function publisher_sanitize_decimal( $input ) {
	filter_var( $input, FILTER_FLAG_ALLOW_FRACTION );
	return ( $input );
}


/**
 * Sanitize image
 */
function publisher_sanitize_image( $value ) {
	if ( '' == $value ) return '';

	return $value;
}


/**
 * Sanitize color
 */
function publisher_sanitize_hex_color( $color ) {
	if ( empty( $color ) ) return '';

	// 3 or 6 hex digits, or the empty string.
    if ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) )
    	return $color;

    return null;
}


/**
 * Sanitize empty
 */
function publisher_sanitize_empty( $value ) {
	if ( '' == $value ) return '';

	return $value;
}


/**
 * Sanitize show/hide
 */
function publisher_sanitize_show_hide( $value ) {
	if ( ! in_array( $value, array( 'show', 'hide' ) ) )
		$value = 'show';

	return $value;
}


/**
 * Sanitize time format
 */
function publisher_sanitize_time_format( $value ) {
    if ( ! in_array( $value, array( 'date', 'time' ) ) )
    	$value = 'date';

    return $value;
}


/**
 * Sanitize posts pagination
 */
function publisher_sanitize_posts_pagination( $value ) {
	if ( ! in_array( $value, array( 'prev-next', 'numeric' ) ) )
		$value = 'prev-next';

	return $value;
}


/**
 * Sanitize link pages
 */
function publisher_sanitize_link_pages( $value ) {
	if ( ! in_array( $value, array( 'next_total_pages', 'next_and_number' ) ) )
		$value = 'next_total_pages';

	return $value;
}


/**
 * Sanitize page layout
 */
function publisher_sanitize_page_layout( $value ) {
	if ( ! in_array( $value, array( 'standard', 'cover', 'wide', 'banner' ) ) )
		$value = 'wide';

	return $value;
}


/**
 * Sanitize page details
 */
function publisher_sanitize_page_details( $value ) {
	if ( is_array( $value ) ) {
		$details = array();
		foreach( $value as $detail ) {
			if ( in_array( $detail, array_filter( array_keys( get_page_details_select() ) ) ) )
				$details[] = $detail;
		}
		return $details;
	} else {
		$value = array();
	}

	return $value;
}


/**
 * Sanitize posts layout
 */
function publisher_sanitize_posts_layout( $value ) {
	if ( ! in_array( $value, array( 'standard', 'list', 'grid' ) ) )
		$value = 'standard';

	return $value;
}


/**
 * Sanitize posts show content
 */
function publisher_sanitize_posts_show_content( $value ) {
	if ( ! in_array( $value, array( 'none', 'excerpt', 'full' ) ) )
		$value = 'excerpt';

	return $value;
}


/**
 * Sanitize posts layout
 */
function publisher_sanitize_post_layout( $value ) {
	if ( ! in_array( $value, array( 'standard', 'cover', 'wide', 'banner' ) ) )
		$value = 'standard';

	return $value;
}


/**
 * Sanitize post pagination
 */
function publisher_sanitize_post_pagination( $value ) {
	if ( ! in_array( $value, array( 'none', 'prev-next', 'post-image', 'next-up' ) ) )
		$value = 'prev-next';

	return $value;
}


/**
 * Sanitize post details
 */
function publisher_sanitize_post_details( $value ) {
	if ( is_array( $value ) ) {
		$details = array();
		foreach( $value as $detail ) {
			if ( in_array( $detail, array_filter( array_keys( get_post_details_select() ) ) ) )
				$details[] = $detail;
		}
		return $details;
	} else {
		$value = array();
	}

	return $value;
}


/**
 * Sanitize comment time format
 */
function publisher_sanitize_comment_time_format( $value ) {
	if ( ! in_array( $value, array( 'date', 'time' ) ) )
		$value = 'time';

	return $value;
}


/**
 * Sanitize comment pagination
 */
function publisher_sanitize_comment_pagination( $value ) {
	if ( ! in_array( $value, array( 'prev-next', 'numeric' ) ) )
		$value = 'numeric';

	return $value;
}


/**
 * Sanitize sidebar alignment
 */
function publisher_sanitize_sidebar_alignment( $value ) {
	if ( ! in_array( $value, array( 'left', 'right' ) ) )
		$value = 'right';

	return $value;
}


/**
 * Sanitize sidebar width
 */
function publisher_sanitize_sidebar_width( $value ) {
	if ( ! in_array( $value, array( 'wide', 'narrow' ) ) )
		$value = 'wide';

	return $value;
}


/**
 * Sanitize featured terms
 */
function publisher_sanitize_featured_terms( $value ) {

	if ( is_array( $value ) ) {

		$taxonomies = get_taxonomies( array( 'public' => true ) );

		if ( $taxonomies ) {
			$results = array();
			foreach ( $taxonomies as $taxonomy ) {
				$tax_terms = get_terms( $taxonomy );

				foreach ( $tax_terms as $tax_term ) {
					$results[] = $tax_term->term_id . '_' . $tax_term->taxonomy;
				}
			}

			$terms = array();
			foreach( $value as $term ) {
				if ( in_array( $term, $results ) )
					$terms[] = $term;
			}

			return $terms;
		}

	} else {
		return '';
	}

	return $value;

}


/**
 * Sanitize hide terms
 */
function publisher_sanitize_hide_terms( $value ) {

	if ( is_array( $value ) ) {

		$taxonomies = get_taxonomies( array( 'public' => true ) );

		if ( $taxonomies ) {
			$results = array();
			foreach ( $taxonomies as $taxonomy ) {
				$tax_terms = get_terms( $taxonomy );

				foreach ( $tax_terms as $tax_term ) {
					$results[] = $tax_term->term_id;
				}
			}

			$terms = array();
			foreach( $value as $term ) {
				if ( in_array( $term, $results ) )
					$terms[] = $term;
			}

			return $terms;
		}

	} else {
		return '';
	}

	return $value;

}


/**
 * Sanitize taxonomy image color
 */
function publisher_sanitize_taxonomy_image_color( $value ) {

	if ( is_array( $value ) ) {

		$taxonomies = get_taxonomies( array( 'public'   => true, '_builtin' => false ), 'objects' );

		if ( $taxonomies ) {
			$results = array();
			foreach ( $taxonomies as $taxonomy ) {
				if ( in_array( $taxonomy->name, publisher_remove_from_taxonomy_loop() ) ) continue;
				$results[] = $taxonomy->name;
			}

			$terms = array();
			foreach( $value as $term ) {
				if ( in_array( $term, $results ) )
					$terms[] = $term;
			}

			return $terms;
		}

	} else {
		return '';
	}

	return $value;

}


/**
 * Sanitize cpt options
 */
function publisher_sanitize_cpt_options( $value ) {

	if ( is_array( $value ) ) {

		$post_types = get_post_types( array( 'public' => true, '_builtin' => false ), 'objects' );

		if ( $post_types ) {
			$results = array();
			foreach ( $post_types as $post_type ) {
				if ( in_array( $post_type->name, publisher_remove_from_cpt_loop() ) ) continue;
				$results[] = $post_type->name;
			}

			$terms = array();
			foreach( $value as $term ) {
				if ( in_array( $term, $results ) )
					$terms[] = $term;
			}

			return $terms;
		}

	} else {
		return '';
	}

	return $value;

}


/**
 * Sanitize shop content
 */
function publisher_sanitize_products_shop_content( $value ) {
    if ( ! in_array( $value, array( 'standard', 'banner' ) ) )
    	$value = 'standard';

    return $value;
}


/**
 * Sanitize product details
 */
function publisher_sanitize_product_details( $value ) {
	if ( is_array( $value ) ) {
		$details = array();
		foreach( $value as $detail ) {
			if ( in_array( $detail, array( 'meta-info', 'views', 'likes', 'shares' ) ) )
				$details[] = $detail;
		}
		return $details;
	} else {
		$value = array();
	}

	return $value;
}


/**
 * Sanitize cta_image_style
 */
function publisher_sanitize_cta_image_style( $value ) {
	if ( ! in_array( $value, array( 'full-cover', 'tiled' ) ) )
    	$value = 'full-cover';

    return $value;
}




/*-----------------------------------------------------------------------------------*/
/*	Customizer Output
/*-----------------------------------------------------------------------------------*/

/**
 * Output Customizer CSS
 */
function publisher_customizer_output_css() {

	$output = '';

	/*-----------------------------------------------------------------------------------*/
	/*	Colors
	/*-----------------------------------------------------------------------------------*/

	/**
	/*	General
	/*-------------------------------------------------*/

	$background = get_background_color();
	$content_accent = get_theme_mod( 'publisher_options_colors_content_accent' );
	// Content Accent
	if ( $content_accent ) {
		$output .= '
			a,
			.screen-reader-text:hover,
			.screen-reader-text:active,
			.screen-reader-text:focus,
			.toggle-button:hover,
			.sidebar-widgets .widget_calendar tbody a,
			.star-rating,
			.bbpress .entry-content .bbp-topic-content a,
			.bbpress .entry-content .bbp-reply-content a
		';
		$output .= '{';
		$output .= 'color: ' . $content_accent . ';';
		$output .= '}';

		// Background Color
		$output .= '
			.pagination .page-numbers.current,
			.footer-widgets .publisher-posts-loop-widget .pagination .page-numbers.current,
			#call-to-action,
			.woocommerce .products li.product a.added_to_cart,
			.woocommerce-pagination ul li span.current,
			#reviews .woocommerce-pagination ul li a:hover,
			#reviews .woocommerce-pagination ul li span.current,
			.site-featured-header .flex-control-paging li a.flex-active,
			.publisher-posts-loop-widget.loading .navigation a.loading,
			.publisher-products-loop-widget[data-type="product_page"] .woocommerce .single-product a.added_to_cart
		';
		$output .= '{';
		$output .= 'background-color: ' . $content_accent . ';';
		$output .= '}';

		// Border Top Color
		$output .= '
			div.post-flash-icon a,
			.woocommerce .status-flash
		';
		$output .= '{';
		$output .= 'border-top-color: ' . $content_accent . ';';
		$output .= '}';
	}


	// Background
	if ( 'f5f5f5' != $background ) {
		$output .= '
			.woocommerce .woocommerce-tabs ul.tabs
		';
		$output .= '{';
		$output .= 'background: #' . $background . ';';
		$output .= '}';

		$output .= '
			.author-info,
			.comments-title,
			.comment-reply-title,
			.comment-list > li,
			.sidebar-widgets .widget_archive li,
			.sidebar-widgets .widget_categories li,
			.sidebar-widgets .widget_meta li,
			.sidebar-widgets .widget_nav_menu li,
			.sidebar-widgets .widget_pages li,
			.sidebar-widgets .widget_recent_comments li,
			.sidebar-widgets .widget_recent_entries li,
			.sidebar-widgets .widget_rss li,
			.sidebar-widgets .widget_archives li,
			.sidebar-widgets .widget_recent-posts li,
			.sidebar-widgets .widget_recent-comments li,
			.sidebar-widgets .woocommerce ul.product_list_widget li,
			.sidebar-widgets .woocommerce.widget_layered_nav li,
			.sidebar-widgets .woocommerce.widget_product_categories li,
			.sidebar-widgets .woocommerce.widget_shopping_cart .widget_shopping_cart_content p.buttons,
			.sidebar-widgets .publisher-posts-loop-widget .posts-index .listing,
			.woocommerce .products li .entry-footer,
			.woocommerce-cart table.cart tbody tr,
			.woocommerce-cart .cart-collaterals .cart_totals table tr.order-total,
			#bbpress-forums li.bbp-body ul.forum,
			#bbpress-forums li.bbp-body ul.topic,
			#bbpress-forums div.bbp-forum-header,
			#bbpress-forums div.bbp-topic-header,
			#bbpress-forums div.bbp-reply-header,
			#bbpress-forums #bbp-single-user-details #bbp-user-navigation ul > li,
			.publisher-posts-loop-widget .posts-index .listing,
			.publisher-posts-loop-widget[data-layout="listing-h"] .posts-index .listing-featured-container,
			.publisher-posts-loop-widget.paged .posts-index .listing-h-layout .site-main .hentry:nth-child(2),
			.publisher-posts-loop-widget .slider-layout .hentry
		';
		$output .= '{';
		$output .= 'border-color: #' . $background . ';';
		$output .= '}';

		// Box Shadow
		$output .= '
			.hentry .article-wrap,
			.comments-wrap,
			.comment-navigation,
			.sidebar-widgets .widget,
			.sidebar-widgets .publisher-posts-loop-widget[data-layout="mini-listing"] .posts-index .site-main,
			.woocommerce .woocommerce-tabs,
			.woocommerce-cart .woocommerce .cart,
			.woocommerce-cart .cart-collaterals .cart_totals table,
			.woocommerce-cart .cart-collaterals .products li .article-wrap,
			#bbpress-forums div.bbp-search-form,
			#bbpress-forums .bbp-topic-form,
			#bbpress-forums .bbp-reply-form,
			#bbpress-forums .bbp-topic-tag-form,
			#bbpress-forums .bbp-topic-split,
			#bbpress-forums .bbp-topic-merge,
			#bbp-your-profile,
			#bbpress-forums ul.bbp-lead-topic,
			#bbpress-forums ul.bbp-topics,
			#bbpress-forums ul.bbp-forums,
			#bbpress-forums ul.bbp-search-results,
			#bbpress-forums #bbp-single-user-details,
			#bbpress-forums .bbp-user-profile,
			.publisher-posts-loop-widget[data-layout="listing-h"] .posts-index .site-main,
			.publisher-posts-loop-widget[data-layout="listing-v"] .posts-index .site-main,
			.publisher-posts-loop-widget[data-layout="mini-listing"] .posts-index .site-main,
			.so-widget-sow-price-table .ow-pt-columns-publisher .ow-pt-column,
			.woocommerce .products li.product-category .article-wrap
		';
		$output .= '{';
		$output .= 'box-shadow: 0 2px 0 ' . publisher_color_adjust( '#' . $background, -5 ) . ';';
		$output .= '}';
	}


	/**
	/*	Navigation
	/*-------------------------------------------------*/

	$nav_base = get_theme_mod( 'publisher_options_colors_navigation_base' );
	$nav_menu_item = get_theme_mod( 'publisher_options_colors_navigation_menu_item' );
	$nav_menu_item_active = get_theme_mod( 'publisher_options_colors_navigation_menu_item_active' );
	$nav_menu_desc = get_theme_mod( 'publisher_options_colors_navigation_menu_desc' );
	$nav_menu_desc_active = get_theme_mod( 'publisher_options_colors_navigation_menu_desc_active' );
	$nav_submenu_base = get_theme_mod( 'publisher_options_colors_navigation_submenu_base' );

	// Navigation Base
	if ( $nav_base ) {
		$output .= '
			.main-navigation,
			.main-navigation .mobile-menu .menu-toggle
		';
		$output .= '{';
		$output .= '-webkit-box-shadow: inset 0px 0px 0px 1px ' . publisher_color_adjust( $nav_base, -16 ) . ';';
		$output .= '-moz-box-shadow: inset 0px 0px 0px 1px ' . publisher_color_adjust( $nav_base, -16 ) . ';';
		$output .= 'box-shadow: inset 0px 0px 0px 1px ' . publisher_color_adjust( $nav_base, -16 ) . ';';
		$output .= 'background: -webkit-linear-gradient(top, ' . publisher_color_adjust( $nav_base, 12 ) . ', ' . $nav_base . ');';
		$output .= 'background: -o-linear-gradient(top, ' . publisher_color_adjust( $nav_base, 12 ) . ', ' . $nav_base . ');';
		$output .= 'background: -moz-linear-gradient(top, ' . publisher_color_adjust( $nav_base, 12 ) . ', ' . $nav_base . ');';
		$output .= 'background: linear-gradient(top, ' . publisher_color_adjust( $nav_base, 12 ) . ', ' . $nav_base . ');';
		$output .= '}';

		// Border
		$output .= '
			.main-navigation .menu > li,
			.main-navigation.page-menu .menu > ul > li,
			.main-navigation .menu .additional-menu-items > ul > li
		';
		$output .= '{';
		$output .= 'border-color: ' . publisher_color_adjust( $nav_base, -16 ) . ';';
		$output .= '}';

		// Box Shadow
		$output .= '
			.main-navigation li.menu-item-has-children:after
		';
		$output .= '{';
		$output .= '-webkit-box-shadow: inset 0px 0px 0px 1px ' . publisher_color_adjust( $nav_base, -16 ) . ', inset 0px 2px 0 0 rgba(255, 255, 255, 0.1);';
		$output .= '-moz-box-shadow: inset 0px 0px 0px 1px ' . publisher_color_adjust( $nav_base, -16 ) . ', inset 0px 2px 0 0 rgba(255, 255, 255, 0.1);';
		$output .= 'box-shadow: inset 0px 0px 0px 1px ' . publisher_color_adjust( $nav_base, -16 ) . ', inset 0px 2px 0 0 rgba(255, 255, 255, 0.1);';
		$output .= 'background: -webkit-linear-gradient(top, ' . publisher_color_adjust( $nav_base, 12 ) . ', ' . $nav_base . ');';
		$output .= 'background: -o-linear-gradient(top, ' . publisher_color_adjust( $nav_base, 12 ) . ', ' . $nav_base . ');';
		$output .= 'background: -moz-linear-gradient(top, ' . publisher_color_adjust( $nav_base, 12 ) . ', ' . $nav_base . ');';
		$output .= 'background: linear-gradient(top, ' . publisher_color_adjust( $nav_base, 12 ) . ', ' . $nav_base . ');';
		$output .= '}';
	}

	// Navigation Menu Item
	if ( $nav_menu_item ) {
		$output .= '
			.main-navigation a,
			.main-navigation .menu .additional-menu-items a,
			.main-navigation, .main-navigation .mobile-menu .menu-toggle,
			.main-navigation li.menu-item-has-children:after
		';
		$output .= '{';
		$output .= 'color: ' . $nav_menu_item . ';';
		$output .= '}';
	}

	// Navigation Menu Item Active
	if ( $nav_menu_item_active ) {
		$output .= '
			.main-navigation a:hover,
			.main-navigation ul ul a,
			.main-navigation .menu > li:hover > a,
			.main-navigation .menu .additional-menu-items > ul li:hover a,
			.main-navigation li.menu-item-has-children.active-sub-menu:after
		';
		$output .= '{';
		$output .= 'color: ' . $nav_menu_item_active . ';';
		$output .= '}';

		// Media Queries
		$output .= '@media only screen and (max-width:782px) {';
			$output .= '
				.main-navigation ul ul li,
				.main-navigation .menu > li.menu-item-has-children:hover,
				.main-navigation .menu > li.menu-item-has-children.active-sub-menu,
				.main-navigation .menu > li.menu-item-has-children.active-sub-menu > a
			';
			$output .= '{';
			$output .= 'color: ' . $nav_menu_item_active . ';';
			$output .= '}';
		$output .= '}';

		// Border Color
		$output .= '@media only screen and (max-width:782px) {';
			$output .= '
				.main-navigation ul ul li
			';
			$output .= '{';
			$output .= 'border-color: ' . $nav_menu_item_active . ';';
			$output .= '}';
		$output .= '}';
	}

	// Navigation Menu Description
	if ( $nav_menu_desc ) {
		$output .= '
			.main-navigation .menu > li > a > .description
		';
		$output .= '{';
		$output .= 'color: ' . $nav_menu_desc . ';';
		$output .= '}';
	}

	// Navigation Menu Description Active
	if ( $nav_menu_desc_active ) {
		$output .= '
			.main-navigation .description,
			.main-navigation .menu > li:hover > a > .description
		';
		$output .= '{';
		$output .= 'color: ' . $nav_menu_desc_active . ';';
		$output .= '}';

		// Media Queries
		$output .= '@media only screen and (max-width:782px) {';
			$output .= '
				.main-navigation .menu > li.menu-item-has-children.active-sub-menu > a > .description
			';
			$output .= '{';
			$output .= 'color: ' . $nav_menu_desc_active . ';';
			$output .= '}';
		$output .= '}';
	}

	// Navigation Submenu Base
	if ( $nav_submenu_base ) {
		$output .= '
			.main-navigation .sub-menu,
			.main-navigation.page-menu .children,
			.main-navigation .menu > li:hover,
			.main-navigation.page-menu .menu > ul > li:hover,
			.main-navigation .menu > li.menu-item-has-children.active-sub-menu,
			.main-navigation .menu .additional-menu-items > ul > li:hover,
			.active-search .search-toggle
		';
		$output .= '{';
		$output .= 'background: ' . $nav_submenu_base . ';';
		$output .= '}';

		$output .= '
			.main-navigation li ul li:hover
		';
		$output .= '{';
		$output .= 'background: ' . publisher_color_adjust( $nav_submenu_base, 16 ) . ';';
		$output .= '}';
	}


	/**
	/*	Header
	/*-------------------------------------------------*/

	$header_base = get_theme_mod( 'publisher_options_colors_header_base' );
	$site_title = get_theme_mod( 'publisher_options_colors_site_title' );
	$site_description = get_theme_mod( 'publisher_options_colors_site_description' );
	$headband = get_theme_mod( 'publisher_options_colors_header_headband' );
	$headband_title = get_theme_mod( 'publisher_options_colors_header_headband_title' );
	$headband_link = get_theme_mod( 'publisher_options_colors_header_headband_link' );

	// Header Base
	if ( $header_base ) {
		$output .= '
			.site-header-content
		';
		$output .= '{';
		$output .= 'background: ' . $header_base . ';';
		$output .= '}';
	}

	// Site Title
	if ( $site_title ) {
		$output .= '
			.site-title a,
			.logo-image
		';
		$output .= '{';
		$output .= 'color: ' . $site_title . ';';
		$output .= '}';
	}

	// Site Description
	if ( $site_description ) {
		$output .= '
			.site-description
		';
		$output .= '{';
		$output .= 'color: ' . $site_description . ';';
		$output .= '}';
	}

	// Headband
	if ( $headband ) {
		$output .= '
			.headband
		';
		$output .= '{';
		$output .= 'background: ' . $headband . ';';
		$output .= '}';

		// Darker
		$output .= '
			.headband .mtphr-dnt h3.mtphr-dnt-title.mtphr-dnt-inline-title
		';
		$output .= '{';
		$output .= 'border-color: ' . publisher_color_adjust( $headband, 13 ) . ';';
		$output .= '}';

		// Darkest
		$output .= '
			.headband .mtphr-dnt h3.mtphr-dnt-title.mtphr-dnt-inline-title span
		';
		$output .= '{';
		$output .= 'border-color: ' . publisher_color_adjust( $headband, -16 ) . ';';
		$output .= '}';
	}

	// Headband Title
	if ( $headband_title ) {
		$output .= '
			.headband h1,
			.headband h2,
			.headband h3,
			.headband h4,
			.headband h5,
			.headband h6,
			.headband .mtphr-dnt h3.mtphr-dnt-title.mtphr-dnt-inline-title
		';
		$output .= '{';
		$output .= 'color: ' . $headband_title . ';';
		$output .= '}';
	}

	// Headband Link
	if ( $headband_link ) {
		$output .= '
			.headband a
		';
		$output .= '{';
		$output .= 'color: ' . $headband_link . ';';
		$output .= '}';

		// Lighter
		$output .= '
			.headband a:hover
		';
		$output .= '{';
		$output .= 'color: ' . publisher_color_adjust( $headband_link, 42 ) . ';';
		$output .= '}';
	}


	/**
	/*	Featured Header
	/*-------------------------------------------------*/

	$featured_header_base = get_theme_mod( 'publisher_options_colors_featured_header_base' );
	$featured_header_titles = get_theme_mod( 'publisher_options_colors_featured_header_titles' );
	$featured_header_text = get_theme_mod( 'publisher_options_colors_featured_header_text' );
	$featured_header_button_base = get_theme_mod( 'publisher_options_colors_featured_header_button_base' );
	$featured_header_button_text = get_theme_mod( 'publisher_options_colors_featured_header_button_text' );
	$featured_header_button_base_active = get_theme_mod( 'publisher_options_colors_featured_header_button_base_active' );
	$featured_header_button_text_active = get_theme_mod( 'publisher_options_colors_featured_header_button_text_active' );

	// Featured Header Base
	if ( $featured_header_base ) {
		$output .= '
			.site-featured-header,
			.entry-layout-cover.has-post-gallery .owl-gallery .owl-stage-outer,
			.single.entry-layout-cover.has-post-gallery .site-featured-header .loader-icon,
			.site-featured-header .site-featured-header-image
		';
		$output .= '{';
		$output .= 'background: ' . $featured_header_base . ';';
		$output .= '}';
	}

	// Featured Header Titles
	if ( $featured_header_titles ) {
		$output .= '
			.hero-title
		';
		$output .= '{';
		$output .= 'color: ' . $featured_header_titles . ';';
		$output .= '}';
	}

	// Featured Header Text
	if ( $featured_header_text ) {
		$output .= '
			.hero-description,
			.hero-description .author-social a,
			.hero-description .author-social a:hover
		';
		$output .= '{';
		$output .= 'color: ' . $featured_header_text . ';';
		$output .= '}';
	}

	// Featured Button Base
	if ( $featured_header_button_base ) {
		$output .= '
			.hero-link.button
		';
		$output .= '{';
		$output .= 'background-color: ' . $featured_header_button_base . ';';
		$output .= '}';

		// Box Shadow
		$output .= '
			.hero-link.button
		';
		$output .= '{';
		$output .= '-webkit-box-shadow: inset 0px 0px 0px 1px ' . publisher_color_adjust( $featured_header_button_base, -16 ) . ', inset 0px 2px 0 0 rgba(255, 255, 255, 0.1);';
		$output .= '-moz-box-shadow: inset 0px 0px 0px 1px ' . publisher_color_adjust( $featured_header_button_base, -16 ) . ', inset 0px 2px 0 0 rgba(255, 255, 255, 0.1);';
		$output .= 'box-shadow: inset 0px 0px 0px 1px ' . publisher_color_adjust( $featured_header_button_base, -16 ) . ', inset 0px 2px 0 0 rgba(255, 255, 255, 0.1);';
		$output .= '}';
	}

	// Featured Button Text
	if ( $featured_header_button_text ) {
		$output .= '
			.hero-link.button
		';
		$output .= '{';
		$output .= 'color: ' . $featured_header_button_text . ';';
		$output .= '}';
	}

	// Featured Button Base Active
	if ( $featured_header_button_base_active ) {
		$output .= '
			.hero-link.button:hover
		';
		$output .= '{';
		$output .= 'background-color: ' . $featured_header_button_base_active . ';';
		$output .= '}';

		// Box Shadow
		$output .= '
			.hero-link.button:hover
		';
		$output .= '{';
		$output .= '-webkit-box-shadow: inset 0px 0px 0px 1px ' . publisher_color_adjust( $featured_header_button_base_active, -33 ) . ', inset 0px 2px 0 0 ' . publisher_color_adjust( $featured_header_button_base_active, 17 ) . ';';
		$output .= '-moz-box-shadow: inset 0px 0px 0px 1px ' . publisher_color_adjust( $featured_header_button_base_active, -33 ) . ', inset 0px 2px 0 0 ' . publisher_color_adjust( $featured_header_button_base_active, 17 ) . ';';
		$output .= 'box-shadow: inset 0px 0px 0px 1px ' . publisher_color_adjust( $featured_header_button_base_active, -33 ) . ', inset 0px 2px 0 0 ' . publisher_color_adjust( $featured_header_button_base_active, 17 ) . ';';
		$output .= '}';
	}

	// Featured Button Text Active
	if ( $featured_header_button_text_active ) {
		$output .= '
			.hero-link.button:hover
		';
		$output .= '{';
		$output .= 'color: ' . $featured_header_button_text_active . ';';
		$output .= '}';
	}


	/**
	/*	Content
	/*-------------------------------------------------*/


	$content_headings = get_theme_mod( 'publisher_options_colors_content_headings' );
	$content_text = get_theme_mod( 'publisher_options_colors_content_text' );
	$content_secondary_headings = get_theme_mod( 'publisher_options_colors_content_secondary_headings' );
	$content_secondary_text = get_theme_mod( 'publisher_options_colors_content_secondary_text' );
	$content_titles = get_theme_mod( 'publisher_options_colors_content_titles' );
	$content_box_base = get_theme_mod( 'publisher_options_colors_content_box_base' );

	// Content Headings
	if ( $content_headings ) {
		// Color
		$output .= '
			h1,
			h2,
			h3,
			h4,
			h5,
			h6,
			.h1,
			.h2,
			.h3,
			.h4,
			.h5,
			.h6,
			blockquote,
			.pull-right,
			.pull-left,
			.dropcap,
			.lead,
			.entry-title a,
			.author-title a,
			.publisher-posts-loop-widget .entry-title a,
			.sidebar-widgets .widget .publisher-posts-loop-widget .entry-title a,
			.woocommerce table.shop_table td.product-name,
			.woocommerce table.shop_table td.product-name a,
			#bbpress-forums fieldset.bbp-form legend,
			#bbpress-forums #bbp-user-wrapper .bbp-user-profile h2.entry-title,
			#bbpress-forums #bbp-user-wrapper #bbp-your-profile h2.entry-title,
			.bbp-topic-title .bbp-topic-permalink,
			#bbpress-forums .bbp-forum-title,
			.so-widget-sow-cta .sow-cta-base .sow-cta-text h4,
			div.sow-features-list .sow-features-feature h5,
			.sow-features-list .sow-features-feature h5 a,
			.footer-widgets .widget .publisher-posts-loop-widget .entry-title a
		';
		$output .= '{';
		$output .= 'color: ' . $content_headings . ';'; // #292929
		$output .= '}';

		$output .= '
			.entry-header .hero-description,
			.comments-title,
			.comment-reply-title,
			#bbpress-forums li.bbp-body ul.forum .bbp-forum-info:before,
			#bbpress-forums li.bbp-body ul.topic .bbp-forum-info:before
		';
		$output .= '{';
		$output .= 'color: ' . publisher_color_adjust( $content_headings, -44, 'saturation' ) . ';'; // #9a9a9a
		$output .= '}';

		// Border
		$output .= '
			blockquote,
			#bbpress-forums fieldset.bbp-form fieldset.bbp-form
		';
		$output .= '{';
		$output .= 'border-color: ' . $content_headings . ';'; // #292929
		$output .= '}';

		// Background
		$output .= '
			.lead:after
		';
		$output .= '{';
		$output .= 'background: ' . $content_headings . ';'; // #292929
		$output .= '}';
	}

	// Content Text
	if ( $content_text ) {
		$output .= '
			body,
			.hentry,
			.comments-area,
			.page-links a:hover,
			.woocommerce .price ins,
			.so-widget-sow-cta .sow-cta-base .sow-cta-text h5
		';
		$output .= '{';
		$output .= 'color: ' . $content_text . ';';
		$output .= '}';

		$output .= '
			.logged-in .primary .comment-form .user-identity,
			.author-title a,
			.comment-author cite,
			.comment-author cite a,
			.author-social a:hover,
			.comment-navigation .pagination .page-numbers.current,
			.comments-area .reply a,
			.no-results-widgets a,
			.posts-index .format-link:not(.publisher-ext) .article-wrap .entry-content .post-link,
			.woocommerce #content div.product .woocommerce-tabs ul.tabs li.active a,
			.woocommerce p.stars a:hover,
			.woocommerce-cart .cart-collaterals .cart_totals table th,
			#bbpress-forums div.bbp-topic-author a.bbp-author-name,
			#bbpress-forums div.bbp-reply-author a.bbp-author-name,
			.woocommerce .summary .woocommerce-review-link
		';
		$output .= '{';
		$output .= 'color: ' . publisher_color_adjust( $content_text, -12 ) . ';'; // #292929
		$output .= '}';

		$output .= '
			.author-info,
			.author-social a,
			.comment-list ol.children > li:before,
			.comment-author-icon,
			.comment-author-icon:hover,
			.entry-meta,
			.entry-meta a,
			.entry-footer .entry-meta.tags,
			.entry-footer .entry-meta.tags a,
			.page-links a,
			.comment-navigation a,
			.archive-navigation a,
			.comment-navigation a:hover,
			.archive-navigation a:hover,
			.comment-metadata a,
			#cancel-comment-reply-link,
			.comment-respond .required,
			.woocommerce .woocommerce-tabs ul.tabs li a,
			.woocommerce .form-row label,
			.woocommerce #review_form .comment-form label,
			.woocommerce p.stars a,
			li.bbp-forum-freshness a,
			li.bbp-topic-freshness a,
			.bbp-body li.bbp-topic-freshness,
			#bbpress-forums .bbp-forums-list li a,
			#bbpress-forums .bbp-forums-list,
			.bbp-topic-started-by a,
			#bbpress-forums fieldset.bbp-form label,
			.bbp-reply-post-date,
			#bbpress-forums div.bbp-forum-content .bbp-forum-permalink,
			#bbpress-forums div.bbp-topic-content .bbp-topic-permalink,
			#bbpress-forums div.bbp-reply-content .bbp-reply-permalink,
			#bbpress-forums div.bbp-reply-author .bbp-author-role,
			#bbpress-forums #bbp-single-user-details #bbp-user-navigation a,
			.bbp-meta .bbp-header,
			.bbp-topic-meta .bbp-topic-started-in,
			#bbpress-forums div.bbp-reply-content .bbp-header,
			.bbp-topic-started-by,
			.bbp-topic-title div:last-child .bbp-topic-started-by .bbp-author-name,
			.bbp-forum-post-date,
			.bbp-topic-post-date,
			.bbp-reply-post-date,
			.publisher-shares-network .shares-header,
			.sow-features-list .sow-features-feature p,
			.woocommerce-cart .cart-collaterals .shipping-calculator-button,
			.footer-widgets .widget .publisher-posts-loop-widget .entry-meta a
		';
		$output .= '{';
		$output .= 'color: ' . publisher_color_adjust( $content_text, -32, 'saturation' ) . ';'; // #9a9a9a
		$output .= '}';

		$output .= '
			.wp-caption-text,
			span.bbp-author-ip,
			span.bbp-admin-links a,
			span.bbp-admin-links,
			#bbpress-forums .bbp-topic-content ul.bbp-topic-revision-log,
			#bbpress-forums .bbp-reply-content ul.bbp-topic-revision-log,
			#bbpress-forums .bbp-reply-content ul.bbp-reply-revision-log
		';
		$output .= '{';
		$output .= 'color: ' . publisher_color_adjust( $content_text, -52, 'saturation' ) . ';'; // #ccc
		$output .= '}';

		// Border
		$output .= '
			.primary abbr,
			.primary acronym
		';
		$output .= '{';
		$output .= 'border-color: ' . publisher_color_adjust( $content_text ) . ';';
		$output .= '}';

		$output .= '
			#bbpress-forums .bbp-topic-content ul.bbp-topic-revision-log,
			#bbpress-forums .bbp-reply-content ul.bbp-topic-revision-log,
			#bbpress-forums .bbp-reply-content ul.bbp-reply-revision-log,
			#bbpress-forums .bbp-forums-list
		';
		$output .= '{';
		$output .= 'border-color: ' . publisher_color_adjust( $content_text, -52, 'saturation' ) . ';'; // #ccc
		$output .= '}';

		$output .= '
			hr,
			.children .comment-body
		';
		$output .= '{';
		$output .= 'border-color: ' . publisher_color_adjust( $content_text, -63, 'saturation' ) . ';'; // #e8e8e8
		$output .= '}';

		$output .= '
			.woocommerce #reviews #comments ol.commentlist li,
			.woocommerce .cart,
			.woocommerce .variations_button,
			.woocommerce p.stars a.star-1,
			.woocommerce p.stars a.star-2,
			.woocommerce p.stars a.star-3,
			.woocommerce p.stars a.star-4,
			.woocommerce p.stars a.star-5,
			.publisher-products-loop-widget[data-type="product_page"] div[itemprop="description"]
		';
		$output .= '{';
		$output .= 'border-color: ' . publisher_color_adjust( $content_text, -67, 'saturation' ) . ';'; // #f2f2f2
		$output .= '}';
	}

	// Content Secondary Headings
	if ( $content_secondary_headings ) {
		$output .= '
			.author-title a,
			.comment-author cite a,
			.logged-in .primary .comment-form .user-identity,
			.woocommerce .variations_form .variations .label,
			.woocommerce #content div.product .woocommerce-tabs ul.tabs li.active a,
			.woocommerce #reviews #comments ol.commentlist li .comment-text p.meta,
			.woocommerce-cart .cart-collaterals .cart_totals table th,
			.woocommerce .shop_table tfoot th,
			.woocommerce #payment ul.payment_methods li label,
			.bbp-topic-title div:last-child .bbp-topic-started-by .bbp-author-name,
			#bbpress-forums div.bbp-topic-author a.bbp-author-name,
			#bbpress-forums div.bbp-reply-author a.bbp-author-name
		';
		$output .= '{';
		$output .= 'color: ' . $content_secondary_headings . ';';
		$output .= '}';

		$output .= '
			.comment-author-icon,
			.comment-author-icon:hover
		';
		$output .= '{';
		$output .= 'color: ' . publisher_color_adjust( $content_secondary_headings, -44, 'saturation' ) . ';'; // #9a9a9a
		$output .= '}';
	}

	// Content Secondary Text
	if ( $content_secondary_text ) {
		$output .= '
			.entry-meta,
			.entry-meta a,
			.author-info,
			.author-social a,
			#bbpress-forums .bbp-forums-list li a,
			.bbp-reply-post-date,
			.footer-widgets .widget .publisher-posts-loop-widget .entry-meta a
		';
		$output .= '{';
		$output .= 'color: ' . $content_secondary_text . ';'; // #9a9a9a
		$output .= '}';

		$output .= '
			#bbpress-forums .bbp-forums-list
		';
		$output .= '{';
		$output .= 'border-color: ' . $content_secondary_text . ';'; // #9a9a9a
		$output .= '}';
	}

	// Content Titles
	if ( $content_titles ) {
		$output .= '
			.entry-title,
			.entry-title a,
			.publisher-posts-loop-widget .entry-title a,
			.sidebar-widgets .widget .publisher-posts-loop-widget .entry-title a,
			.woocommerce .products li a h3,
			.woocommerce .products li.product-category h3 mark,
			.woocommerce table.shop_table td.product-name,
			.woocommerce table.shop_table td.product-name a,
			.bbp-topic-title .bbp-topic-permalink,
			#bbpress-forums .bbp-forum-title,
			#bbpress-forums li.bbp-body ul.forum .bbp-forum-info:before,
			#bbpress-forums li.bbp-body ul.topic .bbp-forum-info:before,
			.footer-widgets .widget .publisher-posts-loop-widget .entry-title a
		';
		$output .= '{';
		$output .= 'color: ' . $content_titles . ';';
		$output .= '}';
	}

	// Content Box Base
	if ( $content_box_base ) {
		// Background
		$output .= '
			.hentry .article-wrap,
			.comments-wrap,
			.comment-navigation,
			.woocommerce .woocommerce-tabs,
			.woocommerce .woocommerce-tabs ul.tabs li,
			.woocommerce-cart .woocommerce .cart,
			.woocommerce-cart .cart-collaterals .cart_totals table,
			.woocommerce-cart .cart-collaterals .products li .article-wrap,
			#bbpress-forums div.bbp-search-form,
			#bbpress-forums .bbp-topic-form,
			#bbpress-forums .bbp-reply-form,
			#bbpress-forums .bbp-topic-tag-form,
			#bbpress-forums .bbp-topic-split,
			#bbpress-forums .bbp-topic-merge,
			#bbp-your-profile,
			#bbpress-forums div.odd,
			#bbpress-forums ul.odd,
			#bbpress-forums div.even,
			#bbpress-forums ul.even,
			#bbpress-forums div.bbp-forum-header,
			#bbpress-forums div.bbp-topic-header,
			#bbpress-forums div.bbp-reply-header,
			#bbpress-forums .bbp-user-profile,
			#bbpress-forums #bbp-single-user-details #bbp-user-navigation a,
			.publisher-posts-loop-widget[data-layout="listing-h"] .posts-index .site-main,
			.publisher-posts-loop-widget[data-layout="listing-v"] .posts-index .site-main,
			.publisher-posts-loop-widget .slider-layout .hentry,
			.publisher-posts-loop-widget .posts-index .listing,
			.woocommerce .products li.product-category .article-wrap
		';
		$output .= '{';
		$output .= 'background: ' . $content_box_base . ';';
		$output .= '}';

		$output .= '
			.woocommerce #payment ul.payment_methods
		';
		$output .= '{';
		$output .= 'background: ' . publisher_color_adjust( $content_box_base, -3 ) . ';'; // #f8f8f8
		$output .= '}';

		$output .= '
			.primary table thead,
			.primary table tr:nth-child(even)
		';
		$output .= '{';
		$output .= 'background: ' . publisher_color_adjust( $content_box_base, -5 ) . ';'; // #f2f2f2
		$output .= '}';

		$output .= '
			.featured-image,
			.posts-index featured-preview,
			.archive-navigation,
			.featured-preview.owl-gallery .gallery-item,
			.single .format-gallery .loader-icon,
			.single.entry-layout-banner.has-post-gallery .owl-gallery .loader-icon,
			.publisher-posts-loop-widget .mini-grid.hentry .featured-preview
		';
		$output .= '{';
		$output .= 'background-color: ' . publisher_color_adjust( $content_box_base, -9 ) . ';'; // #e8e8e8
		$output .= '}';
	}


	/**
	/*	Content Elements
	/*-------------------------------------------------*/

	$content_cat_base = get_theme_mod( 'publisher_options_colors_content_category_base' );
	$content_cat_text = get_theme_mod( 'publisher_options_colors_content_category_text' );
	$content_icon_base = get_theme_mod( 'publisher_options_colors_content_icon_base' );
	$content_button_base = get_theme_mod( 'publisher_options_colors_content_button_base' );
	$content_button_text = get_theme_mod( 'publisher_options_colors_content_button_base_text' );
	$content_button_base_active = get_theme_mod( 'publisher_options_colors_content_button_base_active' );
	$content_button_text_active = get_theme_mod( 'publisher_options_colors_content_button_text_active' );

	// Content Category Base
	if ( $content_cat_base ) {
		$output .= '
			.featured-image,
			.posts-index featured-preview,
			.entry-meta .meta-category a,
			.entry-meta.hero .meta-category a,
			.entry-footer .entry-meta.tags a:hover,
			.posts-index .format-image:not(.publisher-ext) .article-wrap .entry-title a
		';
		$output .= '{';
		$output .= 'background-color: ' . $content_cat_base . ';';
		$output .= '}';
	}

	// Content Category Text
	if ( $content_cat_text ) {
		$output .= '
			.entry-meta .meta-category a,
			.has-featured-header-image .entry-meta.hero .meta-category a,
			.entry-layout-wide.has-post-gallery .site-featured-header .entry-meta.hero .meta-category a,
			.entry-footer .entry-meta.tags a:hover,
			.posts-index .format-image:not(.publisher-ext) .article-wrap .entry-title a,
			.publisher-posts-loop-widget .posts-index .cover.hentry.has-post-thumbnail .meta-category a
		';
		$output .= '{';
		$output .= 'color: ' . $content_cat_text . ';';
		$output .= '}';
	}

	// Content Icon Base
	if ( $content_icon_base ) {
		$output .= '
			.post-format-icon i:before,
		';
		$output .= '{';
		$output .= 'background: ' . $content_icon_base . ';';
		$output .= '}';

		$output .= '
			.sow-features-list .sow-icon-container
		';
		$output .= '{';
		$output .= 'color: ' . $content_icon_base . ';';
		$output .= '}';
	}

	// Content Button Base
	if ( $content_button_base ) {
		$output .= '
			.entry-content .more-link,
			.featured-image-link,
			.no-results-widgets .widget .tagcloud a,
			.sidebar-widgets .widget .publisher-posts-loop-widget .entry-content .more-link,
			button,
			input[type="button"],
			input[type="reset"],
			input[type="submit"],
			.button,
			#reviews .woocommerce-pagination ul li a
		';
		$output .= '{';
		$output .= 'background: ' . $content_button_base . ';';
		$output .= '}';
	}

	// Content Button Text
	if ( $content_button_text ) {
		$output .= '
			.entry-content .more-link,
			.featured-image-link,
			.no-results-widgets .widget .tagcloud a,
			.sidebar-widgets .widget .publisher-posts-loop-widget .entry-content .more-link,
			button,
			input[type="button"],
			input[type="reset"],
			input[type="submit"],
			.button,
			#reviews .woocommerce-pagination ul li a,
			.footer-widgets .widget .publisher-posts-loop-widget .entry-content .more-link
		';
		$output .= '{';
		$output .= 'color: ' . $content_button_text . ';';
		$output .= '}';
	}

	// Content Button Base Active
	if ( $content_button_base_active ) {
		$output .= '
			.entry-content .more-link:hover,
			.featured-image-link:hover,
			.page-links.next-and-number > span.button,
			.no-results-widgets .widget .tagcloud a:hover,
			.sidebar-widgets .widget .publisher-posts-loop-widget .entry-content .more-link:hover,
			button:hover,
			input[type="button"]:hover,
			input[type="reset"]:hover,
			input[type="submit"]:hover,
			.button:hover,
			#reviews .woocommerce-pagination ul li span.current,
			#reviews .woocommerce-pagination ul li a:hover,
			.woocommerce .products li.product a.added_to_cart,
			.footer-widgets .widget .publisher-posts-loop-widget .entry-content .more-link:hover
		';
		$output .= '{';
		$output .= 'background: ' . $content_button_base_active . ';';
		$output .= '}';
	}

	// Content Button Text Active
	if ( $content_button_text_active ) {
		$output .= '
			.entry-content .more-link:hover,
			.featured-image-link:hover,
			.page-links.next-and-number > span.button,
			.no-results-widgets .widget .tagcloud a:hover,
			.sidebar-widgets .widget .publisher-posts-loop-widget .entry-content .more-link:hover,
			button:hover,
			input[type="button"]:hover,
			input[type="reset"]:hover,
			input[type="submit"]:hover,
			.button:hover,
			#reviews .woocommerce-pagination ul li span.current,
			#reviews .woocommerce-pagination ul li a:hover,
			.woocommerce .products li.product a.added_to_cart,
			.footer-widgets .widget .publisher-posts-loop-widget .entry-content .more-link:hover
		';
		$output .= '{';
		$output .= 'color: ' . $content_button_text_active . ';';
		$output .= '}';
	}


	/**
	/*	Secondary
	/*-------------------------------------------------*/

	$secondary_titles = get_theme_mod( 'publisher_options_colors_secondary_titles' );
	$secondary_text = get_theme_mod( 'publisher_options_colors_secondary_text' );
	$secondary_button_base = get_theme_mod( 'publisher_options_colors_secondary_button_base' );
	$secondary_button_text = get_theme_mod( 'publisher_options_colors_secondary_button_text' );
	$secondary_button_base_active = get_theme_mod( 'publisher_options_colors_secondary_button_base_active' );
	$secondary_button_text_active = get_theme_mod( 'publisher_options_colors_secondary_button_text_active' );

	// Secondary Titles
	if ( $secondary_titles ) {
		$output .= '
			.section-title,
			.primary .section-title,
			.breadcrumbs-menu li.current,
			.woocommerce .woocommerce-breadcrumb,
			.woocommerce .products h2,
			.woocommerce .cross-sells h2,
			.woocommerce .cart_totals h2,
			.woocommerce-cart.entry-layout-standard .primary .site-main > .hentry > .article-wrap .entry-title,
			.woocommerce-cart.entry-layout-cover .primary .site-main > .hentry > .article-wrap .entry-title,
			.woocommerce-cart.entry-layout-banner .primary .site-main > .hentry > .article-wrap .entry-title,
			div.bbp-breadcrumb .bbp-breadcrumb-current,
			.bbpress-header .hero-title,
			#bbpress-forums > #subscription-toggle a,
			#bbpress-forums div.bbp-topic-tags,
			#bbpress-forums div.bbp-topic-tags a,
			#bbpress-forums #bbp-user-wrapper h2.entry-title,
			.so-widget-sow-cta .sow-cta-base.no-box .sow-cta-text h4,
			.sow-features-feature .textwidget h5,
			.panel-grid-cell .widget-title,
			.publisher-headline h1.sow-title
		';
		$output .= '{';
		$output .= 'color: ' . $secondary_titles . ';'; // #292929
		$output .= '}';

		// Border Color
		$output .= '
			.has-description .section-title,
			.publisher-headline .decoration
		';
		$output .= '{';
		$output .= 'border-color: ' . $secondary_titles . ';';
		$output .= '}';
	}

	// Secondary Text
	if ( $secondary_text ) {
		$output .= '
			.site-content,
			.breadcrumbs-menu li a,
			.pagination .dots,
			.posts-index .navigation,
			.woocommerce .woocommerce-breadcrumb a,
			.woocommerce .woocommerce-breadcrumb span,
			.woocommerce-cart.entry-layout-standard .primary .site-main > .hentry > .article-wrap .entry-header .hero-description,
			.woocommerce-cart.entry-layout-cover .primary .site-main > .hentry > .article-wrap .entry-header .hero-description,
			.woocommerce-cart.entry-layout-banner .primary .site-main > .hentry > .article-wrap .entry-header .hero-description,
			div.bbp-breadcrumb a,
			.bbp-pagination-count,
			ul.bbp-threaded-replies > li:before,
			.bbp-pagination-links span.dots,
			.so-widget-sow-cta .sow-cta-base.no-box .sow-cta-text h5,
			.section-description,
			.publisher-headline h3.sow-description
		';
		$output .= '{';
		$output .= 'color: ' . $secondary_text . ';'; // #9a9a9a
		$output .= '}';

		$output .= '
			.so-widget-sow-image .image-container p
		';
		$output .= '{';
		$output .= 'color: ' . publisher_color_adjust( $secondary_text, -20, 'saturation' ) . ';'; // #cccccc
		$output .= '}';
	}

	// Button Base
	if ( $secondary_button_base ) {
		$output .= '
			.navigation a,
			.woocommerce-pagination ul li a,
			.woocommerce-cart .cart-collaterals .checkout-button,
			.bbp-pagination-links a,
			.owl-nav .owl-prev,
			.owl-nav .owl-next,
			.sow-features-list .sow-features-feature p.sow-more-text a
		';
		$output .= '{';
		$output .= 'background: ' . $secondary_button_base . ';';
		$output .= '}';

		// Border Color
		$output .= '
			.owl-dots .owl-dot img
		';
		$output .= '{';
		$output .= 'border-color: ' . $secondary_button_base . ';';
		$output .= '}';
	}

	// Button Text
	if ( $secondary_button_text ) {
		$output .= '
			.navigation a,
			.woocommerce-pagination ul li a,
			.woocommerce-cart .cart-collaterals .checkout-button,
			.bbp-pagination-links a,
			.owl-nav .owl-prev,
			.owl-nav .owl-next,
			.sow-features-list .sow-features-feature p.sow-more-text a
		';
		$output .= '{';
		$output .= 'color: ' . $secondary_button_text . ';';
		$output .= '}';
	}

	// Button Base Active
	if ( $secondary_button_base_active ) {
		$output .= '
			.navigation a:hover,
			.pagination .page-numbers.current,
			.post-type-archive .woocommerce-pagination ul li a:hover,
			.post-type-archive .woocommerce-pagination ul li span.current,
			.woocommerce-cart .cart-collaterals .checkout-button:hover,
			.bbp-pagination-links a:hover,
			.bbp-pagination-links span.current,
			.owl-nav .owl-prev:hover,
			.owl-nav .owl-next:hover,
			.sow-features-list .sow-features-feature p.sow-more-text a:hover,
			.publisher-posts-loop-widget .navigation a.loading,
			.woocommerce-pagination ul li a:hover
		';
		$output .= '{';
		$output .= 'background: ' . $secondary_button_base_active . ';';
		$output .= '}';

		// Border Color
		$output .= '
			.owl-dots .owl-dot.active img
		';
		$output .= '{';
		$output .= 'border-color: ' . $secondary_button_base . ';';
		$output .= '}';
	}

	// Button Text Active
	if ( $secondary_button_text_active ) {
		$output .= '
			.navigation a:hover,
			.pagination .page-numbers.current,
			.post-type-archive .woocommerce-pagination ul li a:hover,
			.post-type-archive .woocommerce-pagination ul li span.current,
			.woocommerce-cart .cart-collaterals .checkout-button:hover,
			.bbp-pagination-links a:hover,
			.bbp-pagination-links span.current,
			.owl-nav .owl-prev:hover,
			.owl-nav .owl-next:hover,
			.sow-features-list .sow-features-feature p.sow-more-text a:hover,
			.publisher-posts-loop-widget .navigation a.loading,
			.woocommerce-pagination ul li a:hover
		';
		$output .= '{';
		$output .= 'color: ' . $secondary_button_text_active . ';';
		$output .= '}';
	}


	/**
	/*	Sidebar
	/*-------------------------------------------------*/

	$sidebar_accent = get_theme_mod( 'publisher_options_colors_sidebar_accent' );
	$sidebar_header_base = get_theme_mod( 'publisher_options_colors_sidebar_header_base' );
	$sidebar_header_titles = get_theme_mod( 'publisher_options_colors_sidebar_header_titles' );
	$sidebar_header_text = get_theme_mod( 'publisher_options_colors_sidebar_header_text' );
	$sidebar_text = get_theme_mod( 'publisher_options_colors_sidebar_text' );
	$sidebar_box_base = get_theme_mod( 'publisher_options_colors_sidebar_box_base' );
	$sidebar_button_base = get_theme_mod( 'publisher_options_colors_sidebar_button_base' );
	$sidebar_button_text = get_theme_mod( 'publisher_options_colors_sidebar_button_text' );
	$sidebar_button_base_active = get_theme_mod( 'publisher_options_colors_sidebar_button_base_active' );
	$sidebar_button_text_active = get_theme_mod( 'publisher_options_colors_sidebar_button_text_active' );


	// Sidebar Accent
	if ( $sidebar_accent ) {
		$output .= '
			.sidebar-widgets .widget_rss li .rsswidget,
			.sidebar-widgets .woocommerce ul.product_list_widget li a,
			.sidebar-widgets .publisher-posts-loop-widget[data-layout="mini-listing"] .posts-index .listing .entry-title a,
			.sidebar-widgets .publisher-posts-loop-widget .entry-title a
		';
		$output .= '{';
		$output .= 'color: ' . $sidebar_accent . ';'; #292929
		$output .= '}';

		$output .= '
			.sidebar-widgets .widget_rss .rss-date
		';
		$output .= '{';
		$output .= 'color: ' . publisher_color_adjust( $sidebar_accent, 10 ) . ';';
		$output .= '}';

		$output .= '
			.sidebar-widgets .widget a,
			.sidebar-widgets.widget-area .widget li:before,
			.sidebar-widgets .widget_calendar caption
		';
		$output .= '{';
		$output .= 'color: ' . publisher_color_adjust( $sidebar_accent, 15 ) . ';'; #9a9a9a
		$output .= '}';

		// Border Color
		$output .= '
			.sidebar-widgets .widget_text a
		';
		$output .= '{';
		$output .= 'border-color: ' . publisher_color_adjust( $sidebar_accent, 15 ) . ';'; #9a9a9a
		$output .= '}';
	}

	// Sidebar Header Base
	if ( $sidebar_header_base ) {
		$output .= '
			.sidebar-widgets .widget .widget-title,
			.sidebar-widgets .publisher-posts-loop-widget .section-header.section_block,
			.woocommerce .shop_table thead,
			#bbpress-forums li.bbp-header,
			.publisher-posts-loop-widget .navigation.header-prev-next a,
			.publisher-posts-loop-widget .navigation.header-prev-next a.loading,
			.footer-widgets div.publisher-posts-loop-widget .navigation.header-prev-next a,
			.footer-widgets div.publisher-posts-loop-widget .navigation.header-prev-next a.loading,
			.publisher-posts-loop-widget .section-header.section_block,
			.publisher-headline.section_block
		';
		$output .= '{';
		$output .= '-webkit-box-shadow: inset 0px 0px 0px 1px ' . publisher_color_adjust( $sidebar_header_base, -16 ) . ', inset 0px 2px 0 0 rgba(255, 255, 255, 0.1);';
		$output .= '-moz-box-shadow: inset 0px 0px 0px 1px ' . publisher_color_adjust( $sidebar_header_base, -16 ) . ', inset 0px 2px 0 0 rgba(255, 255, 255, 0.1);';
		$output .= 'box-shadow: inset 0px 0px 0px 1px ' . publisher_color_adjust( $sidebar_header_base, -16 ) . ', inset 0px 2px 0 0 rgba(255, 255, 255, 0.1);';
		$output .= 'background: ' . $sidebar_header_base . ';';
		$output .= 'background: -webkit-linear-gradient(top, ' . publisher_color_adjust( $sidebar_header_base, 12 ) . ', ' . $sidebar_header_base . ');';
		$output .= 'background: -o-linear-gradient(top, ' . publisher_color_adjust( $sidebar_header_base, 12 ) . ', ' . $sidebar_header_base . ');';
		$output .= 'background: -moz-linear-gradient(top, ' . publisher_color_adjust( $sidebar_header_base, 12 ) . ', ' . $sidebar_header_base . ');';
		$output .= 'background: linear-gradient(top, ' . publisher_color_adjust( $sidebar_header_base, 12 ) . ', ' . $sidebar_header_base . ');';
		$output .= '}';

		// Background
		$output .= '
			.article-footer .entry-meta,
			#bbpress-forums #bbp-single-user-details #bbp-user-navigation li.current a
		';
		$output .= '{';
		$output .= 'background: ' . $sidebar_header_base . ';';
		$output .= '}';

		// Description
		$output .= '
			.publisher-headline.section_block.has-description .section-title,
			.publisher-headline.section_block.has-divider .decoration
		';
		$output .= '{';
		$output .= 'border-color: ' . publisher_color_adjust( $sidebar_header_base, -16 ) . ';';
		$output .= '-webkit-box-shadow: inset -1px 0 0 0 ' . publisher_color_adjust( $sidebar_header_base, 13 ) . ';';
		$output .= '-moz-box-shadow: inset -1px 0 0 0 ' . publisher_color_adjust( $sidebar_header_base, 13 ) . ';';
		$output .= 'box-shadow: inset -1px 0 0 0 ' . publisher_color_adjust( $sidebar_header_base, 13 ) . ';';
		$output .= '}';

		// Border
		$output .= '
			.woocommerce-checkout .woocommerce-checkout-review-order,
			.woocommerce .shop_table.order_details,
			.woocommerce .shop_table tfoot tr:first-child,
			.publisher-headline.section_block.has-divider h1
		';
		$output .= '{';
		$output .= 'border-color: ' . publisher_color_adjust( $sidebar_header_base, -16 ) . ';';
		$output .= '}';
	}

	// Sidebar Header Titles
	if ( $sidebar_header_titles ) {
		$output .= '
			.sidebar-widgets.widget-area .widget-title,
			.sidebar-widgets.widget-area .widget-title a,
			.woocommerce .shop_table thead th,
			#bbpress-forums li.bbp-header,
			#bbpress-forums .bbp-reply-content #favorite-toggle a,
			#bbpress-forums .bbp-reply-content #subscription-toggle a,
			#bbpress-forums #bbp-single-user-details #bbp-user-navigation li.current a,
			.publisher-posts-loop-widget .section-header.section_block .section-title,
			.publisher-posts-loop-widget .navigation.header-prev-next a,
			.publisher-posts-loop-widget .navigation.header-prev-next a.loading,
			.footer-widgets div.publisher-posts-loop-widget .navigation.header-prev-next a,
			.footer-widgets div.publisher-posts-loop-widget .navigation.header-prev-next a.loading,
			.publisher-headline.section_block h1
		';
		$output .= '{';
		$output .= 'color: ' . $sidebar_header_titles . ';';
		$output .= '}';
	}

	// Sidebar Header Text
	if ( $sidebar_header_text ) {
		$output .= '
			.article-footer .entry-meta,
			.article-footer .entry-meta a,
			.sidebar-widgets .section-description,
			.publisher-posts-loop-widget .section-header.section_block .section-description,
			.publisher-headline.section_block h3
		';
		$output .= '{';
		$output .= 'color: ' . $sidebar_header_text . ';';
		$output .= '}';

		$output .= '
			.article-footer .entry-meta li a:hover
		';
		$output .= '{';
		$output .= 'color: ' . publisher_color_adjust( $sidebar_header_text, 40 ) . ';';
		$output .= '}';
	}

	// Sidebar Text
	if ( $sidebar_text ) {
		$output .= '
			.sidebar-widgets .widget,
			.sidebar-widgets .widget_calendar table th,
			.sidebar-widgets .widget_calendar table td,
			.sidebar-widgets .publisher-posts-loop-widget[data-layout="mini-listing"] .posts-index .listing .entry-content,
			.sidebar-widgets .entry-meta,
			.sidebar-widgets .widget .publisher-posts-loop-widget .entry-meta a
		';
		$output .= '{';
		$output .= 'color: ' . $sidebar_text . ';'; // #9a9a9a
		$output .= '}';
	}

	// Sidebar Box Base
	if ( $sidebar_box_base ) {
		$output .= '
			.sidebar-widgets .widget,
			.sidebar-widgets .publisher-posts-loop-widget[data-layout="mini-listing"] .posts-index .listing,
			.sidebar-widgets .publisher-posts-loop-widget .posts-index .carousel-layout .site-main > .loader-icon,
			.sidebar-widgets .publisher-posts-loop-widget .posts-index .listing
		';
		$output .= '{';
		$output .= 'background: ' . $sidebar_box_base . ';';
		$output .= '}';

		$output .= '
			.secondary table thead,
			.secondary table tr:nth-child(even)
		';
		$output .= '{';
		$output .= 'background: ' . publisher_color_adjust( $sidebar_box_base, -5 ) . ';';
		$output .= '}';

		$output .= '
			.sidebar-widgets .publisher-posts-loop-widget[data-layout="mini-listing"] .posts-index .listing.has-post-thumbnail .featured-preview
		';
		$output .= '{';
		$output .= 'background-color: ' . publisher_color_adjust( $sidebar_box_base, -9 ) . ';'; // #e8e8e8
		$output .= '}';
	}

	// Sidebar Button Base
	if ( $sidebar_button_base ) {
		$output .= '
			#secondary .button,
			.secondary .button,
			.sidebar-widgets .widget .tagcloud a,
			.woocommerce .widget_price_filter .ui-slider .ui-slider-handle,
			.woocommerce .widget_price_filter .ui-slider .ui-slider-range
		';
		$output .= '{';
		$output .= 'background: ' . $sidebar_button_base . ';';
		$output .= '}';
	}

	// Sidebar Button Text
	if ( $sidebar_button_text ) {
		$output .= '
			#secondary .button,
			.secondary .button,
			.sidebar-widgets .widget .tagcloud a
		';
		$output .= '{';
		$output .= 'color: ' . $sidebar_button_text . ';';
		$output .= '}';
	}

	// Sidebar Button Base Active
	if ( $sidebar_button_base_active ) {
		$output .= '
			#secondary .button:hover,
			.secondary .button:hover,
			.sidebar-widgets .widget .tagcloud a:hover
		';
		$output .= '{';
		$output .= 'background: ' . $sidebar_button_base_active . ';';
		$output .= '}';
	}

	// Sidebar Button Text Active
	if ( $sidebar_button_text_active ) {
		$output .= '
			#secondary .button:hover,
			.secondary .button:hover,
			.sidebar-widgets .widget .tagcloud a:hover
		';
		$output .= '{';
		$output .= 'color: ' . $sidebar_button_text_active . ';';
		$output .= '}';
	}


	/**
	/*	Footer
	/*-------------------------------------------------*/

	$footer_accent = get_theme_mod( 'publisher_options_colors_footer_accent' );
	$footer_header = get_theme_mod( 'publisher_options_colors_footer_titles' );
	$footer_text = get_theme_mod( 'publisher_options_colors_footer_text' );
	$footer_base = get_theme_mod( 'publisher_options_colors_footer_base' );
	$footer_button_base = get_theme_mod( 'publisher_options_colors_footer_button_base' );
	$footer_button_text = get_theme_mod( 'publisher_options_colors_footer_button_text' );
	$footer_button_base_active = get_theme_mod( 'publisher_options_colors_footer_button_base_active' );
	$footer_button_text_active = get_theme_mod( 'publisher_options_colors_footer_button_text_active' );

	// Footer Accent
	if ( $footer_accent ) {
		$output .= '
			.footer-widgets .widget a,
			.footer-widgets .widget li:before,
			.footer-widgets .widget_calendar caption,
			.footnote a,
			.footer-widgets .publisher-headline h1.sow-title
		';
		$output .= '{';
		$output .= 'color: ' . $footer_accent . ';'; // #7a7a7a
		$output .= '}';

		// Light
		$output .= '
			.footer-widgets ul.product_list_widget li a,
			.footer-widgets .widget_text .textwidget a,
			.footer-widgets .widget_rss .rss-date,
			.footer-widgets .section-description,
			.footer-widgets .publisher-headline h3.sow-description,
			.footer-widgets .publisher-posts-loop-widget .posts-index .listing .entry-title a
		';
		$output .= '{';
		$output .= 'color: ' . publisher_color_adjust( $footer_accent, 22 ) . ';'; // #9a9a9a
		$output .= '}';

		// Lighter
		$output .= '
			.footer-widgets .widget a:hover,
			.footer-widgets .publisher-posts-loop-widget .posts-index .listing .entry-title a:hover,
			.footer-widgets .widget_rss li .rsswidget,
			.footer-widgets .widget_text .textwidget a:hover
		';
		$output .= '{';
		$output .= 'color: ' . publisher_color_adjust( $footer_accent, 42 ) . ';'; // #cccccc
		$output .= '}';

		// Lightest
		$output .= '
			.footnote a:hover,
			.footer-widgets .publisher-headline h1.sow-title
		';
		$output .= '{';
		$output .= 'color: ' . publisher_color_adjust( $footer_accent, 57 ) . ';'; // #f2f2f2
		$output .= '}';
	}

	// Footer Header
	if ( $footer_header ) {
		$output .= '
			.footer-widgets .widget .widget-title,
			.footer-widgets.widget-area .widget-title a,
			.footer-widgets .section-title,
			.footer-widgets .publisher-headline h1.sow-title
		';
		$output .= '{';
		$output .= 'color: ' . $footer_header . ';'; // #f2f2f2
		$output .= '}';

		$output .= '
			.footer-widgets .section-description
		';
		$output .= '{';
		$output .= 'border-color: ' . $footer_header . ';';
		$output .= '}';
	}

	// Footer Text
	if ( $footer_text ) {
		$output .= '
			.site-footer,
			.footer-widgets .woocommerce.widget_shopping_cart .total,
			.footer-widgets .widget_calendar table th,
			.footer-widgets .widget_calendar table td,
			.footer-widgets .widget ins,
			.footer-widgets .publisher-posts-loop-widget .posts-index .listing .entry-meta,
			.footer-widgets .publisher-posts-loop-widget .posts-index .listing .entry-meta.header a,
			.footer-widgets .posts-index .navigation
		';
		$output .= '{';
		$output .= 'color: ' . $footer_text . ';'; // #7a7a7a
		$output .= '}';

		$output .= '
			.footnote
		';
		$output .= '{';
		$output .= 'color: ' . publisher_color_adjust( $footer_text, -10, 'saturation' ) . ';'; // #484848
		$output .= '}';

		$output .= '
			.footer-widgets .section-description,
			.footer-widgets .publisher-headline h3.sow-description
		';
		$output .= '{';
		$output .= 'color: ' . publisher_color_adjust( $footer_text, 22, 'saturation' ) . ';'; // #9a9a9a
		$output .= '}';
	}

	// Footer Base
	if ( $footer_base ) {
		$output .= '
			.site-footer
		';
		$output .= '{';
		$output .= 'background: ' . $footer_base . ';';
		$output .= '}';

		// Darker
		$output .= '
			.site-footer-content table thead,
			.site-footer-content table tr:nth-child(even),
			.footnote
		';
		$output .= '{';
		$output .= 'background: ' . publisher_color_adjust( $footer_base, -10 ) . ';';
		$output .= '}';
	}

	// Footer Button Base
	if ( $footer_button_base ) {
		$output .= '
			#footer .site-footer-content .button,
			.footer-widgets .widget .tagcloud a,
			.footer-widgets .publisher-posts-loop-widget .navigation a
		';
		$output .= '{';
		$output .= 'background-color: ' . $footer_button_base . ';';
		$output .= '}';
	}

	// Footer Button Text
	if ( $footer_button_text ) {
		$output .= '
			#footer .site-footer-content .button,
			.footer-widgets .publisher-posts-loop-widget .navigation a,
			.footer-widgets .widget .tagcloud a
		';
		$output .= '{';
		$output .= 'color: ' . $footer_button_text . ';';
		$output .= '}';
	}

	// Footer Button Base Active
	if ( $footer_button_base_active ) {
		$output .= '
			#footer .site-footer-content .button:hover,
			.footer-widgets .publisher-posts-loop-widget .navigation a:hover,
			.footer-widgets .widget .tagcloud a:hover
		';
		$output .= '{';
		$output .= 'background-color: ' . $footer_button_base_active . ';';
		$output .= '}';
	}

	// Footer Button Text Active
	if ( $footer_button_text_active ) {
		$output .= '
			#footer .site-footer-content .button:hover,
			.footer-widgets .publisher-posts-loop-widget .navigation a:hover,
			.footer-widgets .widget_tag_cloud .tagcloud a:hover,
			.footer-widgets .woocommerce.widget_product_tag_cloud .tagcloud a:hover
		';
		$output .= '{';
		$output .= 'color: ' . $footer_button_text_active . ';';
		$output .= '}';
	}


	/**
	/*	Call To Action
	/*-------------------------------------------------*/

	$cta_color = get_theme_mod( 'publisher_options_cta_color' );
	$cta_bg_color = get_theme_mod( 'publisher_options_cta_bg_color' );

	// CTA Text
	if ( $cta_color ) {
		$output .= '
			#call-to-action a
		';
		$output .= '{';
		$output .= 'color: ' . $cta_color . ';';
		$output .= '}';

		// Border Color
		$output .= '
			#call-to-action .cta-button
		';
		$output .= '{';
		$output .= 'border-color: ' . $cta_color . ';';
		$output .= '}';
	}

	// CTA Background
	if ( $cta_bg_color ) {
		$output .= '
			#call-to-action
		';
		$output .= '{';
		$output .= 'background-color: ' . $cta_bg_color . ';';
		$output .= '}';
	}




	/*-----------------------------------------------------------------------------------*/
	/*	Images
	/*-----------------------------------------------------------------------------------*/

	/**
	 * Retina Logo
	 */
	$logo = get_theme_mod( 'publisher_options_logo' );
	$logo_retina = get_theme_mod( 'publisher_options_retina_logo' );

	if ( $logo && $logo_retina ) {
		$logo_attributes = wp_get_attachment_image_src( publisher_get_attachment_id_from_url( $logo ), 'full' );

		$output .= '.logo-image img {';
		$output .= 'max-width: ' . $logo_attributes[1]/2 . 'px';
		$output .= '}';
	}




	/*-----------------------------------------------------------------------------------*/
	/*	Output CSS
	/*-----------------------------------------------------------------------------------*/

	/**
	 * Display Customizer CSS
	 */
	if ( ! empty( $output ) ) {
		$output = '<style type="text/css">' . $output . '</style>';
		echo stripslashes( $output );
	}

}
add_action( 'wp_head', 'publisher_customizer_output_css' );
