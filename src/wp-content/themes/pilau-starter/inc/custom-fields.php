<?php


/**
 * Custom meta fields for posts and users
 *
 * Depends on the Developer's Custom Fields plugin
 * @link http://sltaylor.co.uk/wordpress/developers-custom-fields-docs/
 *
 * @package	Pilau_Starter
 * @since	0.1
 */


if ( PILAU_PLUGIN_EXISTS_DEVELOPERS_CUSTOM_FIELDS ) {
	add_action( 'init', 'pilau_register_custom_fields', 5 );
}
/**
 * Register custom fields
 *
 * @uses slt_cf_register_box()
 * @since	Pilau_Starter 0.1
 */
function pilau_register_custom_fields() {
	global $pilau_slideshow_content_types, $pilau_slideshow_pages;

	// Use custom fields for Admin Columns
	if ( PILAU_PLUGIN_EXISTS_ADMIN_COLUMNS ) {
		add_filter( 'cpac_use_hidden_custom_fields', '__return_true' );
	}

	/* General settings
	********************************************************************************/

	/*
	// Longer teaser text
	slt_cf_register_box( array(
		'type'		=> 'post',
		'title'		=> 'Teaser text',
		'id'		=> 'teaser-text-box',
		'context'	=> 'above-content',
		'priority'	=> 'high',
		'description'	=> __( 'Usually teaser text is taken from the SEO meta description if possible, or else an extract is taken from the main content. For some places, longer teaser text is needed than is good for the meta description, and an automated extract is unsuitable - this text can be used instead.' ),
		'fields'	=> array(
			array(
				'name'			=> 'teaser-text',
				'label'			=> 'Teaser text',
				'type'			=> 'textarea',
				'height'		=> 5,
				'hide_label'	=> true,
				'scope'			=> array( 'post', 'page' ),
				'capabilities'	=> array( 'publish_pages' )
			),
		)
	));
	*/

	/* Slideshows
	********************************************************************************/

	if ( PILAU_SLIDESHOW_ITEMS ) {

		// Slideshow config
		$slideshow_fields = array(
			array(
				'name'			=> 'slideshow-autoplay',
				'label'			=> 'Number of seconds to pause between automatic advance to next slide',
				'type'			=> 'text',
				'width'			=> 3,
				'default'		=> 0,
				'description'	=> __( 'Enter 0 (zero) to disable autoplay.' ),
				'scope'			=> $pilau_slideshow_pages,
				'capabilities'	=> array( 'publish_pages' )
			),
		);
		for ( $i = 1; $i <= PILAU_SLIDESHOW_ITEMS; $i++ ) {
			$slideshow_fields[] = array(
				'name'			=> 'slideshow-item-' . $i,
				'label'			=> 'Item ' . $i,
				'type'			=> 'select',
				'options_type'	=> 'posts',
				'options_query'	=> array(
					'post_type'			=> $pilau_slideshow_content_types,
					'posts_per_page'	=> -1,
					'orderby'			=> 'title',
					'order'				=> 'ASC',
					'meta_query'		=> array(
						array(
							'key'			=> slt_cf_field_key( 'slideshow-image' ),
							'compare'		=> 'EXISTS',
						),
						array(
							'key'			=> slt_cf_field_key( 'slideshow-heading' ),
							'compare'		=> 'EXISTS',
						),
						array(
							'key'			=> slt_cf_field_key( 'slideshow-teaser' ),
							'compare'		=> 'EXISTS',
						),
						array(
							'key'			=> slt_cf_field_key( 'slideshow-button-text' ),
							'compare'		=> 'EXISTS',
						),
					),
				),
				'group_options'			=> true,
				'group_by_post_type'	=> true,
				'scope'					=> array( 'posts' => array( PILAU_PAGE_ID_HOME ) ),
				'capabilities'			=> array( 'publish_pages' )
			);
		}
		slt_cf_register_box( array(
			'type'			=> 'post',
			'title'			=> 'Slideshow configuration',
			'id'			=> 'slideshow-config-box',
			'context'		=> 'above-content',
			'priority'		=> 'high',
			'description'	=> __( 'To include something in the carousel, edit that content and supply the image and text. Then that content will become available to be selected here.' ),
			'fields'		=> $slideshow_fields
		));

		// Slideshow content
		slt_cf_register_box( array(
			'type'			=> 'post',
			'title'			=> 'Slideshow content',
			'id'			=> 'slideshow-content-box',
			'context'		=> 'normal',
			'priority'		=> 'high',
			'description'	=> __( 'Populate these fields to make this content available for slideshows.' ),
			'fields'	=> array(
				array(
					'name'			=> 'slideshow-heading',
					'label'			=> 'Heading',
					'type'			=> 'text',
					'scope'			=> $pilau_slideshow_content_types,
					'capabilities'	=> array( 'publish_pages' )
				),
				array(
					'name'					=> 'slideshow-image',
					'label'					=> 'Image',
					'type'					=> 'file',
					'description'			=> __( 'Optimum image size: 1920px wide, 640px high.' ),
					'file_button_label'		=> __( 'Select image' ),
					'file_dialog_title'		=> __( 'Select image' ),
					'file_restrict_to_type'	=> 'image',
					'preview_size'			=> 'large',
					'scope'					=> $pilau_slideshow_content_types,
					'capabilities'			=> array( 'publish_pages' )
				),
				array(
					'name'			=> 'slideshow-teaser',
					'label'			=> 'Teaser text',
					'type'			=> 'textarea',
					'height'		=> 4,
					'scope'			=> $pilau_slideshow_content_types,
					'capabilities'	=> array( 'publish_pages' )
				),
				array(
					'name'			=> 'slideshow-button-text',
					'label'			=> 'Button text',
					'type'			=> 'text',
					'default'		=> __( 'Read more' ),
					'scope'			=> $pilau_slideshow_content_types,
					'capabilities'	=> array( 'publish_pages' )
				),
			)
		));

	}

}


/**
 * Custom field checker
 *
 * @since	Pilau_Starter 0.1

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
