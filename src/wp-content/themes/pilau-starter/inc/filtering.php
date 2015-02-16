<?php

/**
 * Filtering (for front-end listing pages)
 *
 * @package	Pilau_Starter
 * @since	0.2
 */



/**
 * Initialise a page's filters
 *
 * @since	0.2
 */
function pilau_filters_init( $vars = array() ) {
	global $pilau_filters;
	foreach ( $vars as $var ) {
		$pilau_filters[ $var ] = ! empty( $_REQUEST[ $var ] ) ? $_REQUEST[ $var ] : null;
	}
}


/**
 * Output a select for a filter
 *
 * @since	0.2
 * @param	string	$var
 * @param	string	$label
 * @param	string	$var_type	'taxonomy' | 'post_type'
 * @param	string	$show_all
 * @return	void
 */
function pilau_filter_select( $var, $label, $var_type = 'taxonomy', $show_all = null ) {
	global $pilau_filters;

	// Initialise
	if ( empty( $show_all ) ) {
		$show_all = __( '[Show all]' );
	}

	// Output
	?>

	<label for="input-<?php echo $var; ?>"><?php echo $label; ?></label>

	<select name="<?php echo $var; ?>" id="input-<?php echo $var; ?>" class="form-select">

		<option	value=""<?php selected( $pilau_filters[ $var ], null ); ?>><?php echo $show_all; ?></option>

		<?php
		switch ( $var_type ) {

			case 'taxonomy': {
				$terms = get_terms( $var );
				foreach ( $terms as $term ) {
					echo '<option value="' . $term->term_id . '"' . selected( $pilau_filters[ $var ], $term->term_id, false ) . '>' . $term->name . '</option>';
				}
				break;
			}

			case 'post_type': {
				$posts = get_posts( array(
					'post_type'			=> $var,
					'posts_per_page'	=> -1,
					'orderby'			=> 'title',
					'order'				=> 'ASC',
				));
				foreach ( $posts as $post ) {
					echo '<option value="' . $post->ID . '"' . selected( $pilau_filters[ $var ], $post->ID, false ) . '>' . get_the_title( $post ) . '</option>';
				}
			}

		}
		?>

	</select>

<?php
}