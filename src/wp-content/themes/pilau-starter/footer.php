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