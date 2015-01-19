<?php

/**
 * General admin stuff
 *
 * @package	Pilau_Starter
 * @since	0.1
 */


/* Any admin-specific includes */

/**
 * Admin interface customization
 *
 * @since	Pilau_Starter 0.1
 */
require( dirname( __FILE__ ) . '/admin-interface.php' );


/**
 * Admin initialization
 *
 * @since	Pilau_Starter 0.1
 */
add_action( 'admin_init', 'pilau_admin_init', 10 );
function pilau_admin_init() {


}
