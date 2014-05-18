<?php

/**
 * Admin interface customization
 *
 * @package	Pilau_Starter
 * @since	0.1
 */


/**
 * Admin interface initialization
 *
 * @since	Pilau_Starter 0.1
 */
add_action( 'admin_init', 'pilau_admin_interface_init' );
function pilau_admin_interface_init() {

	/* Enable HTML markup in user profiles */
	//remove_filter( 'pre_user_description', 'wp_filter_kses' );

	/* Disable captions */
	//add_filter( 'disable_captions', '__return_true' );

	/* Disable post format UI
	 * @link	http://wordpress.org/extend/plugins/disable-post-format-ui/
	 */
	add_filter( 'enable_post_format_ui', '__return_false' );

	/* Set up inline hints for image sizes */
	//add_filter( 'admin_post_thumbnail_html', 'pilau_inline_image_size_featured', 10, 2 );

	/* Customize list columns
	 *
	 * For the most part these should be handled by the Codepress Admin Columns plugin.
	 * Include any necessary overrides here.
	 */
	add_filter( 'manage_edit-post_columns', 'pilau_admin_columns', 100000, 1 );
	add_filter( 'manage_edit-page_columns', 'pilau_admin_columns', 100000, 1 );
	foreach ( get_post_types( array( 'public' => true ), 'names' ) as $pt ) {
		add_filter( 'manage_' . $pt . '_posts_columns', 'pilau_admin_columns', 100000, 1 );
	}

}


/**
 * Admin scripts and styles
 *
 * @since	Pilau_Starter 0.1
 */
//add_action( 'admin_enqueue_scripts', 'pilau_admin_enqueue_scripts_styles', 10 );
function pilau_admin_enqueue_scripts_styles() {

	wp_enqueue_style( 'pilau-admin-css', get_stylesheet_directory_uri() . '/styles/wp-admin.css', array(), '1.0' );
	wp_enqueue_script( 'pilau-admin-js', get_stylesheet_directory_uri() . '/js/wp-admin.js', array(), '1.0' );

}


/**
 * Admin notices
 *
 * @since	Pilau_Starter 0.1
 */
//add_action( 'admin_notices', 'pilau_admin_notices', 10 );
function pilau_admin_notices() {

}

/**
 * Test output of $current_screen
 *
 * @since	Pilau_Starter 0.1
 */
//add_action( 'admin_notices', 'pilau_check_current_screen' );
function pilau_check_current_screen() {
	if ( ! is_admin() || ! current_user_can( 'update_core' ) ) {
		return;
	}
	global $current_screen;
	echo '<pre>'; print_r( $current_screen ); echo '</pre>';
}


/**
 * Admin menus
 *
 * @since	Pilau_Starter 0.1
 */
add_action( 'admin_menu', 'pilau_admin_menus', 10 );
function pilau_admin_menus() {

	/* Customize standard menus
	***************************************************************************/

	// Links
	if ( ! PILAU_USE_LINKS ) {
		remove_menu_page( 'link-manager.php' );
	}

	// Comments
	if ( ! PILAU_USE_COMMENTS ) {
		remove_menu_page( 'edit-comments.php' );
	}

	// Menu for all settings
	//add_options_page( __('All Settings'), __('All Settings'), 'manage_options', 'options.php' );

	/* Rename "Posts" in menu to "News"
	 *
	 * Most of our projects have "News", not "Blog"
	 * @link http://new2wp.com/snippet/change-wordpress-posts-post-type-news/
	 */
	global $menu, $submenu;
	$menu[5][0] = 'News';
	$submenu['edit.php'][5][0] = 'News';
	$submenu['edit.php'][10][0] = 'Add News';
	$submenu['edit.php'][16][0] = 'News Tags';

}


/**
 * Rename "Posts" in post type object to "News"
 *
 * @since	Pilau_Starter 0.1
 */
