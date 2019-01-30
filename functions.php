<?php
/**
 * Publisher Functions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link http://codex.wordpress.org/Theme_Development
 * @link http://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * @link http://codex.wordpress.org/Plugin_API
 *
 * @package Publisher
 * @since v1.0
 */


/*-----------------------------------------------------------------------------------*/
/*	Setup Theme Defaults
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'publisher_setup_theme' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function publisher_setup_theme() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Publisher, use a find and replace
	 * to change 'publisher' to the name of your theme in all the template files
	 *
	 * @link http://codex.wordpress.org/Translating_WordPress
	 */
	load_theme_textdomain( 'publisher', get_template_directory() . '/includes/languages' );

	/**
	 * Add styles to post editor
	 */
	add_editor_style( '/includes/css/publisher-editor.css' );

	/**
	 * Add title tag
	 */
	add_theme_support( 'title-tag' );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Enable support for Post Thumbnails on posts and pages
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 150, 150, true ); // Default Thumb
	add_image_size( 'publisher-post-thumb-standard', 790, 9999, false ); // Post Thumbnail
	add_image_size( 'publisher-post-thumb-large', 1198, 9999, false ); // Post Thumbnail Large
	add_image_size( 'publisher-post-thumb-medium', 600, 9999, false ); // Post Thumbnail Medium
	//add_image_size( 'publisher-post-thumb-small', 400, 9999, false ); // Post Thumbnail Small
	//add_image_size( 'publisher-post-thumb-square', 400, 400, true ); // Post Thumbnail Square

/**
 * Remove WordPress Emoji	 */
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

//removes meta name generator
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );
remove_action('wp_head', 'wp_generator'); 

	/**
	 * Register Navigation menu
	 */
	register_nav_menus( array(
		'publisher-menu-primary' => __( 'Primary Menu', 'publisher' ),
		'publisher-header-left'  => __( 'Header Left Menu', 'publisher' ),
		'publisher-header-right' => __( 'Header Right Menu', 'publisher' ),
		'publisher-menu-footer'  => __( 'Footer Menu', 'publisher' )
	) );

	/**
	 * Custom header feature
	 */
	add_theme_support( 'custom-header', apply_filters( 'publisher_filter_custom_header_args', array(
		'header-text' => false,
		'width'		  => 1920,
		'height'	  => 600,
		'flex-width'  => true,
		'flex-height' => true,
	) ) );

	/**
	 * Custom background feature
	 */
	add_theme_support( 'custom-background', apply_filters( 'publisher_filter_custom_background_args', array(
		'default-color' => 'f5f5f5',
		'default-image' => ''
	) ) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'gallery', 'link', 'image', 'quote', 'video', 'audio'
	) );

	/*
	 * Switch default core markup to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'comment-list',
		'search-form',
		'comment-form',
		'gallery',
	) );

}
endif; // publisher_setup_theme
add_action( 'after_setup_theme', 'publisher_setup_theme' );




/*-----------------------------------------------------------------------------------*/
/*	Enqueue Scripts / Styles
/*-----------------------------------------------------------------------------------*/

/**
 * Enqueue scripts and styles
 */
