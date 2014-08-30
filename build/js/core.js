// ==== CORE ==== //

// Anything entered here will end up at the top of pendrell-core.js

// Navigation.js adapted from _s: handles toggling the responsive navigation menu
( function() {
	var container, button, menu;

	container = document.getElementById( 'site-navigation' );
	if ( ! container ) {
		return;
	}

	button = document.getElementById( 'responsive-menu-toggle' );
	if ( 'undefined' === typeof button ) {
		return;
	}

	menu = container.getElementsByTagName( 'ul' )[0];

	// Hide menu toggle button if menu is empty and return early
	if ( 'undefined' === typeof menu ) {
		button.style.display = 'none';
		return;
	}

	button.onclick = function() {
		if ( -1 !== button.className.indexOf( 'toggled' ) ) {
			button.className = button.className.replace( ' toggled', '' );
		} else {
			button.className += ' toggled';
		}
		if ( -1 !== menu.className.indexOf( 'toggled' ) ) {
			menu.className = menu.className.replace( ' toggled', '' );
		} else {
			menu.className += ' toggled';
		}
	};
} )();
