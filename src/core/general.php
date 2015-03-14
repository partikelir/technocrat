<?php // ==== GENERAL ==== //

// == CONTENT == //

// Content class; applies a filter to the content wrapper to allow other functions to alter the look and feel of posts, pages, etc.
// @filter: pendrell_content_class
if ( !function_exists( 'pendrell_content_class' ) ) : function pendrell_content_class() {
  $classes = apply_filters( 'pendrell_content_class', array() );
  if ( !empty( $classes ) )
    echo ' ' . join( ' ', $classes );
} endif;



// == SIDEBAR == //

// Sidebar handler; a filter hook to allow for other functions to disable the sidebar (since WordPress core ships with no such thing)
// @filter: pendrell_sidebar
if ( !function_exists( 'pendrell_sidebar' ) ) : function pendrell_sidebar( $sidebar = true ) {
  $sidebar = (bool) apply_filters( 'pendrell_sidebar', $sidebar );
  if ( $sidebar !== false )
    get_sidebar();
} endif;



// == TEMPLATE LOADING == //

// A wrapper for `get_template_part`, a core WordPress function that ships without a filter
// @filter: pendrell_template_part
if ( !function_exists( 'pendrell_template_part' ) ) : function pendrell_template_part( $slug = 'content', $name = null ) {
  return get_template_part( $slug, apply_filters( 'pendrell_template_part', $name ) );
} endif;

// Force search results to display excerpts
if ( !function_exists( 'pendrell_template_part_search' ) ) : function pendrell_template_part_search( $name ) {
  if ( is_search() )
    $name = 'excerpt';
  return $name;
} endif;
add_filter( 'pendrell_template_part', 'pendrell_template_part_search' );
