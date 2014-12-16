<?php // ==== RESPONSIVE IMAGES ==== //

// The following code depends on Ubik Imagery and Picturefill
// Notes about the `sizes` attribute:
// - The *order* of media queries is important; browsers pick the first match so start with the largest query!
// - The purpose of the `sizes` attribute is to give the browser an accurate estimation of the *rendered size* of an image at different viewport widths
// - The approach taken will depend entirely on how images are displayed in the theme
// - In the case of this theme, Pendrell, there are several complications:
//   - The use of fractional width images (half/third/quarter-width)
//   - The use of a full-width template
//   - Page margins vary according to viewport width
//   - Gallery view switches between a one, two, and three column layout depending on the viewport width
// These scenarious will be discussed in the code below...
// @TODO: investigate Firefox scrollbar inconsistencies; it may be necessary to add another fudge factor to ensure browsers choose the optimal image

// Setup a few media queries for the `sizes` attribute; must be an array even if there is only one value!
// Note: additional code modifying media queries and default `sizes` can be found in `src/modules/views.php`
if ( !function_exists( 'pendrell_sizes_media_queries' ) ) : function pendrell_sizes_media_queries( $queries = array(), $size = '', $width = '', $group = 0 ) {

  // Exit early if we don't have the required size and width data
  if ( empty( $size ) || !is_string( $size ) || empty( $width ) || !is_int( $width ) )
    return $queries;

  global $content_width, $main_width;

  // Set the bounding width (the maximum size for rendered images)
  if ( pendrell_is_full_width() ) {
    $bounding_width = $content_width;
  } else {
    $bounding_width = $main_width;
  }

  // The margins can be filtered; this is mostly in case the inner margin (the space between grouped images) is not the same as the page margins
  // Example: your outer page margins are 30px on each side but the spacing between images is 20px
  $margin       = (int) apply_filters( 'pendrell_sizes_margin', PENDRELL_BASELINE );
  $margin_inner = (int) apply_filters( 'pendrell_sizes_margin_inner', PENDRELL_BASELINE );

  // Breakpoints replicated from `src/scss/_base_config.scss`
  $b_small = ceil( $main_width/1.2 ); // 520px
  $b_medium = $main_width + ( $margin * 3 ); // 714px

  // Usable space for each breakpoint; for comparison with $width
  $b_small_content = ceil( $main_width/1.2 ) - ( $margin * 2 ); // 460px
  $b_medium_content = $main_width; // 624px

  // Fractional width sizes, a complicated example of calculating the `sizes` attribute
  // Note: this only works when images are explicitly grouped (i.e. with the `[group]` shortcode or by setting the $group flag when calling `ubik_image_markup`); otherwise they are treated like any other image
  if ( $group > 0 && in_array( $size, array( 'half', 'half-square', 'third', 'third-square', 'quarter', 'quarter-square' ) ) ) {

    // Multiplier for fractional width sizes
    if ( in_array( $size, array( 'half', 'half-square' ) ) )
      $factor = 2;
    if ( in_array( $size, array( 'third', 'third-square' ) ) )
      $factor = 3;
    if ( in_array( $size, array( 'quarter', 'quarter-square' ) ) )
      $factor = 4;

    // We lead with a media query specifying the minimum viewport width at which an image is *fixed* in size (not fluid)
    // In this theme only images displayed in full-width mode will reach their full potential; everything else will be fixed but downsized
    // The second media query handles anything displayed within the bounds of $main_width
    // Here we abandon $width and attempt to calculate the rendered size of the image from scratch
    if ( pendrell_is_full_width() ) {
      $queries[] = '(min-width: ' . ( $bounding_width + ( $margin * 3 ) ) . 'px) ' . $width . 'px';
    } else {
      $queries[] = '(min-width: ' . ( $bounding_width + ( $margin * 3 ) ) . 'px) ' . round( ( $bounding_width - ( $margin_inner * ( $factor - 1 ) ) ) / $factor, 5 ) . 'px';
    }

    // The following variable accounts for fractional-width images with percentage-based media queries
    // For example: third-width images will take up one third of the viewport width minus one third of the total width of the inner margins (of which there will be two in this case) minus the fixed width of the page margins at a given size
    // These values won't add up to 100 due to the presence of the inner margins
    $viewport = round( ( 1 / $factor - ( ( ( $margin_inner * ( $factor - 1 ) ) / $bounding_width ) ) / $factor ) * 100, 5 );
    $queries[] = '(min-width: ' . $b_medium . 'px) calc(' . $viewport . 'vw - ' . round( ( $margin * 3 ) / $factor, 5 ) . 'px)';
    $queries[] = '(min-width: ' . $b_small . 'px) calc(' . $viewport . 'vw - ' . round( ( $margin * 2 ) / $factor, 5 ) . 'px)';

  } else {

    // Limit the width to the bounding box, whatever it my be for this view
    $width = min( $width, $bounding_width );

    // The topmost breakpoint is set to the width of the image plus known page margins
    // When this media query is satisfied the image is displayed at its original intended width
    // Below this point the image will naturally expand to fill available space
    // However: the rendered size will *not* be '100vw' (i.e. 100% of the viewport) due to the margins around the content area
    // These margins change according to breakpoints set in `src/scss/_base_config.scss`
    // Consequently, the viewport width is modified to account for the changing margins at various breakpoints using calc
    // Note: it is also necessary to specify a default for the `sizes` attribute; have a look at the next function for an example
    if ( $width > $b_medium_content ) {
      $queries[] = '(min-width: ' . ( $width + ( $margin * 3 ) ) . 'px) ' . $width . 'px';
      $queries[] = '(min-width: ' . $b_medium . 'px) calc(100vw - ' . ( $margin * 3 ) . 'px)';
      $queries[] = '(min-width: ' . $b_small . 'px) calc(100vw - ' . ( $margin * 2 ) . 'px)';
    } elseif ( $width > $b_small_content ) {
      $queries[] = '(min-width: ' . ( $width + ( $margin * 2 ) ) . 'px) ' . $width . 'px';
      $queries[] = '(min-width: ' . $b_small . 'px) calc(100vw - ' . ( $margin * 2 ) . 'px)';
    } else {
      $queries[] = '(min-width: ' . ( $width + $margin ) . 'px) ' . $width . 'px';
    }
  }

  // Return an array of arrays (required by Ubik Imagery)
  return array( $queries );

} endif;
add_filter( 'ubik_imagery_sizes_media_queries', 'pendrell_sizes_media_queries', 10, 4 );



