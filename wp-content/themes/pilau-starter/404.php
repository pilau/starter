<?php

/**
 * The template for displaying 404 pages (Not Found)
 *
 * @package	Pilau_Starter
 * @since	0.1
 * @todo	More useful 404 page?
 */

/**
 * Make sure everything's set so this page can be included from elsewhere
 */
header( 'HTTP/1.1 404 Not Found' );
header( 'Status: 404 Not Found' );
add_filter( 'wp_title', 'pilau_404_title', 10000, 3 );

?>

<?php get_header(); ?>

<div id="content" role="main">

	<?php pilau_not_found( __( 'Page not found' ) ); ?>

</div>

<?php get_sidebar( 'primary' ); ?>

<?php get_footer(); ?>