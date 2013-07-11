
/**
 * Global JavaScript
 */

/**
 * Flags for throttling window scroll and resize event functionality
 * @link	http://ejohn.org/blog/learning-from-twitter/
 */
var pilau_did_resize = false;
var pilau_did_scroll = false;

/** Trigger when DOM has loaded */
jQuery( document ).ready( function( $ ) {
	var placeholders = $( '[placeholder]' ),
		cn = $( '#cookie-notice' ),
		di = $( 'img[data-defer-src]' );


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


	/**
	 * Load deferred images
	 * @link	http://24ways.org/2010/speed-up-your-site-with-delayed-content/
	 */
	if ( di.length ) {
		di.each( function() {
			$( this ).attr( 'src', $( this ).data( 'defer-src' ) );
		});
	}

});


/** Trigger when window resizes
jQuery( window ).resize( function( $ ) {
	pilau_did_resize = true;
});
setInterval( function() {
	if ( pilau_did_resize ) {
		pilau_did_resize = false;

		// Do stuff here

	}
}, 250 );
 */


/** Trigger when window scrolls
jQuery( window ).scroll( function( $ ) {
	pilau_did_scroll = true;
});
setInterval( function() {
	if ( pilau_did_scroll ) {
		pilau_did_scroll = false;

		// Do stuff here

	}
}, 250 );
 */


/*
 * Helper functions
 */


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


/**
 * Google Analytics
 *
 * @link	https://developers.google.com/analytics/devguides/collection/gajs/
 *
 * Usage:
 *
 * Basic pageview (when using AJAX):
 * pilau_ga.pageview( '/page/path' );
 *
 * JS event:
 * pilau_ga.event( category, action, opt_label, opt_value );
 * e.g. pilau_ga.event( 'PledgeForm', 'share', 'facebook', 23 );
 *
var pilau_ga = {

	// Track a page view (a page that can be accessed via a URL, but which is viewed via AJAX)
	pageview: function( p ) {
		if ( pilau_ga.activated() )
			_gaq.push([ '_trackPageview', p ]);
	},

	// Track an event (actions without corresponding pages)
	event: function( c, a, l, v ) {
		if ( typeof l == 'undefined' )
			l = null;
		if ( typeof v == 'undefined' )
			v = null;
		if ( pilau_ga.activated() )
			_gaq.push([ '_trackEvent', c, a, l, v ]);
	},

	// Is Analytics activated?
	activated: function() {
		return ( typeof _gaq != 'undefined' );
	}

};
*/
