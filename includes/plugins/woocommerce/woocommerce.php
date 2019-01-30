<?php
/**
 * Woocommerce Compatibility File
 * See: http://www.woothemes.com/woocommerce/
 *
 * @package Publisher
 */

/*-----------------------------------------------------------------------------------*/
/*	Setup WooCommerce Defaults
/*-----------------------------------------------------------------------------------*/

/**
 * Add WooCommerce support
 */
if ( ! function_exists( 'publisher_woocommerce_setup' ) ) :
function publisher_woocommerce_setup() {
	add_theme_support( 'woocommerce' );
}
endif; // publisher_woocommerce_setup
add_action( 'after_setup_theme', 'publisher_woocommerce_setup' );


/**
 * Disable default WooCommerce CSS
 */
add_filter( 'woocommerce_enqueue_styles', '__return_false' );


/**
 * Define image sizes
 */
if ( ! function_exists( 'publisher_woocommerce_get_image_sizes' ) ) :
function publisher_woocommerce_get_image_sizes() {
	global $pagenow;

	if ( ! isset( $_GET['activated'] ) || $pagenow != 'themes.php' ) {
		return;
	}

  	$catalog = array(
		'width' 	=> '456',	// px
		'height'	=> '610',	// px
		'crop'		=> 1 		// true
	);

	$single = array(
		'width' 	=> '728',	// px
		'height'	=> '728',	// px
		'crop'		=> 1 		// true
	);

	$thumbnail = array(
		'width' 	=> '150',	// px
		'height'	=> '150',	// px
		'crop'		=> 0 		// false
	);

	// Image sizes
	update_option( 'shop_catalog_image_size', $catalog ); 		// Product category thumbs
	update_option( 'shop_single_image_size', $single ); 		// Single product image
	update_option( 'shop_thumbnail_image_size', $thumbnail ); 	// Image gallery thumbs
}
endif; // publisher_woocommerce_get_image_sizes
add_action( 'after_switch_theme', 'publisher_woocommerce_get_image_sizes', 1 );


/**
 * Number of products on shop page
 */
if ( ! function_exists( 'publisher_woocommerce_loop_shop_per_page' ) ) :
function publisher_woocommerce_loop_shop_per_page( $option ) {
	$products_per_page = get_option( 'publisher_options_products_posts_per_page' );
	return ( '' != $products_per_page ) ? intval( $products_per_page ) : $option;
}
endif; // publisher_woocommerce_loop_shop_per_page
add_filter( 'loop_shop_per_page', 'publisher_woocommerce_loop_shop_per_page', 20 );




/*-----------------------------------------------------------------------------------*/
/*	Theme Functions
/*-----------------------------------------------------------------------------------*/

/**
 * Register widget area
 */
if ( ! function_exists( 'publisher_woocommerce_widgets_init' ) ) :
function publisher_woocommerce_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Shop Sidebar', 'publisher' ),
		'id'            => 'sidebar-shop',
		'description'   => __( 'Appears in the shop content area when widgets are active.', 'publisher' ),
		'before_widget' => '<aside id="%1$s" class="sidebar-main widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );
}
endif; // publisher_woocommerce_widgets_init
add_action( 'widgets_init', 'publisher_woocommerce_widgets_init' );


/**
 * Theme Customizer Options
 */
