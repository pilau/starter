
/**
 * Global JavaScript
 */

/** Trigger when DOM has loaded */
jQuery( document ).ready( function( $ ) {


	/** JS-dependent elements */
	$( '.hide-if-js' ).remove();
	$( '.hide-if-no-js' ).show();


	/** Make containing elements into links */
	if ( $( '.make-link' ).length ) {
		$( '.make-link' ).css( 'cursor', 'pointer' ).hover(
			function() {
				window.status = $( this ).find( 'a.make-link-target' ).attr( 'href' );
				$( this ).find( 'a.make-link-target' ).addClass( 'hover' );
			},
			function() {
				window.status = '';
				$( this ).find( 'a.make-link-target' ).removeClass( 'hover' );
			}
		).click( function() {
			window.location = $( this ).find( 'a.make-link-target' ).attr( 'href' );
		});
	}
	/** Make sibling elements into links */
	if ( $( '.make-sibling-link' ).length ) {
		$( '.make-sibling-link' ).css( 'cursor', 'pointer' ).hover(
			function() { window.status = $( this ).parents( '.make-link-container' ).find( 'a.make-link-target' ).attr( 'href' ); },
			function() { window.status = ''; }
		).click( function() {
			window.location = $( this ).parents( '.make-link-container' ).find( 'a.make-link-target' ).attr( 'href' );
		});
	}
	// Stuff for both types of make-link
	if ( $( '.make-link, .make-sibling-link' ).length ) {
		$( 'a.make-link-target' ).click( function(e) { e.preventDefault(); });
	}

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

