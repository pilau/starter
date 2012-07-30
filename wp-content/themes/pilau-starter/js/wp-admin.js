/**
 * WP admin scripts
 */


/**
 * Warning flag for changed forms
 *
 * @since	Pilau_Starter 0.1
 */
var pilau_form_warning = false;


/* Trigger when DOM has loaded */
jQuery( document ).ready( function( $ ) {


	/**
	 * Warning for leaving forms that have changed
	 *
	 * If the form's hidden fields are changed dynamically, they need to trigger the
	 * change event specifically, e.g. $( 'input#id' ).val( 'test' ).trigger( 'change' )
	 *
	 * @since	Pilau_Starter 0.1
	 */
	if ( $( 'form.warn-if-changed' ).length ) {
		$( 'form.warn-if-changed :input' ).on( 'change', function() {
			pilau_form_warning = true;
		});
		window.onbeforeunload = function() {
			if ( pilau_form_warning )
				return "You have made changes on this page that you have not yet confirmed. If you navigate away from this page you will lose your unsaved changes.";
		}
		$( 'form.warn-if-changed' ).on( 'submit', function() {
			window.onbeforeunload = null;
		});
	}


});