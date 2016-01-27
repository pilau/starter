<?php

/**
 * Admin interface customization
 *
 * @package	Pilau_Starter
 * @since	0.1
 */


add_action( 'admin_init', 'pilau_admin_interface_init' );
/**
 * Admin interface initialization
 *
 * @since	Pilau_Starter 0.1
 */
function pilau_admin_interface_init() {

	/* Enable HTML markup in user profiles */
	//remove_filter( 'pre_user_description', 'wp_filter_kses' );

	/* Disable captions */
	//add_filter( 'disable_captions', '__return_true' );

	/* Disable post format UI
	 * @link	http://wordpress.org/extend/plugins/disable-post-format-ui/
	 */
	//add_filter( 'enable_post_format_ui', '__return_false' );

	/* Set up inline hints for image sizes */
	add_filter( 'admin_post_thumbnail_html', 'pilau_inline_image_size_featured', 10, 2 );

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


add_action( 'admin_enqueue_scripts', 'pilau_admin_enqueue_scripts_styles', 10 );
/**
 * Admin scripts and styles
 *
 * @since	Pilau_Starter 0.1
 */
function pilau_admin_enqueue_scripts_styles() {
	global $current_screen, $pilau_gforms_protected;

	// Register any scripts that might be enqueued elsewhere
	wp_register_script( 'pilau-upload-media', get_stylesheet_directory_uri() . '/js/media.js', array( 'jquery' ), '1.0' );

	// Enqueue scripts
	wp_enqueue_style( 'pilau-admin-css', get_stylesheet_directory_uri() . '/styles/admin.css', array(), '1.0' );
	wp_enqueue_script( 'pilau-admin-js', get_stylesheet_directory_uri() . '/js/admin.js', array( 'jquery' ), '1.0' );

	// Anything to pass to admin JS?
	$admin_js_vars = array(
		'ajaxurl'			=> admin_url( 'admin-ajax.php', PILAU_REQUEST_PROTOCOL ),
		'user_is_admin'		=> current_user_can( 'update_core' )
	);

	// Protected Gravity Forms?
	if ( ! empty( $pilau_gforms_protected ) ) {
		$admin_js_vars['gforms_protected'] = $pilau_gforms_protected;
	}

	/*
	 * Check post edit screens for taxonomies with JS validation rules
	 *
	 * When registering a taxonomy, use the following custom arguments:
	 * (bool)	pilau_required (when true, tries to force the selection of at least one term)
	 * (bool)	pilau_multiple (when false, tries to make sure no more than one term can be selected)
	 * (bool)	pilau_hierarchical (when false, hides the Parent drop-down if hierarchical is true;
	 * 			useful if you want categories-style interface without actual hierarchy)
	 */
	$taxonomies = get_taxonomies( array( 'public' => true ), 'objects' );
	foreach ( $taxonomies as $taxonomy ) {
		if ( in_array( $current_screen->post_type, $taxonomy->object_type ) ) {
			foreach ( array( 'required', 'multiple', 'hierarchical' ) as $arg ) {
				if ( isset( $taxonomy->{'pilau_' . $arg} ) ) {
					$admin_js_vars[ $taxonomy->name . '_' . $arg ] = ! empty( $taxonomy->{'pilau_' . $arg} );
				}
			}
		}
	}

	// Pass through even if empty to prevent pilau_admin not defined errors
	wp_localize_script( 'pilau-admin-js', 'pilau_admin', $admin_js_vars );

}


add_filter( 'gform_noconflict_scripts', 'pilau_gform_noconflict_scripts' );
/**
 * Tell Gravity Forms to use our admin scripts even if No Conflict mode is on
 */
function pilau_gform_noconflict_scripts( $scripts ) {
	$scripts[] = 'pilau-admin-js';
	return $scripts;
}


//add_action( 'admin_notices', 'pilau_admin_notices', 10 );
/**
 * Admin notices
 *
 * @since	Pilau_Starter 0.1
 */
function pilau_admin_notices() {

}

//add_action( 'admin_notices', 'pilau_check_current_screen' );
/**
 * Test output of $current_screen
 *
 * @since	Pilau_Starter 0.1
 */
function pilau_check_current_screen() {
	if ( ! is_admin() || ! current_user_can( 'update_core' ) ) {
		return;
	}
	global $current_screen;
	echo '<pre>'; print_r( $current_screen ); echo '</pre>';
}


add_action( 'admin_menu', 'pilau_admin_menus', 10 );
/**
 * Admin menus
 *
 * @since	Pilau_Starter 0.1
 */
function pilau_admin_menus() {
	global $menu, $submenu;

	/* Customize standard menus
	***************************************************************************/

	// Comments
	if ( ! PILAU_USE_COMMENTS ) {
		remove_menu_page( 'edit-comments.php' );
	}

	// Core taxonomies
	if ( ! PILAU_USE_CATEGORIES || PILAU_HIDE_CATEGORIES ) {
		remove_submenu_page( 'edit.php', 'edit-tags.php?taxonomy=category' );
	}
	if ( ! PILAU_USE_TAGS || PILAU_HIDE_TAGS ) {
		remove_submenu_page( 'edit.php', 'edit-tags.php?taxonomy=post_tag' );
	}

	// Menu for all settings
	//add_options_page( __('All Settings'), __('All Settings'), 'manage_options', 'options.php' );

	/* Rename "Posts" in menu to "News"
	 *
	 * Most of our projects have "News", not "Blog"
	 * @link http://new2wp.com/snippet/change-wordpress-posts-post-type-news/
	 */
	if ( PILAU_RENAME_POSTS_NEWS ) {
		//echo '<pre>'; print_r( $menu ); echo '</pre>';
		//echo '<pre>'; print_r( $submenu ); echo '</pre>'; exit;
		$menu[5][0] = 'News';
		$submenu['edit.php'][5][0] = 'All News';
	}

	/* Register new menus
	***************************************************************************/

	// Site settings
	add_options_page(
		get_bloginfo( 'name' ) . ' ' . __( 'settings' ),
		get_bloginfo( 'name' ) . ' ' . __( 'settings' ),
		'manage_options',
		'pilau-site-settings',
		'pilau_site_settings_admin_page'
	);

	/* Add help tabs
	***************************************************************************/

	// Jump links shortcode
	if ( shortcode_exists( 'anchor' ) ) {
		add_action( 'load-post.php', 'pilau_jump_links_shortcode_help_tab' );
	}

}


add_action( 'add_meta_boxes', 'pilau_remove_meta_boxes', 10 );
/**
 * Remove meta boxes
 *
 * @since	Pilau_Starter 0.1
 */
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
	remove_meta_box( 'postexcerpt', 'post', 'normal' );

	/* Post format */
	//remove_meta_box( 'formatdiv', 'post', 'normal' );

	/* Trackbacks */
	remove_meta_box( 'trackbacksdiv', 'post', 'normal' );

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
	global $pilau_image_sizes;
	$hint = null;

	// Custom messages about placement etc.
	/*
	switch ( get_post_type() ) {
		case 'service':
			$hint .= __( 'Set an image here and it will be placed below the heading, above the content.' );
			break;
		case 'page':
			switch ( get_page_template_slug() ) {
				case 'page_families-carers.php':
				case 'page_professionals.php':
					$hint = __( 'Set a featured image here and it will be used as the hero image.' );
					break;
				default:
					$hint = __( 'Set a featured image here and it will be placed below the heading, above the content.' );
					break;
			}
			break;
	}
	*/

	// See if there's a size hint from the image sizes array
	$details_for_size_hint = null;
	foreach ( $pilau_image_sizes as $size => $details ) {
		if ( ! empty( $details['featured'] ) ) {

			// Do the check for whether this post has featured details defined
			$featured_check = false;
			// This array is an ordered hierarchy - earlier check types override details from later check types
			foreach ( array( 'post_type', 'template' ) as $featured_check_type ) {
				if ( array_key_exists( $featured_check_type, $details['featured'] ) ) {
					switch ( $featured_check_type ) {
						case 'post_ids': {
							$featured_check = (
								! empty( $details['featured']['post_ids'] ) &&
								is_array( $details['featured']['post_ids'] ) &&
								in_array( $_REQUEST['post'], $details['featured']['post_ids'] )
							);
							break;
						}
						case 'post_type': {
							$featured_check = (
									! empty( $details['featured']['post_type'] ) &&
									is_array( $details['featured']['post_type'] ) &&
									in_array( get_post_type(), $details['featured']['post_type'] )
							);
							break;
						}
						case 'template': {
							$featured_check = (
									! empty( $details['featured']['template'] ) &&
									is_array( $details['featured']['template'] ) &&
									get_post_type() == 'page' &&
									(
											in_array( get_page_template_slug(), $details['featured']['template'] ) ||
											( get_page_template_slug() == '' && in_array( 'default', $details['featured']['template'] ) )
									)
							);
							break;
						}
					}
				}
				if ( $featured_check ) {
					break;
				}
			}

			// Pick up details if check passes
			if ( $featured_check ) {
				$details_for_size_hint = $details;
			}

		}
	}

	// Have we got some details for the size hint?
	if ( $details_for_size_hint ) {

		// Add size hint
		if ( $details_for_size_hint['height'] ) {

			// Height specified - work out proportion
			$proportion1 = 1;
			$proportion2 = 1;
			if ( $details_for_size_hint['width'] > $details_for_size_hint['height'] ) {
				$proportion1 = round( $details_for_size_hint['width'] / $details_for_size_hint['height'], 1 );
			} else if ( $details_for_size_hint['width'] < $details_for_size_hint['height'] ) {
				$proportion2 = round( $details_for_size_hint['height'] / $details_for_size_hint['width'], 1 );
			}
			$hint .= ' ' . sprintf( __( 'Make sure the image is a 72 ppi JPG, and %1$dpx wide by %2$dpx high. The image can be bigger, but try to keep similar proportions (%3$s : %4$s).' ), $details_for_size_hint['width'], $details_for_size_hint['height'], $proportion1, $proportion2 );

		} else {

			// Height not specified
			$hint .= ' ' . sprintf( __( 'Make sure the image is a 72 ppi JPG. It needn\'t be wider than %1$dpx. When resized, it\'ll keep its proportions.' ), $details_for_size_hint['width'] );

		}

		// Crop hint
		if ( $details_for_size_hint['crop'] ) {
			$hint .= ' ' . sprintf( __( 'The image can be bigger, but if the proportions are different, bits may be cropped off when it\'s resized.' ) );
		} else {
			// Not sure this is necessary
			//$hint .= ' ' . sprintf( __( 'The image can be bigger, but there may be gaps around it when it\'s resized.' ) );
		}

	}

	if ( $hint ) {
		$content = '<p><i>' . $hint . '</i></p>' . $content;
	}

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


add_filter( 'cac/column/value', 'pilau_cac_column_value', 10, 4 );
/**
 * Custom filtering of Admin Columns values
 *
 * @param	string	$value			Value
 * @param	int		$object_id		Post/User/Comment/Term ID
 * @param	object	$column			CPAC_Column object
 * @param	string	$storage_key	e.g.: post/page/wp-users/wp-media/wp-comments
 * @return	string	$value
 */
function pilau_cac_column_value( $value, $object_id, $column, $storage_key ) {

	// Check for custom field column
	if ( $column->is_type( 'column-meta' ) ) {

		// Check for entry_id field and capability
		if ( $column->get_option( 'field' ) == 'entry_id' && $value && PILAU_PLUGIN_EXISTS_GRAVITY_FORMS && current_user_can( 'gravityforms_edit_entries' ) ) {

			// Link to GF entry edit screen
			$entry = GFAPI::get_entry( $value );
			$form_id = $entry['form_id'];
			$value = '<a href="' . admin_url( 'admin.php?page=gf_entries&view=entry&id=' . $form_id . '&lid=' . $value ) . '" title="' . __( 'Click to view this entry' ) . '">' . $value . '</a>';

		}

	}

	return $value;
}


add_filter( 'tiny_mce_before_init', 'pilau_tinymce_buttons' );
/**
 * Customize default tiny MCE buttons
 *
 * @since	Pilau_Starter 0.1
 * @link	http://wordpress.stackexchange.com/questions/141534/how-to-customize-tinymce4-in-wp-3-9-the-old-way-for-styles-and-formats-doesnt
 */
function pilau_tinymce_buttons( $init_array ) {
	//echo '<pre>'; print_r( $init_array ); echo '</pre>'; exit;

	// Limit block-level formats
	$init_array['block_formats'] = 'Paragraph=p;Heading 2=h2;Heading 3=h3;Heading 4=h4';

	// Redo toolbars
	$init_array['toolbar1'] = 'formatselect,bullist,numlist,blockquote,hr,wp_more,bold,italic,link,unlink,pastetext,removeformat,charmap,spellchecker,wp_fullscreen,undo,redo,wp_help';
	$init_array['toolbar2'] = '';

	return $init_array;
}


add_action( 'admin_head', 'pilau_disable_content_editor' );
/**
 * Disable editor for certain pages
 *
 * @since	Pilau_Starter 0.1
 */
function pilau_disable_content_editor() {
	global $post;
	if ( isset( $post->ID ) && in_array( $post->ID, array( PILAU_PAGE_ID_HOME ) ) ) {
		remove_post_type_support( get_post_type( $post ), 'editor' );
	}
}


add_action( 'wp_dashboard_setup', 'pilau_disable_default_dashboard_widgets', 10 );
/**
 * Disable default dashboard widgets
 *
 * @since	Pilau_Starter 0.1
 * @link	http://codex.wordpress.org/Dashboard_Widgets_API
 */
function pilau_disable_default_dashboard_widgets() {

	//remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
	//remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
	//remove_meta_box( 'dashboard_primary', 'dashboard', 'side' ); /* WordPress blog */
	//remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' ); /* Other WordPress News */
	/**
	 * NOTE: Right now is removed largely because this theme can be set to disable
	 * core taxonomies (i.e. categories or tags) - and the core Right now widget
	 * doesn't test to see if the taxonomies exist before outputting their details.
	 * @todo Come up with a good replacement "overview" widget
	 */
	//remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
	//remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
	//remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'normal' );

}


add_filter( 'get_user_option_managenav-menuscolumnshidden', 'pilau_nav_menus_columns_hidden' );
/**
 * Menus hidden columns
 *
 * @since	Pilau_Starter 0.1
 */
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


/* Help tabs
 ******************************************************************************/


/**
 * Add help tab for jump links shortcode
 */
function pilau_jump_links_shortcode_help_tab() {

	$content = '<p>' . __( 'Jump links are links that go to a specific place in a page - either from within the page itself or from another page. Setting them up involves two steps:' ) . '</p>';
	$content .= '<ol>';
	$content .= '<li>' . sprintf( __( 'In the content editor, where you want to be able to jump to, type something like this: %s. Change the %s bit, and make sure it\'s unique on the page. Only use letters and dashes.' ), '<code>[anchor id="useful-info"]</code>', '"useful-info"' ) . '</li>';
	$content .= '<li>' . sprintf( __( 'Create a link to the anchor. If the link is on the same page as the anchor, the format will be: %s (making sure to include the hash mark, and making sure the text corresponds to the anchor id from before. If the link\'s on another page, simply add that format to the end of the target page\'s normal URL.' ), '<code>#useful-info</code>', '"useful-info"' ) . '</li>';
	$content .= '</ol>';

	$screen = get_current_screen();
	$screen->add_help_tab( array(
		'id'		=> 'jump_links_shortcode_help_tab',
		'title'		=> __( 'Jump links' ),
		'content'	=> $content,
	) );

}



/* Admin pages
 ******************************************************************************/


/**
 * Site settings admin page
 *
 * @since	Pilau_Starter 0.1
 */
function pilau_site_settings_admin_page() {
	$settings = get_option( 'pilau_site_settings' );

	// Output
	?>

	<div class="wrap">

		<h1><?php echo get_admin_page_title(); ?></h1>

		<?php if ( isset( $_GET['done'] ) ) { ?>
			<div class="updated"><p><strong><?php _e( 'Settings updated successfully.' ); ?></strong></p></div>
		<?php } ?>

		<form method="post" action="">

			<?php wp_nonce_field( 'pilau-site-settings', 'pilau_site_settings_admin_nonce' ); ?>

			<h3><?php _e( 'Contact details' ); ?></h3>
			<table class="form-table">
				<tbody>
				<tr valign="top">
					<th scope="row">
						<label for="pilau_contact_phone"><?php _e( 'Phone number' ); ?></label>
					</th>
					<td>
						<input type="text" name="contact_phone" id="pilau_contact_phone" value="<?php echo $settings['contact_phone']; ?>">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="pilau_contact_email"><?php _e( 'Email address' ); ?></label>
					</th>
					<td>
						<input type="text" name="contact_email" id="pilau_contact_email" value="<?php echo $settings['contact_email']; ?>">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="pilau_contact_postal_address"><?php _e( 'Postal address' ); ?></label>
					</th>
					<td>
						<textarea name="contact_postal_address" id="pilau_contact_postal_address" rows="6" cols="30"><?php echo esc_textarea( $settings['contact_postal_address'] ); ?></textarea>
					</td>
				</tr>
				</tbody>
			</table>

			<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>

		</form>

	</div>

<?php

}


add_action( 'admin_init', 'pilau_site_settings_admin_page_process' );
/**
 * Process site settings
 *
 * @since	Pilau_Starter 0.1
 */
function pilau_site_settings_admin_page_process() {

	// Submitted?
	if ( isset( $_POST['pilau_site_settings_admin_nonce'] ) && check_admin_referer( 'pilau-site-settings', 'pilau_site_settings_admin_nonce' ) ) {

		// Gather into array
		$settings = array();
		$settings['contact_phone'] = strip_tags( $_POST['contact_phone'] );
		$settings['contact_postal_address'] = strip_tags( $_POST['contact_postal_address'] );
		$settings['contact_email'] = sanitize_email( $_POST['contact_email'] );

		// Save as option
		update_option( 'pilau_site_settings', $settings );

		// Redirect
		wp_redirect( admin_url( 'options-general.php?page=pilau-site-settings&done=1' ) );

	}

}
