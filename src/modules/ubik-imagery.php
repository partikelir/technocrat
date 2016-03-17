<?php // ==== UBIK IMAGERY ==== //

// Requirements:
// - Ubik Imagery: https://github.com/synapticism/ubik-imagery
// - Lazysizes: https://github.com/aFarkas/lazysizes
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

// Load plugin core
require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-imagery/ubik-imagery.php' );



// == RESPONSIVE IMAGES == //

// Setup a few media queries for the `sizes` attribute; must be an array even if there is only one value!
// @filter: pendrell_sizes_margin
// @filter: pendrell_sizes_margin_inner
// @constant: PENDRELL_BASELINE
function pendrell_sizes_media_queries( $queries = array(), $size = '', $width = '', $context = '' ) {

  // Exit early if we don't have the required size and width data
  if ( empty( $size ) || !is_string( $size ) || empty( $width ) || !is_int( $width ) )
    return $queries;

  // Limit width by the content width; what we're interested in here is the *rendered size* of an image which won't be larger than the container
  global $content_width, $medium_width;
  if ( PENDRELL_COLUMNS === 1 ) {
    $width = min( $width, $content_width );
  } else {
    $width = min( $width, $medium_width );
  }

  // Test the context object for various scenarios; this allows the theme to handle the sizes attribute in different ways depending on how images are being used
  $group          = ubik_imagery_context( $context, 'group' );
  $responsive     = ubik_imagery_context( $context, 'responsive' );
  $static         = ubik_imagery_context( $context, 'static' );

  // The margins can be filtered; this is mostly in case the inner margin (the space *between* grouped images) is not the same as the page margins
  // Example: your outer page margins are 30px on each side but the spacing between images is 20px
  // Such functionality is superfluous for this theme as margins are all handled in increments of 30px
  $margin         = (int) apply_filters( 'pendrell_sizes_margin', PENDRELL_BASELINE );
  $margin_inner   = (int) apply_filters( 'pendrell_sizes_margin_inner', PENDRELL_BASELINE );

  // Breakpoints replicated from `src/scss/config/_settings.scss`
  $tiny           = ceil( $medium_width / 1.5 );                    // 390px
  $small          = ceil( $medium_width / 1.2 );                    // 520px
  $medium         = $medium_width + ( $margin * 3 );                // 714px
  $large          = $medium_width + ( $margin * 8 );                // 864px
  $full           = $content_width + ( $margin * 3 );               // 990px

  // Usable space for each breakpoint; for comparison with $width
  $tiny_content   = ceil( $medium_width / 1.5 ) - $margin;          // 360px
  $small_content  = ceil( $medium_width / 1.2 ) - ( $margin * 2 );  // 460px
  $medium_content = $medium_width;                                  // 624px
  $large_content  = $medium_width + ( $margin * 5 );                // 774px

  // This first chunk of code deals with grouped fractional width images
  // These sizes are defined as fractions of $content_width by `ubik_imagery_add_fractional_sizes()`
  // Note: this only works when images are explicitly grouped (i.e. with the `[group]` shortcode or by passing `group` to the context object when calling `ubik_imagery`); otherwise they are treated like any other image
  if ( $group === true || $responsive === true ) {

    // Multiplier for use with fractional width sizes
    $factor = 2; // $group is true so we expect two images in a row by default
    if ( in_array( $size, array( 'third', 'third-square' ) ) )
      $factor = 3;
    if ( in_array( $size, array( 'quarter', 'quarter-square' ) ) )
      $factor = 4;

    // We lead with a media query specifying the minimum pixel width at which an image is *fixed* in size (not fluid)
    $queries[] = '(min-width: ' . $full . 'px) ' . $width . 'px';

    // A regular grouped gallery; a helper class like `gallery-columns-3` and the corresponding image size (e.g. `third-square`) must be passed to Ubik Imagery
    if ( $responsive === false ) {

      // This next media query handles the medium breakpoint
      // By this point all images are rendered smaller than their intrinsic sizes and will be sized based on a percentage width of the viewport minus page margins and margins between images within a group
      // For example: third-width images will take up one third of the viewport width minus one third of the total width of the inner margins (of which there will be two in this case) minus the *fixed* width of the page margins (hence the use of `calc`)
      // Note: these values won't add up to 100 due to the presence of the inner margins
      $viewport = round( ( 1 / $factor - ( ( ( $margin_inner * ( $factor - 1 ) ) / $content_width ) ) / $factor ) * 100, 5 );
      $queries[] = '(min-width: ' . $medium . 'px) calc(' . $viewport . 'vw - ' . round( ( $margin * 3 ) / $factor, 5 ) . 'px)';

      // Special case: for non-static galleries the 4 column layout collapses into a 2 column layout below the medium breakpoint
      if ( $static === false && $factor === 4 ) {
        $factor = 2;
        $viewport = round( ( 1 / $factor - ( ( ( $margin_inner * ( $factor - 1 ) ) / $content_width ) ) / $factor ) * 100, 5 );
      }

      // The 2 and 3 column layouts only need to have the page margins progressively reduced; everything else remains the same
      $queries[] = '(min-width: ' . $small . 'px) calc(' . $viewport . 'vw - ' . round( ( $margin * 2 ) / $factor, 5 ) . 'px)';
      $queries[] = '(min-width: ' . $tiny . 'px) calc(' . $viewport . 'vw - ' . round( $margin / $factor, 5 ) . 'px)';

    // Responsive gallery handling; no need to specify a column count or work with factors here
    // This is a special case that allows for a portfolio-like tiling of images in rows of 3, 2, or 1 image depending on the size of the viewport window
    // Naturally this works best with posts per page equally divisible by all three factors e.g. 6, 12, 18, etc.
    } else {

      // Medium:  3x columns, 3x page margins
      // Small:   2x columns, 2x page margins
      // Tiny:    2x columns, 1x page margins
      // Default: 1x columns, 1x page margins; handled by `pendrell_sizes_default()`
      $queries[] = '(min-width: ' . $medium . 'px) calc(' . round( ( 1 / 3 - ( ( ( $margin_inner * 2 ) / $content_width ) ) / 3 ) * 100, 5 ) . 'vw - ' . round( ( $margin * 3 ) / 3, 5 ) . 'px)';
      $queries[] = '(min-width: ' . $small . 'px) calc(' . round( ( 1 / 2 - ( ( $margin_inner / $content_width ) ) / 2 ) * 100, 5 ) . 'vw - ' . round( ( $margin * 2 ) / 2, 5 ) . 'px)';
      $queries[] = '(min-width: ' . $tiny . 'px) calc(' . round( ( 1 / 2 - ( ( $margin_inner / $content_width ) ) / 2 ) * 100, 5 ) . 'vw - ' . round( $margin / 2, 5 ) . 'px)';
    }
  } else {

    // As before, the first media query must specify the minimum width at which an image is rendered at the *requested* width
    // Page margins also introduce some ambiguity at the small and medium breakpoints
    // Note: viewport calculations will *not* add up to 100 due to the presence of margins around the content area
    if ( ( $width + ( $margin * 2 ) ) > $medium ) {
      $queries[] = '(min-width: ' . ( $width + ( $margin * 3 ) ) . 'px) ' . $width . 'px';
      $queries[] = '(min-width: ' . $medium . 'px) calc(100vw - ' . ( $margin * 3 ) . 'px)';
      $queries[] = '(min-width: ' . $small . 'px) calc(100vw - ' . ( $margin * 2 ) . 'px)';
    } elseif ( ( $width + $margin ) > $small ) {
      $queries[] = '(min-width: ' . ( $width + ( $margin * 2 ) ) . 'px) ' . $width . 'px';
      $queries[] = '(min-width: ' . $small . 'px) calc(100vw - ' . ( $margin * 2 ) . 'px)';
    } else {
      $queries[] = '(min-width: ' . ( $width + $margin ) . 'px) ' . $width . 'px';
    }
  }

  // Return an array of arrays (required by Ubik Imagery)
  return $queries;
}

