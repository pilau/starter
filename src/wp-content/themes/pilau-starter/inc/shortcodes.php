<?php

/**
 * Shortcodes
 *
 * Try to add TinyMCE buttons for shortcodes you create
 * @link https://gist.github.com/3156062
 *
 * @package Pilau_Starter
 * @since	0.1
 */


//add_shortcode( 'anchor', 'pilau_anchor_link_shortcode' );
/**
 * Anchor link
 *
 * @since	Pilau_Starter 0.1
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
 * @since	Pilau_Starter 0.1
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


add_shortcode( 'sitemap', 'pilau_sitemap_shortcode' );
/**
 * Site map
 *
 * @since	0.1
 */
function pilau_sitemap_shortcode() {

	// Get pages to exclude
	$excluded_list = implode( ',', pilau_get_noindex_pages() );
	//echo '<pre>'; print_r( $excluded_list ); echo '</pre>'; exit;

	// Use core page menu
	$output = wp_page_menu( array(
		'sort_column'	=> 'menu_order',
		'show_home'		=> true,
		'echo'			=> false,
		'menu_class'	=> 'sitemap',
		'exclude_tree'	=> $excluded_list,
	));

	return $output;
}
