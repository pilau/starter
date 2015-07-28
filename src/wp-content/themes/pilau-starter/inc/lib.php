<?php

/**
 * Library of general helper functions
 *
 * @package	Pilau_Starter
 * @since	0.1
 */


/**
 * Output post date
 *
 * @since	Pilau_Starter 0.1
 * @uses	the_time()
 * @return	void
 */
function pilau_post_date() { ?>
	<time datetime="<?php the_time( DATE_W3C ); ?>"><?php the_time( get_option( 'date_format' ) ); ?></time>
<?php }


/**
 * Output social media icons
 *
 * @since	Pilau_Starter 0.1
 *
 * @param	bool	$global		Global style?
 * @return	void
 */
function pilau_share_icons( $global = false ) {
	$url = '';
	if ( $global ) {
		$url = ' st_url="' . home_url() . '"';
	}
	?>
	<ul>
		<li class="facebook img-rep"><span class="st_facebook_custom" tabindex="0" title="<?php _e( 'Share on Facebook' ); ?>"<?php echo $url; ?>>Facebook</span></li>
		<li class="twitter img-rep"><span class="st_twitter_custom" st_via="<?php echo PILAU_USERNAME_TWITTER; ?>" tabindex="0" title="<?php _e( 'Share on Twitter' ); ?>"<?php echo $url; ?>>Twitter</span></li>
		<li class="google img-rep"><span class="st_plusone_custom" tabindex="0" title="<?php _e( 'Share on Google Plus' ); ?>"<?php echo $url; ?>>Google+</span></li>
		<?php if ( ! $global ) { ?>
			<li class="email img-rep"><span class="st_email_custom" tabindex="0" title="<?php _e( 'Share by email' ); ?>"<?php echo $url; ?>>Email</span></li>
		<?php } ?>
		<li class="share img-rep"><span class="st_sharethis_custom" st_via="<?php echo PILAU_USERNAME_TWITTER; ?>" tabindex="0" title="<?php _e( 'More sharing options...' ); ?>"<?php echo $url; ?>>More sharing...</span></li>
	</ul>
<?php
}


/**
 * Create plain share URLs for different social media services
 *
 * @since	Pilau_Starter 0.1
 *
 * @param	string	$service	'facebook' | 'twitter' | 'google+'
 * @param	string	$url		Defaults to current URL
 * @return	string
 */
function pilau_share_url( $service, $url = null ) {
	$share_url = '';

	if ( ! $url ) {
		$url = get_permalink();
	}

	switch ( $service ) {
		case 'facebook': {
			$share_url = 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode( $url );
			break;
		}
		case 'twitter': {
			$share_url = 'https://twitter.com/home?status=' . urlencode( $url );
			break;
		}
		case 'google+': {
			$share_url = 'https://plus.google.com/share?url=' . urlencode( $url );
			break;
		}
	}

	return $share_url;
}


/**
 * Output tweets
 *
 * Works with oAuth Twitter Feed for Developers plugin
 *
 * @since	Pilau_Starter 0.1
 *
 * @param	int		$max
 * @return	void
 */
function pilau_tweets( $max = 4 ) {
	if ( PILAU_PLUGIN_EXISTS_TWITTER_FEED && defined( 'PILAU_USERNAME_TWITTER' ) && PILAU_USERNAME_TWITTER ) {

		// Get tweets
		$tweets = getTweets( $max, false, array( 'include_rts' => true ) );
		if ( $tweets && ! array_key_exists( 'error', $tweets ) ) {

			echo '<ul class="tweets">';

			foreach ( $tweets as $tweet ) {
				//echo '<pre>'; print_r( $tweet ); echo '</pre>';
				$tweet_date = explode( ' ', $tweet['created_at'] );
				$tweet_time = implode( ':', array_slice( explode( ':', $tweet_date[3] ), 0, 2 ) );
				$tweet_text = $tweet['text'];
				if ( isset( $tweet['retweeted_status']['text'] ) ) {
					$tweet_text = 'RT: ' . $tweet['retweeted_status']['text'];
					//$screen_name = $tweet['entities']['user_mentions'][0]['screen_name'];
				}
				echo '<li><p class="tweet-date"><a href="' . pilau_construct_website_url( 'twitter', PILAU_USERNAME_TWITTER ) . '/status/' . $tweet['id_str'] . '" title="Link to this tweet">' . $tweet_time . ' ' . $tweet_date[2] . ' ' . $tweet_date[1] . ' ' . $tweet_date[5] . '</a></p><p class="tweet-text"><a class="user-link" href="' . pilau_construct_website_url( 'twitter', PILAU_USERNAME_TWITTER ) . '">@' . PILAU_USERNAME_TWITTER . '</a>: ' . pilau_link_urls( esc_html( $tweet_text ) ) . '</p></li>';
			}

			echo '</ul>';

		} else {

			echo '<p><em>Tweets coming soon...</em></p>';

		}

	} else {

		echo '<p><em>Tweets coming soon...</em></p>';

	}
}


