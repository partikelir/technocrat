<?php // ==== PICTUREFILL ==== //

// Notes about the `sizes` attribute:
// - The *order* of media queries is important; browsers pick the first match so start with the largest query!
// - The purpose of the `sizes` attribute is to give the browser an accurate estimation of the *rendered size* of an image at different viewport widths
// - The approach taken will depend entirely on how images are displayed in the theme
// - In the case of this theme, Pendrell, there are several complications:
//   - The use of fractional width images (half/third/quarter-width)
//   - The use of a full-width template
//   - Page margins that vary according to viewport width
//   - Gallery view switches between a two and three column layout (still @TODO)
// These scenarious will be discussed in the code below...

// Setup a few media queries for the `sizes` attribute; must be an array even if there is only one value!
if ( !function_exists( 'pendrell_sizes_media_queries' ) ) : function pendrell_sizes_media_queries( $queries = array(), $size = '', $width = '' ) {

  // Exit early if we don't have the required size and width data
  if ( empty( $size ) || !is_string( $size ) || empty( $width ) || !is_int( $width ) )
    return;

  global $content_width, $main_width;

  // Get the margin from the main configuration file; 30px
  $margin = (int) PENDRELL_BASELINE;

  // Breakpoints replicated from `src/scss/_base_config.scss`
  $small = ceil( $main_width/1.2 ) - ( $margin * 2 ); // 460px
  $small_padded = $small + ( $margin * 2 ); // 520px
  $medium = $main_width; // 624px
  $medium_padded = $medium + ( $margin * 3 ); // 714px

  // Fractional width sizes, a complicated example of calculating the `sizes` attribute...
  if ( in_array( $size, array( 'half', 'half-square', 'third', 'third-square', 'quarter', 'quarter-square' ) ) ) {

    // Multiplier for fractional width sizes
    $factor = 1;
    if ( in_array( $size, array( 'half', 'half-square' ) ) )
      $factor = 2;
    if ( in_array( $size, array( 'third', 'third-square' ) ) )
      $factor = 3;
    if ( in_array( $size, array( 'quarter', 'quarter-square' ) ) )
      $factor = 4;

    // This works but it's confusing as fuck; @TODO: document this!
    $viewport = round( 100 / $factor, 5 );
    $margin_inner = $margin * ( $factor - 1 );
    $combined_width = ( $width * $factor ) + $margin_inner;

    if ( $combined_width > $medium ) {
      $queries[] = '(min-width: ' . ( $combined_width + ( $margin * 3 ) ) . 'px) ' . $width . 'px';
      $queries[] = '(min-width: ' . $medium_padded . 'px) calc(' . $viewport . 'vw - ' . round( ( ( ( $margin * 3 ) + $margin_inner ) / $factor ), 5 ) . 'px)';
      $queries[] = '(min-width: ' . $small_padded . 'px) calc(' . $viewport . 'vw - ' . round( ( ( ( $margin * 2 ) + $margin_inner ) / $factor ), 5 ) . 'px)';
    } elseif ( $combined_width > $small ) {
      $queries[] = '(min-width: ' . ( $combined_width + ( $margin * 2 ) ) . 'px) ' . $width . 'px';
      $queries[] = '(min-width: ' . $small_padded . 'px) calc(' . $viewport . 'vw - ' . round( ( ( ( $margin * 2 ) + $margin_inner ) / $factor ), 5 ) . 'px)';
    } else {
      $queries[] = '(min-width: ' . ( $combined_width + $margin ) . 'px) ' . $width . 'px';
    }

  } else {

    // Maximum rendered image size for the theme; only applies to the non-fractional image size case
    if ( pendrell_is_full_width() ) {
      $width = min( $width, $content_width ); // Can't render any larger than $content_width
    } else {
      $width = min( $width, $main_width ); // Can't render any larger than $main_width
    }

    // The following media queries handle all images intended to fill the content area of this theme
    // The topmost breakpoint is set to the width of the image plus known page margins
    // When this media query is satisfied the image is displayed at its original intended width
    // Below this point the image will naturally expand to fill available space
    // However: the rendered size will *not* be '100vw' (i.e. 100% of the viewport) due to the margins around the content area
    // These margins change according to breakpoints set in `src/scss/_base_config.scss`
    // Consequently, the viewport width is modified to account for the changing margins at various breakpoints
    // Finally, the default media query (defined below) handles all other scenarios
    if ( $width > $medium ) {
      $queries[] = '(min-width: ' . ( $width + ( $margin * 3 ) ) . 'px) ' . $width . 'px';
      $queries[] = '(min-width: ' . $medium_padded . 'px) calc(100vw - ' . ( $margin * 3 ) . 'px)';
      $queries[] = '(min-width: ' . $small_padded . 'px) calc(100vw - ' . ( $margin * 2 ) . 'px)';
    } elseif ( $width > $small ) {
      $queries[] = '(min-width: ' . ( $width + ( $margin * 2 ) ) . 'px) ' . $width . 'px';
      $queries[] = '(min-width: ' . $small_padded . 'px) calc(100vw - ' . ( $margin * 2 ) . 'px)';
    } else {
      $queries[] = '(min-width: ' . ( $width + $margin ) . 'px) ' . $width . 'px';
    }
  }

  // Return an array of arrays (required by Ubik Imagery)
  return array( $queries );

} endif;
add_filter( 'ubik_imagery_sizes_media_queries', 'pendrell_sizes_media_queries', 10, 3 );



// Filter the default `sizes` attribute (which is otherwise blank)
if ( !function_exists( 'pendrell_sizes_default' ) ) : function pendrell_sizes_default( $default = '', $size = '', $width = '' ) {

  // Default viewport width and (optionally) a margin; both integers
  $viewport = 100;
  $margin = (int) PENDRELL_BASELINE;

  // Multiplier
  $factor = 1;
  if ( in_array( $size, array( 'half', 'half-square' ) ) )
    $factor = 2;
  if ( in_array( $size, array( 'third', 'third-square' ) ) )
    $factor = 3;
  if ( in_array( $size, array( 'quarter', 'quarter-square' ) ) )
    $factor = 4;

  // Special handling for fractional width images
  if ( $factor > 1 ) {
    $viewport = round( 100 / $factor, 5 ); // Divide the default viewport width for half/third/quarter-width images
    $margin = ( $margin + ( $margin * ( $factor - 1 ) ) ) / $factor; // Um... trust me
  }

  // Margins in this theme vary according to viewport size; what we want here is the smallest possible margin
  if ( !empty( $margin ) ) {
    $default = 'calc(' . $viewport . 'vw - ' . $margin . 'px)'; // `calc()` support: http://caniuse.com/#search=calc
  } else {
    $default = $viewport . 'vw'; // Without margins we'll just assume that images take up the full viewport on smaller screens
  }

  // Return the default `sizes` attribute
  return $default;

} endif;
add_filter( 'ubik_imagery_sizes_default', 'pendrell_sizes_default', 10, 3 );
