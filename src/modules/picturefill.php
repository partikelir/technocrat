<?php // ==== PICTUREFILL ==== //

// Setup a few media queries for the `sizes` attribute; must be an array even if there is only one value
if ( !function_exists( 'pendrell_sizes_media_queries' ) ) : function pendrell_sizes_media_queries( $queries = array(), $size = '', $width = '' ) {

  // Exit early if we don't have the required size and width data
  if ( empty( $size ) || !is_string( $size ) || empty( $width ) || !is_int( $width ) )
    return;

  global $content_width, $main_width;

  // Media queries for this theme should be fairly straight-forward as the layout is fairly simple
  // Either content is displayed with a sidebar or it's all displayed in a single column
  // The main thing that varies is the margins (which get progressively smaller as the viewport width decreases)
  // One special case: half/third/quarter-width images require a slightly more refined approach
  // Note: the *order* of media queries is important; browsers pick the first match!

  // Breakpoints replicated from `src/scss/_base_config.scss`
  $full   = $content_width + ( PENDRELL_BASELINE * 3);
  $large  = $main_width + ( PENDRELL_BASELINE * 8);
  $medium = $main_width + ( PENDRELL_BASELINE * 3);
  $small  = ceil( $main_width/1.2 );

  // @TODO: Houston, we have a problem

  // Full-width is a special case and requires its own media queries
  if ( pendrell_is_full_width() ) {

    // Approach: start with the largest media query and move down as long as the image width is less than one breakpoint and more than the next
    // What we want to specify is the minimum width at which an image is reliably going to scale to its target width
    // So, for instance, if we have a 624px image (the default medium size), the minimum width for full display will be 624px + margins
    // However, if our target image is grouped (e.g. in a set of two, three, four, or more)

    if ( $width > $medium ) {
      $queries[] = '(min-width: ' . ( $width + ( PENDRELL_BASELINE * 3 ) ) . 'px) ' . $width . 'px';
    } elseif ( $width <= $medium && $width > $small ) {
      $queries[] = '(min-width: ' . ( $width + ( PENDRELL_BASELINE * 2 ) ) . 'px) ' . $width . 'px';
    } else {
      $queries[] = '(min-width: ' . ( $width + PENDRELL_BASELINE ) . 'px) ' . $width . 'px';
    }

  } else {

    // Divide and conquer
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
  }

  // Note: below the previously specified breakpoints most images will expand to fill the window (minus margins)
  // For this reason no further media queries are specified; the default (below) is good enough to handle these cases

  // Return an array of arrays
  return array( $queries );

} endif;
add_filter( 'ubik_imagery_sizes_media_queries', 'pendrell_sizes_media_queries', 10, 3 );



// Filter the default `sizes` attribute (which is otherwise blank)
if ( !function_exists( 'pendrell_sizes_default' ) ) : function pendrell_sizes_default( $default = '', $size = '', $width = '' ) {

  // Default viewport width
  $viewport = 100;

  // Divide the default viewport width for half/third/quarter-width images
  if ( in_array( $size, array( 'half', 'half-square' ) ) )
    $viewport = round( $viewport / 2, 5);
  if ( in_array( $size, array( 'third', 'third-square' ) ) )
    $viewport = round( $viewport / 3, 5);
  if ( in_array( $size, array( 'quarter', 'quarter-square' ) ) )
    $viewport = round( $viewport / 4, 5);

  // Allows another function to override this one
  if ( empty( $default ) ) {

    // An attempt to account for page margins; use $default = '100vw' if this seems too complicated
    $default = 'calc(' . $viewport . 'vw - ' . PENDRELL_BASELINE . 'px)'; // `calc()` support: http://caniuse.com/#search=calc
  }

  // Return the default string
  return $default;

} endif;
add_filter( 'ubik_imagery_sizes_default', 'pendrell_sizes_default', 10, 3 );
