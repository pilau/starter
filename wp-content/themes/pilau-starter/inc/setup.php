<?php

/**
 * Initial theme setup
 *
 * @package	Pilau_Starter
 * @since	0.1
 */


/**
 * Set up theme
 *
 * @since	Pilau_Starter 0.1
 */
add_action( 'after_setup_theme', 'pilau_setup' );
function pilau_setup() {
	global $WPLessPlugin, $pilau_theme_options;

	/*
	 * Theme options (not settings page)
	 */
	$pilau_theme_options = get_option( 'pilau_theme_options', array() );
	if ( ! is_array( $pilau_theme_options ) || empty( $pilau_theme_options ) ) {

		// First time theme has been activated
		$pilau_theme_options = array(
			'plugins_installer_run'		=> false,
			'plugins_nag_dismissed'		=> false,
			'settings_script_run'		=> false,
			'settings_nag_dismissed'	=> false
		);
		update_option( 'settings_script_run', $pilau_theme_options );

	}

	/* Get WP-LESS to compile and cache all styles */
	add_action( 'wp_print_styles', array( $WPLessPlugin, 'processStylesheets' ) );

	/* Enable shortcodes in widgets */
	add_filter( 'widget_text', 'do_shortcode' );

	/*
	 * Override core automatic feed links
	 * @see inc/feeds.php
	 */
	remove_theme_support( 'automatic-feed-links' );

	/* Featured image */
	add_theme_support( 'post-thumbnails' );

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

	/*
	 * Refresh
	 *
	 * The 'refresh' query parameter should be picked up by any theme caching mechanisms.
	 * This is to hook into any caching plugins, to refresh them too.
	 *
	 * TODO:	QuickCache not working, and possibly no longer supported - look into other plugins?
	 * http://wordpress.org/support/topic/plugin-quick-cache-speed-without-compromise-is-quick-cache-plugin-still-supported?replies=1
	 */
	/*
	if ( PILAU_FRONT_OR_AJAX && isset( $_GET['refresh'] ) ) {
		if ( defined( 'WS_PLUGIN__QCACHE_VERSION' ) ) {
			// Quick Cache plugin
		}
	}
	*/

}


/**
 * Manage core taxonomies
 *
 * @since	Pilau_Starter 0.1
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
 * Blank default nav menu
 *
 * @link	http://www.rlmseo.com/blog/cutom-navigation-menus-in-wordpress-3-0/
 * @since	Pilau_Starter 0.1
 */
function default_nav_menu() { return ''; }


/**
 * Set up that needs to happen when $post object is ready
 *
 * @since	Pilau_Starter 0.1
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
	if ( is_home() && ! is_front_page() )
		$current_page_id = get_option( 'page_for_posts' );
	else if ( is_singular() )
		$current_page_id = $post->ID;
	define( 'PILAU_CURRENT_PAGE_ID', $current_page_id );

	/*
	 * Get all custom fields for current post
	 */
	//if ( PILAU_CURRENT_PAGE_ID && function_exists( 'slt_cf_all_field_values' ) )
	//	$pilau_custom_fields = slt_cf_all_field_values( 'post', $current_page_id );

}


/**
 * Move scripts to the footer for better performance
 * @link	http://developer.yahoo.com/performance/rules.html#js_bottom
 *
 * You may want to disable this if you have jQuery you want to run within the page,
 * as it loads.
 *
 * Code from @link http://www.prelovac.com/vladimir/wordpress-plugins/footer-javascript
 *
 * @since	Pilau_Starter 0.1
 */
add_action( 'after_theme_setup', 'pilau_scripts_to_footer' );
function pilau_scripts_to_footer() {
	remove_action( 'wp_head', 'wp_print_scripts' );
	remove_action( 'wp_head', 'wp_print_head_scripts', 9 );
	remove_action( 'wp_head', 'wp_enqueue_scripts', 1 );
	add_action( 'wp_footer', 'wp_print_scripts', 5 );
	add_action( 'wp_footer', 'wp_enqueue_scripts', 5 );
	add_action( 'wp_footer', 'wp_print_head_scripts', 5 );
}


/**
 * Manage scripts and styles for the front-end
 *
 * Always use the $ver parameter when registering or enqueuing styles or scripts, and
 * update it when deploying a new version - this helps prevent browser caching issues.
 * (Actually this is made redundant by using Better WordPress Minify, with its
 * appended parameter - but this is a good habit to get into ;-)
 *
 * The Modernizr script has to be included in the header, so in case pilau_scripts_to_footer()
 * is used to move scripts to the footer, Modernizr is hard-coded into header.php
 *
 * @since	Pilau_Starter 0.1
 * @issue	This should be hooked to wp_enqueue_scripts (http://codex.wordpress.org/Function_Reference/wp_enqueue_style#Examples).
 * 			Currently hooked to init so styles don't get moved to the footer by
 * 			pilau_scripts_to_footer(), along with the scripts.
 */
add_action( 'init', 'pilau_enqueue_scripts_styles' );
function pilau_enqueue_scripts_styles() {

	/*
	 * Enqueue styles
	 *
	 * Version numbers probably not necessary because these get concatenated into one
	 * file by Better WordPress Minify, which adds its own version parameter. But hey.
	 */
	wp_enqueue_style( 'html5-reset', get_template_directory_uri() . '/css/html5-reset.css', array(), '1.0' );
	wp_enqueue_style( 'wp-core', get_template_directory_uri() . '/css/wp-core.css', array(), '1.0' );
	wp_enqueue_style( 'pilau-main', get_template_directory_uri() . '/less/main.less', array( 'html5-reset', 'wp-core' ), '1.0' );
	wp_enqueue_style( 'pilau-print', get_template_directory_uri() . '/less/print.less', array( 'html5-reset', 'wp-core' ), '1.0' );

	/*
	 * Enqueue scripts
	 */
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'pilau-global', get_template_directory_uri() . '/js/global.js', array( 'jquery' ), '1.0' );

	/*
	 * Comment reply script - adjust the conditional if you need comments on post types other than 'post'
	 */
	if ( defined( 'PILAU_USE_COMMENTS' ) && PILAU_USE_COMMENTS && is_single() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/*
	 * Use this to pass the AJAX URL to the client when using AJAX
	 * @link	http://wp.smashingmagazine.com/2011/10/18/how-to-use-ajax-in-wordpress/
	 */
	//wp_localize_script( 'pilau-global', 'pilau_global', array( 'ajaxurl' => admin_url( 'admin-ajax.php', PILAU_REQUEST_PROTOCOL ) ) );

}


/**
 * Login styles and scripts
 *
 * @since	Pilau_Starter 0.1
 */
//add_action( 'login_head', 'pilau_login_styles_scripts', 10000 );
function pilau_login_styles_scripts() { ?>
	<link rel="stylesheet" href="<?php echo get_template_directory_uri() . '/css/wp-login.css'; ?>">
<?php }


/**
 * Remove unnecessary title attributes from page list links
 *
 * @since	Pilau_Starter 0.1
 */
add_filter( 'wp_list_pages', 'pilau_remove_title_attributes' );
function pilau_remove_title_attributes( $input ) {
	return preg_replace( '/\s*title\s*=\s*(["\']).*?\1/', '', $input );
}
