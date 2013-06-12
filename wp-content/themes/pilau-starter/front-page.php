<?php

/**
 * Front page
 *
 * @package	Pilau_Starter
 * @since	0.1
 */

?>

<?php get_header(); ?>

<div id="content" role="main">

	<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : the_post(); ?>

			<?php the_content(); ?>

		<?php endwhile; ?>

	<?php endif; ?>

</div>

<?php get_sidebar( 'primary' ); ?>

<?php get_footer(); ?>