<?php
/**
 * Taxonomy Metaboxes
 *
 * @package Publisher
 * @since v1.0
 */

/*-----------------------------------------------------------------------------------*/
/*	Metabox Taxonomy Class
/*-----------------------------------------------------------------------------------*/

/**
 * Publisher Tax Meta Class
 *
 * The Publisher Tax Meta Class is derived from My-Meta-Box (https://github.com/bainternet/My-Meta-Box script)
 * by Ohad Raz, which is a class for creating custom meta boxes for WordPress.
 *
 * @author Ohad Raz (http://en.bainternet.info)
 * @edited Davadrian Maramis
 *
 * @package Publisher
 */


if ( ! class_exists( 'Publisher_Tax_Meta_Class' ) ) :
class Publisher_Tax_Meta_Class {

	/**
	 * Protected variables
	 */
	protected $_meta_box; // holds metabox object
	protected $_prefix; // holds metabox fields
	protected $_fields; // holds prefix for metabox fields
	protected $_form_type; // form type: edit or new term


	/**
	 * Constructor
	 */
	public function __construct ( $meta_box ) {

		// If we are not in admin area exit
		if ( ! is_admin() )
			return;

	    // Assign meta box values to local variables and add it's missed values
	    $this->_meta_box = $meta_box;
	    $this->_prefix = ( isset( $meta_box['prefix'] ) ) ? $meta_box['prefix'] : '';
	    $this->_fields = $this->_meta_box['fields'];

	    $this->add_missed_values();

	    // Register Hooks
	    add_action( 'admin_init', array( $this, 'add' ) );
	    add_action( 'admin_enqueue_scripts', array( $this, 'load_scripts_styles' ) );
	    add_action( 'delete_term', array( $this, 'delete_taxonomy_metadata' ), 10, 2 );

	}



	/*-----------------------------------------------------------------------------------*/
	/*	Enqueue Scripts / Styles
	/*-----------------------------------------------------------------------------------*/

	/**
	 * Load all Javascript and CSS when needed
	 */
	public function load_scripts_styles() {

	    //only load styles and js when needed
	    $taxnow = isset( $_REQUEST['taxonomy'] ) ? $_REQUEST['taxonomy'] : '';

	    if ( in_array( $taxnow, $this->_meta_box['pages'] ) ) {
		    // Check for special fields and add needed actions for them
		    $this->check_field_color();

		    // Enqueue scripts
		    wp_enqueue_script( 'publisher-taxonomy-js', get_template_directory_uri() . '/includes/js/publisher-metabox.js', array( 'jquery', 'wp-color-picker' ), null, true );
	    }

	}


	/**
	 * Check Field Upload
	 */
	public function check_field_upload() {
		if ( ! $this->has_field( 'image' ) && ! $this->has_field( 'file' ) )
			return;

		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-sortable' );
	}


	/**
	 * Check Field Color
	 */
	public function check_field_color() {
	    if ( $this->has_field( 'color' ) && $this->is_edit_page() ) {
		    wp_enqueue_style( 'wp-color-picker' );
		}
	}


	/**
	 * Check Field Time
	 */
	public function check_field_time() {
	    if ( $this->has_field( 'time' ) && $this->is_edit_page() ) {
		    wp_enqueue_script( 'at-timepicker', '//cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.4.5/jquery-ui-timepicker-addon.min.js', array( 'tmc-jquery-ui' ),false,true );
	    }
	}




	/*-----------------------------------------------------------------------------------*/
	/*	Add Metaboxes
	/*-----------------------------------------------------------------------------------*/

	/**
	 * Add Meta Box for multiple post types
	 */
	public function add() {
	    // Loop through array
	    foreach ( $this->_meta_box['pages'] as $page ) {

	      // add fields
	      add_action( $page . '_edit_form_fields', array( $this, 'show_edit_form' ) );
	      add_action( $page . '_add_form_fields', array( $this, 'show_new_form' ) );

	      // save fields
	      add_action( 'edited_' . $page, array( $this, 'save' ), 10, 2 );
	      add_action( 'created_' . $page, array( $this, 'save' ), 10, 2 );
	    }

	    // Delete all attachments when delete custom post type.
	    add_action( 'wp_ajax_at_delete_file', array( $this, 'delete_file' ) );
	    add_action( 'wp_ajax_at_reorder_images', array( $this, 'reorder_images' ) );
	    add_action( 'wp_ajax_at_delete_mupload', array( $this, 'wp_ajax_delete_image' ) );
	}


