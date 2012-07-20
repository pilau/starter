<?php


/**
 * Header modifications
 *
 * Includes dynamic additions to the header, any title manipulation, extensions to default WP classes for body and posts, etc.
 */


/**
 * Clean up WP header stuff
 */
add_action( 'template_redirect', 'pilau_cleanup_head' );
function pilau_cleanup_head() {

	/** Remove generator meta tags */
	remove_action( 'wp_head', 'wp_generator' );

	/** Remove extra links such as category feeds */
	remove_action( 'wp_head', 'feed_links_extra', 3 );

	/** Remove the link to the Really Simple Discovery service endpoint, EditURI link */
	remove_action( 'wp_head', 'rsd_link' );

	/** Remove the link to the Windows Live Writer manifest file */
	remove_action( 'wp_head', 'wlwmanifest_link' );

	/** Remove rel links unless dealing with posts */
	if ( ! is_home() || ( get_post_type() != 'post' ) || ! is_archive() ) {
		remove_action( 'wp_head', 'start_post_rel_link' );
		remove_action( 'wp_head', 'index_rel_link' );
		remove_action( 'wp_head', 'adjacent_posts_rel_link' );
	}

}


/**
 * Header stuff
 */
add_action( 'wp_head', 'pilau_head' );
function pilau_head() {
	global $pilau_custom_fields;

	/**
	 * Robots meta
	 *
	 * noindex,nofollow for dev and staging; let Yoast SEO take over on production
	 * @todo No way to override Yoast's robots output selectively?
	 */
	if ( ( defined( 'WP_LOCAL_DEV' ) && WP_LOCAL_DEV ) || ( defined( 'PILAU_REMOTE_ENV' ) && PILAU_REMOTE_ENV == 'staging' ) ) { ?>
		<meta name="robots" content="noindex,nofollow">
		<?php
	}

}


/**
 * Add custom classes to the array of body classes
 */
//add_filter( 'body_class', 'pilau_body_class' );
function pilau_body_class( $classes ) {
	return $classes;
}
