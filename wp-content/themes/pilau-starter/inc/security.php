<?php

/**
 * Security functions
 *
 * @package	Pilau_Starter
 * @since	0.1
 */


/**
 * Block attempted comments without a referrer
 *
 * @since	Pilau_Starter 0.1
 * @uses	wp_die()
 */
add_action( 'check_comment_flood', 'pilau_check_referrer' );
function pilau_check_referrer() {
	if ( ! isset( $_SERVER['HTTP_REFERER'] ) || $_SERVER['HTTP_REFERER'] == "" )
		wp_die( __( 'Please enable referrers in your browser.' ) );
}

/**
 * Block malicious requests
 *
 * @since	Pilau_Starter 0.1
 * @link	http://perishablepress.com/press/2009/12/22/protect-wordpress-against-malicious-url-requests/
 * @uses	is_user_logged_in()
 */
add_action( 'init', 'pilau_block_malicious_requests' );
function pilau_block_malicious_requests() {
	if (	( strlen( $_SERVER['REQUEST_URI'] ) > 255 && ! is_user_logged_in() ) ||
			strpos( $_SERVER['REQUEST_URI'], "eval(" ) ||
			strpos( $_SERVER['REQUEST_URI'], "base64" ) ) {
		@header( "HTTP/1.1 414 Request-URI Too Long" );
		@header( "Status: 414 Request-URI Too Long" );
		@header( "Connection: Close" );
		@exit;
	}
}

/**
 * Remove WP version from RSS
 *
 * @since	Pilau_Starter 0.1
 */
add_filter( 'the_generator', 'pilau_rss_version' );
function pilau_rss_version() { return ''; }