// Default `sizes` attribute handling specific to Pendrell
function pendrell_sizes_default( $default = '', $size = '', $width = '', $context = '' ) {

  // Set bounding width
  global $content_width, $medium_width;
  if ( PENDRELL_COLUMNS === 1 ) {
    $bounding_width = $content_width;
  } else {
    $bounding_width = $medium_width;
  }

  // Default viewport width (integer)
  $viewport     = 100;

  // The margins can be filtered; this is mostly in case the inner margin (the space between grouped images) is not the same as the page margins
  // Example: your outer page margins are 30px on each side but the spacing between images is 20px
  $margin       = (int) apply_filters( 'pendrell_sizes_margin', PENDRELL_BASELINE );
  $margin_inner = (int) apply_filters( 'pendrell_sizes_margin_inner', PENDRELL_BASELINE );

  // Test the context object for various scenarios
  $content      = ubik_imagery_context( $context, 'content' ); // Defaults back to 100vw as images fill the viewport
  $group        = ubik_imagery_context( $context, 'group' );
  $static       = ubik_imagery_context( $context, 'static' );

  // Static galleries are a special case; for everything else we can safely default back to the full viewport minus basic page margins
  // This presumes that Ubik Imagery's sizing conventions are being followed; see: https://github.com/synapticism/ubik-imagery
  if ( $group === true && $static === true && $content === false ) {
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
  if ( !empty( $margin ) && $content === false ) {
    $default = 'calc(' . $viewport . 'vw - ' . $margin . 'px)'; // `calc()` support: http://caniuse.com/#search=calc
  } else {
    $default = $viewport . 'vw'; // Without a pre-defined margin we'll just assume that images take up the full viewport on smaller screens
  }

  // Return the default `sizes` attribute
  return $default;
}

// Activate the previous functions OR disable `srcset` and `sizes` output
if ( PENDRELL_RESPONSIVE_IMAGES ) {
  add_filter( 'ubik_imagery_sizes_media_queries', 'pendrell_sizes_media_queries', 10, 4 );
  add_filter( 'ubik_imagery_sizes_default', 'pendrell_sizes_default', 10, 4 );
} else {
  add_filter( 'ubik_imagery_srcset_html', '__return_empty_string' );
  add_filter( 'ubik_imagery_sizes_html', '__return_empty_string' );
}



// == LAZYSIZES == //

// WordPress will not provision `srcset` unless there are 2 or more sources; as such, smaller images won't have a `srcset` attribute
// This means we only want to swap the `srcset` attribute for a blank if there's already something there
// A blank `srcset` and filled `data-srcset` allows Lazysizes to lazy load responsive images
// Note: you can filter the "count" where lazyloading will begin; this allows you to load images "above the fold" like normal, skipping any JS trickery
// @filter: pendrell_image_lazysizes_count
function pendrell_image_lazysizes_srcset( $html = '' ) {
  static $counter = 1; // This counter is presumably triggered as often as the one in the next function
  if ( !empty( $html ) && $counter > apply_filters( 'pendrell_image_lazysizes_count', 1 ) )
    $html = 'data-' . $html . ' srcset="' . ubik_imagery_blank() . '" ';
  $counter++;
  return $html;
}
function pendrell_image_lazysizes_class( $classes = array() ) {
  static $counter = 1;
  if ( $counter > apply_filters( 'pendrell_image_lazysizes_count', 1 ) )
    $classes[] = 'lazyload'; // Activates Lazysizes on associated images
  $counter++;
  return $classes;
}
if ( PENDRELL_LAZYSIZES ) {
  add_filter( 'ubik_imagery_img_class', 'pendrell_image_lazysizes_class' ); // Activates Lazysizes; we could also add `data-sizes="auto"` but this seems buggy
  add_filter( 'ubik_imagery_srcset_html', 'pendrell_image_lazysizes_srcset' ); // Swap out the `srcset` attribute where available
  add_filter( 'ubik_imagery_dimensions', '__return_empty_string' ); // Force height/width attribute to conform to this theme's content width
}
add_filter( 'ubik_imagery_dimensions', '__return_empty_string' ); // Force height/width attribute to conform to this theme's content width



// == IMAGE WRAPPERS == //

// Dump structured data if the context prohibits it; we don't want certain item properties on related images
function pendrell_image_wrap_attributes( $attributes, $html, $id, $caption, $class, $align, $contents, $context, $width, $height  ) {
  if ( ubik_imagery_context( $context, 'related' ) && isset( $attributes['schema'] ) )
    $attributes['schema'] = str_replace( ' itemprop="image"', '', $attributes['schema'] );
  if ( !empty( $width ) )
    $attributes['style'] = 'style="width: ' . $width . 'px;"'; // Setting an explicit width for use with the intrinsic ratio technique
  return $attributes;
}
add_filter( 'ubik_imagery_wrap_attributes', 'pendrell_image_wrap_attributes', 10, 10 );



// Add intrinsic ratio wrapper to the image element itself
function pendrell_image_wrap_inner( $html, $width, $height ) {
  $padding = ubik_imagery_sizes_percent( $width, $height );
  if ( !empty( $padding ) )
    $html = '<div class="ubik-wrap-inner" style="padding-bottom: ' . $padding . '%;">' . $html . '</div>';
  return $html;
}
//add_filter( 'ubik_imagery_img_html', 'pendrell_image_wrap_inner', 10, 3 );



// Set default image size for the entire theme
function pendrell_image_default_size( $size ) {
  if ( PENDRELL_COLUMNS > 1 )
    return 'medium';
  return 'large';
}
add_filter( 'ubik_imagery_default_size', 'pendrell_image_default_size' );



// == PLACEHOLDERS == //

// Placeholder icons; not used in the master branch but likely used in specific implementations
function pendrell_image_placeholder( $html = '', $id = '' ) {

  global $post;

  // Post ID is set; let's use this for some conditional checks rather than relying on $post
  if ( !empty( $post ) ) {
    if ( has_tag( 'wordpress', $post->ID ) ) {
      return ubik_svg_icon( 'ion-social-wordpress', 'WordPress' );
    } elseif ( has_tag( 'sass', $post->ID ) ) {
      return ubik_svg_icon( 'ion-social-sass', 'Sass' );
    } elseif ( has_tag( 'nodejs', $post->ID ) ) {
      return ubik_svg_icon( 'ion-social-nodejs', 'JavaScript' );
    } elseif ( has_tag( 'javascript', $post->ID ) ) {
      return ubik_svg_icon( 'ion-social-javascript', 'JavaScript' );
    } elseif ( has_tag( 'html5', $post->ID ) ) {
      return ubik_svg_icon( 'ion-social-html5', 'HTML5' );
    } elseif ( has_tag( 'javascript', $post->ID ) ) {
      return ubik_svg_icon( 'ion-social-css3', 'CSS3' );
    } elseif ( has_tag( 'javascript', $post->ID ) ) {
      return ubik_svg_icon( 'ion-social-javascript', 'JavaScript' );
    }
  }
  return $html;
}
// Note: this doesn't work in the context of related posts as we don't have a working ID to check against
//add_filter( 'ubik_imagery_placeholder', 'pendrell_image_placeholder', 10, 2 );