function publisher_woocommerce_customize_register( $wp_customize ) {

	/*-----------------------------------------------------------------------------------*/
	/*	Shop
	/*-----------------------------------------------------------------------------------*/

	$wp_customize->add_section( 'publisher_section_shop', array(
		'title'				=> __( 'Shop', 'publisher' ),
		'panel'				=> 'publisher_panel_theme_section'
	) );

	// Shop Page Content
	$wp_customize->add_setting( 'publisher_options_products_shop_content', array(
		'default'			=> 'standard',
		'type'				=> 'option',
		'sanitize_callback' => 'publisher_sanitize_products_shop_content',
	) );

	$wp_customize->add_control( 'publisher_options_products_shop_content', array(
		'label'    			=> __( 'Shop Page Content', 'publisher' ),
		'section'  			=> 'publisher_section_shop',
		'settings' 			=> 'publisher_options_products_shop_content',
		'type'     			=> 'select',
		'description'  		=> __( 'Set the shop page content location', 'publisher' ),
		'choices'  			=> array(
			'standard' => __( 'Column', 'publisher' ),
			'banner'   => __( 'Full Width', 'publisher' )
		),
	) );

	// Show only on main page
	$wp_customize->add_setting( 'publisher_options_products_shop_content_main', array(
		'default' 			=> 0,
		'sanitize_callback' => 'publisher_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'publisher_options_products_shop_content_main', array(
		'type'    			=> 'checkbox',
		'label'   			=> __( 'Show only on main page', 'publisher' ),
		'section' 			=> 'publisher_section_shop',
	) );

	// Shop Page Columns
	$wp_customize->add_setting( 'publisher_options_products_post_columns', array(
		'default'			=> 3,
		'type'				=> 'option',
		'sanitize_callback' => 'publisher_sanitize_integer',
	) );

	$wp_customize->add_control( 'publisher_options_products_post_columns', array(
		'label'    			=> __( 'Shop Page Columns', 'publisher' ),
		'section'  			=> 'publisher_section_shop',
		'settings' 			=> 'publisher_options_products_post_columns',
		'type'     			=> 'select',
		'description'  		=> __( 'Set the amount of columns to show', 'publisher' ),
		'choices'  			=> array(
			1 => __( '1', 'publisher' ),
			2 => __( '2', 'publisher' ),
			3 => __( '3', 'publisher' ),
			4 => __( '4', 'publisher' ),
			5 => __( '5', 'publisher' )
		),
	) );

	// Products Per Page
	$wp_customize->add_setting( 'publisher_options_products_posts_per_page', array(
		'type'				=> 'option',
		'sanitize_callback' => 'publisher_sanitize_text',
	) );

	$wp_customize->add_control( 'publisher_options_products_posts_per_page', array(
		'label'        		=> __( 'Products Per Page', 'publisher' ),
		'section'      		=> 'publisher_section_shop',
		'settings'     		=> 'publisher_options_products_posts_per_page',
		'type'         		=> 'text',
		'description'  		=> __( 'Number of products to show in the shop page. If nothing, will use Wordpress settings.', 'publisher' ),
	) );

	// Shop Page Sidebar
	$wp_customize->add_setting( 'publisher_options_products_sidebar_display', array(
		'default'			=> 'show',
		'type'				=> 'option',
		'sanitize_callback' => 'publisher_sanitize_show_hide',
	) );

	$wp_customize->add_control( 'publisher_options_products_sidebar_display', array(
		'label'    			=> __( 'Shop Page Sidebar', 'publisher' ),
		'section'  			=> 'publisher_section_shop',
		'settings' 			=> 'publisher_options_products_sidebar_display',
		'type'     			=> 'select',
		'description'  		=> __( 'Set the default shop page sidebar layout', 'publisher' ),
		'choices'  			=> array(
			'show' => __( 'Show', 'publisher' ),
			'hide' => __( 'Hide', 'publisher' ),
		),
	) );

	// Product Sidebar
	$wp_customize->add_setting( 'publisher_options_product_sidebar_display', array(
		'default'			=> 'show',
		'type'				=> 'option',
		'sanitize_callback' => 'publisher_sanitize_show_hide',
	) );

	$wp_customize->add_control( 'publisher_options_product_sidebar_display', array(
		'label'    			=> __( 'Product Sidebar', 'publisher' ),
		'section'  			=> 'publisher_section_shop',
		'settings' 			=> 'publisher_options_product_sidebar_display',
		'type'     			=> 'select',
		'description'  		=> __( 'Set the default product sidebar layout', 'publisher' ),
		'choices'  			=> array(
			'show' => __( 'Show', 'publisher' ),
			'hide' => __( 'Hide', 'publisher' ),
		),
	) );

	// Product Details
	$wp_customize->add_setting( 'publisher_options_product_details', array(
		'capability'   		=> 'edit_theme_options',
		'sanitize_callback' => 'publisher_sanitize_product_details',
	) );

	$wp_customize->add_control( new Custom_Multi_Select_Control( $wp_customize, 'publisher_options_product_details', array(
		'label'        		=> __( 'Product Details', 'publisher' ),
		'section'      		=> 'publisher_section_shop',
		'settings'     		=> 'publisher_options_product_details',
		'type'     			=> 'multiple-select',
		'description'  		=> __( 'Hide the following product details', 'publisher' ),
		'choices'			=> array(
			''			  		=> __( '---', 'publisher' ),
			'meta-info'	  		=> __( 'Meta Info', 'publisher' ),
			'views'		  		=> __( 'Views', 'publisher' ),
			'likes'		  		=> __( 'Likes', 'publisher' ),
			'shares'	  		=> __( 'Shares', 'publisher' ),
		),
	) )	);

	// Shopping Cart
	$wp_customize->add_setting( 'publisher_options_menus_cart', array(
		'default'			=> 'show',
		'type'				=> 'option',
		'sanitize_callback' => 'publisher_sanitize_show_hide',
	) );

	$wp_customize->add_control( 'publisher_options_menus_cart', array(
		'label'    			=> __( 'Shopping Cart', 'publisher' ),
		'section'  			=> 'publisher_section_shop',
		'settings' 			=> 'publisher_options_menus_cart',
		'type'     			=> 'select',
		'description'  		=> __( 'Show shopping cart in the menu bar', 'publisher' ),
		'choices'			=> array(
			'show' => __( 'Show', 'publisher' ),
			'hide' => __( 'Hide', 'publisher' ),
		),
	) );

}
add_action( 'customize_register', 'publisher_woocommerce_customize_register' );