// Default `sizes` attribute handling for Pendrell; note that gallery view is handled in `src/modules/views.php`
// @filter: pendrell_sizes_margin
// @filter: pendrell_sizes_margin_inner
// @constant: PENDRELL_BASELINE
if ( !function_exists( 'pendrell_sizes_default' ) ) : function pendrell_sizes_default( $default = '', $size = '', $width = '', $group = 0 ) {

  global $content_width, $main_width;

  // Set the bounding width (the maximum size for rendered images)
  if ( pendrell_is_full_width() ) {
    $bounding_width = $content_width;
  } else {
    $bounding_width = $main_width;
  }

  // Default viewport width (integer)
  $viewport     = 100;

  // The margins can be filtered; this is mostly in case the inner margin (the space between grouped images) is not the same as the page margins
  // Example: your outer page margins are 30px on each side but the spacing between images is 20px
  $margin       = (int) apply_filters( 'pendrell_sizes_margin', PENDRELL_BASELINE );
  $margin_inner = (int) apply_filters( 'pendrell_sizes_margin_inner', PENDRELL_BASELINE );

  // Set the factor by which things need to be divided based on the requested image size
  // This presumes that Ubik Imagery's sizing conventions are being followed; see: https://github.com/synapticism/ubik-imagery
  $factor = 1;
  if ( in_array( $size, array( 'half', 'half-square' ) ) )
    $factor = 2;
  if ( in_array( $size, array( 'third', 'third-square' ) ) )
    $factor = 3;
  if ( in_array( $size, array( 'quarter', 'quarter-square' ) ) )
    $factor = 4;

  // Special handling for fractional width images: divide the default viewport width for half/third/quarter-width images (minus the inner margin contribution on a per image basis)
  if ( $factor > 1 && $group > 0 ) {
    $viewport = round( ( 1 / $factor - ( ( ( $margin_inner * ( $factor - 1 ) ) / $bounding_width ) ) / $factor ) * 100, 5 ) + 0.001;
    $margin   = $margin / $factor;
  }

  // Margins in this theme vary according to viewport size; what we want here is the smallest possible margin (since this is the default media query we are returning)
  if ( !empty( $margin ) ) {
    $default = 'calc(' . $viewport . 'vw - ' . $margin . 'px)'; // `calc()` support: http://caniuse.com/#search=calc
  } else {
    $default = $viewport . 'vw'; // Without margins we'll just assume that images take up the full viewport on smaller screens
  }

  // Return the default `sizes` attribute
  return $default;

} endif;
add_filter( 'ubik_imagery_sizes_default', 'pendrell_sizes_default', 10, 4 );
