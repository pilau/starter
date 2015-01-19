<?php

/**
 * Widgets and sidebars
 *
 * @package	Pilau_Starter
 * @since	0.1
 */


add_action( 'widgets_init', 'pilau_register_sidebars' );
/**
 * Register sidebars
 *
 * @since	Pilau_Starter 0.1
 */
function pilau_register_sidebars() {

	/* Primary sidebar */
	register_sidebar( array(
		'id'				=> 'primary-sidebar',
		'name'				=> __( 'Primary sidebar' ),
		'before_widget'		=> '<aside id="%1$s" class="widget %2$s">',
		'after_widget'		=> '</aside>',
		'before_title'		=> '<h2 class="widget-title">',
		'after_title'		=> '</h2>',
	));

}


add_action( 'widgets_init', 'pilau_unregister_widgets', 1 );
/**
 * Unregister some default widgets
 *
 * @since	Pilau_Starter 0.1
 */
function pilau_unregister_widgets() {
	unregister_widget( 'WP_Widget_Links' );
	unregister_widget( 'WP_Widget_Meta' );
	unregister_widget( 'WP_Widget_Recent_Posts' );
	unregister_widget( 'WP_Widget_RSS' );
	if ( ! PILAU_USE_TAGS ) {
		unregister_widget( 'WP_Widget_Tag_Cloud' );
	}
	if ( ! PILAU_USE_CATEGORIES ) {
		unregister_widget( 'WP_Widget_Categories' );
	}
	if ( ! PILAU_USE_COMMENTS ) {
		unregister_widget( 'WP_Widget_Recent_Comments' );
	}
	//unregister_widget( 'WP_Widget_Search' );
	//unregister_widget( 'WP_Widget_Text' );
	//unregister_widget( 'WP_Widget_Pages' );
	//unregister_widget( 'WP_Widget_Calendar' );
	//unregister_widget( 'WP_Widget_Archives' );
}


if ( function_exists( 'slt_obfuscate_email' ) ) {
	add_filter( 'widget_text', 'pilau_widget_email_obfuscation' );
}
/**
 * Obfuscate emails in widgets
 *
 * @since	Pilau_Starter 0.1
 * @uses	pilau_obfuscate_email()
 */
function slt_widget_email_obfuscation( $text ) {
	return preg_replace_callback( '/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i', create_function( '$matches', 'return pilau_obfuscate_email( $matches[0] );' ), $text );
}
