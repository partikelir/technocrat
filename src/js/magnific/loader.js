// ==== MAGNIFIC POPUP LOADER ==== //

;(function($){
  $(function(){

    // Initialize HTML5-History-API polyfill with this single line
    var location = window.history.location || window.location;

    // Magnific options
    var magnificOptions = {
      baseURL: location.href, // Save this for future use
      disableOn: 500, // Don't load the popup gallery on screens with a viewport width less than this
      delegate: 'figure.wp-caption', // Open the popup by clicking on child elements matching this selector
      key: 'mfp-main',
      overflowY: 'scroll', // Assumes there is a scrollbar; prevents elements from shifting around in Chrome and perhaps other browsers
      closeBtnInside: false,
      showCloseBtn: false,
      tLoading: '', // Empties "loading" text; Magnific Popup default preloader is text-based but we're using a spinner
      type: 'image',
      image: {
        titleSrc: function(item) {
          return item.el.find('figcaption').text();
        },
        tError: '<a href="%url%">This image</a> could not be loaded.' // Error message; @TODO: error handling
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
          magnificPendrell.blurOpen();
          magnificPendrell.invokeSpinner();
        },
        close: function() {
          magnificPendrell.blurClose();

          // Restore the original base URL
          if (typeof this.st.baseURL !== 'undefined' && this.st.baseURL !== location.href) {
            history.pushState(null,null,this.st.baseURL);
          }
        },
        resize: function() {
          // @TODO: display a different image size from `srcset` attribute as needed
          //magnificPendrell.imageSelect(this.currItem);
        },
        imageLoadComplete: function() {

          // Get the image and link (if available)
          var img   = $('.mfp-img'),
              link  = this.currItem.el.find('a:first').clone().empty(),
              url   = '';

          // Remove any existing link
          if ( img.parent().is('a') ) {
            img.unwrap();
          }

          // Add the link from the body of the post and update the URL
          if (typeof link !== 'undefined' && link.length) {
            img.wrap(link);
            url = link.attr('href');
          } else if (typeof this.st.baseURL !== 'undefined') {
            url = this.st.baseURL;
          }

          // Update address bar
          if (url !== location.href) {
            history.pushState(null,null,url);
          }
        },
        elementParse: function(item) {
          magnificPendrell.imageSelect(item);
        }
      }
    };

    // Custom functions for Pendrell's Magnific Popup integration
    var magnificPendrell = {

      // Blur
      blurOpen: function() {
        $('#page').removeClass('blur-out');
        $('#page').addClass('blur');
      },

      // Unblur
      blurClose: function() {
        $('#page').addClass('blur-out');
        $('#page').removeClass('blur');
      },

      // Create spinner and invoke spin.js (if it exists); reference: https://fgnass.github.io/spin.js/
      invokeSpinner: function() {
        $('.mfp-container').append('<div class="mfp-spinner"></div>');
        if ( $.isFunction(window.Spinner) ) {
          $('.mfp-spinner').append('<div id="spinner" style="position: relative;"></div>');
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

      // Magnific-based image selector; requires an item object
      imageSelect: function(item) {
        var srcset = item.el.find('img').attr('srcset');
        if (srcset.length) {
          item.src = this.srcsetHandler(srcset); // Pick a source from the `srcset` attribute; @TODO: retina support?
        } else if ( typeof item.src === undefined || item.src === '' ) {
          item.src = item.el.find('img').attr('src'); // Fallback on the main `src` attribute for the image if no `srcset` exists
        }
      },

      // Magnific Popup `srcset` handler
      srcsetHandler: function(srcset) {

        // Initialize the URL to return
        var url = null;

        // Set target width; https://stackoverflow.com/questions/1248081/get-the-browser-viewport-dimensions-with-javascript
        var targetWidth   = Math.max(document.documentElement.clientWidth, window.innerWidth || 0),
            targetHeight  = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);

        // Parse `srcset` attribute; https://github.com/albell/parse-srcset
        var imagesAvailable = parseSrcset( srcset ),
            pattern  = /-(\d+)x(\d+)/, // Matches standard WordPress image dimensions in resized image filenames
            fudge    = 1.15; // Fudge factor

        // Sort backwards from smallest to largest and cycle through to find an appropriate image choice
        imagesAvailable.sort( function(a, b) {
          return a.w - b.w;
        }).forEach( function(a) {
          var filename = a.url.substring(a.url.lastIndexOf('/')+1),
              fragment = null; // Empty this out

          // `srcset` does not contain height but we might be able to pull that from the filename
          if (pattern.test(filename)) {
            fragment = filename.match(pattern);
            a.h = parseInt(fragment[2], 10);
          } else {
            a.h = 0; // Don't test height
          }

          // Make an informed guess about which image to load
          if ( a.w < ( targetWidth * fudge ) && a.h < ( targetHeight * fudge ) ) {
            url = a.url; // Last one standing wins
          }
        });

        // Return whatever we've got
        return url;
      }
    };

    // Return to the base URL when the back button is pressed
    $(window).on('popstate', function() {
      location.href = magnificOptions.baseURL;
    });

    // Engage
    $('.entry-content').magnificPopup(magnificOptions);
  });
}(jQuery));
