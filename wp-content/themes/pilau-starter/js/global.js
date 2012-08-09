
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

