// ==== CORE HEADER ==== //

// Anything entered here will end up in the header; use this for mission critical stuff
;(function() {

  // Font Face Observer code adapted from https://viget.com/extend/the-font-face-dilemma
  // These definitions should match whatever is set in the theme
  var fontFamilies = {
    'Ubuntu': [
      { weight: 300 }
    , { weight: 400 }
    , { weight: 700 }
    ],
    'Oxygen': [
      { weight: 300 }
    , { weight: 400 }
    , { weight: 700 }
    ]
  };

  // Initialize all observers
  var fontObservers = [];
  Object.keys(fontFamilies).forEach(function(family) {
    fontObservers.push(fontFamilies[family].map(function(config) {
      return new FontFaceObserver(family, config).check();
    }));
  });

  // Add a class to the document when fonts have been loaded
  Promise.all(fontObservers).then(function() {
    document.documentElement.classList.add('wf-active');
  }, function() {
    // Fonts did not load
  });

  // Initialize svg4everybody 2.0.0+
  svg4everybody();

  // Lazysizes options
  window.lazySizesConfig = window.lazySizesConfig || {};
  lazySizesConfig.expand = 900; // How many pixels to expand the viewport for loading purposes
  lazySizesConfig.expFactor = 3; // Triple the expanded viewport when network is idling

})();
