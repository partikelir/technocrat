// ==== AJAXIFY WORDPRESS ==== //

// This script is heavily adapted from:
// - The original Ajaxify v1.0.1 gist: https://github.com/browserstate/ajaxify
// - Ajaxify WordPress Site v1.5.4: https://github.com/wp-plugins/ajaxify-wordpress-site

// With guidance from:
// - http://blog.fraser-hart.co.uk/how-to-ajaxify-your-website-with-ajaxify-and-history-js/

// Major architectural changes:
// - Removes history.js (buggy) and jQuery.scrollTo
// - Requires HTML5-History-API: https://github.com/devote/HTML5-History-API

// Features:
// - Improved comments to give you more of an idea of what's going on
// - All user-configurable settings are near the top of the file; no options screen bloat
// - Integrates spin.js to dispense with loading graphics
// - Extensions whitelist; won't Ajaxify links to downloadable files
// - Only updates changed scripts in the body and outside of the content selector
// - Inline scripts outside of the content selector will be added as needed (might be a bit buggy)
// - Some meta and link elements in the header now change (not for SEO, just for user bookmarking and such)
// - Uses jQuery native scrollTop instead of jQuery.scrollTo plugin; scrolls only when below the top of the content selector
// - Smooth fade in and fade out animations
// - Compatible with the new Universal Analytics script from Google

// Caveats:
// - This is my first halfway serious jQuery/Ajax project; there will be bugs
// - If you see something worth changing please contribute!

// To do:
// - Handle scripts in the head



// Edit these variables to customize this script; there should be no need to touch anything else
var ajaxifyOptions = {
    contentSelector:      '#content-wrapper'
  , menuSelector:         '.menu-header' // The selector for the entire menu
  , contentFadeOut:       600
  , contentFadeIn:        120
  , scrollDuration:       300

  // spin.js options; reference: https://fgnass.github.io/spin.js/
  , spinnerFadeOut:       300
  , spinnerOptions: {
      lines: 25
    , length: 0
    , width: 4
    , radius: 25
    , speed: 1.5
    , trail: 40
    , top: '25%'
  }

  // Whitelisted extensions
  , extensionsFilter:     /\.(jpg|jpeg|png|gif|bmp|pdf|mp3|flac|wav|rar|zip)$/i

  // Handle various WordPress-related edge cases
  , wordPress:            true
};



