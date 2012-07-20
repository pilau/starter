<?php


/**
 * Functions
 */


/**
 * Configurable constants
 */

/** Global flag for activating comments */
define( 'PILAU_USE_COMMENTS', false );


/**
 * Automatic constants - can be configured but do this with care!
 */

/** Flag for requests from front, or AJAX - is_admin() returns true for AJAX because the AJAX script is in /wp-admin/ */
define( 'PILAU_FRONT_OR_AJAX', ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) );

/** Store the protocol of the current request */
define( 'PILAU_REQUEST_PROTOCOL', isset( $_SERVER[ 'HTTPS' ] ) ? 'https' : 'http' );


/**
 * WP-LESS CSS compilation
 *
 */
global $WPLessPlugin;
require( 'inc/wp-less/bootstrap-for-theme.php' );


/**
 * Set up theme
 *
 * - Set up theme features, nav menus
 * - $post-based initialization
 * - Enqueue scripts and styles
 *
 */
require( 'inc/setup.php' );


/**
 * Header modifications
 *
 * - Clean up core stuff
 * - HTML title
 * - Meta tags
 * - body_class (inactive)
 */
require( 'inc/header.php');


/**
 * Custom meta fields
 *
 * Depends on Developer's Custom Fields plugin
 */
require( 'inc/custom-fields.php' );


/**
 * Admin-only stuff
 */
if ( ! PILAU_FRONT_OR_AJAX ) {
	require( 'inc/admin-interface.php' );
}

