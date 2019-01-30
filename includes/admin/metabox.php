<?php
/**
 * Metaboxes
 *
 * @package Publisher
 * @since v1.0
 */

/*-----------------------------------------------------------------------------------*/
/*	Add Metaboxes
/*-----------------------------------------------------------------------------------*/

/**
 * Add Metaboxes
 */
function publisher_add_meta_boxes() {
	global $post;

    if ( ! empty( $post ) ) {
	    $pageTemplate = get_post_meta( $post->ID, '_wp_page_template', true );

	    if ( 'template-pagebuilder.php' == $pageTemplate ) { } else {
		    // Screens
			$screens = array( 'post', 'page' );
			$post_types = get_post_types( array( 'public' => true, '_builtin' => false ), 'names' );
			foreach ( $post_types as $post_type ) {
				if ( in_array( $post_type, array( 'ditty_news_ticker' ) ) ) continue;
				$screens[] = $post_type;
			}

			/**
			 * Adds Page Subtitle metabox
			 */
			add_meta_box( 'publisher_page_subtitles_meta', __( 'Page Subtitle', 'publisher' ), 'publisher_page_subtitles_meta_box', 'page', 'normal', 'high' );

			/**
			 * Adds Featured Preview / Header metabox
			 */
			foreach ( $screens as $screen ) {
				if ( in_array( $screen, array( 'attachment', 'product', 'forum', 'topic', 'reply' ) ) ) continue;
				add_meta_box( 'publisher_featured_preview_meta', __( 'Featured Preview', 'publisher' ), 'publisher_featured_preview_meta_box', $screen, 'normal', 'high' );
			}

			/**
			 * Adds Post Layout metabox
			 */
			foreach ( $screens as $screen ) {
				if ( in_array( $screen, array( 'attachment', 'product', 'forum', 'topic', 'reply' ) ) ) continue;
				add_meta_box( 'publisher_post_layout_meta', __( 'Post Layout', 'publisher' ), 'publisher_post_layout_meta_box', $screen, 'normal', 'high' );
			}
	    }
    }
}
add_action( 'add_meta_boxes', 'publisher_add_meta_boxes' );




/*-----------------------------------------------------------------------------------*/
/*	Print Metaboxes
/*-----------------------------------------------------------------------------------*/

/**
 * Page Subtitles
 */
function publisher_page_subtitles_meta_box( $post ) {
	// Use nonce for verification
	wp_nonce_field( plugin_basename( __FILE__ ), 'publisher_page_subtitles_meta_nonce' );

	// Get existing value
	$value = get_post_meta( $post->ID, 'publisher_page_subtitles_meta', true ); ?>

	<input type="text" id="publisher_page_subtitles_meta" name="publisher_page_subtitles_meta" value="<?php echo esc_attr( $value ) ?>" size="30" style="width:100%;" />
	<p><?php _e( 'Add a subtitle for your page (optional).', 'publisher' ) ?></p>

	<?php
}


/**
 * Post Layout
 */
