<?php // ==== PICTUREFILL ==== //

// Setup a few media queries for the `sizes` attribute; must be an array even if there is only one value
if ( !function_exists( 'pendrell_sizes_media_queries' ) ) : function pendrell_sizes_media_queries( $queries = array(), $width = '' ) {

  global $content_width, $main_width;

  // Breakpoints replicated from `src/scss/_base_config.scss`
  $full = $content_width + ( PENDRELL_BASELINE * 3) . 'px';
  $large = $main_width + ( PENDRELL_BASELINE * 8) . 'px';
  $medium = $main_width + ( PENDRELL_BASELINE * 3) . 'px';
  $small = $main_width/1.2 . 'px';

  // Note: the *order* of media queries is important; browsers pick the first match!

  // Full-width is a special case and requires its own media queries
  if ( pendrell_is_full_width() ) {

    // Above the full breakpoint full-width images will max out at $content_width
    if ( empty( $width ) || $width >= $content_width ) {
      $queries[] = '(min-width: ' . $full . ') ' . $content_width . 'px';
    } else {
      // Approach: start with the largest media query and move down as long as the image width is less than the breakpoint
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
      // 0.65 is the difference between $content_width and $main_width
      $queries[] = '(min-width: ' . $medium . ') ' . $width * 0.65 . 'px';
      if ( $width <= $small )
        $queries[] = '(min-width: ' . $small . ') ' . $width * 0.65 . 'px';
    }
  }

  // Note: below the previously specified breakpoints most images will expand to fill the window (minus margins)
  // For this reason no further media queries are specified; the default (below) is good enough to handle these cases

  // Return an array of arrays
  return array( $queries );

} endif;
add_filter( 'ubik_imagery_sizes_media_queries', 'pendrell_sizes_media_queries', 10, 2 );



// Filter the default `sizes` attribute (which is otherwise blank)
if ( !function_exists( 'pendrell_sizes_default' ) ) : function pendrell_sizes_default( $default ) {

  // Allows another function to override this one
  if ( empty( $default ) )
    $default = 'calc(100vw - ' . PENDRELL_BASELINE . 'px)'; // An attempt to account for page margins; use $default = '100vw' if this seems too complicated

  // Return the default string
  return $default;

} endif;
add_filter( 'ubik_imagery_sizes_default', 'pendrell_sizes_default' );
