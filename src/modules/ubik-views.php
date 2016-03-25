<?php // ==== UBIK VIEWS ==== //

// Load plugin
require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-views/ubik-views.php' );
require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-views-shortcode.php' ); // @TODO: fix up this kludge



// == CONFIGURATION == //

// Set default views for different taxonomies; format is 'taxonomy' => array( 'term' => 'view' )
function pendrell_views_default( $default = array() ) {
  return array(
    'category'    => array(),
    'post_format' => array(
      'post-format-gallery' => 'gallery',
      'post-format-image'   => 'gallery'
    ),
    'post_tag'    => array()
  );
}
add_filter( 'ubik_views_default', 'pendrell_views_default' );

// Default home view
function pendrell_views_default_home() {
  return 'gallery';
}
//add_filter( 'ubik_views_default_home', 'pendrell_views_default_home' );



// == CONTROLS == //

// A user interface element for switching between views; this is displayed on archive pages and other places where they might be useful
function pendrell_views_buttons( $buttons ) {

  // Loop through the views and construct the list
  $links = ubik_views_links();
  if ( !empty( $links ) ) {
    foreach ( $links as $view => $data ) {
      //$buttons .= '<a class="button view-link" href="' . $data['link'] . '" rel="nofollow" role="button">' . $data['name'] . '</a>';
    }
  }
  return $buttons;
}
add_filter( 'pendrell_header_buttons', 'pendrell_views_buttons', 10 );

// Switch for next and previous pages
function pendrell_views_nav_content_switch( $switch, $id ) {
  if ( $id === 'nav-above' && ubik_views_test( 'gallery' ) )
    $switch = false;
  return $switch;
}
//add_filter( 'pendrell_nav_content_switch', 'pendrell_views_nav_content_switch', 10, 2 );



// == PRESENTATION == //

// View content class filter; adds classes to the main content element rather than body class (for compatibility with the full-width module)
function pendrell_views_body_class( $classes ) {
  if ( ubik_views_test( 'gallery' ) )
    $classes[] = 'gallery gallery-flex';
  return $classes;
}
add_filter( 'body_class', 'pendrell_views_body_class' );



// == QUERY == //

// Modify how many posts per page are displayed for different views; adapted from: http://wordpress.stackexchange.com/questions/21/show-a-different-number-of-posts-per-page-depending-on-context-e-g-homepage
function pendrell_views_pre_get_posts( $query ) {
  if ( ubik_views_test( 'gallery' ) ) {
    $query->set( 'ignore_sticky_posts', true );
    $query->set( 'posts_per_page', 18 ); // Best if this is a number divisible by both 2 and 3
  }
}
add_action( 'pre_get_posts', 'pendrell_views_pre_get_posts' );

// Template selector based on current view
function pendrell_views_template_part( $name ) {

  // Assign a template name unless the view is empty or not the default 'posts' view
  $current = ubik_views_current();
  if ( !empty( $current ) ) {
    if ( $current !== 'posts' )
      $name = $current;
  }
  return $name;
}
add_filter( 'pendrell_template_part', 'pendrell_views_template_part' );



// == LIST VIEW == //

// Entry meta for list view, called directly from the template; overwrites whatever $contents might already be there
function pendrell_views_list_meta( $contents ) {
  if ( ubik_views_test( 'list' ) )
    $contents = ubik_meta_date_published( _x( 'F j, Y', 'list view date format', 'pendrell' ) ); //strip_tags( $date[0], '<span><time>' ); // Publication date with any potential links stripped
  return $contents;
}
add_filter( 'pendrell_entry_header_meta', 'pendrell_views_list_meta', 999 );
