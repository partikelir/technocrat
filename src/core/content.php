<?php // ==== CONTENT ==== //

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

  pendrell_entry_meta_buttons();

  ?><div class="entry-meta-main">
    <?php echo ubik_meta(); ?>
  </div><?php

  do_action( 'pendrell_entry_meta_after' );

} endif;



// Entry meta buttons: edit post link (for users with the appropriate capabilities) and the response count (where available)
if ( !function_exists( 'pendrell_entry_meta_buttons' ) ) : function pendrell_entry_meta_buttons() {
  ?><div class="entry-meta-buttons">
    <?php
      echo pendrell_content_edit_link();
      pendrell_comments_link();
    ?>
  </div><?php
} endif;



// Return the edit post link; adapted from WordPress core
if ( !function_exists( 'pendrell_content_edit_link' ) ) : function pendrell_content_edit_link() {
  if ( ! $post = get_post() )
    return;
  if ( ! $url = get_edit_post_link( $post->ID ) )
    return;
  return '<a class="button post-edit-link" href="' . $url . '" rel="nofollow">' . pendrell_icon( 'typ-edit', __( 'Edit', 'pendrell' ) ) . '</a>';
} endif;



// Icon shortcut
if ( !function_exists( 'pendrell_icon' ) ) : function pendrell_icon( $icon, $text = '', $description = '' ) {
  return ubik_svg_icon( $icon, $text, $description ) . $text;
} endif;



// Body class filter
if ( !function_exists( 'pendrell_body_class' ) ) : function pendrell_body_class( $classes ) {

  // Remove duplicate 'page-templates' (note the 's'); for example 'page-template-page-templates' results from the directory structure
  foreach ( $classes as $id => $class ) {
    if ( strpos( $class, 'page-templates' ) )
      unset( $classes[$id] );
  }

  return $classes;
} endif;
add_filter( 'body_class', 'pendrell_body_class' );



// Content class; applies a filter to the content wrapper to allow other functions to alter the look and feel of posts, pages, etc.
// @filter: pendrell_content_class
if ( !function_exists( 'pendrell_content_class' ) ) : function pendrell_content_class() {
  $classes = apply_filters( 'pendrell_content_class', array() );
  if ( !empty( $classes ) )
    echo ' ' . join( ' ', $classes );
} endif;



// Better password form based on WordPress core function; @TODO: spin this off into Ubik
if ( !function_exists( 'pendrell_password_form' ) ) : function pendrell_password_form( $output = '' ) {

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
  $submit = '<button type="submit" name="Submit">' . pendrell_icon( 'typ-key', __( 'Access', 'pendrell' ) ) . '</button>';

  // Form wrapper
  $output = $prompt . '<form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" class="post-password-form" method="post">' . $label . $input . $submit . '</form>';

  return $output;
} endif;
add_filter( 'the_password_form', 'pendrell_password_form' );
