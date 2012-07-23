<?php

/**
 * Custom Post Types
 *
 * @package	Pilau_Starter
 * @since	0.1
 */

add_action( 'init', 'pilau_register_post_types', 0 );
function pilau_register_post_types() {

	/*
	register_post_type(
		'project', array(
			'labels'				=> array(
				'name'					=> __( 'Projects' ),
				'singular_name'		=> __( 'Project' ),
				'add_new'				=> __( 'Add New' ),
				'add_new_item'			=> __( 'Add New Project' ),
				'edit'					=> __( 'Edit' ),
				'edit_item'				=> __( 'Edit Project' ),
				'new_item'				=> __( 'New Project' ),
				'view'					=> __( 'View Project' ),
				'view_item'				=> __( 'View Project' ),
				'search_items'			=> __( 'Search Projects' ),
				'not_found'				=> __( 'No Projects found' ),
				'not_found_in_trash'	=> __( 'No Projects found in Trash' )
			),
			'public'				=> true,
			'menu_position'	=> 20,
			'supports'			=> array( 'title', 'editor', 'custom-fields', 'thumbnail', 'revisions' ),
			'taxonomies'		=> array( 'projecttype' ),
			'rewrite'			=> array( 'slug' => 'projects', 'with_front' => false )
		)
	);
	*/

}
