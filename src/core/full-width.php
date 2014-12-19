<?php // ==== FULL WIDTH ==== //

// Abstracted function to test whether the current view is full-width
// @filter: pendrell_full_width
if ( !function_exists( 'pendrell_is_full_width' ) ) : function pendrell_is_full_width() {

  // Allow other functions to pass the test
  $full_width_test = (bool) apply_filters( 'pendrell_full_width', $full_width_test = false );

  // If we're on a full-width page, attachment, single image post format, or there is no sidebar active let's expand the viewing window
  if (
    is_page_template( 'page-templates/full-width.php' )
    || ( is_attachment() && wp_attachment_is_image() )
    || ( is_singular() && has_post_format( array( 'image', 'gallery' ) ) )
    || ( is_tax( 'post_format' ) && has_post_format( array( 'image', 'gallery' ) ) )
    || !is_active_sidebar( 'sidebar-main' )
    || $full_width_test === true
  ) {
    return true;
  } else {
    return false;
  }
} endif;



// Force certain categories and tags to be full-width; a good example of how to filter the pendrell_is_full_width conditional
if ( !function_exists( 'pendrell_full_width_content' ) ) : function pendrell_full_width_content( $full_width_test ) {

  // Return immediately if the test has already been passed
  if ( $full_width_test === true )
    return $full_width_test;

  // Test the categories and tags set in functions-config.php
  global $pendrell_full_width_cats, $pendrell_full_width_tags;

  // Test categories
  if ( !empty( $pendrell_full_width_cats ) ) {
    if ( is_category( $pendrell_full_width_cats ) || ( is_singular() && in_category( $pendrell_full_width_cats ) ) )
      return true;
  }

  // Test tags
  if ( !empty( $pendrell_full_width_tags ) ) {
    if ( is_tag( $pendrell_full_width_tags ) || ( is_singular() && has_tag( $pendrell_full_width_tags ) ) )
      return true;
  }

  return $full_width_test;

} endif;
add_filter( 'pendrell_full_width', 'pendrell_full_width_content' );



// Full-width body class filter; adds a full-width class for styling purposes
if ( !function_exists( 'pendrell_full_width_body_class' ) ) : function pendrell_full_width_body_class( $classes ) {
  if ( pendrell_is_full_width() )
    $classes[] = 'full-width';
  return $classes;
} endif;
add_filter( 'body_class', 'pendrell_full_width_body_class' );



// Sidebar filter; removes sidebar on full-width view
if ( !function_exists( 'pendrell_full_width_sidebar' ) ) : function pendrell_full_width_sidebar( $sidebar ) {
  if ( pendrell_is_full_width() )
    $sidebar = false;
  return $sidebar;
} endif;
add_filter( 'pendrell_sidebar', 'pendrell_full_width_sidebar' );



// Full-width image size filter; assumes 'large' size images fill the window, which they should
if ( !function_exists( 'pendrell_full_width_image_resize' ) ) : function pendrell_full_width_image_resize( $size ) {
  if ( pendrell_is_full_width() ) {
    if ( $size === 'medium' )
      $size = 'large';
  } else {
    if ( $size === 'large' || $size === 'full' ) // Try to catch images that are exactly 960 px
      $size = 'medium';
  }
  return $size;
} endif;
add_filter( 'ubik_imagery_size', 'pendrell_full_width_image_resize' );