	/**
	 * Callback function to show fields on term add form
	 */
	public function show_new_form( $term_id ) {
	    $this->_form_type = 'new';
	    $this->show( $term_id );
    }


	/**
	 * Callback function to show fields on term edit form
	 */
	public function show_edit_form( $term_id ) {
		$this->_form_type = 'edit';
		$this->show( $term_id );
	}


	/**
	 * Callback function to show fields in metabox
	 */
	public function show( $term_id ) {
	    wp_nonce_field( basename(__FILE__), 'tax_meta_class_nonce' );

	    foreach ( $this->_fields as $field ) {
	    	$multiple = isset( $field['multiple'] ) ? $field['multiple'] : false;
			$meta = $this->get_tax_meta( $term_id, $field['id'], ! $multiple );
			$meta = ( $meta !== '' ) ? $meta : ( isset( $field['std'] ) ? $field['std'] : '' );

			if ( 'image' != $field['type'] && $field['type'] != 'repeater' )
	        	$meta = is_array( $meta ) ? array_map( 'esc_attr', $meta ) : esc_attr( $meta );

	        echo '<tr class="form-field">';
				// Call Separated methods for displaying each type of field
				call_user_func (
					array( $this, 'show_field_' . $field['type'] ),
					$field,
					is_array( $meta ) ? $meta : stripslashes( $meta )
				);
			echo '</tr>';
	    }
	    echo '</table>';
	}




	/*-----------------------------------------------------------------------------------*/
	/*	Save Metabox Fields
	/*-----------------------------------------------------------------------------------*/

	/**
	 * Save Data from Metabox
	 */
	public function save( $term_id ) {

	    // check if the we are coming from quick edit
	    if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'inline-save-tax' ) {
		    return $term_id;
	    }

	    if (
	    	! isset( $term_id ) 															// Check Revision
		    || ( ! isset( $_POST['taxonomy'] ) ) 											// Check if current taxonomy type is set.
		    || ( ! in_array( $_POST['taxonomy'], $this->_meta_box['pages'] ) )              // Check if current taxonomy type is supported.
		    || ( ! check_admin_referer( basename( __FILE__ ), 'tax_meta_class_nonce') )    	// Check nonce - Security
		    || ( ! current_user_can( 'manage_categories' ) )								// Check permission
		) {
			return $term_id;
	    }

