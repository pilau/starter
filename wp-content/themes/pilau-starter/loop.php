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

<article id="post-<?php the_ID(); ?>" <?php post_class( array( 'make-link' ) ); ?> role="article">

	<header>

		<h1><a href="<?php the_permalink(); ?>" rel="bookmark" class="make-link-target"><?php the_title(); ?></a></h1>

		<?php if ( get_post_type() == 'post' ) : ?>
			<p class="post-meta">
				<?php the_time( get_option( 'date_format' ) ); ?>
			</p>
		<?php endif; ?>

	</header>

	<div class="post-extract">
		<?php echo pilau_content(); ?>
	</div><!-- .post-extract -->

</article><!-- #post-<?php the_ID(); ?> -->
