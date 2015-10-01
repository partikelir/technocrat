// ==== MAGNIFIC POPUP LOADER ==== //

;(function($){
  $(function(){

    // Initialize HTML5-History-API polyfill with this single line
    var location = window.history.location || window.location;

    // Save a copy of the initial URL for future use
    var magnificInitURL = location.href;

    // Magnific options
    var magnificOptions = {
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
          magnificPendrell.spinner();
        },
        close: function() {
          magnificPendrell.blurClose();
          magnificPendrell.popstateClose();
        },
        resize: function() {
          // @TODO: display a different image size from `srcset` attribute as needed
          // @TODO: debounce this function
          //magnificPendrell.srcSelect(this.currItem);
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
          } else if (typeof magnificInitURL !== 'undefined') {
            url = magnificInitURL;
          }

          // Update address bar
          if (url !== location.href) {
            history.pushState({ magnific: 1, index: this.currItem.index }, null, url);
          }
        },
        elementParse: function(item) {
          magnificPendrell.srcSelect(item);
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

      // Restore the original base URL when closing the popup
      popstateClose: function() {
        history.pushState(null, null, magnificInitURL);
      },

      // Popstate handler; mainly used to move to the appropriate image when the back button is pressed
      popstateHandler: function(state) {
        $.magnificPopup.instance.goTo(state.index);
      },

      // Create spinner and invoke spin.js (if it exists); reference: https://fgnass.github.io/spin.js/
      spinner: function() {
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
      srcSelect: function(item) {
        var srcset = item.el.find('img').attr('srcset');
        if (srcset.length) {
          item.src = this.srcsetHandler(srcset); // Pick a source from the `srcset` attribute; @TODO: retina support?
        } else if (typeof item.src === undefined || item.src === '') {
          item.src = item.el.find('img').attr('src'); // Fallback on the main `src` attribute for the image if no `srcset` exists
        }
      },

      // Magnific Popup `srcset` handler
      srcsetHandler: function(srcset) {

        // Initialize the source URL to return
        var src = null;

        // Set target width; https://stackoverflow.com/questions/1248081/get-the-browser-viewport-dimensions-with-javascript
        var targetWidth   = Math.max(document.documentElement.clientWidth, window.innerWidth || 0),
            targetHeight  = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);

        // Parse `srcset` attribute; https://github.com/albell/parse-srcset
        var srcAvailable = parseSrcset( srcset ),
            pattern  = /-(\d+)x(\d+)/, // Matches standard WordPress image dimensions in resized image filenames
            fudge    = 1.15; // Fudge factor

        // Sort backwards from smallest to largest and cycle through to find an appropriate image choice
        srcAvailable.sort( function(a, b) {
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
            src = a.url; // Last one standing wins
          }
        });

        // Return whatever we've got
        return src;
      }
    };

    // Handle browser back button action from within Magnific Popup
    $(window).on('popstate', function(e) {
      var state = e.originalEvent.state || null;
      if (state !== null && state.hasOwnProperty('magnific')) {
        magnificPendrell.popstateHandler(state);
      } else {
        $.magnificPopup.instance.close(); // Attempt to close the popup (will fail gracefully if none was instantiated)
      }
    });

    // Engage
    $('.entry-content').magnificPopup(magnificOptions);
  });
}(jQuery));