	    foreach ( $this->_fields as $field ) {
		    $name = $field['id'];
		    $type = $field['type'];
		    $multiple = isset( $field['multiple'] ) ? $field['multiple'] : false;
		    $old = $this->get_tax_meta( $term_id, $name, ! $multiple );
		    $new = ( isset( $_POST[$name] ) ) ? $_POST[$name] : ( ( $multiple ) ? array() : '' );

		    // Validate meta value
		    if ( class_exists( 'Tax_Meta_Validate' ) && method_exists( 'Tax_Meta_Validate', $field['validate_func'] ) ) {
			    $new = call_user_func( array( 'Tax_Meta_Validate', $field['validate_func'] ), $new );
			}

			// Skip Paragraph field
			if ( $type != "paragraph" ) {
		        // Call defined method to save meta value, if there's no methods, call common one.
		        $save_func = 'save_field_' . $type;
		        if ( method_exists( $this, $save_func ) ) {
			        call_user_func( array( $this, 'save_field_' . $type ), $term_id, $field, $old, $new );
		        } else {
			        $this->save_field( $term_id, $field, $old, $new );
		        }
	        }
	    }

	}


	/**
	 * Common function for saving fields
	 */
	public function save_field( $term_id, $field, $old, $new ) {
	    $name = $field['id'];
	    $this->delete_tax_meta( $term_id, $name );
	    if ( $new === '' || $new === array() ) return;

	    $this->update_tax_meta( $term_id, $name, $new );
	}




	/*-----------------------------------------------------------------------------------*/
	/*	Helper Functions
	/*-----------------------------------------------------------------------------------*/

	/**
	 * Add missed values for meta box
	 */
	public function add_missed_values() {

	    // Default values for meta box
	    $this->_meta_box = array_merge( array( 'context' => 'normal', 'priority' => 'high', 'pages' => array( 'post' ) ), ( array ) $this->_meta_box );

	    // Default values for fields
	    foreach ( ( array ) $this->_fields as $field ) {
		    $multiple = in_array( $field['type'], array( 'checkbox_list', 'file', 'image' ) );
		    $std = $multiple ? array() : '';
		    $format = 'date' == $field['type'] ? 'yy-mm-dd' : ( 'time' == $field['type'] ? 'hh:mm' : '' );

		    $field = array_merge( array( 'multiple' => $multiple, 'std' => $std, 'desc' => '', 'format' => $format, 'validate_func' => '' ), $field );
	    }
	}


	/**
	 * Check if field with $type exists
	 */
	public function has_field( $type ) {
	    foreach ( $this->_fields as $field ) {
		    if ( $type == $field['type'] ) {
		    	return true;
		    } elseif( 'repeater' == $field['type'] ) {
			    foreach( ( array ) $field["fields"] as $repeater_field ) {
				    if ( $type == $repeater_field["type"] ) return true;
	        	}
	    	}
	    }
	    return false;
	}


	/**
	 * Check if current page is edit page
	 */
	public function is_edit_page() {
		global $pagenow;
		return ( $pagenow == 'edit-tags.php' );
	}


	/**
	 * Fixes the odd indexing of multiple file uploads.
	 *
	 * Goes from the format:
	 * $_FILES['field']['key']['index']
	 * to
	 * The More standard and appropriate:
	 * $_FILES['field']['index']['key']
	 */
	public function fix_file_array( &$files ) {
		$output = array();

		foreach ( $files as $key => $list ) {
			foreach ( $list as $index => $value ) {
				$output[$index][$key] = $value;
			}
		}

		return $files = $output;
	}


	/**
	 * Check for empty arrays
	 */
	public function is_array_empty( $array ) {
		if ( !is_array( $array ) )
			return true;

		foreach ($array as $a ) {
			if ( is_array( $a ) ) {
				foreach ( $a as $sub_a ) {
					if ( ! empty( $sub_a ) && $sub_a != '' )
						return false;
				}
			} else {
				if ( ! empty( $a ) && $a != '' )
					return false;
			}
	    }

	    return true;
	}


	/**
     * Get Attachment ID from URL
     */
    static function get_attachment_id_from_url( $url ) {
        global $wpdb;
        $id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE guid = '%s'", array( $url ) ) );
        return ( ! empty( $id ) ) ? $id : NULL;
    }


	/**
	 * Get term meta field
	 */
	public function get_tax_meta( $term_id, $key, $multi = false ) {
		$t_id = ( is_object( $term_id ) ) ? $term_id->term_id : $term_id;
		$m = get_option( 'tax_meta_' . $t_id );
		if ( isset( $m[$key] ) ) {
			return $m[$key];
		} else {
			return '';
		}
	}


	/**
	 * Delete Meta
	 */
	public function delete_tax_meta( $term_id, $key ) {
		$m = get_option( 'tax_meta_' . $term_id );
		if ( isset( $m[$key] ) ) {
			unset( $m[$key] );
		}
		update_option('tax_meta_'.$term_id,$m);
	}


	/**
	 * Update Meta
	 */
	public function update_tax_meta( $term_id, $key, $value ) {
		$m = get_option( 'tax_meta_' . $term_id );
		$m[$key] = $value;
		update_option( 'tax_meta_' . $term_id, $m );
	}


	/**
	 * Delete meta on term deletion
	 */
	public function delete_taxonomy_metadata( $term, $term_id ) {
		delete_option( 'tax_meta_' . $term_id );
	}




	/*-----------------------------------------------------------------------------------*/
	/*	Construct Metabox Fields
	/*-----------------------------------------------------------------------------------*/

	/**
	 * Begin Field
	 */
	public function show_field_begin( $field, $meta ) {

		if ( isset( $field['group'] ) ) {
	    	if ( $field['group'] == "start" ) {
		    	echo "<td class='at-field'>";
	    	}
	    } else {
	    	if ( $this->_form_type == 'edit' ) {
				echo '<th valign="top" scope="row">';
	    	} else {
				echo '<td><div class="form-field">';
	    	}
	    }

	    if ( $field['name'] != '' || $field['name'] != false ) {
	        echo "<label for='{$field['id']}'>{$field['name']}</label>";
	    }

	    if ( $this->_form_type == 'edit' ) {
	        echo '</th><td>';
	    }

	}


	/**
	 * End Field
	 */
	public function show_field_end( $field, $meta = null , $group = false ) {
	    if ( isset( $field['group'] ) ) {
	    	if ( $group == 'end' ) {
		    	if ( isset( $field['desc'] ) && $field['desc'] != '' ) {
			    	echo "<p class='description'>{$field['desc']}</p></td>";
			    } else {
				    echo "</td>";
				}
			} else {
		        if ( isset( $field['desc'] ) && $field['desc'] != '' ) {
			        echo "<p class='description'>{$field['desc']}</p><br/>";
		        } else {
			        echo '<br/>';
		        }
		    }
	    } else {
		    if ( isset( $field['desc'] ) && $field['desc'] != '' ) {
			    echo "<p class='description'>{$field['desc']}</p>";
			}

			if ( $this->_form_type == 'edit' ) {
				echo '</td>';
			} else {
				echo '</td></div>';
			}
		}
	}


	/**
	 * Show Image Field
	 */
	public function show_field_image( $field, $meta ) {
	  	$this->show_field_begin( $field, $meta );

	  	wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_media();

		$width         	= isset( $field['width'] ) ? $field['width'] : '60px';
		$height        	= isset( $field['height'] )? $field['height'] : '60px';
		$preview_style 	= "style='width: $width; height: $height; margin-right: 10px;'";

		if ( ! empty( $meta ) ) {
			$thumb_id 		= self::get_attachment_id_from_url( $meta );
			$attachment 	= wp_get_attachment_image_src( $thumb_id, 'thumbnail' );
			$image 			= esc_url( $attachment[0] );
			$remove_styles 	= 'display: inline-block;';
		} else {
			$image 			= '';
			$remove_styles 	= 'display: none;';
		}

		echo "<img class='tax-image' src='{$image}' {$preview_style} />";
		echo "<input type='text' name='{$field['id']}' value='{$meta}' style='display:none;' />";
		echo "<button class='button button-upload' style='margin-right: 3px;'>" . __( 'Upload/Add Image', 'publisher' ) . "</button>";
		echo "<button class='button button-remove' style='{$remove_styles}'>" . __( 'Remove Image', 'publisher' ) . "</button>";

        $this->show_field_end( $field, $meta );
	}


	/**
	 * Show Color Field
	 */
	public function show_field_color( $field, $meta ) {
	    $this->show_field_begin( $field, $meta );
	    	echo "<input class='tax-color-picker' type='text' name='{$field['id']}' id='{$field['id']}' value='{$meta}' data-default-color='{$field['std']}' />";
	    $this->show_field_end($field, $meta);
	}




	/*-----------------------------------------------------------------------------------*/
	/*	Call Metabox Fields
	/*-----------------------------------------------------------------------------------*/

	/**
	 *  Add Field to meta box (generic function)
	 *
	 *  @param $id string  field id, i.e. the meta key
	 *  @param $args mixed|array
	 */
	public function addField( $id, $args ) {
	    $new_field = array( 'id'=> $id, 'std' => '', 'desc' => '', 'style' => '', 'multiple' => false );
	    $new_field = array_merge( $new_field, $args );
	    $this->_fields[] = $new_field;
	}


	/**
	 *  Add Color Field to meta box
	 *
	 *  @param $id string  field id, i.e. the meta key
	 *  @param $args mixed|array
	 *    'name' => // field name/label string optional
	 *    'desc' => // field description, string optional
	 *    'std' => // default value, string optional
	 *    'validate_func' => // validate function, string optional
	 *  @param $repeater bool  is this a field inside a repeatr? true|false(default)
	 */
	public function addColor( $id, $args, $repeater = false ) {
	    $new_field = array( 'type' => 'color', 'id' => $id, 'std' => '', 'style' => '', 'desc' => '', 'name' => 'ColorPicker Field', 'multiple' => false );
	    $new_field = array_merge( $new_field, $args );
	    if ( false === $repeater ) {
		    $this->_fields[] = $new_field;
		} else {
			return $new_field;
		}
	}


	/**
	 *  Add Image Field to meta box
	 *
	 *  @param $id string  field id, i.e. the meta key
	 *  @param $args mixed|array
	 *    'name' => // field name/label string optional
	 *    'desc' => // field description, string optional
	 *    'validate_func' => // validate function, string optional
	 *  @param $repeater bool  is this a field inside a repeatr? true|false(default)
	 */
	public function addImage( $id, $args, $repeater = false ) {
	    $new_field = array( 'type' => 'image', 'id' => $id, 'desc' => '', 'style' => '', 'name' => 'Image Field', 'std' => '', 'multiple' => false );
	    $new_field = array_merge( $new_field, $args );
	    if ( false === $repeater ) {
		    $this->_fields[] = $new_field;
		} else {
			return $new_field;
		}
	}


	/**
	 * Finish Declaration of Meta Box
	 */
	public function Finish() {
		$this->add_missed_values();
	}


}
endif; // Publisher_Tax_Meta_Class




