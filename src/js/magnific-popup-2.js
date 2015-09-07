// ==== MAGNIFIC POPUP ==== //

// This completes the module wrapper usually added in the Magnific Popup build process
/* jshint ignore:start */
  _checkInstance();
}));
/* jshint ignore:end */

//
;(function($){
  $(function(){

    // Magnific Popup lightbox settings
    var magnificOptions = {
      disableOn: 500, // Don't load the popup gallery on screens with a viewport width less than this
      delegate: 'figure.wp-caption', // Open the popup by clicking on child elements matching this selector
      overflowY: 'scroll', // Assumes there is a scrollbar; prevents elements from shifting around in Chrome and perhaps other browsers
      showCloseBtn: false,
      type: 'image',
      image: {
        titleSrc: function(item) {
          return item.el.find('figcaption').text();
        },
        tError: '<a href="%url%">This image</a> could not be loaded.' // Error message
      },
      gallery: {
        enabled: true,
        //preload: [0,2], // Lazy loading options
        arrowMarkup: '<div class="mfp-arrow mfp-arrow-%dir%">' + svgIcon( 'typ-arrow-%dir%-thick', '%title%', '%title%' ) + '</div>',
        tPrev: 'Previous', // title for left button
        tNext: 'Next', // title for right button
        tCounter: '<span class="mfp-counter">%curr% of %total%</span>' // markup of counter
      },
      callbacks: {
        open: function() {
          $('#page').removeClass('blur-out');
          $('#page').addClass('blur');
        },
        close: function() {
          $('#page').addClass('blur-out');
          $('#page').removeClass('blur');
        },
        resize: function() {
          // @TODO: display a different image size from `srcset` attribute as needed
        },
        imageLoadComplete: function() {
          // Fires when image in current popup finished loading; use this to remove spinner
        },
        elementParse: function(item) {

          // Use `srcset` to determine what image to load based on current viewport width; @TODO: retina support
          item.srcset = item.el.find('img').attr('srcset');
          if ( item.srcset ) {

            // Set target width; https://stackoverflow.com/questions/1248081/get-the-browser-viewport-dimensions-with-javascript
            var targetWidth = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);

            // Parse `srcset` attribute; https://github.com/albell/parse-srcset
            var imagesAvailable = parseSrcset( item.srcset );

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
    $('.entry-content').magnificPopup(magnificOptions);

  });
}(jQuery));
