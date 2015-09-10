// ==== CORE ==== //

// Anything entered here will end up at the bottom of `p-core.js`
;(function($){
  $(function(){

    // Autosizes textareas based on user input
    autosize(document.querySelectorAll('textarea'));

    // Humanize dates
    $.timeago.settings.cutoff = 1209600000; // Two weeks in milliseconds; don't alter time elements older than this
    $('time').timeago();

    // Allows for select menus to be styled somewhat sanely
    $('select').selectric({
      arrowButtonMarkup: svgIcon( 'awe-sort' )
    , customClass: { prefix: 's3c' }
    });
  });
}(jQuery));
