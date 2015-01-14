<?php

/**
 * Front page
 *
 * @package	[[theme-phpdoc-name]]
 * @since	0.1
 */

?>

<?php get_header(); ?>

<div id="content" role="main">

	<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : the_post(); ?>

			<div class="post-content">
				<?php the_content(); ?>
			</div>

		<?php endwhile; ?>

	<?php endif; ?>

</div>

<?php get_sidebar( 'primary' ); ?>

<?php get_footer(); ?>