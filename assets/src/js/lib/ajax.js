// A rudimentary AJAX template, unused as of yet, doing things the WordPress way
jQuery(document).ready(function($) {

  var data = {
    action: 'loader'
  };

  $.post(PendrellAjax.ajaxurl, data, function(response) {
    //alert('Got this from the server: ' + response);
  });
});
