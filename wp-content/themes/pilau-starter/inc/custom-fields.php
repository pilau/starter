<?php


/**
 * Custom meta fields for posts and users
 *
 * Depends on the Developer's Custom Fields plugin
 * @link http://sltaylor.co.uk/wordpress/developers-custom-fields-docs/
 *
 * @package	[[theme-phpdoc-name]]
 * @since	0.1
 */


/**
 * Register custom fields
 *
 * @uses slt_cf_register_box()
 * @since	[[theme-phpdoc-name]] 0.1
 */
if ( function_exists( 'slt_cf_register_box') ) {
	add_action( 'init', 'pilau_register_custom_fields', 5 );
}
function pilau_register_custom_fields() {

	// Use custom fields for Admin Columns
	if ( class_exists( 'Codepress_Admin_Columns' ) ) {
		add_filter( 'cpac_use_hidden_custom_fields', '__return_true' );
	}

	/*
	slt_cf_register_box( array(
		'type'		=> 'post',
		'title'		=> 'Sample custom field',
		'id'		=> 'sample',
		'context'	=> 'advanced',
		'fields'	=> array(
			array(
				'name'			=> 'sample',
				'label'			=> 'Sample',
				'description'	=> 'Some hints for the user.',
				'type'			=> 'text',
				'scope'			=> array( 'post', 'page' ),
				'capabilities'	=> array( 'edit_posts', 'edit_pages' )
			)
		)
	));
	*/

}


/**
 * Custom field checker
 *
 * @since	[[theme-phpdoc-name]] 0.1

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
