/**
 * Minimal accordion behaviour for page-faq.php, replacing the
 * useState-driven open/close logic in FaqAccordion.jsx. Only one item
 * stays open at a time, matching the original component.
 */
document.addEventListener( 'DOMContentLoaded', function () {
	var items = document.querySelectorAll( '.faq-accordion__item' );

	items.forEach( function ( item ) {
		var trigger = item.querySelector( '.faq-accordion__trigger' );

		if ( ! trigger ) {
			return;
		}

		trigger.addEventListener( 'click', function () {
			var wasOpen = item.classList.contains( 'is-open' );

			items.forEach( function ( otherItem ) {
				otherItem.classList.remove( 'is-open' );

				var otherTrigger = otherItem.querySelector( '.faq-accordion__trigger' );
				if ( otherTrigger ) {
					otherTrigger.setAttribute( 'aria-expanded', 'false' );
				}
			} );

			if ( ! wasOpen ) {
				item.classList.add( 'is-open' );
				trigger.setAttribute( 'aria-expanded', 'true' );
			}
		} );
	} );
} );
