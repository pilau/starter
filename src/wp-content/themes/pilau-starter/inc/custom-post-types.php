<?php

/**
 * Custom Post Types
 *
 * NOTE: When defining a rewrite slug for a CPT, make it as short as possible.
 * Then use the redirects in .htaccess to redirect this slug with no CPT postname
 * to the actual listing page URL.
 *
 * @package	Pilau_Starter
 * @since	0.1
 */


add_action( 'init', 'pilau_register_post_types', 0 );
/**
 * Register custom post types
 *
 * @since	0.1
 * @return	void
 */
function pilau_register_post_types() {

	/*
	 * For CPTs to support authors:
	 * @link	http://themehybrid.com/weblog/members-role-levels-wordpress-plugin
	 * @link	http://themehybrid.com/weblog/correcting-the-author-meta-box-drop-down
	 */

	/*
	register_post_type(
		'project', array(
			'label'					=> _x( 'projects', 'post type plural name' ),
			'labels'				=> array(
				'name'					=> _x( 'Projects', 'post type general name' ),
				'singular_name'			=> _x( 'Project', 'post type singular name' ),
				'menu_name'				=> _x( 'Projects', 'admin menu' ),
				'name_admin_bar'		=> _x( 'Project', 'add new on admin bar' ),
				'add_new'				=> _x( 'Add New', 'project' ),
				'add_new_item'			=> __( 'Add New Project' ),
				'new_item'				=> __( 'New Project' ),
				'edit_item'				=> __( 'Edit Project' ),
				'view_item'				=> __( 'View Project' ),
				'all_items'				=> __( 'All Projects' ),
				'search_items'			=> __( 'Search Projects' ),
				'parent_item_colon'		=> __( 'Parent Projects:' ),
				'not_found'				=> __( 'No Projects found.' ),
				'not_found_in_trash'	=> __( 'No Projects found in Trash.' ),
				'featured_image'		=> __( 'Featured image' ),
				'set_featured_image'	=> __( 'Set featured image' ),
				'remove_featured_image'	=> __( 'Remove featured image' ),
				'use_featured_image'	=> __( 'Use as featured image' ),
				'insert_into_item'		=> __( 'Insert into content' ),
				'uploaded_to_this_item'	=> __( 'Uploaded to this post' ),
			),
			'public'				=> true,
			'publicly_queryable'	=> true,
			'show_ui'				=> true,
			'show_in_nav_menus'		=> true,
			'show_in_menu'			=> true,
			'show_in_admin_bar'		=> true,
			'menu_position'			=> 20, // Below Pages
			'menu_icon'				=> 'dashicons-portfolio', // @link https://developer.wordpress.org/resource/dashicons/
			'query_var'				=> true,
			'rewrite'				=> array( 'slug' => 'project', 'with_front' => false ),
			'capability_type'		=> 'project',
			'map_meta_cap'			=> false,
			'capabilities' => array(
				// meta caps (don't assign these to roles, they're handled in pilau_cpt_map_meta_cap)
				'edit_post'              => 'edit_project',
				'read_post'              => 'read_project',
				'delete_post'            => 'delete_project',
				// primitive/meta caps
				'create_posts'           => 'create_projects',
				// primitive caps used outside of map_meta_cap()
				'edit_posts'             => 'edit_projects',
				'edit_others_posts'      => 'manage_projects',
				'publish_posts'          => 'manage_projects',
				'read_private_posts'     => 'read',
				// primitive caps used inside of map_meta_cap()
				'read'                   => 'read',
				'delete_posts'           => 'manage_projects',
				'delete_private_posts'   => 'manage_projects',
				'delete_published_posts' => 'manage_projects',
				'delete_others_posts'    => 'manage_projects',
				'edit_private_posts'     => 'edit_projects',
				'edit_published_posts'   => 'edit_projects'
			),
			'has_archive'			=> false,
			'hierarchical'			=> false,
			'supports'				=> array( 'title', 'editor', 'custom-fields', 'thumbnail', 'revisions' ),
			'taxonomies'			=> array( 'projecttype' ),
		)
	);
	*/


	/*
	// FAQs
	register_post_type(
		'faq', array(
			'label'					=> _x( 'FAQs', 'post type plural name' ),
			'labels'				=> array(
				'name'					=> _x( 'FAQs', 'post type general name' ),
				'singular_name'			=> _x( 'FAQ', 'post type singular name' ),
				'menu_name'				=> _x( 'FAQs', 'admin menu' ),
				'name_admin_bar'		=> _x( 'FAQ', 'add new on admin bar' ),
				'add_new'				=> _x( 'Add New', 'FAQ' ),
				'add_new_item'			=> __( 'Add New FAQ' ),
				'new_item'				=> __( 'New FAQ' ),
				'edit_item'				=> __( 'Edit FAQ' ),
				'view_item'				=> __( 'View FAQ' ),
				'all_items'				=> __( 'All FAQs' ),
				'search_items'			=> __( 'Search FAQs' ),
				'parent_item_colon'		=> __( 'Parent FAQs:' ),
				'not_found'				=> __( 'No FAQs found.' ),
				'not_found_in_trash'	=> __( 'No FAQs found in Trash.' )
			),
			'public'				=> false,
			'publicly_queryable'	=> false,
			'show_ui'				=> true,
			'show_in_nav_menus'		=> false,
			'show_in_menu'			=> true,
			'show_in_admin_bar'		=> true,
			'menu_position'			=> 20, // Below Pages
			'menu_icon'				=> 'dashicons-editor-help', // @link https://developer.wordpress.org/resource/dashicons/
			'query_var'				=> false,
			'rewrite'				=> false,
			'capability_type'		=> 'faq',
			'map_meta_cap'			=> false,
			'capabilities' => array(
				'publish_posts'			=> 'publish_faqs',
				'edit_posts'			=> 'edit_faqs',
				'edit_others_posts'		=> 'edit_others_faqs',
				'delete_posts'			=> 'delete_faqs',
				'delete_others_posts'	=> 'delete_others_faqs',
				'read_private_posts'	=> 'read_private_faqs',
				'edit_post'				=> 'edit_faq',
				'delete_post'			=> 'delete_faq',
				'read_post'				=> 'read_faq',
			),
			'has_archive'			=> false,
			'hierarchical'			=> false,
			'supports'				=> array( 'title', 'editor', 'custom-fields' ),
		)
	);
	 */

	// Generated by Pilau Init
	//[[custom-post-types]]


}