add_action( 'init', 'pilau_change_post_object_label' );
function pilau_change_post_object_label() {
	global $wp_post_types;
	$labels = &$wp_post_types['post']->labels;
	$labels->name = 'News';
	$labels->singular_name = 'News';
	$labels->add_new = 'Add News';
	$labels->add_new_item = 'Add News';
	$labels->edit_item = 'Edit News';
	$labels->new_item = 'News';
	$labels->view_item = 'View News';
	$labels->search_items = 'Search News';
	$labels->not_found = 'No News found';
	$labels->not_found_in_trash = 'No News found in Trash';
}


/**
 * Remove meta boxes
 *
 * @since	Pilau_Starter 0.1
 */
//add_action( 'add_meta_boxes', 'pilau_remove_meta_boxes', 10 );
function pilau_remove_meta_boxes() {

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
 * Inline image hints for Featured Image box
 *
 * @since	Pilau_Starter 0.1
 * @param	string	$content
 * @return	string
 */
function pilau_inline_image_size_featured( $content ) {
	$hint = null;

	switch ( get_post_type() ) {
		case 'investment':
			$hint = 'Optimum image size: 647 x 461 px. If larger, try to keep similar proportions.';
			break;
		case 'page':
			switch ( get_page_template_slug() ) {
				case 'page_priority-area.php' :
					$hint = 'Optimum image size: 472 x 364 px. If larger, try to keep similar proportions.';
					break;
				default:
					if ( get_page_template_slug() == '' )
						$hint = 'For listing page images, the optimum size is 203 x 161 px.';
					break;
			}
			break;
	}

	if ( $hint )
		$content = '<p><i>' . $hint . '</i></p>' . $content;

	return $content;
}


/**
 * Global handler for all post type columns
 *
 * @param	array $cols
 * @return	array
 */
function pilau_admin_columns( $cols ) {

	// Override WordPress SEO plugin stuff
	if ( defined( 'WPSEO_VERSION' ) ) {
		foreach ( array( /*'wpseo-score',*/ 'wpseo-title', 'wpseo-metadesc', 'wpseo-focuskw' ) as $wp_seo_col ) {
			if ( isset( $cols[ $wp_seo_col ] ) ) {
				unset( $cols[ $wp_seo_col ] );
			}
		}
	}

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
 * Disable editor for home page
 *
 * @since	Pilau_Starter 0.1
 */
//add_action( 'admin_head', 'pilau_disable_home_editor' );
function pilau_disable_home_editor() {
	global $post;
	if ( $post->ID == PILAU_HOME_PAGE_ID ) {
		remove_post_type_support( 'page', 'editor' );
	}
}


/**
 * Disable default dashboard widgets
 *
 * @since	Pilau_Starter 0.1
 * @link	http://codex.wordpress.org/Dashboard_Widgets_API
 */
add_action( 'wp_dashboard_setup', 'pilau_disable_default_dashboard_widgets', 10 );
function pilau_disable_default_dashboard_widgets() {

	remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_primary', 'dashboard', 'side' ); /* WordPress blog */
	remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' ); /* Other WordPress News */
	/**
	 * NOTE: Right now is removed largely because this theme can be set to disable
	 * core taxonomies (i.e. categories or tags) - and the core Right now widget
	 * doesn't test to see if the taxonomies exist before outputting their details.
	 * @todo Come up with a good replacement "overview" widget
	 */
	remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
	//remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
	//remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'normal' );

}


/**
 * Menus hidden columns
 *
 * @since	Pilau_Starter 0.1
 */
add_filter( 'get_user_option_managenav-menuscolumnshidden', 'pilau_nav_menus_columns_hidden' );
function pilau_nav_menus_columns_hidden( $result ) {

	/** Description always on */
	if ( is_array( $result ) && in_array( 'description', $result ) ) {
		unset( $result[ array_search( 'description', $result ) ] );
	}

	/** Classes always on
	if ( is_array( $result ) && in_array( 'styles-classes', $result ) ) {
		unset( $result[ array_search( 'styles-classes', $result ) ] );
	}
	*/

	return $result;
}