function publisher_enqueue_scripts() {

	/**
     * Main Stylesheet
     */
	wp_enqueue_style( 'publisher-style', get_stylesheet_uri() );

	/**
     * IE < 10 Stylesheet
     */
	wp_enqueue_style( 'publisher-style-ie', get_stylesheet_directory_uri() . '/includes/css/publisher-ie.css', array( 'publisher-style' ) );
	wp_style_add_data( 'publisher-style-ie', 'conditional', 'lte IE 9' );

	/**
     * Add RTL Stylesheet
     */
	if ( is_rtl() ) {
		wp_enqueue_style( 'publisher-style-rtl', get_template_directory_uri() . '/includes/css/publisher-rtl.css' );
	}

	/**
     * Animate
     */
	/*wp_enqueue_style( 'publisher-animate-style', get_template_directory_uri() . '/includes/css/animate.css', array(), '1.0.0', 'screen' );*/

	/**
     * Google Fonts
     */
	wp_enqueue_style( 'publisher-google-fonts', publisher_google_fonts(), array(), null );

	/**
     * FontAwesome
     */
	wp_enqueue_style( 'publisher-fontawesome-style', '//cdn.jsdelivr.net/fontawesome/4.6.3/css/font-awesome.min.css' );

	/**
     * Main Scripts
     */
	wp_enqueue_script( 'publisher-scripts-js', get_template_directory_uri() . '/includes/js/publisher-scripts.js', array( 'jquery' ), '1.0.0', true );

	/**
     * Fitvids
     */
	wp_enqueue_script( 'publisher-fitvids-js', get_template_directory_uri() . '/includes/js/jquery.fitvids.js', array( 'jquery' ), '1.0.3', true );

	/**
	 * Owl Carousel
	 */
	wp_register_style( 'publisher-owl-style', get_template_directory_uri() . '/includes/css/owl.carousel.css', array(), '2.4.4', 'screen' );
	wp_register_script( 'publisher-owl-js', get_stylesheet_directory_uri() . '/includes/js/owl.carousel.js', array( 'jquery' ), '2.4.4', true );

	if ( 'gallery' == get_post_format() && get_post_gallery() ) {
		wp_enqueue_style( 'publisher-owl-style' );
		wp_enqueue_script( 'publisher-owl-js' );
	}

	/**
     * Masonry
     */
	wp_enqueue_script( 'jquery-masonry' );

	/**
     * HoverIntent
     */
	wp_enqueue_script( 'hoverIntent' );

	/**
     * Load the comment reply script
     */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	/**
	 * Localize Scripts
	 */
	wp_localize_script( 'publisher-scripts-js', 'publisher_scripts_js_vars', array(
		'load_masonry' => publisher_cpt_options() ? in_array( get_option( 'publisher_options_' . get_post_type() . '_posts_layout' ), array( 'grid', 'cover' ) ) : in_array( get_option( 'publisher_options_posts_layout' ), array( 'grid', 'cover' ) ) && ! publisher_is_index( 'product' ),
		'is_rtl' => is_rtl(),
		'wp_is_mobile' => wp_is_mobile()
	) );

}
add_action( 'wp_enqueue_scripts', 'publisher_enqueue_scripts' );


/**
 * Enqueue admin scripts and styles
 */
function publisher_admin_enqueue_scripts( $hook_suffix ) {

	/**
     * Admin CSS
     */
    wp_enqueue_style( 'publisher-admin-style', get_template_directory_uri() . '/includes/css/publisher-admin.css' );

	/**
     * Google Fonts
     */
	wp_enqueue_style( 'publisher-google-fonts', publisher_google_fonts(), array(), null );

	/**
     * Enqueue Scripts
     */
	wp_enqueue_media();
	wp_enqueue_script( 'jquery-ui-sortable' );
	wp_enqueue_script( 'publisher-taxonomy-js', get_template_directory_uri() . '/includes/js/publisher-metabox.js', array( 'jquery', 'wp-color-picker' ), null, true );

}
add_action( 'admin_enqueue_scripts', 'publisher_admin_enqueue_scripts' );




/*-----------------------------------------------------------------------------------*/
/*	Content Width
/*-----------------------------------------------------------------------------------*/

/**
 * Set the content width in pixels based on the theme's design and stylesheet
 */
if ( ! isset( $content_width ) ) {
	$content_width = 1198;
}




/*-----------------------------------------------------------------------------------*/
/*	Register Widget Areas
/*-----------------------------------------------------------------------------------*/

/**
 * Register widget area
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function publisher_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'publisher' ),
		'id'            => 'sidebar-main',
		'description'   => __( 'Appears in the main content area when widgets are active.', 'publisher' ),
		'before_widget' => '<aside id="%1$s" class="sidebar-main widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer', 'publisher' ),
		'id'            => 'sidebar-footer',
		'description'   => __( 'Appears at the bottom of the site when widgets are active. Go to theme options to set the number of columns.', 'publisher' ),
		'before_widget' => '<aside id="%1$s" class="sidebar-footer widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );

}
add_action( 'widgets_init', 'publisher_widgets_init' );




/*-----------------------------------------------------------------------------------*/
/*	Register Fonts
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'publisher_google_fonts' ) ):
/**
 * Return the Google font stylesheet URL
 */
function publisher_google_fonts() {

	$fonts_url = '';

	/**
	 * Translators: If there are characters in your language that are not supported
	 * by this font, translate this to 'off'. Do not translate into your own language.
	 */
	$google_font_1 = _x( 'on', 'Heading font: on or off', 'publisher' );
	$google_font_2 = _x( 'on', 'Content font: on or off', 'publisher' );

	if ( 'off' !== $google_font_1 || 'off' !== $google_font_2 ) {
		$font_families = array();

		if ( 'off' !== $google_font_1 )
			$font_families[] = apply_filters( 'publisher_filter_google_heading_font', 'Oxygen:400,700' );

		if ( 'off' !== $google_font_2 )
			$font_families[] = apply_filters( 'publisher_filter_google_content_font', 'Source+Sans+Pro:400,700,400italic,700italic' );

		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);
		$fonts_url = add_query_arg( $query_args, esc_url( "//fonts.googleapis.com/css" ) );
	}

	return $fonts_url;
}
endif; // publisher_google_fonts




