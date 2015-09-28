/**
 * Global JavaScript
 */


// Declare variables that need to be accessed in various contexts
var pilau_html;
var pilau_body;
var pilau_nav;
var pilau_nav_wrap;
var pilau_popups;
var pilau_slideshows;
// Breakpoints
var pilau_bps = {
	'large':	pilauBreakpointLarge, // This and above is "large"
	'medium':	pilauBreakpointMedium // This and above is "medium"; below is "small"
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
	//var placeholders = $( '[placeholder]' );
	//var cn = $( '#cookie-notice' );
	//var di = $( 'img[data-defer-src]' );
	var op = $( '.older-posts' );
	var tl = $( '[role=tablist]' );
	var nmc = $( '.nav-mobile-control' );
	pilau_html = $( 'html' );
	pilau_body = $( 'body' );
	pilau_nav = $( '.nav-dynamic' );
	pilau_nav_wrap = $( '#nav-wrap' );
	pilau_popups = $( '.popup-wrap' );
	pilau_slideshows = $( '.slideshow' );


	/** Initialise viewport infos */
	pilau_viewport_infos();


	/** Slideshows */
	pilau_slideshows_manage();


	/** Hack for IE10 / 11 styling (conditional comments not supported) */
	if ( /MSIE 1[01]\.\d+;/.test( navigator.userAgent ) || /Trident/.test( navigator.userAgent ) ) {
		pilau_html.addClass( 'ie' );
		if ( /MSIE 10\.\d+;/.test( navigator.userAgent ) ) {
			pilau_html.addClass( 'ie10' );
			pilau_html.addClass( 'lt-ie11' );
			pilau_html.addClass( 'lt-ie12' );
		}
		if ( /Trident.*rv[ :]*11\./.test( navigator.userAgent ) ) {
			pilau_html.addClass( 'ie11' );
			pilau_html.addClass( 'lt-ie12' );
		}
	}


	/** JS-dependent elements */
	$( '.remove-if-js' ).remove();


	/**
	 * Placeholder fallback
	 */
	if ( ! Modernizr.input.placeholder && typeof placeholders !== 'undefined' && placeholders.length ) {

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
	if ( typeof cn !== 'undefined' && cn.length ) {
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
	if ( typeof di !== 'undefined' && di.length ) {
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
	if ( typeof op !== 'undefined' && op.length ) {

		// Replace label
		op.each( function() {
			var el = $( this );
			var i = parseInt( el.data( 'instance' ) );
			el.find( 'a' ).html( pilau_ajax_more_data[ i ].show_more_label );
		});

		// Click event
		op.on( 'click', 'a', function( e ) {
			var vars;
			var el_op = $( this ).parent();
			var i = parseInt( el_op.data( 'instance' ) );
			e.preventDefault();

			// Initialize vars
			vars = pilau_ajax_more_data[ i ];
			vars['action'] = 'pilau_get_more_posts';
			vars['offset'] = $( this ).parent().siblings( 'li' ).length;
			//console.log( vars );

			// Get posts
			$.post(
				pilau_global.ajaxurl,
				vars,
				function( data ) {
					var p, first_post_id, iefix;
					//console.log( data );

					// Remove "last" class from current last post
					el_op.prev().removeClass( 'last' );

					// Insert and reveal posts
					p = 0;
					pilau_body.append( '<div id="_ieAjaxFix" style="display:none"></div>' );
					iefix = $( "#_ieAjaxFix" );

					// Match li.hentry, the only class applied to all items by get_post_class()
					iefix.html( data ).find( "li.hentry" ).each( function() {
						var el = $( this );
						if ( p == 0 ) {
							first_post_id = el.attr( "id" );
						}
						el.hide().insertBefore( el_op ).slideDown( 600 );
						p++;
					});
					iefix.remove();

					// Get rid of more posts link if there's no more
					if ( el_op.siblings( 'li' ).length >= pilau_ajax_more_data[ i ].found_posts ) {
						el_op.fadeOut( 1000 );
					}

					// Scroll to right place
					//$( 'html, body' ).delay( 1000 ).animate( { scrollTop: $( "#" + first_post_id ).offset().top }, 1000 );

				}
			);

		});

	}


	/**
	 * Navigation
	 */
	if ( typeof pilau_nav !== 'undefined' && pilau_nav.length ) {

		// Manage both mouse and keyboard behaviours
		pilau_nav.on( 'mouseenter focus', '.menu-level-0.menu-item-has-children > .menu-item-link', function( e ) {
			if ( ! pilau_vw_small ) {
				var el = $( this );
				el.toggleClass( 'has-focus' );
				//console.log( 'focus top level link: ' + el.text() );
				// Show sub-menu
				el.parents( '.menu-item' ).attr( 'aria-expanded', 'true' );
			}
		}).on( 'mouseleave blur', '.menu-level-0.menu-item-has-children > .menu-item-link', function( e ) {
			if ( ! pilau_vw_small ) {
				var el = $( this );
				el.toggleClass( 'has-focus' );
				//console.log( 'blur top level link: ' + el.text() );
				// Only hide sub-menu after a short delay, so links get a chance to catch focus from tabbing
				setTimeout( function() {
					var smw = el.siblings( '.sub-menu-wrapper' );
					if ( smw.attr( 'data-has-focus' ) !== 'true' ) {
						el.parents( '.menu-item' ).attr( 'aria-expanded', 'false' );
					}
				}, 100 );
			}
		}).on( 'mouseenter focusin', '.sub-menu-wrapper', function( e ) {
			if ( ! pilau_vw_small ) {
				var el = $( this );
				//console.log( 'focus sub-menu-wrapper' );
				el.attr( 'data-has-focus', 'true' );
			}
		}).on( 'mouseleave blur', '.sub-menu-wrapper', function( e ) {
			if ( ! pilau_vw_small ) {
				var el = $( this );
				setTimeout( function () {
					// Check if anything else has picked up focus (i.e. next link in sub-menu)
					if ( el.find( ':focus' ).length === 0 ) {
						//console.log( 'blur sub-menu link: ' + el.text() );
						el.attr( 'data-has-focus', 'false' );
						// Hide sub-menu on the way out if parent link doesn't have focus
						if ( el.siblings( '.menu-item-link.has-focus' ).length === 0 ) {
							el.parents( '.menu-level-0' ).attr( 'aria-expanded', 'false' );
						}
					}
				}, 100 );
			}
		});

	}

	if ( typeof pilau_nav_wrap !== 'undefined' && pilau_nav_wrap.length ) {
		// Toggle sub-menus in mobile nav
		pilau_nav_wrap.on( 'click', '.sub-menu-control', function ( e ) {
			if ( pilau_vw_small ) {
				var el = $( this );
				var p = el.parents( '[aria-expanded]' );
				el.toggleClass( 'sub-menu-control-open' );
				p.attr( 'aria-expanded', ( p.attr( 'aria-expanded' ) == 'true' ) ? 'false' : 'true' );
			}
		} );
	}


	/**
	 * Popups
	 */
	pilau_popups.on( 'click', '.popup-button', function( e ) {
		var pw = $( this ).parents( '.popup-wrap' );
		if ( pw.hasClass( 'popup-closed' ) ) {
			// By default, close all others before opening
			pilau_popups.not( this ).each( function() {
				$( this ).pilauPopupClose();
			});
			pw.pilauPopupOpen();
		} else {
			pw.pilauPopupClose();
		}
	});

	// Close popups when user clicks on page
	$( document ).click( function( e ) {
		var t = $( e.target );
		// Ignore if a button's being clicked, or if the click is somewhere inside an open popup
		if ( typeof pilau_popups !== 'undefined' && ! t.hasClass( 'popup-button' ) && ! t.closest( '.popup-box' ).length ) {
			pilau_popups.pilauPopupClose();
		}
	});

	// Helpers to open/close popup
	$.fn.pilauPopupOpen = function() {
		this.removeClass( 'popup-closed' ).addClass( 'popup-open' ).children( '.popup-box' );
		if ( this.is( '[aria-hidden]' ) ) {
			this.attr( 'aria-hidden', 'false' );
		}
	};
	$.fn.pilauPopupClose = function() {
		this.removeClass( 'popup-open' ).addClass( 'popup-closed' ).children( '.popup-box' );
		if ( this.is( '[aria-hidden]' ) ) {
			this.attr( 'aria-hidden', 'true' );
		}
	};


	/**
	 * Tabs
	 */
	if ( typeof tl !== 'undefined' && tl.length ) {

		tl.on( 'click', '[role=tab]', function( e ) {
			// Clicking on a tab
			e.preventDefault();
			var el = $( this );
			if ( el.attr( 'aria-selected' ) == 'false' ) {
				var panel = $( '#' + el.attr( 'aria-controls' ) );
				el.attr( 'aria-selected', 'true' );
				el.siblings().attr( 'aria-selected', 'false' );
				panel.attr( 'aria-hidden', 'false' );
				panel.siblings().attr( 'aria-hidden', 'true' );
			}
		} ).on( 'keydown', '[role=tab]', function( e ) {
			// Make enter key act like a click
			if ( e.which == 13 ) {
				$( this ).click()
			}
		});

	}


	/**
	 * Smooth scroll to fragment
	 *
	 * @link	https://css-tricks.com/snippets/jquery/smooth-scrolling/
	 */
	pilau_body.on( 'click', 'a[href*=#]:not([href=#])', function() {
		if ( location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname ) {
			var target = $( this.hash );
			target = target.length ? target : $( '[name=' + this.hash.slice(1) + ']' );
			if ( target.length ) {
				$( 'html,body' ).animate( {
					scrollTop: target.offset().top
				}, 700 );
				return false;
			}
		}
	})


});


/** Trigger when window resizes */
jQuery( window ).resize( function( $ ) {
	pilau_did_resize = true;
});
setInterval( function() {
	if ( pilau_did_resize ) {
		pilau_did_resize = false;

		// Refresh viewport infos
		pilau_viewport_infos();

		// Manage slideshows
		pilau_slideshows_manage();

	}
}, 250 );


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
 * Send an event to Google Analytics (via Yoast Analytics)
 *
 * @uses	__gaTracker()
 * @param	{string}	category
 * @param	{string}	action
 * @param	{string}	label
 * @param	{string}	value
 * @return	{void}
 */
function pilau_ga_event( category, action, label, value ) {
	if ( typeof __gaTracker === 'function' ) {
		var event_object = {
			'hitType':			'event',
			'eventCategory':	category,
			'eventAction':		action
		};
		if ( typeof label !== 'undefined' ) {
			event_object.eventLabel = label;
		}
		if ( typeof value !== 'undefined' ) {
			event_object.eventValue = value;
		}
		__gaTracker( 'send', event_object );
	}
}


/**
 * Viewport infos
 */
function pilau_viewport_infos() { jQuery( function($) {
	pilau_vw = $( window ).width();
	pilau_vw_large = pilau_vw >= pilau_bps.large;
	pilau_vw_medium = pilau_vw >= pilau_bps.medium && pilau_vw < pilau_bps.large;
	pilau_vw_small = pilau_vw < pilau_bps.medium;
});}


/**
 * Manage any slideshow adjustments
 */
function pilau_slideshows_manage() { jQuery( function($) {

	pilau_slideshows.each( function( i ) {
		var el = $( this );
		// This keeps the viewport fluid based on a proportion of the width
		el.find( '.flickity-viewport' ).css({
			'height':			'0',
			'padding-bottom':	el.outerHeight(),
		});
	});

});}


/**
 * Place preloader image
 *
 * @param	{string}	e	Selector for element to place preloader in the centre of
 * @return	void
 */
function pilau_preloader_place( e ) {
	var size = 35,
		p = jQuery( '<img src="' + pilau_global.themeurl + 'img/preloader.gif" width="' + size + '" height="' + size + '" alt="" class="preloader">' ),
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
 * Debug logging - only logs on local / staging
 *
 * @since	Pilau_Starter 0.1
 * @param	{mixed}		d		The data to log
 * @return	void
 */
function pilau_log( d ) {
	if ( pilau_body.hasClass( 'env-local' ) || pilau_body.hasClass( 'env-staging' ) ) {
		console.log( d );
	}
}


/**
 * Fix for IE and Chrome issue affecting skip links and tabbing focus
 * @link	http://www.nczonline.net/blog/2013/01/15/fixing-skip-to-content-links/
 */
if ( window.addEventListener ) {
	window.addEventListener( 'hashchange', function( e ) {
		var el = document.getElementById( location.hash.substring( 1 ) );
		if ( el ) {
			if ( !/^(?:a|select|input|button|textarea)$/i.test( el.tagName ) ) {
				el.tabIndex = -1;
			}
			el.focus();
		}
	}, false);
}