/**
 * Create Twitter follow link
 *
 * @since	Pilau_Starter 0.1
 * @param	string		$user
 * @return	string
 */
function pilau_twitter_follow_link( $user = null ) {

	if ( ! $user && defined( 'PILAU_USERNAME_TWITTER' ) && PILAU_USERNAME_TWITTER ) {
		$user = PILAU_USERNAME_TWITTER;
	}

	return 'https://twitter.com/intent/user?screen_name=' . $user;
}


/**
 * Create Google map link
 *
 * @since	Pilau_Starter 0.1
 * @param	string		$location
 * @return	string
 */
function pilau_google_map_link( $location ) {
	$link = '';

	if ( $location && is_string( $location ) ) {
		$link = 'https://maps.google.co.uk/?q=' . urlencode( $location );
	}

	return $link;
}


/**
 * Output a Google Map
 *
 * Will only output if maps JS is enqueued
 *
 * @since	Pilau_Starter 0.1
 * @param	string		$latitude
 * @param	string		$longitude
 * @param	string		$width
 * @param	string		$height
 * @param	string		$id
 * @param	bool		$marker
 * @param	array		$args
 * @return	void
 * @todo	Multiple maps not working
 */
function pilau_google_map( $latitude, $longitude, $width = '100%', $height = '400px', $id = null, $marker = true, $args = array() ) {
	if ( wp_script_is( 'google-maps-api', 'enqueued' ) ) {
		static $count = 0;

		// Default ID
		if ( is_null( $id ) ) {
			$id = 'pilau-google-map-' . $count;
		}

		// Default args
		// @link https://developers.google.com/maps/documentation/javascript/reference#MapOptions
		$args = wp_parse_args(
			$args,
			array(
				'disableDoubleClickZoom'	=> false,
				'draggable'					=> true,
				'mapTypeControl'			=> false,
				'mapTypeId'					=> 'ROADMAP',
				'maxZoom'					=> null,
				'minZoom'					=> null,
				'scrollwheel'				=> false,
				'streetViewControl'			=> false,
				'zoom'						=> 15,
				'zoomControl'				=> true,
			)
		);

		// Output canvas
		echo '<div class="pilau-google-map-canvas" id="' . esc_attr( $id ). '" style="width:' . esc_attr( $width ) . '; height:' . esc_attr( $height ) . '"></div>';

		// Output inline JS ?>
		<script>
			window.onload = function () {
				if ( typeof google === 'object' && typeof google.maps === 'object' ) {
					var latlng = new google.maps.LatLng( <?php echo floatval( $latitude ); ?>, <?php echo floatval( $longitude ); ?> );
					var map = new google.maps.Map(
						document.getElementById( '<?php echo esc_attr( $id ); ?>' ),
						{
							disableDoubleClickZoom:		<?php echo json_encode( $args['disableDoubleClickZoom'] ); ?>,
							center:						latlng,
							draggable:					<?php echo json_encode( $args['draggable'] ); ?>,
							mapTypeControl:				<?php echo json_encode( $args['mapTypeControl'] ); ?>,
							<?php if ( ! is_null( $args['maxZoom'] ) ) { ?>
							maxZoom:				<?php echo (int) $args['maxZoom']; ?>,
							<?php } ?>
							<?php if ( ! is_null( $args['minZoom'] ) ) { ?>
							minZoom:				<?php echo (int) $args['minZoom']; ?>,
							<?php } ?>
							scrollwheel:				<?php echo json_encode( $args['scrollwheel'] ); ?>,
							streetViewControl:			<?php echo json_encode( $args['streetViewControl'] ); ?>,
							zoom:						<?php echo floatval( $args['zoom'] ); ?>,
							zoomControl:				<?php echo json_encode( $args['zoomControl'] ); ?>,
						}
					);
					<?php if ( $marker ) { ?>
					var marker = new google.maps.Marker({
						position:	latlng,
						map:		map,
						animation:	google.maps.Animation.DROP,
					});
					<?php } ?>
				}
			};
		</script>
		<?php

		$count++;
	}
}