/*-----------------------------------------------------------------------------------*/
/*	Taxonomy Metabox Functions
/*-----------------------------------------------------------------------------------*/

/*
 * Get Term Meta Field
 */
if ( ! function_exists( 'get_tax_meta' ) ) :
function get_tax_meta( $term_id, $key, $multi = false ) {

	$t_id = ( is_object( $term_id ) ) ? $term_id->term_id : $term_id;
	$m = get_option( 'tax_meta_' . $t_id );

	if ( isset( $m[$key] ) ) {
		return $m[$key];
	} else {
		return '';
	}
}
endif; // get_tax_meta


/*
 * Delete Meta
 */
if ( ! function_exists( 'delete_tax_meta' ) ) :
function delete_tax_meta( $term_id, $key ) {
	$m = get_option( 'tax_meta_' . $term_id );
	if ( isset( $m[$key] ) ) {
		unset( $m[$key] );
	}
	update_option( 'tax_meta_' . $term_id, $m );
}
endif; // delete_tax_meta


/*
 * Update Meta
 */
if ( ! function_exists( 'update_tax_meta' ) ) :
function update_tax_meta( $term_id, $key, $value ) {
	$m = get_option( 'tax_meta_' . $term_id );
	$m[$key] = $value;
	update_option( 'tax_meta_' . $term_id, $m );
}
endif; // update_tax_meta


