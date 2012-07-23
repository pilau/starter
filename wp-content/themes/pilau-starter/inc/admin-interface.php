<?php

/**
 * Admin interface customization
 *
 * @package	Pilau_Starter
 * @since	0.1
 * @todo	Selectively remove post category / link category options on options-writing.php
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
		add_plugins_page( __( 'Pilau plugins' ), __( 'Pilau plugins' ), 'update_core', 'pilau-plugins', 'pilau_plugins_page' );

	// Theme settings script
	if ( PILAU_USE_SETTINGS_SCRIPT )
		add_options_page( __( 'Pilau settings initialization and reset script' ), __( 'Pilau settings script' ), 'update_core', 'pilau-settings-script', 'pilau_settings_script_page' );

}


/**
 * Theme plugins page
 *
 * @since	Pilau_Starter 0.1
 */
function pilau_plugins_page() {
	global $pilau_plugins_infos;

	// Output
	?>

	<div class="wrap">

		<div id="icon-plugins" class="icon32"><br></div>
		<h2><?php _e( 'Pilau plugin management' ); ?></h2>

		<form method="post" action="">

			<?php wp_nonce_field( 'pilau-plugins', 'pilau_plugins_nonce' ); ?>

			<p style="text-align:right;"><input class="button-primary" type="submit" name="submit" value="<?php _e( 'Update' ); ?>"></p>

			<table class="wp-list-table widefat plugins pilau-plugins" cellspacing="0">
				<?php foreach ( array( 'head', 'foot' ) as $row ) { ?>
					<t<?php echo $row; ?>>
						<tr>
							<th scope="col" class="manage-column column-name"><?php _e( 'Plugin' ); ?></th>
							<th scope="col" class="manage-column column-description"><?php _e( 'Description' ); ?></th>
							<th scope="col" class="manage-column column-cb check-column"><?php _e( 'Installed?' ); ?>&nbsp;&nbsp;</th>
							<th scope="col" class="manage-column column-cb check-column"><?php _e( 'Activated?' ); ?></th>
						</tr>
					</t<?php echo $row; ?>>
				<?php } ?>
				<tbody id="the-list">
					<?php

					// Loop for each record
					if ( ! empty( $pilau_plugins_infos ) ) {

						foreach ( $pilau_plugins_infos as $plugin_key => $plugin_data ) {

							$plugin_installed = pilau_is_plugin_installed( $plugin_data['path'] );
							$plugin_activated = is_plugin_active( $plugin_data['path'] );
							$row_class = 'inactive';
							if ( ! $plugin_installed ) {
								$row_class = 'not-installed';
							} else if ( $plugin_activated ) {
								$row_class = 'active';
							}

							?>

							<tr class="<?php echo $row_class; ?>">
								<td class="plugin-title">
									<strong><?php echo $plugin_data["title"];  ?></strong>
								</td>
								<td class="column-description desc">
									<div class="plugin-description"><p><?php echo $plugin_data["description"]; ?></p></div>
									<p class="plugin-meta"><?php _e( 'Status' ); ?>: <?php echo $plugin_data["status"]; ?></p>
								</td>
								<td class="check-column" style="text-align:center;">
									<?php
									if ( $plugin_installed ) {
										// Already installed
										echo '<img src="/wp-admin/images/yes.png" alt="' . __( 'Already installed' ) . '" />';
									} else {
										// Not installed, checkbox default determined by status
										echo '<input type="checkbox" name="installed_' . $plugin_key . '" id="installed_' . $plugin_key . '"';
										if ( $plugin_data['status'] == 'canonical' || $plugin_data['status'] == 'recommended' )
											echo ' checked="checked"';
										echo ' />';
									}
									?>
								</td>
								<td class="check-column" style="text-align:center;">
									<?php
									if ( $plugin_installed && $plugin_activated ) {
										// Already activated
										echo '<img src="/wp-admin/images/yes.png" alt="' . __( 'Already activated' ) . '" />';
									} else {
										// Not activated, checkbox default determined by status
										echo '<input type="checkbox" name="activated_' . $plugin_key . '" id="activated_' . $plugin_key . '"';
										if ( $plugin_data['status'] == 'canonical' )
											echo ' checked="checked"';
										echo ' />';
									}
									?>
								</td>
							</tr>

							<?php

						}

					} else {

						// No plugins
						?>
						<tr class="no-items"><td class="colspanchange" colspan="4"><?php _e( 'No theme plugins data supplied.' ); ?></td></tr>
						<?php

					}

					?>

				</tbody>

			</table>

			<p style="text-align:right;"><input class="button-primary" type="submit" name="submit" value="<?php _e( 'Update' ); ?>"></p>

		</form>

	</div>

	<?php

}

