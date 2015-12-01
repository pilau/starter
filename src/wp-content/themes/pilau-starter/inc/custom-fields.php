<?php


/**
 * Custom meta fields for posts and users
 *
 * Basic fields handled by CMB2
 * @link	https://wordpress.org/plugins/cmb2/
 *
 * More advanced fields can be handled by ACF if necessary
 * @link	http://www.advancedcustomfields.com/pro
 *
 * @package	Pilau_Starter
 * @since	0.1
 */


/**
 * Use custom fields for Admin Columns
 */
if ( PILAU_PLUGIN_EXISTS_ADMIN_COLUMNS ) {
	add_filter( 'cpac_use_hidden_custom_fields', '__return_true' );
}


add_filter( 'cmb2_meta_box_url', 'pilau_cmb2_meta_box_url', 10, 2 );
/**
 * Adjust CMB2 URL when local (and plugin is symlinked)
 */
function pilau_cmb2_meta_box_url( $url, $version ) {
	if ( defined( 'WP_LOCAL_DEV' ) && WP_LOCAL_DEV ) {
		$url = plugins_url( 'cmb2/' );
	}
	return $url;
}


if ( PILAU_PLUGIN_EXISTS_CMB2 ) {
	add_action( 'do_meta_boxes', 'pilau_remove_default_custom_fields_meta_box', 1, 3 );
	add_action( 'cmb2_admin_init', 'pilau_cmb2_custom_fields' );
}
/**
 * Remove standard custom fields metabox
 *
 * @since	0.1
 */
function pilau_remove_default_custom_fields_meta_box( $post_type, $context, $post ) {
	remove_meta_box( 'postcustom', $post_type, $context );
}
/**
 * Register custom fields
 *
 * @uses new_cmb2_box()
 * @since	Pilau_Starter 0.1
 */