/*
 * Get term meta field and strip slashes
 */
if ( ! function_exists( 'get_tax_meta_strip' ) ) :
function get_tax_meta_strip( $term_id, $key, $multi = false ) {
	$t_id = ( is_object( $term_id ) ) ? $term_id->term_id : $term_id;
	$m = get_option( 'tax_meta_' . $t_id );
	if ( isset( $m[$key] ) ) {
		return is_array( $m[$key] ) ? $m[$key] : stripslashes( $m[$key] );
	} else {
		return '';
	}
}
endif; // get_tax_meta_strip


/*
 * Get all meta fields of a term
 */
if ( ! function_exists( 'get_tax_meta_all' ) ) :
function get_tax_meta_all( $term_id ) {
	$t_id = ( is_object( $term_id ) ) ? $term_id->term_id : $term_id;
	return get_option( 'tax_meta_' . $t_id, array() );
}
endif; // get_tax_meta_all




/*-----------------------------------------------------------------------------------*/
/*	Metabox Metaboxes
/*-----------------------------------------------------------------------------------*/

if ( is_admin() ) {

	// Get taxonomy terms to add metaboxes
	$taxonomy_image_color = get_theme_mod( 'publisher_options_taxonomy_image_color' );
	if ( empty( $taxonomy_image_color ) ) $taxonomy_image_color = array();

	$taxonomy_pages = array_merge( array( 'category', 'post_tag', 'product_cat', 'product_tag' ), array_filter( $taxonomy_image_color ) );

	$taxonomy_metabox =  new Publisher_Tax_Meta_Class( array(
		'id' 		=> 'publisher_taxonomy_metabox',					// unique metabox id
		'title' 	=> __( 'Taxonomy Metabox', 'publisher' ),			// metabox title
		'pages' 	=> $taxonomy_pages, 								// taxonomy name - array()
		'context' 	=> 'normal',										// where the meta box appear: normal (default), advanced, side; optional
		'fields' 	=> array(),											// list of meta fields (can be added by field arrays)
	) );

	// Color Field
	$taxonomy_metabox->addColor( 'publisher_tax_options_color', array(
		'name' => __( 'Taxonomy Color ','publisher' ),
		'desc' => __( 'Set the color to show as the taxonomy label.', 'publisher' ),
	) );

	// Image Field
	$taxonomy_metabox->addImage( 'publisher_tax_options_featured_image', array(
		'name' => __( 'Featured Image', 'publisher' ),
		'desc' => __( 'Select the featured image for this taxonomy.', 'publisher' ),
	) );

	$taxonomy_metabox->Finish();

}
