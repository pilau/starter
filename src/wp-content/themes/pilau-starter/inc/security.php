<?php

/**
 * Security
 *
 * @package	Pilau_Starter
 * @since	0.1
 */


//add_filter( 'authenticate', 'pilau_block_roles_login', 30, 3 );
/**
 * Completely block certain roles from login
 *
 * @param	object				$user
 * @param	string				$username
 * @param	string				$password
 * @return	WP_Error|WP_User
 */
function pilau_block_roles_login( $user, $username, $password ) {
	if ( $user instanceof WP_User && in_array( pilau_get_user_role( $user ), array() ) ) {
		return new WP_Error( 1, __( 'Login for this role is prohibited.' ) );
	}
	return $user;
}


add_action( 'init', 'pilau_block_front_end' );
/**
 * Block front-end for non-admins
 *
 * @since	0.1
 */
function pilau_block_front_end() {
	$always_allowed_ips = array(
		'90.197.157.134', // Public Life
	);
	if (	! ( defined( 'WP_LOCAL_DEV' ) && WP_LOCAL_DEV ) &&
			( defined( 'PILAU_BLOCK_FRONT_END' ) && PILAU_BLOCK_FRONT_END ) &&
			! is_admin() &&
			! pilau_is_login_page() &&
			! current_user_can( 'update_core' ) &&
			! in_array( $_SERVER['REMOTE_ADDR'], $always_allowed_ips )
	) {
		wp_die(
			'<p>' . __( 'Sorry, the front end of this site is currently blocked.' ) . '</p>',
			__( 'Front end blocked' ),
			array(
				'back_link'	=> true
			)
		);
	}
}


/**
 * Wrapper for wp_head() which manages SSL
 *
 * @since	0.1
 * @uses	wp_head()
 * @uses	PILAU_FORCE_SSL
 * @return	void
 */
function pilau_wp_head_ssl() {

	if ( ! defined( 'PILAU_FORCE_SSL' ) || ! PILAU_FORCE_SSL || ! is_ssl() ) {

		// Just output
		wp_head();

	} else {

		// Capture wp_head output with buffering
		ob_start();
		wp_head();
		$wp_head = ob_get_contents();
		ob_end_clean();

		// Replace plain protocols
		$wp_head = preg_replace( '/=(["\'])http:\/\//', '=\1https://', $wp_head );

		// Replace specific URLs
		$wp_head = str_replace( '//w.sharethis.com', '//ws.sharethis.com', $wp_head );

		// Output
		echo $wp_head;

	}

}


add_filter( 'clean_url', 'pilau_secure_internal_urls', 999999, 3 );
/**
 * Make sure any internal URL output using esc_url() uses HTTPS
 *
 * Since all URL output should go through esc_url(), this is a way of ensuring
 * internal URLs which may be manually entered into custom fields by editors
 * use the HTTPS protocol
 */
function pilau_secure_internal_urls( $url, $original_url, $_context ) {

	// Is this an SSL request?
	if ( is_ssl() ) {

		// Is this an internal URL?
		$url_parsed = parse_url( $url );
		if ( $url_parsed['host'] == $_SERVER['HTTP_HOST'] ) {

			// Change HTTP to HTTPS
			$url = preg_replace( '/^http:\/\//', 'https://', $url );

		}

	}

	return $url;
}


add_filter( 'image_send_to_editor', 'pilau_protocol_relative_image_urls', 999999 );
/**
 * Filter images sent to editor to make the URLs protocol-relative for possible SSL
 *
 * @since	0.1
 */
function pilau_protocol_relative_image_urls( $html ) {

	// Replace protocols with relative schema
	$html = str_replace( array( 'http://', 'https://' ), '//', $html );

	return $html;
}


add_filter( 'option_siteurl', 'pilau_fix_ssl_url' );
add_filter( 'option_home', 'pilau_fix_ssl_url' );
add_filter( 'option_url', 'pilau_fix_ssl_url' );
add_filter( 'option_wpurl', 'pilau_fix_ssl_url' );
add_filter( 'option_stylesheet_url', 'pilau_fix_ssl_url' );
add_filter( 'option_template_url', 'pilau_fix_ssl_url' );
add_filter( 'wp_get_attachment_url', 'pilau_fix_ssl_url' );
/**
 * Fix other URLs for SSL
 *
 * @link	http://wordpress.stackexchange.com/a/29756/2336
 * @link	https://core.trac.wordpress.org/ticket/15928
 * @param	string	$url
 * @return	string
 */
function pilau_fix_ssl_url( $url ) {
	if ( strpos( $url, 'http' ) === 0 && is_ssl() ) {
		$url = str_replace( 'http://', 'https://', $url );
	}
	return $url;
}


add_filter( 'wp_calculate_image_srcset', 'pilau_wp_calculate_image_srcset', PHP_INT_MAX );
/**
 * Force HTTPS scheme on srcset URLs
 *
 * @link	http://wordpress.stackexchange.com/a/211376/2336
 */
function pilau_wp_calculate_image_srcset( $sources ) {
	if ( is_array( $sources ) ) {
		foreach ( $sources as &$source ) {
			if ( isset( $source['url'] ) ) {
				$source['url'] = set_url_scheme( $source['url'], 'https' );
			}
		}
	}
	return $sources;
}
