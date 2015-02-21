<?php

/**
 * Media handling
 *
 * @package	Pilau_Starter
 * @since	0.1
 */


add_action( 'after_setup_theme', 'pilau_setup_media' );
/**
 * Set up media
 *
 * @since	Pilau_Starter 0.1
 */
function pilau_setup_media() {

	/*
	 * Override main image size settings
	 * These shouldn't be set via admin!
	 */
	add_filter( 'option_thumbnail_size_w',	function() { return 100; } );
	add_filter( 'option_thumbnail_size_h',	function() { return 100; } );
	add_filter( 'option_thumbnail_crop',	function() { return 1; } );
	add_filter( 'option_medium_size_w',		function() { return 250; } );
	add_filter( 'option_medium_size_h',		function() { return 0; } );
	add_filter( 'option_medium_crop',		function() { return 0; } );
	add_filter( 'option_large_size_w',		function() { return 800; } );
	add_filter( 'option_large_size_h',		function() { return 0; } );
	add_filter( 'option_large_crop',		function() { return 0; } );

	/* Featured image */
	add_theme_support( 'post-thumbnails' );
	//set_post_thumbnail_size( 203, 161 ); // default Post Thumbnail dimensions

	/* Set custom image sizes */
	//add_image_size( 'image-banner', 250, 0, false );

}


add_filter( 'img_caption_shortcode', 'pilau_img_caption_shortcode_filter', 10, 3 );
/**
 * Improves the caption shortcode with HTML5 figure & figcaption; microdata & wai-aria attributes
 *
 * @link    http://joostkiens.com/improving-wp-caption-shortcode/
 * @param   string  $val     Empty
 * @param   array   $attr    Shortcode attributes
 * @param   string  $content Shortcode content
 * @return  string           Shortcode output
 */
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


add_filter( 'get_image_tag_class', 'pilau_post_image_classes' );
/**
 * Add classes to post images
 *
 * @since	Pilau_Starter 0.1
 */
function pilau_post_image_classes( $class ) {
	$class .= ' wp-image';
	return $class;
}


add_filter( 'wp_get_attachment_image_attributes', 'pilau_image_attributes', 10, 2 );
/**
 * Manage default WP image attributes
 *
 * @since	Pilau_Starter 0.1
 */
function pilau_image_attributes( $attr, $attachment ) {
	unset( $attr['title'] );
	return $attr;
}
