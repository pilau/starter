<?php

/**
 * Pilau plugins infos
 *
 * Sets a variable storing information on Pilau canonical, recommended, etc. plugins.
 *
 * Format as follows:
 *
 * <code>
 * array(
 * 		'path'			=> 'plugin-dir/plugin-file.php',
 * 		'title'			=> 'Plugin title',
 * 		'description'	=> 'Plugin description',
 * 		'status'		=> 'canonical|recommended|useful|unconfirmed',
 * 		'uri'			=> 'Download URI, if not at wordpress.org/extend/plugins/[plugin-dir]/.', // optional
 * 		'download'		=> true|false, // optional - whether plugin can be downloaded or not
 * )
 * </code>
 *
 * Statuses:
 * 'canonical' means the default is to download and activate.
 * 'recommended' means that the default is to download but not activate.
 * 'useful' means the default is to just list.
 * 'unconfirmed' means there may still be testing to determine the plugin's
 * 		suitability for use with Pilau.
 *
 * @package	Pilau_Starter
 * @since	0.1
 * @todo	Redo as XML config file?
 */


/**
 * Pilau plugin infos
 *
 * @since	Pilau_Starter 0.1
 * @global	array
 */
global $pilau_plugins_infos;
$pilau_plugins_infos = array(
	array(
		'path'			=> 'control-live-changes/control-live-changes.php',
		'title'			=> 'Control Live Changes',
		'description'	=> 'If this project is being versioned, use this plugin to prevent, for instance, plugins being updated on a production server when plugin updates should be done locally then pushed.',
		'status'		=> 'recommended'
	),
	array(
		'path'			=> 'developers-custom-fields/slt-custom-fields.php',
		'title'			=> 'Developer\s Custom Fields',
		'description'	=> 'API for managing custom fields for post types and users.',
		'status'		=> 'canonical'
	),
	array(
		'path'			=> 'lock-pages/lock-pages.php',
		'title'			=> 'Lock Pages',
		'description'	=> 'Helps preserve permalinks and stops key pages in the site structure from being messed up.',
		'status'		=> 'canonical'
	),
	array(
		'path'			=> 'force-strong-passwords/force-strong-passwords.php',
		'title'			=> 'Force Strong Passwords',
		'description'	=> 'Forces users to use a strong password when updating their profile.',
		'status'		=> 'canonical'
	),
	array(
		'path'			=> 'bwp-minify/bwp-minify.php',
		'title'			=> 'Better WordPress Minify',
		'description'	=> 'Minifies and concatenates CSS and JS.',
		'status'		=> 'canonical'
	),
	array(
		'path'			=> 'quick-cache/quick-cache.php',
		'title'			=> 'Quick Cache',
		'description'	=> 'Caches page output as flat HTML.',
		'status'		=> 'unconfirmed'
	),
	array(
		'path'			=> 'members/members.php',
		'title'			=> 'Members',
		'description'	=> 'Essential for controlling roles and capabilities.',
		'status'		=> 'canonical'
	),
	array(
		'path'			=> 'seo-slugs/seo-slugs.php',
		'title'			=> 'SEO Slugs',
		'description'	=> 'Strips common short words from permalinks.',
		'status'		=> 'canonical'
	),
	array(
		'path'			=> 'simple-page-ordering/simple-page-ordering.php',
		'title'			=> 'Simple Page Ordering',
		'description'	=> 'Drag-and-drop page ordering.',
		'status'		=> 'canonical'
	),
	array(
		'path'			=> 'use-google-libraries/use-google-libraries.php',
		'title'			=> 'Use Google Libraries',
		'description'	=> 'Loads scripts from Google\'s servers where possible.',
		'status'		=> 'unconfirmed'
	),
	array(
		'path'			=> 'wordpress-seo/wp-seo.php',
		'title'			=> 'WordPress SEO',
		'description'	=> 'Various SEO functions, plus XML sitemap and breadcrumbs.',
		'status'		=> 'unconfirmed'
	),
	array(
		'path'			=> 'wp-db-backup/wp-db-backup.php',
		'title'			=> 'WordPress Database Backup',
		'description'	=> 'Schedule daily backups to go to a specific email address. Also use this for downloading a backup just before updating the core.',
		'status'		=> 'canonical'
	),
	array(
		'path'			=> 'limit-login-attempts/limit-login-attempts.php',
		'title'			=> 'Limit Login Attempts',
		'description'	=> 'Security measure to prevent forced entry.',
		'status'		=> 'canonical'
	),
	array(
		'path'			=> 'simple-events/slt-simple-events.php',
		'title'			=> 'Simple Events',
		'description'	=> 'Essential for sites with events as a CPT.',
		'status'		=> 'useful'
	),
	array(
		'path'			=> 'gravityforms/gravityforms.php',
		'title'			=> 'Gravity Forms',
		'description'	=> 'Security measure to prevent forced entry.',
		'status'		=> 'recommended',
		'uri'			=> 'http://www.gravityforms.com/'
	),
	array(
		'path'			=> 'dynamic-widgets/dynamic-widgets.php',
		'title'			=> 'Dynamic Widgets',
		'description'	=> 'Fine-grained control over what widgets appear where.',
		'status'		=> 'useful'
	),
	array(
		'path'			=> 'google-analytics-for-wordpress/googleanalytics.php',
		'title'			=> 'Google Analytics for WordPress',
		'description'	=> 'Manages Google Analytics tracking code. Usually set it to exclude logged-in users, though some membership sites may want to include Subscribers or Contributors.',
		'status'		=> 'recommended'
	),
	array(
		'path'			=> 'wp-htaccess-control/wp-htaccess-control.php',
		'title'			=> 'WP Htaccess Control',
		'description'	=> 'Good for advanced customization of rewrites (not for actually changing .htaccess contents!).',
		'status'		=> 'useful'
	),
	array(
		'path'			=> 'user-photo/user-photo.php',
		'title'			=> 'User Photo',
		'description'	=> 'If you find a better plugin than this for user profile images, shout!',
		'status'		=> 'useful'
	),
	array(
		'path'			=> 'simple-301-redirects/wp-simple-301-redirects.php',
		'title'			=> 'Simple 301 Redirects',
		'description'	=> 'For when clients want to control redirects.',
		'status'		=> 'useful'
	),
	array(
		'path'			=> 'codepress-admin-columns/codepress-admin-columns.php',
		'title'			=> 'Codepress Admin Columns',
		'description'	=> 'Good GUI for customizing the columns in admin lists.',
		'status'		=> 'canonical'
	)
);

// Sort it out!
function pilau_compare_status( $a, $b ) {
	return strnatcmp( $a['status'], $b['status'] );
}
usort( $pilau_plugins_infos, 'pilau_compare_status' );