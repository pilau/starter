<?php

/**
 * Content functionality
 *
 * @package	Pilau_Starter
 * @since	0.1
 */


/**
 * Custom handling handling of WP "more" tag
 *
 * @since	Pilau_Starter 0.1
 * @uses	pilau_extract()
 *
 * @param	$content Content from WP post
 * @param	string $action 'extended' (return text after more tag) | 'extract' (return text before more tag)
 * 			or
 * @param	bool $strip_imgs
 * @param	int $paragraphs
 * @param	bool $filter Apply the_content filter to result?
 * @return	string
 */
function pilau_more( $content, $action = "extract", $strip_imgs = true, $paras = 1, $filter = true ) {
	$more = '<!--more-->';
	$more_pos = strpos( $content, $more );

	switch( $action ) {
		case "extended": {
			if ( $more_pos !== false ) {
				// Extended, i.e. return text after "more"
				$content = substr( $content, 0, ( $more_pos + strlen( $more ) ) );
			} else {
				// No more tag, return nothing
				$content = "";
			}
			break;
		}
		default: {
			if ( $more_pos !== false ) {
				// Extract, i.e. return text before "more"
				$content = substr( $content, 0, ( $more_pos - 1 ) );
			} else {
				// No "more" tag, get an automatic extract
				$content = pilau_extract( $content, 0, $paras );
			}
			break;
		}
	}

	if ( $strip_imgs )
		$content = preg_replace( '/<img[^\/]*\/?>/i', '', $content );

	if ( $filter )
		$content = apply_filters( "the_content", $content );

	return $content;
}

