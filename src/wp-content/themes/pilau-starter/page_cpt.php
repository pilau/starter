<?php

/**
 * Template Name: CPT sample page
 *
 * @package	Walsingham
 * @since	0.1
 */

$pilau_cpts = get_posts( array(
	'post_type'			=> 'cpt',
	'posts_per_page'	=> -1,
	'orderby'			=> 'title',
	'order'				=> 'ASC',
));

?>

<?php get_header(); ?>

<main role="main" id="content">
	<div class="wrap">


		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

			<article <?php post_class( 'content-body' ); ?> role="article">

				<h1 class="main-heading"><?php the_title(); ?></h1>

				<div class="editor-content main-content">
					<?php the_content(); ?>
				</div>

				<ul class="cpts-list vertical-list">
					<?php
					foreach( $pilau_cpts as $pilau_cpt ) {
						echo '<li class="cpts-item"><a href="' . get_permalink( $pilau_cpt ) . '">' . get_the_title( $pilau_cpt ) . '</a></li>';
					}
					?>
				</ul>

			</article>

		<?php endwhile; endif; ?>


		<div role="complementary" class="sidebar">
			<?php dynamic_sidebar( 'default-sidebar' ); ?>
		</div>


	</div>
</main>

<?php get_footer(); ?>