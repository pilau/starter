<?php

// Make sure the WP core list table class is present
if ( ! class_exists( 'WP_List_Table' ) )
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

/**
 * Pilau plugins list class
 *
 * Code inspiration:
 * @link	http://ottopress.com/2012/themeplugin-dependencies/
 * @link	http://wp.smashingmagazine.com/2011/11/03/native-admin-tables-wordpress/
 *
 * @package	Pilau_Starter
 * @since	0.1
 */
class Pilau_Plugins_Table extends WP_List_Table {

	/**
	 * Plugins data
	 *
	 * Keys are the path within the plugins folder when installed, 'e.g. developers-custom-fields/slt-custom-fields.php'.
	 * The first part is presumed to correspond to the slug on wordpress.org/extend/plugins.
	 *
	 * Statuses:
	 * 'canonical' means the default is to download and activate.
	 * 'recommended' means that the default is to download but not activate.
	 * 'useful' means the default is to just list.
	 * 'unconfirmed' means there may still be testing to determine the plugin's
	 * 		suitability for use with Pilau.
	 *
	 * @since	Pilau_Starter 0.1
	 * @var		array
	 * @access	public
	 */
	var $plugins_data = array(
		'control-live-changes/control-live-changes.php'				=> array(
			'name'			=> 'Control Live Changes',
			'description'	=> 'If this project is being versioned, use this plugin to prevent, for instance, plugins being updated on a production server when plugin updates should be done locally then pushed.',
			'status'		=> 'recommended'
		),
		'developers-custom-fields/slt-custom-fields.php'			=> array(
			'title'			=> 'Developer\s Custom Fields',
			'description'	=> 'API for managing custom fields for post types and users.',
			'status'		=> 'canonical'
		),
		'lock-pages/lock-pages.php'									=> array(
			'title'			=> 'Lock Pages',
			'description'	=> 'Helps preserve permalinks and stops key pages in the site structure from being messed up.',
			'status'		=> 'canonical'
		),
		'force-strong-passwords/force-strong-passwords.php'			=> array(
			'title'			=> 'Force Strong Passwords',
			'description'	=> 'Forces users to use a strong password when updating their profile.',
			'status'		=> 'canonical'
		),
		'bwp-minify/bwp-minify.php'									=> array(
			'title'			=> 'Better WordPress Minify',
			'description'	=> 'Minifies and concatenates CSS and JS.',
			'status'		=> 'canonical'
		),
		'quick-cache/quick-cache.php'								=> array(
			'title'			=> 'Quick Cache',
			'description'	=> 'Caches page output as flat HTML.',
			'status'		=> 'unconfirmed'
		),
		'members/members.php'										=> array(
			'title'			=> 'Members',
			'description'	=> 'Essential for controlling roles and capabilities.',
			'status'		=> 'canonical'
		),
		'seo-slugs/seo-slugs.php'									=> array(
			'title'			=> 'SEO Slugs',
			'description'	=> 'Strips common short words from permalinks.',
			'status'		=> 'canonical'
		),
		'simple-page-ordering/simple-page-ordering.php'				=> array(
			'title'			=> 'Simple Page Ordering',
			'description'	=> 'Drag-and-drop page ordering.',
			'status'		=> 'canonical'
		),
		'use-google-libraries/use-google-libraries.php'				=> array(
			'title'			=> 'Use Google Libraries',
			'description'	=> 'Loads scripts from Google\'s servers where possible.',
			'status'		=> 'unconfirmed'
		),
		'wordpress-seo/wp-seo.php'									=> array(
			'title'			=> 'WordPress SEO',
			'description'	=> 'Various SEO functions, plus XML sitemap and breadcrumbs.',
			'status'		=> 'unconfirmed'
		),
		'wp-db-backup/wp-db-backup.php'								=> array(
			'title'			=> 'WordPress Database Backup',
			'description'	=> 'Schedule daily backups to go to a specific email address. Also use this for downloading a backup just before updating the core.',
			'status'		=> 'canonical'
		),
		'limit-login-attempts/limit-login-attempts.php'				=> array(
			'title'			=> 'Limit Login Attempts',
			'description'	=> 'Security measure to prevent forced entry.',
			'status'		=> 'canonical'
		),
		'simple-events/slt-simple-events.php'						=> array(
			'title'			=> 'Simple Events',
			'description'	=> 'Essential for sites with events as a CPT.',
			'status'		=> 'useful'
		),
		'gravityforms/gravityforms.php'								=> array(
			'title'			=> 'Gravity Forms',
			'description'	=> 'Security measure to prevent forced entry.',
			'status'		=> 'recommended',
			'download'		=> false,
			'uri'			=> 'http://www.gravityforms.com/'
		),
		'dynamic-widgets/dynamic-widgets.php'						=> array(
			'title'			=> 'Dynamic Widgets',
			'description'	=> 'Fine-grained control over what widgets appear where.',
			'status'		=> 'useful'
		),
		'google-analytics-for-wordpress/googleanalytics.php'		=> array(
			'title'			=> 'Google Analytics for WordPress',
			'description'	=> 'Manages Google Analytics tracking code. Usually set it to exclude logged-in users, though some membership sites may want to include Subscribers or Contributors.',
			'status'		=> 'recommended'
		),
		'wp-htaccess-control/wp-htaccess-control.php'				=> array(
			'title'			=> 'WP Htaccess Control',
			'description'	=> 'Good for advanced customization of rewrites (not for actually changing .htaccess contents!).',
			'status'		=> 'useful'
		),
		'user-photo/user-photo.php'									=> array(
			'title'			=> 'User Photo',
			'description'	=> 'If you find a better plugin than this for user profile images, shout!',
			'status'		=> 'useful'
		),
		'simple-301-redirects/wp-simple-301-redirects.php'			=> array(
			'title'			=> 'Simple 301 Redirects',
			'description'	=> 'For when clients want to control redirects.',
			'status'		=> 'useful'
		),
		'codepress-admin-columns/codepress-admin-columns.php'		=> array(
			'title'			=> 'Codepress Admin Columns',
			'description'	=> 'Good GUI for customizing the columns in admin lists.',
			'status'		=> 'canonical'
		)
	);

