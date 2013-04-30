
/**
 * Global JavaScript
 */

/** Trigger when DOM has loaded */
jQuery( document ).ready( function( $ ) {
	var placeholders = $( '[placeholder]' ),
		cn = $( '#cookie-notice' );


	/** JS-dependent elements */
	$( '.remove-if-js' ).remove();


	/**
	 * Placeholder fallback
	 */
	if ( ! Modernizr.input.placeholder ) {

		// set placeholder values
		placeholders.each( function() {
			if ( $( this ).val() == '' ) {
				$( this ).val( $( this ).attr( 'placeholder' ) );
			}
		});

		// focus and blur of placeholders
		placeholders.on( 'focus', function() {
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
		placeholders.closest( 'form' ).on( 'submit', function() {
			$( this ).find( '[placeholder]' ).each( function() {
				if ( $( this ).val() == $( this ).attr( 'placeholder' ) ) {
					$( this ).val('');
				}
			})
		});

	}


	/** Cookie notice */
	if ( cn.length ) {
		cn.find( '.close' ).on( 'click', 'a', function () {
			cn.slideUp( 400, function() {
				$( this ).remove();
			});
			return false;
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
 * Place preloader image
 *
 * @param	{string}	e	Selector for element to place preloader in the centre of
 * @return	void
 */
function pilau_preloader_place( e ) {
	var size = 35,
		p = jQuery( '<img src="' + pilau_global.themeurl + '/img/preloader.gif" width="' + size + '" height="' + size + '" alt="" class="preloader">' ),
		t, l;

	if ( typeof e != 'undefined' ) {

		// Position within an element
		e = jQuery( e );

		// Make sure the container is positioned right
		if ( e.css( 'position' ) == 'static' )
			e.css( 'position', 'relative' );

		// Place the preloader
		p.appendTo( e );

	} else {

		// Position centered in viewport
		// Override default styles
		t = ( jQuery( window ).height() - size ) / 2;
		w = ( jQuery( window ).width() - size ) / 2;
		p.css({
			'top':			t,
			'left':			l,
			'margin-left':	0,
			'margin-right':	0
		}).appendTo( 'body' );

	}

}


/**
 * Remove preloader image
 *
 * @param	{string}	e	Selector for element to remove preloader from
 * @return	void
 */
function pilau_preloader_remove( e ) {
	if ( typeof e == 'undefined' )
		e = 'body';
	jQuery( 'img.preloader', e ).remove();
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