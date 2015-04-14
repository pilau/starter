<?php

/**
 * Theme header
 *
 * @package	Pilau_Starter
 * @since	0.1
 */

/*
 * Conditional HTML classes for IE / JS targetting
 * @link http://paulirish.com/2008/conditional-stylesheets-vs-styles-hacks-answer-neither/
 */
?><!DOCTYPE html>
<!--[if IE 8]><html class="no-js lt-ie11 lt-ie10 lt-ie9 ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9]><html class="no-js lt-ie11 lt-ie10 ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 9]><!--><html class="no-js" <?php language_attributes(); ?>><!--<![endif]-->
<head>

	<meta charset="<?php bloginfo( 'charset' ); ?>">

	<title><?php wp_title(); ?></title>

	<!-- Mobile meta -->
	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<?php /*
 	<!-- Site icons -->
 	<!-- Use something like http://realfavicongenerator.net/ -->
	*/ ?>

	<?php /* Fonts */ ?>
	<link href='http://fonts.googleapis.com/css?family=Droid+Sans:400,700' rel='stylesheet' type='text/css'>

	<?php
	/*
	 * livereload
	 * @link	https://github.com/gruntjs/grunt-contrib-watch/blob/master/docs/watch-examples.md#enabling-live-reload-in-your-html
	 */
	if ( WP_LOCAL_DEV ) { ?>
		<script src="//127.0.0.1:35729/livereload.js"></script>
	<?php } ?>

	<?php

	/*
	 * For any HTML header code that requires PHP processing, hook to wp_head
	 * Check inc/header.php
	 */
	wp_head();

	/*
	 * Respond.js for IE 8 and below
	 */
	?>
	<!--[if lt IE 9]><script src="<?php echo get_stylesheet_directory_uri() . '/js/respond.min.js?ver=1.4.2'; ?>"></script><![endif]-->

	<?php

	/*
	* Modernizr custom build - must be in the header
	*
	* For features in this build, and for customizing it further, check the build URL in modernizr.js
	* Remember to update the version appended here if you upgrade!
	*/
	?>
	<script src="<?php echo get_stylesheet_directory_uri() . '/js/modernizr.js?ver=2.8.3'; ?>"></script>

</head>
<body <?php body_class(); ?> role="document">


<p class="assistive-text skip-link"><a href="#content"><?php _e( 'Skip to main content' ); ?></a></p>


<?php /* Upgrade notice for IE 7 and below */ ?>
<!--[if lt IE 8]><p class="upgrade-browser">Please note that this site does not support Internet Explorer 7 and below. Neither does Microsoft! <a href="http://browsehappy.com/">Please upgrade to a modern browser</a> if possible, or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this and other sites in your current browser.</p><![endif]-->


<?php
/* Cookie notice */
if ( PILAU_USE_COOKIE_NOTICE && ! isset( $_COOKIE['pilau_cookie_notice'] ) ) { ?>
	<div id="cookie-notice">
		<div class="wrap">
			<div class="text">
				<p><strong>This site uses cookies.</strong></p>
				<p>By continuing to browse the site you are agreeing to our use of cookies. To find out more <a href="/privacy/">read our privacy policy</a>.</p>
			</div>
			<p class="close"><a href="?close-cookie-notice=1">Close</a></p>
		</div>
	</div>
<?php } ?>


<header class="header-main" role="banner">

	<div class="header-branding">
		<div class="wrap">
			<h1 class="site-title img-rep"><a href="<?php echo home_url( '/' ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<p class="site-description"><?php bloginfo( 'description' ); ?></p>
		</div>
	</div>

	<nav role="navigation">
		<div class="wrap">
			<ul class="nav nav-header">
				<?php
				echo pilau_menu_without_containers( 'nav_header' );
				?>
			</ul>

		</div>
	</nav>

</header>


<?php

/* Breadcrumbs - NEEDS ENABLING AND CONFIGURING ON PLUGIN SETTINGS PAGE, SEO > Internal links
if ( ! is_front_page() && PILAU_PLUGIN_EXISTS_WPSEO ) {
	echo '<p class="breadcrumbs">';
	yoast_breadcrumb();
	echo '</p>';
}
 */