/**
 * Process plugins page submissions
 *
 * @since	Pilau_Starter 0.1
 * @todo	Installation and activation!
 */
add_action( 'admin_init', 'pilau_plugins_page_process' );
function pilau_plugins_page_process() {
	global $pilau_plugins_infos;

	// Submitted?
	if ( isset( $_POST['pilau_plugins_nonce'] ) && check_admin_referer( 'pilau-plugins', 'pilau_plugins_nonce' ) ) {

		// Loop through post data
		foreach ( $_POST as $field => $value ) {

			// Installed / activated field?
			if ( strlen( $field ) > 10 && strpos( $field, '_' ) ) {
				$field_parts = explode( '_', $field );

				if ( $field_parts[0] == 'installed' ) {
					// Need to install plugin????

				} else if ( $field_parts[0] == 'activated' ) {
					// Need to activate plugin????

				}

			}

		}

		// Redirect
		wp_redirect( admin_url( 'plugins.php?page=pilau-plugins&done=1' ) );

	}

}


/**
 * Settings initialization / reset page
 *
 * @since	Pilau_Starter 0.1
 */
function pilau_settings_script_page() {

	// Output
	?>

	<div class="wrap">

		<div id="icon-options-general" class="icon32"><br></div>
		<h2><?php _e( 'Pilau settings initialization and reset script' ); ?></h2>

		<div class="error">
			<p><?php _e( 'Running this script will initialize / reset core and / or plugin settings to Pilau defaults. Use with care, and please backup your database before doing a reset!' ); ?></p>
		</div>

		<form method="post" action="">

			<?php wp_nonce_field( 'pilau-settings-script', 'pilau_settings_script_nonce' ); ?>

			<p><input class="button-primary" type="submit" name="submit" value="<?php _e( 'Run script' ); ?>"></p>

			<table class="wp-list-table widefat plugins pilau-settings-script" cellspacing="0">
				<?php foreach ( array( 'head', 'foot' ) as $row ) { ?>
				<t<?php echo $row; ?>>
					<tr>
						<th scope="col" class="manage-column column-cb check-column"><input type="checkbox"></th>
						<th scope="col" class="manage-column column-name"><?php _e( 'Settings' ); ?></th>
					</tr>
				</t<?php echo $row; ?>>
				<?php } ?>
				<tbody id="the-list">
					<tr>
						<th scope="row" class="check-column"><input type="checkbox" name="checked[]" value="core" id="checkbox_core"><label class="screen-reader-text" for="checkbox_core"><?php _e( 'Select core settings' ); ?></label></th>
						<td class="column-description desc"><?php _e( 'WordPress core settings' ); ?></td>
					</tr>
					<tr>
						<th scope="row" class="check-column"><input type="checkbox" name="checked[]" value="home-news" id="checkbox_home-news"><label class="screen-reader-text" for="checkbox_core"><?php _e( 'Select home + news page creation' ); ?></label></th>
						<td class="column-description desc"><?php _e( 'Create Home + News page' ); ?></td>
					</tr>
						<?php



					?>
				</tbody>

			</table>

			<p><input class="button-primary" type="submit" name="submit" value="<?php _e( 'Run script' ); ?>"></p>

		</form>

	</div>

	<?php

}


/**
 * Process settings script page submissions
 *
 * @since	Pilau_Starter 0.1
 */
add_action( 'admin_init', 'pilau_settings_script_page_process' );
function pilau_settings_script_page_process() {
	global $pilau_wp_plugins;

	// Submitted?
	if ( isset( $_POST['pilau_settings_script_nonce'] ) && check_admin_referer( 'pilau-settings-script', 'pilau_settings_script_nonce' ) ) {
		$checked = array_values( $_POST['checked'] );

		if ( in_array( 'core', $checked ) ) {

			// Core settings
			update_option( 'date_format', 'F jS Y' );
			update_option( 'default_post_edit_rows', '30' );
			update_option( 'default_post_edit_rows', '30' );
			update_option( 'default_post_edit_rows', '30' );
			update_option( 'default_post_edit_rows', '30' );

		}


		// Redirect
		wp_redirect( admin_url( 'options-general.php?page=pilau-settings-script&done=1' ) );

	}

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

/** Page columns */
add_filter( 'manage_edit-page_columns', 'pilau_page_columns', 10000 );
function pilau_page_columns( $cols ) {
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