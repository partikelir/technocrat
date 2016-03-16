<?php // ==== MAIN ==== //

// Render the main header; this is only called from `index.php`
// @action: pendrell_404_header
// @action: pendrell_archive_header
// @action: pendrell_home_header
// @action: pendrell_search_header
function pendrell_main_header() {
  if ( !is_comments_popup() && !is_singular() ) {
    ?><header class="main-header"><?php
      if ( is_404() )
        do_action( 'pendrell_404_header' );
      if ( is_archive() )
        do_action( 'pendrell_archive_header' );
      if ( is_home() )
        do_action( 'pendrell_home_header' );
      if ( is_search() )
        do_action( 'pendrell_search_header' );
    ?></header><?php
  }
}
add_action( 'pendrell_main_before', 'pendrell_main_header' );



// A simple filter for adding titles to archive, search, and other pages using Ubik Title; activate with action hooks
// @filter: pendrell_main_title
function pendrell_main_title( $title = '' ) {
  if ( empty( $title ) ) {
    if ( function_exists( 'ubik_title' ) ) {
      $title = ubik_title();
    } else {
      $title = get_the_archive_title(); // Requires WP 4.1
    }
  }
  echo '<h1 class="main-title">' . apply_filters( 'pendrell_main_title', $title ) . '</h1>';
}
add_action( 'pendrell_archive_header', 'pendrell_main_title', 10 );
add_action( 'pendrell_search_header', 'pendrell_main_title', 10 );



// == ARCHIVES == //

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
    echo '<div class="main-desc">' . pendrell_author_info() . '</div>';

  // Output the archive description for most terms
  } elseif ( !empty( $desc ) ) {
    echo '<div class="main-desc">' . apply_filters( 'pendrell_archive_description', $desc ) . '</div>';
  }
}
add_action( 'pendrell_archive_header', 'pendrell_archive_description', 20 );
