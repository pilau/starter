<?php

/**
 * WordPress Toolbar customization (formerly admin bar)
 *
 * @package	Pilau_Starter
 * @since	0.1
 * @link	http://www.sitepoint.com/change-wordpress-33-toolbar/
 */
add_action( 'admin_bar_menu', 'pilau_customize_toolbar', 10000 );
function pilau_customize_toolbar( $toolbar ) {

	/* Remove comments? */
	if ( ! PILAU_USE_COMMENTS )
		$toolbar->remove_node( 'comments' );

	/* Remove themes */
	$toolbar->remove_node( 'appearance' );

	/* For the front-end  */
	if ( ! is_admin() ) {

		/* Add widgets under site name */
		$toolbar->add_node(array(
			'id'		=> 'widgets',
			'title'		=> 'Widgets',
			'parent'	=> 'site-name',
			'href'		=> '/wp-admin/widgets.php'
		));

		/* Generic refreshing of any data cached by theme */
		$toolbar->add_node(array(
			'id'		=> 'refresh',
			'title'		=> 'Refresh',
			'href'		=> '?refresh=1'
		));

	}

}
