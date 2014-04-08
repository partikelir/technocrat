// Remap jQuery to $
jQuery(document).ready(function($) {

  // Better Pull Quotes: http://css-tricks.com/better-pull-quotes/
  $('span.pullquote').each(function(index){
      var $parentParagraph = $(this).parent('p');
      $parentParagraph.css('position', 'relative');
      $(this).clone()
      .addClass('pulledquote')
      // Doesn't work: .replaceWith('<aside class="pulledquote">' + $(this).text() + '</aside>')
      .prependTo($parentParagraph);
    });
  $('span.pullquoteleft').each(function(index){
      var $parentParagraph = $(this).parent('p');
      $parentParagraph.css('position', 'relative');
      $(this).clone()
      .replaceWith('<aside class="pulledquoteleft">' + $(this).text() + '</aside>')
      .prependTo($parentParagraph);
    });

});