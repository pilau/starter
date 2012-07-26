<?php

/**
 * Content functionality
 *
 * @package	Pilau_Starter
 * @since	0.1
 */


/**
 * Custom handling of content output
 *
 * @since	Pilau_Starter 0.1
 * @uses	get_the_content()
 * @uses	pilau_extract()
 * @uses	get_the_excerpt()
 *
 * @param	string $content Content from WP post - defaults to current Loop content
 * @param	string $action 'extended' (return text after more tag) | 'extract' (return text before more tag)
 * @param	bool $strip_imgs
 * @param	int $paragraphs
 * @param	bool $filter Apply the_content filter to result?
 * @return	string
 */
function pilau_content( $content = null, $action = "extract", $strip_imgs = true, $paras = 1, $filter = true ) {
	if ( is_null( $content ) )
		$content = get_the_content();
	$more = '<!--more-->';
	$more_pos = strpos( $content, $more );

	if ( $strip_imgs )
		$content = preg_replace( '/<img[^\/]*\/?>/i', '', $content );

	switch( $action ) {

		case "extended":
			if ( $more_pos !== false ) {
				// Extended, i.e. return text after "more"
				$content = substr( $content, 0, ( $more_pos + strlen( $more ) ) );
			} else {
				// No more tag, return nothing
				$content = "";
			}
			break;

		default:
			if ( $more_pos !== false ) {
				// Extract, i.e. return text before "more"
				$content = substr( $content, 0, ( $more_pos - 1 ) );
			} else {
				// No "more" tag, is there a specific excerpt set?
				if ( $excerpt = get_the_excerpt() ) {
					// Use the excerpt
					$content = $excerpt;
				} else {
					// Get a manual extract
					$content = pilau_extract( $content, 0, $paras );
				}
			}
			break;

	}

	if ( $filter )
		$content = apply_filters( "the_content", $content );

	return $content;
}


/**
 * Return the description or an extract
 *
 * If there's a description set by Yoast SEO or Developer's Custom Fields, returns
 * that; otherwise, returns a content extract. Content is always tidied up, i.e. no HTML tags.
 *
 * @since	Pilau_Starter 0.1
 * @uses	slt_cf_field_value()
 * @uses	get_post()
 * @uses	pilau_extract()
 * @param	$post_id
 * @param	string $description If you possibly already have the description, pass it here
 * 			to be trimmed
 * @param	string $post_content  If you possibly already have the full content, pass it here
 * @param	int $maxwords Set to zero to avoid trimming description custom field; content will
 * 			always be trimmed.
 * @return	string
 *
 */
function pilau_description_or_extract( $post_id, $description = '', $post_content = '', $maxwords = 30 ) {
	if ( function_exists( 'wpseo_get_value' ) && ! $description )
		$description = wpseo_get_value( 'metadesc', $post_id );
	if ( function_exists( 'slt_cf_field_value' ) && ! $description )
		$description = slt_cf_field_value( 'description', 'post', $post_id );
	if ( ! $description ) {
		if ( ! $post_content ) {
			$post_data = get_post( $post_id );
			$post_content = $post_data->post_content;
		}
		$post_content = strip_shortcodes( $post_content );
		$post_content = trim( strip_tags( $post_content ) );
		$description = $post_content;
		if ( $maxwords )
			$description = pilau_extract( $description, $maxwords );
	}
	return $description;
}


/**
 * "Not found" fragment
 *
 * @since	Pilau_Starter 0.1
 * @param	string $title
 */
function pilau_not_found( $title = null ) {
	if ( ! $title )
		$title = __( 'Content not found' );
	?>

	<article id="post-0" class="post error404 not-found" role="article">
		<h1><?php echo $title; ?></h1>
		<div class="post-content">
			<p><?php _e( "The content you're looking for could not be found. Please try navigating somewhere else, or try searching." ); ?></p>
		</div>
	</article><!-- #post-0 -->

	<?php
}

/**
 * 404 page title
 *
 * To be hooked as a filter onto wp_title when needed.
 *
 * @since	Pilau_Starter 0.1
 * @param	string $title
 * @param	string $sep
 * @param	string $seplocation
 * @return	string
 * @todo	Respect $seplocation
 */
function pilau_404_title( $title, $sep, $seplocation ) {
	return __( 'Page not found' ) . ' ' . $sep . ' ' . get_bloginfo( 'name' );
}
