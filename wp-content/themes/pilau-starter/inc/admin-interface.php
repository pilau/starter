<?php

/**
 * Admin interface customization
 *
 * @package	Pilau_Starter
 * @since	0.1
 */


/**
 * Admin scripts and styles
 *
 * @since	Pilau_Starter 0.1
 */
add_action( 'admin_enqueue_scripts', 'pilau_admin_enqueue_scripts_styles' );
function pilau_admin_enqueue_scripts_styles() {
	wp_enqueue_style( 'pilau-admin', get_template_directory_uri() . '/css/wp-admin.css', array(), '1.0' );
}


/**
 * Custom Post Type icons
 *
 * Get icons from @link http://randyjensenonline.com/thoughts/wordpress-custom-post-type-fugue-icons/
 * Name icons cpt-[POST-TYPE].png and place into img/icons/
 *
 * @since	Pilau_Starter 0.1
 * @todo Test!
 * @todo Document on GitHub wiki
 */
//add_action( 'admin_head', 'pilau_cpt_icons' );
function pilau_cpt_icons() {
	$cpts = get_post_types( array( 'show_ui' => true ), 'names' );
	if ( $cpts ) {
		?>
		<style media="screen">
			<?php foreach ( $cpts as $cpt ) { ?>
			#menu-posts-<?php echo $cpt; ?> .wp-menu-image {
				background: url('<?php echo get_template_directory_uri(); ?>/img/icons/cpt-<?php echo $cpt; ?>.png') no-repeat 6px -17px !important;
			}
			#menu-posts-<?php echo $cpt; ?>:hover .wp-menu-image, #menu-posts-<?php echo $cpt; ?>.wp-has-current-submenu .wp-menu-image {
				background-position: 6px 7px !important;
			}
			<?php } ?>
		</style>
		<?php
	}
}


/**
 * Admin notices
 *
 * @since	Pilau_Starter 0.1
 */
add_action( 'admin_notices', 'pilau_admin_notices' );
function pilau_admin_notices() {
	global $pilau_theme_options;

	// Theme activation
	if ( ! $pilau_theme_options['settings_script_run'] ) {

	}

}


/**
 * Admin menus
 *
 * @since	Pilau_Starter 0.1
 */
add_action( 'admin_menu', 'pilau_admin_menus' );
function pilau_admin_menus() {

	/* Customize standard menus
	***************************************************************************/

	// Links
	if ( ! PILAU_USE_LINKS )
		remove_menu_page( 'link-manager.php' );

	// Comments
	if ( ! PILAU_USE_COMMENTS )
		remove_menu_page( 'edit-comments.php' );

	// Menu for all settings
	//add_options_page( __('All Settings'), __('All Settings'), 'manage_options', 'options.php' );

	/* Register new menus
	***************************************************************************/

	// Theme plugins
	if ( PILAU_USE_PLUGINS_PAGE )
		add_submenu_page( 'plugins.php', __( 'Pilau plugins' ), __( 'Pilau plugins' ), 'update_core', 'pilau-plugins', 'pilau_plugins_page' );

}


/**
 * Theme plugins page
 *
 * @since	Pilau_Starter 0.1
 */
function pilau_plugins_page() {

	$plugins_list_table = new Pilau_Plugins_Table();
	$plugins_list_table->prepare_items();
	$plugins_list_table->display();

}


/**
 * Remove meta boxes
 *
 * @since	Pilau_Starter 0.1
 */
