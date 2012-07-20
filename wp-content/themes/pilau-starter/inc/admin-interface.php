<?php

/**
 * Admin interface customization
 */


/**
 * Custom Post Type icons
 *
 * Get icons from @link http://randyjensenonline.com/thoughts/wordpress-custom-post-type-fugue-icons/
 * Name icons cpt-[POST-TYPE].png and place into img/icons/
 *
 * @todo Test!
 * @todo Document on GitHub wiki
 */
//add_action( 'admin_head', 'pilau_cpt_icons' );
function pilau_cpt_icons() {
	$cpts = get_post_types( array( 'show_ui' => true ), 'names' );
	if ( $cpts ) {
		?>
		<style media="screen">
			<?php foreach ( $cpts as $cpt ) { ?>
			#menu-posts-<?php echo $cpt; ?> .wp-menu-image {
				background: url('<?php echo get_template_directory_uri(); ?>/img/icons/cpt-<?php echo $cpt; ?>.png') no-repeat 6px -17px !important;
			}
			#menu-posts-<?php echo $cpt; ?>:hover .wp-menu-image, #menu-posts-<?php echo $cpt; ?>.wp-has-current-submenu .wp-menu-image {
				background-position: 6px 7px !important;
			}
			<?php } ?>
		</style>
		<?php
	}
}