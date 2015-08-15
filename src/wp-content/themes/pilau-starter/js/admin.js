/**
 * WP admin scripts
 */


/**
 * Warning flag for changed forms
 *
 * @since	Pilau_Starter 0.2
 */
var pilau_form_warning = false;


/* Trigger when DOM has loaded */
jQuery( document ).ready( function( $ ) {
	var b = $( 'body' );
	var fwic = $( 'form.warn-if-changed' );
	var cd = $( '.categorydiv' );


	/**
	 * Warning for leaving forms that have changed
	 *
	 * If the form's hidden fields are changed dynamically, they need to trigger the
	 * change event specifically, e.g. $( 'input#id' ).val( 'test' ).trigger( 'change' )
	 *
	 * @since	Pilau_Starter 0.2
	 */
	if ( fwic.length ) {
		$( ':input', fwic ).on( 'change', function() {
			pilau_form_warning = true;
		});
		window.onbeforeunload = function() {
			if ( pilau_form_warning )
				return "You have made changes on this page that you have not yet confirmed. If you navigate away from this page you will lose your unsaved changes.";
		};
		fwic.on( 'submit', function() {
			window.onbeforeunload = null;
		});
	}


	/**
	 * Taxonomy validation?
	 *
	 * When registering a taxonomy, use the following custom arguments:
	 * (bool) pilau_required
	 * (bool) pilau_multiple
	 *
	 * @since   Pilau_Starter 0.2
	 */
	cd.on( 'click', 'input:checkbox', function( e ) {
		var el = $( this );
		var id_parts = el.attr( 'id' ).split( '-' );
		var tax = id_parts[1];
		var checked = el.parents( '.categorychecklist' ).find( ':checked' );

		if ( typeof pilau_admin[ tax + '_multiple' ] != 'undefined' && pilau_admin[ tax + '_multiple' ] == '' && checked.length > 1 ) {

			// Only one can be selected
			checked.prop( 'checked', false );
			el.prop( 'checked', true );

		} else if ( typeof pilau_admin[ tax + '_required' ] != 'undefined' && pilau_admin[ tax + '_required' ] == '1' && checked.length == 0 ) {

			// Required, none selected
			alert( 'Please select at least one term from this taxonomy.' );
			el.prop( 'checked', true );

		}

	});


	/**
	 * Remove term parent selection if pilau_hierarchical is false
	 *
	 * When registering a taxonomy, use the following custom arguments:
	 * (bool) pilau_hierarchical
	 *
	 * @since   Pilau_Starter 0.1
	 */

	// When editing terms
	if ( b.hasClass( 'edit-tags-php' ) ) {
		var tax = $( 'input[name=taxonomy]' ).val();

		if ( typeof tax == 'string' && typeof pilau_admin[ tax + '_hierarchical' ] != 'undefined' && pilau_admin[ tax + '_hierarchical' ] == '' ) {
			$( '.term-parent-wrap' ).css({
				position:   'absolute',
				visibility: 'hidden'
			});
		}

	}

	// When editing posts
	cd.each( function() {
		var el = $( this );
		var tax = el.attr( 'id' ).replace( 'taxonomy-', '' );
		if ( typeof tax == 'string' && typeof pilau_admin[ tax + '_hierarchical' ] != 'undefined' && pilau_admin[ tax + '_hierarchical' ] == '' ) {
			$( '#new' + tax + '_parent' ).css({
				position:   'absolute',
				visibility: 'hidden'
			});
		}
	});


	/**
	 * CMB 'show when' functionality - currently just for selects
	 */

	// Make sure initial state is right
	$( '.cmb-show-when-target' ).each( function() {
		var el = $( this );
		var tf = pilau_get_class_value( el.attr( 'class' ), 'cmb-show-when-field-' );
		var tv = pilau_get_class_value( el.attr( 'class' ), 'cmb-show-when-value-' );
		if ( $( '[name=' + tf + ']' ).val() != tv ) {
			el.hide();
		}
	});

	// Change for selects
	b.on( 'change', '.cmb-show-when-source select', function() {
		var el = $( this );
		$( '.cmb-show-when-field-' + el.attr( 'name' ) ).each( function() {
			var el2 = $( this );
			if ( el2.hasClass( 'cmb-show-when-value-' + el.val() ) ) {
				el2.show();
			} else {
				el2.hide();
			}
		});
	});


});


/**
 * Get a value that's stored in a class, using a base
 *
 * @param	{string}	classes
 * @param	{string}	class_base
 * @returns {string}
 */
function pilau_get_class_value( classes, class_base ) {
	var val = '';
	var class_regexp = new RegExp( class_base + '([^ ]+)' );
	var class_full = classes.match( class_regexp );
	if ( typeof class_full[1] !== 'undefined' ) {
		val = class_full[1];
	}
	return val;
}