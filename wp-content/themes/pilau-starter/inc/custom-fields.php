<?php


/**
 * Custom meta fields for posts and users
 *
 * Depends on the Developer's Custom Fields plugin
 * @link http://sltaylor.co.uk/wordpress/plugins/slt-custom-fields/docs/
 *
 * @package	Pilau_Starter
 * @since	0.1
 */


/**
 * Register custom fields
 *
 * @uses slt_cf_register_box
 * @since	Pilau_Starter 0.1
 */
if ( function_exists( 'slt_cf_register_box') )
	add_action( 'init', 'pilau_register_custom_fields', 5 );
function pilau_register_custom_fields() {

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
				'scope'			=>	array( 'post', 'page' ),
				'capabilities'	=> array( 'edit_posts', 'edit_pages' )
			)
		)
	));
	*/

}
