<?php // ==== TEMPLATES ==== //

// A wrapper for `get_template_part`, a core WordPress function that ships without a filter
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
