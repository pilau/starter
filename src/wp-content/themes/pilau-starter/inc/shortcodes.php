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


add_filter( 'wp_page_menu', 'pilau_sitemap_shortcode_cpts', 10, 2 );
/**
 * Filter sitemap output to include public CPTs
 */
function pilau_sitemap_shortcode_cpts( $menu, $args ) {
	global $pilau_post_type_ancestors;

	// Check for sitemap
	if ( $args['menu_class'] == 'sitemap' ) {

		// Go through public custom post types, and posts
		foreach ( array_merge( array( 'post' ), pilau_get_public_custom_post_type_names() ) as $cpt_name ) {

			// Does this post type have a landing page?
			if ( $pilau_post_type_ancestors[ $cpt_name ] ) {

				// Try to find landing page in markup
				$cpt_landing_link = get_permalink( $pilau_post_type_ancestors[ $cpt_name ][0] );
				$cpt_landing_pattern_start = '<a href="' . $cpt_landing_link . '">';
				$cpt_landing_pattern_end = '</a>';
				if ( $cpt_landing_link && preg_match( '%' . $cpt_landing_pattern_start . '([^<]+)' . $cpt_landing_pattern_end . '%', $menu, $matches, PREG_OFFSET_CAPTURE ) === 1 ) {

					// Get posts
					$posts = get_posts( array(
						'post_type'			=> $cpt_name,
						'posts_per_page'	=> -1
					));
					if ( $posts ) {

						// Build list markup
						$list_markup = '';
						foreach ( $posts as $post ) {
							$list_markup .= '<li><a href="' . get_permalink( $post ) . '">' . get_the_title( $post ) . '</a></li>';
						}

						// Calculate the position to insert the posts
						$pos =	$matches[1][1] + // start of the link text
							strlen( $matches[1][0] ) + // length of the link text
							strlen( $cpt_landing_pattern_end ); // length of end of pattern

						// Add posts add the right position
						$menu = substr_replace( $menu, '<ul class="children">' . $list_markup . '</ul>', $pos, 0 );

					}

				}

			}

		}

	}

	return $menu;
}