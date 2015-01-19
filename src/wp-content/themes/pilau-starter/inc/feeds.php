<?php

/**
 * Feed customization
 *
 * For details on how to completely control WordPress feeds, see:
 * @link http://www.456bereastreet.com/archive/201103/controlling_and_customising_rss_feeds_in_wordpress/
 *
 * @package	Pilau_Starter
 * @since	0.1
 */


add_action( 'wp_head', 'pilau_feed_links' );
/**
 * Pilau feed links
 *
 * This is used instead of the core feed_links() function (which is triggered if
 * the theme is set to support 'automatic-feed-links'), because that outputs both
 * posts and comments feed links. Here, we can optionally not output the comments
 * feed link. Note that even if the link isn't in the header, the feed is still there.
 * To properly remove it:
 * @link http://www.456bereastreet.com/archive/201103/controlling_and_customising_rss_feeds_in_wordpress/
 *
 * @since	Pilau_Starter 0.1
 */
function pilau_feed_links() {

	$defaults = array(
		/* translators: Separator between blog name and feed type in feed links */
		'separator'	=> _x('&raquo;', 'feed link'),
		/* translators: 1: blog title, 2: separator (raquo) */
		'feedtitle'	=> __('%1$s %2$s Feed'),
		/* translators: %s: blog title, 2: separator (raquo) */
		'comstitle'	=> __('%1$s %2$s Comments Feed'),
	);

	echo '<link rel="alternate" type="' . feed_content_type() . '" title="' . esc_attr(sprintf( $defaults['feedtitle'], get_bloginfo('name'), $defaults['separator'] )) . '" href="' . get_feed_link() . "\">\n";

	if ( PILAU_USE_COMMENTS ) {
		echo '<link rel="alternate" type="' . feed_content_type() . '" title="' . esc_attr(sprintf( $defaults['comstitle'], get_bloginfo('name'), $defaults['separator'] )) . '" href="' . get_feed_link( 'comments_' . get_default_feed() ) . "\">\n";
	}

}

