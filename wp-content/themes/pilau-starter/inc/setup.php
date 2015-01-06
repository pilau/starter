<?php

/**
 * Initial theme setup
 *
 * @package	[[theme-phpdoc-name]]
 * @since	0.1
 */


/**
 * Set up theme
 *
 * @since	[[theme-phpdoc-name]] 0.1
 */
add_action( 'after_setup_theme', 'pilau_setup', 10 );
function pilau_setup() {
	global $pilau_site_settings;

	/* Enable shortcodes in widgets */
	add_filter( 'widget_text', 'do_shortcode' );

	/*
	 * Override core automatic feed links
	 * @see inc/feeds.php
	 */
	remove_theme_support( 'automatic-feed-links' );

	/* Featured image */
	add_theme_support( 'post-thumbnails' );
	//set_post_thumbnail_size( 203, 161 ); // default Post Thumbnail dimensions

	/* Set custom image sizes */
	//add_image_size( 'image-banner', 250, 0, false );

	/*
	 * Post formats - may be useful for some blog-heavy projects
	 * @link http://codex.wordpress.org/Post_Formats
	 */
	//add_theme_support( 'post-formats', array( 'aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat' ) );

	/*
	 * Register nav menus
	 */
	/*
	register_nav_menus( array(
		'nav_main'		=> __( 'Main navigation' )
	) );
	*/

	/* Site settings
	$pilau_site_settings = get_option( 'pilau_site_settings' );
	*/

}


add_action( 'tgmpa_register', 'pilau_register_required_plugins' );
/**
 * Register the required plugins for this theme
 *
 * @since	[[theme-phpdoc-name]] 0.1
 */
function pilau_register_required_plugins() {

	// Try to get plugin infos stored by pilau-init
	if ( file_exists( ABSPATH . '.pi-plugin-infos' ) ) {

		// Set plugin infos
		$plugins = unserialize( file_get_contents( ABSPATH . '.pi-plugin-infos' ) );

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
		tgmpa( $plugins, $config );

	}

}


/**
 * Cookie notice handling
 *
 * @since	[[theme-phpdoc-name]] 0.1
 * @todo	Implement more sophisticated cookie handling (JS?) to hide notice for users who have disabled cookies
 */
if ( PILAU_USE_COOKIE_NOTICE )
	add_action( 'init', 'pilau_cookie_notice' );
function pilau_cookie_notice() {

	// Check for this domain in referrer
	if ( parse_url( $_SERVER['HTTP_REFERER'], PHP_URL_HOST ) == $_SERVER['SERVER_NAME'] ) {

		// Set cookie showing (implied) consent
		// Expires in 10 years
		setcookie( 'pilau_cookie_notice', 1, time() + ( 10 * 365 * 24 * 60 * 60 ), '/' );

	}

}


/**
 * Manage core taxonomies
 *
 * @since	[[theme-phpdoc-name]] 0.1
 * @link	http://w4dev.com/wp/remove-taxonomy/
 */
add_action( 'init', 'pilau_core_taxonomies' );
function pilau_core_taxonomies() {
	global $wp_taxonomies;

	/* Disable categories? */
	if ( taxonomy_exists( 'category' ) && ! PILAU_USE_CATEGORIES )
		unset( $wp_taxonomies['category'] );

	/* Disable tags? */
	if ( taxonomy_exists( 'post_tag' ) && ! PILAU_USE_TAGS )
		unset( $wp_taxonomies['post_tag'] );

}


/**
 * Rename "Posts" in post type object to "News"
 *
 * @since	[[theme-phpdoc-name]] 0.1
 */
if ( PILAU_RENAME_POSTS_NEWS ) {
	add_action( 'init', 'pilau_change_post_object_label' );
}
function pilau_change_post_object_label() {
	global $wp_post_types;
	$post = &$wp_post_types['post'];
	$post->label = 'News';
	$post->labels->name = 'News';
	$post->labels->singular_name = 'News';
	$post->labels->add_new = 'Add News';
	$post->labels->add_new_item = 'Add News';
	$post->labels->edit_item = 'Edit News';
	$post->labels->new_item = 'News';
	$post->labels->view_item = 'View News';
	$post->labels->search_items = 'Search News';
	$post->labels->not_found = 'No News found';
	$post->labels->not_found_in_trash = 'No News found in Trash';
	$post->labels->all_items = 'All News';
	$post->labels->menu_name = 'News';
	$post->labels->name_admin_bar = 'News';
}


/**
 * Set up that needs to happen when $post object is ready
 *
 * @since	[[theme-phpdoc-name]] 0.1
 */
