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
 * Pilau plugins management
 *
 * @since Pilau_Starter 0.1
 */
if ( PILAU_USE_PLUGINS_PAGE ) {
	require( dirname( __FILE__ ) . '/plugins-infos.php' );
}


/**
 * Admin initialization
 *
 * @since	Pilau_Starter 0.1
 */
add_action( 'admin_init', 'pilau_admin_init' );
function pilau_admin_init() {
	global $pilau_wp_plugins;

	/* Enable HTML markup in user profiles */
	//remove_filter( 'pre_user_description', 'wp_filter_kses' );

	/* Disable captions */
	//add_filter( 'disable_captions', '__return_true' );

	/* Set up inline hints for image sizes */
	//add_filter( 'admin_post_thumbnail_html', 'pilau_inline_image_size_featured', 10, 2 );

	/**
	 * Installed plugins data
	 *
	 * @since	Pilau_Starter 0.1
	 * @global	array
	 */
	if ( PILAU_USE_PLUGINS_PAGE )
		$pilau_wp_plugins = get_plugins();

}


/**
 * Inline image hints for Featured Image box
 *
 * @since	Pilau_Starter 0.1
 * @param	string	$content
 * @return	string
 */
function pilau_inline_image_size_featured( $content ) {
	$hint = null;

	switch ( get_post_type() ) {
		case 'investment':
			$hint = 'Optimum image size: 647 x 461 px. If larger, try to keep similar proportions.';
			break;
		case 'page':
			switch ( get_page_template_slug() ) {
				case 'page_priority-area.php' :
					$hint = 'Optimum image size: 472 x 364 px. If larger, try to keep similar proportions.';
					break;
				default:
					if ( get_page_template_slug() == '' )
						$hint = 'For listing page images, the optimum size is 203 x 161 px.';
					break;
			}
			break;
	}

	if ( $hint )
		$content = '<p><i>' . $hint . '</i></p>' . $content;

	return $content;
}


/**
 * Limit length of slugs
 *
 * @since	Pilau_Starter 0.1
 */
add_filter( 'name_save_pre', 'pilau_slug_length', 10 );
function pilau_slug_length( $slug ) {
	$maxwords = PILAU_SLUG_LENGTH;
	$slug_array = explode( "-", $slug );
	if ( count( $slug_array ) > $maxwords )
		$slug_array = array_slice( $slug_array, 0, $maxwords );
	return implode( "-", $slug_array );
}


/**
 * Only check for updates for active plugins
 *
 * @since	Pilau_Starter 0.1
 * @link http://wordpress.org/extend/plugins/update-active-plugins-only/
 */
add_filter( 'http_request_args', 'pilau_update_active_plugins_only', 10, 2 );
function pilau_update_active_plugins_only( $r, $url ) {
	if ( 0 === strpos( $url, 'http://api.wordpress.org/plugins/update-check/' ) ) {
		$plugins = unserialize( $r['body']['plugins'] );
		$plugins->plugins = array_intersect_key( $plugins->plugins, array_flip( $plugins->active ) );
		$r['body']['plugins'] = serialize( $plugins );
	}
	return $r;
}


/**
 * A workaround to fix the Dynamic Widgets lists of CPTs
 *
 * @since	Pilau_Starter 0.1
 */
if ( defined( 'DW_VERSION' ) )
	add_filter( 'pre_get_posts', 'pilau_dynwid_cpt_fix' );
function pilau_dynwid_cpt_fix( $query ) {
	if ( isset( $_GET['page'] ) && $_GET['page'] == 'dynwid-config' && isset( $_GET['action'] ) && $_GET['action'] == 'edit' ) {
		$query->set( 'posts_per_page', -1 );
	}
	return $query;
}
