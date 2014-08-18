// Adapted from the original Ajaxify v1.0.1 gist and the Ajaxify WordPress Site v1.5.4; https://github.com/browserstate/ajaxify and https://github.com/wp-plugins/ajaxify-wordpress-site
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
    // Reference: https://fgnass.github.io/spin.js/
    , spinnerOptions: {
        lines: 8
      , length: 24
      , width: 12
      , radius: 24
      , speed: 1.5
      , trail: 40
      , top: '25%'
    }
  };

  // Check to see if History.js is enabled; if not, return false and let the site load normally
  if ( !History.enabled ) {
    return false;
  }

  // Wait for Document
  $(function(){
    var
      $window               = $(window)
    , $body                 = $(document.body)
    , contentSelector       = ajaxifyOptions.contentSelector
    , menuSelector          = ajaxifyOptions.menuSelector
    , $content              = $(contentSelector).filter(':first')
    , contentNode           = $content.get(0)
    , rootUrl               = History.getRootUrl()
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

    // HTML helper; easily select elements from the page to load
    var documentHtml = function(html){
      var result = String(html)
        .replace(/<\!DOCTYPE[^>]*>/i, '')
        .replace(/<(html|head|body|title|meta|script)([\s\>])/gi,'<div id="document-$1"$2') // id attribute is used to support body class switching
        .replace(/<\/(html|head|body|title|meta|script)\>/gi,'</div>')
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
        return !this.href.match(/\.(jpg|jpeg|png|gif|bmp|pdf|mp3|flac|wav|rar|zip)$/i);
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
          , $metas
          , $links
          , contentHtml;

          // Add classes to body; makes WordPress run smoothly
          $('body').attr('class', $dataBody.attr('class'));

          // Fetch the scripts from the body; this way scripts can be updated dynamically as they can differ from page to page
          $scripts = $dataBody.find('#document-script');
          if ( $scripts.length ) {
            $scripts.detach();
          }

          // @TODO: update meta and link tags as well

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
          $content.animate({ opacity: 1, visibility: "visible" },ajaxifyOptions.contentFadeIn);

          // Scroll to the top of the content container when below the fold
          if ( $(window).scrollTop() > $(contentSelector).offset().top ) {
            $('html, body').animate({ scrollTop: $(contentSelector).offset().top }, ajaxifyOptions.scrollDuration, "swing");
          }

          // Update the title
          document.title = $data.find('#document-title:first').text();
          try {
            document.getElementsByTagName('title')[0].innerHTML = document.title.replace('<','&lt;').replace('>','&gt;').replace(' & ',' &amp; ');
          }
          catch ( Exception ) { }

          // Add the scripts; via Ajaxify WordPress Site: https://github.com/wp-plugins/ajaxify-wordpress-site
          $scripts.each(function(){
            var scriptText = $(this).html();
            if ( '' != scriptText ) {
              scriptNode = document.createElement('script');
              scriptNode.appendChild(document.createTextNode(scriptText));
              contentNode.appendChild(scriptNode);
            } else {
              $.getScript( $(this).attr('src') );
            }
          });

          // Swap the menus after scrolling and ajaxify the menu links themselves
          $(menuSelector).html($dataMenu.html()).ajaxify();

          // Clean up
          $body.removeClass('loading');
          $('#spinner').fadeOut(ajaxifyOptions.spinnerFadeOut, function() { $(this).remove(); });
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