/**
 * Adds custom classes to the array of post classes
 */
function publisher_woocommerce_post_class( $classes ) {

	// Add hentry to products
	if ( 'product' == get_post_type() ) {
		$classes[] = 'hentry';
	}

	return $classes;

}
add_filter( 'post_class', 'publisher_woocommerce_post_class' );


/**
 * Get Archive Title
 */
function publisher_woocommerce_get_the_archive_title( $title ) {

	if ( is_shop() && ! is_search() && ! is_front_page() ) {
		$shop_page = get_post( get_option( 'woocommerce_shop_page_id' ) );
		$title = $shop_page->post_title;
	}

	return $title;

}
add_filter( 'get_the_archive_title', 'publisher_woocommerce_get_the_archive_title' );


/**
 * Get Archive Description
 */
function publisher_woocommerce_get_the_archive_description( $description ) {

	if ( is_shop() && ! is_search() ) {
		$shop_description = get_post_meta( get_option( 'woocommerce_shop_page_id' ), 'publisher_page_subtitles_meta', true );
		$description = $shop_description ? sprintf( '<p>%1$s</p>', $shop_description ) : '';
	} elseif ( wc_get_page_id( 'cart' ) == get_the_ID() ) {
		$cart_description = get_post_meta( get_the_ID(), 'publisher_page_subtitles_meta', true );
		$cart_number = WC()->cart->get_cart_contents_count();
		$description = $cart_description ? sprintf( '<p>%1$s</p>', $cart_description ) : sprintf( '<p>' . _nx( 'You have %s item in your cart', 'You have %s items in your cart', $cart_number, '%s: number of items in shopping cart', 'publisher' ) . '</p>', $cart_number );
	}

	return $description;

}
add_filter( 'get_the_archive_description', 'publisher_woocommerce_get_the_archive_description' );


/**
 * Use shop sidebar
 */
if ( ! function_exists( 'publisher_woocommerce_use_shop_sidebar' ) ) :
function publisher_woocommerce_use_shop_sidebar( $sidebar ) {

	if ( is_woocommerce() ) {
		return 'sidebar-shop';
	}

	return $sidebar;

}
endif; // publisher_woocommerce_use_shop_sidebar
add_filter( 'publisher_filter_sidebar', 'publisher_woocommerce_use_shop_sidebar' );


/**
 * Show shop sidebar
 */
if ( ! function_exists( 'publisher_woocommerce_has_sidebar' ) ) :
function publisher_woocommerce_has_sidebar( $retval ) {

	if ( is_woocommerce() ) {
		$sidebar_layout = '';
		if ( is_shop() || is_product_category() || is_product_tag() ) {
			$sidebar_layout = get_option( 'publisher_options_products_sidebar_display' );
		} else {
			$sidebar_layout = get_option( 'publisher_options_product_sidebar_display' );
		}

		// If set to hide, return false
		if ( 'hide' == $sidebar_layout ) {
			return false;
		}
	}

	return $retval;

}
endif; // publisher_woocommerce_has_sidebar
add_filter( 'publisher_filter_has_sidebar', 'publisher_woocommerce_has_sidebar' );


/**
 * Set grid layout
 */
if ( ! function_exists( 'publisher_woocommerce_primary_classes' ) ):
function publisher_woocommerce_primary_classes( $classes ) {

	if ( is_shop() || is_product_category() || is_product_tag() ) {

		// Posts Page Grid Columns
		$post_columns_number = get_option( 'publisher_options_products_post_columns' );
		if ( '' != $post_columns_number ) {
			$classes[] = 'columns-' . $post_columns_number;
		}

	}

	return $classes;

}
endif; // publisher_woocommerce_primary_classes
add_filter( 'publisher_filter_primary_classes', 'publisher_woocommerce_primary_classes' );


/**
 * Get Header Image
 */
function publisher_woocommerce_get_header_image( $bg_image_url ) {

	if ( is_shop() ) {
		$shop_page_id = get_option( 'woocommerce_shop_page_id' );
		$get_bg_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $shop_page_id ), 'full', false, '' );
		$bg_image_url = esc_url( $get_bg_image_url[0] );
	}

	return $bg_image_url;

}
add_filter( 'publisher_filter_header_image', 'publisher_woocommerce_get_header_image' );


/**
 * Set has category
 */
function publisher_woocommerce_has_category_activate( $activate_options ) {

	if ( 'product' == get_post_type() ) {
		return true;
	}

	return $activate_options;

}
add_filter( 'publisher_filter_has_category_activate', 'publisher_woocommerce_has_category_activate' );


/**
 * Set category select
 */
function publisher_woocommerce_get_the_category_select( $cat_select ) {

	if ( 'product' == get_post_type() ) {
		return 'product_cat';
	}

	return $cat_select;

}
add_filter( 'publisher_filter_get_the_category_select', 'publisher_woocommerce_get_the_category_select' );




