// ==== CORE ==== //

// Anything entered here will end up at the top of `p-core.js`
;(function($){
  $(function(){ // Shortcut to $(document).ready(handler);
    autosize(document.querySelectorAll('textarea')); // Autosizes textareas based on user input
  });
}(jQuery));
