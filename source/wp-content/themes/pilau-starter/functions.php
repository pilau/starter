<?php

/**
 * Configuration and functions
 *
 * As part of a child theme, this functions.php file will be loaded BEFORE the functions.php
 * file of the parent theme (Pilau Base). Any functions or constants defined in the parent
 * can be overridden in the child.
 * @link	http://codex.wordpress.org/Child_Themes#Using_functions.php
 *
 * @package	[[theme-phpdoc-name]]
 * @since	0.1
 *
 */


/* Configurable constants */

/**
 * Global flag for activating comments
 *
 * @since	[[theme-phpdoc-name]] 0.1
 */
define( 'PILAU_USE_COMMENTS', false );

/**
 * Global flag for activating categories
 *
 * @since	[[theme-phpdoc-name]] 0.1
 */
define( 'PILAU_USE_CATEGORIES', false );

/**
 * Global flag for activating tags
 *
 * @since	[[theme-phpdoc-name]] 0.1
 */
define( 'PILAU_USE_TAGS', false );

/**
 * Ignore updates for inactive plugins?
 *
 * @since	[[theme-phpdoc-name]] 0.1
 */
define( 'PILAU_IGNORE_UPDATES_FOR_INACTIVE_PLUGINS', true );

/**
 * Use the cookie notice?
 *
 * @since	[[theme-phpdoc-name]] 0.1
 */
define( 'PILAU_USE_COOKIE_NOTICE', false );

/**
 * Picturefill for responsive image sizes?
 *
 * @since	[[theme-phpdoc-name]] 0.1
 */
define( 'PILAU_USE_PICTUREFILL', false );

/**
 * Twitter screen name
 * Don't include @ prefix
 *
 * @since	[[theme-phpdoc-name]] 0.1
 */
define( 'PILAU_TWITTER_SCREEN_NAME', '' );

/**
 * Maximum length of slugs in words
 *
 * @since	[[theme-phpdoc-name]] 0.1
 */
define( 'PILAU_SLUG_LENGTH', 8 );

/**
 * Home page ID
 *
 * @since	[[theme-phpdoc-name]] 0.1
 */
define( 'PILAU_HOME_PAGE_ID', 2 );

/**
 * Rename 'posts' to 'news'
 *
 * @since	[[theme-phpdoc-name]] 0.1
 */
define( 'PILAU_RENAME_POSTS_NEWS', true );


/*
 * Constants not intended for configuration
 *
 * These are defined here, because this functions.php is loaded before the parent's
 * functions.php. Even though they are not intended to be changed, and rightfully live
 * in the parent theme (where they are defined as a fall-back), they may get used before
 * the parent's functions.php is loaded.
 */

/**
 * Flag for requests from front, or AJAX - is_admin() returns true for AJAX
 * because the AJAX script is in /wp-admin/
 *
 * @since	[[theme-phpdoc-name]] 0.1
 */
if ( ! defined( 'PILAU_FRONT_OR_AJAX' ) ) {
	define( 'PILAU_FRONT_OR_AJAX', ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) );
}

/**
 * Store the protocol of the current request
 *
 * @since	[[theme-phpdoc-name]] 0.1
 */
if ( ! defined( 'PILAU_REQUEST_PROTOCOL' ) ) {
	define( 'PILAU_REQUEST_PROTOCOL', isset( $_SERVER[ 'HTTPS' ] ) ? 'https' : 'http' );
}

/**
 * Store the top-level slug
 *
 * @since	[[theme-phpdoc-name]] 0.1
 */
if ( ! defined( 'PILAU_TOP_LEVEL_SLUG' ) ) {
	$pilau_top_level_slug = explode( '/', trim( $_SERVER['REQUEST_URI'], '/' ) );
	define( 'PILAU_TOP_LEVEL_SLUG', reset( $pilau_top_level_slug ) );
}

/**
 * Placeholder GIF URL (used for deferred loading of images)
 *
 * @since	[[theme-phpdoc-name]] 0.1
 */
if ( ! defined( 'PILAU_PLACEHOLDER_GIF_URL' ) ) {
	define( 'PILAU_PLACEHOLDER_GIF_URL', get_template_directory_uri() . '/img/placeholder.gif' );
}


/**
 * PHP settings
 */

/** Default timezone */
date_default_timezone_set( 'Europe/London' );


/**
 * TMG Plugin Activation
 *
 * Manages required / recommended plugins
 *
 * @link	http://tgmpluginactivation.com/
 *
 * @since	[[theme-phpdoc-name]] 0.1
 */
require( dirname( __FILE__ ) . '/inc/class-tgm-plugin-activation.php' );

/**
 * Set up theme
 *
 * @since	[[theme-phpdoc-name]] 0.1
 */
require( dirname( __FILE__ ) . '/inc/setup.php' );

/**
 * Configuration (deferred from Pilau Init)
 *
 * @since	[[theme-phpdoc-name]] 0.1
 */
require( dirname( __FILE__ ) . '/inc/config.php' );

/**
 * Functions library
 *
 * @since	[[theme-phpdoc-name]] 0.1
 */
require( dirname( __FILE__ ) . '/inc/lib.php' );

/**
 * AJAX functionality
 *
 * @since	[[theme-phpdoc-name]] 0.1
 */
if ( PILAU_FRONT_OR_AJAX ) {
	require( dirname( __FILE__ ) . '/inc/ajax.php' );
}

/**
 * Header modifications
 *
 * - Clean up core stuff
 * - HTML title
 * - Meta tags
 * - body_class (inactive)
 *
 * @since	[[theme-phpdoc-name]] 0.1
 */
require( dirname( __FILE__ ) . '/inc/header.php');

/**
 * Media functionality
 *
 * @since	[[theme-phpdoc-name]] 0.1
 */
require( dirname( __FILE__ ) . '/inc/media.php');

/**
 * Custom management of feeds
 *
 * @since	[[theme-phpdoc-name]] 0.1
 */
require( dirname( __FILE__ ) . '/inc/feeds.php');

/**
 * Custom post types
 *
 * @since	[[theme-phpdoc-name]] 0.1
 */
require( dirname( __FILE__ ) . '/inc/custom-post-types.php' );

/**
 * Custom taxonomies
 *
 * @since	[[theme-phpdoc-name]] 0.1
 */
require( dirname( __FILE__ ) . '/inc/custom-taxonomies.php' );

/**
 * Custom meta fields
 *
 * Depends on Developer's Custom Fields plugin
 *
 * @since	[[theme-phpdoc-name]] 0.1
 */
require( dirname( __FILE__ ) . '/inc/custom-fields.php' );

/**
 * Shortcodes
 *
 * @since	[[theme-phpdoc-name]] 0.1
 */
require( dirname( __FILE__ ) . '/inc/shortcodes.php' );

/**
 * Widgets and sidebars
 *
 * @since	[[theme-phpdoc-name]] 0.1
 */
require( dirname( __FILE__ ) . '/inc/widgets.php' );

/**
 * WordPress toolbar customization (formerly admin bar)
 *
 * @since	[[theme-phpdoc-name]] 0.1
 */
require( dirname( __FILE__ ) . '/inc/wp-toolbar.php' );

/**
 * Admin stuff
 *
 * All other admin-*.php files are included within admin.php
 *
 * @since	[[theme-phpdoc-name]] 0.1
 */
if ( ! PILAU_FRONT_OR_AJAX ) {
	require( dirname( __FILE__ ) . '/inc/admin.php' );
}
