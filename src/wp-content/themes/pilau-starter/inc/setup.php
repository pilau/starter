<?php

/**
 * Initial theme setup
 *
 * @package	Pilau_Starter
 * @since	0.1
 */


add_action( 'after_setup_theme', 'pilau_setup' );
/**
 * Set up theme
 *
 * @since	Pilau_Starter 0.1
 */
function pilau_setup() {
	global $pilau_site_settings, $pilau_filters, $pilau_download_mime_types, $pilau_use_breadcrumbs, $pilau_gforms_protected;

	// Enable shortcodes in widgets
	add_filter( 'widget_text', 'do_shortcode' );

	// Override core automatic feed links
	// @see inc/feeds.php
	remove_theme_support( 'automatic-feed-links' );

	// Post formats - may be useful for some blog-heavy projects
	// @link http://codex.wordpress.org/Post_Formats
	//add_theme_support( 'post-formats', array( 'aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat' ) );

	// HTML5 support
	add_theme_support( 'html5', array( 'widgets', 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );

	// Title tag
	// @link https://make.wordpress.org/core/2014/10/29/title-tags-in-4-1/
	add_theme_support( 'title-tag' );

	// Hide admin bar for non-editors
	if ( ! current_user_can( 'edit_posts' ) ) {
		add_filter( 'show_admin_bar', '__return_false' );
	}

	// Register nav menus
	register_nav_menus( array(
		'nav_header' => __( 'Header navigation' ),
		'nav_footer' => __( 'Footer navigation' ),
	));

	// Site settings
	$pilau_site_settings = get_option( 'pilau_site_settings' );

	// Filtering (see inc/filtering.php)
	$pilau_filters = array();

	// Mime types
	$pilau_download_mime_types = array(
		'application/pdf',
		'application/msword',
		'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		'application/vnd.ms-excel',
		'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		'application/vnd.ms-powerpoint',
		'application/vnd.openxmlformats-officedocument.presentationml.presentation',
	);

	// Breadcrumbs flag (set to false before get_header() in template to override)
	$pilau_use_breadcrumbs = true;

	// Protected Gravity Forms
	// For now, just jQuery to remove interface elements - can't figure out a capabilities method
	$pilau_gforms_protected = array(); // forms IDs

}


add_action( 'tgmpa_register', 'pilau_register_required_plugins' );
/**
 * Register the required plugins for this theme
 *
 * @since	Pilau_Starter 0.1
 */
function pilau_register_required_plugins() {

	// Try to get plugin infos stored by pilau-init
	if ( $plugin_infos = get_option( 'pi_plugin_infos' ) ) {

		// Config options
		$config = array(
			'default_path' => '',                      // Default absolute path to pre-packaged plugins.
			'menu'         => 'tgmpa-install-plugins', // Menu slug.
			'has_notices'  => true,                    // Show admin notices or not.
			'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
			'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
			'is_automatic' => false,                   // Automatically activate plugins after installation or not.
			'message'      => '',                      // Message to output right before the plugins table.
			'strings'      => array(
				'page_title'                      => __( 'Install Required Plugins', 'tgmpa' ),
				'menu_title'                      => __( 'Install Plugins', 'tgmpa' ),
				'installing'                      => __( 'Installing Plugin: %s', 'tgmpa' ), // %s = plugin name.
				'oops'                            => __( 'Something went wrong with the plugin API.', 'tgmpa' ),
				'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ), // %1$s = plugin name(s).
				'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ), // %1$s = plugin name(s).
				'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s).
				'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s).
				'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s).
				'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s).
				'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s).
				'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s).
				'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
				'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins' ),
				'return'                          => __( 'Return to Required Plugins Installer', 'tgmpa' ),
				'plugin_activated'                => __( 'Plugin activated successfully.', 'tgmpa' ),
				'complete'                        => __( 'All plugins installed and activated successfully. %s', 'tgmpa' ), // %s = dashboard link.
				'nag_type'                        => 'updated' // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
			)
		);

		// Do it
		tgmpa( $plugin_infos, $config );

	}

}


if ( PILAU_USE_COOKIE_NOTICE ) {
	add_action( 'init', 'pilau_cookie_notice' );
}
/**
 * Cookie notice handling
 *
 * @since	Pilau_Starter 0.1
 * @todo	Implement more sophisticated cookie handling (JS?) to hide notice for users who have disabled cookies
 */
function pilau_cookie_notice() {
	global $pilau_cookie_notice_just_set;
	$pilau_cookie_notice_just_set = false;

	// Check for this domain in referrer
	if ( ! empty( $_SERVER['HTTP_REFERER'] ) && parse_url( $_SERVER['HTTP_REFERER'], PHP_URL_HOST ) == $_SERVER['SERVER_NAME'] ) {

		// Set cookie showing (implied) consent
		// Expires in 10 years
		setcookie( 'pilau_cookie_notice', 1, time() + ( 10 * 365 * 24 * 60 * 60 ), '/' );
		$pilau_cookie_notice_just_set = true;

	}

}


