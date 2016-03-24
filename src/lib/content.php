<?php // ==== ENTRY CONTENT ==== //

// == HEADER == //

// Entry title; displayed at the top of posts, pages, etc.
// @filter: pendrell_entry_title
// @action: pendrell_entry_title_before
// @action: pendrell_entry_title_after
function pendrell_entry_title( $title = '' ) {
  if ( empty( $title ) )
    $title = get_the_title();
  if ( !is_singular() )
    $title = '<a href="' . get_permalink() . '" rel="bookmark">' . $title . '</a>';
  echo apply_filters( 'pendrell_entry_title', '<h2 class="entry-title p-name" itemprop="headline">' . $title . '</h2>' );
}
add_action( 'pendrell_entry_header', 'pendrell_entry_title' );



// Display metadata below the entry title
function pendrell_entry_header_meta() {
  $output = apply_filters( 'pendrell_entry_header_meta', '' ); // Hook for other functions to add metadata
  if ( !empty( $output ) )
    echo '<div class="entry-header-meta" role="contentinfo">' . $output . '</div>';
}
add_action( 'pendrell_entry_header', 'pendrell_entry_header_meta', 12 );



// Default metadata to display in this theme
function pendrell_entry_header_metadata( $contents ) {

  // Initialize
  $output = '';

  // Special handling for pages and attachments
  if ( is_page() || is_attachment() ) {

    // Get the parent (might be empty in the case of pages)
    $parent = ubik_meta_parent();
    if ( !empty( $parent ) )
      $output = '<span class="parent">' . sprintf( __( 'Return to %s', 'pendrell' ), $parent ) . '&nbsp;&larrhk;</span>';

  // Everything else should have a date of publication
  } else {
    $output = '<span class="date">' . ucfirst( ubik_meta_date_published( 'F j, Y' ) ) . '</span>';
  }

  return $contents . $output;
}
add_filter( 'pendrell_entry_header_meta', 'pendrell_entry_header_metadata' );



// == FOOTER == //

// Entry footer meta
function pendrell_entry_footer_meta() {
  echo '<div class="entry-footer-meta">' . ubik_meta() . '</div>'; // role="contentinfo" should be implied by the `footer` wrapping element
}
add_action( 'pendrell_entry_footer', 'pendrell_entry_footer_meta', 10 );



// Entry footer buttons
// @filter: pendrell_entry_footer_buttons
function pendrell_entry_footer_buttons() {
  $buttons = apply_filters( 'pendrell_entry_footer_buttons', '' );
  if ( !empty( $buttons ) )
    echo '<div class="buttons buttons-merge">' . $buttons . '</div>';
}
add_action( 'pendrell_entry_footer', 'pendrell_entry_footer_buttons', 5 );



// Return the edit post link; adapted from WordPress core
function pendrell_entry_edit_link( $buttons ) {
  if ( ! $post = get_post() )
    return $buttons;
  if ( ! $url = get_edit_post_link( $post->ID ) )
    return $buttons;
  $buttons .= '<a class="button" href="' . $url . '" rel="nofollow" role="button">' . pendrell_icon_text( 'content-edit', __( 'Edit', 'pendrell' ) ) . '</a>';
  return $buttons;
}
add_filter( 'pendrell_entry_footer_buttons', 'pendrell_entry_edit_link', 9 );



// == VARIOUS == //

// Better password form based on WordPress core function; @TODO: spin this off into Ubik
function pendrell_entry_password_form( $output = '' ) {

  // Work with the global post object
  global $post;

  // Exit early if something goes wrong
  if ( empty( $post ) )
    return $output;

  // Generate an ID
  $id = 'password-input-' . ( empty( $post->ID ) ? rand() : $post->ID );

  // Descriptive prompt
  $prompt = '<p>' . __( 'This content is protected. Please enter the password:', 'pendrell' ) . '</p>';

  // Label for the form input field
  $label = '<label for="' . $id . '" class="screen-reader-text">' . __( 'Password', 'pendrell' ) . '</label>';

  // The actual input field itself
  $input = '<input name="post_password" id="' . $id . '" type="password" size="20" required="" />';

  // Submit button
  $submit = '<button id="submit" type="submit" name="submit">' . apply_filters( 'pendrell_entry_password_form_button', __( 'Enter password', 'pendrell' ) ) . '</button>';

  // Form wrapper
  $output = $prompt . '<form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" class="form-post-password" method="post">' . $label . $input . $submit . '</form>';

  return $output;
}
add_filter( 'the_password_form', 'pendrell_entry_password_form' );



// Add an icon to the password entry button
function pendrell_entry_password_form_button( $contents ) {
  return pendrell_icon_text( 'content-protected', $contents );
}
add_filter( 'pendrell_entry_password_form_button', 'pendrell_entry_password_form_button' );



// Microformats2 compatibility
function pendrell_entry_post_class( $classes ) {
  $classes[] = 'h-entry';
  return $classes;
}
add_filter( 'post_class', 'pendrell_entry_post_class' );
