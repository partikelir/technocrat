// ==== CORE ==== //

// Anything entered here will end up at the top of pendrell-core.js

// For compatibility with Ajaxinate be sure to bind events to a DOM element that isn't ever replaced (e.g. `body`)

;(function($){
  $('body').on('click', '.view-options button', function(){
    $(this).toggleClass('view-options-on');
    $('.button-dropdown').toggle(200, 'swing');
  });
}(jQuery));