//add_filter( 'post_type_link', 'pilau_custom_post_type_link', 10, 2 );
/**
 * Customise permalinks on output
 *
 * If custom post type permalinks are dynamic, containing placeholders, replace
 * them here.
 */
function pilau_custom_post_type_link( $url, $post ) {

	// Permalinks with region placeholder
	if ( strpos( $url, '%region%' ) !== false ) {

		// Is there a region for the post?
		if ( $region = get_post_meta( $post->ID, pilau_cmb2_meta_key( 'region' ), true ) ) {

			// Replace
			$url = str_replace( '%region%', urlencode( get_post_field( 'post_name', $region ) ), $url );

		}

	}

	return $url;
}


add_filter( 'simple_page_ordering_is_sortable', 'pilau_simple_page_ordering_is_sortable', 10, 2 );
/**
 * Flag CPTs which should be sortable (click 'Sort by Order' for drag-and-drop)
 *
 * This seems to be necessary because of problems getting the single-{$post_type}.php
 * template to work for CPTs that are set to be hierarchical (the other way of making
 * CPTs orderable). Because there's also an issue with hierarchical CPTs causing memory
 * issues, this approach seems to be best for now.
 * @link	http://wordpress.stackexchange.com/questions/203715/templates-for-hierarchical-custom-post-type
 */
function pilau_simple_page_ordering_is_sortable( $sortable, $post_type ) {
	return in_array( $post_type, array(
		'page'
	));
}


