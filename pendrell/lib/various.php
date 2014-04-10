<?php // ==== VARIOUS ==== //

// Body class filter
function pendrell_body_class( $classes ) {

  // Full width page templates (as specified below)
  if ( pendrell_is_full_width() ) {
    $classes[] = 'full-width';
  }

  if ( !is_multi_author() )
    $classes[] = 'single-author';

	return $classes;
}
add_filter( 'body_class', 'pendrell_body_class' );



// Abstracted function to test whether the current view is full-width
function pendrell_is_full_width() {
  // Allow other functions to pass the test
  $full_width_test = apply_filters( 'pendrell_full_width', $full_width_test = false );

  // If we're on a full-width page, attachment, single image post format, or there is no sidebar active let's expand the viewing window
  if (
    is_page_template( 'page-templates/full-width.php' )
    || ( is_attachment() && wp_attachment_is_image() )
    || ( is_singular() && has_post_format( 'image' ) )
    || ( is_tax( 'post_format' ) && has_post_format( 'image' ) )
    || !is_active_sidebar( 'sidebar-main' )
    || $full_width_test === true
  ) {
    return true;
  } else {
    return false;
  }
}



// This lets Pendrell know to make portfolio items full-width
function pendrell_portfolio_full_width() {
  $pendrell_portfolio_cats = array( 'design', 'photography', 'creative' );
  if (
    is_category( $pendrell_portfolio_cats )
    || ( is_singular() && in_category( $pendrell_portfolio_cats ) )
  ) {
    return true;
  } else {
    return false;
  }
}
add_filter( 'pendrell_full_width', 'pendrell_portfolio_full_width' );



// == UBIK == //

// Shortcode fallback; in case Ubik or other plugins are disabled
function pendrell_shortcode_fallback( $atts, $content = null ) {
  return $content;
}

function pendrell_shortcode_init() {
  // Conditional list of shortcodes to pass through
  if ( !function_exists( 'ubik_places_init') )
    add_shortcode( 'place', 'pendrell_shortcode_fallback' );
}
add_action( 'init', 'pendrell_shortcode_init' );
