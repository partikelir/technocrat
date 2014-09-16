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



// Conditional archive descriptions
if ( !function_exists( 'pendrell_archive_description' ) ) : function pendrell_archive_description() {

  // Only output archive descriptions for categories, tags, taxonomies
  if ( is_category() || is_tag() || is_tax() ) {

    // Initialize content variable
    $content = '';

    // This filter allows for the insertion of breadcrumps and other things
    $content = apply_filters( 'pendrell_archive_term_before', $content );

    // Check to see if we have a description for this category, tag, or taxonomy
    $description = apply_filters( 'pendrell_archive_term_description', term_description() );

    // Got something?
    if ( !empty( $description ) )
      $content .= '<div class="archive-description">' . $description . '</div>';

    // Filter:
    $content = apply_filters( 'pendrell_archive_term_after', $content );

    // Conditional output
    if ( !empty( $content ) ) {
      ?><div class="archive-content"><?php echo $content; ?></div><?php
    }

  // Also output descriptions for authors
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

// Allow shortcodes in term descriptions
add_filter( 'term_description', 'shortcode_unautop' );
add_filter( 'term_description', 'do_shortcode' );



// Display an edit term link for authorized users
if ( !function_exists( 'pendrell_archive_edit_term_link' ) ) : function pendrell_archive_edit_term_link( $content = null ) {

  // Fetch the current query object
  $term = get_queried_object();

  // Bail if something went wrong
  if ( !$term )
    return $content;

  // Fetch the taxonomy and test the current user's capabilities
  $tax = get_taxonomy( $term->taxonomy );
  if ( !current_user_can( $tax->cap->edit_terms ) )
    return $content;

  // If we made it this far let's grab the raw edit term link
  $link = get_edit_term_link( $term->term_id, $term->taxonomy );

  // If that worked let's make it pretty
  if ( !empty( $link ) )
    $content .= "\n" . '<div class="entry-meta-buttons"><span class="edit-link button"><a href="' . $link . '">' . __( 'Edit', 'pendrell' ) . '</a></span></div>';

  return $content;
} endif;
add_filter( 'pendrell_archive_term_before', 'pendrell_archive_edit_term_link' );



// Empty term description prompt; a bit of a placeholder for a front-end term editor
if ( !function_exists( 'pendrell_archive_term_description' ) ) : function pendrell_archive_term_description( $content = null ) {

  if ( empty( $content ) ) {

    // Fetch the current query object
    $term = get_queried_object();

    // Bail if something went wrong
    if ( !$term )
      return $content;

    // Fetch the taxonomy and test the current user's capabilities
    $tax = get_taxonomy( $term->taxonomy );
    if ( !current_user_can( $tax->cap->edit_terms ) )
      return $content;

    $content = '<span class="warning">' . __( 'This term description is empty!', 'pendrell' ) . '</span>';
  }

  return $content;
} endif;
add_filter( 'pendrell_archive_term_description', 'pendrell_archive_term_description' );
