// ==== NAVIGATION ==== //

// Navigation.js adapted from _s; changed to active the entire site navigation element, not just the menu within it
;(function($) {
	var nav 			= document.getElementById('site-navigation'),
			menu 			= nav.getElementsByTagName('ul')[0],
			button 		= document.getElementById('menu-toggle'),
			icon      = button.getElementsByTagName('svg')[0];

	// Early exit if we're missing anything essential
	if (!nav || typeof button === 'undefined') {
		return;
	}

	// Hide button if menu is empty and return early
	if (typeof menu === 'undefined') {
		button.style.display = 'none';
		return;
	}

	// Toggle navigation; add or remove a class to both the button and the nav element itself; @TODO: swap icons or transform the shadow as well
	button.onclick = function() {
		if (button.className.indexOf( 'active' ) !== -1) {
			button.className = button.className.replace(' active', '');
			nav.className = nav.className.replace(' active', '');
			icon.style.transform = '';
		} else {
			button.className += ' active';
			nav.className += ' active';
			icon.style.transform = 'scaleY(-1)';
		}
	};
})(jQuery);
