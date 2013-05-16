<?php


/**
 * Configuration and functions
 *
 * @package	Pilau_Starter
 * @since	0.1
 *
 */


/* Configurable constants */

/**
 * Global flag for activating comments
 *
 * @since	Pilau_Starter 0.1
 */
define( 'PILAU_USE_COMMENTS', false );

/**
 * Global flag for activating links
 *
 * @since	Pilau_Starter 0.1
 */
define( 'PILAU_USE_LINKS', false );

/**
 * Global flag for activating categories
 *
 * @since	Pilau_Starter 0.1
 */
define( 'PILAU_USE_CATEGORIES', false );

/**
 * Global flag for activating tags
 *
 * @since	Pilau_Starter 0.1
 */
define( 'PILAU_USE_TAGS', false );

/**
 * Use the Pilau plugins page? (unfinished)
 *
 * @since	Pilau_Starter 0.1
 */
define( 'PILAU_USE_PLUGINS_PAGE', false );

/**
 * Include the Pilau settings script? (unfinished)
 *
 * @since	Pilau_Starter 0.1
 */
define( 'PILAU_USE_SETTINGS_SCRIPT', false );

/**
 * Use the cookie notice?
 *
 * @since	Pilau_Starter 0.1
 */
define( 'PILAU_USE_COOKIE_NOTICE', false );

/**
 * Maximum length of slugs in words
 *
 * @since	Pilau_Starter 0.1
 */
define( 'PILAU_SLUG_LENGTH', 8 );


/* Automatic constants - can be configured but do this with care! */

/**
 * Flag for requests from front, or AJAX - is_admin() returns true for AJAX
 * because the AJAX script is in /wp-admin/
 *
 * @since Pilau_Starter 0.1
 */
define( 'PILAU_FRONT_OR_AJAX', ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) );

/**
 * Store the protocol of the current request
 *
 * @since	Pilau_Starter 0.1
 */
define( 'PILAU_REQUEST_PROTOCOL', isset( $_SERVER[ 'HTTPS' ] ) ? 'https' : 'http' );

/**
 * Store the top-level slug
 *
 * @since	Pilau_Starter 0.1
 */
define( 'PILAU_TOP_LEVEL_SLUG', reset( explode( '/', trim( $_SERVER['REQUEST_URI'], '/' ) ) ) );


/**
 * PHP settings
 */

/** Default timezone */
date_default_timezone_set( 'Europe/London' );


/**
 * Security
 *
 * @since	Pilau_Starter 0.1
 */
require( dirname( __FILE__ ) . '/inc/security.php' );


/**
 * Functions library
 *
 * @since	Pilau_Starter 0.1
 */
require( dirname( __FILE__ ) . '/inc/lib.php' );


/**
 * WP-LESS CSS compilation
 *
 * @since	Pilau_Starter 0.1
 */
global $WPLessPlugin;
require( dirname( __FILE__ ) . '/inc/wp-less/bootstrap-for-theme.php' );


/**
 * Set up theme
 *
 * - Set up theme features, nav menus
 * - $post-based initialization
 * - Enqueue scripts and styles for front-end and login
 *
 * @since	Pilau_Starter 0.1
 */
require( dirname( __FILE__ ) . '/inc/setup.php' );


/**
 * Header modifications
 *
 * - Clean up core stuff
 * - HTML title
 * - Meta tags
 * - body_class (inactive)
 *
 * @since	Pilau_Starter 0.1
 */
require( dirname( __FILE__ ) . '/inc/header.php');


/**
 * Content functionality
 *
 * @since	Pilau_Starter 0.1
 */
require( dirname( __FILE__ ) . '/inc/content.php');


/**
 * Media functionality
 *
 * @since	Pilau_Starter 0.1
 */
require( dirname( __FILE__ ) . '/inc/media.php');


/**
 * Custom management of feeds
 *
 * @since	Pilau_Starter 0.1
 */
require( dirname( __FILE__ ) . '/inc/feeds.php');


/**
 * Custom post types
 *
 * @since	Pilau_Starter 0.1
 */
//require( dirname( __FILE__ ) . '/inc/custom-post-types.php' );


/**
 * Custom taxonomies
 *
 * @since	Pilau_Starter 0.1
 */
//require( dirname( __FILE__ ) . '/inc/custom-taxonomies.php' );


/**
 * Custom meta fields
 *
 * Depends on Developer's Custom Fields plugin
 *
 * @since	Pilau_Starter 0.1
 */
require( dirname( __FILE__ ) . '/inc/custom-fields.php' );


/**
 * Shortcodes
 *
 * @since	Pilau_Starter 0.1
 */
//require( dirname( __FILE__ ) . '/inc/shortcodes.php' );


/**
 * Widgets and sidebars
 *
 * @since	Pilau_Starter 0.1
 */
require( dirname( __FILE__ ) . '/inc/widgets.php' );


/**
 * WordPress toolbar customization (formerly admin bar)
 *
 * @since	Pilau_Starter 0.1
 */
require( dirname( __FILE__ ) . '/inc/wp-toolbar.php' );


/**
 * Admin stuff
 *
 * All other admin-*.php files are included within admin.php
 *
 * @since	Pilau_Starter 0.1
 */
if ( ! PILAU_FRONT_OR_AJAX )
	require( dirname( __FILE__ ) . '/inc/admin.php' );