function pilau_cmb2_custom_fields() {
	global $pilau_slideshow_content_types, $pilau_slideshow_pages, $pilau_image_sizes, $pilau_breakpoints;


	/* General settings
	********************************************************************************/

	/*
	// Longer teaser text
	$cmb = new_cmb2_box( array(
		'id'				=> 'teaser_text_box',
		'title'				=> __( 'Teaser text' ),
		'object_types'		=> array( 'page', 'post' ),
		'show_on_cb'		=> 'pilau_cmb2_show_on_custom',
		'show_on_custom'	=> array(
			'user_can'			=> 'publish_pages',
		),
		'context'			=> 'normal',
		'priority'			=> 'high',
		'show_names'		=> true,
	));
	$cmb->add_field( array(
		'name'				=> __( 'Teaser text' ),
		'desc'				=> __( 'Usually teaser text is taken from the SEO meta description if possible, or else an extract is taken from the main content. For some places, longer teaser text is needed than is good for the meta description, and an automated extract is unsuitable - this text can be used instead.' ),
		'id'				=> pilau_cmb2_meta_key( 'teaser-text' ),
		'type'				=> 'textarea_small',
		'on_front'			=> false,
	) );
	*/


	/* CPT author
	********************************************************************************/

	/*
	 * NOTE: This code is kept here in case it comes in useful. However, for the basic
	 * problem here, a better solution now is probably on of these:
	 * @link	http://themehybrid.com/weblog/members-role-levels-wordpress-plugin
	 * @link	http://themehybrid.com/weblog/correcting-the-author-meta-box-drop-down
	 * Remove this code if not needed.

	// Loop through CPTs
	foreach ( get_post_types( array( '_builtin' => false ), 'objects' ) as $cpt ) {

		// Check for support for custom author
		if ( post_type_supports( $cpt->name, 'pilau-author' ) ) {

			$cpt_author_options = array();
			// Gather users that can 'edit_posts' for this CPT
			foreach ( pilau_get_users_by_capability( $cpt->cap->edit_posts ) as $cpt_author ) {
				$cpt_author_options[ $cpt_author->ID ] = $cpt_author->display_name;
			}
			$cmb = new_cmb2_box( array(
				'id'				=> 'cpt_author_' . $cpt->name . 'box',
				'title'				=> __( 'Author' ),
				'object_types'		=> array( $cpt->name ),
				'show_on_cb'		=> 'pilau_cmb2_show_on_custom',
				'show_on_custom'	=> array(
					'user_can'			=> 'edit_others_post_types',
				),
				'context'			=> 'side',
				'priority'			=> 'low',
				'show_names'		=> false,
			));
			$cmb->add_field( array(
				'name'				=> __( 'Author' ),
				'id'				=> pilau_cmb2_meta_key( 'author' ),
				'type'				=> 'select',
				'options'			=> $cpt_author_options,
				'default'			=> get_current_user_id(),
				'on_front'			=> false,
			) );

		}
	}

	*/


	/* FAQs
	*******************************************************************************

	// Location
	$cmb = new_cmb2_box( array(
		'id'				=> 'faq_location_box',
		'title'				=> __( 'Location' ),
		'object_types'		=> array( 'faq' ),
		'show_on_cb'		=> 'pilau_cmb2_show_on_custom',
		'show_on_custom'	=> array(
			'user_can'			=> 'publish_services',
		),
		'context'			=> 'normal',
		'priority'			=> 'high',
		'show_names'		=> false,
		'closed'			=> false,
	));
	$cmb->add_field( array(
		'name'				=> __( 'Location' ),
		'desc'				=> __( 'FAQ locations are pages assigned to the FAQ Listing template.' ),
		'id'				=> pilau_cmb2_meta_key( 'faq-location' ),
		'type'				=> 'select',
		'options'			=> pilau_cmb2_get_template_pages_options( 'page_listing-faqs.php' ),
		'on_front'			=> false,
	) );
	*/

	/* Slideshows
	********************************************************************************/

	if ( PILAU_SLIDESHOW_ITEMS ) {

		$cmb = new_cmb2_box( array(
			'id'				=> 'slideshow_config_box',
			'title'				=> __( 'Slideshow configuration' ),
			'object_types'		=> array( 'page' ),
			'show_on'			=> array(
				'key'		=> 'id',
				'value'		=> $pilau_slideshow_pages
			),
			'show_on_cb'		=> 'pilau_cmb2_show_on_custom',
			'show_on_custom'	=> array(
				'user_can'			=> 'publish_pages',
			),
			'context'			=> 'normal',
			'priority'			=> 'high',
			'show_names'		=> true,
		));
		// before_row on first field in lieu of box description for now
		$cmb->add_field( array(
			'name'				=> __( 'Autoplay pause' ),
			'desc'				=> __( 'Number of seconds to pause between automatic advance to next slide. Enter 0 (zero) to disable autoplay.' ),
			'id'				=> pilau_cmb2_meta_key( 'slideshow-autoplay' ),
			'type'				=> 'text_small',
			'default'			=> 0,
			'on_front'			=> false,
			'before_row'		=> '<p>' . __( 'To include something in the carousel, edit that content and supply the image and text. Then that content will become available to be selected here.' ) . '</p>',
		) );
		for ( $i = 1; $i <= PILAU_SLIDESHOW_ITEMS; $i++ ) {
			$cmb->add_field( array(
				'id'				=> pilau_cmb2_meta_key( 'slideshow-item-' . $i ),
				'name'				=> 'Item ' . $i,
				'type'				=> 'select',
				'show_option_none'	=> '[' . __( 'Nothing' ) . ']',
				'options'			=> pilau_cmb2_get_post_options( array(
					'post_type'			=> $pilau_slideshow_content_types,
					'posts_per_page'	=> -1,
					'orderby'			=> 'title',
					'order'				=> 'ASC',
					'meta_query'		=> array(
						array(
							'key'			=> pilau_cmb2_meta_key( 'slideshow-image' ),
							'compare'		=> 'EXISTS',
						),
						array(
							'key'			=> pilau_cmb2_meta_key( 'slideshow-image-portrait' ),
							'compare'		=> 'EXISTS',
						),
						array(
							'key'			=> pilau_cmb2_meta_key( 'slideshow-heading' ),
							'compare'		=> 'EXISTS',
						),
						array(
							'key'			=> pilau_cmb2_meta_key( 'slideshow-teaser' ),
							'compare'		=> 'EXISTS',
						),
						array(
							'key'			=> pilau_cmb2_meta_key( 'slideshow-button-text' ),
							'compare'		=> 'EXISTS',
						),
					),
				)),
			));
		}

		// Slideshow content
		$cmb = new_cmb2_box( array(
			'id'				=> 'slideshow_content_box',
			'title'				=> __( 'Slideshow content' ),
			'object_types'		=> $pilau_slideshow_content_types,
			'show_on_cb'		=> 'pilau_cmb2_show_on_custom',
			'show_on_custom'	=> array(
				'user_can'			=> 'publish_pages',
			),
			'context'			=> 'normal',
			'priority'			=> 'high',
			'show_names'		=> true,
		));
		$cmb->add_field( array(
			'name'				=> __( 'Heading' ),
			'id'				=> pilau_cmb2_meta_key( 'slideshow-heading' ),
			'type'				=> 'text',
			'on_front'			=> false,
			'before_row'		=> '<p>' . __( 'Populate these fields to make this content available for inclusion in slideshows.' ) . '</p>',
		) );
		$cmb->add_field( array(
			'name'				=> __( 'Landscape image' ),
			'id'				=> pilau_cmb2_meta_key( 'slideshow-image' ),
			'type'				=> 'file',
			'options'			=> pilau_cmb2_options_file( 'image' ),
			'desc'				=> sprintf( __( 'For larger, landscape-oriented screens. Optimum image size: %dpx wide, %dpx high.' ), $pilau_image_sizes['slideshow']['width'], $pilau_image_sizes['slideshow']['height'] ),
			'on_front'			=> false,
		) );
		$cmb->add_field( array(
			'name'				=> __( 'Portrait image' ),
			'id'				=> pilau_cmb2_meta_key( 'slideshow-image-portrait' ),
			'type'				=> 'file',
			'options'			=> pilau_cmb2_options_file( 'image' ),
			'desc'				=> sprintf( __( 'For smaller, portrait-oriented screens. Optimum image size: %dpx wide, %dpx high.' ), $pilau_image_sizes['slideshow-portrait']['width'], $pilau_image_sizes['slideshow-portrait']['height'] ),
			'on_front'			=> false,
		) );
		$cmb->add_field( array(
			'name'				=> __( 'Teaser' ),
			'id'				=> pilau_cmb2_meta_key( 'slideshow-teaser' ),
			'type'				=> 'textarea_small',
			'on_front'			=> false,
		) );
		$cmb->add_field( array(
			'name'				=> __( 'Button text' ),
			'id'				=> pilau_cmb2_meta_key( 'slideshow-button-text' ),
			'type'				=> 'text_small',
			'default'			=> __( 'Read more' ),
			'on_front'			=> false,
		) );

	}

}


