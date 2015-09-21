// ==== MAGNIFIC POPUP LOADER ==== //

;(function($){
  $(function(){

    // Magnific options
    var magnificOptions = {
      disableOn: 500, // Don't load the popup gallery on screens with a viewport width less than this
      delegate: 'figure.wp-caption', // Open the popup by clicking on child elements matching this selector
      key: 'mfp-main',
      overflowY: 'scroll', // Assumes there is a scrollbar; prevents elements from shifting around in Chrome and perhaps other browsers
      closeBtnInside: false,
      showCloseBtn: false,
      tLoading: '', // Empties "loading" text
      type: 'image',
      image: {
        titleSrc: function(item) {
          return item.el.find('figcaption').text();
        },
        tError: '<a href="%url%">This image</a> could not be loaded.' // Error message
      },
      gallery: {
        enabled: true,
        arrowMarkup: '<div class="mfp-arrow mfp-arrow-%dir%">' + svgIcon( 'typ-arrow-%dir%-thick', '%title%', '%title%' ) + '</div>',
        navigateByImgClick: false,
        preload: [0,4], // Lazy loading options: # of previous / next images
        tCounter: '%curr%/%total%',
        tNext: 'Next',
        tPrev: 'Previous'
      },
      callbacks: {
        open: function() {
          $('#page').removeClass('blur-out');
          $('#page').addClass('blur');

          // Invoke spin.js (if it exists); reference: https://fgnass.github.io/spin.js/
          if ( $.isFunction(window.Spinner) ) {
            $('.mfp-preloader').append('<div id="spinner" style="position: relative;"></div>');
            $('#spinner').spin({
              lines:  25
            , length: 0
            , width:  4
            , radius: 25
            , scale:  3
            , speed:  1.5
            , trail:  40
            });
          }
        },
        close: function() {
          $('#page').addClass('blur-out');
          $('#page').removeClass('blur');
        },
        resize: function() {
          // @TODO: display a different image size from `srcset` attribute as needed
        },
        imageLoadComplete: function() {

          // Get the image and link (if available)
          var img   = $('.mfp-img'),
              link  = this.currItem.el.find('a:first').clone().empty();

          // Remove any existing link
          if ( img.parent().is('a') ) {
            img.unwrap();
          }

          // Add the link from the body of the post and update the URL
          if (typeof link !== 'undefined' && link.length) {
            img.wrap(link);
          }
        },
        elementParse: function(item) {

          // Use `srcset` to determine what image to load based on current viewport width; @TODO: retina support?
          var srcset = item.el.find('img').attr('srcset');
          if (srcset.length) {

            // Set target width; https://stackoverflow.com/questions/1248081/get-the-browser-viewport-dimensions-with-javascript
            var targetWidth = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);

            // Parse `srcset` attribute; https://github.com/albell/parse-srcset
            var imagesAvailable = parseSrcset( srcset );

            // Sort backwards from smallest to largest and cycle through to find an appropriate image choice
            imagesAvailable.sort( function(a, b) {
              return a.w - b.w;
            }).forEach( function(a) {
              if ( a.w < ( targetWidth * 1.2 ) ) { // Fudge factor
                item.src = a.url; // Last one standing wins
              }
            });
          }

          // Fallback on the main `src` attribute for the image if no `srcset` exists
          if ( typeof item.src === undefined || item.src === '' ) {
            item.src = item.el.find('img').attr('src');
          }
        }
      }
    };

    // Engage
    $('.entry-content').magnificPopup(magnificOptions);
  });
}(jQuery));
