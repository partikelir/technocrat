// ==== WP AJAX PAGE LOADER ==== //

// Invoke the AJAX page loader; this is in its own file as we only load this script where it will be useful
;(function($){
  $(function(){
    $(document.body).ajaxPageLoader({ next: '#nav-below .next-page', prev: '#nav-below .prev-page' });
  });
}(jQuery));