/*-----------------------------------------------------------------------------------*/
/*	Includes
/*-----------------------------------------------------------------------------------*/

/**
 * Add theme customizer options
 */
require( get_template_directory() . '/includes/admin/customizer.php' );


/**
 * Add custom metabox options
 */
require( get_template_directory() . '/includes/admin/metabox.php' );
require( get_template_directory() . '/includes/admin/metabox-taxonomy.php' );


/**
 * Custom functions specific to this theme
 */
require( get_template_directory() . '/includes/template-tags/template-tags.php' );
require( get_template_directory() . '/includes/template-tags/content-tags.php' );
require( get_template_directory() . '/includes/template-tags/content-image-tags.php' );
require( get_template_directory() . '/includes/template-tags/content-link-tags.php' );
require( get_template_directory() . '/includes/template-tags/content-none-tags.php' );
require( get_template_directory() . '/includes/template-tags/content-page-tags.php' );
require( get_template_directory() . '/includes/template-tags/content-quote-tags.php' );
require( get_template_directory() . '/includes/template-tags/content-single-tags.php' );
require( get_template_directory() . '/includes/template-tags/footer-tags.php' );
require( get_template_directory() . '/includes/template-tags/header-tags.php' );
require( get_template_directory() . '/includes/template-tags/index-tags.php' );
require( get_template_directory() . '/includes/template-tags/page-tags.php' );
require( get_template_directory() . '/includes/template-tags/single-tags.php' );




/*-----------------------------------------------------------------------------------*/
/*	Modules
/*-----------------------------------------------------------------------------------*/

/**
 * Load Likes
 */
require( get_template_directory() . '/includes/modules/publisher-likes/publisher-likes.php' );


/**
 * Load Shares
 */
require( get_template_directory() . '/includes/modules/publisher-shares/publisher-shares.php' );




/*-----------------------------------------------------------------------------------*/
/*	Plugins
/*-----------------------------------------------------------------------------------*/

/**
 * Load Site Origin Widgets Bundle compatibility file
 */
if ( class_exists( 'SiteOrigin_Widgets_Bundle' ) ) require( get_template_directory() . '/includes/plugins/so-widgets-bundle/so-widgets-bundle.php' );


/**
 * Load Ditty News Ticker compatibility file
 */
if ( function_exists( 'mtphr_dnt_activation' ) ) require( get_template_directory() . '/includes/plugins/dittynewsticker/dittynewsticker.php' );


/**
 * Load WooCommerce compatibility file
 */
if ( class_exists( 'woocommerce' ) ) require( get_template_directory() . '/includes/plugins/woocommerce/woocommerce.php' );


/**
 * Load Jetpack compatibility file
 */
if ( class_exists( 'Jetpack' ) ) require( get_template_directory() . '/includes/plugins/jetpack/jetpack.php' );


/**
 * Load BBpress compatibility file
 */
if ( class_exists( 'bbPress' ) ) require( get_template_directory() . '/includes/plugins/bbpress/bbpress.php' );


/**
 * Load Metaslider compatibility file
 */
if ( class_exists( 'MetaSliderPlugin' ) ) require( get_template_directory() . '/includes/plugins/metaslider/metaslider.php' );


/**
 * Load MaxMegaMenu compatibility file
 */
if ( class_exists( 'Mega_Menu' ) ) require( get_template_directory() . '/includes/plugins/maxmegamenu/maxmegamenu.php' );


/**
 * Load Wordpress Popular Posts compatibility file
 */
if ( class_exists( 'WordpressPopularPosts' ) ) require( get_template_directory() . '/includes/plugins/wordpress-popular-posts/wordpress-popular-posts.php' );