function publisher_post_layout_meta_box( $post ) {
	// Use nonce for verification
	wp_nonce_field( plugin_basename( __FILE__ ), 'publisher_post_layout_meta_nonce' );

	// Get existing values
	$value = get_post_meta( $post->ID, 'publisher_post_layout_meta', true );
	$select_value = get_post_meta( $post->ID, 'publisher_sidebar_layout_meta', true );
	$name = 'publisher_post_layout_meta';
	$select_name = 'publisher_sidebar_layout_meta';

	// Set default values
	if ( empty( $value ) || ( ! empty( $value ) && ! in_array( $value, array( 'standard', 'cover', 'wide', 'banner' ) ) ) ) {
		$value = "default";
	};

	if ( empty( $select_value ) || ( ! empty( $select_value ) && ! in_array( $select_value, array( 'default', 'show', 'hide' ) ) ) ) {
		$select_value = 'default';
	};
	?>

	<p><strong><?php _e( 'Layout', 'publisher' ) ?></strong></p>

	<label class="publisher-post-layout-button" for="post-layout-default">
		<input type="radio" name="<?php echo esc_attr( $name ) ?>" class="post-layout" id="post-layout-default" value="default" <?php checked( $value, 'default' ); ?> >
		<img src="<?php echo get_stylesheet_directory_uri() . '/includes/images/post-layout-default.gif' ?>"/>
		<span><?php _e( 'Default', 'publisher' ); ?></span>
	</label>

	<label class="publisher-post-layout-button" for="post-layout-standard">
		<input type="radio" name="<?php echo esc_attr( $name ) ?>" class="post-layout" id="post-layout-standard" value="standard" <?php checked( $value, 'standard' ); ?> >
		<img src="<?php echo get_stylesheet_directory_uri() . '/includes/images/post-layout-standard.gif' ?>"/>
		<span><?php _e( 'Standard', 'publisher' ); ?></span>
	</label>

	<label class="publisher-post-layout-button" for="post-layout-cover">
		<input type="radio" name="<?php echo esc_attr( $name ) ?>" class="post-layout" id="post-layout-cover" value="cover" <?php checked( $value, 'cover' ); ?> >
		<img src="<?php echo get_stylesheet_directory_uri() . '/includes/images/post-layout-cover.gif' ?>"/>
		<span><?php _e( 'Cover', 'publisher' ); ?></span>
	</label>

	<label class="publisher-post-layout-button" for="post-layout-wide">
		<input type="radio" name="<?php echo esc_attr( $name ) ?>" class="post-layout" id="post-layout-wide" value="wide" <?php checked( $value, 'wide' ); ?> >
		<img src="<?php echo get_stylesheet_directory_uri() . '/includes/images/post-layout-wide.gif' ?>"/>
		<span><?php _e( 'Wide', 'publisher' ); ?></span>
	</label>

	<label class="publisher-post-layout-button" for="post-layout-banner">
		<input type="radio" name="<?php echo esc_attr( $name ) ?>" class="post-layout" id="post-layout-banner" value="banner" <?php checked( $value, 'banner' ); ?> >
		<img src="<?php echo get_stylesheet_directory_uri() . '/includes/images/post-layout-banner.gif' ?>"/>
		<span><?php _e( 'Banner', 'publisher' ); ?></span>
	</label>

	<p><strong><?php _e( 'Sidebar', 'publisher' ) ?></strong></p>

	<label class="post-layout-button" for="post-layout-sidebar">
		 <select name="<?php echo esc_attr( $select_name ) ?>" id="post-layout-sidebar">
			 <option value="default" <?php selected( $select_value, 'default' ); ?>><?php _e( 'Default', 'publisher' )?></option>
			 <option value="show" <?php selected( $select_value, 'show' ); ?>><?php _e( 'Show', 'publisher' )?></option>
			 <option value="hide" <?php selected( $select_value, 'hide' ); ?>><?php _e( 'Hide', 'publisher' )?></option>
		 </select>
	</label>

<?php
}


/**
 * Featured Preview
 */
function publisher_featured_preview_meta_box( $post ) {
	// Use nonce for verification
	wp_nonce_field( plugin_basename( __FILE__ ), 'publisher_featured_preview_meta_nonce' );

	// Get existing values
	$value_featured = get_post_meta( $post->ID, 'publisher_featured_preview_meta', true );
	$value_checked_2 = get_post_meta( $post->ID, 'publisher_show_index_meta', true );
	$value_full_width = empty( $value_checked ) ? 0 : 1;
	$value_show_index = empty( $value_checked_2 ) ? 0 : 1;
	?>

	<textarea id="publisher_featured_preview_meta" name="publisher_featured_preview_meta" rows="3" size="30" style="width:100%;" /><?php echo esc_attr( $value_featured ) ?></textarea>

	<p>
		<?php
			printf( __( 'Embed videos, images, tweets, audio, and other content by entering its URL. <a class="thickbox" href="%1$s">More Info</a>', 'publisher' ),
			esc_url( 'http://codex.wordpress.org/Embeds?TB_iframe=true&amp;width=900&amp;height=600' )
			);
		?>
	</p>

	<p>
		<label for="publisher_show_index_meta">
			<input type="checkbox" name="publisher_show_index_meta" id="publisher_show_index_meta" value="1" <?php checked( $value_show_index, 1 ); ?> />
			<?php _e( 'Show featured preview in the posts index.', 'publisher' )?>
		</label>
	</p>

<?php
}




/*-----------------------------------------------------------------------------------*/
/*	Save Metaboxes
/*-----------------------------------------------------------------------------------*/

/**
 * Sanitize Save Metabox
 */
function publisher_sanitize_save_meta_box( $post_id, $nonce_field ) {

	// Get the posted data and sanitize it
	$new_meta_value = ( isset( $_POST[$nonce_field] ) ? $_POST[$nonce_field] : '' );

	// Get the meta key
	$meta_key = $nonce_field;

	// Get the meta value of the custom field key
	$meta_value = get_post_meta( $post_id, $meta_key, true );

	// If a new meta value was added and there was no previous value, add it
	if ( $new_meta_value && '' == $meta_value )
		add_post_meta( $post_id, $meta_key, $new_meta_value, true );

	// If the new meta value does not match the old value, update it
	elseif ( $new_meta_value && $new_meta_value != $meta_value )
		update_post_meta( $post_id, $meta_key, $new_meta_value );

	// If there is no new meta value but an old value exists, delete it
	elseif ( '' == $new_meta_value && $meta_value )
		delete_post_meta( $post_id, $meta_key, $meta_value );

}


