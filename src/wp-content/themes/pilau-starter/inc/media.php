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
	global $pilau_image_sizes;

	/**
	 * Set up image sizes
	 *
	 * The 'featured' entry indicates the post types, and page templates
	 * where the size will be used. Pass everything as arrays, and use 'default' for
	 * the default page template.
	 *
	 * @todo	Integrate with custom fields?
	 */
	$pilau_image_sizes = array(
		'thumbnail'			=> array(
			'width'		=> 100,
			'height'	=> 100,
			'crop'		=> true,
		),
		'medium'			=> array(
			'width'		=> 250,
			'height'	=> 0,
			'crop'		=> false,
		),
		'large'				=> array(
			'width'		=> 800,
			'height'	=> 0,
			'crop'		=> false,
			'featured'			=> array(
				'post_type'			=> array( 'service' ),
				'template'			=> array( 'default' ),
			),
		),
		'post-thumbnail'	=> array(
			'width'		=> 203,
			'height'	=> 161,
			'crop'		=> false,
		),
		'custom-size'	=> array(
			'width'		=> 250,
			'height'	=> 0,
			'crop'		=> false,
		),
	);

	/*
	 * Override main image size settings
	 * These shouldn't be set via admin!
	 */
	add_filter( 'option_thumbnail_size_w',	function() { global $pilau_image_sizes; return $pilau_image_sizes['thumbnail']['width']; } );
	add_filter( 'option_thumbnail_size_h',	function() { global $pilau_image_sizes; return $pilau_image_sizes['thumbnail']['height']; } );
	add_filter( 'option_thumbnail_crop',	function() { global $pilau_image_sizes; return $pilau_image_sizes['thumbnail']['crop']; } );
	add_filter( 'option_medium_size_w',		function() { global $pilau_image_sizes; return $pilau_image_sizes['medium']['width']; } );
	add_filter( 'option_medium_size_h',		function() { global $pilau_image_sizes; return $pilau_image_sizes['medium']['height']; } );
	add_filter( 'option_medium_crop',		function() { global $pilau_image_sizes; return $pilau_image_sizes['medium']['crop']; } );
	add_filter( 'option_large_size_w',		function() { global $pilau_image_sizes; return $pilau_image_sizes['large']['width']; } );
	add_filter( 'option_large_size_h',		function() { global $pilau_image_sizes; return $pilau_image_sizes['large']['height']; } );
	add_filter( 'option_large_crop',		function() { global $pilau_image_sizes; return $pilau_image_sizes['large']['crop']; } );

	/* WooCommerce overrides */
	if ( PILAU_PLUGIN_EXISTS_WOOCOMMERCE ) {
		add_filter( 'woocommerce_get_image_size_shop_thumbnail',	function() { global $pilau_image_sizes; return $pilau_image_sizes['post-thumbnail']; } );
		add_filter( 'woocommerce_get_image_size_shop_catalog',		function() { global $pilau_image_sizes; return $pilau_image_sizes['post-thumbnail']; } );
		add_filter( 'woocommerce_get_image_size_shop_single',		function() { global $pilau_image_sizes; return $pilau_image_sizes['medium']; } );
	}

	/* Featured image */
	add_theme_support( 'post-thumbnails' );
	//set_post_thumbnail_size( $pilau_image_sizes['post-thumbnail']['width'], $pilau_image_sizes['post-thumbnail']['height'], $pilau_image_sizes['post-thumbnail']['crop'] );

	/* Set custom image sizes
	foreach ( array( 'custom-size' ) as $custom_size ) {
		add_image_size( $custom_size, $pilau_image_sizes[$custom_size]['width'], $pilau_image_sizes[$custom_size]['height'], $pilau_image_sizes[$custom_size]['crop'] );
	} */

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
