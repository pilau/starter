<?php

/**
 * The template for displaying 404 pages (Not Found)
 *
 * @package	Walsingham
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

?>

<?php get_header(); ?>

	<main role="main">
		<div class="wrap">

			<div class="content-body">
				<?php pilau_not_found( __( 'Page not found' ) ); ?>
			</div>

			<div role="complementary" class="sidebar">
				<?php dynamic_sidebar( 'default-sidebar' ); ?>
			</div>

		</div>
	</main>

<?php get_footer(); ?>