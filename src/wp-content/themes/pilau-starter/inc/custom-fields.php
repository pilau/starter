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


if ( PILAU_PLUGIN_EXISTS_CMB2 ) {
	add_action( 'cmb2_init', 'pilau_cmb2_custom_fields' );
}
/**
 * Register custom fields
 *
 * @uses new_cmb2_box()
 * @since	Pilau_Starter 0.1
 */
function pilau_cmb2_custom_fields() {
	global $pilau_slideshow_content_types, $pilau_slideshow_pages;


	/* General settings
	********************************************************************************/

	/*
	// Longer teaser text
	$cmb = new_cmb2_box( array(
		'id'			=> 'teaser_text_box',
		'title'			=> __( 'Teaser text' ),
		'object_types'	=> array( 'page', 'post' ),
		'show_on'		=> array( 'key' => 'user-can', 'value' => 'publish_pages' ),
		'context'		=> 'normal',
		'priority'		=> 'high',
		'show_names'	=> true,
		'closed'		=> true,
	));
	$cmb->add_field( array(
		'name'				=> __( 'Teaser text' ),
		'desc'				=> __( 'Usually teaser text is taken from the SEO meta description if possible, or else an extract is taken from the main content. For some places, longer teaser text is needed than is good for the meta description, and an automated extract is unsuitable - this text can be used instead.' ),
		'id'				=> pilau_cmb2_meta_key( 'teaser-text' ),
		'type'				=> 'textarea_small',
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
			'show_on_multiple'	=> array(
				array(
					'key' 			=> 'user-can',
					'value'			=> 'publish_pages'
				),
			),
			'context'			=> 'normal',
			'priority'			=> 'high',
			'show_names'		=> true,
			'closed'			=> false,
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
				'id'			=> pilau_cmb2_meta_key( 'slideshow-item-' . $i ),
				'name'			=> 'Item ' . $i,
				'type'			=> 'select',
				'options'		=> pilau_cmb2_get_post_options( array(
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
			'show_on'	=> array(
				'key' 		=> 'user-can',
				'value'		=> 'publish_pages'
			),
			'context'			=> 'normal',
			'priority'			=> 'high',
			'show_names'		=> true,
			'closed'			=> false,
		));
		$cmb->add_field( array(
			'name'				=> __( 'Heading' ),
			'id'				=> pilau_cmb2_meta_key( 'slideshow-heading' ),
			'type'				=> 'text',
			'on_front'			=> false,
		) );
		$cmb->add_field( array(
			'name'				=> __( 'Image' ),
			'id'				=> pilau_cmb2_meta_key( 'slideshow-image' ),
			'type'				=> 'file',
			'options'			=> array(
				'url'		=> false,
			),
			'desc'				=> __( 'Optimum image size: 1920px wide, 640px high.' ),
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


//add_filter( 'cmb2_show_on', 'pilau_cmb2_show_on', 10, 3 );
/**
 * All custom show_on filters
 *
 * @param bool $display
 * @param array $meta_box
 * @return bool display metabox
 */
function pilau_cmb2_show_on( $display, $metabox, $cmb ) {
	if ( empty( $metabox['show_on'] ) && empty( $metabox['show_on_multiple'] ) ) {
		return $display;
	}

	// Build array of restrictions
	$restrictions = array();
	if ( ! empty( $metabox['show_on'] ) ) {
		$restrictions[] = $metabox['show_on'];
	}
	if ( ! empty( $metabox['show_on_multiple'] ) ) {
		$restrictions .= $metabox['show_on_multiple'];
	}

	// Check them all
	foreach ( $restrictions as $restriction ) {
		switch ( $restriction['key'] ) {

			// Capability check
			case 'user-can': {
				$display = current_user_can( $restriction['value'] );
				break;
			}

		}

		// Break if restriction already triggered
		if ( ! $display ) {
			break;
		}

	}

	return $display;
}


/**
 * Get posts to populate CMB2 options
 *
 * @param		array	$query_args
 * @return		array
 */
function pilau_cmb2_get_post_options( $query_args ) {

	$args = wp_parse_args( $query_args, array(
		'post_type'			=> 'post',
		'posts_per_page'	=> -1,
	) );

	$posts = get_posts( $args );

	$post_options = array();
	if ( $posts ) {
		foreach ( $posts as $post ) {
			$post_options[ $post->ID ] = $post->post_title;
		}
	}

	return $post_options;
}
