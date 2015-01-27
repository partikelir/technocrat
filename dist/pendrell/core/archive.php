<?php // ==== ARCHIVES ==== //

// A simple wrapper for archive titles using Ubik Title; active in 404, archive, and search templates
// @filter: pendrell_archive_title
if ( !function_exists( 'pendrell_archive_title' ) ) : function pendrell_archive_title( $title = '' ) {
  if ( empty( $title ) ) {
    if ( function_exists( 'ubik_title' ) ) {
      $title = ubik_title();
    } else {
      $title = get_the_archive_title(); // Requires WP 4.1
    }
  }

  // Filter the title
  echo '<h1 class="archive-title">' . apply_filters( 'pendrell_archive_title', $title ) . '</h1>';
} endif;



// Archive descriptions
// @filter: pendrell_archive_description_term
// @filter: pendrell_archive_description
// @action: pendrell_archive_description_before
// @action: pendrell_archive_description_after
// @TODO: hook into new WordPress core archive description template tags: https://make.wordpress.org/core/2014/12/04/new-template-tags-in-4-1/
if ( !function_exists( 'pendrell_archive_description' ) ) : function pendrell_archive_description( $desc = '' ) {

  // Before the archive description (e.g. for breadcrumbs)
  do_action( 'pendrell_archive_description_before' );

  // Archive descriptions for categories, tags, taxonomies
  if ( is_category() || is_tag() || is_tax() ) {

    // Check to see if we have a description for this category, tag, or taxonomy and filter the results
    if ( empty( $desc ) )
      $desc = apply_filters( 'pendrell_archive_description_term', term_description() );
  }

  // Archive descriptions for individual authors (direct output; must skip conditional description output)
  if ( is_author() ) {
    if ( get_the_author_meta( 'description' ) ) {
      echo '<div class="archive-desc">';
      pendrell_author_info();
      echo '</div>';
    }

  // Output the archive description for most terms
  } elseif ( !empty( $desc ) ) {
    echo '<div class="archive-desc">' . apply_filters( 'pendrell_archive_description', $desc ) . '</div>';
  }

  // After the archive description
  do_action( 'pendrell_archive_description_after' );

} endif;