/**
 * Custom field checker
 *
 * @since	0.1

 * @param	string	$field	The field name to check
 * @param	mixed	$value	The value to check against (returns true if the field value is equivalent)
 * @param	boolean	$notset	The return value if the field isn't set at all (defaults to false)
 * @return	boolean
 */
function pilau_custom_field_check( $field, $value = true, $notset = false ) {
	global $pilau_custom_fields;
	$result = false;
	if ( isset( $pilau_custom_fields[ $field ] ) ) {
		$result = ( $pilau_custom_fields[ $field ] == $value );
	} else {
		$result = $notset;
	}
	return $result;
}


/*
 * CMB2 plugin stuff
 *************************************************************************************/


//add_action( 'cmb2_save_post_fields', 'pilau_cmb2_checkbox_update_timestamp', 10, 4 );
/**
 * Record a timestamp for when certain checkboxes are updated
 *
 * @param	int		$object_id
 * @param	int		$box_id
 * @param	array	$updated_fields
 * @param	object	$cmb2_object
 * @return	void
 */
function pilau_cmb2_checkbox_update_timestamp( $object_id, $box_id, $updated_fields, $cmb2_object ) {
	foreach ( $cmb2_object->meta_box['fields'] as $field ) {
		if ( $field['type'] == 'checkbox' && ! empty( $field['pilau_checkbox_timestamp'] ) && in_array( $field['id'], $updated_fields ) ) {
			update_post_meta( $object_id, $field['id'] . '_last_updated', time() );
		}
	}
}


add_filter( 'teeny_mce_before_init', 'pilau_tinymce_paste_as_text' );
add_filter( 'tiny_mce_before_init', 'pilau_tinymce_paste_as_text' );
/**
 * Force paste as text in TinyMCE
 *
 * @link	http://www.wizzud.com/2014/02/14/force-paste-as-text-on-in-wordpress/
 * @link	http://stackoverflow.com/a/27501677/1087660
 * @todo	Not working?
 * @param	array	$init
 * @return	array
 */
