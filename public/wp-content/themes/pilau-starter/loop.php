<?php

/**
 * Default post in loop output
 *
 * Most of our projects have an extract on the listing page rather than
 * listing whole posts; this template is geared to reflect that.
 *
 * @package	[[theme-phpdoc-name]]
 * @since	0.1
 */

?>

<li id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<article role="article"><a class="block-wrapper" href="<?php the_permalink(); ?>" rel="bookmark">

		<header>

			<h1><?php the_title(); ?></h1>

			<?php if ( get_post_type() == 'post' ) : ?>
				<p class="post-meta">
					<?php the_time( get_option( 'date_format' ) ); ?>
				</p>
			<?php endif; ?>

		</header>

		<div class="post-extract">
			<?php echo pilau_content(); ?>
		</div><!-- .post-extract -->

	</a></article><!-- #post-<?php the_ID(); ?> -->

</li>
