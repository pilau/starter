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

	/* Enable HTML markup in user profiles */
	//remove_filter( 'pre_user_description', 'wp_filter_kses' );

	/* Disable captions */
	//add_filter( 'disable_captions', '__return_true' );

	/* Set up inline hints for image sizes */
	//add_filter( 'admin_post_thumbnail_html', 'pilau_inline_image_size_featured', 10, 2 );

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

