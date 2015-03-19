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

	// Use custom fields for Admin Columns
	if ( PILAU_PLUGIN_EXISTS_ADMIN_COLUMNS ) {
		add_filter( 'cpac_use_hidden_custom_fields', '__return_true' );
	}

	/* General settings
	********************************************************************************/

	// Exclude pages from dynamic nav
	// Nav queries must explicitly reference this field to exclude
	slt_cf_register_box( array(
		'type'		=> 'post',
		'title'		=> 'Navigation',
		'id'		=> 'navigation-box',
		'context'	=> 'side',
		'priority'	=> 'high',
		'fields'	=> array(
			array(
				'name'			=> 'exclude-from-nav',
				'type'			=> 'checkbox',
				'label'			=> __( 'Exclude this page from dynamic navigation' ),
				'description'	=> __( 'This will exclude any descendant pages, too.' ),
				'scope'			=> array( 'page' ),
				'capabilities'	=> array( 'publish_pages' )
			),
		)
	));

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
