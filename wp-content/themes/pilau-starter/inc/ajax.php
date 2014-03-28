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
 * @since	Pilau_Starter 0.1
 * @uses	$wp_query
 * @uses	get_option()
 * @uses	esc_attr()
 * @uses	wp_kses()
 * @uses	esc_js()
 *
 * @param	string	$base_url			The base URL for the posts listing
 * @param	string	$post_type			The post type
 * @param	string	$older_label		Label for older posts non-JS fallback (default: 'Older posts')
 * @param	string	$newer_label		Label for newer posts (default: 'Newer posts')
 * @param	array	$taxonomies			Taxonomies
 * @param	int		$posts_per_page		Number of posts per (default: get_option( 'posts_per_page' ) )
 * @param	string	$show_more_label	Label for showing more (default: 'Show more')
 * @param	array	$custom_vars		Custom variables
 * @param	object	$query				The query object (default: $wp_query)
 * @param	array	$exclude			Post IDs to exclude
 * @param	string	$orderby			Field to order by (default: 'date')
 * @param	string	$order				Order (default: 'DESC')
 * @param	array	$meta_query			Meta query (currently only supports keys and string values)
 * @return	void
 */
function pilau_more_posts_link( $base_url = '/', $post_type = 'post', $older_label = null, $newer_label = null, $taxonomies = array(), $posts_per_page = null, $show_more_label = null, $custom_vars = array(), &$query = null, $exclude = array(), $orderby = 'date', $order = 'DESC', $meta_query = null ) {
	global $wp_query;

	// Initialize
	$base_url = trailingslashit( $base_url );
	if ( $older_label == null ) {
		$older_label = __( "Older posts" );
	}
	if ( $newer_label == null ) {
		$newer_label = __( "Newer posts" );
	}
	if ( $show_more_label == null ) {
		$show_more_label = __( "Show more" );
	}
	if ( $posts_per_page == null ) {
		$posts_per_page = get_option( 'posts_per_page' );
	}
	if ( ! $query ) {
		$query =& $wp_query;
	}
	$page = $query->query_vars["paged"];
	if ( ! $page ) {
		$page = 1;
	}
	$qs = $_SERVER["QUERY_STRING"] ? "?" . $_SERVER["QUERY_STRING"] : "";

	// This whole thing is only necessary if there's more found posts than posts per page
	if ( $query->found_posts > $query->query_vars["posts_per_page"] ) {

		// Fallback links
		if ( $page < $query->max_num_pages ) {
			echo '<li id="older-posts" class="more-posts"><a href="' . esc_attr( $base_url . 'page/' . ( $page + 1 ) . '/' . $qs ) . '">' . wp_kses( $older_label, array() ) . '</a></li>';
		}
		if ( $page > 1 ) {
			echo '<li id="newer-posts" class="more-posts"><a href="' . esc_attr( $base_url . 'page/' . ( $page - 1 ) . '/' . $qs ) . '">' . wp_kses( $newer_label, array() ) . '</a></li>';
		}

		// Some JS
		?>
		<script>

			// Replace 'older posts' label with 'show 'more'
			jQuery( 'li#older-posts' ).find( 'a' ).text( '<?php echo wp_kses( $show_more_label, array() ); ?>' );

			// Vars to pass through for AJAX use
			var pilau_ajax_more_data = {
				'post_type':		'<?php echo $post_type; ?>',
				<?php
				if ( isset( $taxonomies ) && is_array( $taxonomies ) && ! empty( $taxonomies ) ) {
					foreach( $taxonomies as $tax ) {
						?>'taxonomy':	'<?php echo $tax['taxonomy']; ?>',
						'term_id':		'<?php echo $tax['terms']; ?>',
					<?php }
				}
				if ( isset( $meta_query ) && is_array( $meta_query ) && ! empty( $meta_query ) ) {
					for( $i = 0; $i < count( $meta_query ); $i++ ) {
						?>'meta_query_<?php echo $i; ?>_key':	'<?php echo $meta_query[ $i ]['key']; ?>',
						'meta_query_<?php echo $i; ?>_value':	'<?php echo $meta_query[ $i ]['value']; ?>',
					<?php }
				}
				?>
				'found_posts':		<?php echo $query->found_posts; ?>,
				'posts_per_page':	<?php echo $posts_per_page; ?>,
				'orderby':	        '<?php echo $orderby; ?>',
				'order':	        '<?php echo $order; ?>',
				'offset':           <?php echo $posts_per_page; ?>,
				'post__not_in':		'<?php echo implode( ",", $exclude ); ?>',
				'is_vars': {
					<?php
					// Pass through conditional variables from query
					$is_vars = array( 'archive', 'date', 'year', 'month', 'day', 'time', 'author', 'category', 'tag', 'tax', 'search', 'home', 'posts_page', 'post_type_archive' );
					$array_query = (array) $query;
					foreach ( $is_vars as $is_var ) {
						echo "'$is_var': " . ( $array_query['is_' . $is_var] ? 'true' : 'false' );
						if ( $is_var != $is_vars[ count( $is_vars ) -1 ] ) {
							echo ',';
						}
					}
					?>
				}<?php
				if ( ! empty( $custom_vars) ) {
					echo ",\n";
					echo "'custom_vars': {\n";
					foreach ( $custom_vars as $custom_var_key => $custom_var_value )
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

