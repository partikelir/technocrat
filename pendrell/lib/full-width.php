<?php // ==== FULL WIDTH ==== //

// Abstracted function to test whether the current view is full-width
if ( !function_exists( 'pendrell_is_full_width' ) ) : function pendrell_is_full_width() {
  // Allow other functions to pass the test
  $full_width_test = apply_filters( 'pendrell_full_width', $full_width_test = false );

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



// Force certain categories to be full-width; a good example of how to filter the pendrell_is_full_width conditional
if ( !function_exists( 'pendrell_full_width_cats' ) ) : function pendrell_full_width_cats() {
  $pendrell_portfolio_cats = array( 'design', 'photography', 'creative' );
  if (
    is_category( $pendrell_portfolio_cats )
    || ( is_singular() && in_category( $pendrell_portfolio_cats ) )
  ) {
    return true;
  } else {
    return false;
  }
} endif;
add_filter( 'pendrell_full_width', 'pendrell_full_width_cats' );



// Full-width thumbnails filter; assumes 'large' size images fill the window, which they should
if ( !function_exists( 'pendrell_fill_width_thumbnail_size' ) ) : function pendrell_full_width_thumbnail_size( $size ) {
  if ( pendrell_is_full_width() ) {
    if ( $size === 'medium' )
      $size = 'large';
  } else {
    // Try to catch images that are exactly 960 px
    if ( $size === 'large' || $size === 'full' ) {
      $size = 'medium';
    }
  }
  return $size;
} endif;
add_filter( 'post_thumbnail_size', 'pendrell_full_width_thumbnail_size' );
add_filter( 'ubik_image_markup_size', 'pendrell_full_width_thumbnail_size' );



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
