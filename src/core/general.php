<?php // ==== GENERAL ==== //

// == CONTENT == //

// Content class; applies a filter to the content wrapper to allow other functions to alter the look and feel of posts, pages, etc.
// @filter: pendrell_content_class
function pendrell_content_class() {
  $classes = apply_filters( 'pendrell_content_class', array() );
  if ( !empty( $classes ) )
    echo ' ' . join( ' ', $classes );
}



// == SIDEBAR == //

// Allow for functions to hook into the sidebar and hijack the contents; can also be set to false to not display any sidebar at all
// @filter: pendrell_sidebar (bool|string)
function pendrell_sidebar() {
  $sidebar = apply_filters( 'pendrell_sidebar', true );
  if ( $sidebar !== false ) {
    echo '<div id="wrap-sidebar" class="wrap-sidebar"><div id="secondary" class="site-sidebar" role="complementary">';
    if ( is_string( $sidebar ) ) {
      echo $sidebar; // Custom sidebar
    } else {
      dynamic_sidebar( 'sidebar-main' ); // Regular sidebar
    }
    echo '</div></div>';
  }
}



// == TEMPLATE LOADING == //

// A wrapper for `get_template_part`, a core WordPress function that ships without a filter
// @filter: pendrell_template_part
function pendrell_template_part( $slug = 'content', $name = null ) {
  return get_template_part( $slug, apply_filters( 'pendrell_template_part', $name ) );
}

// Force search results to display excerpts
function pendrell_template_part_search( $name ) {
  if ( is_search() )
    $name = 'excerpt';
  return $name;
}
add_filter( 'pendrell_template_part', 'pendrell_template_part_search' );
