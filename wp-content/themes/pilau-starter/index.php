<?php

/**
 * Theme index (main posts listing)
 *
 * @package	Pilau_Starter
 * @since	0.1
 */

?>

<?php get_header(); ?>

<div id="content" role="main">

	<h1><?php _e( 'News' ) ?></h1>

	<?php if ( have_posts() ) : ?>

		<ul>

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'loop', get_post_format() ); ?>

			<?php endwhile; ?>

			<?php

			pilau_more_posts_link();

			?>

		</ul>

	<?php endif; ?>

</div>

<?php get_sidebar( 'primary' ); ?>

<?php get_footer(); ?>