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
		return '<img src="' . esc_url( $url ) . '" alt="' . esc_attr( $alt ) . '">';
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
 * @since	SLTaylor 0.1
 * @param	int				$image_id		ID of the image
 * @param	string			$size			Size of the image; defaults to 'post-thumbnail'
 * @param	string			$alt			Alternate text for the image; defaults to image alt or post title
 * @param	array|string	$fig_class		Class(es) for the <figure> tag
 * @param	string			$fig_id			ID for the <figure> tag
 * @param	string			$link			Optional link to wrap around image
 * @param	bool			$defer			Optional deferred loading
 * @return	void
 */
function pilau_image_maybe_caption( $image_id, $size = 'post-thumbnail', $alt = null, $fig_class = null, $fig_id = null, $link = null, $defer = false ) {

	// Try to get image
	if ( ! is_int( $image_id ) && ! ctype_digit( $image_id ) )
		return;
	$image = get_post( $image_id );
	if ( ! $image )
		return;

	// Initialize
	$fig_class = (array) $fig_class;

	// Start output
	echo '<figure class="' . esc_attr( implode( ' ', $fig_class ) ) . '"';
	if ( $fig_id )
		echo ' id="' . esc_attr( $fig_id ) . '"';
	echo '>';

	// Link?
	if ( $link )
		echo '<a href="' . esc_url( $link ) . '">';

	// Image
	pilau_img_defer_load( $image_id, $size, $alt, $defer );

	// Link?
	if ( $link )
		echo '</a>';

	// Caption?
	if ( $image->post_excerpt ) {
		echo '<figcaption>' . $image->post_excerpt . '</figcaption>';
	}

	echo '</figure>';

}


/**
 * Ouput an image, with optional deferred loading
 *
 * @link	http://24ways.org/2010/speed-up-your-site-with-delayed-content/
 * @param	mixed	$image	Either an attachment ID, or an array with 'width', 'height', 'src', 'alt'
 * @param	string	$size	Size of the image (if attachment ID is passed); defaults to 'post-thumbnail'
 * @param	string	$alt	Alternate text for the image; defaults to image alt or post title
 * @param	bool	$defer	Defaults to false
 * @return	void
 */
function pilau_img_defer_load( $image, $size = 'post-thumbnail', $alt = null, $defer = false ) {

	// Initialize
	if ( ! is_array( $image ) && $image_meta = wp_get_attachment_metadata( $image ) ) {
		$image_id = $image;
		$image = array(
			'width'		=> $image_meta['width'],
			'height'	=> $image_meta['height'],
			'src'		=> pilau_get_image_url( $image_id, $size )
		);
		if ( array_key_exists( $size, $image_meta['sizes'] ) ) {
			$image['width'] = $image_meta['sizes'][ $size ]['width'];
			$image['height'] = $image_meta['sizes'][ $size ]['height'];
		}
		if ( ! is_null( $alt ) ) {
			$image['alt'] = $alt;
		} else if ( ! $image['alt'] = array_shift( get_post_meta( $image_id, '_wp_attachment_image_alt' ) ) ) {
			$image['alt'] = get_the_title( $image_id );
		}
	}

	// Output?
	if ( is_array( $image ) ) {
		echo '<img ';
		if ( $defer ) {
			echo 'data-defer-src="' . esc_url( $image['src'] ) . '" src="' . get_stylesheet_directory_uri() . '/img/placeholder.gif"';
		} else {
			echo 'src="' . esc_url( $image['src'] ) . '"';
		}
		echo ' width="' . esc_attr( $image['width'] ) . '" height="' . esc_attr( $image['height'] ) . '" alt="' . esc_attr( $image['alt'] ) . '">';
	}

}