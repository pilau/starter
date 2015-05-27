<?php

/**
 * AJAX functionality
 *
 * @package	Pilau_Starter
 * @since	0.1
 */


/**
 * AJAX "more posts" link
 *
 * Output as last <li> in list of posts. Includes non-JS basic pagination fallback links.
 * Currently only works with one list of posts per page.
 *
 * Arguments for the $args array are:
 *
 * 'base_url' (string) - The base URL for the posts listing (default: get_home_url())
 * 'older_label' (string) - Label for older posts non-JS fallback (default: 'Older posts')
 * 'newer_label' (string) - Label for newer posts (default: 'Newer posts')
 * 'show_more_label' (string) - Label for showing more (default: 'Show more')
 * 'custom_vars' (array) - Custom variables (optional)
 * 'query' (object) - The query object (default: $wp_query)
 *
 * @since	Pilau_Starter 0.1
 * @uses	$wp_query
 * @uses	get_option()
 * @uses	get_home_url()
 * @uses	esc_attr()
 * @uses	wp_kses()
 * @uses	esc_js()
 * @return	void
 */
function pilau_more_posts_link( $args = null ) {
	global $wp_query;

	// Defaults
	$defaults = array(
		'base_url' 			=> get_home_url(),
		'older_label'		=> __( 'Older posts' ),
		'newer_label'		=> __( 'Newer posts' ),
		'show_more_label'	=> __( 'Show more' ),
		'custom_vars'		=> null,
		'query'				=> $wp_query
	);
	$r = wp_parse_args( $args, $defaults );
	$posts_per_page = ! empty( $r['query']->query_vars["posts_per_page"] ) ? $r['query']->query_vars["posts_per_page"] : -1;
	//echo '<pre>'; print_r( $r['query']->query_vars ); echo '</pre>'; exit;

	// Initialize
	$r['base_url'] = trailingslashit( $r['base_url'] );
	$page = $r['query']->query_vars["paged"];
	if ( ! $page ) {
		$page = 1;
	}
	$qs = $_SERVER["QUERY_STRING"] ? "?" . $_SERVER["QUERY_STRING"] : "";

	// This whole thing is only necessary if there's more found posts than posts per page
	if ( $r['query']->found_posts > $posts_per_page ) {

		// Fallback links
		if ( $page < $r['query']->max_num_pages ) {
			echo '<li id="older-posts" class="more-posts"><a class="more" href="' . esc_attr( $r['base_url'] . 'page/' . ( $page + 1 ) . '/' . $qs ) . '">' . wp_kses( $r['older_label'], array() ) . '</a></li>';
		}
		if ( $page > 1 ) {
			echo '<li id="newer-posts" class="more-posts"><a class="more" href="' . esc_attr( $r['base_url'] . 'page/' . ( $page - 1 ) . '/' . $qs ) . '">' . wp_kses( $r['newer_label'], array() ) . '</a></li>';
		}

		// Some JS
		?>
		<script>

			// Vars to pass through for AJAX use
			var pilau_ajax_more_data = {
				'show_more_label': '<?php
					echo wp_kses( $r['show_more_label'], array(
						'span'	=> array( 'class' => array() ),
						'i'		=> array( 'class' => array() ),
						'b'		=> array( 'class' => array() )
					)); ?>',
				'found_posts':		<?php echo $r['query']->found_posts; ?>,
				'query_vars':		'<?php echo serialize( $r['query']->query_vars ); ?>'
			};

		</script>

	<?php

	}

}

// AJAX wrapper to get more posts
add_action( 'wp_ajax_nopriv_pilau_get_more_posts', 'pilau_get_more_posts_ajax' );
add_action( 'wp_ajax_pilau_get_more_posts', 'pilau_get_more_posts_ajax' );
function pilau_get_more_posts_ajax() {
	global $pilau_loop, $wp_query;

	// Initialize
	ob_end_clean();
	//print_r( $_REQUEST, false ); exit;

	$args = maybe_unserialize( stripslashes( $_REQUEST['query_vars'] ) );
	$args['offset'] = (int) $_REQUEST['offset'];
	//print_r( $args, false ); exit;

	// Get posts
	$pilau_loop = new WP_Query( $args );
	//print_r( $pilau_loop, false ); exit;

	// Force any conditional query variables
	foreach ( $args as $key => $value ) {
		if ( strlen( $key ) > 3 && substr( $key, 0, 3 ) == 'is_' ) {
			$wp_query->$key = $value;
		}
	}

	// Build output
	while ( $pilau_loop->have_posts() ) {
		$pilau_loop->the_post();
		get_template_part( 'loop', $args['post_type'] );
	}

	// Reset and exit
	wp_reset_query();
	exit( 0 );
}

