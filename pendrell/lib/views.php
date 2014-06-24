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
        return true;
      } else {
        return false;
      }
    }
    return true;
  }
  return false;
} endif;
add_filter( 'query_vars', 'pendrell_views_query_var' );



// Swap templates as needed based on query var
if ( !function_exists( 'pendrell_views_template' ) ) : function pendrell_views_template( $template ) {
  if ( pendrell_is_view( 'list' ) || is_search() )
    $template = 'list';
  if ( pendrell_is_view( 'thumb' ) )
    $template = 'thumb';
  return $template;
} endif;
add_filter( 'pendrell_content_template', 'pendrell_views_template' );



// Force certain views to be full-width
if ( !function_exists( 'pendrell_views_full_width' ) ) : function pendrell_views_full_width() {
  if ( pendrell_is_view( 'thumb' ) )
    return true;
  return false;
} endif;
add_filter( 'pendrell_full_width', 'pendrell_views_full_width' );



// Full-width body class filter; adds a full-width class for styling purposes
if ( !function_exists( 'pendrell_views_body_class' ) ) : function pendrell_views_body_class( $classes ) {
  if ( pendrell_is_view( 'list' ) )
    $classes[] = 'list-view';
  if ( pendrell_is_view( 'thumb' ) )
    $classes[] = 'thumb-view';
  return $classes;
} endif;
add_filter( 'body_class', 'pendrell_views_body_class' );