function pilau_tinymce_paste_as_text( $init ) {
	$init['paste_as_text'] = true;
	$init['paste_text_use_dialog'] = false;
	$init['paste_text_sticky'] = true;
	$init['paste_text_sticky_default'] = true;
	return $init;
}


/**
 * Helper for WYSIWYG options
 *
 * @param       string		$style				'minimal' | 'medium' | 'full'
 * @param		array		$options_override	To override defaults
 * @return		array
 */
function pilau_cmb2_options_wysiwyg( $style, $options_override = array() ) {
	$options = array();

	// Defaults according to style
	switch ( $style ) {
		case 'minimal': {
			$options = array(
				'wpautop'			=> true,
				'media_buttons'		=> false,
				'textarea_rows'		=> 3,
				'teeny'				=> true,
				'quicktags'			=> false,
				'tinymce'			=> array(
					'toolbar1'			=> 'link,unlink'
				)
			);
			break;
		}
		case 'medium': {
			$options = array(
				'wpautop'			=> true,
				'media_buttons'		=> false,
				'textarea_rows'		=> 8,
				'teeny'				=> true,
				'quicktags'			=> false,
				'tinymce'			=> array(
					'toolbar1'			=> 'bold,italic,bullist,numlist,link,unlink,undo,redo'
				)
			);
			break;
		}
		case 'full': {
			$options = array(
				'wpautop'			=> true,
				'media_buttons'		=> true,
				'textarea_rows'		=> 16,
				'teeny'				=> false,
				'quicktags'			=> true,
			);
			break;
		}
	}

	// Overrides
	$options = wp_parse_args( $options_override, $options );

	return $options;
}


/**
 * Helper for file upload options
 *
 * @param       string		$style				'image' | 'document'
 * @param		array		$options_override	To override defaults
 * @todo		Work out how to filter file types shown in modal
 * @return		array
 */
function pilau_cmb2_options_file( $style, $options_override = array() ) {
	$options = array( 'url' => false );

	// Defaults according to style
	switch ( $style ) {
		case 'image': {
			$options['add_upload_file_text'] = __( 'Add image' );
			break;
		}
		case 'document': {
			$options['add_upload_file_text'] = __( 'Add document' );
			break;
		}
	}

	// Overrides
	$options = wp_parse_args( $options_override, $options );

	return $options;
}


add_filter( 'cmb2_override_' . pilau_cmb2_meta_key( 'author' ) . '_meta_save', 'pilau_cpt_author_meta_save_override', 0, 4 );
/**
 * Override CPT author meta save in order to store as post author
 */
function pilau_cpt_author_meta_save_override( $override, $data_args, $args, $field ) {

	// Checks to avoid infinite loops
	// @link	https://codex.wordpress.org/Function_Reference/wp_update_post#Caution_-_Infinite_loop
	if ( ! wp_is_post_revision( $data_args['id'] ) ) {

		// Remove filter to avoid loop
		remove_filter( 'cmb2_override_' . pilau_cmb2_meta_key( 'author' ) . '_meta_save', 'pilau_cpt_author_meta_save_override', 0 );

		// Update post author
		// Will return non-null value to short-circuit normal meta save
		$override = wp_update_post( array(
			'ID'			=> $data_args['id'],
			'post_author'	=> $data_args['value'],
		));

		// Add filter back
		add_filter( 'cmb2_override_' . pilau_cmb2_meta_key( 'author' ) . '_meta_save', 'pilau_cpt_author_meta_save_override', 0 );

	}

	return $override;
}


add_filter( 'cmb2_override_' . pilau_cmb2_meta_key( 'author' ) . '_meta_value', 'pilau_cpt_author_meta_value_override', 0, 4 );
/**
 * Override CPT author meta value in order to fetch post author
 */
function pilau_cpt_author_meta_value_override( $data, $object_id, $data_args, $field ) {
	return get_post_field( 'post_author', $object_id );
}


/**
 * Return custom field meta key for CMB2
 *
 * @since	0.1
 * @param	string	$field_name
 * @return	string
 */
function pilau_cmb2_meta_key( $field_name ) {
	return PILAU_CUSTOM_FIELDS_PREFIX . $field_name;
}


