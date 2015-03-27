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

// Allow for functions to hook into the sidebar and hijack the contents; can also be set to false to not display any sidebar at all
// @filter: pendrell_sidebar (bool|string)
if ( !function_exists( 'pendrell_sidebar' ) ) : function pendrell_sidebar() {
  $sidebar = apply_filters( 'pendrell_sidebar', true );
  if ( $sidebar !== false ) {
    echo '<div id="wrap-sidebar" class="wrap-sidebar"><div id="secondary" class="site-sidebar" role="complementary">';
    if ( is_string( $sidebar ) ) {
      echo $sidebar; // Custom sidebar
    } else {
      echo '<aside id="social" class="social">' . pendrell_author_social() . '</aside>';
      dynamic_sidebar( 'sidebar-main' ); // Regular sidebar
    }
    echo '</div></div>';
  }
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
