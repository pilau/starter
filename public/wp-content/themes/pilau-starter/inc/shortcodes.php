<?php

/**
 * Shortcodes
 *
 * Try to add TinyMCE buttons for shortcodes you create
 * @link https://gist.github.com/3156062
 *
 * @package [[theme-phpdoc-name]]
 * @since	0.1
 */


//add_shortcode( 'anchor', 'pilau_anchor_link_shortcode' );
/**
 * Anchor link
 *
 * @since	[[theme-phpdoc-name]] 0.1
 * @uses	shortcode_atts()
 */
function pilau_anchor_link_shortcode( $atts ) {
	$a = shortcode_atts( array(
		'id' => '',
	), $atts );
	return '<a name="' . $a['id'] . '"></a>';
}


//add_shortcode( 'expires', 'pilau_expire_content_shortcode' );
/**
 * Expire content
 *
 * @since	[[theme-phpdoc-name]] 0.1
 * @link	http://crowdfavorite.com/wordpress/plugins/expiring-content-shortcode/
 */
function pilau_expire_content_shortcode( $atts = array(), $content = '' ) {
	$a = shortcode_atts( array(
		'on' => 'tomorrow',
	), $atts );
	if ( strtotime( $a['on'] ) > time() ) {
		return $content;
	}
	return '';
}
