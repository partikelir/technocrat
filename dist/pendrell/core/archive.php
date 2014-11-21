<?php // ==== ARCHIVES ==== //

// A simple wrapper for archive titles using Ubik Title; active in 404, archive, and search templates
if ( !function_exists( 'pendrell_archive_title' ) ) : function pendrell_archive_title() {
  if ( function_exists( 'ubik_title' ) ) {
    $title = ubik_title();
  } else {
    $title = single_cat_title( '', false ); // Lame fallback; under no circumstances should ubik_title not be found
  }
  echo '<h1 class="archive-title">' . $title . '</h1>';
} endif;



// Archive descriptions
if ( !function_exists( 'pendrell_archive_description' ) ) : function pendrell_archive_description() {

  // Archive descriptions for categories, tags, taxonomies
  if ( is_category() || is_tag() || is_tax() ) {

    // Initialize description
    $desc = '';

    // Check to see if we have a description for this category, tag, or taxonomy and apply a filter
    $desc = apply_filters( 'pendrell_term_archive_description', term_description() );

    // Got something?
    if ( !empty( $desc ) )
      $desc = '<div class="archive-description">' . $desc . '</div>';

    // Before the archive description (e.g. for breadcrumbs)
    do_action( 'pendrell_archive_description_before' );

    // Conditional output
    if ( !empty( $desc ) ) {
      ?><div class="archive-content"><?php echo apply_filters( 'pendrell_archive_description', $desc ); ?></div><?php
    }

  // Archive descriptions for individual authors
  } elseif ( is_author() ) {

    // Author edit link for users with the appropriate capabilities
    $edit_author_link = get_edit_user_link();
    if ( !empty( $edit_author_link ) )
      echo '<div class="entry-meta-buttons"><span class="edit-link button"><a href="' . $edit_author_link . '">' . __( 'Edit', 'pendrell' ) . '</a></span></div>';

    if ( get_the_author_meta( 'description' ) ) {
      ?><div class="archive-content">
        <?php pendrell_author_info(); ?>
      </div><?php
    }
  }
} endif;
