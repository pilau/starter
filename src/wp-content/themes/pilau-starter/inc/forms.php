<?php

/**
 * Forms
 *
 * @package	Pilau_Starter
 * @since	0.2
 */


//add_filter( 'gform_get_form_filter', 'pilau_disable_gravity_forms' );
/**
 * Temporarily disable all Gravity Forms
 *
 * @since	0.1
 */
function pilau_disable_gravity_forms( $output ) {
	return '<em>' . __( 'Sorry, this form is temporarily unavailable. Please check back soon!' ) . '</em>';
}


add_action( 'wp', 'pilau_gform_status' );
/**
 * Detect status of Gravity Forms submissions
 *
 * @since	0.1
 */
function pilau_gform_status() {
	global $pilau_gform_submitted, $pilau_gform_valid;
	$pilau_gform_submitted = null;
	$pilau_gform_valid = true;

	if ( PILAU_PLUGIN_EXISTS_GRAVITY_FORMS && isset( $_POST['gform_submit'] ) ) {
		$pilau_gform_submitted = $_POST['gform_submit'];
		$submissions_array = GFFormDisplay::$submission;
		$pilau_gform_valid = $submissions_array[ $pilau_gform_submitted ]['is_valid'];
	}

}


//add_filter( 'gform_tabindex', 'pilau_gform_tabindexer', 10, 2 );
/**
 * Fix Gravity Forms Tabindex Conflicts
 * @link	http://gravitywiz.com/fix-gravity-form-tabindex-conflicts/
 */
function pilau_gform_tabindexer( $tab_index, $form = false ) {
	$starting_index = 1000; // if you need a higher tabindex, update this number
	if ( $form ) {
		add_filter( 'gform_tabindex_' . $form['id'], 'pilau_gform_tabindexer' );
	}
	return GFCommon::$tab_index >= $starting_index ? GFCommon::$tab_index : $starting_index;
}
// Actually, no, disable Gravity Forms tabindexing works better
add_filter( 'gform_tabindex', '__return_false' );


add_filter( 'gform_submit_button', 'pilau_gform_submit_button', 10, 2 );
/**
 * Filter GF submit buttons
 */
function pilau_gform_submit_button( $button, $form ) {

	// Turn it into a button element for easier styling
	$button_text = __( 'Submit' );
	if ( preg_match( "/ value='([^']+)'/", $button, $matches ) ) {
		$button_text = $matches[1];
	}
	$button = str_replace( '<input', '<button', $button );
	$button = str_replace( '/>', '>' . $button_text . '</button>', $button );
	$button = str_replace( ' type="submit"', '', $button );

	return $button;
}