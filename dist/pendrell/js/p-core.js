/*! svg4everybody v1.0.0 | github.com/jonathantneal/svg4everybody */
(function (document, uses, requestAnimationFrame, CACHE, IE9TO11) {
	function embed(svg, g) {
		if (g) {
			var
			viewBox = g.getAttribute('viewBox'),
			fragment = document.createDocumentFragment(),
			clone = g.cloneNode(true);

			if (viewBox) {
				svg.setAttribute('viewBox', viewBox);
			}

			while (clone.childNodes.length) {
				fragment.appendChild(clone.childNodes[0]);
			}

			svg.appendChild(fragment);
		}
	}

	function onload() {
		var xhr = this, x = document.createElement('x'), s = xhr.s;

		x.innerHTML = xhr.responseText;

		xhr.onload = function () {
			s.splice(0).map(function (array) {
				embed(array[0], x.querySelector('#' + array[1].replace(/(\W)/g, '\\$1')));
			});
		};

		xhr.onload();
	}

	function onframe() {
		var use;

		while ((use = uses[0])) {
			var
			svg = use.parentNode,
			url = use.getAttribute('xlink:href').split('#'),
			url_root = url[0],
			url_hash = url[1];

			svg.removeChild(use);

			if (url_root.length) {
				var xhr = CACHE[url_root] = CACHE[url_root] || new XMLHttpRequest();

				if (!xhr.s) {
					xhr.s = [];

					xhr.open('GET', url_root);

					xhr.onload = onload;

					xhr.send();
				}

				xhr.s.push([svg, url_hash]);

				if (xhr.readyState === 4) {
					xhr.onload();
				}

			} else {
				embed(svg, document.getElementById(url_hash));
			}
		}

		requestAnimationFrame(onframe);
	}

	if (IE9TO11) {
		onframe();
	}
})(
	document,
	document.getElementsByTagName('use'),
	window.requestAnimationFrame || window.setTimeout,
	{},
	/Trident\/[567]\b/.test(navigator.userAgent) || (navigator.userAgent.match(/AppleWebKit\/(\d+)/) || [])[1] < 537
);

// ==== ICONIZE ==== //

// Replicates most of the functionality of `pendrell_icon` in `src/modules/icons.php`
;(function($){
  'use strict';

  // Inject SVG `use` markup to display an icon
  $.fn.iconize = function(icon, title, desc) {
    var $this = $(this), aria, set, url;

    // Check for title and description and wrap them both in the appropriate markup
    if (typeof title !== 'undefined') {
      title = '<title id="title">'+title+'</title>';
    }
    if (typeof desc !== 'undefined') {
      desc = '<desc id="desc">'+desc+'</desc>';
    }

    // Check for title and description and generate ARIA attributes as needed
    if (typeof title !== 'undefined' && typeof desc !== 'undefined') {
      aria = ' aria-labelledby="title desc"';
    } else if (typeof title !== 'undefined') {
      aria = ' aria-labelledby="title"';
    } else if (typeof desc !== 'undefined') {
      aria = ' aria-labelledby="desc"';
    }

    // Add a class matching the icon set prefix (anything before the hyphen)
    if ( icon.substr('-') ) {
      set = icon.split('-');
      set = ' icon-'+set[0];
    }

    // Use an external icon sheet if a URL has been defined
    if (typeof pendrellVars.iconsUrl !== 'undefined') {
      url = pendrellVars.iconsUrl;
    }

    // Put it all together
    $this.prepend('<svg class="icon'+set+' icon-'+icon+'"'+aria+'>'+title+desc+'<use xlink:href="'+url+'#i-'+icon+'"></use></svg>');

    return this;
  };
}(jQuery));

// ==== NAVIGATION ==== //

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

// Anything entered here will end up at the top of `p-core.js`
;(function($){
  $(function(){ // Shortcut to $(document).ready(handler);

    // Iconize: an example
    //$('#archive-header').iconize('ion-search', 'Testing', 'description');
  });
}(jQuery));
