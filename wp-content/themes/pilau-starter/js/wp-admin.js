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
	var fwic = $( 'form.warn-if-changed' );


	/**
	 * Warning for leaving forms that have changed
	 *
	 * If the form's hidden fields are changed dynamically, they need to trigger the
	 * change event specifically, e.g. $( 'input#id' ).val( 'test' ).trigger( 'change' )
	 *
	 * @since	Pilau_Base 0.2
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


});