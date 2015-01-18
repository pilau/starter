<?php

/**
 * General admin stuff
 *
 * @package	[[theme-phpdoc-name]]
 * @since	0.1
 */


/* Any admin-specific includes */

/**
 * Admin interface customization
 *
 * @since	[[theme-phpdoc-name]] 0.1
 */
require( dirname( __FILE__ ) . '/admin-interface.php' );


/**
 * Admin initialization
 *
 * @since	[[theme-phpdoc-name]] 0.1
 */
add_action( 'admin_init', 'pilau_admin_init', 10 );
function pilau_admin_init() {


}
