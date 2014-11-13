<?php // ==== UBIK ==== //

// A small collection of functions to improve integration between Pendrell and Ubik components

// == PLACES SIDEBAR == //

// Don't display regular sidebar on full-width items
function ubik_places_sidebar( $sidebar ) {
  if ( is_tax( 'places' ) && !pendrell_is_full_width() ) {
    ubik_places_widget();
    $sidebar = false;
  }
  return $sidebar;
}
if ( PENDRELL_UBIK_PLACES )
  add_filter( 'pendrell_sidebar', 'ubik_places_sidebar' );
