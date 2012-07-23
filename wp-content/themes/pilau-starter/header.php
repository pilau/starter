<?php

/**
 * Theme header
 *
 * @package	Pilau_Starter
 * @since	0.1
 */

/*
 * Conditional HTML classes for IE / JS targetting
 * @link http://paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/
 */
?><!DOCTYPE html>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7 ie6" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8 ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9 ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--><html class="no-js" <?php language_attributes(); ?>><!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<title><?php wp_title(); ?></title>
	<?php

	/*
	 * For any HTML header code that requires complex PHP processing, hook to wp_head
	 * Check inc/header.php
	 */
	wp_head();

	/*
	* Modernizr custom build - must be in the header
	*
	* For features in this build, and for customizing it further, check the build URL in modernizr.js
	* Remember to update the version appended here if you upgrade!
	*/
	?>
	<script src="<?php echo get_template_directory_uri() . '/js/modernizr.js?ver=2.6.1'; ?>"></script>
</head>
<body <?php body_class(); ?> role="document">

<?php /* Upgrade notice for IE 6 and below */ ?>
<!--[if lt IE 7]><p class="upgrade-browser">Please note that this site does not support Internet Explorer 6 and below. Neither does Microsoft! <a href="http://browsehappy.com/">Please upgrade to a modern browser</a> if possible, or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this and other sites in your current browser.</p><![endif]-->

<?php /* Wrapper class used for layouts that need sections to be wider than the rest of the layout (i.e. with multiple wrappers */ ?>
<div class="wrapper">

<?php /* Minimal header - adapt as necessary */ ?>
<header id="header" role="banner">

	<hgroup>
		<h1 class="site-title"><a href="<?php echo home_url( '/' ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
		<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
	</hgroup>

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