/**
 * Generate teaser text
 *
 * Tries to get WP SEO meta description; uses automated extract as fallback
 *
 * @since	Pilau_Starter 0.1
 *
 * @param	int		$post_id	Defaults to ID of post in current loop
 * @param	int		$max_words	For extract, maximum words
 * @param	int		$max_paras	For extract, maximum paragraphs
 * @param	bool	$strip_tags	For extract, strip tags?
 * @param	bool	$custom		Check for custom field first
 * @return	string
 */
function pilau_teaser_text( $post_id = null, $max_words = 30, $max_paras = 0, $strip_tags = true, $custom = true ) {
	global $pilau_custom_fields;
	$teaser = '';

	// Default post ID
	if ( ! $post_id ) {
		$post_id = get_the_ID();
		$custom_fields = $pilau_custom_fields;
	} else {
		$custom_fields = pilau_get_custom_fields( $post_id, 'post' );
	}

	// Check for custom first?
	if ( $custom && ! empty( $custom_fields['teaser-text'] ) ) {
		$teaser = $custom_fields['teaser-text'];
	}

	// If we've still got nothing
	if ( empty( $teaser ) ) {

		// Check for meta description
		if ( PILAU_PLUGIN_EXISTS_WPSEO ) {
			$teaser = trim( WPSEO_Meta::get_value( 'metadesc', $post_id ) );
		}

		// If still nothing, content extract
		if ( empty( $teaser ) && function_exists( 'pilau_extract' ) ) {
			$teaser = pilau_extract( get_post_field( 'post_content', $post_id ), $max_words, $max_paras, $strip_tags );
		}

	}

	return $teaser;
}


add_filter( 'the_posts', 'pilau_multiply_posts', 10, 2 );
/**
 * Allow the multiplication of posts in query results for testing purposes
 *
 * @since	Pilau_Starter 0.1
 */
function pilau_multiply_posts( $posts, $query ) {

	// Is the query set to multiply
	if ( isset( $query->query['pilau_multiply'] ) && $query->query['pilau_multiply'] ) {

		// Store original set of posts
		$posts_original = $posts;

		// Multiply
		for ( $i = 1; $i < $query->query['pilau_multiply']; $i++ ) {
			$posts = array_merge( $posts, $posts_original );
		}

		// Adjust count
		$query->found_posts = count( $posts );

	}

	return $posts;
}


/**
 * Get the default ID for a type of object
 *
 * @since		0.1
 * @param		string		$type	'post' | 'user'
 * @param		int			$id
 * @return		int
 */
function pilau_default_object_id( $type = 'post', $id = null ) {
	global $post, $profileuser;

	if ( empty( $id ) ) {

		switch ( $type ) {

			case 'post': {

				// Post ID
				if ( is_object( $post ) && property_exists( $post, 'ID' ) ) {
					$id = $post->ID;
				}

				break;
			}

			case 'user': {

				if ( is_admin() ) {

					// ID of user being edited in admin
					$id = $profileuser->ID;

				} else {

					// Front-end
					if ( is_author() ) {
						// Author archive page
						$user = null;
						if ( get_query_var( 'author_name' ) ) {
							$user = get_user_by( 'slug', get_query_var( 'author_name' ) );
						} else if ( get_query_var( 'author' ) ) {
							$user = get_userdata( get_query_var( 'author' ) );
						}
						if ( is_object( $user ) && property_exists( $user, 'ID' ) ) {
							$id = $user->ID;
						}
					} else {
						// Try to get author of current post
						$id = $post->post_author;
					}

				}

				break;
			}

		}

	}

	return $id;
}
