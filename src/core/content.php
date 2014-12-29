<?php // ==== CONTENT ==== //

// Content title; displayed at the top of posts, pages, etc.
// @filter: pendrell_entry_title
// @action: pendrell_entry_title_after
if ( !function_exists( 'pendrell_entry_title' ) ) : function pendrell_entry_title( $content = '' ) {
  if ( empty( $content ) )
    $content = get_the_title();
  $title = '<h1 class="entry-title"><a href="' . get_permalink() . '" rel="bookmark">' . $content . '</a></h1>';
  echo apply_filters( 'pendrell_entry_title', $title );
  do_action( 'pendrell_entry_title_after' );
} endif;



// Content class; applies a filter to the content wrapper to allow other functions to alter the look and feel of posts, pages, etc.
// @filter: pendrell_content_class
if ( !function_exists( 'pendrell_content_class' ) ) : function pendrell_content_class() {
  $classes = apply_filters( 'pendrell_content_class', array() );
  if ( !empty( $classes ) )
    echo ' ' . join( ' ', $classes );
} endif;



// Entry meta buttons: edit post link (for users with the appropriate capabilities) and the response count (where available)
if ( !function_exists( 'pendrell_entry_meta_buttons' ) ) : function pendrell_entry_meta_buttons() {
  ?><div class="entry-meta-buttons">
    <?php
      edit_post_link( pendrell_icon( 'typ-pencil', __( 'Edit', 'pendrell' ) ) . __( 'Edit', 'pendrell' ), ' <span class="edit-link button">', '</span>' );
      if ( !is_singular() && !post_password_required() && ( comments_open() || get_comments_number() != '0' ) ) {
        ?>&nbsp;<span class="leave-reply button"><?php comments_popup_link( __( 'Respond', 'pendrell' ), __( '1 Response', 'pendrell' ), __( '% Responses', 'pendrell' ) ); ?></span><?php
      }
    ?>
  </div><?php
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
