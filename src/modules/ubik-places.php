<?php // ==== UBIK PLACES ==== //

define( 'PENDRELL_PLACES_TEMPLATE_ID', false );
require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-places/ubik-places.php' );
add_action( 'pendrell_archive_header', 'ubik_places_breadcrumb', 15 );

// Display the Ubik Places sidebar
function pendrell_sidebar_places( $sidebar ) {
  if ( is_tax( 'places' ) ) {

    // Retrieve data from Ubik Places
    $widgets = ubik_places_list();

    // Only output places widget markup if we have results; @TODO: turn this into a real widget
    if ( !empty( $widgets ) ) {
      $sidebar = '';
      foreach ( $widgets as $key => $widget ) {
        $index = ''; // A simple hack to insert a link to the places index page
        if ( $key === ( count( $widgets ) - 1 ) && PENDRELL_PLACES_TEMPLATE_ID !== false )
          $index = '<li class="cat-item"><strong><a href="' . get_permalink( PENDRELL_PLACES_TEMPLATE_ID ) . '">' . __( 'All places', 'pendrell' ) . '</a></strong></li>';
        $sidebar .= '<aside id="ubik-places" class="widget places-' . strtolower( $widget['name'] ) . '"><h2>' . $widget['title'] . '</h2><ul class="place-list">' . $index . wp_list_categories( array_merge( $widget['args'], array( 'echo' => 0 ) ) ) . '</ul></aside>';
      }
    }
  }
  return $sidebar;
}
add_filter( 'pendrell_sidebar', 'pendrell_sidebar_places' );

// Adds places to entry metadata right after other taxonomies; @DEPENDENCY: Ubik Terms
function pendrell_places_meta( $meta ) {
  if ( has_term( '', 'places' ) )
    $meta .= sprintf( __( 'Places: %s.', 'pendrell' ), ubik_meta_terms( 'places', '', ', ', '', 1 ) );
  return $meta;
}
add_filter( 'ubik_meta_taxonomies', 'pendrell_places_meta' );

// Body class filter
function pendrell_places_body_class( $classes ) {
  if ( is_page_template( 'page-templates/places.php' ) )
    $classes[] = 'gallery-flex';
  return $classes;
}
add_filter( 'body_class', 'pendrell_places_body_class' );

// Force places base template to be full-width
function pendrell_places_full_width( $test ) {
  if ( is_page_template( 'page-templates/places.php' ) )
    return true;
  return $test;
}
add_filter( 'pendrell_full_width', 'pendrell_places_full_width' );

// Add places to the views taxonomy
function pendrell_places_views( $taxonomies ) {
  $taxonomies[] = 'places';
  return $taxonomies;
}
add_filter( 'ubik_views_taxonomies', 'pendrell_places_views' );

// Adds place descriptions to the quick edit box
if ( PENDRELL_UBIK_QUICK_TERMS ) {
  function pendrell_places_quick_terms( $taxonomies ) {
    $taxonomies[] = 'places';
    return $taxonomies;
  }
  add_filter( 'ubik_quick_terms_taxonomies', 'pendrell_places_quick_terms' );
}
