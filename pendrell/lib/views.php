<?php // ==== VIEWS ==== //

// == CONDITIONALS == //

// Views conditional test; is this a view and, if so, does it match the type supplied?
if ( !function_exists( 'pendrell_is_view' ) ) : function pendrell_is_view( $type = '' ) {
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



if ( !function_exists( 'pendrell_is_view_default' ) ) : function pendrell_is_view_default() {

  // Test the categories and tags set in functions-config.php
  global $pendrell_default_view_cats, $pendrell_default_view_tags;
  if ( is_array( $pendrell_default_view_cats ) ) {
    foreach ( $pendrell_default_view_cats as $cats => $view ) {
      if ( is_category( $cats ) )
        return true;
    }
  }
  if ( is_array( $pendrell_default_view_tags ) ) {
    foreach ( $pendrell_default_view_tags as $tags => $view ) {
      if ( is_tag( $tags ) )
        return true;
    }
  }
  return false; // No default view has been set
} endif;



// == QUERY VARIABLES == //

// Add the view query var
if ( !function_exists( 'pendrell_view_query_var' ) ) : function pendrell_view_query_var( $vars ) {
  $vars[] = "view";
  return $vars;
} endif;
add_filter( 'query_vars', 'pendrell_view_query_var' );



// Set default views under different conditions
if ( !function_exists( 'pendrell_view_default' ) ) : function pendrell_view_default() {

  // Set search results
  if ( is_search() && !pendrell_is_view() ) {
    set_query_var( 'view', 'list' );
  }

  // Test the categories and tags set in functions-config.php
  global $pendrell_default_view_cats, $pendrell_default_view_tags;
  if ( is_array( $pendrell_default_view_cats ) ) {
    foreach ( $pendrell_default_view_cats as $cats => $view ) {
      if ( is_category( $cats ) && !pendrell_is_view() )
        set_query_var( 'view', $view );
    }
  }
  if ( is_array( $pendrell_default_view_tags ) ) {
    foreach ( $pendrell_default_view_tags as $tags => $view ) {
      if ( is_tag( $tags ) && !pendrell_is_view() )
        set_query_var( 'view', $view );
    }
  }

} endif;
add_action( 'parse_query', 'pendrell_view_default' );



// Modify how many posts per page are displayed for different views; adapted from: http://wordpress.stackexchange.com/questions/21/show-a-different-number-of-posts-per-page-depending-on-context-e-g-homepage
if ( !function_exists( 'pendrell_view_pre_get_posts' ) ) : function pendrell_view_pre_get_posts( $query ) {
  if ( pendrell_is_view( 'gallery' ) ) {
    $query->set( 'ignore_sticky_posts', true );
    $query->set( 'posts_per_page', 15 );
  }
} endif;
add_action( 'pre_get_posts', 'pendrell_view_pre_get_posts' );



// == TEMPLATES & CLASSES == //

// Swap templates as needed based on query var
if ( !function_exists( 'pendrell_view_template' ) ) : function pendrell_view_template( $template ) {
  if ( pendrell_is_view( 'excerpt' ) )
    $template = 'excerpt';
  if ( pendrell_is_view( 'gallery' ) )
    $template = 'gallery';
  if ( pendrell_is_view( 'list' ) )
    $template = 'list';
  return $template;
} endif;
add_filter( 'pendrell_content_template', 'pendrell_view_template' );



// View content class filter; adds classes to the main content element rather than body class (for compatibility with the full-width module)
if ( !function_exists( 'pendrell_view_content_class' ) ) : function pendrell_view_content_class( $classes ) {
  if ( pendrell_is_view( 'excerpt' ) )
    $classes[] = 'excerpt-view';
  if ( pendrell_is_view( 'gallery' ) )
    $classes[] = 'gallery-view image-group image-group-columns-3';
  if ( pendrell_is_view( 'list' ) )
    $classes[] = 'list-view';
  return $classes;
} endif;
add_filter( 'pendrell_content_class', 'pendrell_view_content_class' );



// == FULL-WIDTH == //

// Force certain views to be full-width
if ( !function_exists( 'pendrell_view_full_width' ) ) : function pendrell_view_full_width( $full_width_test ) {

  // Return immediately if the test has already been passed
  if ( $full_width_test === true )
    return $full_width_test;

  // Test the view
  if ( pendrell_is_view( 'gallery' ) )
    return true;

  // Otherwise return false
  return false;
} endif;
add_filter( 'pendrell_full_width', 'pendrell_view_full_width' );



// == INTERFACE == //

// View switch prototype
if ( !function_exists( 'pendrell_view_switch' ) ) : function pendrell_view_switch() {

  // Do we want to display this function here?
  if ( is_search() || is_singular() )
    return;

  // Check the current view
  $current = get_query_var( 'view' );

  // If view isn't set then we're looking at a standard view
  if ( empty( $current ) )
    $current = 'standard';

  // Has a default view been set?
  if ( pendrell_is_view_default() ) {
    $current_url = add_query_arg( 'view', 'standard' );
  } else {
    $current_url = remove_query_arg( 'view' );
  }

  // Setup views
  $views = array(
    array(
      'name' => 'standard',
      'text' => __( 'Standard', 'pendrell' ),
      'icon' => 'dashicons-format-gallery',
      'url'  =>  $current_url
    ),
    array(
      'name' => 'gallery',
      'text' => __( 'Gallery', 'pendrell' ),
      'icon' => 'dashicons-format-gallery',
      'url'  => add_query_arg( 'view', 'gallery' )
    ),
    array(
      'name' => 'list',
      'text' => __( 'List', 'pendrell' ),
      'icon' => 'dashicons-list-view',
      'url'  => add_query_arg( 'view', 'list' )
    ),
    array(
      'name' => 'excerpt',
      'text' => __( 'Excerpt', 'pendrell' ),
      'icon' => 'dashicons-exerpt-view', // Sic
      'url'  => add_query_arg( 'view', 'excerpt' )
    )
  );

  $output = '';

  // Iterate through views to create the options list
  foreach ( $views as $view ) {
    if ( $view['name'] != $current ) {
      $output .= '<span class="' . $view['name'] . '-view-option button"><a href="' . $view['url'] . '" class="button"><span class="dashicons ' . $view['icon'] . '"></span> ' . $view['text'] . '</a></span> ';
    }
  }

  // Scaffolding
  $output = '<div class="view-options">' . $output . '</div>' . "\n";

  echo $output;

} endif;
add_action( 'pendrell_site_navigation_above', 'pendrell_view_switch' );
