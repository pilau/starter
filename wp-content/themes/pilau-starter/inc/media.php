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
 * Manage default WP image attributes
 *
 * @since	Pilau_Starter 0.1
 */
add_filter( 'wp_get_attachment_image_attributes', 'pilau_image_attributes', 10, 2 );
function pilau_image_attributes( $attr, $attachment ) {
	unset( $attr['title'] );
	return $attr;
}


/**
 * Output an image with optional caption, using <figure> and <figcaption> tags
 *
 * @since	Pilau_Starter 0.1
 * @param	int				$image_id		ID of the image
 * @param	string			$size			Size of the image; defaults to 'post-thumbnail'
 * @param	string			$alt			Alternate text for the image; defaults to image alt or post title
 * @param	array|string	$fig_class		Class(es) for the <figure> tag
 * @param	string			$fig_id			ID for the <figure> tag
 * @return	void
 */
function pilau_image_maybe_caption( $image_id, $size = 'post-thumbnail', $alt = null, $fig_class = null, $fig_id = null ) {

	// Try to get image
	if ( ! ctype_digit( $image_id ) )
		return;
	$image = get_post( $image_id );
	if ( ! $image )
		return;

	// Initialize
	$fig_class = (array) $fig_class;
	$image_meta = wp_get_attachment_metadata( $image_id );
	$image_width = $image_meta['width'];
	$image_height = $image_meta['height'];
	if ( array_key_exists( $size, $image_meta['sizes'] ) ) {
		$image_width = $image_meta['sizes'][ $size ]['width'];
		$image_height = $image_meta['sizes'][ $size ]['height'];
	}
	if ( ! is_null( $alt ) ) {
		$image_alt = $alt;
	} else if ( ! $image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt' )[0] ) {
		$image_alt = $image->post_title;
	}

	// Start output
	echo '<figure class="' . esc_attr( implode( ' ', $fig_class ) ) . '"';
	if ( $fig_id )
		echo ' id="' . esc_attr( $fig_id ) . '"';
	echo '>';

	// Image
	echo '<img src="' . pilau_get_image_url( $image_id, $size ) . '" width="' . esc_attr( $image_width ) . '" height="' . esc_attr( $image_height ) . '" alt="' . esc_attr( $image_alt ) . '">';

	// Caption?
	if ( $image->post_excerpt ) {
		echo '<figcaption>' . $image->post_excerpt . '</figcaption>';
	}

	echo '</figure>';

}