/*-----------------------------------------------------------------------------------*/
/*	Woocommerce Actions / Filters
/*-----------------------------------------------------------------------------------*/

/**
 * Remove the shop page title
 */
add_filter( 'woocommerce_show_page_title', '__return_false' );


/**
 * Move the shop breadcrumbs, result count, catalog ordering
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb',	20, 0 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

add_action( 'publisher_action_woocommerce_single_above_primary', 'woocommerce_breadcrumb', 10 );
add_action( 'publisher_woocommerce_necktie', 'woocommerce_result_count', 10 );
add_action( 'publisher_woocommerce_necktie', 'woocommerce_catalog_ordering', 30 );


/**
 * Modify the breadcrumbs
 */
function publisher_woocommerce_breadcrumb_defaults( $args ) {
	if ( is_array( $args ) ) {
		$args['delimiter'] = '<span> &#47; </span>';
	}
	return $args;
}
add_filter( 'woocommerce_breadcrumb_defaults', 'publisher_woocommerce_breadcrumb_defaults' );


/**
 * Modify the out of stock read more
 */
function publisher_woocommerce_product_add_to_cart_text( $text, $status ) {

	if ( 'outofstock' == $status->stock_status ) {
		$text = __( 'Out of Stock', 'publisher' );
	}

	return $text;

}
add_filter( 'woocommerce_product_add_to_cart_text', 'publisher_woocommerce_product_add_to_cart_text', 10, 2 );




/*-----------------------------------------------------------------------------------*/
/*	Woocommerce Templates
/*-----------------------------------------------------------------------------------*/

/**
 * Before Shop Main Content
 */
if ( ! function_exists( 'publisher_woocommerce_before_main_content' ) ) :
function publisher_woocommerce_before_main_content() { ?>

	<?php
		/**
		 * @hooked publisher_woocommerce_above_primary - 10
		 */
		do_action( 'publisher_action_woocommerce_above_primary' );
	?>

	<div id="primary" class="<?php publisher_primary_classes( 'primary content-area' ) ?>">

		<?php
			/**
			 * @hooked publisher_woocommerce_above_site_main - 10
			 */
			do_action( 'publisher_action_woocommerce_above_site_main' );
		?>

		<main id="main" class="site-main publisher-shop-main">

<?php
}
endif; // publisher_woocommerce_before_main_content
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
add_action( 'woocommerce_before_main_content', 'publisher_woocommerce_before_main_content', 10 );


/**
 * After Shop Main Content
 */
if ( ! function_exists( 'publisher_woocommerce_after_main_content' ) ) :
function publisher_woocommerce_after_main_content() { ?>

		</main><!-- #main -->

		<?php do_action( 'publisher_action_woocommerce_below_site_main' ); ?>

	</div><!-- #primary -->

	<?php do_action( 'publisher_action_woocommerce_below_primary' ); ?>

<?php
}
endif; // publisher_woocommerce_after_main_content
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
add_action( 'woocommerce_after_main_content', 'publisher_woocommerce_after_main_content', 10 );


/**
 * Before Shop Item Title
 */
if ( ! function_exists( 'publisher_woocommerce_open_article_wrap' ) ) :
function publisher_woocommerce_open_article_wrap() { ?>

	<div class="article-wrap">

		<?php
			/**
			 * @hooked publisher_woocommerce_featured_preview - 10
			 */
			do_action( 'publisher_action_woocommerce_above_entry_wrap' );
		?>

<?php
}
endif; // publisher_woocommerce_open_article_wrap
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
add_action( 'woocommerce_before_shop_loop_item', 'publisher_woocommerce_open_article_wrap', 10 );


/**
 * Before Shop Item
 */
if ( ! function_exists( 'publisher_woocommerce_open_entry_wrap' ) ) :
function publisher_woocommerce_open_entry_wrap() { ?>

		<div class="entry-wrap">
			<header class="entry-header">

<?php
}
endif; // publisher_woocommerce_open_entry_wrap
add_action( 'woocommerce_before_shop_loop_item', 'publisher_woocommerce_open_entry_wrap', 20 );
add_action( 'woocommerce_before_subcategory', 'publisher_woocommerce_open_entry_wrap', 20 );


/**
 * After Shop Item
 */
if ( ! function_exists( 'publisher_woocommerce_close_entry_wrap' ) ) :
function publisher_woocommerce_close_entry_wrap( $category ) { ?>

			</header><!-- .entry-header -->

			<?php
				/**
				 * @hooked publisher_woocommerce_entry_content - 10
				 * @hooked publisher_woocommerce_entry_footer - 20
				 */
				do_action( 'publisher_action_woocommerce_entry_wrap', $category );
			?>

		</div><!-- .entry-wrap -->

		<?php do_action( 'publisher_action_woocommerce_below_entry_wrap' ); ?>

	</div><!-- .article-wrap -->

<?php
}
endif; // publisher_woocommerce_close_entry_wrap
add_action( 'woocommerce_after_shop_loop_item', 'publisher_woocommerce_close_entry_wrap', 20 );
add_action( 'woocommerce_after_subcategory', 'publisher_woocommerce_close_entry_wrap', 20 );




