// ==== CORE ==== //

// Anything entered here will end up at the bottom of `p-core.js`
;(function($){
  $(function(){

    // Initialize svg4everybody 2.0.0+
    svg4everybody();

    // Autosizes textareas based on user input
    autosize(document.querySelectorAll('textarea'));

    // Live updating timestamps; the cutoff is set to 3 days and the rest is handled server-side
    if ( $.timeago ) {
      $.timeago.settings.cutoff = 259200000;
      $('time').timeago();
    }

    // Allows for select menus to be styled somewhat sanely
    $('select').selectric({
      arrowButtonMarkup: svgIcon( 'awe-sort' )
    , customClass: { prefix: 's3c' }
    });
  });
}(jQuery));
