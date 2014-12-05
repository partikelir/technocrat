<?php // ==== PICTUREFILL ==== //

// Setup a few media queries for the `sizes` attribute; must be an array even if there is only one value
if ( !function_exists( 'pendrell_sizes_media_queries' ) ) : function pendrell_sizes_media_queries( $queries = array(), $width = '' ) {

  global $content_width, $main_width;

  // Media queries for this theme should be fairly straight-forward as the layout is fairly simple
  // Either content is displayed with a sidebar or it's all displayed in a single column
  // The main thing that varies is the margins (which get progressively smaller as the viewport width decreases)

  // Breakpoints replicated from `src/scss/_base_config.scss`
  $full   = $content_width + ( PENDRELL_BASELINE * 3);
  $large  = $main_width + ( PENDRELL_BASELINE * 8);
  $medium = $main_width + ( PENDRELL_BASELINE * 3);
  $small  = round( $main_width/1.2 );

  // Note: the *order* of media queries is important; browsers pick the first match!

  // Full-width is a special case and requires its own media queries
  if ( pendrell_is_full_width() ) {

    if ( !empty( $width ) ) {

      // Approach: start with the largest media query and move down as long as the image width is less than one breakpoint and more than the next
      if ( $width > $medium ) {
        $queries[] = '(min-width: ' . ( $width + ( PENDRELL_BASELINE * 3 ) ) . 'px) ' . $width . 'px';
      } elseif ( $width <= $medium && $width > $small ) {
        $queries[] = '(min-width: ' . ( $width + ( PENDRELL_BASELINE * 2 ) ) . 'px) ' . $width . 'px';
      } else {
        $queries[] = '(min-width: ' . ( $width + PENDRELL_BASELINE ) . 'px) ' . $width . 'px';
      }
    } else {
      $queries[] = '(min-width: ' . $full . 'px) ' . $content_width . 'px';
    }

  } else {

    if ( !empty( $width ) ) {

      // Divide
      if ( $width < $main_width )
        $width = $width * ( $main_width / $content_width );

      // Approach: start with the largest media query and move down as long as the image width is less than one breakpoint and more than the next
      if ( $width > $medium ) {
        $queries[] = '(min-width: ' . ( $width + ( PENDRELL_BASELINE * 3 ) ) . 'px) ' . $width . 'px';
      } elseif ( $width <= $medium && $width > $small ) {
        $queries[] = '(min-width: ' . ( $width + ( PENDRELL_BASELINE * 2 ) ) . 'px) ' . $width . 'px';
      } else {
        $queries[] = '(min-width: ' . ( $width + PENDRELL_BASELINE ) . 'px) ' . $width . 'px';
      }
    } else {
      $queries[] = '(min-width: ' . $medium . 'px) ' . $main_width . 'px';
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
  if ( empty( $default ) ) {
    // An attempt to account for page margins; use $default = '100vw' if this seems too complicated
    $default = 'calc(100vw - ' . PENDRELL_BASELINE . 'px)'; // `calc()` support: http://caniuse.com/#search=calc
  }


  // Return the default string
  return $default;

} endif;
add_filter( 'ubik_imagery_sizes_default', 'pendrell_sizes_default' );
