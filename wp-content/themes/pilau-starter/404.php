<?php

/**
 * The template for displaying 404 pages (Not Found)
 *
 * @package	Pilau_Starter
 * @since	0.1
 * @todo	More useful 404 page?
 */
global $wp_query;

/**
 * Make sure everything's set so this page can be included from elsewhere
 */
$wp_query->is_404 = true;
$wp_query->is_single = false;
$wp_query->is_singular = false;
$wp_query->is_page = false;
status_header( '404' );
add_filter( 'wp_title', 'pilau_404_title', 10000, 3 );

// This hack is needed to prevent an infinite loop caused by this plugin's get_closest_page method
// A "known issue"!
remove_filter( 'wp_get_nav_menu_items', array( 'l3radyCPTStructureHelper', 'add_active_to_menus_for_CPTs' ), 3, 10 );

?>

<?php get_header(); ?>

<div id="content" role="main">

	<?php pilau_not_found( __( 'Page not found' ) ); ?>

</div>

<?php get_sidebar( 'primary' ); ?>

<?php get_footer(); ?>