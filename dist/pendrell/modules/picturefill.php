<?php // ==== PICTUREFILL ==== //

// Setup a few media queries for the `sizes` attribute; must be an array even if there is only one value
if ( !function_exists( 'pendrell_sizes_media_queries' ) ) : function pendrell_sizes_media_queries( $queries = array(), $width = '' ) {

  global $content_width, $main_width;

  // Breakpoints replicated from `src/scss/_base_config.scss`
  $full = $content_width + ( PENDRELL_BASELINE * 3) . 'px';
  $large = $main_width + ( PENDRELL_BASELINE * 8) . 'px';
  $medium = $main_width + ( PENDRELL_BASELINE * 3) . 'px';
  $small = $main_width/1.2 . 'px';

  // Note: the order of media queries is important, for the browser will go with the first match!

  // Full-width is a special case and requires its own media queries
  if ( pendrell_is_full_width() ) {

    // Above the full breakpoint full-width images will max out at $content_width
    if ( empty( $width ) || $width >= $content_width ) {
      $queries[] = '(min-width: ' . $full . ') ' . $content_width . 'px';
    } else {
      $queries[] = '(min-width: ' . $full . ') ' . $width . 'px';
      if ( $width <= $large )
        $queries[] = '(min-width: ' . $large . ') ' . $width . 'px';
      if ( $width <= $medium )
        $queries[] = '(min-width: ' . $medium . ') ' . $width . 'px';
      if ( $width <= $small )
        $queries[] = '(min-width: ' . $small . ') ' . $width . 'px';
    }

  } else {

    // Above the medium breakpoint most images will max out at $main_width
    if ( empty( $width ) || $width >= $main_width ) {
      $queries[] = '(min-width: ' . $medium . ') ' . $main_width . 'px';
    } else {
      $queries[] = '(min-width: ' . $medium . ') ' . $width . 'px';
    }
  }

  // Note: below the previously specified breakpoints most images will expand to fill the window (minus margins)
  // For this reason no further media queries are specified; the default "100vw" is good enough to handle these cases

  // Return an array of arrays
  return array( $queries );

} endif;
add_filter( 'ubik_imagery_sizes_media_queries', 'pendrell_sizes_media_queries', 10, 2 );



// An example function; there's no need to filter the default "100vw" with this theme
if ( !function_exists( 'pendrell_sizes_default' ) ) : function pendrell_sizes_default( $default ) {
  return $default;
} endif;
//add_filter( 'ubik_imagery_sizes_default', 'pendrell_sizes_default' );
