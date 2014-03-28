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
 * 'base_url' (string) - The base URL for the posts listing (default: '/')
 * 'post_type' (string) - The post type (default: 'post')
 * 'older_label' (string) - Label for older posts non-JS fallback (default: 'Older posts')
 * 'newer_label' (string) - Label for newer posts (default: 'Newer posts')
 * 'taxonomies' (array) - Taxonomies (optional)
 * 'posts_per_page' (int) - Number of posts per (default: get_option( 'posts_per_page' ) )
 * 'show_more_label' (string) - Label for showing more (default: 'Show more')
 * 'custom_vars' (array) - Custom variables (optional)
 * 'query' (object) - The query object (default: $wp_query)
 * 'exclude' (array) - Post IDs to exclude (optional)
 * 'orderby' (string) - Field to order by (default: 'date')
 * 'order' (string) - Order (default: 'DESC')
 * 'meta_query' (array) - Meta query (currently only supports keys and string values) (optional)
 *
 * @since	Pilau_Starter 0.1
 * @uses	$wp_query
 * @uses	get_option()
 * @uses	esc_attr()
 * @uses	wp_kses()
 * @uses	esc_js()
 * @return	void
 */
function pilau_more_posts_link( $args = null ) {
	global $wp_query;

	// Defaults
	$defaults = array(
		'base_url' 			=> '/',
		'post_type'			=> 'post',
		'older_label'		=> __( 'Older posts' ),
		'newer_label'		=> __( 'Newer posts' ),
		'taxonomies'		=> null,
		'posts_per_page'	=> get_option( 'posts_per_page' ),
		'show_more_label'	=> __( 'Show more' ),
		'custom_vars'		=> null,
		'query'				=> $wp_query,
		'exclude'			=> null,
		'orderby'			=> 'date',
		'order'				=> 'DESC',
		'meta_query'		=> null
	);
	$r = wp_parse_args( $args, $defaults );

	// Initialize
	$r['base_url'] = trailingslashit( $r['base_url'] );
	$page = $r['query']->query_vars["paged"];
	if ( ! $page ) {
		$page = 1;
	}
	$qs = $_SERVER["QUERY_STRING"] ? "?" . $_SERVER["QUERY_STRING"] : "";

	// This whole thing is only necessary if there's more found posts than posts per page
	if ( $r['query']->found_posts > $r['query']->query_vars["posts_per_page"] ) {

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

			// Replace 'older posts' label with 'show 'more'
			jQuery( 'li#older-posts' ).find( 'a' ).text( '<?php echo wp_kses( $r['show_more_label'], array() ); ?>' );

			// Vars to pass through for AJAX use
			var pilau_ajax_more_data = {
				'post_type':		'<?php echo $r['post_type']; ?>',
				<?php
				if ( isset( $r['taxonomies'] ) && is_array( $r['taxonomies'] ) && ! empty( $r['taxonomies'] ) ) {
					foreach( $r['taxonomies'] as $tax ) {
						?>'taxonomy':	'<?php echo $tax['taxonomy']; ?>',
						'term_id':		'<?php echo $tax['terms']; ?>',
					<?php }
				}
				if ( isset( $r['meta_query'] ) && is_array( $r['meta_query'] ) && ! empty( $r['meta_query'] ) ) {
					for( $i = 0; $i < count( $r['meta_query'] ); $i++ ) {
						?>'meta_query_<?php echo $i; ?>_key':	'<?php echo $r['meta_query'][ $i ]['key']; ?>',
						'meta_query_<?php echo $i; ?>_value':	'<?php echo $r['meta_query'][ $i ]['value']; ?>',
					<?php }
				}
				?>
				'found_posts':		<?php echo $r['query']->found_posts; ?>,
				'posts_per_page':	<?php echo $r['posts_per_page']; ?>,
				'orderby':	        '<?php echo $r['orderby']; ?>',
				'order':	        '<?php echo $r['order']; ?>',
				'offset':           <?php echo $r['posts_per_page']; ?>,
				<?php if ( is_array( $r['exclude'] ) ) { ?>
				'post__not_in':		'<?php echo implode( ",", $r['exclude'] ); ?>',
				<?php } ?>
				'is_vars': {
					<?php
					// Pass through conditional variables from query
					$is_vars = array( 'archive', 'date', 'year', 'month', 'day', 'time', 'author', 'category', 'tag', 'tax', 'search', 'home', 'posts_page', 'post_type_archive' );
					$array_query = (array) $r['query'];
					foreach ( $is_vars as $is_var ) {
						echo "'$is_var': " . ( $array_query['is_' . $is_var] ? 'true' : 'false' );
						if ( $is_var != $is_vars[ count( $is_vars ) -1 ] ) {
							echo ',';
						}
					}
					?>
				}<?php
				if ( ! empty( $r['custom_vars']) ) {
					echo ",\n";
					echo "'custom_vars': {\n";
					foreach ( $r['custom_vars'] as $custom_var_key => $custom_var_value )
						echo "'" . esc_js( $custom_var_key ) . "': '" . esc_js( $custom_var_value ) . "',\n";
					echo "}";
				}
				?>
			};

		</script>

	<?php }

}

// AJAX wrapper to get more posts
add_action( 'wp_ajax_nopriv_pilau_get_more_posts', 'pilau_get_more_posts_ajax' );
add_action( 'wp_ajax_pilau_get_more_posts', 'pilau_get_more_posts_ajax' );
function pilau_get_more_posts_ajax() {
	global $pilau_loop, $wp_query;

	// Initialize
	ob_end_clean();

	$args = array(
		'post_type'			=> $_REQUEST['post_type'],
		'posts_per_page'	=> $_REQUEST['posts_per_page'],
		'offset'			=> $_REQUEST['offset'],
		'post__not_in'		=> explode( ',', $_REQUEST['post__not_in'] ),
		'post_status'		=> 'publish',
		'orderby'           => $_REQUEST['orderby'],
		'order'             => $_REQUEST['order']
	);

	// Taxonomy query?
	$tax_query = array();
	if ( isset( $_REQUEST['taxonomy'] ) && $_REQUEST['taxonomy'] && isset( $_REQUEST['term_id'] ) && $_REQUEST['term_id'] ) {
		$tax_query[] = array(
			'taxonomy'	=> $_REQUEST['taxonomy'],
			'field'		=> 'id',
			'terms'		=> $_REQUEST['term_id']
		);
	}
	if ( $tax_query ) {
		$args['tax_query'] = $tax_query;
	}

	// Meta query?
	$meta_query = array();
	$i = 0;
	while ( isset( $_REQUEST['meta_query_' . $i . '_key'] ) && $_REQUEST['meta_query_' . $i . '_key'] && isset( $_REQUEST['meta_query_' . $i . '_value'] ) && $_REQUEST['meta_query_' . $i . '_value'] ) {
		$meta_query[] = array(
			'key'		=> $_REQUEST['meta_query_' . $i . '_key'],
			'value'		=> $_REQUEST['meta_query_' . $i . '_value']
		);
		$i++;
	}
	if ( $meta_query ) {
		$args['meta_query'] = $meta_query;
	}

	// Get posts
	$pilau_loop = new WP_Query( $args );

	// Force any conditional query variables
	foreach ( $_REQUEST as $key => $value ) {
		if ( strlen( $key ) > 3 && substr( $key, 0, 3 ) == 'is_' )
			$wp_query->$key = $value;
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