/**
 * Get public post type names (includes CPTs, excludes attachments)
 *
 * @return	array
 */
function pilau_get_public_post_type_names() {
	return array_merge( array( 'page', 'post' ), pilau_get_public_custom_post_type_names() );
}


/**
 * Get public custom post type names
 *
 * @return	array
 */
function pilau_get_public_custom_post_type_names() {
	return get_post_types( array( '_builtin' => false, 'public' => true ), 'names' );
}


add_filter( 'map_meta_cap', 'pilau_cpt_map_meta_cap', 10, 4 );
/**
 * Map meta caps for CPTs
 *
 * @link	http://justintadlock.com/archives/2010/07/10/meta-capabilities-for-custom-post-types
 * @uses	get_post_types()
 * @uses	get_post()
 * @uses	get_post_type_object()
 * @param	array  $caps    The user's actual capabilities
 * @param	string $cap     Capability name
 * @param	int    $user_id The user ID
 * @param	array  $args    Adds the context to the cap. Typically the object ID
 * @return	array
 */
function pilau_cpt_map_meta_cap( $caps, $cap, $user_id, $args ) {
	$cap_roots = array( 'edit', 'delete', 'read' );
	$cpts = get_post_types( array( '_builtin' => false ), 'names' );
	$cap_parts = explode( '_', $cap );
	$cap_root = array_shift( $cap_parts ); // The first bit before an underscore
	$cap_cpt = implode( '_', $cap_parts ); // The other bits after the first underscore

	// If editing, deleting, or reading a CPT, get the post and post type object
	if ( in_array( $cap_root, $cap_roots ) && in_array( $cap_cpt, $cpts ) ) {
		$post = get_post( $args[0] );
		$post_type = get_post_type_object( $post->post_type );
		// Set an empty array for the caps
		$caps = array();

		switch ( $cap_root ) {

			case 'edit': {
				// Editing
				if ( $user_id == $post->post_author ) {
					$caps[] = $post_type->cap->edit_posts;
				} else {
					$caps[] = $post_type->cap->edit_others_posts;
				}
				break;
			}

			case 'delete': {
				// Deleting...
				if ( $user_id == $post->post_author ) {
					$caps[] = $post_type->cap->delete_posts;
				} else {
					$caps[] = $post_type->cap->delete_others_posts;
				}
				break;
			}

			case 'read': {
				// Reading
				if ( $post->post_status != 'private' || $post->post_author == $user_id ) {
					$caps[] = 'read';
				} else {
					$caps[] = $post_type->cap->read_private_posts;
				}
				break;
			}

		}

	}

	// Return the capabilities required by the user
	return $caps;
}


/* Managing CPT ancestors
 *****************************************************************************************/


add_action( 'init', 'pilau_define_post_type_ancestors' );
/**
 * Define the basic post type ancestors
 */
function pilau_define_post_type_ancestors() {
	global $pilau_post_type_ancestors;

	// [post_type]	=> array( [parent ID], [grandparent ID], etc. )
	$pilau_post_type_ancestors = array(
	);

}


/**
 * Get the ancestors of a CPT
 *
 * @since	0.2
 * @param	int|WP_Post		$post	Post ID or post object
 * @return	array
 */
function pilau_get_ancestors( $post = null ) {
	global $pilau_post_type_ancestors;
	$ancestors = array();
	if ( is_null( $post ) ) {
		$post = get_queried_object_id();
	}
	$post_id = is_object( $post ) ? $post->ID : $post;

	if ( $post_id ) {

		if ( get_post_type( $post_id ) == 'page' ) {

			$ancestors = get_post_ancestors( $post_id );

		} else {

			if ( is_int( $post_id ) && array_key_exists( get_post_type( $post_id ), $pilau_post_type_ancestors ) ) {
				$ancestors = $pilau_post_type_ancestors[ get_post_type( $post_id ) ];

				// Exceptions to assign dynamically
				/*
				switch ( get_post_type( $post ) ) {

					case 'service': {
						$ancestors = array( get_post_meta( $post_id, pilau_cmb2_meta_key( 'region' ), true ), PILAU_PAGE_ID_LOCAL_SERVICES );
						break;
					}

				}
				*/

			}

		}

	}

	return $ancestors;
}


