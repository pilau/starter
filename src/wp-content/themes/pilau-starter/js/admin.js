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
	$( '.categorydiv' ).on( 'click', 'input:checkbox', function( e ) {
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
	 * @since   Walsingham 0.1
	 */
	if ( b.hasClass( 'edit-tags-php' ) ) {
		var tax = $( 'input[name=taxonomy]' ).val();

		if ( typeof tax == 'string' && typeof pilau_admin[ tax + '_hierarchical' ] != 'undefined' && pilau_admin[ tax + '_hierarchical' ] == '' ) {
			$( '.term-parent-wrap' ).css({
				position:   'absolute',
				visibility: 'hidden'
			});
		}

	}


});