<?php

/**
 * Email login credentials to a newly-registered user
 *
 * Overwriters the function in wp-includes/pluggable.php
 *
 * @param int    $user_id        User ID.
 * @param string $plaintext_pass Optional. The user's plaintext password. Default empty.
 */
function wp_new_user_notification($user_id, $plaintext_pass = '') {
	$user = get_userdata( $user_id );

	// The blogname option is escaped with esc_html on the way into the database in sanitize_option
	// we want to reverse this for the plain text arena of emails.
	$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

	$message  = sprintf(__('New user registration on your site %s:'), $blogname) . "\r\n\r\n";
	$message .= sprintf(__('Username: %s'), $user->user_login) . "\r\n\r\n";
	$message .= sprintf(__('E-mail: %s'), $user->user_email) . "\r\n";

	@wp_mail(get_option('admin_email'), sprintf(__('[%s] New User Registration'), $blogname), $message);

	if ( empty($plaintext_pass) )
		return;

	$message = "\r\n" . sprintf( __( 'Please login here: %s' ), get_permalink( WALS_PAGE_ID_MEMBERS ) ) . "\r\n";
	$message .= "\r\n";
	$message .= sprintf( __( 'Please login using this email address (%s) and the password you chose when you registered.' ), $user->user_email ) . "\r\n";
	$message .= "\r\n";
	$message .= sprintf( __( 'If you\'ve lost your password, you can reset it here: %s' ), wp_lostpassword_url() ) . "\r\n";

	wp_mail($user->user_email, sprintf(__('[%s] Your account is now activated'), $blogname), $message);

}
