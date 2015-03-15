<?php // ==== ENTRY CONTENT ==== //

// Content title; displayed at the top of posts, pages, etc.
// @filter: pendrell_entry_title
// @action: pendrell_entry_title_after
if ( !function_exists( 'pendrell_entry_title' ) ) : function pendrell_entry_title( $title = '' ) {
  if ( empty( $title ) )
    $title = get_the_title();
  if ( !is_singular() )
    $title = '<a href="' . get_permalink() . '" rel="bookmark">' . $title . '</a>';
  echo apply_filters( 'pendrell_entry_title', '<h1 class="entry-title">' . $title . '</h1>' );
  do_action( 'pendrell_entry_title_after' );
} endif;



// Entry meta wrapper
// @action: pendrell_entry_meta_before
// @action: pendrell_entry_meta_after
if ( !function_exists( 'pendrell_entry_meta' ) ) : function pendrell_entry_meta() {
  do_action( 'pendrell_entry_meta_before' );

  ?><div class="entry-meta-main">
    <?php echo ubik_meta(); ?>
  </div><?php

  do_action( 'pendrell_entry_meta_after' );
} endif;



// Entry buttons
// @filter: pendrell_entry_buttons
if ( !function_exists( 'pendrell_entry_buttons' ) ) : function pendrell_entry_buttons() {
  $buttons = apply_filters( 'pendrell_entry_buttons', '' );
  if ( !empty( $buttons ) )
    echo '<div class="buttons buttons-merge">' . $buttons . '</div>';
} endif;
add_action( 'pendrell_entry_meta_before', 'pendrell_entry_buttons' );



// Return the edit post link; adapted from WordPress core
if ( !function_exists( 'pendrell_entry_edit_link' ) ) : function pendrell_entry_edit_link( $buttons ) {
  if ( ! $post = get_post() )
    return $buttons;
  if ( ! $url = get_edit_post_link( $post->ID ) )
    return $buttons;
  $buttons .= '<a class="button post-edit-link" href="' . $url . '" rel="nofollow" role="button">' . pendrell_icon( 'content-edit', __( 'Edit', 'pendrell' ) ) . '</a>';
  return $buttons;
} endif;
add_filter( 'pendrell_entry_buttons', 'pendrell_entry_edit_link', 9 );



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
  $input = '<input name="post_password" id="' . $id . '" type="password" size="20" /> ';

  // Submit button
  $submit = '<button type="submit" name="Submit">' . pendrell_icon( 'content-protected', __( 'Access', 'pendrell' ) ) . '</button>';

  // Form wrapper
  $output = $prompt . '<form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" class="post-password-form" method="post">' . $label . $input . $submit . '</form>';

  return $output;
} endif;
add_filter( 'the_password_form', 'pendrell_entry_password_form' );
