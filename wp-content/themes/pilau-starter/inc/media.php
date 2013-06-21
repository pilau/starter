<?php

/**
 * Media functions
 *
 * @package	Pilau_Starter
 * @since	0.1
 */



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
