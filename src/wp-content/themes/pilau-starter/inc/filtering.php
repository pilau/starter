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
 * @param	string	$var_type	'taxonomy' | 'post_type' | 'event_date'
 * @param	string	$show_all
 * @param	string	$format		For 'event_date': 'Y' | 'mY'
 * @return	void
 */
function pilau_filter_select( $var, $label, $var_type = 'taxonomy', $show_all = null, $format = 'mY' ) {
	global $pilau_filters, $wpdb;

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
				break;
			}

			case 'event_date': {

				if ( PILAU_PLUGIN_EXISTS_DEVELOPERS_CUSTOM_FIELDS && PILAU_PLUGIN_EXISTS_SIMPLE_EVENTS ) {

					// Get all dates for current post type
					$dates = $wpdb->get_col("
						SELECT		pm.meta_value
						FROM		$wpdb->postmeta pm, $wpdb->posts p
						WHERE		pm.post_id		= p.ID
						AND			p.post_status	= 'publish'
						AND 		p.post_type		= '" . SLT_SE_EVENT_POST_TYPE . "'
						AND			pm.meta_key		= '" . slt_cf_field_key( SLT_SE_EVENT_DATE_FIELD ) . "'
						ORDER BY	pm.meta_value DESC
					");

					// Output in selected format
					$last_date_value = null;
					foreach ( $dates as $date ) {
						$date_value = $date_output = null;
						$date_timestamp = slt_se_date_to_timestamp( $date );
						switch ( $format ) {
							case 'y': {
								$date_value = date( 'Y', $date_timestamp );
								$date_output = date( 'Y', $date_timestamp );
								break;
							}
							case 'mY': {
								$date_value = date( 'm', $date_timestamp ) . date( 'Y', $date_timestamp );
								$date_output = date( 'F', $date_timestamp ) . ' ' . date( 'Y', $date_timestamp );
								break;
							}
						}
						if ( $date_value !== $last_date_value ) {
							echo '<option value="' . $date_value . '"' . selected( $pilau_filters[ $var ], $date_value, false ) . '>' . $date_output . '</option>';
						}
						$last_date_value = $date_value;
					}

				}
				break;
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


/**
 * Output single checkbox for a filter
 *
 * @since	0.2
 * @param	string	$var
 * @param	string	$label
 * @return	void
 */
function pilau_filter_checkbox( $var, $label ) {
	global $pilau_filters;

	// Output
	?>

	<div class="filter-checkbox">

		<label for="input-<?php echo $var; ?>"><?php echo $label; ?> &nbsp;<input type="checkbox" name="<?php echo $var; ?>" id="input-<?php echo $var; ?>" value="1" <?php checked( $pilau_filters[ $var ] ); ?>></label>

	</div>

<?php
}