<?php // ==== NAVIGATION ==== //

// == BREADCRUMBS == //

// Breadcrumb navigation for places based on http://www.billerickson.net/wordpress-taxonomy-breadcrumbs/
if ( !function_exists( 'ubik_places_breadcrumb' ) ) : function ubik_places_breadcrumb( $term = '' ) {

  $content = '';
  $tax = get_query_var( 'taxonomy' );

  if ( $tax !== 'places' )
    return;

  // Allows us to pass an explicit term and achieve the same functionality
  if ( empty( $term ) || $term == '' )
    $term = get_term_by( 'slug', get_query_var( 'term' ), $tax );

  // Create a list of all the term's parents
  $parent = $term->parent;

  if ( $parent ) {

    // Back things up a bit so that the current term is included
    $parent = $term->term_id;

    while ( $parent ) {
      $parents[] = $parent;
      $parent_parent = get_term_by( 'id', $parent, $tax );
      $parent = $parent_parent->parent; // Heh
    }

    if( !empty( $parents ) ) {
      $parents = array_reverse( $parents );

      // Wrap it up
      $content .= "\n" . '<nav class="breadcrumbs"><ul>';

      // For each parent, create a breadcrumb item
      foreach ( $parents as $parent ) {
        $item = get_term_by( 'id', $parent, $tax );
        $link = get_term_link( $parent, $tax );
        $content .= '<li><a href="' . $link . '">' . $item->name . '</a></li>';
      }

      // Wrap it up
      $content .= '</ul></nav>' . "\n";
    }
  }
  echo $content;
} endif; // ubik_places_breadcrumb()
