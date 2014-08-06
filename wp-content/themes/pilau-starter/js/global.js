/**
 * Global JavaScript
 */


// Declare variables that need to be accessed in various contexts
var pilau_body; // The body
// Breakpoints
var pilau_bps = {
	'large':	1000, // This and above is "large"
	'medium':	640 // This and above is "medium"; below is "small"
};
var pilau_vw; // Viewport width
var pilau_vw_large;
var pilau_vw_medium;
var pilau_vw_small;

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
		op = $( 'li#older-posts' );
	pilau_body = $( 'body' );
	pilau_vw = $( window ).width();
	pilau_vw_large = pilau_vw >= pilau_bps.large;
	pilau_vw_medium = pilau_vw >= pilau_bps.medium && pilau_vw < pilau_bps.medium;
	pilau_vw_small = pilau_vw < pilau_bps.small;


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
	 *
	 * You can trigger JS functions when the deferred image has loaded like this:
	 * <img src="<?php echo PILAU_PLACEHOLDER_GIF_URL; ?>" data-defer-src="<?php echo $image_id; ?>" data-defer-callback="my_callback_function" alt="">
	 *
	 * @link	http://24ways.org/2010/speed-up-your-site-with-delayed-content/
	 */
	if ( di.length ) {
		di.each( function() {
			var el = $( this );
			var cb = el.data( 'defer-callback' );
			if ( typeof cb !== 'undefined' ) {
				// Attach callback function to load event
				el.on( 'load', window[ cb ] );
			}
			el.attr( 'src', el.data( 'defer-src' ) );
		});
	}


	/**
	 * AJAX "more posts" loading
	 */
	if ( op.length ) {

		// Replace label
		op.find( 'a' ).html( pilau_ajax_more_data.show_more_label );

		// Click event
		op.on( 'click', 'a', function( e ) {
			var vars;
			e.preventDefault();

			// Initialize vars
			vars = pilau_ajax_more_data;
			vars['action'] = 'pilau_get_more_posts';
			vars['offset'] = $( this ).parent().siblings( 'li' ).length;
			//console.log( vars );

			// Get posts
			$.post(
				pilau_global.ajaxurl,
				vars,
				function( data ) {
					var i, first_post_id, iefix;
					//console.log( data );

					// Remove "last" class from current last post
					op.prev().removeClass( 'last' );

					// Insert and reveal posts
					i = 0;
					pilau_body.append( '<div id="_ieAjaxFix" style="display:none"></div>' );
					iefix = $( "#_ieAjaxFix" );

					// Match li.hentry, the only class applied to all items by get_post_class()
					iefix.html( data ).find( "li.hentry" ).each( function() {
						var el = $( this );
						if ( i == 0 ) {
							first_post_id = el.attr( "id" );
						}
						el.hide().insertBefore( 'li#older-posts' ).slideDown( 600 );
						i++;
					});
					iefix.remove();

					// Get rid of more posts link if there's no more
					if ( op.siblings( 'li' ).length >= pilau_ajax_more_data.found_posts ) {
						op.fadeOut( 1000 );
					}

					// Scroll to right place
					//$( 'html, body' ).delay( 1000 ).animate( { scrollTop: $( "#" + first_post_id ).offset().top }, 1000 );

				}
			);

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


/**
 * Sample function with jQuery shortcut included
 * I always forget this simple syntax!
 */
function pilau_sample_jquery_shortcut_function() { jQuery( function($) {

});}


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
		}).appendTo( pilau_body );

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
		e = pilau_body;
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


