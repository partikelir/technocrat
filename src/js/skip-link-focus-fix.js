// Skip link focus fix via _s: https://github.com/Automattic/_s
// More about this: http://www.nczonline.net/blog/2013/01/15/fixing-skip-to-content-links/
( function() {
  var isWebkit = navigator.userAgent.toLowerCase().indexOf( 'webkit' ) > -1,
      isOpera  = navigator.userAgent.toLowerCase().indexOf( 'opera' )  > -1,
      isIe     = navigator.userAgent.toLowerCase().indexOf( 'msie' )   > -1;

  if ( ( isWebkit || isOpera || isIe ) && document.getElementById && window.addEventListener ) {
    window.addEventListener( 'hashchange', function() {
      var element = document.getElementById( location.hash.substring( 1 ) );

      if ( element ) {
        if ( ! /^(?:a|select|input|button|textarea)$/i.test( element.tagName ) ) {
          element.tabIndex = -1;
        }
        element.focus();
      }
    }, false );
  }
})();