/*-----------------------------------------------------------------------------------*/
/*	Product Loop
/*-----------------------------------------------------------------------------------*/

/**
 * Move sale flash / Out of stock
 */
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
add_action( 'publisher_action_woocommerce_featured_preview', 'woocommerce_show_product_loop_sale_flash', 15 );
add_action( 'publisher_action_woocommerce_featured_preview', 'publisher_woocommerce_show_product_loop_out_of_stock_flash', 15 );


/**
 * Modify sale flash
 */
function publisher_woocommerce_sale_flash() {
	$sales = sprintf( '<div class="post-flash-icon"><a href="%1$s" class="status-flash status-onsale" title="%2$s"><span class="onsale"></span></a></div>',
		( is_single() && has_post_thumbnail() ) ? wp_get_attachment_url( get_post_thumbnail_id() ) : get_the_permalink(),
		__( 'Sale', 'publisher' )
	);
	return $sales;
}
add_filter( 'woocommerce_sale_flash', 'publisher_woocommerce_sale_flash' );


/**
 * Create out of stock flash
 */
if ( ! function_exists( 'publisher_woocommerce_show_product_loop_out_of_stock_flash' ) ) :
function publisher_woocommerce_show_product_loop_out_of_stock_flash() {
	global $product;
	if ( ! $product->is_in_stock() ) {

		$out_of_stock = sprintf( '<div class="post-flash-icon"><a href="%1$s" class="status-flash status-out-of-stock" title="%2$s"><span class="out-of-stock"></span></a></div>',
			( is_single() && has_post_thumbnail() ) ? esc_url( wp_get_attachment_url( get_post_thumbnail_id() ) ) : esc_url( get_the_permalink() ),
			__( 'Out of Stock', 'publisher' )
		);

		echo $out_of_stock;
	}
};
endif; // publisher_woocommerce_show_product_loop_out_of_stock_flash


/**
 * Move rating
 */
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_rating', 5 );


/**
 * Move price
 */
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_price', 10 );


/**
 * Move add to cart
 */
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
add_action( 'publisher_woocommerce_entry_footer_inside', 'woocommerce_template_loop_add_to_cart', 10 );


/**
 * New Add To Cart Loader Icon
 */
function publisher_woocommerce_loop_add_to_cart_link( $cart_link, $product ) {
	if ( $product->is_purchasable() && $product->is_in_stock() ) {
		return sprintf( '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" data-quantity="%s" class="button %s product_type_%s">%s<span>%s</span></a>',
			esc_url( $product->add_to_cart_url() ),
			esc_attr( $product->id ),
			esc_attr( $product->get_sku() ),
			esc_attr( isset( $quantity ) ? $quantity : 1 ),
			$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
			esc_attr( $product->product_type ),
			'<div class="woo-loader-icon"><div class="loader"></div></div>',
			esc_html( $product->add_to_cart_text() )
		);
	}
	return $cart_link;
}
add_filter( 'woocommerce_loop_add_to_cart_link', 'publisher_woocommerce_loop_add_to_cart_link', 10, 2 );


/**
 * Add Category
 */
function publisher_woocommerce_taxonomy_category() {
	if ( publisher_has_category() ) {
		printf( '<ul class="entry-meta header">%s</ul><!-- .entry-meta -->',
			publisher_get_the_categories( '<li class="meta-category">', '</li>' )
		);
	}
}
add_action( 'woocommerce_before_shop_loop_item', 'publisher_woocommerce_taxonomy_category', 25 );




/*-----------------------------------------------------------------------------------*/
/*	Product Category / Product Subcategory / Product Tag
/*-----------------------------------------------------------------------------------*/

/**
 * Remove the subcategory thumbnail
 */
remove_action( 'woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail', 10 );


/**
 * Remove default archive descriptions location
 */
remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );
remove_action( 'woocommerce_archive_description', 'woocommerce_product_archive_description', 10 );


/**
 * Add the modified subcategory thumbnail
 */
if ( ! function_exists( 'publisher_woocommerce_subcategory_thumbnail' ) ) :
function publisher_woocommerce_subcategory_thumbnail( $category ) {
	$thumbnail_id = get_woocommerce_term_meta( $category->term_id, 'thumbnail_id', true  );	?>

	<div class="article-wrap">
		<?php if ( $thumbnail_id ) { ?>

			<div class="featured-preview">
				<?php
					$image_size = publisher_get_the_image_size( '', '', '', 'publisher-post-thumb-standard' );
					$image = wp_get_attachment_image_src( $thumbnail_id, $image_size );

					// Get the featured image
					printf( '<figure class="featured-image"><a href="%1$s"><img src="%2$s" alt="%3$s" /></a></figure>',
						esc_url( get_term_link( $category->slug, 'product_cat' ) ),
						esc_url( $image[0] ),
						esc_attr( $category->name )
					);
				?>
			</div><!-- .featured-preview -->
			<?php
		}
	// .article-wrap closes in publisher_woocommerce_close_entry_wrap
}
endif; // publisher_woocommerce_subcategory_thumbnail
add_action( 'woocommerce_before_subcategory', 'publisher_woocommerce_subcategory_thumbnail', 10 );




