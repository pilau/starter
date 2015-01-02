<?php

/**
 * WordPress Toolbar customization (formerly admin bar)
 *
 * @package	[[theme-phpdoc-name]]
 * @since	0.1
 * @link	http://www.sitepoint.com/change-wordpress-33-toolbar/
 */
//add_action( 'admin_bar_menu', 'pilau_customize_toolbar', 100000 );
function pilau_customize_toolbar( $toolbar ) {

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

	}

}
