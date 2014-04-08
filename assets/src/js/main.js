/* Pendrell custom functions */

// Remap jQuery to $
jQuery(document).ready(function($) {

  // Search Term Highlighter: http://weblogtoolscollection.com/archives/2009/04/10/how-to-highlight-search-terms-with-jquery/
  $.fn.extend({
    highlight: function(search, insensitive, searchClass){
    var regex = new RegExp("(<[^>]*>)|(\\b"+ search.replace(/([-.*+?^${}()|[\]\/\\])/g,"\\$1") +")", insensitive ? "ig" : "g");
      return this.html(this.html().replace(regex, function(a, b, c){
        return (a.charAt(0) === "<") ? a : "<mark class=\""+ searchClass +"\">" + c + "</mark>";
      }));
    }
  });
  if (typeof(pendrellSearchQuery) !== 'undefined') {
    $('#content > article').highlight(pendrellSearchQuery, 1, 'search-term');
  }

});
