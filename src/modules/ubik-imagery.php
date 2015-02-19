<?php // ==== UBIK IMAGERY ==== //

// Requirements:
// - Ubik Imagery: https://github.com/synapticism/ubik-imagery
// - Picturefill: https://github.com/scottjehl/picturefill

// Notes about the `sizes` attribute:
// - The *order* of media queries is important; browsers pick the first match so start with the largest query!
// - The purpose of the `sizes` attribute is to give the browser an accurate estimation of the *rendered size* of an image at different viewport widths
// - The approach taken will depend entirely on how images are displayed in the theme
// - In the case of this theme, Pendrell, there are several scenarios (discussed at greater length in the code below):
//   + Page margins vary according to viewport width at different breakpoints
//   + Pages can be full-width; this changes the rendered size of fractional-width images
//   + Images can be grouped together into ad hoc galleries with 2, 3, or 4 images in a row
//   + These galleries have three different responses to changing viewport width:
//     = Normal: image layout reverts to a single column of images below a breakpoint
//     = Static: image layout is maintained at all viewport widths
//     = Responsive: column count decreases by 1 at various breakpoints (so a 4 column layout turns to 3, a 3 column layout turns to 2, etc.)

// @TODO: investigate Firefox scrollbar inconsistencies; it may be necessary to add another fudge factor to ensure browsers choose the optimal image

// Load plugin core
require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-imagery/ubik-imagery.php' );



// == SIZES ATTRIBUTE MEDIA QUERIES == //

