/**
 * Header search: open/close panel + live suggestions dropdown, porting
 * HeaderSearch.jsx's behavior (debounced search-as-you-type, keyboard nav,
 * click-outside/Escape to close) onto the native WP REST API instead of
 * the SPA's WordPress-API-via-fetch layer. Pressing Enter with no results
 * loaded yet falls back to the plain <form> GET submit (?s=...) to the
 * native search.php results page.
 */
( function () {
	'use strict';

	var MIN_QUERY_LENGTH = 2;
	var DEBOUNCE_MS = 250;

	function ready( fn ) {
		if ( document.readyState !== 'loading' ) {
			fn();
		} else {
			document.addEventListener( 'DOMContentLoaded', fn );
		}
	}

	function stripHtml( html ) {
		var div = document.createElement( 'div' );
		div.innerHTML = String( html || '' );
		return ( div.textContent || div.innerText || '' ).trim();
	}

	ready( function () {
		var root = document.querySelector( '.site-header__search' );
		if ( ! root ) {
			return;
		}

		var toggle = root.querySelector( '.site-header__search-toggle' );
		var form = root.querySelector( 'form' );
		var input = root.querySelector( '.site-header__search-input' );
		var list = root.querySelector( '.site-header__search-suggestions' );
		var ajaxUrl = ( window.sassystridesSearch && window.sassystridesSearch.ajaxUrl ) || '';

		if ( ! toggle || ! input || ! list || ! ajaxUrl ) {
			return;
		}

		var debounceTimer = null;
		var activeIndex = -1;
		var results = [];
		var requestId = 0;

		function isOpen() {
			return root.classList.contains( 'is-open' );
		}

		function openPanel() {
			root.classList.add( 'is-open' );
			toggle.setAttribute( 'aria-expanded', 'true' );
		}

		function closePanel() {
			root.classList.remove( 'is-open' );
			toggle.setAttribute( 'aria-expanded', 'false' );
			hideSuggestions();
		}

		function focusInput() {
			window.requestAnimationFrame( function () {
				input.focus();
			} );
		}

		function hideSuggestions() {
			list.hidden = true;
			list.innerHTML = '';
			input.setAttribute( 'aria-expanded', 'false' );
			input.removeAttribute( 'aria-activedescendant' );
			activeIndex = -1;
			results = [];
		}

		function setActive( index ) {
			var options = list.querySelectorAll( '.site-header__search-option' );
			options.forEach( function ( option, optionIndex ) {
				option.classList.toggle( 'is-active', optionIndex === index );
			} );
			activeIndex = index;

			if ( index >= 0 && options[ index ] ) {
				input.setAttribute( 'aria-activedescendant', options[ index ].id );
			} else {
				input.removeAttribute( 'aria-activedescendant' );
			}
		}

		function renderStatus( text ) {
			list.innerHTML = '';
			var status = document.createElement( 'li' );
			status.className = 'site-header__search-status';
			status.setAttribute( 'role', 'presentation' );
			status.textContent = text;
			list.appendChild( status );
			list.hidden = false;
			input.setAttribute( 'aria-expanded', 'true' );
		}

		function renderResults( posts ) {
			results = posts;
			list.innerHTML = '';

			if ( ! posts.length ) {
				renderStatus( 'No stories found' );
				return;
			}

			posts.forEach( function ( post, index ) {
				var li = document.createElement( 'li' );
				li.setAttribute( 'role', 'presentation' );

				var button = document.createElement( 'button' );
				button.type = 'button';
				button.id = 'site-header-search-option-' + index;
				button.setAttribute( 'role', 'option' );
				button.className = 'site-header__search-option';
				button.textContent = stripHtml( post.title );
				button.addEventListener( 'mouseenter', function () {
					setActive( index );
				} );
				button.addEventListener( 'click', function () {
					goToPost( post );
				} );

				li.appendChild( button );
				list.appendChild( li );
			} );

			list.hidden = false;
			input.setAttribute( 'aria-expanded', 'true' );
			setActive( -1 );
		}

		function goToPost( post ) {
			if ( post && post.link ) {
				window.location.href = post.link;
			}
		}

		function runSearch( query ) {
			var thisRequest = ++requestId;
			renderStatus( 'Searching...' );

			var url = ajaxUrl + ( ajaxUrl.indexOf( '?' ) === -1 ? '?' : '&' ) +
				'action=sassystrides_search&query=' + encodeURIComponent( query );

			window
				.fetch( url, { credentials: 'same-origin' } )
				.then( function ( response ) {
					if ( ! response.ok ) {
						throw new Error( 'Search request failed' );
					}
					return response.json();
				} )
				.then( function ( posts ) {
					if ( thisRequest !== requestId ) {
						return;
					}
					renderResults( Array.isArray( posts ) ? posts : [] );
				} )
				.catch( function () {
					if ( thisRequest !== requestId ) {
						return;
					}
					hideSuggestions();
				} );
		}

		toggle.addEventListener( 'click', function () {
			if ( isOpen() ) {
				closePanel();
			} else {
				openPanel();
				focusInput();
			}
		} );

		input.addEventListener( 'focus', openPanel );

		input.addEventListener( 'input', function () {
			var value = input.value.trim();
			window.clearTimeout( debounceTimer );

			if ( value.length < MIN_QUERY_LENGTH ) {
				hideSuggestions();
				return;
			}

			debounceTimer = window.setTimeout( function () {
				runSearch( value );
			}, DEBOUNCE_MS );
		} );

		input.addEventListener( 'keydown', function ( event ) {
			if ( event.key === 'ArrowDown' ) {
				if ( ! results.length ) {
					return;
				}
				event.preventDefault();
				setActive( activeIndex + 1 >= results.length ? 0 : activeIndex + 1 );
				return;
			}

			if ( event.key === 'ArrowUp' ) {
				if ( ! results.length ) {
					return;
				}
				event.preventDefault();
				setActive( activeIndex <= 0 ? results.length - 1 : activeIndex - 1 );
				return;
			}

			if ( event.key === 'Enter' ) {
				if ( ! results.length ) {
					// No suggestions loaded — let the form submit natively to
					// the ?s= search results page.
					return;
				}
				event.preventDefault();
				goToPost( results[ activeIndex >= 0 ? activeIndex : 0 ] );
			}
		} );

		document.addEventListener( 'click', function ( event ) {
			if ( isOpen() && ! root.contains( event.target ) ) {
				closePanel();
			}
		} );

		document.addEventListener( 'keydown', function ( event ) {
			if ( event.key === 'Escape' && isOpen() ) {
				closePanel();
				toggle.focus();
			}
		} );

		if ( form ) {
			form.addEventListener( 'submit', function ( event ) {
				if ( results.length ) {
					event.preventDefault();
					goToPost( results[ activeIndex >= 0 ? activeIndex : 0 ] );
				}
			} );
		}
	} );
} )();