// Ready...
(function(window,undefined){

  // Prepare our base variables
  var
    $         = window.jQuery
  , document  = window.document
  , location  = window.history.location || window.location;

  // Check to see if the browser supports the HTML5 history API; if not, let the site load normally
  if ( !(window.history && history.pushState) ) {
    return false;
  }

  // Wait for document
  $(function(){
    var
      $window         = $(window)
    , $body           = $(document.body)
    , contentSelector = ajaxifyOptions.contentSelector
    , menuSelector    = ajaxifyOptions.menuSelector
    , $content        = $(contentSelector).filter(':first')
    , rootUrl         = (function(){
      var
        port = document.location.port ? ':' + document.location.port : ''
      , root = document.location.protocol + '//' + ( document.location.hostname || document.location.host ) + port + '/';
      return root;
    }());

    // Ensure content isn't empty; if it is, use the current document contents
    if ( $content.length === 0 ) {
      $content = $body;
    }

    // A selector to identify internal links
    $.expr[':'].internal = function(obj,index,meta,stack){
      var
        $this = $(obj)
      , url = ( $this.attr('href') || '' )
      , isInternalLink;

      // Validate the link to ensure it matches the current root URL
      isInternalLink = url.substring(0,rootUrl.length) === rootUrl || url.indexOf(':') === -1;

      return isInternalLink;
    };

    // Ajaxify helper; trigger page loading for matched links
    $.fn.ajaxify = function(){
      var $this = $(this);

      // Run the script when clicking on certain links; WP-specific exceptions have been added here
      $this.find('a:internal:not(.no-ajaxy, [href^="#"], [href*="wp-login"], [href*="wp-admin"])')

      // Filter URLs that end in specified file extensions; the use of the not operator only returns links that aren't to files
      .filter(function() {
        return !this.href.match(ajaxifyOptions.extensionsFilter);
      })
      .on('click', function(event){
        var
          $this = $(this),
          url   = $this.attr('href'),
          title = $this.attr('title') || null;

        // Continue as normal for command and control key clicks to open in a new tab and such; reference: https://github.com/browserstate/ajaxify/pull/15/files
        if ( event.which === 2 || event.metaKey || event.ctrlKey ) {
          return true;
        }

        // If url is a hash or not set, just return; @TODO: test this
        if ( !url || url[0] === '#') {
          return true;
        }

        // Push state, load new content via AJAX, and prevent the default action with `return false`
        history.pushState(null,title,url);
        ajaxifyLoader();
        return false;
      });

      // Chain this function
      return $this;
    };

    // This function dynamically loads content from the requested page
    function ajaxifyLoader(){
      var
        url = location.href
      , relativeUrl = url.replace(rootUrl, '');

      // HTML helper; easily select elements from the page to load by selecting #document-html, #document-head, and #document-body
      var documentHtml = function(html){
        var result = String(html)
          .replace(/<\!DOCTYPE[^>]*>/i, '')
          .replace(/<(html|head|body)([\s\>])/gi,'<div id="document-$1"$2')
          .replace(/<\/(html|head|body)\>/gi,'</div>')
        ;
        return $.trim(result);
      };

      // Set loading class
      $body.addClass('loading');

      // Fade out existing content; use animate to preserve the element's height (avoids scrollbar flicker)
      $content.animate({opacity:0}, ajaxifyOptions.contentFadeOut);

      // Spin spin sugar; degrades gracefully if spin.js not found
      if ( $.isFunction( window.Spinner ) ) {
        $content.before('<div id="spinner" style="position: fixed; height: 100%; width: 100%;"></div>');
        $('#spinner').spin(ajaxifyOptions.spinnerOptions);
      }

      // AJAX page request; fetch the content we need
      $.ajax({
        url: url,
        success: function(data,textStatus,jqXHR){
          var
            $data         = $(documentHtml(data))
          , $dataBody     = $data.find('#document-body:first')
          , $dataContent  = $dataBody.find(contentSelector).filter(':first')
          , $dataMenu     = $dataBody.find(menuSelector)
          , $scripts
          , contentHtml;

          // Add classes to body; makes WordPress pages render smoothly
          $('body').attr( 'class', $dataBody.attr('class') );

          // Fetch body scripts not in the content (which will be replaced anyhow)
          $scripts = $dataBody.find('script').not(contentSelector+' script');
          if ( $scripts.length ) { $scripts.detach(); }

          // Fetch the content
          contentHtml = $dataContent.html() || $data.html();

          // If no content is received for any reason let's forward the user on to the target URL
          if ( !contentHtml ) {
            document.location.href = url;
            return false;
          }

          // Update the content and ajaxify the links contained within
          $content.stop(true, true);
          $content.html(contentHtml).ajaxify();

          // Fade the content back in
          $content.animate({opacity:1,visibility:"visible"}, ajaxifyOptions.contentFadeIn);

          // Scroll to the top of the content container when below the fold
          if ( $(window).scrollTop() > $(contentSelector).offset().top) {
            $('html, body').animate({ scrollTop: $(contentSelector).offset().top }, ajaxifyOptions.scrollDuration, "swing");
          }

          // Update the title
          document.title = $data.find('title:first').text();
          try {
            document.getElementsByTagName('title')[0].innerHTML = document.title.replace('<','&lt;').replace('>','&gt;').replace(' & ',' &amp; ');
          }
          catch ( Exception ) { }

          // Update the body scripts outside of the content area; we're assuming that header scripts don't change
          $scripts.each(function(){

            // If the current script is empty we assume it must be inline
            if ( '' !== $(this).html() ) {

              // Save the currect script contents for use in the loop to follow
              var inlineScript = $(this).html(),
                  notNewScript = false;

              // Check existing scripts to see if they contain the same stuff
              $.each( $('body').find('script:not(:empty)').not(contentSelector+' script'), function(){
                if ( $(this).html() === inlineScript ) { notNewScript = true; }
              });

              // Paste new stuff in if it isn't found in the current DOM; this is quite a kludge
              if ( notNewScript === false ) {
                $('body').find('script:not(:empty)').not(contentSelector+' script').filter(':first').before( '<script>'+$(this).html()+'</script>' );
              }

            } else {

              // Test for the presence of each body script; if it isn't found load it and add it to the DOM
              if ( !$('body').find('script[src*="' + $(this).attr('src') + '"]').length ) {
                $.getScript( $(this).attr('src') );
                var newScript = document.createElement('script');
                newScript.src = $(this).attr('src'); // No need for type="text/javascript" in HTML5
                $('body')[0].appendChild( newScript );
              }
            }
          });

          // Swap the menus after scrolling and ajaxify the menu links themselves
          $(menuSelector).html($dataMenu.html()).ajaxify();

          // Change the meta description tag; presumably this is used in bookmarking and such
          if ( $data.find('meta[name="description"]').length ) {
            if ( !$('meta[name="description"]').length ) {
              $('head').append( $data.find('meta[name="description"]') );
            } else {
              $('meta[name="description"]').attr('content', $data.find('meta[name="description"]').attr('content') );
            }
          }

          // Kill some meta tags that change from page to page; they aren't important enough to swap out
          $('meta[property], meta[name^="twitter"]').remove();

          // Set various link properties
          $.each( [
            'link[rel="prev"]'
          , 'link[rel="next"]'
          , 'link[rel="canonical"]'
          , 'link[rel="shortlink"]'
          , 'link[rel="author"]'
          ], function( i, val ){

            // Link element not found in new page; remove it from current page if it exists
            if ( !$data.find(val).length ) {
              $(val).remove();

            // Link element found in new page
            } else {

              // Link element isn't on current page; add it to the header
              if ( !$(val).length ) {
                $('head').append( $data.find(val) );

              // Link element IS on current page; update attributes
              } else {
                if ( $data.find(val).attr('href') ) {
                  $(val).attr('href', $data.find(val).attr('href') );
                }
                if ( $data.find(val).attr('title') ) {
                  $(val).attr('title', $data.find(val).attr('title') );
                }
              }
            }
          });

          // Clean up
          $body.removeClass('loading');
          $('#spinner').fadeOut(ajaxifyOptions.spinnerFadeOut, function(){ $(this).remove(); });

          // Inform Google Analytics of the change; compatible with the new Universal Analytics script; @TODO: test this in a live environment
          if ( typeof window.ga !== 'undefined' ) {
            window.ga('send', 'pageview', relativeUrl);
          } else if ( typeof window._gaq !== 'undefined' ) {
            window._gaq.push(['_trackPageview', relativeUrl]); // Legacy analytics; ref: https://github.com/browserstate/ajaxify/pull/25
          }

        },

        // Fallback functionality
        error: function(jqXHR, textStatus, errorThrown){
          document.location.href = url;
          return false;
        }
      }); // end AJAX page request
    }

    // Hook into state changes i.e. when the user goes backwards or forwards in the browser
    $window.on('popstate', function(e) {
      ajaxifyLoader();
    }); // end onStateChange

    // Ajaxify our internal links
    $body.ajaxify();

  }); // end onDomLoad

})(window);
