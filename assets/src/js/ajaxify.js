// ==== AJAXIFY WORDPRESS ==== //

// Adapted from:
// - The original Ajaxify v1.0.1 gist: https://github.com/browserstate/ajaxify
// - Ajaxify WordPress Site v1.5.4: https://github.com/wp-plugins/ajaxify-wordpress-site

// With guidance from:
// - http://blog.fraser-hart.co.uk/how-to-ajaxify-your-website-with-ajaxify-and-history-js/

// Features:
// - Improved comments
// - All user-configurable settings are near the top of the file; no options screen bloat
// - Integrates spin.js to dispense with loading graphics
// - Won't Ajaxify links to downloadable files
// - Only updates changed scripts in the body and outside of the content selector
// - Inline scripts outside of the content selector will be added as needed (might be a bit buggy)
// - Some meta and link elements in the header now change (not for SEO, just for user bookmarking and such)
// - Uses jQuery native scrollTop instead of jQuery.scrollTo plugin; scrolls only when below the top of the content selector
// - Smooth fade in and fade out animations
// - Works with the new Universal Analytics script from Google

// Caveats:
// - This is my first halfway serious jQuery/Ajax project; there may be some bugs
// - If you see something worth changing please contribute!

// Ready...
(function(window,undefined){

  // Prepare our base variables and configuration settings
  var
    History = window.History
  , $ = window.jQuery
  , document = window.document

  // Edit these variables to customize this script; there should be no need to touch anything else
  , ajaxifyOptions = {
      contentSelector:      '#content-wrapper'
    , menuSelector:         '.menu-header' // The selector for the entire menu
    , contentFadeOut:       600
    , contentFadeIn:        120
    , scrollDuration:       300
    , spinnerFadeOut:       300
    , spinnerOptions: {     // Reference: https://fgnass.github.io/spin.js/
        lines: 25
      , length: 0
      , width: 4
      , radius: 25
      , speed: 1.5
      , trail: 40
      , top: '25%'
    }
  };

  // Check to see if History.js is enabled; if not, let the site load normally
  if ( !History.enabled ) { return false; }

  // Wait for Document
  $(function(){
    var
      $window               = $(window)
    , $body                 = $(document.body)
    , rootUrl               = History.getRootUrl()
    , contentSelector       = ajaxifyOptions.contentSelector
    , menuSelector          = ajaxifyOptions.menuSelector
    , $content              = $(contentSelector).filter(':first')
    , contentNode           = $content.get(0)
    ;

    // Ensure content isn't empty
    if ( $content.length === 0 ) {
      $content = $body;
    }

    // Identifies internal links
    $.expr[':'].internal = function(obj, index, meta, stack){
      var
        $this = $(obj),
        url = $this.attr('href') || '',
        isInternalLink;

      // Test the link
      isInternalLink = url.substring(0,rootUrl.length) === rootUrl || url.indexOf(':') === -1;

      // Ignore or keep
      return isInternalLink;
    };

    // HTML helper; easily select elements from the page to load by selecting #document-html, #document-head, and #document-body
    var documentHtml = function(html){
      var result = String(html)
        .replace(/<\!DOCTYPE[^>]*>/i, '')
        .replace(/<(html|head|body)([\s\>])/gi,'<div id="document-$1"$2')
        .replace(/<\/(html|head|body)\>/gi,'</div>')
      ;
      return $.trim(result);
    };

    // Ajaxify helper; trigger page loading for qualifying links
    $.fn.ajaxify = function(){
      var $this = $(this);

      // @TODO: external links handler
      //$this.find('a:not(:internal)')
      //.on('click', function(event){
        // target blank or open popup for anything not covered below
      //  event.preventDefault();
      //  return false;
      //});

      // Run the script when clicking on certain links; WP-specific exceptions have been added here
      $this.find('a:internal:not(.no-ajaxy, [href^="#"], [href*="wp-login"], [href*="wp-admin"])')

      // Filter URLs that end in specified file extensions; the use of the not operator only returns links that aren't to files
      .filter(function() {
        return !this.href.match(/\.(jpg|jpeg|png|gif|bmp|pdf|mp3|flac|wav|rar|zip)$/i); // @TODO: move this up to ajaxifyOptions
      })
      .on('click', function(event){
        var
          $this = $(this),
          url   = $this.attr('href'),
          title = $this.attr('title') || null;

        // Continue as normal for command clicks etc.
        if ( event.which == 2 || event.metaKey ) {
          return true;
        }

        // Ajaxify this link
        History.pushState(null,title,url);
        event.preventDefault();
        return false;
      });

      // Chain this function
      return $this;
    };

    // Ajaxify our internal links
    $body.ajaxify();

    // Hook into state changes
    $window.bind('statechange', function(){
      var
        State = History.getState(),
        url = State.url,
        relativeUrl = url.replace(rootUrl, '');

      // Set loading class
      $body.addClass('loading');

      // Fade out existing content; use animate to preserve the element's height (avoids scrollbar flicker)
      $content.animate({ opacity: 0 }, ajaxifyOptions.contentFadeOut);

      // Spin spin sugar; degrades gracefully if spin.js not found
      if ( $.isFunction(window.Spinner) ) {
        $content.before('<div id="spinner" style="position: fixed; height: 100%; width: 100%;"></div>');
        $('#spinner').spin(ajaxifyOptions.spinnerOptions);
      }

      // AJAX page request; fetch the content we need
      $.ajax({
        url: url,
        success: function(data, textStatus, jqXHR){
          var
            $data         = $(documentHtml(data))
          , $dataBody     = $data.find('#document-body:first')
          , $dataContent  = $dataBody.find(contentSelector).filter(':first')
          , $dataMenu     = $dataBody.find(menuSelector)
          , $scripts
          , contentHtml;

          // Add classes to body; makes WordPress pages render smoothly
          $('body').attr('class', $dataBody.attr('class') );

          // Fetch body scripts not in the content (which will be replaced anyhow)
          $scripts = $dataBody.find('script').not(contentSelector+' script');
          if ( $scripts.length ) { $scripts.detach(); }

          // Fetch the content
          contentHtml = $dataContent.html() || $data.html();
          if ( !contentHtml ) {
            document.location.href = url;
            return false;
          }

          // Update the content and ajaxify the links contained within
          $content.stop(true,true);
          $content.html(contentHtml).ajaxify();

          // Fade the content back in
          $content.animate({ opacity: 1, visibility: "visible" }, ajaxifyOptions.contentFadeIn);

          // Scroll to the top of the content container when below the fold
          if ( $(window).scrollTop() > $(contentSelector).offset().top ) {
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
            if ( '' != $(this).html() ) {

              // Save the currect script contents for use in the loop to follow
              var inlineScript = $(this).html(),
                  notNewScript = false;

              // Check existing scripts to see if they contain the same stuff
              $.each( $('body').find('script:not(:empty)').not(contentSelector+' script'), function(){
                if ( $(this).html() == inlineScript ) { notNewScript = true; }
              });

              // Paste new stuff in if it isn't found in the current DOM; this is quite a kludge
              if ( notNewScript == false ) {
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
          $window.trigger('statechangecomplete'); // This is an arbitrary name give to trigger other events after this script fires

          // Inform Google Analytics of the change; compatible with the new Universal Analytics script; @TODO: test this in a live environment
          if ( typeof window.ga !== 'undefined' ) {
            window.ga('send', 'pageview', relativeUrl);
          }

        },
        // Fallback functionality
        error: function(jqXHR, textStatus, errorThrown){
          document.location.href = url;
          return false;
        }
      }); // end AJAX page request

    }); // end onStateChange

  }); // end onDomLoad

})(window);

// @TODO: ajaxify search and other form elements (e.g. the monthly dropdown menu)
// The code below, borrowed from AWS, does not do what we want
// Search URL needs to be preserved; this just hacks it together based on a guess
// Process: create a hidden div, implant a link to the desired URL, swap actions when clicking on the submit button to clicking on said link
// If we get the link right then the rest falls into place
jQuery(document).ready(function(){

  // Append anchor tag to DOM to make the search in site ajaxify.
  var searchButtonHtml = '<span id="ajax-search" style="display:none;"><a href=""></a></span>'
  jQuery("body").prepend(searchButtonHtml);

  //Make the link ajaxify.
  jQuery("#ajax-search").ajaxify();

  jQuery('.search-form').on('submit',
    function(d){
      //d.preventDefault();
      var host = "http://synaptic.dev:8080/?s=";
      jQuery("#ajax-search a").attr("href", host + jQuery(this).find('input[type="search"]').val());
      jQuery("#ajax-search a").trigger("click");
    }
  );
});