// ==== RESPONSIVE MENU ==== //

// This script is adapted from _s
;(function($) {
	var nav 			= document.getElementById('site-navigation'),
			menu 			= document.getElementById('menu-header'),
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

	// Toggle navigation; add or remove a class to both the button and the nav element itself
	button.onclick = function() {
		//$(nav).toggle( 150 ); // Looks nice but it's too sluggish on mobile
		if (button.className.indexOf( 'active' ) !== -1) {
			nav.style.display = 'none';
			button.className = button.className.replace(' active', '');
			icon.style.transform = '';
		} else {
			nav.style.display = 'block';
			button.className += ' active';
			icon.style.transform = 'scaleY(-1)';
		}
	};
})(jQuery);
