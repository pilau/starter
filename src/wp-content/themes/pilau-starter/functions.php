<?php

/**
 * Configuration and functions
 *
 * As part of a child theme, this functions.php file will be loaded BEFORE the functions.php
 * file of the parent theme (Pilau Base). Any functions or constants defined in the parent
 * can be overridden in the child.
 * @link	http://codex.wordpress.org/Child_Themes#Using_functions.php
 *
 * @package	Pilau_Starter
 * @since	0.1
 *
 */


/**
 * Configurable constants
 */

define( 'PILAU_USE_COMMENTS', false );
define( 'PILAU_USE_CATEGORIES', false );
define( 'PILAU_HIDE_CATEGORIES', true ); // Sometimes disabling a taxonomy completely causes issues
define( 'PILAU_USE_TAGS', false );
define( 'PILAU_HIDE_TAGS', true ); // Sometimes disabling a taxonomy completely causes issues
define( 'PILAU_IGNORE_UPDATES_FOR_INACTIVE_PLUGINS', true );
define( 'PILAU_USE_COOKIE_NOTICE', false );
define( 'PILAU_TWITTER_SCREEN_NAME', '' );
define( 'PILAU_SLUG_LENGTH', 8 );
define( 'PILAU_PAGE_ID_HOME', 2 );
define( 'PILAU_RENAME_POSTS_NEWS', true );


/**
 * Constants not intended for configuration
 *
 * These are defined here, because this functions.php is loaded before the parent's
 * functions.php. Even though they are not intended to be changed, and rightfully live
 * in the parent theme (where they are defined as a fall-back), they may get used before
 * the parent's functions.php is loaded.
 */

// Flag for requests from front, or AJAX
// is_admin() returns true for AJAX because the AJAX script is in /wp-admin/
if ( ! defined( 'PILAU_FRONT_OR_AJAX' ) ) {
	define( 'PILAU_FRONT_OR_AJAX', ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) );
}

// Store the protocol of the current request
if ( ! defined( 'PILAU_REQUEST_PROTOCOL' ) ) {
	define( 'PILAU_REQUEST_PROTOCOL', isset( $_SERVER[ 'HTTPS' ] ) ? 'https' : 'http' );
}

// Store the top-level slug
if ( ! defined( 'PILAU_TOP_LEVEL_SLUG' ) ) {
	$pilau_top_level_slug = explode( '/', trim( $_SERVER['REQUEST_URI'], '/' ) );
	define( 'PILAU_TOP_LEVEL_SLUG', reset( $pilau_top_level_slug ) );
}

// Placeholder GIF URL (used for deferred loading of images)
if ( ! defined( 'PILAU_PLACEHOLDER_GIF_URL' ) ) {
	define( 'PILAU_PLACEHOLDER_GIF_URL', get_template_directory_uri() . '/img/placeholder.gif' );
}


/**
 * Checks for plugins
 *
 * For precautionary tests when using plugin functions. Every use of a plugin
 * function should be wrapped by a test, just in case for some unforeseen
 * reason the plugin isn't installed and activated.
 */
define( 'PILAU_PLUGIN_EXISTS_DEVELOPERS_CUSTOM_FIELDS', function_exists( 'slt_cf_field_key' ) );
define( 'PILAU_PLUGIN_EXISTS_ADMIN_COLUMNS', class_exists( 'Codepress_Admin_Columns' ) );
define( 'PILAU_PLUGIN_EXISTS_TWITTER_FEED', function_exists( 'getTweets' ) );
define( 'PILAU_PLUGIN_EXISTS_WPSEO', class_exists( 'WPSEO_Admin' ) );
define( 'PILAU_PLUGIN_EXISTS_GRAVITY_FORMS', class_exists( 'GFAPI' ) );


/**
 * PHP settings
 */

date_default_timezone_set( 'Europe/London' );


/**
 * Get loaded
 */
require( dirname( __FILE__ ) . '/inc/class-tgm-plugin-activation.php' );
require( dirname( __FILE__ ) . '/inc/setup.php' );
require( dirname( __FILE__ ) . '/inc/config.php' );
require( dirname( __FILE__ ) . '/inc/lib.php' );
if ( PILAU_FRONT_OR_AJAX ) {
	require( dirname( __FILE__ ) . '/inc/ajax.php' );
}
require( dirname( __FILE__ ) . '/inc/header.php');
require( dirname( __FILE__ ) . '/inc/media.php');
require( dirname( __FILE__ ) . '/inc/feeds.php');
require( dirname( __FILE__ ) . '/inc/custom-post-types.php' );
require( dirname( __FILE__ ) . '/inc/custom-taxonomies.php' );
require( dirname( __FILE__ ) . '/inc/custom-fields.php' );
require( dirname( __FILE__ ) . '/inc/shortcodes.php' );
require( dirname( __FILE__ ) . '/inc/widgets.php' );
require( dirname( __FILE__ ) . '/inc/filtering.php');
require( dirname( __FILE__ ) . '/inc/forms.php');
require( dirname( __FILE__ ) . '/inc/wp-toolbar.php' );
if ( ! PILAU_FRONT_OR_AJAX ) {
	require( dirname( __FILE__ ) . '/inc/admin.php' );
	// All other admin-*.php files are included within admin.php
}