/*-----------------------------------------------------------------------------------*/
/*	Single Product
/*-----------------------------------------------------------------------------------*/

/**
 * Move sales flash / Out of stock
 */
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
add_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_sale_flash', 10 );
add_action( 'woocommerce_product_thumbnails', 'publisher_woocommerce_show_product_loop_out_of_stock_flash', 10 );


/**
 * Move single product meta
 */
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );


/**
 * Open Wrap Summary
 */
if ( ! function_exists( 'publisher_woocommerce_after_single_product_summary_wrap_open' ) ) :
function publisher_woocommerce_after_single_product_summary_wrap_open() { ?>
	<div class="article-wrap main-item">
		<div class="summary-wrap clearfix">
<?php
}
endif; // publisher_woocommerce_after_single_product_summary_wrap_open
add_action( 'woocommerce_before_single_product_summary', 'publisher_woocommerce_after_single_product_summary_wrap_open', 2 );


/**
 * Close Wrap Summary
 */
if ( ! function_exists( 'publisher_woocommerce_after_single_product_summary_wrap_close' ) ) :
function publisher_woocommerce_after_single_product_summary_wrap_close() {

	$product_details_mod = get_theme_mod( 'publisher_options_product_details' );
	$product_details = $product_details_mod ? $product_details_mod : array();

	$hide_meta_info = in_array( 'meta-info' , $product_details );
	$hide_views = in_array( 'views' , $product_details );
	$hide_likes = in_array( 'likes' , $product_details );
	$hide_shares = in_array( 'shares' , $product_details );
	?>

		</div><!-- .summary-wrap -->

		<div class="article-footer">
			<?php if ( $hide_meta_info && $hide_views && $hide_likes && $hide_shares ) { } else { ?>
				<ul class="entry-meta footer">
					<?php
						// Read Time
						if ( ! $hide_meta_info ) {
							echo '<li class="meta-product-meta">';
							woocommerce_template_single_meta();
							echo '</li>';
						}

						// View Count via Wordpress Popular Posts
						if ( function_exists( 'publisher_wordpress_popular_poosts_get_views' ) && ! $hide_views ) {
							echo publisher_wordpress_popular_poosts_get_views( '<li class="meta-post-views">', '</li>', '<i class="fa fa-eye"></i><span class="publisher-views-count">', '</span>' );
						};

						// Pluggable: Likes
						if ( function_exists( 'publisher_get_likes' ) && ! $hide_likes ) {
							echo publisher_get_likes( '<li class="meta-post-likes">', '</li>', '<i class="fa fa-heart"></i>' );
						};

						// Pluggable: Shares
						if ( function_exists( 'publisher_get_shares' ) && ! $hide_shares ) {
							echo publisher_get_shares( '<li class="meta-post-shares">', '</li>', '<i class="fa fa-share"></i>' );
						};
					?>
				</ul><!-- .entry-meta -->
			<?php } ?>
		</div><!-- .article-footer -->

	</div><!-- .article-wrap -->

<?php
}
endif; // publisher_woocommerce_after_single_product_summary_wrap_close
add_action( 'woocommerce_after_single_product_summary', 'publisher_woocommerce_after_single_product_summary_wrap_close', 2 );


/**
 * Add product category above titles
 */
if ( ! function_exists( 'publisher_woocommerce_single_product_category' ) ) :
function publisher_woocommerce_single_product_category() { ?>

	<ul class="entry-meta">
		<?php
			// Category
			if ( publisher_has_category() ) {
				echo publisher_get_the_categories( '<li class="meta-category">', '</li>' );
			};
		?>
	</ul><!-- .entry-meta -->

<?php
}
endif; // publisher_woocommerce_single_product_category
add_action( 'woocommerce_single_product_summary', 'publisher_woocommerce_single_product_category', 4 );


/**
 * Related Products
 */
if ( ! function_exists( 'publisher_woocommerce_output_related_products_args' ) ) :
function publisher_woocommerce_output_related_products_args( $args ) {
	$args = array(
		'posts_per_page' => 3,
		'columns'        => 3,
	);
	return $args;
}
endif; // publisher_woocommerce_output_related_products_args
add_filter( 'woocommerce_output_related_products_args', 'publisher_woocommerce_output_related_products_args' );




/*-----------------------------------------------------------------------------------*/
/*	Cart
/*-----------------------------------------------------------------------------------*/

/**
 * You May Be Interested In
 */
