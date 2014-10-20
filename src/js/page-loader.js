// ==== PAGE LOADER ==== //

// Based on Ajaxinate: https://github.com/synapticism/ajaxinate
// With some help from: http://www.problogdesign.com/wordpress/load-next-wordpress-posts-with-ajax/

// Global namespace object; inspiration for the design of this via Ryan Florence: http://ryanflorence.com/authoring-jquery-plugins-with-object-oriented-javascript/
var PG8 = {};

;(function($, document, window, undefined){
  'use strict';

  // Exit early if WordPress didn't pass us anything
  if (typeof pendrellVars === 'undefined' || pendrellVars.PG8 === '') {
    return;
  }

  // Initialize HTML5-History-API polyfill with this single line
  var location = window.history.location || window.location;

  // Constructor function
  var PageLoader = this.PageLoader = function(opts){
    this.thisPage = parseInt(pendrellVars.PG8.startPage);
    this.thisLink = location.href;
    this.nextPage = this.thisPage + 1;
    this.nextLink = pendrellVars.PG8.nextLink;
    this.maxPages = parseInt(pendrellVars.PG8.maxPages);
    this.opts     = $.extend({}, $.fn.pageloader.defaults, opts);
    this.content  = $(this.opts.contentSel);

    // Initialize page loader only if there are pages to load
    if (this.nextPage <= this.maxPages) {
      this.init();
    }
  };



  // Prototype functionality
  PageLoader.prototype = {
    init: function(){

      // Wrap all the children of the main element in a way that is consistent with how content is loaded
      this.content.children().wrapAll('<div id="content-page-'+this.thisPage+'" class="clear" data-href="'+this.thisLink+'"></div>');

      // Create the first (p)lace)holder div that content will be loaded into
      this.holder();

      // Bind event handlers
      this.handler();

      // Initialize spinner
      this.spinner();
    },



    // Create a placeholder; abstracted into a function for DRYness
    holder: function(){
      this.content.append('<div id="content-page-' + this.nextPage + '" class="clear" data-href="'+this.nextLink+'"></div>');
    }, // end holder()



    // Event handlers
    handler: function(){
      var
        self    = this,
        $window = $(window);

      // Bind to click events on the body element to ensure compatibility with other forms of DOM manipulation we may be doing
      $('body').on('click', self.opts.nextSel, function(event){

        // Are there more posts to load? This has to be checked again as nextPage increments
        if (self.nextPage <= self.maxPages) {

          // Cancel page request
          event.preventDefault();

          // Invoke spinner
          $(this).parents('nav:first').before($('#spinner').show());

          // Load content
          self.loader(self.nextPage, self.nextLink);
        }
      }); // end .on('click')



      // Watch scroll position and change URL accordingly
      $window.on('scroll', this.content, function(event){

        // Clear previously set timer
        clearTimeout($.data(this, 'pushTimer'));
        clearTimeout($.data(this, 'infinTimer'));

        // Manage push state based on scroll position
        $.data(this, 'pushTimer', setTimeout(function() {

          // Setup some useful variables including info about the top-most page
          var
            firstPage = self.content.children(':first'),
            firstTop  = firstPage.offset().top,
            firstLink = firstPage.data('href'),
            winTop    = $window.scrollTop();

          // Push state if the top of the window is above the first page
          if ( winTop <= firstTop ) {
            self.pusher(firstLink);
          } else {

            // Monitor the children of the main content selector; should be a bunch of divs representing each page of content
            self.content.children().each(function(){
              var
                $this   = $(this),
                top     = $this.offset().top - self.opts.scrollOffset,
                bottom  = $this.outerHeight()+top;

              // Push state if the top of the window falls into the range of a given page
              if ( top <= winTop && winTop < bottom ) {
                self.pusher($this.data('href'));
              }
            }); // end each()
          } // end if
        }, self.opts.pushDelay)); // end $.data()

        // Infinite scroll, a lazy implementation
        $.data(this, 'infinTimer', setTimeout(function() {
          var
            $document       = $(document),
            scrollHeight    = $document.height(),
            scrollPosition  = $window.height() + $window.scrollTop(),
            scrollDiff      = scrollHeight - scrollPosition;

          // Trigger a click near the bottom of the window... but not at the -absolute- bottom (this way we can still reach the footer)
          if ( scrollDiff < self.opts.infinOffset && scrollDiff >= 1 ) {
            $(self.opts.nextSel).trigger('click');
          }
        }, self.opts.infinDelay)); // end $.data()
      }); // end $window.on('scroll')
    }, // end handler()



    // Conditionally initialize spinner div; degrades gracefully if spin.js not found
    spinner: function(){
      if ( $.isFunction(window.Spinner) ) {
        this.content.after('<div id="spinner" style="position: relative;"></div>');
        $('#spinner').spin(this.opts.spinOpts).hide();
      }
    }, // end spinner()



    pusher: function(url){
      if (typeof url !== 'undefined' && url !== location.href) {
        history.pushState(null,null,url);
      }
    }, // end pusher()



    // Page loader
    loader: function(page, link){
      var self = this;

      // Load content into the appropriate page loader
      $('#content-page-'+page).load(link+' '+self.opts.contentSel+' > *', function() {

        // Cache the next selector
        var $navLink = $(self.opts.nextSel);

        // Update page number and nextLink
        self.thisPage = page;
        self.thisLink = link;
        self.nextPage = page + 1;
        self.nextLink = link.replace(/\/page\/[0-9]?/, '/page/'+self.nextPage);

        // @TODO: load scripts necessary to display content on new pages e.g. MediaElement.js
        // Follow the link for an example: https://github.com/Automattic/jetpack/blob/master/modules/infinite-scroll/infinity.js

        // Change the URL
        self.pusher(self.thisLink);

        // Create another placeholder
        self.holder();

        // Navigation link handling
        if (self.nextPage > self.maxPages) {
          $navLink.remove(); // End of the line
        } else {
          $('a', $navLink).attr('href', self.nextLink); // Update the navigation links (for right-click etc.)
        }

        // Hide spinner
        $('#spinner').hide();

        self.loaded();

        // Scroll to the appropriate location
        self.scroll();

        // Update analytics with relative URL on load (not on scroll)
        self.analytics('/'+location.href.replace(self.root(), ''));
      });
    }, // end loader()



    // Emit the DOMContentLoaded event (for compatibility with Prism.js and other scripts)
    loaded: function(){
      var loaded = document.createEvent("Event");
      loaded.initEvent("DOMContentLoaded", true, true);
      window.document.dispatchEvent(loaded);
    },



    // Scroll to the top of the new content container
    scroll: function(){
      var top = $('#content-page-'+this.thisPage).children(':first').offset().top - this.opts.scrollOffset;
      $('body, html').animate({ scrollTop: top }, this.opts.scrollDelay, "swing");
    }, // end scroll()



    // Update Google Analytics on content load, not on scroll
    analytics: function(url){
      // Inform Google Analytics of the change; compatible with the new Universal Analytics script
      if ( typeof window.ga !== 'undefined' ) {
        window.ga('send', 'pageview', url);
      } else if ( typeof window._gaq !== 'undefined' ) {
        window._gaq.push(['_trackPageview', url]); // Legacy analytics; ref: https://github.com/browserstate/ajaxify/pull/25
      }
    }, // end analytics()



    // Utility function to get the root URL with trailing slash e.g. http(s)://yourdomain.com/
    root: function(){
      var
        port = document.location.port ? ':' + document.location.port : '',
        url = document.location.protocol + '//' + (document.location.hostname || document.location.host) + port + '/';
      return url;
    } // end root()
  };



  $.fn.pageloader = function (opts){
    return this.each(function(){
      if (!$.data(this, 'PG8_pageloader')) {
        $.data(this, 'PG8_pageloader', new PageLoader(opts));
      }
    });
  };



  // Extensible default settings
  $.fn.pageloader.defaults = {
    contentSel:    'main'       // The content selector
  , nextSel:       '.nav-next'  // Selector for the "next" navigation link
  , scrollDelay:   300          // Smooth scrolling delay
  , scrollOffset:  30           // To account for margins and such
  , pushDelay:     250          // How long to wait on scroll before trying to update history
  , infinDelay:    600          // How long to wait before pulling new content automatically
  , infinOffset:   250          // How close to the bottom of the window to trigger infinite scrolling
  , spinOpts: {                 // spin.js options; reference: https://fgnass.github.io/spin.js/
      lines:  25
    , length: 0
    , width:  4
    , radius: 25
    , speed:  1.5
    , trail:  40
    , top:    '15px'
    }
  };



  // Instantiate the plugin
  $(document.body).pageloader();

}).apply(PG8, [jQuery, document, window]);
