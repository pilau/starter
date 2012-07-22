<?php

/**
 * Media functions
 *
 * @package	Pilau_Starter
 * @since	0.1
 */


/**
 * Add wmode parameter to Flash embeds to avoid z-index issue
 *
 * @since 0.1
 */
add_filter( 'oembed_result', 'pilau_wmode_opaque', 10, 3 );
function pilau_wmode_opaque( $html, $url, $args ) {
	if ( strpos( $html, '<param name="movie"' ) !== false )
		$html = preg_replace( '|</param>|', '</param><param name="wmode" value="opaque"></param>', $html, 1 );
	if ( strpos( $html, '<embed' ) !== false )
		$html = str_replace( '<embed', '<embed wmode="opaque"', $html );
	return $html;
}


/**
 * Try to output a video or an image
 *
 * @since	Pilau_Starter 0.1
 * @uses	wp_oembed_get()
 * @uses	esc_url()
 * @uses	esc_attr()
 * @param	string $url URL, perhaps an image, perhaps a video embed URL
 * @param	string $alt Alt text for an image
 * @return	string
 */
function pilau_video_or_image( $url, $alt = '' ) {
	$url_parts = parse_url( $url );
	if ( $url_parts['host'] == $_SERVER['HTTP_HOST'] && file_exists( ABSPATH . trim( $url_parts['path'], '/' ) ) ) {
		// Image
		return '<img src="' . esc_url( $url ) . '" alt="' . esc_attr( $alt ) . '" />';
	} else {
		// Video?
		return wp_oembed_get( $url );
	}
}


/**
 * Add classes to post images
 *
 * @since	Pilau_Starter 0.1
 */
add_filter( 'get_image_tag_class', 'pilau_post_image_classes' );
function pilau_post_image_classes( $class ) {
	$class .= ' wp-image';
	return $class;
}


/**
 * Strip out unnecessary image title attributes
 *
 * @since	Pilau_Starter 0.1
 */
add_filter( 'image_send_to_editor', 'pilau_no_image_titles' );
function pilau_no_image_titles( $html ) {
	$html = preg_replace( '/ title="[^"]*"/', '', $html );
	return $html;
}
