<?php

/**
 * Default post in loop output
 *
 * Most of our projects have an extract on the listing page rather than
 * listing whole posts; this template is geared to reflect that.
 *
 * @package	Pilau_Starter
 * @since	0.1
 */

?>

<li <?php post_class(); ?>>

	<article role="article"><a class="link-block" href="<?php the_permalink(); ?>" rel="bookmark">

		<header>

			<h1 class="heading-loop"><?php the_title(); ?></h1>

			<?php if ( get_post_type() == 'post' ) : ?>
				<p class="post-meta">
					<?php the_time( get_option( 'date_format' ) ); ?>
				</p>
			<?php endif; ?>

		</header>

		<div class="teaser">
			<?php echo pilau_content(); ?>
		</div>

	</a></article>

</li>
