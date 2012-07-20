<?php


/**
 * Set up theme
 */
add_action( 'after_setup_theme', 'pilau_setup' );
function pilau_setup() {
	global $WPLessPlugin;

	/** Get WP-LESS to compile and cache all styles */
	add_action( 'wp_print_styles', array( $WPLessPlugin, 'processStylesheets' ) );

	/** Add feed links automatically */
	add_theme_support( 'automatic-feed-links' );

	/** Featured image */
	add_theme_support( 'post-thumbnails' );

	/**
	 * Post formats - may be useful for some blog-heavy projects
	 * @link http://codex.wordpress.org/Post_Formats
	 */
	//add_theme_support( 'post-formats', array( 'aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat' ) );

	/**
	 * Register nav menus
	 */
	/*
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', '_s' ),
	) );
	*/

}


/**
 * Blank default nav menu
 * @link http://www.rlmseo.com/blog/cutom-navigation-menus-in-wordpress-3-0/
 */
function default_nav_menu() { return ''; }


/**
 * Set up that needs to happen when $post object is ready
 */
add_action( 'template_redirect', 'pilau_setup_after_post' );
function pilau_setup_after_post() {
	global $pilau_custom_fields, $post;
	$pilau_custom_fields = array();

	/**
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

	/**
	 * Get all custom fields for current post
	 */
	//if ( PILAU_CURRENT_PAGE_ID && function_exists( 'slt_cf_all_field_values' ) )
	//	$pilau_custom_fields = slt_cf_all_field_values( 'post', $current_page_id );

}


/**
 * Manage scripts and styles for the front-end
 *
 * Always use the $ver parameter when registering or enqueuing styles or scripts, and
 * update it when deploying a new version - this helps prevent browser caching issues.
 * (Actually this is made redundant by using Better WordPress Minify, with its
 * appended parameter - but this is a good habit to get into ;-)
 *
 * The Modernizr script has to be included in the header, so because we usually use
 * the JavaScript to Footer plugin (for performance reasons), Modernizr is hard-coded
 * into header.php
 *
 * @issue	This should be hooked to wp_enqueue_scripts (http://codex.wordpress.org/Function_Reference/wp_enqueue_style#Examples).
 * 			Currently hooked to init so scripts don't get moved to the footer by JavaScript to Footer plugin
 */
add_action( 'init', 'pilau_enqueue_scripts_styles' );
function pilau_enqueue_scripts_styles() {

	/**
	 * Enqueue styles
	 */
	wp_enqueue_style( 'html5-reset', get_template_directory_uri() . '/css/html5-reset.css', array(), '1.0' );
	wp_enqueue_style( 'screen', get_template_directory_uri() . '/less/screen.less', array( 'html5-reset' ), '1.0' );
	wp_enqueue_style( 'print', get_template_directory_uri() . '/less/print.less', array( 'html5-reset' ), '1.0' );

	/**
	 * Default scripts
	 */
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'pilau-global', get_template_directory_uri() . '/js/global.js', array( 'jquery' ), '1.0' );

	/**
	 * Comment reply script - adjust the conditional if you need comments on post types other than 'post'
	 */
	if ( defined( 'PILAU_USE_COMMENTS' ) && PILAU_USE_COMMENTS && is_single() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/**
	 * Use this to pass the AJAX URL to the client when using AJAX
	 * @link http://wp.smashingmagazine.com/2011/10/18/how-to-use-ajax-in-wordpress/
	 */
	//wp_localize_script( 'pilau-global', 'pilau_global', array( 'ajaxurl' => admin_url( 'admin-ajax.php', PILAU_REQUEST_PROTOCOL ) ) );

}




