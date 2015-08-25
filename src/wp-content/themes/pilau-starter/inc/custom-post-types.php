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
				'not_found_in_trash'	=> __( 'No Projects found in Trash.' )
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
			'hierarchical'			=> false, // Set to true to allow ordering
			'supports'				=> array( 'title', 'editor', 'custom-fields', 'thumbnail', 'revisions' ),
			'taxonomies'			=> array( 'projecttype' ),
		)
	);
	*/


	// Generated by Pilau Init
	//[[custom-post-types]]


}


/**
 * Get CPTs (cached)
 *
 * @param	string	$output		'names' | 'objects'
 * @return	array
 */
function pilau_get_cpts( $output = 'names' ) {
	if ( false === ( $cpts = get_transient( 'pilau_cpts_' . $output ) ) || isset( $_GET['refresh'] ) ) {
		$cpts = get_post_types( array( '_builtin' => false ), $output );
		set_transient( 'pilau_cpts_' . $output, $cpts, 60*60*24 ); // Cache for 24 hours
	}
	return $cpts;
}


add_filter( 'map_meta_cap', 'pilau_cpt_map_meta_cap', 10, 4 );
/**
 * Map meta caps for CPTs
 *
 * @link	http://justintadlock.com/archives/2010/07/10/meta-capabilities-for-custom-post-types
 * @uses	pilau_get_cpts()
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
	$cpts = pilau_get_cpts();
	$cap_parts = explode( '_', $cap );

	if ( count( $cap_parts ) == 2 ) {
		$cap_root = $cap_parts[0];
		$cap_cpt = $cap_parts[1];

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

	}

	// Return the capabilities required by the user
	return $caps;
}


/* Managing CPT ancestors
 *****************************************************************************************/


add_action( 'after_setup_theme', 'pilau_define_cpt_ancestors' );
/**
 * Define CPT ancestors
 *
 * To situate non-page post types within the page hierarchy:
 * [post_type]	=> array( [parent ID], [grandparent ID], etc. )
 *
 * Also includes post, as a non-hierarchical core post type
 *
 * @since	0.2
 */
function pilau_define_cpt_ancestors() {
	global $pilau_cpt_ancestors;
	$pilau_cpt_ancestors = array(
	);
}


/**
 * Get the ancestors of a CPT
 *
 * @since	0.2
 * @param	int|WP_Post		$post	Post ID or post object
 * @return	array
 */
function pilau_get_cpt_ancestors( $post ) {
	global $pilau_cpt_ancestors;
	if ( ( ! is_int( $post ) && ! is_object( $post ) ) || ! array_key_exists( get_post_type( $post ), $pilau_cpt_ancestors ) ) {
		return array();
	}
	return $pilau_cpt_ancestors[ get_post_type( $post ) ];
}


/**
 * Check if a given page is an ancestor of the current page, accounting for CPTs
 *
 * @since	0.2
 * @param	int		$page_id
 * @return	bool
 */
function pilau_is_ancestor( $page_id ) {
	$is_ancestor = false;
	if ( get_post_type() == 'page' ) {
		$is_ancestor = in_array( $page_id, get_post_ancestors( PILAU_PAGE_ID_CURRENT ) );
	} else if ( is_singular( 'post' ) ) {
		$is_ancestor = $page_id == get_option( 'page_for_posts' );
	} else {
		$is_ancestor = in_array( $page_id, pilau_get_cpt_ancestors( PILAU_PAGE_ID_CURRENT ) );
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
	global $post;

	// Link text
	if ( ! $link_text ) {
		$link_text = __( 'Back' );
	}

	// Link
	$link = null;
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
			$ancestors = pilau_get_cpt_ancestors( $post );
			$link = get_permalink( $ancestors[0] );
			break;
		}
	}

	if ( $link ) {

		// Keep query string?
		if ( $keep_referer_qs ) {
			$link .= '?' . parse_url( $_SERVER['HTTP_REFERER'], PHP_URL_QUERY );
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
 * @since	Trollope_Society 0.1
 */
function pilau_wpseo_breadcrumb_links( $links ) {
	global $post, $pilau_cpt_ancestors;

	// Check for single CPT posts and add ancestors
	if ( is_single() && get_post_type() != 'post' && ! empty( $pilau_cpt_ancestors[ get_post_type() ] ) ) {

		foreach ( array_reverse( $pilau_cpt_ancestors[ get_post_type() ] ) as $ancestor ) {
			array_splice( $links, -1, 0, array(
				array(
					'id'	=> $ancestor
				)
			));
		}

	}

	return $links;
}