/**
 * Get an object's custom fields, stripping any CMB2 prefixes
 *
 * @since	0.1
 * @param	int		$id
 * @param	string	$type	'post' | 'user'
 * @return	array
 */
function pilau_get_custom_fields( $id = null, $type = 'post' ) {
	global $post;
	$fields = null;
	$id = pilau_default_object_id( $type, $id );
	$values = array();
	$values_no_prefix = array();

	switch ( $type ) {
		case 'post': {
			$values = get_post_custom( $id );
			break;
		}
		case 'user': {
			$values = get_user_meta( $id );
			break;
		}
	}

	if ( ! empty( $values ) ) {

		foreach ( $values as $key => $value ) {
			$new_key = $key;

			// Strip standard prefix?
			if ( strlen( $key ) > strlen( PILAU_CUSTOM_FIELDS_PREFIX ) && substr( $key, 0, strlen( PILAU_CUSTOM_FIELDS_PREFIX ) ) == PILAU_CUSTOM_FIELDS_PREFIX ) {
				$new_key = preg_replace( '#' . PILAU_CUSTOM_FIELDS_PREFIX . '#', '', $key, 1 );
			}

			$values_no_prefix[ $new_key ] = $value[0];
		}

	}

	// Unserialize? Maybe
	$values_no_prefix = array_map( 'maybe_unserialize', $values_no_prefix );

	return $values_no_prefix;
}


/**
 * Callback to handle custom show_on restrictions
 *
 * @since	0.1
 * @return	bool
 */
function pilau_cmb2_show_on_custom( $cmb ) {
	$show = true;
	$show_on_custom = $cmb->prop( 'show_on_custom' );

	if ( ! empty( $show_on_custom ) ) {
		$screen = get_current_screen();

		// Loop through restrictions
		foreach ( $show_on_custom as $show_on_type => $show_on_condition ) {

			switch ( $show_on_type ) {

				case 'user_can': {

					// Special capabilities
					switch ( $show_on_condition ) {
						case 'publish_post_types':
						case 'edit_others_post_types': {
							$cap_for_posts = str_replace( '_post_types', '_posts', $show_on_condition );
							$post_type_object = get_post_type_object( $screen->post_type );
							$cap = $post_type_object->cap->$cap_for_posts;
							break;
						}
						default: {
							$cap = $show_on_condition;
							break;
						}
					}

					// Check user capability
					$show = current_user_can( $cap );

					break;
				}

				case 'mime_types': {
					// Check for media MIME types
					if ( ! empty( $show_on_condition ) && is_array( $show_on_condition ) && in_array( 'attachment', $cmb->prop( 'object_types' ) ) ) {
						$show = in_array( get_post_mime_type(), $show_on_condition );
					}
					break;
				}

				case 'exclude_ids': {
					// Check for excluding based on IDs
					if ( ! empty( $show_on_condition ) && is_array( $show_on_condition ) ) {
						$show = ! in_array( $cmb->object_id, $show_on_condition );
					}
					break;
				}

				case 'include_ids': {
					// Check for including based on IDs
					if ( ! empty( $show_on_condition ) && is_array( $show_on_condition ) ) {
						$show = in_array( $cmb->object_id, $show_on_condition );
					}
					break;
				}

				case 'include_templates': {
					// Replicates built-in check, seems to not work with 'show_on' and 'show_on_cb'
					if ( ! empty( $show_on_condition ) && is_array( $show_on_condition ) ) {
						$show = in_array( get_page_template_slug( $cmb->object_id ), $show_on_condition );
					}
					break;
				}

				case 'parent': {
					// Parent
					if ( $screen->post_type == 'page' && ! empty( $show_on_condition ) && ( ctype_digit( $show_on_condition ) || is_int( $show_on_condition ) ) ) {
						$show = wp_get_post_parent_id( $cmb->object_id ) == $show_on_condition;
					}
					break;
				}

			}

			// If a condition has failed, break out
			if ( ! $show ) {
				break;
			}

		}

	}

	return $show;
}


/**
 * Get posts to populate CMB2 options
 *
 * @param		array	$query_args
 * @param		bool	$show_parent
 * @return		array
 */
