<?php

/**
 * Front page
 *
 * @package	Pilau_Starter
 * @since	0.1
 */

?>

<?php get_header(); ?>

<main role="main" id="content">
	<div class="wrap">

		<?php if ( have_posts() ) : ?>

			<?php while ( have_posts() ) : the_post(); ?>

				<div class="editor-content">
					<?php the_content(); ?>
				</div>

			<?php endwhile; ?>

		<?php endif; ?>

	</div>
</main>

<?php get_footer(); ?>