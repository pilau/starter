<?php

/**
 * Single post
 *
 * @package	Pilau_Starter
 * @since	0.1
 */

?>

<?php get_header(); ?>

<main role="main" id="content">
	<div class="wrap">


		<?php pilau_post_back_link( __( 'Back to news listing' ) ); ?>


		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

			<article <?php post_class( 'content-body' ); ?> role="article">

				<header>

					<h1 class="main-heading"><?php the_title(); ?></h1>

					<p class="main-meta"><?php pilau_post_date(); ?></p>

				</header>

				<div class="editor-content main-content">
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