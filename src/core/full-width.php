<?php // ==== FULL WIDTH ==== //

// Abstracted function to test whether the current page is full-width; filter `pendrell_full_width` and return true to override
// @filter: pendrell_full_width
function pendrell_full_width() {

  // Unused in this theme
  return false;

  // If we're on a full-width page, attachment, single image post format, or there is no sidebar active let's expand content area
  if (
    (bool) apply_filters( 'pendrell_full_width', false ) === true
    || ( is_attachment() && wp_attachment_is_image() )
    || ( is_singular() && has_post_format( array( 'image', 'gallery' ) ) )
    || ( is_tax( 'post_format' ) && has_post_format( array( 'image', 'gallery' ) ) )
    || is_page_template( 'page-templates/full-width.php' )
  ) {
    $full_width = true;
  } else {
    $full_width = false;
  }

  // Provide one last chance to override the full-width test
  return (bool) apply_filters( 'pendrell_full_width_override', $full_width );
}



// Full-width body class filter; adds a full-width class for styling purposes
function pendrell_full_width_body_class( $classes ) {
  if ( pendrell_full_width() )
    $classes[] = 'full-width';
  return $classes;
}
add_filter( 'body_class', 'pendrell_full_width_body_class' );