/**
 * Check if a given page is an ancestor of another page (usually the current one),
 * accounting for CPTs
 *
 * @since	0.2
 * @param	int		$page_id
 * @return	bool
 */
function pilau_is_ancestor( $maybe_ancestor_id, $page_id = PILAU_PAGE_ID_CURRENT ) {
	$is_ancestor = false;
	if ( get_post_type() == 'page' ) {
		$is_ancestor = in_array( $maybe_ancestor_id, get_post_ancestors( $page_id ) );
	} else if ( is_singular( 'post' ) ) {
		$is_ancestor = $maybe_ancestor_id == get_option( 'page_for_posts' );
	} else {
		$is_ancestor = in_array( $maybe_ancestor_id, pilau_get_ancestors( $page_id ) );
	}
	return $is_ancestor;
}


/**
 * Back link for single post
 *
 * @since	0.1
 * @param	string	$link_text
 * @param	bool	$keep_referer_qs
 * @return	void
 */
function pilau_post_back_link( $link_text = null, $keep_referer_qs = false ) {
	global $post, $pilau_endpoint_page;

	// Link text
	if ( ! $link_text ) {
		$link_text = __( 'Back' );
	}

	// Link
	$link = null;
	if ( $pilau_endpoint_page ) {

		// For endpoints, "back" is to "current" post
		$link = get_permalink();

	} else {

		switch ( get_post_type() ) {
			case 'post': {
				if ( $posts_page_id = get_option( 'page_for_posts' ) ) {
					$link = get_permalink( $posts_page_id );
				} else {
					$link = get_home_url();
				}
				break;
			}
			case 'page': {
				$ancestors = get_post_ancestors( $post );
				$link = get_permalink( $ancestors[0] );
				break;
			}
			default: {
				// Custom post type
				$ancestors = pilau_get_ancestors( $post );
				$link = get_permalink( $ancestors[0] );
				break;
			}
		}

	}

	if ( $link ) {

		// Keep query string?
		if ( $keep_referer_qs && ! empty( $_SERVER['HTTP_REFERER'] ) && $query_string = parse_url( $_SERVER['HTTP_REFERER'], PHP_URL_QUERY ) ) {
			$link .= '?' . $query_string;
		}

		// Output
		echo '<p class="post-back-link"><a rel="index" href="' . $link . '">&laquo; ' . $link_text . '</a></p>';

	}

}


add_filter( 'nav_menu_css_class', 'pilau_cpt_nav_menu_css_class', 10, 2 );
/**
 * Custom classes for nav menus
 *
 * @since	0.1
 */
function pilau_cpt_nav_menu_css_class( $classes, $item ) {
	if ( get_post_type() != 'page' && pilau_is_ancestor( $item->object_id ) ) {
		$classes[] = 'current-menu-ancestor';
	}
	return $classes;
}




add_filter( 'wpseo_breadcrumb_links', 'pilau_wpseo_breadcrumb_links' );
/**
 * Filter Yoast breadcrumbs to do custom stuff
 *
 * @since	0.1
 */
function pilau_wpseo_breadcrumb_links( $links ) {
	global $pilau_endpoint_page;

	// Check for single CPT posts and endpoint pages and add ancestors
	if (	in_array( get_post_type(), pilau_get_public_custom_post_type_names() ) ||
			$pilau_endpoint_page
	) {
		$ancestors = pilau_get_ancestors();

		foreach ( array_reverse( $ancestors ) as $ancestor ) {
			array_splice( $links, -1, 0, array(
				array(
					'id'	=> $ancestor
				)
			));
		}

		// Endpoint pages
		switch ( $pilau_endpoint_page ) {
			case 'enquiries': {
				$links[] = array(
					'text'		=> __( 'Enquiries' ),
				);
				break;
			}
		}

	}

	return $links;
}
