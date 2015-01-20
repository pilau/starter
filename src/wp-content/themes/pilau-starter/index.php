<?php

/**
 * Theme index (main posts listing)
 *
 * @package	Pilau_Starter
 * @since	0.1
 */

?>

<?php get_header(); ?>

<main role="main" id="content">
	<div class="wrap">

		<h1 class="heading-main"><?php _e( 'News' ) ?></h1>

		<?php if ( have_posts() ) : ?>

			<ul class="list-blog">

				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'loop', get_post_format() ); ?>

				<?php endwhile; ?>

				<?php

				pilau_more_posts_link();

				?>

			</ul>

		<?php endif; ?>

	</div>
</main>

<?php get_footer(); ?>