// Setup a few media queries for the `sizes` attribute; must be an array even if there is only one value!
function pendrell_sizes_media_queries( $queries = array(), $size = '', $width = '', $context = '' ) {

  // Exit early if we don't have the required size and width data
  if ( empty( $size ) || !is_string( $size ) || empty( $width ) || !is_int( $width ) )
    return $queries;

  // Note: by core conventions $content_width is the maximum rendered width of an image; $main_width is a theme-specifc short-hand for the "normal" width of images
  global $content_width, $main_width;

  // Set the bounding width (the maximum size for rendered images)
  if ( pendrell_is_full_width() ) {
    $bounding_width = $content_width;
  } else {
    $bounding_width = $main_width;
  }

  // Let's limit the requested width to the bounding box since we're interested in rendered size
  $width = min( $width, $bounding_width );

  // Test the context object for various scenarios
  $group        = pendrell_sizes_context( $context, 'group' );
  $responsive   = pendrell_sizes_context( $context, 'responsive' );
  $static       = pendrell_sizes_context( $context, 'static' );

  // The margins can be filtered; this is mostly in case the inner margin (the space *between* grouped images) is not the same as the page margins
  // Example: your outer page margins are 30px on each side but the spacing between images is 20px
  $margin           = pendrell_sizes_margin();
  $margin_inner     = pendrell_sizes_margin_inner();

  // Breakpoints replicated from `src/scss/config/_settings.scss`
  $b_tiny           = ceil( $main_width / 1.5 );                    // 390px
  $b_small          = ceil( $main_width / 1.2 );                    // 520px
  $b_medium         = $main_width + ( $margin * 3 );                // 714px

  // Usable space for each breakpoint; for comparison with $width
  $b_tiny_content   = ceil( $main_width / 1.5 ) - $margin;          // 360px
  $b_small_content  = ceil( $main_width / 1.2 ) - ( $margin * 2 );  // 460px
  $b_medium_content = $main_width;                                  // 624px

  // Fractional width sizes, a complicated example of calculating the `sizes` attribute
  // These sizes are defined as fractions of $content_width by `ubik_imagery_add_fractional_sizes()`
  // Note: this only works when images are explicitly grouped (i.e. with the `[group]` shortcode or by passing `group` to the context object when calling `ubik_imagery`); otherwise they are treated like any other image
  if ( $group === true || $responsive === true ) {

    // Multiplier for use with fractional width sizes`
    $factor = 2; // $group is true so we expect two images in a row by default
    if ( in_array( $size, array( 'third', 'third-square' ) ) )
      $factor = 3;
    if ( in_array( $size, array( 'quarter', 'quarter-square' ) ) )
      $factor = 4;

    // We lead with a media query specifying the minimum viewport width at which an image is *fixed* in size (not fluid)
    // In this theme only images displayed in full-width mode will render at the requested width ($width); everything else will be fixed but downsized
    // As such we test whether this is a full-width view and, if not, attempt to calculate the downsized width given a smaller $bounding_width
    if ( !pendrell_is_full_width() )
      $width = round( ( $bounding_width - ( $margin_inner * ( $factor - 1 ) ) ) / $factor, 5 );
    $queries[] = '(min-width: ' . ( $bounding_width + ( $margin * 3 ) ) . 'px) ' . $width . 'px';

    // A regular grouped gallery
    if ( $responsive === false ) {

      // The next media query handles the medium breakpoint
      // By this point all images are rendered smaller than their intrinsic sizes and will be sized based on a percentage width of the viewport minus page margins and margins between images within a group
      // For example: third-width images will take up one third of the viewport width minus one third of the total width of the inner margins (of which there will be two in this case) minus the *fixed* width of the page margins (hence the use of `calc`)
      // Note: these values won't add up to 100 due to the presence of the inner margins
      $viewport = round( ( 1 / $factor - ( ( ( $margin_inner * ( $factor - 1 ) ) / $bounding_width ) ) / $factor ) * 100, 5 );
      $queries[] = '(min-width: ' . $b_medium . 'px) calc(' . $viewport . 'vw - ' . round( ( $margin * 3 ) / $factor, 5 ) . 'px)';

      // Special case: for non-static galleries the 4 column layout collapses into a 2 column layout below the medium breakpoint
      if ( $static === false && $factor === 4 ) {
        $factor = 2;
        $viewport = round( ( 1 / $factor - ( ( ( $margin_inner * ( $factor - 1 ) ) / $bounding_width ) ) / $factor ) * 100, 5 );
      }

      // The 2 and 3 column layouts only need to have the page margins progressively reduced; everything else remains the same
      $queries[] = '(min-width: ' . $b_small . 'px) calc(' . $viewport . 'vw - ' . round( ( $margin * 2 ) / $factor, 5 ) . 'px)';
      $queries[] = '(min-width: ' . $b_tiny . 'px) calc(' . $viewport . 'vw - ' . round( $margin / $factor, 5 ) . 'px)';

    // Responsive gallery handling
    } else {

      // Medium:  3x columns, 3x page margins
      // Small:   2x columns, 2x page margins
      // Tiny:    2x columns, 1x page margins
      // Default: 1x columns, 1x page margins; handled by `pendrell_sizes_default()`
      $queries[] = '(min-width: ' . $b_medium . 'px) calc(' . round( ( 1 / 3 - ( ( ( $margin_inner * 2 ) / $content_width ) ) / 3 ) * 100, 5 ) . 'vw - ' . round( ( $margin * 3 ) / 3, 5 ) . 'px)';
      $queries[] = '(min-width: ' . $b_small . 'px) calc(' . round( ( 1 / 2 - ( ( $margin_inner / $content_width ) ) / 2 ) * 100, 5 ) . 'vw - ' . round( ( $margin * 2 ) / 2, 5 ) . 'px)';
      $queries[] = '(min-width: ' . $b_tiny . 'px) calc(' . round( ( 1 / 2 - ( ( $margin_inner / $content_width ) ) / 2 ) * 100, 5 ) . 'vw - ' . round( $margin / 2, 5 ) . 'px)';
    }
  } else {

    // This block handles the most basic responsive images scenario: single images, presumably filling available space
    // The topmost breakpoint is set to the width of the image plus known page margins
    // When this media query is satisfied the image is displayed at its original intended width
    // Below this point the image will naturally expand to fill available space
    // However: the rendered size will *not* be '100vw' (i.e. 100% of the viewport) due to the margins around the content area
    // These margins change according to breakpoints set in `src/scss/config/_settings.scss`
    // Consequently, the viewport width is modified to account for the changing margins at various breakpoints using `calc`
    // Note: it is also necessary to specify a default for the `sizes` attribute; have a look at the next function for an example
    if ( $width >= $b_medium_content ) {
      $queries[] = '(min-width: ' . ( $width + ( $margin * 3 ) ) . 'px) ' . $width . 'px';
      $queries[] = '(min-width: ' . $b_medium . 'px) calc(100vw - ' . ( $margin * 3 ) . 'px)';
      $queries[] = '(min-width: ' . $b_small . 'px) calc(100vw - ' . ( $margin * 2 ) . 'px)';
    } elseif ( $width >= $b_small_content ) {
      $queries[] = '(min-width: ' . ( $width + ( $margin * 2 ) ) . 'px) ' . $width . 'px';
      $queries[] = '(min-width: ' . $b_small . 'px) calc(100vw - ' . ( $margin * 2 ) . 'px)';
    } else {
      $queries[] = '(min-width: ' . ( $width + $margin ) . 'px) ' . $width . 'px';
    }
  }

  // Return an array of arrays (required by Ubik Imagery)
  return array( $queries );

}
if ( PENDRELL_SCRIPTS_PICTUREFILL )
  add_filter( 'ubik_imagery_sizes_media_queries', 'pendrell_sizes_media_queries', 10, 4 );



