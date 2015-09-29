<?php

/**
 * Template name: Listing page - FAQs
 *
 * @package	Pilau_Starter
 * @since	0.1
 */

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

					<?php

					// Get FAQs for this page
					$pilau_faqs = new WP_Query( array(
						'post_type'			=> 'faq',
						'posts_per_page'	=> -1,
						'meta_query'		=> array(
							'key'		=> pilau_cmb2_meta_key( 'faq-location' ),
							'value'		=> get_queried_object_id(),
						),
						'orderby'			=> 'menu_order',
						'order'				=> 'ASC'
					));

					if ( $pilau_faqs->have_posts() ) {

						echo '<dl class="faqs-list">';
						while ( $pilau_faqs->have_posts() ) {
							$pilau_faqs->the_post();
							echo '<dt class="faqs-question">' . get_the_title() . '</dt>';
							echo '<dd class="faqs-answer">' . get_the_content() . '</dd>';
						}
						echo '</dl>';

					} else {

						echo '<p><em>' . __( 'There are currently no FAQs here.' ) . '</em></p>';

					}

					// Reset query
					wp_reset_postdata();

					?>

				</article>


			<?php endwhile; endif; ?>


			<div role="complementary" class="sidebar">
				<?php dynamic_sidebar( 'default-sidebar' ); ?>
			</div>


		</div>
	</main>

<?php get_footer(); ?>