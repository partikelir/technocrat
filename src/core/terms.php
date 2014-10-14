<?php // ==== TERMS ==== //

// Display an edit term link for authorized users
if ( !function_exists( 'pendrell_term_archive_edit_link' ) ) : function pendrell_term_archive_edit_link( $content = null ) {

  // Fetch the current query object
  $term = get_queried_object();

  // Bail if something went wrong
  if ( empty( $term ) )
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

  echo $content;
} endif;
add_action( 'pendrell_archive_description_before', 'pendrell_term_archive_edit_link' );



// Empty term description prompt; a placeholder for a real front-end term editor with a much smaller impact
if ( !function_exists( 'pendrell_term_description_prompt' ) ) : function pendrell_term_description_prompt( $content = null ) {

  if ( empty( $content ) ) {

    // Fetch the current query object
    $term = get_queried_object();

    // Bail if something went wrong
    if ( empty( $term ) )
      return $content;

    // Fetch the taxonomy and test the current user's capabilities
    $tax = get_taxonomy( $term->taxonomy );
    if ( !current_user_can( $tax->cap->edit_terms ) )
      return $content;

    $content = '<span class="warning">' . __( 'This term description is empty!', 'pendrell' ) . '</span>';
  }

  return $content;
} endif;
add_filter( 'pendrell_term_archive_description', 'pendrell_term_description_prompt' );



// Allow shortcodes in term descriptions
add_filter( 'term_description', 'shortcode_unautop' );
add_filter( 'term_description', 'do_shortcode' );
