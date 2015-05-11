<?php

/**
 * Theme footer
 *
 * @package	Pilau_Starter
 * @since	0.1
 */

?>

<footer class="footer-main" role="contentinfo">
	<div class="wrap">

		<p class="back-to-top"><a href="#header" title="<?php _e( 'Click to go to the top of the page' ); ?>" class="icon-angle-up"><?php _e( 'Back to top' ); ?></a></p>

		<ul class="nav nav-footer">
			<?php
			echo pilau_menu_without_containers( 'nav_footer' );
			?>
		</ul>

	</div>
</footer>

<?php wp_footer(); ?>

</body>
</html>