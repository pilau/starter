<?php

/**
 * Default archive index
 *
 * @package	Pilau_Starter
 * @since	0.1
 */

/*
 * Usually most archive types will be disabled
 */
if ( ! is_month() ) {
	require( '404.php' );
	exit;
}

?>

<?php get_header(); ?>

<main role="main" id="content">
	<div class="wrap">

		<?php if ( have_posts() ) : ?>

			<h1 class="main-heading"><?php printf( __( 'Monthly archives: %s' ), '<span>' . get_the_date( 'F Y' ) . '</span>' ); ?></h1>

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'loop', get_post_format() ); ?>

			<?php endwhile; ?>

		<?php else : ?>

			<?php pilau_not_found( __( 'No posts found' ) ); ?>

		<?php endif; ?>

	</div>
</main>

<?php get_footer(); ?>