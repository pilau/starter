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
<!--[if lt IE 7]><html class="no-js lt-ie10 lt-ie9 lt-ie8 lt-ie7 ie6" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]><html class="no-js lt-ie10 lt-ie9 lt-ie8 ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]><html class="no-js lt-ie10 lt-ie9 ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9]><html class="no-js lt-ie10 ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 9]><!--><html class="no-js" <?php language_attributes(); ?>><!--<![endif]-->
<head>

	<meta charset="<?php bloginfo( 'charset' ); ?>">

	<title><?php wp_title(); ?></title>

	<?php /*
	<!-- Mobile meta -->
	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	*/ ?>

	<?php /*
 	<!-- Site icons -->
 	<!-- Use something like http://realfavicongenerator.net/ -->
	*/ ?>

	<?php

	/*
	 * For any HTML header code that requires PHP processing, hook to wp_head
	 * Check inc/header.php
	 */
	wp_head();

	/*
	 * Picturefill
	 * @link	http://scottjehl.github.io/picturefill/
	 *
	 * Not currently enqueued because I want to use async, and I'm not yet sure about how
	 * async will play with enqueuing and concatenation...
	 * @link
	 *
	<script src="<?php echo get_stylesheet_directory_uri() . '/js/picturefill.js?ver=2.1.0'; ?>" async="async"></script>
	 */
	?>
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

<?php /* Upgrade notice for IE 6 and below */ ?>
<!--[if lt IE 7]><p class="upgrade-browser">Please note that this site does not support Internet Explorer 6 and below. Neither does Microsoft! <a href="http://browsehappy.com/">Please upgrade to a modern browser</a> if possible, or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this and other sites in your current browser.</p><![endif]-->

<?php
/* Cookie notice */
if ( PILAU_USE_COOKIE_NOTICE && ! isset( $_COOKIE['pilau_cookie_notice'] ) ) { ?>
	<div id="cookie-notice">
		<div class="wrapper">
			<div class="text">
				<p><strong>This site uses cookies.</strong></p>
				<p>By continuing to browse the site you are agreeing to our use of cookies. To find out more <a href="/privacy-policy/">read our privacy policy</a>.</p>
			</div>
			<p class="close"><a href="?close-cookie-notice=1">Close</a></p>
		</div>
	</div>
<?php } ?>

<?php /* Wrapper class used for layouts that need sections to be wider than the rest of the layout (i.e. with multiple wrappers */ ?>
<div class="wrapper">

<?php /* Minimal header - adapt as necessary */ ?>
<header id="header" role="banner">

	<h1 class="site-title"><a href="<?php echo home_url( '/' ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
	<p class="site-description"><?php bloginfo( 'description' ); ?></p>

	<nav role="navigation" id="nav-main">
		<h1 class="assistive-text"><?php _e( 'Menu' ); ?></h1>
		<p class="assistive-text skip-link"><a href="#content"><?php _e( 'Skip to content' ); ?></a></p>
		<ul>
			<?php
			wp_list_pages( array(
				'depth'			=> 1,
				'sort_column'	=> 'menu_order',
				'child_of'		=> 0,
				'exclude'		=> get_option( 'page_on_front' ),
				'title_li'		=> ''
			)); ?>
		</ul>
	</nav>

</header><!-- #header -->

<?php

/* Breadcrumbs - NEEDS ENABLING AND CONFIGURING ON PLUGIN SETTINGS PAGE, SEO > Internal links
if ( ! is_front_page() && function_exists( 'yoast_breadcrumb' ) ) {
	echo '<p id="breadcrumbs">';
	yoast_breadcrumb();
	echo '</p>';
}
 */

