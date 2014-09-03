<?php // ==== TERMS ==== //

// == CATEGORIES == //

// Check whether a blog has more than one category; via _s: https://github.com/Automattic/_s/blob/master/inc/template-tags.php
function pendrell_categorized_blog() {
  if ( false === ( $all_the_cool_cats = get_transient( '_pendrell_categories' ) ) ) {
    // Create an array of all the categories that are attached to posts.
    $all_the_cool_cats = get_categories( array(
      'fields'     => 'ids',
      'hide_empty' => 1,
      'number'     => 2, // We only need to know if there is more than one category.
    ) );

    // Count the number of categories that are attached to the posts.
    $all_the_cool_cats = count( $all_the_cool_cats );

    set_transient( '_pendrell_categories', $all_the_cool_cats );
  }

  if ( $all_the_cool_cats > 1 ) {
    return true;
  } else {
    return false;
  }
}

// Flush out the transients used in pendrell_categorized_blog
function pendrell_category_transient_flusher() {
  delete_transient( 'all_the_cool_cats' );
}
add_action( 'edit_category', 'pendrell_category_transient_flusher' );
add_action( 'save_post',     'pendrell_category_transient_flusher' );
