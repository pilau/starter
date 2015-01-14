<?php

/**
 * Configuration (deferred from Pilau Init)
 *
 * @package	[[theme-phpdoc-name]]
 * @since	0.2
 */


add_action( 'activated_plugin', 'pilau_activated_plugin' );
/**
 * Detect plugins being activated for the first time, apply config options if necessary
 *
 * @since	0.2
 * @param	string	$plugin
 * @return	void
 */
function pilau_activated_plugin( $plugin ) {

	// Not worth proceeding if there's no stored config options...
	if ( $plugins_config = get_option( 'pi_plugins_config' ) ) {

		// Slug is file or directory name
		$plugin_parts = explode( '/', $plugin );
		$plugin_slug = null;
		if ( count( $plugin_parts ) == 1 ) {
			$plugin_slug = pathinfo( $plugin, PATHINFO_FILENAME );
		} else {
			$plugin_slug = $plugin_parts[0];
		}

		// Is this the first time the plugin's been activated?
		if ( ! isset( $plugins_config['activated_once'][ $plugin_slug ] ) ) {

			// Check according to plugin slug
			switch( $plugin_slug ) {

				// Members
				case 'members': {

					// Create Super Editor role?
					if ( ! empty( $plugins_config['members-super-editor'] ) && $plugins_config['members-super-editor'] ) {
						add_role(
							'super_editor',
							__( 'Super Editor' ),
							array(
								'activate_plugins' => false,
								'add_users' => true,
								'create_roles' => false,
								'create_users' => true,
								'delete_others_pages' => true,
								'delete_others_posts' => true,
								'delete_pages' => true,
								'delete_plugins' => false,
								'delete_posts' => true,
								'delete_private_pages' => true,
								'delete_private_posts' => true,
								'delete_published_pages' => true,
								'delete_published_posts' => true,
								'delete_roles' => false,
								'delete_themes' => false,
								'delete_users' => true,
								'edit_dashboard' => true,
								'edit_files' => false,
								'edit_others_pages' => true,
								'edit_others_posts' => true,
								'edit_pages' => true,
								'edit_plugins' => false,
								'edit_posts' => true,
								'edit_private_pages' => true,
								'edit_private_posts' => true,
								'edit_published_pages' => true,
								'edit_published_posts' => true,
								'edit_roles' => false,
								'edit_theme_options' => true,
								'edit_themes' => false,
								'edit_users' => true,
								'export' => true,
								'import' => true,
								'install_plugins' => false,
								'install_themes' => false,
								'list_roles' => false,
								'list_users' => true,
								'manage_categories' => true,
								'manage_links' => true,
								'manage_options' => true,
								'moderate_comments' => true,
								'promote_users' => true,
								'publish_pages' => true,
								'publish_posts' => true,
								'read' => true,
								'read_private_pages' => true,
								'read_private_posts' => true,
								'remove_users' => true,
								'restrict_content' => true,
								'switch_themes' => false,
								'unfiltered_html' => true,
								'unfiltered_upload' => true,
								'update_core' => false,
								'update_plugins' => false,
								'update_themes' => false,
								'upload_files' => true,
							)
						);
					}

					break;
				}

			}

			// Signal that this plugin has been activated once
			if ( ! isset( $plugins_config['activated_once'] ) ) {
				$plugins_config['activated_once'] = array();
			}
			$plugins_config['activated_once'][ $plugin_slug ] = true;
			update_option( 'pi_plugins_config', $plugins_config );

		}

	}

}