// == SIZES ATTRIBUTE DEFAULT == //

// Default `sizes` attribute handling specific to Pendrell
function pendrell_sizes_default( $default = '', $size = '', $width = '', $context = '' ) {

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
  $margin       = pendrell_sizes_margin();
  $margin_inner = pendrell_sizes_margin_inner();

  // Test the context object for various scenarios
  $group        = pendrell_sizes_context( $context, 'group' );
  $static       = pendrell_sizes_context( $context, 'static' );

  // Static galleries are a special case; for everything else we can safely default back to the full viewport minus basic page margins
  // This presumes that Ubik Imagery's sizing conventions are being followed; see: https://github.com/synapticism/ubik-imagery
  if ( $group === true && $static === true ) {
    $factor = 2; // $group is true so we expect two images in a row by default
    if ( in_array( $size, array( 'third', 'third-square' ) ) )
      $factor = 3;
    if ( in_array( $size, array( 'quarter', 'quarter-square' ) ) )
      $factor = 4;

    // Divide the default viewport width for half/third/quarter-width images (minus the inner margin contribution on a per image basis)
    $viewport = round( ( 1 / $factor - ( ( ( $margin_inner * ( $factor - 1 ) ) / $bounding_width ) ) / $factor ) * 100, 5 ) + 0.001;
    $margin   = $margin / $factor;
  }

  // Margins in this theme vary according to viewport size; what we want here is the smallest possible margin (since this is the default media query we are returning)
  if ( !empty( $margin ) ) {
    $default = 'calc(' . $viewport . 'vw - ' . $margin . 'px)'; // `calc()` support: http://caniuse.com/#search=calc
  } else {
    $default = $viewport . 'vw'; // Without a pre-defined margin we'll just assume that images take up the full viewport on smaller screens
  }

  // Return the default `sizes` attribute
  return $default;
}
if ( PENDRELL_SCRIPTS_PICTUREFILL )
  add_filter( 'ubik_imagery_sizes_default', 'pendrell_sizes_default', 10, 4 );



// == MARGINS == //

// Two functions to allow for margins to be filtered
// These functions are somewhat unnecessary for this theme as the inner margin matches the outer page margin; just coding this for the sake of being complete
// @filter: pendrell_sizes_margin
// @filter: pendrell_sizes_margin_inner
// @constant: PENDRELL_BASELINE
function pendrell_sizes_margin() {
  return (int) apply_filters( 'pendrell_sizes_margin', PENDRELL_BASELINE );
}
function pendrell_sizes_margin_inner() {
  return (int) apply_filters( 'pendrell_sizes_margin_inner', PENDRELL_BASELINE );
}



// == CONTEXT == //

// Test the context object, which is either an array of contexts or a string (possibly comma-delimited)
function pendrell_sizes_context( $context = '', $test = '' ) {
  if ( !empty( $context ) && !empty( $test ) ) {
    if ( ( is_array( $context ) && in_array( $test, $context ) ) ) {
      return true;
    } elseif ( is_string( $context ) ) {
      if ( strpos( $test, $context ) !== false )
        return true;
    }
  }
  return false;
}



// == PLACEHOLDERS == //

// Placeholder icons
function pendrell_image_placeholder( $html = '' ) {

  global $post;

  // Post ID is set; let's use this for some conditional checks rather than relying on $post
  if ( !empty( $post ) ) {
    if ( has_tag( 'wordpress', $post->ID ) ) {
      $html = ubik_svg_icon( 'ion-social-wordpress', __( 'WordPress placeholder', 'pendrell' ) );
    } elseif ( has_tag( 'sass', $post->ID ) ) {
      $html = ubik_svg_icon( 'ion-social-sass', __( 'Sass placeholder', 'pendrell' ) );
    } elseif ( has_tag( 'development', $post->ID ) ) {
      $html = ubik_svg_icon( 'typ-spanner', __( 'Development placeholder', 'pendrell' ) );
    } elseif ( is_object_in_term( $post->ID, 'places' ) ) {
      $html = ubik_svg_icon( 'typ-location', __( 'Places placeholder', 'pendrell' ) );
    }
  }
  return $html;
}
//add_filter( 'ubik_imagery_placeholder', 'pendrell_image_placeholder' );
