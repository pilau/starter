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
		op = $( 'li#older-posts' );


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

		op.on( 'click', 'a', function() {
			var i, vars, iefix;

			// Initialize vars
			vars = {
				'action':			'pilau_get_more_posts',
				'post_type':		pilau_ajax_more_data.post_type,
				'orderby':		    pilau_ajax_more_data.orderby,
				'order':		    pilau_ajax_more_data.order,
				'taxonomy':			pilau_ajax_more_data.taxonomy,
				'term_id':			pilau_ajax_more_data.term_id,
				'posts_per_page':	pilau_ajax_more_data.posts_per_page,
				'offset':			$( this ).parent().siblings( 'li' ).length
			};
			i = 0;
			while ( typeof pilau_ajax_more_data['meta_query_' + i + '_key'] != 'undefined' && typeof pilau_ajax_more_data['meta_query_' + i + '_value'] != 'undefined' ) {
				vars['meta_query_' + i + '_key'] = pilau_ajax_more_data['meta_query_' + i + '_key'];
				vars['meta_query_' + i + '_value'] = pilau_ajax_more_data['meta_query_' + i + '_value'];
				i++;
			}
			for ( var key in pilau_ajax_more_data.is_vars ) {
				vars['is_'+key] = pilau_ajax_more_data.is_vars[key];
			}

			// Get posts
			$.post(
				pilau_global.ajaxurl,
				vars,
				function( data ) {
					var i, first_post_id;
					//console.log( data );

					// Remove "last" class from current last post
					op.prev().removeClass( 'last' );

					// Insert and reveal posts
					i = 0;
					$( 'body' ).append( '<div id="_ieAjaxFix" style="display:none"></div>' );
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

			return false;
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
