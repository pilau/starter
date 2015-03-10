<?php

/**
 * Forms
 *
 * @package	Pilau_Starter
 * @since	0.2
 */


add_filter( 'gform_tabindex', 'pilau_gform_tabindexer', 10, 2 );
/**
 * Fix Gravity Form Tabindex Conflicts
 * @link	http://gravitywiz.com/fix-gravity-form-tabindex-conflicts/
 */
function pilau_gform_tabindexer( $tab_index, $form = false ) {
	$starting_index = 1000; // if you need a higher tabindex, update this number
	if ( $form ) {
		add_filter( 'gform_tabindex_' . $form['id'], 'pilau_gform_tabindexer' );
	}
	return GFCommon::$tab_index >= $starting_index ? GFCommon::$tab_index : $starting_index;
}