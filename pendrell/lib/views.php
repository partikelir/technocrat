<?php // ==== VIEWS ==== //

// Add a "view" query var
if ( !function_exists( 'pendrell_views_query_var' ) ) : function pendrell_views_query_var( $vars ) {
  $vars[] = "view";
  return $vars;
} endif;
add_filter( 'query_vars', 'pendrell_views_query_var' );



// Views conditional test; is this a view and, if so, does it match the type supplied?
if ( !function_exists( 'pendrell_is_view' ) ) : function pendrell_is_view( $type ) {
  $view = get_query_var( 'view' );
  if ( !empty( $view ) ) {
    if ( !empty( $type ) ) {
      if ( $view === $type ) {
        return true; // This is the specified type
      } else {
        return false; // This is a view but it is not the specified type
      }
    }
    return true; // This is a view
  }
  return false; // This is not a view
} endif;
add_filter( 'query_vars', 'pendrell_views_query_var' );



// Swap templates as needed based on query var
if ( !function_exists( 'pendrell_views_template' ) ) : function pendrell_views_template( $template ) {
  if ( pendrell_is_view( 'gallery' ) )
    $template = 'gallery';
  if ( pendrell_is_view( 'list' ) )
    $template = 'list';
  if ( pendrell_is_view( 'summary' ) || is_search() )
    $template = 'summary';
  return $template;
} endif;
add_filter( 'pendrell_content_template', 'pendrell_views_template' );



// Force certain views to be full-width
if ( !function_exists( 'pendrell_views_full_width' ) ) : function pendrell_views_full_width( $full_width_test ) {

  // Return immediately if the test has already been passed
  if ( $full_width_test === true )
    return $full_width_test;

  // Test the view
  if ( pendrell_is_view( 'gallery' ) )
    return true;

  // Otherwise return false
  return false;
} endif;
add_filter( 'pendrell_full_width', 'pendrell_views_full_width' );



// Full-width body class filter; adds a full-width class for styling purposes
if ( !function_exists( 'pendrell_views_content_class' ) ) : function pendrell_views_content_class( $classes ) {
  if ( pendrell_is_view( 'gallery' ) )
    $classes[] = 'gallery-view';
  if ( pendrell_is_view( 'list' ) )
    $classes[] = 'list-view';
  if ( pendrell_is_view( 'summary' ) )
    $classes[] = 'summary-view';
  return $classes;
} endif;
add_filter( 'pendrell_content_class', 'pendrell_views_content_class' );



// Modify how many posts per page are displayed for different views; adapted from: http://wordpress.stackexchange.com/questions/21/show-a-different-number-of-posts-per-page-depending-on-context-e-g-homepage
if ( !function_exists( 'pendrell_views_pre_get_posts' ) ) : function pendrell_views_pre_get_posts( $query ) {
  if ( pendrell_is_view( 'gallery' ) ) {
    $query->set( 'ignore_sticky_posts', true );
    $query->set( 'posts_per_page', 15 );
  }
} endif;
add_action( 'pre_get_posts', 'pendrell_views_pre_get_posts' );
