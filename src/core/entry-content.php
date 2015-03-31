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



// Display metadata below the entry title
if ( !function_exists( 'pendrell_entry_header_meta' ) ) : function pendrell_entry_header_meta() {
  $output = apply_filters( 'pendrell_entry_header_meta', '' ); // Hook for other functions to add metadata
  if ( !empty( $output ) )
    echo '<footer class="entry-meta">' . $output . '</footer>';
} endif;
add_action( 'pendrell_entry_header', 'pendrell_entry_header_meta', 12 );



// Default metadata to display in this theme
if ( !function_exists( 'pendrell_entry_header_metadata' ) ) : function pendrell_entry_header_metadata( $contents ) {

  // Initialize
  $output = '';

  // Special handling for pages and attachments
  if ( is_page() || is_attachment() ) {

    // Get the parent (might be empty in the case of pages)
    $parent = ubik_meta_parent();
    if ( !empty( $parent ) )
      $output = '<div class="parent">' . sprintf( __( 'Return to %s', 'pendrell' ), $parent ) . '&nbsp;&larrhk;</div>';

  // Everything else
  } else {
    $date = '<div class="date"><time datetime="' . date( 'c', get_the_time( 'U' ) ) . '">' . get_the_time( 'M j Y' ) . '</time></div>';
    $cats = get_the_category_list( '</div> <div class="cats">' );
    if ( !empty( $cats ) )
      $cats = ' <div class="cats">' . $cats . '</div>';
    $tags = ubik_terms_popular_list( get_the_ID(), 'post_tag', ' <div class="tags">', '</div> <div class="tags">', '</div>' );
    $author = ubik_meta_author();
    if ( !empty( $author ) )
      $author = ' <div class="author">' . sprintf( __( '<div class="by">by</div> %s', 'pendrell' ), $author ) . '</div>';
    $output = $date . $cats . $tags . $author;
  }

  return $contents . $output;
} endif;
add_filter( 'pendrell_entry_header_meta', 'pendrell_entry_header_metadata' );



// == FOOTER == //

// Entry footer meta
if ( !function_exists( 'pendrell_entry_footer_meta' ) ) : function pendrell_entry_footer_meta() {
  echo '<div class="entry-meta">' . ubik_meta_date_statement() . '</div>';
} endif;
add_action( 'pendrell_entry_footer', 'pendrell_entry_footer_meta', 10 );



// Entry footer buttons
// @filter: pendrell_entry_footer_buttons
if ( !function_exists( 'pendrell_entry_footer_buttons' ) ) : function pendrell_entry_footer_buttons() {
  $buttons = apply_filters( 'pendrell_entry_footer_buttons', '' );
  if ( !empty( $buttons ) )
    echo '<div class="buttons">' . $buttons . '</div>';
} endif;
add_action( 'pendrell_entry_footer', 'pendrell_entry_footer_buttons', 5 );



// Entry meta link button
if ( !function_exists( 'pendrell_entry_meta_link' ) ) : function pendrell_entry_meta_link( $buttons ) {
  $buttons .= '<a class="button" href="' . get_permalink() . '" rel="bookmark" role="button">' . pendrell_icon( 'anchor', __( 'Link', 'pendrell' ) ) . '</a>';
  return $buttons;
} endif;
add_filter( 'pendrell_entry_footer_buttons', 'pendrell_entry_meta_link', 3 );



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