function pilau_cmb2_get_post_options( $query_args, $show_parent = false ) {

	// Default args
	$args = wp_parse_args( $query_args, array(
		'post_type'			=> 'post',
		'posts_per_page'	=> -1,
	) );

	// Placeholder replacements
	array_walk_recursive( $args, 'pilau_cmb2_placeholder_replace' );

	// Get posts
	$posts = get_posts( $args );

	// Populate options
	$post_options = array();
	if ( $posts ) {
		foreach ( $posts as $post ) {
			$post_options[ $post->ID ] = $post->post_title;
			if ( $show_parent && $post->post_parent ) {
				$post_options[ $post->ID ] = get_the_title( $post->post_parent ) . ' &gt; ' . $post_options[ $post->ID ];
			}
		}
	}

	return $post_options;
}


/**
 * Do CMB2 placeholder replacements (used with array_walk_recursive)
 */
function pilau_cmb2_placeholder_replace( &$value, $key ) {
	global $post;
	// Use if / else if, with ===, because switch/case does odd things
	if ( $value === '[[POST_ID]]' ) {
		if ( ! empty( $post->ID ) ) {
			$value = $post->ID;
		} else if ( ! empty( $_REQUEST['post'] ) ) {
			$value = (int) $_REQUEST['post'];
		}
	}
}


/**
 * Get pages of a certain template as options
 *
 * @param	string	$template
 * @param	bool	$show_parent
 * @return	array
 */
function pilau_cmb2_get_template_pages_options( $template, $show_parent = true ) {
	return pilau_cmb2_get_post_options( array(
		'post_type'			=> 'page',
		'posts_per_page'	=> -1,
		'meta_query'		=> array(
			array(
				'key'			=> '_wp_page_template',
				'value'			=> $template
			)
		)
	), $show_parent );
}


/**
 * Get hierarchical page tree to populate CMB2 options
 *
 * @param		array			$query_args
 * @param		int				$descendants_of
 * @param		bool|string	$show_none
 * @return		array
 */
function pilau_cmb2_get_page_tree_options( $descendants_of, $query_args = array(), $show_none = false ) {

	// Default args
	$args = wp_parse_args( $query_args, array(
			'post_type'			=> 'page',
			'posts_per_page'	=> -1,
			'child_of'			=> $descendants_of,
			'sort_column'		=> 'menu_order',
	) );

	// Placeholder replacements
	//echo '<pre>'; print_r( $args ); echo '</pre>';
	array_walk_recursive( $args, 'pilau_cmb2_placeholder_replace' );
	//echo '<pre>'; print_r( $args ); echo '</pre>'; exit;

	// Get pages
	$pages = get_pages( $args );
	//echo '<pre>'; print_r( $pages ); echo '</pre>'; exit;

	// Kick off recursive population
	$options = pilau_cmb2_get_page_tree_options_populate( $pages, array( $descendants_of ) );
	//echo '<pre>'; print_r( count( $options ) ); echo '</pre>';
	//echo '<pre>'; print_r( $options ); echo '</pre>'; exit;

	// Add 'show none'?
	if ( $show_none ) {
		if ( ! is_string( $show_none ) ) {
			$show_none = '[' . __( 'None' ) . ']';
		}
		$options = array( 0 => $show_none ) + $options;
	}

	return $options;
}

// Helper function for page tree options recursion
function pilau_cmb2_get_page_tree_options_populate( $pages, $previous_level_ids, &$options = array(), $level = 0 ) {
	static $children_count = array(); // Keep track of how many child pages have been added for each page
	// Reset static vars?
	if ( empty( $options ) ) {
		$children_count = array();
	}
	$this_level_ids = array();

	// Loop through pages results and add in pages at current level
	foreach ( $pages as $page ) {

		// Is this page's parent in the previous level?
		if ( in_array( $page->post_parent, $previous_level_ids ) ) {

			// Register in the children count and level IDs arrays
			if ( ! empty( $children_count[ $page->post_parent ] ) ) {
				$children_count[ $page->post_parent ] = 1;
			} else {
				$children_count[ $page->post_parent ]++;
			}
			$this_level_ids[] = $page->ID;

			// If we're at the top level, adding is simple
			if ( $level == 0 ) {

				$options[ $page->ID ] = $page->post_title;

			} else {

				// If we're any lower than the top level, we need to work out where to insert

				// Find the position of the parent
				$parent_pos = array_search( $page->post_parent, array_keys( $options ) );

				// Account for existing children
				$insert_at_pos = $parent_pos + $children_count[ $page->post_parent ];

				// Add the page in after the parent, and after any existing child pages
				$options =	array_slice( $options, 0, $insert_at_pos, true ) +
						array( $page->ID => str_repeat( '&#151;', $level ) . ' ' . $page->post_title ) +
						array_slice( $options, $insert_at_pos, count( $options ) - 1, true );

			}

		}

	}
	//echo '<pre>'; print_r( $options ); echo '</pre>'; exit;

	// Recurse?
	if ( count( $options ) < count( $pages ) ) {
		pilau_cmb2_get_page_tree_options_populate( $pages, $this_level_ids, $options, $level + 1 );
	}

	return $options;
}


