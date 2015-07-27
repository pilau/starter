<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * @package WordPress
 */

/** Server-dependent settings */
if ( file_exists( dirname( __FILE__ ) . '/wp-config-local.php' ) ) {

	/** Signal we're in the local dev environment */
	define( 'WP_LOCAL_DEV', true );

	/**
	 * Local configuration file
	 * @link http://markjaquith.wordpress.com/2011/06/24/wordpress-local-dev-tips/
	 */
	include( dirname( __FILE__ ) . '/wp-config-local.php' );

} else {

	/** Signal we're NOT in the local dev environment */
	define( 'WP_LOCAL_DEV', false );

	switch ( $_SERVER['HTTP_HOST'] ) {

		case "[[staging-domain]]":
			/**
			 * Staging server settings
			 */
			define( 'DB_NAME', '[[staging-db-name]]' );
			define( 'DB_USER', '[[staging-db-user]]' );
			define( 'DB_PASSWORD', '[[staging-db-password]]' );
			define( 'DB_HOST', 'localhost' );
			define( 'DB_CHARSET', 'utf8' );
			define( 'DB_COLLATE', '' );
			define( 'WP_DEBUG', isset( $_GET['debug'] ) ); // Used to avoid annoying notices; append '?debug=1' to quickly see debug infos
			define( 'WP_DEBUG_DISPLAY', true );
			define( 'WP_DEBUG_LOG', false );
			define( 'SCRIPT_DEBUG', false );
			define( 'SAVEQUERIES', false );
			// Does your server need an FTP connection for one-click updates?
			define( 'FTP_HOST', '[[staging-ftp-host]]' );
			define( 'FTP_USER', '[[staging-ftp-user]]' );
			define( 'FTP_PASS', '[[staging-ftp-password]]' );
			define( 'WP_POST_REVISIONS', 3 );
			define( 'AUTOSAVE_INTERVAL', 60 );
			//define( 'EMPTY_TRASH_DAYS', 30 ); // Set to 0 to disable trash
			//define( 'WP_MEMORY_LIMIT', '64M' );
			//define( 'FORCE_SSL_ADMIN', true );
			$table_prefix  = '[[db-prefix]]';
			/*
			 * Add constants for IDs of important locked pages here when they're different
			 * between environments (i.e. created after dev has been uploaded to staging etc.).
			 * Page IDs that are the same across environments (i.e. created before dev has
			 * been uploaded) are defined in functions.php
			 */
			//define( 'PILAU_PAGE_ID_EXAMPLE', 23 );


			/** Flag the remote environment */
			define( 'PILAU_REMOTE_ENV', 'staging' );

			break;

		default:
			/**
			 * Production server settings
			 */
			define( 'DB_NAME', '[[production-db-name]]' );
			define( 'DB_USER', '[[production-db-user]]' );
			define( 'DB_PASSWORD', '[[production-db-password]]' );
			define( 'DB_HOST', 'localhost' );
			define( 'DB_CHARSET', 'utf8' );
			define( 'DB_COLLATE', '' );
			define( 'WP_DEBUG', false );
			define( 'WP_DEBUG_DISPLAY', false );
			define( 'WP_DEBUG_LOG', false );
			define( 'SCRIPT_DEBUG', false );
			define( 'SAVEQUERIES', false );
			// Does your server need an FTP connection for one-click updates?
			define( 'FTP_HOST', '[[production-ftp-host]]' );
			define( 'FTP_USER', '[[production-ftp-user]]' );
			define( 'FTP_PASS', '[[production-ftp-password]]' );
			define( 'WP_POST_REVISIONS', 3 );
			define( 'AUTOSAVE_INTERVAL', 60 );
			//define( 'EMPTY_TRASH_DAYS', 30 ); // Set to 0 to disable trash
			//define( 'WP_MEMORY_LIMIT', '64M' );
			//define( 'FORCE_SSL_ADMIN', true );
			$table_prefix  = '[[db-prefix]]';
			/*
			 * Add constants for IDs of important locked pages here when they're different
			 * between environments (i.e. created after dev has been ported to staging etc.).
			 * Page IDs that are the same across environments (i.e. created before dev has
			 * been uploaded) are defined in functions.php
			 */
			//define( 'PILAU_PAGE_ID_EXAMPLE', 23 );

			/** Flag the remote environment */
			define( 'PILAU_REMOTE_ENV', 'production' );

			/** WP Super Cache */
			define( 'WP_CACHE', true );
			define( 'WPCACHEHOME', __DIR__ . '/wp-content/plugins/wp-super-cache/' );

			break;

	}
}

/** Disable plugin and theme editing */
define( 'DISALLOW_FILE_EDIT', true );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
//[[config-keys-salts]]
/**#@-*/

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define( 'WPLANG', 'en_GB' );

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
