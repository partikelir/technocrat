<?php // ==== UBIK VIEWS ==== //

// Load plugin
require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-views/ubik-views.php' );



// Taxonomies to apply rewrite rules to (this way <website.com/category/kittens/list> works as you would expect)
function pendrell_views_taxonomies( $taxonomies = array() ) {
  return array( 'category', 'post_tag', 'post_format', 'places' );
}
add_filter( 'ubik_views_taxonomies', 'pendrell_views_taxonomies' );



// Set default views for different taxonomies; format is 'taxonomy' => array( 'term' => 'view' )
function pendrell_views_defaults( $defaults = array() ) {
  return array(
    'category'    => array(
      'photography'         => 'gallery' // Not live on s.com; only here as an example
    ),
    'post_format' => array(
      'post-format-gallery' => 'gallery',
      'post-format-image'   => 'gallery'
    ),
    'post_tag'    => array(
      'design'              => 'gallery',
      'photography'         => 'gallery',
      'eframe'              => 'gallery',
      'highlights'          => 'list'
    )
  );
}
add_filter( 'ubik_views_defaults', 'pendrell_views_defaults' );



// A user interface element for switching between views
function pendrell_views_navigation() {

  // Get links
  $nav_data = ubik_views_navigation();

  // Initialize output
  $output = '';

  // Loop through the views and construct the list
  if ( !empty( $nav_data ) ) {
    foreach ( $nav_data as $view => $data ) {
      $output .= '<li><a href="' . $data['link'] . '">' . $data['name'] . '</a></li>';
    }

    // Output wrapper
    $output = '<nav class="view-switcher">' . "\n" . '<ul>' . "\n" . $output . '</ul>' . "\n" . '</nav>' . "\n";
  }

  // Show what you got
  echo $output;
}
add_action( 'pendrell_archive_description_before', 'pendrell_views_navigation', 9 );



// Do not display views navigation on certain terms
function pendrell_views_navigation_display( $switch ) {
  if ( is_tag( array( 'culture-log', 'development', 'development-log', 'photography-log' ) ) )
    $switch = false;
  return $switch;
}
add_filter( 'ubik_views_navigation_display', 'pendrell_views_navigation_display' );



// View content class filter; adds classes to the main content element rather than body class (for compatibility with the full-width module)
function pendrell_views_content_class( $classes ) {
  if ( ubik_is_view( 'gallery' ) )
    $classes[] = 'gallery';
  return $classes;
}
add_filter( 'pendrell_content_class', 'pendrell_views_content_class' );



// Force certain views to be full-width
function pendrell_views_full_width( $full_width_test ) {
  if ( ubik_is_view( 'gallery' ) )
    return true;
  return $full_width_test;
}
add_filter( 'pendrell_full_width', 'pendrell_views_full_width' );



// Modify how many posts per page are displayed for different views; adapted from: http://wordpress.stackexchange.com/questions/21/show-a-different-number-of-posts-per-page-depending-on-context-e-g-homepage
function pendrell_views_pre_get_posts( $query ) {
  if ( ubik_is_view( 'gallery' ) ) {
    $query->set( 'ignore_sticky_posts', true );
    $query->set( 'posts_per_page', 18 ); // Best if this is a number divisible by both 2 and 3
  }
}
add_action( 'pre_get_posts', 'pendrell_views_pre_get_posts' );



// Modify entry meta format on list view
function pendrell_views_date_format( $format ) {
  if ( ubik_is_view( 'gallery' ) )
    $format = _x( 'M Y', 'gallery view date format', 'pendrell' );
  if ( ubik_is_view( 'list' ) )
    $format = _x( 'F jS, Y', 'list view date format', 'pendrell' );
  return $format;
}
add_filter( 'ubik_time_human_format', 'pendrell_views_date_format' );
add_filter( 'ubik_meta_full_format', 'pendrell_views_date_format' );



// Entry meta for list view, called directly from the template
function pendrell_entry_meta_list_view() {
  $date = ubik_meta_date();
  echo strip_tags( $date[0], '<span><time>' ); // Publication date with any potential links stripped
}



// Template selector based on current view
function pendrell_views_template_part( $name ) {

  // Get the current view
  $view = ubik_views_current();

  // Assign a template name unless the view is empty or not the default 'posts' view
  if ( !empty( $view ) ) {
    if ( $view !== 'posts' )
      $name = $view;
  }

  return $name;
}
add_filter( 'pendrell_template_part', 'pendrell_views_template_part' );