add_action( 'add_meta_boxes', 'pilau_remove_meta_boxes' );
function pilau_remove_meta_boxes() {

	/* Comments */
	if ( ! PILAU_USE_COMMENTS ) {
		remove_meta_box( 'commentsdiv', 'post', 'normal' );
		remove_meta_box( 'commentsdiv', 'page', 'normal' );
		remove_meta_box( 'commentstatusdiv', 'post', 'normal' );
		remove_meta_box( 'commentstatusdiv', 'page', 'normal' );
	}

	/* Publish */
	//remove_meta_box( 'submitdiv', 'post', 'normal' );

	/* Revisions */
	//remove_meta_box( 'revisionsdiv', 'post', 'normal' );

	/* Author */
	//remove_meta_box( 'authordiv', 'post', 'normal' );

	/* Slug */
	//remove_meta_box( 'slugdiv', 'post', 'normal' );

	/* Excerpt */
	//remove_meta_box( 'postexcerpt', 'post', 'normal' );

	/* Post format */
	//remove_meta_box( 'formatdiv', 'post', 'normal' );

	/* Trackbacks */
	//remove_meta_box( 'trackbacksdiv', 'post', 'normal' );

	/* Featured image */
	//remove_meta_box( 'postimagediv', 'post', 'side' );

	/* Page attributes */
	//remove_meta_box( 'pageparentdiv', 'page', 'side' );

}


/**
 * Customize list columns
 *
 * For the most part these should be handled by the Codepress Admin Columns plugin.
 * Include any necessary overrides here.
 *
 * @since	Pilau_Starter 0.1
 */

/** Post columns */
add_filter( 'manage_edit-post_columns', 'pilau_post_columns', 10000 );
function pilau_post_columns( $cols ) {
	if ( ! PILAU_USE_CATEGORIES )
		unset( $cols['categories'] );
	if ( ! PILAU_USE_TAGS)
		unset( $cols['tags'] );
	if ( ! PILAU_USE_COMMENTS )
		unset( $cols['comments'] );
	return $cols;
}

/** Media columns */
add_filter( 'manage_upload_columns', 'pilau_media_columns', 10000 );
function pilau_media_columns( $cols ) {
	if ( ! PILAU_USE_COMMENTS )
		unset( $cols['comments'] );
	return $cols;
}


/**
 * Customize default tiny MCE buttons
 *
 * @since	Pilau_Starter 0.1
 * @link	http://wpengineer.com/customize-wordpress-wysiwyg-editor/
 * @link	http://wiki.moxiecode.com/index.php/TinyMCE:Control_reference
 */
add_filter( 'tiny_mce_before_init', 'pilau_tinymce_buttons' );
function pilau_tinymce_buttons( $init_array ) {
	$init_array['theme_advanced_blockformats'] = 'p,h2,h3';
	$init_array['theme_advanced_disable'] = 'forecolor,strikethrough,justifyleft,justifyright,justifyfull,underline,media';
	return $init_array;
}


/**
 * Disable default dashboard widgets
 *
 * @since	Pilau_Starter 0.1
 * @link	http://digwp.com/2010/10/customize-wordpress-dashboard/
 */
add_action( 'admin_menu', 'pilau_disable_default_dashboard_widgets' );
function pilau_disable_default_dashboard_widgets() {
	remove_meta_box( 'dashboard_plugins', 'dashboard', 'core' );
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'core' );
	remove_meta_box( 'dashboard_primary', 'dashboard', 'core' ); /* WordPress blog */
	remove_meta_box( 'dashboard_secondary', 'dashboard', 'core' ); /* Other WordPress News */
	/**
	 * NOTE: Right now is removed largely because this theme can be set to disable
	 * core taxonomies (i.e. categories or tags) - and the core Right now widget
	 * doesn't test to see if the taxonomies exist before outputting their details.
	 * @todo Come up with a good replacement "overview" widget
	 */
	remove_meta_box( 'dashboard_right_now', 'dashboard', 'core' );
	if ( ! PILAU_USE_COMMENTS )
		remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'core' );
	//remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'core' );
	//remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'core' );
}


/**
 * Menus hidden columns
 *
 * @since	Pilau_Starter 0.1
 */
add_filter( 'get_user_option_managenav-menuscolumnshidden', 'pilau_nav_menus_columns_hidden' );
function pilau_nav_menus_columns_hidden( $result ) {

	/** Description always on */
	if ( in_array( 'description', $result ) )
		unset( $result[ array_search( 'description', $result ) ] );

	return $result;
}