/**
* Load custom gravity form for website field
*/
// add the 'novalidate' setting to <form> tag
// stackoverflow.com/questions/3090369/
function my_novalidate($form_tag, $form) {

  // collect field types
  $types = array();
  foreach ( $form['fields'] as $field ) {
    $types[] = $field['type'];
  }

  // bail if form doesn't have a website field
  if ( ! in_array('website', $types) )
    return $form_tag; 

  // add the 'novalidate' setting to the website <form> element
  $pattern = "#method=\'post\'#i";
  $replacement = "method='post' novalidate";
  $form_tag = preg_replace($pattern, $replacement, $form_tag);

  return $form_tag;
}
add_filter('gform_form_tag','my_novalidate',10,2);

// add "http://" to website if protocol omitted
function my_protocol($form) {

  // loop through fields, taking action if website
  foreach ( $form['fields'] as $field ) {

  // skip if not a website field
  if ( 'website' != $field['type'] )
      continue;

  // retrieve website field value
  $value = RGFormsModel::get_field_value($field);

  // if there is no protocol, add "http://"
  // Recognizes ftp://, ftps://, http://, https://
  // stackoverflow.com/questions/2762061/
  if ( ! empty($value) && ! preg_match("~^(?:f|ht)tps?://~i", $value) ) {
    $value = "http://" . $value;

    // update value in the $_POST array
    $id = (string) $field['id'];
    $_POST['input_' . $id] = $value;
  }
  }

  return $form;
}
add_filter('gform_pre_validation','my_protocol');

//New dashboard styles starts
/*
function swc_dash() {
echo '<link rel="stylesheet" type="text/css" href="' . get_bloginfo('template_directory') . '/dash.css" />';
}
add_action('login_head', 'swc_dash');


function swc_login_logo() {
return get_bloginfo( 'url' );
}
add_filter( 'login_headerurl', 'swc_login_logo' );

function swc_login_logo_url_title() {
return 'SomeWhat Creative';
}
add_filter( 'login_headertitle', 'swc_login_logo_url_title' );

function swc_login_error_override()
{
    return 'Incorrect login details.';
}
add_filter('login_errors', 'swc_login_error_override');


function swc_login_redirect( $redirect_to, $request, $user )
{
global $user;
if( isset( $user->roles ) && is_array( $user->roles ) ) {
if( in_array( "administrator", $user->roles ) ) {
return $redirect_to;
} else {
return home_url();
}
}
else
{
return $redirect_to;
}
}
add_filter("login_redirect", "swc_login_redirect", 10, 3);

function swc_login_checked_remember_me() {
add_filter( 'login_footer', 'swc_remember_me_checked' );
}
add_action( 'init', 'swc_login_checked_remember_me' );

function swc_remember_me_checked() {
echo "<script>document.getElementById('rememberme').checked = true;</script>";
}
*/
//New dashboard styles end

//Somewhat custom cloudflare - been disabled

/*
add_action( 'send_headers', 'somewhat_remove_headers_for_cloudflare', 10000 );
function somewhat_remove_headers_for_cloudflare() {
    if ( is_admin() ) { 
        return;
    }
    header_remove('Pragma');
    header_remove('Expires');
    header_remove('Cache-Control');
    header('Cache-Control: max-age=3600'); // 1 hour
}*/

//Automatically set the image Title, Alt-Text, Caption & Description upon upload
add_action( 'add_attachment', 'my_set_image_meta_upon_image_upload' );
function my_set_image_meta_upon_image_upload( $post_ID ) {

	// Check if uploaded file is an image, else do nothing
	if ( wp_attachment_is_image( $post_ID ) ) {
		$my_image_title = get_post( $post_ID )->post_title;

		// Sanitize the title:  remove hyphens, underscores & extra spaces:
		$my_image_title = preg_replace( '%\s*[-_\s]+\s*%', ' ',  $my_image_title );

		// Sanitize the title:  capitalize first letter of every word (other letters lower case):
		$my_image_title = ucwords( strtolower( $my_image_title ) );

		// Create an array with the image meta (Title, Caption, Description) to be updated
		// Note:  comment out the Excerpt/Caption or Content/Description lines if not needed
		$my_image_meta = array(
			'ID'		=> $post_ID,			// Specify the image (ID) to be updated
			'post_title'	=> $my_image_title,		// Set image Title to sanitized title
			//'post_excerpt'	=> $my_image_title,		// Set image Caption (Excerpt) to sanitized title
			//'post_content'	=> $my_image_title,		// Set image Description (Content) to sanitized title
		);

		// Set the image Alt-Text
		update_post_meta( $post_ID, '_wp_attachment_image_alt', $my_image_title );

		// Set the image meta (e.g. Title, Excerpt, Content)
		wp_update_post( $my_image_meta );
	} 
}