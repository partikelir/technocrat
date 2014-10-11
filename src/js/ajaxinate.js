// ==== AJAXINATE ==== //

// Selective content loading for WordPress: https://github.com/synapticism/ajaxinate

;(function($){
  $(function(){ // Shortcut to $(document).ready(handler);
    $(document.body).ajaxinate({
      contentSel: '#content-wrapper',
      menuSel: '.menu-header'
    }); // To set an option include an object literal e.g. `ajaxinate({ searchBase: 'search' });`
  });
}(jQuery));
