// ==== CORE HEADER ==== //

// Anything entered here will end up in the header; use this for mission critical stuff
;(function() {

  // These definitions should match whatever is set in the theme
  var fontObserverHead = new FontFaceObserver('Ubuntu');
  var fontObserverBody = new FontFaceObserver('Oxygen');
  //var fontObserverText = new FontFaceObserver('Oxygen');

  // Wait for fonts to load an add a class to the document; see `/src/scss/config/_fonts.scss` for more
  fontObserverHead.check().then(function() {
    document.documentElement.classList.add('wf-head-active');
  }, function(){});
  fontObserverBody.check().then(function() {
    document.documentElement.classList.add('wf-body-active');
  }, function(){});
  //fontObserverText.check().then(function() {
  //  document.documentElement.classList.add('wf-text-active');
  //}, function(){});

  // Initialize svg4everybody 2.0.0+
  svg4everybody();
})();
