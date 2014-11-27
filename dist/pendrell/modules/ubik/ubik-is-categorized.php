<?php // ==== IS CATEGORIZED ==== //

// Alias for function below
if ( !function_exists( 'is_categorized' ) ) : function is_categorized() {
  return ubik_is_categorized();
} endif;

// Check whether a blog has more than one category; via _s: https://github.com/Automattic/_s/blob/master/inc/template-tags.php
// @constant: UBIK_NO_CATEGORIES
if ( !function_exists( 'ubik_is_categorized' ) ) : function ubik_is_categorized() {

  // Hard switch for the category test; only acts when false
  if ( UBIK_NO_CATEGORIES )
    return false;

  if ( ( $category_transient = get_transient( '_ubik_categorized' ) ) === false ) {

    // Create an array of all the categories that are attached to posts.
    $category_transient = get_categories( array(
      'fields'     => 'ids',
      'hide_empty' => 1,
      'number'     => 2, // We only need to know if there is more than one category.
    ) );

    // Count the number of categories that are attached to the posts.
    $category_transient = count( $category_transient );

    set_transient( '_ubik_categorized', $category_transient );
  }

  if ( $category_transient > 1 ) {
    return true;
  } else {
    return false;
  }
} endif;

// Flush out the transients used in ubik_is_categorized
if ( !function_exists( 'ubik_is_categorized_transient_flusher' ) ) : function ubik_is_categorized_transient_flusher() {
  delete_transient( '_ubik_categorized' );
} endif;
add_action( 'edit_category', 'ubik_is_categorized_transient_flusher' );
add_action( 'save_post', 'ubik_is_categorized_transient_flusher' );
