<?php // === CONTENT === //

// Content title
function pendrell_title() {
  ?><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute( array( 'before' => __( 'Permalink to ', 'pendrell' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a><?php
}

// Entry metadata
function pendrell_entry_meta() {
  global $post;

  do_action( 'pendrell_entry_meta_before' );

  ?><div class="entry-meta-buttons">
    <?php edit_post_link( __( 'Edit', 'pendrell' ), ' <span class="edit-link button">', '</span>' );
    if ( comments_open() && !is_singular() ) {
      ?> <span class="leave-reply button"><?php comments_popup_link( __( 'Respond', 'pendrell' ), __( '1 Response', 'pendrell' ), __( '% Responses', 'pendrell' ) ); ?></span><?php
    } ?>
  </div>
  <div class="entry-meta-main">
    <?php pendrell_entry_meta_generator(); ?>
  </div><?php

  do_action( 'pendrell_entry_meta_after' );
}

function pendrell_entry_meta_generator() {

  // @TODO: fill in some minimal meta stuff here; we're taking the rest of this to Ubik
  ubik_content_meta();

  // action hook

}