/**
 * Get terms to populate CMB2 options
 *
 * @param		string	$taxonomy
 * @param		array	$args
 * @return		array
 */
function pilau_cmb2_get_term_options( $taxonomy, $args = array() ) {

	$args = wp_parse_args( $args, array(
		'hide_empty'		=> false,
	) );

	$terms = get_terms( $taxonomy, $args );

	$term_options = array();
	if ( $terms ) {
		foreach ( $terms as $term ) {
			$term_options[ $term->term_id ] = $term->name;
		}
	}

	return $term_options;
}


add_filter( 'cmb2_sanitize_text', 'pilau_cmb2_sanitize_text', 10, 5 );
add_filter( 'cmb2_sanitize_text_small', 'pilau_cmb2_sanitize_text', 10, 5 );
add_filter( 'cmb2_sanitize_text_medium', 'pilau_cmb2_sanitize_text', 10, 5 );
add_filter( 'cmb2_sanitize_textarea', 'pilau_cmb2_sanitize_text', 10, 5 );
add_filter( 'cmb2_sanitize_textarea_small', 'pilau_cmb2_sanitize_text', 10, 5 );
/**
 * Custom text sanitization to allow HTML
 *
 * Set a field's 'allow_html' argument to an array containing the HTML tags
 * allowed
 *
 * @param		string	$override_value	Passed as null; return non-null to override default sanitization
 * @param		string	$value				The entered value
 * @param		int		$object_id			The ID of the object
 * @param		array	$field_args		Field arguments
 * @param		object	$cmb2_sanitize
 * @return		string
 */
function pilau_cmb2_sanitize_text( $override_value, $value, $object_id, $field_args, $cmb2_sanitize ) {

	// allow_html argument
	if ( ! empty( $field_args['allow_html'] ) && is_array( $field_args['allow_html'] ) ) {
		$kses_array = array();
		foreach ( $field_args['allow_html'] as $tag ) {
			$kses_array[ $tag ] = array();
		}
		$override_value = wp_kses( $value, $kses_array );
	}

	return $override_value;
}


//add_filter( 'teeny_mce_before_init', 'pilau_teenymce_buttons' );
/**
 * Adjust buttons for teeny version of TinyMCE
 *
 * @since	Pilau_Starter 0.1
 * @link	http://wordpress.stackexchange.com/questions/141534/how-to-customize-tinymce4-in-wp-3-9-the-old-way-for-styles-and-formats-doesnt
 */
function pilau_teenymce_buttons( $init_array ) {
	//echo '<pre>'; print_r( $init_array ); echo '</pre>'; exit;

	// selector will be the custom field key (with prefix), all dashes replaced by underscores, prefixed by hash
	if ( in_array( $init_array['selector'], array( '#' . pilau_cmb2_meta_key( 'some_field_or_other' ) ) ) ) {
		$init_array['toolbar1'] = 'bold,italic,link,unlink,charmap,undo,redo';
	}

	return $init_array;
}


add_filter( 'cmb2_row_classes', 'pilau_cmb2_row_classes', 10, 2 );
/**
 * Filter row classes for CMB fields
 */
function pilau_cmb2_row_classes( $classes, $field ) {

	if ( ! empty( $field->args['show_when_target'] ) ) {
		// Add 'show when' target classes
		$classes .= ' cmb-show-when-target cmb-show-when-field-' . $field->args['show_when_target']['source_field'] . ' cmb-show-when-value-' . $field->args['show_when_target']['source_value'];
	}

	if ( ! empty( $field->args['show_when_source'] ) ) {
		// Add 'show when' source classes
		$classes .= ' cmb-show-when-source';
	}

	return $classes;
}