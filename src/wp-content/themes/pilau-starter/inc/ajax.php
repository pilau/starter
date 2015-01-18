<?php

/**
 * AJAX functionality
 *
 * @package	[[theme-phpdoc-name]]
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
 * @since	[[theme-phpdoc-name]] 0.1
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
	$post_type = $r['query']->query_vars["post_type"] ? $r['query']->query_vars["post_type"] : 'post';
	$tax_queries = isset( $r['query']->tax_query->queries ) && is_array( $r['query']->tax_query->queries ) && ! empty( $r['query']->tax_query->queries ) ? $r['query']->tax_query->queries : null;
	$meta_queries = isset( $r['query']->meta_query->queries ) && is_array( $r['query']->meta_query->queries ) && ! empty( $r['query']->meta_query->queries ) ? $r['query']->meta_query->queries : null;
	$posts_per_page = ! empty( $r['query']->query_vars["posts_per_page"] ) ? $r['query']->query_vars["posts_per_page"] : -1;
	$orderby = ! empty( $r['query']->query_vars["orderby"] ) ? $r['query']->query_vars["orderby"] : 'date';
	$order = ! empty( $r['query']->query_vars["order"] ) ? $r['query']->query_vars["order"] : 'DESC';
	$meta_key = ! empty( $r['query']->query_vars["meta_key"] ) ? $r['query']->query_vars["meta_key"] : null;
	$meta_value = ! empty( $r['query']->query_vars["meta_value"] ) ? $r['query']->query_vars["meta_value"] : null;
	$s = ! empty( $r['query']->query_vars["s"] ) ? $r['query']->query_vars["s"] : null;

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
			echo '<li id="older-posts" class="more-posts"><a href="' . esc_attr( $r['base_url'] . 'page/' . ( $page + 1 ) . '/' . $qs ) . '">' . wp_kses( $r['older_label'], array() ) . '</a></li>';
		}
		if ( $page > 1 ) {
			echo '<li id="newer-posts" class="more-posts"><a href="' . esc_attr( $r['base_url'] . 'page/' . ( $page - 1 ) . '/' . $qs ) . '">' . wp_kses( $r['newer_label'], array() ) . '</a></li>';
		}

		// Some JS
		?>
		<script>

			// Vars to pass through for AJAX use
			var pilau_ajax_more_data = {
				'show_more_label':	'<?php echo wp_kses( $r['show_more_label'], array( 'span' => array( 'class' => array() ), 'i' => array( 'class' => array() ), 'b' => array( 'class' => array() ) ) ); ?>',
				'post_type':		'<?php echo $post_type; ?>',
				'found_posts':		<?php echo $r['query']->found_posts; ?>,
				'posts_per_page':	<?php echo $posts_per_page; ?>,
				'orderby':	        '<?php echo $orderby; ?>',
				'order':	        '<?php echo $order; ?>',
				'meta_key':	        '<?php echo $meta_key; ?>',
				'meta_value':		'<?php echo $meta_value; ?>',
				's':		        '<?php echo $s; ?>',
				<?php if ( $tax_queries ) { ?>
				'tax_query':	[
					<?php foreach ( $tax_queries as $tax_query ) {
						?> {
						'taxonomy':		'<?php echo $tax_query['taxonomy']; ?>',
						'field':		'<?php echo $tax_query['field']; ?>',
						'terms':		<?php
							$terms = (array) $tax_query['terms'];
							if ( $tax_query['field'] == 'id' ) {
								echo implode( ',', $terms );
							} else {
								echo "'" . implode( "','", $terms ) . "'";
							}
						?>
					},
					<?php } ?>
				],<?php }
				if ( $meta_queries ) { ?>
				'meta_query':	[
					<?php foreach ( $meta_queries as $meta_query ) {
						?> {
						'key':		'<?php echo $meta_query['key']; ?>'
						<?php if ( $meta_query['value'] ) { ?>,
						'value':	'<?php echo $meta_query['value']; ?>'
						<?php } ?>
						<?php if ( $meta_query['compare'] ) { ?>,
						'compare':	'<?php echo $meta_query['compare']; ?>'
						<?php } ?>
					},
					<?php } ?>
				],<?php }
				if ( is_array( $r['query']->query_vars['post__not_in'] ) && $r['query']->query_vars['post__not_in'] ) {
				?>'post__not_in':		[ <?php echo implode( ',', $r['query']->query_vars['post__not_in'] ); ?> ],
				<?php }
				?>'is_vars': {
					<?php
					// Pass through conditional variables from query
					$is_vars = array( 'archive', 'date', 'year', 'month', 'day', 'time', 'author', 'category', 'tag', 'tax', 'search', 'home', 'posts_page', 'post_type_archive' );
					foreach ( $is_vars as $is_var ) {
						echo "'$is_var': " . ( $r['query']->{'is_'.$is_var} ? 'true' : 'false' );
						if ( $is_var != $is_vars[ count( $is_vars ) - 1 ] ) {
							echo ',';
						}
					}
					?>
				}<?php
				if ( ! empty( $r['custom_vars']) ) {
					echo ",\n";
					echo "'custom_vars': {\n";
					foreach ( $r['custom_vars'] as $custom_var_key => $custom_var_value ) {
						echo "'" . esc_js( $custom_var_key ) . "': '" . esc_js( $custom_var_value ) . "',\n";
					}
					echo "\n}";
				}
			echo "\n}";
			?>

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

	$args = array(
		'post_type'			=> $_REQUEST['post_type'],
		'posts_per_page'	=> $_REQUEST['posts_per_page'],
		'offset'			=> $_REQUEST['offset'],
		'post__not_in'		=> $_REQUEST['post__not_in'],
		'post_status'		=> 'publish',
		'orderby'			=> $_REQUEST['orderby'],
		'order'				=> $_REQUEST['order'],
		'meta_key'			=> $_REQUEST['meta_key'],
		'meta_value'		=> $_REQUEST['meta_value'],
		's'      			=> $_REQUEST['s'],
	);
	if ( isset( $_REQUEST['tax_query'] ) ) {
		$args['tax_query'] = $_REQUEST['tax_query'];
	}
	if ( isset( $_REQUEST['meta_query'] ) ) {
		$args['meta_query'] = $_REQUEST['meta_query'];
	}
	//print_r( $args, false ); exit;

	// Get posts
	$pilau_loop = new WP_Query( $args );
	//print_r( $pilau_loop, false ); exit;

	// Force any conditional query variables
	foreach ( $_REQUEST as $key => $value ) {
		if ( strlen( $key ) > 3 && substr( $key, 0, 3 ) == 'is_' ) {
			$wp_query->$key = $value;
		}
	}

	// Build output
	while ( $pilau_loop->have_posts() ) {
		$pilau_loop->the_post();
		get_template_part( 'loop', $_REQUEST['post_type'] );
	}

	// Reset and exit
	wp_reset_query();
	exit( 0 );
}

