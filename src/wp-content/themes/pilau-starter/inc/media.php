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
 * @since	0.1
 */
function pilau_setup_media() {
	global $pilau_image_sizes, $pilau_slideshow_content_types, $pilau_slideshow_pages, $pilau_breakpoints;

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

	/* Breakpoints */
	if ( false === ( $pilau_breakpoints = get_transient( 'pilau_breakpoints' ) ) || isset( $_GET['refresh'] ) ) {
		$pilau_breakpoints = json_decode( file_get_contents( trailingslashit( ABSPATH ) . 'breakpoints.json' ) );
		set_transient( 'pilau_breakpoints', $pilau_breakpoints, 60*60*24 ); // Cache for 24 hours
	}
	//echo '<pre>'; print_r( $pilau_breakpoints ); echo '</pre>'; exit;

	/* Slideshows */

	// Content types available to be made into slides
	$pilau_slideshow_content_types = array( 'post' );

	// For CMB2, which posts / pages have slideshows?
	$pilau_slideshow_pages = array( PILAU_PAGE_ID_HOME );

}


add_filter( 'img_caption_shortcode', 'pilau_img_caption_shortcode_filter', 10, 3 );
/**
 * Improves the caption shortcode with HTML5 figure & figcaption; microdata & wai-aria attributes
 *
 * @since	0.1
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
 * @since	0.1
 */
function pilau_post_image_classes( $class ) {
	$class .= ' wp-image';
	return $class;
}


add_filter( 'wp_get_attachment_image_attributes', 'pilau_image_attributes', 10, 2 );
/**
 * Manage default WP image attributes
 *
 * @since	0.1
 */
function pilau_image_attributes( $attr, $attachment ) {
	unset( $attr['title'] );
	return $attr;
}


add_filter( 'embed_oembed_html', 'pilau_fluid_embed_wrap', 10, 4 );
/**
 * Add wrapper to embeds to enable fluid videos
 */
function pilau_fluid_embed_wrap( $html, $url, $attr, $post_id ) {
	return '<div class="pilau-embed-wrap">' . $html . '</div>';
}


add_filter( 'image_send_to_editor', 'pilau_protocol_relative_image_urls', 999999 );
/**
 * Filter images sent to editor to make the URLs protocol-relative for possible SSL
 *
 * @since	0.1
 */
function pilau_protocol_relative_image_urls( $html ) {

	// Replace protocols with relative schema
	$html = str_replace( array( 'http://', 'https://' ), '//', $html );

	return $html;
}


/**
 * Place a slideshow
 *
 * Adjust data-flickity-options as necessary, possibly add a custom field alongside
 * 'slideshow-autoplay' for editorial control. Note that setGallerySize should be set
 * to 'true' to base the slideshow's height on the cell height. Currently this is
 * controlled via CSS && JS to be proportional to the width.
 * @link	http://flickity.metafizzy.co/options.html
 *
 * @since	0.1
 */
function pilau_slideshow() {
	global $pilau_custom_fields, $pilau_breakpoints;

	// Gather custom fields from items
	$items = array();
	for ( $i = 1; $i <= PILAU_SLIDESHOW_ITEMS; $i++ ) {
		if ( ! empty( $pilau_custom_fields['slideshow-item-' . $i ] ) ) {
			$items[ $pilau_custom_fields['slideshow-item-' . $i ] ] = pilau_get_custom_fields( $pilau_custom_fields['slideshow-item-' . $i ], 'post' );
		}
	}
	//echo '<pre>'; print_r( $items ); echo '</pre>'; exit;

	// Do we have anything?
	if ( ! empty( $items ) ) {

		// Init
		$autoplay = ! empty( $pilau_custom_fields['slideshow-autoplay'] ) ? intval( $pilau_custom_fields['slideshow-autoplay'] ) : 0;
		if ( $autoplay ) {
			$autoplay = $autoplay * 1000; // Convert from seconds to milliseconds
		}

		?>

		<div class="slideshow js-flickity" data-flickity-options='{ "wrapAround": true, "setGallerySize": false, "pageDots": true, "autoPlay": <?php echo $autoplay; ?> }'>

			<?php foreach ( $items as $item_id => $item_custom_fields ) { ?>

				<div class="gallery-cell">
					<a class="link-block" href="<?php echo get_permalink( $item_id ); ?>" title="<?php echo __( 'Click to read more about:' ) . ' ' . get_the_title( $item_id ) ?>">
						<div class="text">
							<h2 class="heading"><?php echo $item_custom_fields['slideshow-heading']; ?></h2>
							<p class="teaser"><?php echo $item_custom_fields['slideshow-teaser']; ?></p>
							<p class="button"><?php echo $item_custom_fields['slideshow-button-text']; ?></p>
						</div>
						<figure class="image">
							<?php

							// Set up alternate portrait image
							$picture_srcs = array();
							if ( ! empty( $item_custom_fields['slideshow-image-portrait'] ) ) {
								$picture_srcs[] = array(
									'media'		=> '(max-width: ' . $pilau_breakpoints->medium . 'px)',
									'srcset'	=> $item_custom_fields['slideshow-image-portrait']
								);
							}

							echo pilau_responsive_image(
								$item_custom_fields['slideshow-image_id'],
								array( 'medium', 'large', 'full-width' ),
								'full-width',
								null,
								null,
								array(),
								$picture_srcs
							);
							?>
						</figure>
					</a>
				</div>

			<?php } ?>

		</div>

	<?php }

}