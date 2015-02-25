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
 * @uses	$pilau_filters
 * @param	array	$vars
 * @return	void
 */
function pilau_filters_init( $vars = array() ) {
	global $pilau_filters;
	foreach ( $vars as $var ) {
		$pilau_filters[ $var ] = ! empty( $_REQUEST[ $var ] ) ? $_REQUEST[ $var ] : null;
	}
}


/**
 * Check if there's any filters set
 *
 * @since	0.2
 * @uses	$pilau_filters
 * @return	bool
 */
function pilau_filters_set() {
	global $pilau_filters;
	$empty_values_removed = array_filter( $pilau_filters );
	return ! empty( $empty_values_removed );
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
				$terms = get_terms( str_replace( '-', '_', $var ) );
				foreach ( $terms as $term ) {
					echo '<option value="' . $term->term_id . '"' . selected( $pilau_filters[ $var ], $term->term_id, false ) . '>' . $term->name . '</option>';
				}
				break;
			}

			case 'post_type': {
				$posts = get_posts( array(
					'post_type'			=> str_replace( '-', '_', $var ),
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


/**
 * Output checkboxes for a filter
 *
 * @since	0.2
 * @param	string	$var
 * @param	string	$legend
 * @param	string	$var_type	'taxonomy' | 'post_type'
 * @return	void
 */
function pilau_filter_checkboxes( $var, $legend, $var_type = 'taxonomy' ) {
	global $pilau_filters;

	// Output
	?>

	<fieldset class="filter-checkboxes">

		<legend><?php echo $legend; ?></legend>

		<ul class="filter-checkboxes-list">

			<?php
			switch ( $var_type ) {

				case 'taxonomy': {
					$terms = get_terms( str_replace( '-', '_', $var ) );
					foreach ( $terms as $term ) {
						echo '<label for="' . $var . '-' . $term->term_id . '"><input type="checkbox" name="' . $var . '[]" id="' . $var . '-' . $term->term_id . '" value="' . $term->term_id . '" ' . pilau_checked( $pilau_filters[ $var ], $term->term_id, false ) . '> ' . $term->name . '</label> ';
					}
					break;
				}

				case 'post_type': {
					$posts = get_posts( array(
						'post_type'			=> str_replace( '-', '_', $var ),
						'posts_per_page'	=> -1,
						'orderby'			=> 'title',
						'order'				=> 'ASC',
					));
					foreach ( $posts as $post ) {
						echo '<label for="' . $var . '-' . $post->ID . '"><input type="checkbox" name="' . $var . '[]" id="' . $var . '-' . $post->ID . '" value="' . $post->ID . '" ' . pilau_checked( $pilau_filters[ $var ], $post->ID, false ) . '> ' . get_the_title( $post ) . '</label> ';
					}
				}

			}
			?>

		</ul>

	</fieldset>

<?php
}