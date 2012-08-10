
/**
 * Global JavaScript
 */

/** Trigger when DOM has loaded */
jQuery( document ).ready( function( $ ) {


	/**
	 * Placeholder fallback
	 * @link	http://uniquemethod.com/html5-placeholder-text-jquery-fallback-with-modernizr
	 */
	if ( ! Modernizr.input.placeholder ) {

		// set placeholder values
		$( 'body' ).find( '[placeholder]' ).each( function() {
			if ( $( this ).val() == '' ) {
				$( this ).val( $( this ).attr( 'placeholder' ) );
			}
		});

		// focus and blur of placeholders
		$( '[placeholder]' ).on( 'focus', function() {
			if ( $( this ).val() == $( this ).attr( 'placeholder' ) ) {
				$( this ).val('');
				$( this ).removeClass( 'placeholder' );
			}
		}).on( 'blur', function() {
				if ( $( this ).val() == '' || $( this ).val() == $( this ).attr( 'placeholder' ) ) {
					$( this ).val( $( this ).attr( 'placeholder' ) );
					$( this ).addClass( 'placeholder' );
				}
			});

		// remove placeholders on submit
		$( '[placeholder]' ).closest( 'form' ).on( 'submit', function() {
			$( this ).find( '[placeholder]' ).each( function() {
				if ( $( this ).val() == $( this ).attr( 'placeholder' ) ) {
					$( this ).val('');
				}
			})
		});

	}

});


/** Trigger when window resizes
jQuery( window ).resize( function( $ ) {

});
 */


/*
 * Helper functions
 */


/**
 * Get a part of a string
 *
 * @since	Pilau_Starter 0.1
 * @param	{string}		s		The string
 * @param	{number|string}	i		The numeric index, or 'first' or 'last' (default 'last')
 * @param	{string}		sep		The character used a separator in the passed string (default '-')
 * @return	{string}
 */
function pilau_get_string_part( s, i, sep ) {
	var parts;
	if ( ! sep )
		sep = '-';
	parts = s.split( sep );
	if ( ! i || i == 'last' )
		i = parts.length - 1;
	else if ( i == 'first' )
		i = 0;
	return parts[ i ];
}


/**
 * Generic AJAX error message
 *
 * Add to AJAX calls like this:
 * <code>
 * jQuery.get( pilau_global.ajax_url, { action: 'pilau_ajax_action' }, function( r ) { ... }).error( function( e ) { pilau_ajax_error( e ); });
 * </code>
 *
 * @since	Pilau_Starter 0.1
 * @param	{object}	e	The error response
 * @return	void
 */
function pilau_ajax_error( e ) {
	alert( 'Sorry, there was a problem contacting the server.\n\nPlease try again!' );
}