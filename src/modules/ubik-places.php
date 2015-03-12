<?php // ==== UBIK PLACES ==== //

define( 'PENDRELL_PLACES_TEMPLATE_ID', false );
require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-places/ubik-places.php' );
add_action( 'pendrell_archive_description_before', 'ubik_places_breadcrumb' );

// Display the Ubik Places sidebar
function pendrell_sidebar_places( $sidebar ) {
  if ( is_tax( 'places' ) && !pendrell_is_full_width() ) {

    // Retrieve data from Ubik Places
    $places = ubik_places_list();

    // Only output places widget markup if we have results; @TODO: turn this into a real widget
    if ( !empty( $places ) ) {
      ?><div id="wrap-sidebar" class="wrap-sidebar">
        <div id="secondary" class="widget-area" role="complementary">
          <aside id="ubik-places" class="widget">
            <?php if ( !empty( $places ) ) {
              foreach ( $places as $key => $place ) {
                $places_index = ''; // A simple hack to insert a link to the places index page
                if ( $key === ( count( $places ) - 1 ) && PENDRELL_PLACES_TEMPLATE_ID !== false )
                  $places_index = '<li class="cat-item"><strong><a href="' . get_permalink( PENDRELL_PLACES_TEMPLATE_ID ) . '">' . __( 'All places', 'pendrell' ) . '</a></strong></li>';
                ?><h2><?php echo $place['title']; ?></h2>
                <ul class="place-list">
                  <?php echo $places_index; echo wp_list_categories( $place['args'] ); ?>
                </ul><?php
              }
            } ?>
          </aside>
        </div>
      </div><?php
    }

    // Return false to prevent the regular sidebar from displaying
    $sidebar = false;
  }
  return $sidebar;
}
add_filter( 'pendrell_sidebar', 'pendrell_sidebar_places' );

// Adds places to entry metadata right after other taxonomies; @DEPENDENCY: relies on popular terms function in Ubik core
function pendrell_places_meta( $meta ) {
  if ( has_term( '', 'places' ) )
    $meta .= ubik_terms_popular_list( get_the_ID(), 'places', 'Places: ', ', ', '. ' );
  return $meta;
}
add_filter( 'ubik_meta_taxonomies', 'pendrell_places_meta' );

// Body class filter
function pendrell_places_body_class( $classes ) {
  if ( is_page_template( 'page-templates/places.php' ) )
    $classes[] = 'gallery-view';
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

// Adds place descriptions to the quick edit box
if ( PENDRELL_UBIK_QUICK_TERMS ) {
  function pendrell_places_quick_terms( $taxonomies ) {
    $taxonomies[] = 'places';
    return $taxonomies;
  }
  add_filter( 'ubik_quick_terms_taxonomies', 'pendrell_places_quick_terms' );
}