if ( ! function_exists( 'publisher_woocommerce_cross_sells_total' ) ) :
function publisher_woocommerce_cross_sells_total( $posts_per_page ) {
	return 2;
}
endif; // publisher_woocommerce_cross_sells_total
add_filter( 'woocommerce_cross_sells_total', 'publisher_woocommerce_cross_sells_total' );




/*-----------------------------------------------------------------------------------*/
/*	WooCommerce Header Shop Functions
/*-----------------------------------------------------------------------------------*/

/**
 * Add header cart to additional menu items
 */
if ( ! function_exists( 'publisher_woocommerce_additional_menu_items_cart' ) ) :
function publisher_woocommerce_additional_menu_items_cart() {
	$cart = get_option( 'publisher_options_menus_cart' );

	if ( 'hide' != $cart ) { ?>
		<li class="menu-item menu-item-cart">
			<a href="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" class="cart-link" title="<?php _e( 'View your shopping cart', 'publisher' ) ?>">
				<?php
					$item_count = WC()->cart->get_cart_contents_count();

					printf( '<i class="fa fa-shopping-cart"></i>%1$s',
						0 == $item_count ? '' : '<span class="count">' . $item_count . '</span>'
					);
				?>
			</a>
		</li>
	<?php
	}

}
endif; // publisher_nav_menu_items
add_action( 'publisher_action_additional_menu_items', 'publisher_woocommerce_additional_menu_items_cart', 5 );


/**
 * Cart Fragments
 *
 * Ensure cart contents update when products are added to the cart via AJAX
 * @param  array $fragments Fragments to refresh via AJAX
 * @return array            Fragments to refresh via AJAX
 */
if ( ! function_exists( 'publisher_woocommerce_add_to_cart_fragments' ) ) :
function publisher_woocommerce_add_to_cart_fragments( $fragments ) {
	global $woocommerce;
	ob_start();
	publisher_woocommerce_additional_menu_items_cart();
	$fragments['li.menu-item-cart'] = ob_get_clean();
	return $fragments;
}
endif; // publisher_woocommerce_add_to_cart_fragments
add_filter( 'add_to_cart_fragments', 'publisher_woocommerce_add_to_cart_fragments' );




/*-----------------------------------------------------------------------------------*/
/*	Template Actions
/*-----------------------------------------------------------------------------------*/

/**
 * Above Primary
 *
 * @action publisher_action_woocommerce_above_primary
 */
if ( ! function_exists( 'publisher_woocommerce_above_primary' ) ) :
function publisher_woocommerce_above_primary() {

	if ( is_shop() ) {
		$shop_page_id = get_option( 'woocommerce_shop_page_id' );
		$page_layout_option = get_option( 'publisher_options_products_shop_content' );
		$post_layout_meta = get_post_meta( $shop_page_id, 'publisher_post_layout_meta', true );
		$post_layout = $post_layout_meta ? $post_layout_meta : $page_layout_option;

		switch( $post_layout ) {
			case 'cover' :
			case 'wide' :
			case 'banner' :
				publisher_woocommerce_content( $shop_page_id );
				break;

			default :
				break;

		};

	} elseif ( is_single() ) {
		/**
		 * @hooked woocommerce_breadcrumb - 10
		 */
		do_action( 'publisher_action_woocommerce_single_above_primary' );
	}

}
endif; // publisher_woocommerce_above_primary
add_action( 'publisher_action_woocommerce_above_primary', 'publisher_woocommerce_above_primary', 10 );


/**
 * Above Site Main
 *
 * @action publisher_action_woocommerce_above_site_main
 */
if ( ! function_exists( 'publisher_woocommerce_above_site_main' ) ) :
function publisher_woocommerce_above_site_main() {

	if ( is_shop() ) {
		$shop_page_id = get_option( 'woocommerce_shop_page_id' );
		$page_layout_option = get_option( 'publisher_options_products_shop_content' );
		$post_layout_meta = get_post_meta( $shop_page_id, 'publisher_post_layout_meta', true );
		$post_layout = $post_layout_meta ? $post_layout_meta : $page_layout_option;

		switch( $post_layout ) {
			case 'cover' :
			case 'wide' :
			case 'banner' :
				break;

			default :
				publisher_woocommerce_content( $shop_page_id );
				break;

		};
	}

	if ( ( is_shop() || is_product_category() || is_product_tag() ) && woocommerce_products_will_display() ) {
		publisher_woocommerce_necktie();
		publisher_woocommerce_loader_icon();
	}

}
endif; // publisher_woocommerce_above_site_main
add_action( 'publisher_action_woocommerce_above_site_main', 'publisher_woocommerce_above_site_main', 10 );


/**
 * Featured Preview
 *
 * @action publisher_action_woocommerce_above_entry_wrap
 */
