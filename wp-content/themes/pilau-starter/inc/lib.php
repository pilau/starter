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
		<li class="twitter img-rep"><span class="st_twitter_custom" st_via="[Twitter username]" tabindex="0" title="<?php _e( 'Share on Twitter' ); ?>"<?php echo $url; ?>>Twitter</span></li>
		<li class="google img-rep"><span class="st_plusone_custom" tabindex="0" title="<?php _e( 'Share on Google Plus' ); ?>"<?php echo $url; ?>>Google+</span></li>
		<?php if ( ! $global ) { ?>
			<li class="email img-rep"><span class="st_email_custom" tabindex="0" title="<?php _e( 'Share by email' ); ?>"<?php echo $url; ?>>Email</span></li>
		<?php } ?>
		<li class="share img-rep"><span class="st_sharethis_custom" st_via="[Twitter username]" tabindex="0" title="<?php _e( 'More sharing options...' ); ?>"<?php echo $url; ?>>More sharing...</span></li>
	</ul>
<?php
}