add_action( 'init', 'pilau_core_taxonomies' );
/**
 * Manage core taxonomies
 *
 * @since	Pilau_Starter 0.1
 * @link	http://w4dev.com/wp/remove-taxonomy/
 */
function pilau_core_taxonomies() {
	global $wp_taxonomies;

	/* Disable categories? */
	if ( taxonomy_exists( 'category' ) && ! PILAU_USE_CATEGORIES ) {
		unset( $wp_taxonomies['category'] );
	}

	/* Disable tags? */
	if ( taxonomy_exists( 'post_tag' ) && ! PILAU_USE_TAGS ) {
		unset( $wp_taxonomies['post_tag'] );
	}

}


if ( PILAU_RENAME_POSTS_NEWS ) {
	add_action( 'init', 'pilau_change_post_object_label' );
}
/**
 * Rename "Posts" in post type object to "News"
 *
 * @since	Pilau_Starter 0.1
 */
function pilau_change_post_object_label() {
	global $wp_post_types;
	$post = &$wp_post_types['post'];
	$post->label =						'News';
	$post->labels->name =				'News';
	$post->labels->singular_name =		'News';
	$post->labels->add_new =			'Add News';
	$post->labels->add_new_item =		'Add News';
	$post->labels->edit_item =			'Edit News';
	$post->labels->new_item =			'News';
	$post->labels->view_item =			'View News';
	$post->labels->search_items =		'Search News';
	$post->labels->not_found =			'No News found';
	$post->labels->not_found_in_trash =	'No News found in Trash';
	$post->labels->all_items =			'All News';
	$post->labels->menu_name =			'News';
	$post->labels->name_admin_bar =		'News';
}


if ( PILAU_FRONT_OR_AJAX ) {
	add_action( 'wp', 'pilau_setup_after_post', 0 );
}
/**
 * Set up that needs to happen when $post object is ready
 *
 * @since	Pilau_Starter 0.1
 */
function pilau_setup_after_post() {
	global $pilau_custom_fields, $post, $pilau_endpoint_page, $wp_query;
	$pilau_custom_fields = array();

	/*
	 * Determine current page ID, if we're on a page
	 *
	 * This may not be $post->ID, if we're on the blog home page.
	 * Set to false if the current view isn't related to a singular post or page.
	 */
	$current_page_id = null;
	if ( is_home() && ! is_front_page() ) {
		$current_page_id = get_option( 'page_for_posts' );
	} else if ( is_singular() ) {
		$current_page_id = $post->ID;
	}
	define( 'PILAU_PAGE_ID_CURRENT', $current_page_id );

	/*
	 * Determine top-level page
	 */
	$top_level_page_id = null;
	switch ( get_post_type() ) {
		case 'page': {
			$post_ancestors = get_post_ancestors( $post );
			if ( empty( $post_ancestors ) ) {
				$top_level_page_id = PILAU_PAGE_ID_CURRENT;
			} else {
				$top_level_page_id = $post_ancestors[ count( $post_ancestors ) - 1 ];
			}
			break;
		}
		case 'post': {
			$top_level_page_id = get_option( 'page_for_posts' );
			break;
		}
		default: {
			// Use virtual post hierarchy for non-pages
			$post_ancestors = pilau_get_ancestors( $post );
			if ( count( $post_ancestors ) ) {
				$top_level_page_id = $post_ancestors[ count( $post_ancestors ) - 1 ];
			}
			break;
		}
	}
	define( 'PILAU_PAGE_ID_TOP_LEVEL', $top_level_page_id );

	/*
	 * Get all custom fields for current post
	 */
	if ( PILAU_PAGE_ID_CURRENT ) {
		$pilau_custom_fields = pilau_get_custom_fields( PILAU_PAGE_ID_CURRENT, 'post' );
	}

	/*
	 * Pages managed by endpoints
	 */
	$pilau_endpoint_page = null;
	/*
	if ( in_array( get_post_type(), array( 'region', 'service', 'service_detail' ) ) && isset( $wp_query->query_vars['enquiries'] ) ) {
		$pilau_endpoint_page = 'enquiries';
	}
	*/

}


//add_action( 'template_redirect', 'pilau_process_endpoints' );
/**
 * Process rewrite endpoints
 */
function pilau_process_endpoints() {
	global $pilau_endpoint_page;

	switch ( $pilau_endpoint_page ) {
		case 'enquiries': {
			get_template_part( 'page_enquiries' );
			exit;
		}
	}

}


