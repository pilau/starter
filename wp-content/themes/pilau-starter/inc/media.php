<?php

/**
 * Media functions
 *
 * @package	[[theme-phpdoc-name]]
 * @since	0.1
 */


/**
 * Improves the caption shortcode with HTML5 figure & figcaption; microdata & wai-aria attributes
 *
 * @link    http://joostkiens.com/improving-wp-caption-shortcode/
 * @param   string  $val     Empty
 * @param   array   $attr    Shortcode attributes
 * @param   string  $content Shortcode content
 * @return  string           Shortcode output
 */
add_filter( 'img_caption_shortcode', 'pilau_img_caption_shortcode_filter', 10, 3 );
function pilau_img_caption_shortcode_filter( $val, $attr, $content = null ) {
	extract( shortcode_atts( array(
		'id'      => '',
		'align'   => 'aligncenter',
		'width'   => '',
		'caption' => ''
	), $attr ) );

	// No caption, no dice... But why width?
	if ( 1 > (int) $width || empty($caption) ) {
		return $val;
	}

	if ( $id ) {
		$id = esc_attr( $id );
	}

	// Add itemprop="contentURL" to image - Ugly hack
	$content = str_replace( '<img', '<img itemprop="contentURL"', $content );

	return '<figure id="' . $id . '" aria-describedby="figcaption_' . $id . '" class="wp-caption ' . esc_attr( $align ) . '" itemscope itemtype="http://schema.org/ImageObject" style="width: ' . ( 0 + (int) $width ) . 'px">' . do_shortcode( $content ) . '<figcaption id="figcaption_'. $id . '" class="wp-caption-text" itemprop="description">' . $caption . '</figcaption></figure>';

}


/**
 * Add classes to post images
 *
 * @since	[[theme-phpdoc-name]] 0.1
 */
add_filter( 'get_image_tag_class', 'pilau_post_image_classes' );
function pilau_post_image_classes( $class ) {
	$class .= ' wp-image';
	return $class;
}


/**
 * Manage default WP image attributes
 *
 * @since	[[theme-phpdoc-name]] 0.1
 */
add_filter( 'wp_get_attachment_image_attributes', 'pilau_image_attributes', 10, 2 );
function pilau_image_attributes( $attr, $attachment ) {
	unset( $attr['title'] );
	return $attr;
}