/**
 * Save Page Subtitles
 */
function publisher_page_subtitles_meta_box_save( $post_id ) {
	global $post;

	// Return early if this is a newly created post that hasn't been saved yet.
	if ( 'auto-draft' == get_post_status( $post_id ) ) {
		return $post_id;
	}

	// Verify the nonce before proceeding
	if ( ! isset( $_POST['publisher_page_subtitles_meta_nonce'] ) || ! wp_verify_nonce( $_POST['publisher_page_subtitles_meta_nonce'], plugin_basename( __FILE__ ) ) )
		return $post_id;

	// Get post type object
	$post_type = get_post_type_object( $post->post_type );

	// Check if user has permission to edit the post
	if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
		return $post_id;
	}

	// Save Page Subtitles
	publisher_sanitize_save_meta_box( $post_id, 'publisher_page_subtitles_meta' );

}
add_action( 'save_post', 'publisher_page_subtitles_meta_box_save' );


/**
 * Save Post Layout
 */
function publisher_post_layout_meta_box_save( $post_id ) {
	global $post;

	// Return early if this is a newly created post that hasn't been saved yet.
	if ( 'auto-draft' == get_post_status( $post_id ) ) {
		return $post_id;
	}

	// Verify the nonce before proceeding
	if ( ! isset( $_POST['publisher_post_layout_meta_nonce'] ) || ! wp_verify_nonce( $_POST['publisher_post_layout_meta_nonce'], plugin_basename( __FILE__ ) ) )
    	return $post_id;

	// Get post type object
	$post_type = get_post_type_object( $post->post_type );

	// Check if user has permission to edit the post
	if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
		return $post_id;
	}

	// Save Post Layout
	if ( isset( $_POST['publisher_post_layout_meta'] ) ) {
		if ( 'default' == $_POST[ 'publisher_post_layout_meta' ] ) {
			delete_post_meta( $post_id, 'publisher_post_layout_meta' );
		} else {
			update_post_meta( $post_id, 'publisher_post_layout_meta', sanitize_html_class( $_POST[ 'publisher_post_layout_meta' ] ) );
		}
	} else {
		delete_post_meta( $post_id, 'publisher_post_layout_meta' );
	}

	// Save Sidebar Layout
	if ( isset( $_POST[ 'publisher_sidebar_layout_meta' ] ) ) {
		if ( 'default' == $_POST[ 'publisher_sidebar_layout_meta' ] ) {
			delete_post_meta( $post_id, 'publisher_sidebar_layout_meta' );
		} else {
			update_post_meta( $post_id, 'publisher_sidebar_layout_meta', sanitize_html_class( $_POST[ 'publisher_sidebar_layout_meta' ] ) );
		}
	} else {
		delete_post_meta( $post_id, 'publisher_sidebar_layout_meta' );
	}

}
add_action( 'save_post', 'publisher_post_layout_meta_box_save' );


/**
 * Save Featured Preview
 */
function publisher_featured_preview_meta_box_save( $post_id ) {
	global $post;

	// Return early if this is a newly created post that hasn't been saved yet.
	if ( 'auto-draft' == get_post_status( $post_id ) ) {
		return $post_id;
	}

	// Verify the nonce before proceeding
	if ( ! isset( $_POST['publisher_featured_preview_meta_nonce'] ) || ! wp_verify_nonce( $_POST['publisher_featured_preview_meta_nonce'], plugin_basename( __FILE__ ) ) )
    	return $post_id;

	// Get post type object
	$post_type = get_post_type_object( $post->post_type );

	// Check if user has permission to edit the post
	if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
		return $post_id;
	}

	// Save Featured Preview
	publisher_sanitize_save_meta_box( $post_id, 'publisher_featured_preview_meta' );

	// Save Show Posts Index
	if ( isset( $_POST[ 'publisher_show_index_meta' ] ) ) {
	    update_post_meta( $post_id, 'publisher_show_index_meta', 1 );
	} else {
	    delete_post_meta( $post_id, 'publisher_show_index_meta' );
	}

}
add_action( 'save_post', 'publisher_featured_preview_meta_box_save' );
