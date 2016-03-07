<?php // ==== ARCHIVES ==== //

// A simple wrapper for archive titles using Ubik Title; active in 404, search, and the links page template
// @filter: pendrell_archive_title
function pendrell_archive_title( $title = '' ) {
  if ( empty( $title ) ) {
    if ( function_exists( 'ubik_title' ) ) {
      $title = ubik_title();
    } else {
      $title = get_the_archive_title(); // Requires WP 4.1
    }
  }
  echo '<h1 class="archive-title">' . apply_filters( 'pendrell_archive_title', $title ) . '</h1>';
}
add_action( 'pendrell_archive_header', 'pendrell_archive_title', 10 );



// Archive buttons; a hook for interactive elements like edit links
// @filter: pendrell_archive_buttons
function pendrell_archive_buttons() {
  $buttons = apply_filters( 'pendrell_archive_buttons', '' );
  if ( !empty( $buttons ) )
    echo '<div class="buttons buttons-merge">' . $buttons . '</div>';
}
add_action( 'pendrell_archive_header', 'pendrell_archive_buttons', 5 );



// Archive descriptions
// @filter: pendrell_archive_description
function pendrell_archive_description( $desc = '' ) {

  // Archive descriptions for categories, tags, taxonomies
  if ( is_category() || is_tag() || is_tax() ) {

    // Check to see if we have a description for this category, tag, or taxonomy and filter the results
    if ( empty( $desc ) )
      $desc = get_the_archive_description();
  }

  // Archive descriptions for individual authors
  if ( is_author() && get_the_author_meta( 'description' ) ) {
    echo '<div class="archive-desc">' . pendrell_author_info() . '</div>';

  // Output the archive description for most terms
  } elseif ( !empty( $desc ) ) {
    echo '<div class="archive-desc">' . apply_filters( 'pendrell_archive_description', $desc ) . '</div>';
  }
}
add_action( 'pendrell_archive_header', 'pendrell_archive_description', 20 );