add_action( 'wp_enqueue_scripts', 'pilau_enqueue_scripts', 0 );
/**
 * Manage scripts for the front-end
 *
 * Always use the $ver parameter when registering or enqueuing styles or scripts, and
 * update it when deploying a new version - this helps prevent browser caching issues.
 * (Actually this is made redundant by using Better WordPress Minify, with its
 * appended parameter - but this is a good habit to get into ;-)
 *
 * @since	Pilau_Starter 0.1
 */
function pilau_enqueue_scripts() {
	// This test is done here because applying the test to the hook breaks due to pilau_is_login_page() not being defined yet...
	if ( ! is_admin() && ! pilau_is_login_page() ) {

		/*
		 * Note: All scripts are set to enqueue in footer, but jQuery would require some de-registering
		 * and re-registering trickery to get that in the footer safely. For now, Better WordPress Minify
		 * is used to manage putting scripts in the footer.
		 */
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'fitvids', get_stylesheet_directory_uri() . '/js/fitvids.js', array( 'jquery' ), '1.1', true );
		wp_enqueue_script( 'pilau-front-js', get_stylesheet_directory_uri() . '/js/front.js', array( 'jquery', 'fitvids' ), '1.0', true );

		// Google Maps API
		if ( false ) {
			wp_enqueue_script( 'google-maps-api', '//maps.googleapis.com/maps/api/js', array(), '3.20', true );
		}

		// Comment reply script - adjust the conditional if you need comments on post types other than 'post'
		if ( defined( 'PILAU_USE_COMMENTS' ) && PILAU_USE_COMMENTS && is_single() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply', false, array(), false, true );
		}

		// Picturefill
		// @link	http://scottjehl.github.io/picturefill/
		wp_enqueue_script( 'picturefill', get_stylesheet_directory_uri() . '/js/picturefill.js', array(), '2.3.1', true );

		// Slideshow
		if ( PILAU_SLIDESHOW_ITEMS ) {
			wp_enqueue_script( 'flickity', get_stylesheet_directory_uri() . '/js/flickity.js', array(), '1.0.0', true );
		}

		// Use this to pass the AJAX URL to the client when using AJAX - and any other PHP-to-JS vars
		// @link	http://wp.smashingmagazine.com/2011/10/18/how-to-use-ajax-in-wordpress/
		wp_localize_script( 'pilau-front-js', 'pilau_front', array(
			'ajaxurl'		=> admin_url( 'admin-ajax.php', PILAU_REQUEST_PROTOCOL ),
			'themeurl'		=> trailingslashit( get_stylesheet_directory_uri() )
		));

	}
}


add_action( 'wp_enqueue_scripts', 'pilau_enqueue_styles', 10 );
/**
 * Manage styles for the front-end
 *
 * Always use the $ver parameter when registering or enqueuing styles or scripts, and
 * update it when deploying a new version - this helps prevent browser caching issues.
 * (Actually this is made redundant by using Better WordPress Minify, with its
 * appended parameter - but this is a good habit to get into ;-)
 *
 * @since	Pilau_Starter 0.1
 */
function pilau_enqueue_styles() {
	// This test is done here because applying the test to the hook breaks due to pilau_is_login_page() not being defined yet...
	if ( ! is_admin() && ! pilau_is_login_page() ) {

		// Flickity
		if ( PILAU_SLIDESHOW_ITEMS ) {
			wp_enqueue_style( 'flickity', get_stylesheet_directory_uri() . '/styles/flickity.css', array(), '1.0.0' );
		}

		wp_enqueue_style( 'pilau-main', get_stylesheet_directory_uri() . '/styles/main.css', array(), '1.0' );
		wp_enqueue_style( 'pilau-print', get_stylesheet_directory_uri() . '/styles/print.css', array(), '1.0' );

	}
}


//add_action( 'login_enqueue_scripts', 'pilau_login_enqueue_scripts', PHP_INT_MAX );
/**
 * Login styles and scripts
 */
function pilau_login_enqueue_scripts() {
	wp_enqueue_style( 'pilau-login', get_stylesheet_directory_uri() . '/styles/login.css', array(), '1.0' );
}


add_filter( 'comments_open', 'pilau_comments_open', 10, 2 );
/**
 * Comments open
 */
function pilau_comments_open( $open, $post_id ) {

	if ( ! PILAU_USE_COMMENTS ) {
		$open = false;
	}

	return $open;
}

add_filter( 'get_comments_number', 'pilau_get_comments_number', 10, 2 );
/**
 * Comments number
 */
function pilau_get_comments_number( $number, $post_id ) {

	if ( ! PILAU_USE_COMMENTS ) {
		$number = 0;
	}

	return $number;
}