<?php // ==== ARCHIVES ==== //

// Generates all-purpose archive titles
if ( !function_exists( 'pendrell_archive_title' ) ) : function pendrell_archive_title() {
  if ( is_day() ) {
    $title = sprintf( __( 'Daily archives: %s', 'pendrell' ), get_the_date() );
  } elseif ( is_month() ) {
    $title = sprintf( __( 'Monthly archives: %s', 'pendrell' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'pendrell' ) ) );
  } elseif ( is_year() ) {
    $title = sprintf( __( 'Yearly archives: %s', 'pendrell' ), get_the_date( _x( 'Y', 'yearly archives date format', 'pendrell' ) ) );
  } elseif ( is_author() ) {
    $title = sprintf( __( 'Posts by %s', 'pendrell' ), get_the_author() );
  } elseif ( is_category() ) {
    $title = sprintf( __( '%s', 'pendrell' ), single_cat_title( '', false ) );
  } elseif ( is_post_type_archive() ) {
    $title = sprintf( __( '%s', 'pendrell' ), post_type_archive_title( '', false ) );
  } elseif ( is_tag() ) {
    $title = sprintf( __( '%s', 'pendrell' ), single_tag_title( '', false ) );
  } elseif ( is_tax() ) {
    if ( is_tax( 'post_format' ) && get_post_format() === 'quote' ) {
      $title = sprintf( __( '%s archives', 'pendrell' ), __( 'Quotation', 'pendrell' ) );
    } else {
      $title = sprintf( __( '%s archives', 'pendrell' ), single_term_title( '', false ) );
    }
  } else {
    $title = __( 'Archives', 'pendrell' );
  }
  echo '<h1 class="archive-title">' . apply_filters( 'pendrell_archive_title', $title ) . '</h1>';
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
