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
 * Ignore updates for inactive plugins?
 *
 * @since	Pilau_Starter 0.1
 */
if ( ! defined( 'PILAU_IGNORE_UPDATES_FOR_INACTIVE_PLUGINS' ) ) {
	define( 'PILAU_IGNORE_UPDATES_FOR_INACTIVE_PLUGINS', true );
}

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


/**
 * PHP settings
 */

/** Default timezone */
date_default_timezone_set( 'Europe/London' );


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
