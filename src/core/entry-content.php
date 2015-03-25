<?php // ==== ENTRY CONTENT ==== //

// == HEADER == //

// Entry title; displayed at the top of posts, pages, etc.
// @filter: pendrell_entry_title
// @action: pendrell_entry_title_before
// @action: pendrell_entry_title_after
if ( !function_exists( 'pendrell_entry_title' ) ) : function pendrell_entry_title( $title = '' ) {
  if ( empty( $title ) )
    $title = get_the_title();
  if ( !is_singular() )
    $title = '<a href="' . get_permalink() . '" rel="bookmark">' . $title . '</a>';
  echo apply_filters( 'pendrell_entry_title', '<h1 class="entry-title p-name">' . $title . '</h1>' );
} endif;
add_action( 'pendrell_entry_header', 'pendrell_entry_title' );



// Display a date above the title
if ( !function_exists( 'pendrell_entry_header_meta' ) ) : function pendrell_entry_header_meta() {

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
    $output = '<span class="date">' . ubik_meta_date_published( 'F j, Y' ) . '</span>';
  }

  // Only add the footer if we have something to output
  if ( !empty( $output ) )
    echo '<footer class="entry-meta">' . $output . '</footer>';
} endif;
add_action( 'pendrell_entry_header', 'pendrell_entry_header_meta', 12 );



// == FOOTER == //

// Entry footer meta
if ( !function_exists( 'pendrell_entry_footer_meta' ) ) : function pendrell_entry_footer_meta() {
  echo '<div class="entry-meta">' . ubik_meta() . '</div>';
} endif;
add_action( 'pendrell_entry_footer', 'pendrell_entry_footer_meta', 10 );



// Entry footer buttons
// @filter: pendrell_entry_footer_buttons
if ( !function_exists( 'pendrell_entry_footer_buttons' ) ) : function pendrell_entry_footer_buttons() {
  $buttons = apply_filters( 'pendrell_entry_footer_buttons', '' );
  if ( !empty( $buttons ) )
    echo '<div class="buttons buttons-merge">' . $buttons . '</div>';
} endif;
add_action( 'pendrell_entry_footer', 'pendrell_entry_footer_buttons', 5 );



// Return the edit post link; adapted from WordPress core
if ( !function_exists( 'pendrell_entry_edit_link' ) ) : function pendrell_entry_edit_link( $buttons ) {
  if ( ! $post = get_post() )
    return $buttons;
  if ( ! $url = get_edit_post_link( $post->ID ) )
    return $buttons;
  $buttons .= '<a class="button post-edit-link" href="' . $url . '" rel="nofollow" role="button">' . pendrell_icon( 'content-edit', __( 'Edit', 'pendrell' ) ) . '</a>';
  return $buttons;
} endif;
add_filter( 'pendrell_entry_footer_buttons', 'pendrell_entry_edit_link', 9 );



// == VARIOUS == //

// Better password form based on WordPress core function; @TODO: spin this off into Ubik
if ( !function_exists( 'pendrell_entry_password_form' ) ) : function pendrell_entry_password_form( $output = '' ) {

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
  $submit = '<button id="submit" type="submit" name="submit">' . pendrell_icon( 'content-protected', __( 'Enter password', 'pendrell' ) ) . '</button>';

  // Form wrapper
  $output = $prompt . '<form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" class="form-post-password" method="post">' . $label . $input . $submit . '</form>';

  return $output;
} endif;
add_filter( 'the_password_form', 'pendrell_entry_password_form' );



// Microformats2 compatibility
if ( !function_exists( 'pendrell_entry_post_class' ) ) : function pendrell_entry_post_class( $classes ) {
  $classes[] = 'h-entry';
  return $classes;
} endif;
add_filter( 'post_class', 'pendrell_entry_post_class' );
