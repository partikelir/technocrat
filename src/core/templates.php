<?php // ==== TEMPLATES ==== //

// == SIDEBAR == //

// Sidebar handler; since WordPress hasn't really made things easy in this department
// @filter: pendrell_sidebar
if ( !function_exists( 'pendrell_sidebar' ) ) : function pendrell_sidebar( $sidebar = true ) {

  // Filter the $sidebar variable; this way we can set it to "false" by hooking into this function elsewhere
  // This way the regular sidebar can be disabled and you can output whatever you want
  $sidebar = apply_filters( 'pendrell_sidebar', $sidebar );

  // Include the regular sidebar template if $sidebar has not been set to "false"
  if ( $sidebar )
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