add_action( 'template_redirect', 'pilau_setup_after_post' );
function pilau_setup_after_post() {
	global $pilau_custom_fields, $post;
	$pilau_custom_fields = array();

	/*
	 * Determine current page ID, if we're on a page
	 *
	 * This may not be $post->ID, if we're on the blog home page.
	 * Set to false if the current view isn't related to a singular post or page.
	 */
	$current_page_id = false;
	if ( is_home() && ! is_front_page() ) {
		$current_page_id = get_option( 'page_for_posts' );
	} else if ( is_singular() ) {
		$current_page_id = $post->ID;
	}
	define( 'PILAU_CURRENT_PAGE_ID', $current_page_id );

	/*
	 * Get all custom fields for current post
	 */
	//if ( PILAU_CURRENT_PAGE_ID && function_exists( 'slt_cf_all_field_values' ) )
	//	$pilau_custom_fields = slt_cf_all_field_values( 'post', $current_page_id );

	// De-activate removal of menu item IDs from Pilau Base
	//remove_filter( 'nav_menu_item_id', '__return_empty_array', 10000 );

}


/**
 * Manage scripts for the front-end
 *
 * Always use the $ver parameter when registering or enqueuing styles or scripts, and
 * update it when deploying a new version - this helps prevent browser caching issues.
 * (Actually this is made redundant by using Better WordPress Minify, with its
 * appended parameter - but this is a good habit to get into ;-)
 *
 * @since	[[theme-phpdoc-name]] 0.1
 */
add_action( 'wp_enqueue_scripts', 'pilau_enqueue_scripts', 0 );
function pilau_enqueue_scripts() {
	// This test is done here because applying the test to the hook breaks due to pilau_is_login_page() not being defined yet...
	if ( ! is_admin() && ! pilau_is_login_page() ) {

		/*
		 * Note: All scripts are set to enqueue in footer, but jQuery would require some de-registering
		 * and re-registering trickery to get that in the footer safely. For now, Better WordPress Minify
		 * is used to manage putting scripts in the footer.
		 */
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'pilau-global', get_stylesheet_directory_uri() . '/js/global.js', array( 'jquery' ), '1.0', true );

		/*
		 * Comment reply script - adjust the conditional if you need comments on post types other than 'post'
		 */
		if ( defined( 'PILAU_USE_COMMENTS' ) && PILAU_USE_COMMENTS && is_single() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply', false, array(), false, true );
		}

		/*
		 * Use this to pass the AJAX URL to the client when using AJAX
		 * @link	http://wp.smashingmagazine.com/2011/10/18/how-to-use-ajax-in-wordpress/
		 */
		wp_localize_script( 'pilau-global', 'pilau_global', array( 'ajaxurl' => admin_url( 'admin-ajax.php', PILAU_REQUEST_PROTOCOL ) ) );

	}
}


/**
 * Manage styles for the front-end
 *
 * Always use the $ver parameter when registering or enqueuing styles or scripts, and
 * update it when deploying a new version - this helps prevent browser caching issues.
 * (Actually this is made redundant by using Better WordPress Minify, with its
 * appended parameter - but this is a good habit to get into ;-)
 *
 * @since	[[theme-phpdoc-name]] 0.1
 */
add_action( 'wp_enqueue_scripts', 'pilau_enqueue_styles', 10 );
function pilau_enqueue_styles() {
	// This test is done here because applying the test to the hook breaks due to pilau_is_login_page() not being defined yet...
	if ( ! is_admin() && ! pilau_is_login_page() ) {
		global $wp_styles; // In case we need IE-only styles with conditional wrapper

		wp_enqueue_style( 'pilau-main', get_stylesheet_directory_uri() . '/styles/main.css', array( 'html5-reset', 'wp-core', 'pilau-classes' ), '1.0' );
		wp_enqueue_style( 'pilau-print', get_stylesheet_directory_uri() . '/styles/print.css', array( 'html5-reset', 'wp-core', 'pilau-classes' ), '1.0' );

		// IE-only styles
		// BEWARE: When using Better WordPress Minify plugin, these appear before the other CSS files in the header
		//wp_enqueue_style( 'pilau-ie', get_stylesheet_directory_uri() . '/styles/ie.css', array( 'html5-reset', 'wp-core', 'pilau-classes' ), '1.0' );
		//$wp_styles->add_data( 'pilau-ie', 'conditional', 'lt IE 9' );

	}
}


/**
 * Login styles and scripts
 *
 * @since	[[theme-phpdoc-name]] 0.1
 */
//add_action( 'login_head', 'pilau_login_styles_scripts', 10000 );
function pilau_login_styles_scripts() { ?>
	<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri() . '/styles/wp-login.css'; ?>">
<?php }
