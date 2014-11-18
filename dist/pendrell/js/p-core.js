// Navigation.js adapted from _s; changed to toggled the entire site navigation element, not just the menu within it
;(function() {
	var nav 			= document.getElementById('site-navigation'),
			menu 			= nav.getElementsByTagName('ul')[0],
			button 		= document.getElementById('responsive-menu-toggle');

	// Early exit if we're missing anything essential
	if (!nav || typeof button === 'undefined') {
		return;
	}

	// Hide button if menu is empty and return early
	if (typeof menu === 'undefined') {
		button.style.display = 'none';
		return;
	}

	// Toggle navigation; add or remove a class to both the button and the nav element itself
	button.onclick = function() {
		if (button.className.indexOf( 'toggled' ) !== -1) {
			button.className = button.className.replace(' toggled', '');
		} else {
			button.className += ' toggled';
		}

		if (nav.className.indexOf( 'toggled' ) !== -1) {
			nav.className = nav.className.replace(' toggled', '');
		} else {
			nav.className += ' toggled';
		}
	};
} )();

// ==== CORE ==== //

// Anything entered here will end up at the top of pendrell-core.js

// For compatibility with Ajaxinate be sure to bind events to a DOM element that isn't ever replaced (e.g. `body`)

;(function($){
  $(function(){ // Shortcut to $(document).ready(handler);
    // Insert code here
  });
}(jQuery));
