<?php

/**
 * Standard page
 *
 * @package	Pilau_Starter
 * @since	0.1
 */

?>

<?php get_header(); ?>

<main role="main" id="content">
	<div class="wrap">


		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

			<article <?php post_class(); ?> role="article">

				<h1 class="heading-main"><?php the_title(); ?></h1>

				<div class="editor-content">
					<?php the_content(); ?>
				</div>

			</article>


		<?php endwhile; endif; ?>


		<div role="complementary" class="sidebar">
			<?php dynamic_sidebar( 'default-sidebar' ); ?>
		</div>


	</div>
</main>

<?php get_footer(); ?>