/**
 * Toggles the header search panel open/closed. Ports the open/close and
 * click-outside/Escape behavior from HeaderSearch.jsx's `open` state — the
 * panel itself is a plain WordPress <form method="get"> search (see
 * header.php), not the SPA's AJAX combobox.
 */
( function () {
	'use strict';

	function ready( fn ) {
		if ( document.readyState !== 'loading' ) {
			fn();
		} else {
			document.addEventListener( 'DOMContentLoaded', fn );
		}
	}

	ready( function () {
		var root = document.querySelector( '.site-header__search' );
		if ( ! root ) {
			return;
		}

		var toggle = root.querySelector( '.site-header__search-toggle' );
		var input = root.querySelector( '.site-header__search-input' );
		if ( ! toggle || ! input ) {
			return;
		}

		function isOpen() {
			return root.classList.contains( 'is-open' );
		}

		function openSearch() {
			root.classList.add( 'is-open' );
			toggle.setAttribute( 'aria-expanded', 'true' );
			window.requestAnimationFrame( function () {
				input.focus();
			} );
		}

		function closeSearch() {
			root.classList.remove( 'is-open' );
			toggle.setAttribute( 'aria-expanded', 'false' );
		}

		toggle.addEventListener( 'click', function () {
			if ( isOpen() ) {
				closeSearch();
			} else {
				openSearch();
			}
		} );

		input.addEventListener( 'focus', openSearch );

		document.addEventListener( 'click', function ( event ) {
			if ( isOpen() && ! root.contains( event.target ) ) {
				closeSearch();
			}
		} );

		document.addEventListener( 'keydown', function ( event ) {
			if ( event.key === 'Escape' && isOpen() ) {
				closeSearch();
				toggle.focus();
			}
		} );
	} );
} )();