	/**
	 * Installed plugins
	 *
	 * @since	Pilau_Starter 0.1
	 * @var		array
	 * @access	public
	 */
	var $wp_plugins;


	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct( array(
			'singular'	=> 'wp_list_pilau_plugin',
			'plural'	=> 'wp_list_pilau_plugins',
			'ajax'		=> false
		));
		$this->wp_plugins = get_plugins();
	}

	/**
	 * Define the columns that are going to be used in the table
	 *
	 * @return	array $columns
	 */
	function get_columns() {
		return $columns = array(
			'col_plugin_name'			=> __( 'Plugin' ),
			'col_plugin_description'	=> __( 'Description' ),
			'col_plugin_installed'		=> __( 'Installed?' ),
			'col_plugin_activated'		=> __( 'Activated?' )
		);
	}

	/**
	 * Prepare the table data
	 */
	function prepare_items() {
		global $_wp_column_headers;
		$screen = get_current_screen();
		$columns = $this->get_columns();
		$_wp_column_headers[ $screen->id ] = $columns;
		$this->items = $this->plugins_data;
	}

	/**
	 * Display the rows of records in the table
	 *
	 * @return	string
	 */
	function display_rows() {

		// Get the columns registered in the get_columns and get_sortable_columns methods
		list( $columns, $hidden ) = $this->get_column_info();

		// Loop for each record
		if ( ! empty( $this->items ) ) { foreach ( $this->items as $plugin_slug => $plugin_data ) {
			$sanitized_slug = str_replace( array( '/', '.' ), array( '-', '-' ), $plugin_slug );
			$installed_plugins = array_keys( $this->wp_plugins );

			echo '<tr id="record_' . $sanitized_slug . '">';

			foreach ( $columns as $column_name => $column_display_name ) {

				// Style attributes for each col
				$classes = array( $column_name, "column-$column_name" );

				// Display the cell
				switch ( $column_name ) {

					case "col_plugin_name":
						echo '<td ' . implode( " ", $classes ) . '><strong>' . $plugin_data["title"] .'</strong></td>';
						break;

					case "col_plugin_description":
						echo '<td ' . implode( " ", $classes ) . '>' . $plugin_data["description"] . '</a></td>';
						break;

					case "col_plugin_installed":
						echo '<td ' . implode( " ", $classes ) . '>';
						if ( in_array( $plugin_slug, $installed_plugins ) ) {
							// Already installed
							echo '<img src="/wp-admin/images/yes.png" alt="' . __( 'Already installed' ) . '" />';

						} else {
							// Not installed, checkbox default determined by status
							echo '<input type="checkbox" name="installed_' . $sanitized_slug . '" id="installed_' . $sanitized_slug . '"';
							if ( $plugin_data['status'] == 'canonical' || $plugin_data['status'] == 'recommended' )
								echo ' checked="checked"';
							echo ' />';
						}
						echo '</td>';
						break;

					case "col_plugin_activated":
						echo '<td ' . implode( " ", $classes ) . '>';
						if ( in_array( $plugin_slug, $installed_plugins ) && is_plugin_active( $plugin_slug ) ) {
							// Already activated
							echo '<img src="/wp-admin/images/yes.png" alt="' . __( 'Already activated' ) . '" />';

						} else {
							// Not activated, checkbox default determined by status
							echo '<input type="checkbox" name="activated_' . $sanitized_slug . '" id="activated_' . $sanitized_slug . '"';
							if ( $plugin_data['status'] == 'canonical' )
								echo ' checked="checked"';
							echo ' />';
						}
						echo '</td>';
						break;

				}

			}

			// Close the line
			echo '</tr>';

		}}

	}

}