if ( ! function_exists( 'publisher_woocommerce_featured_preview' ) ) :
function publisher_woocommerce_featured_preview() {

	// Featured Image
	if ( has_post_thumbnail() ) {

		// Get taxonomy color
		$taxonomy_color = '';
		$get_categories = get_the_terms( get_the_ID(), 'product_cat' );

		if ( '' != $get_categories ) {
			foreach( $get_categories as $get_category ) {
				$taxonomy_color = publisher_get_taxonomy_color( 'style="background-color: ', ';"', esc_attr( $get_category->term_id ), esc_attr( $get_category->taxonomy ) );

				if ( ! empty( $taxonomy_color ) )
					break;
			}
		}
		?>

		<div class="featured-preview" <?php echo $taxonomy_color ?>>
			<?php
				// Get the featured image
				printf( '<figure class="featured-image"><a href="%1$s" title="%2$s">%3$s</a></figure>',
					get_the_permalink(),
					the_title_attribute( 'echo=0' ),
					woocommerce_get_product_thumbnail()
				);

				/**
				 * @hooked woocommerce_show_product_loop_sale_flash - 15
				 */
				do_action( 'publisher_action_woocommerce_featured_preview' );
			?>
		</div><!-- .featured-preview -->

	<?php
	}

}
endif; // publisher_woocommerce_featured_preview
add_action( 'publisher_action_woocommerce_above_entry_wrap', 'publisher_woocommerce_featured_preview', 10 );


/**
 * Entry Footer
 *
 * @action publisher_action_woocommerce_entry_wrap
 */
if ( ! function_exists( 'publisher_woocommerce_entry_footer' ) ) :
function publisher_woocommerce_entry_footer( $category ) {

	if ( $category ) { return; } ?>

	<footer class="entry-footer">
		<?php
			/**
			 * @hooked woocommerce_template_loop_add_to_cart - 10
			 */
			do_action( 'publisher_woocommerce_entry_footer_inside' );
		?>
	</footer><!-- .entry-footer -->

<?php
}
endif; // publisher_woocommerce_entry_footer
add_action( 'publisher_action_woocommerce_entry_wrap', 'publisher_woocommerce_entry_footer', 20 );




/*-----------------------------------------------------------------------------------*/
/*	Template Tags
/*-----------------------------------------------------------------------------------*/

/**
 * Woocommerce Shop Content
 *
 * @action publisher_action_woocommerce_above_site_main
 */
if ( ! function_exists( 'publisher_woocommerce_content' ) ) :
function publisher_woocommerce_content( $shop_page_id = '' ) {

	if ( is_paged() && get_theme_mod( 'publisher_options_products_shop_content_main' ) ) {
		return;
	}

	$shop_page_query = new WP_Query( array( 'post_type' => 'page', 'post__in' => array( $shop_page_id ) ) );
	$featured_preview = get_post_meta( $shop_page_id, 'publisher_featured_preview_meta', true );

	if ( $shop_page_query->have_posts() ) :
		while ( $shop_page_query->have_posts() ) : $shop_page_query->the_post();

			if ( '' != get_the_content() || $featured_preview ) { ?>
				<article id="page-<?php echo esc_attr( $shop_page_id ) ?>" class="shop-content">
					<?php
						if ( $featured_preview ) {
							printf( '<div class="featured-preview">%1$s</div>',
								! wp_oembed_get( $featured_preview ) ? do_shortcode( $featured_preview ) : wp_oembed_get( $featured_preview )
							);
						}

						the_content();
					?>
				</article><!-- #page-<?php echo esc_attr( $shop_page_id ) ?> -->
			<?php
			}

		endwhile;
		wp_reset_postdata();
	endif;

}
endif; // publisher_woocommerce_content


/**
 * Necktie
 *
 * @action publisher_action_woocommerce_above_site_main
 */
if ( ! function_exists( 'publisher_woocommerce_necktie' ) ) :
function publisher_woocommerce_necktie() { ?>

	<div class="necktie clearfix">
		<?php
			/**
			 * @hooked woocommerce_result_count - 10
			 * @hooked woocommerce_catalog_ordering - 30
			 */
			do_action( 'publisher_woocommerce_necktie' );
		?>
	</div>

<?php
}
endif; // publisher_woocommerce_necktie


/**
 * Loader Icon
 *
 * @action publisher_action_woocommerce_above_site_main
 */
if ( ! function_exists( 'publisher_woocommerce_loader_icon' ) ) :
function publisher_woocommerce_loader_icon() {
	echo publisher_get_loader_icon();
}
endif; // publisher_woocommerce_loader_icon




/*-----------------------------------------------------------------------------------*/
/*	Misc Filters and Actions
/*-----------------------------------------------------------------------------------*/

/**
 * Remove Disqus from interfering with Woocommerce reviews
 */
if ( ! function_exists( 'publisher_disqus_comments_template' ) ) :
function publisher_disqus_comments_template( $file ) {
    if ( is_singular( 'product' ) ) {
        remove_filter( 'comments_template', 'dsq_comments_template' );
    }
    return $file;
}
endif; // publisher_disqus_comments_template
add_filter( 'comments_template' , 'publisher_disqus_comments_template', 1 );
