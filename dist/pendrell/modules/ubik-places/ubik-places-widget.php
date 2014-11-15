<?php // ==== WIDGET ==== //

// Places widget; this isn't a true widget... but it's also not 200+ lines of code I don't need
function ubik_places_widget( $term = null ) {

  $tax = 'places';

  // Allows us to pass an explicit term and achieve the same functionality
  if ( empty( $term ) || $term == '' )
    $term = get_term_by( 'slug', get_query_var( 'term' ), $tax );

  // Check again
  if ( !empty( $term ) ) {

    $places = array();

    // Get direct descendents of the current term
    $children = get_term_children( $term->term_id, $tax );

    // Get direct descendents of the parent of the current term; get_term_children is not appropriate here
    $siblings = get_terms( $tax, array( 'parent' => $term->parent ) );

    // Get ancestors of the current term
    $ancestors = get_ancestors( $term->term_id, $tax );

    // Get the highest ancestor of the current term
    if ( !empty( $ancestors ) ) {
      $patriarch = get_term( end( $ancestors ), $tax );
    } else {
      $patriarch = $term;
    }

    // Unite the whole family (the current term plus all ancestors)
    $family = $ancestors;
    $family[] = $term->term_id;

    // Setup children query
    if ( !empty( $children ) ) {

      // Attempt to limit terms with an abundance of children; this is pure guess work
      if ( count( $children ) >= 25 && !empty( $ancestors) ) {
        $depth = 1;
      } else {
        $depth = 2;
      }

      $places[] = array(
        'title' => sprintf( __( 'Places in %s', 'ubik' ), apply_filters( 'ubik_places_title', $term->name ) ),
        'args' => array(
          'child_of'            => $term->term_id,
          'depth'               => $depth,
          'show_count'          => 1,
          'hide_empty'          => 0,
          'taxonomy'            => $tax,
          'title_li'            => '',
          'show_option_none'    => __( 'No places found', 'ubik' ),
          'echo'                => 0
        )
      );

    // If there are no childen at least show the parent tree; no different than breadcrumbs, really
    } elseif ( !empty( $ancestors ) ) {

      $places[] = array(
        'title' => sprintf( __( '%s in context', 'ubik' ), apply_filters( 'ubik_places_title', $term->name ) ),
        'args' => array(
          'depth'               => 0,
          'taxonomy'            => $tax,
          'include'             => $family,
          'title_li'            => '',
          'show_option_none'    => __( 'No places found', 'ubik' ),
          'echo'                => 0
        )
      );

    }

    // Setup sibling query; conditions: more than 2 siblings and not a top-level place
    if ( !empty( $siblings ) && count( $siblings ) >= 2 && !empty( $ancestors ) ) {

      $places[] = array(
        'title' => __( 'Related places', 'ubik' ),
        'args' => array(
          'child_of'            => $term->parent,
          'depth'               => 1,
          'taxonomy'            => $tax,
          'exclude'             => $term->term_id,
          'title_li'            => '',
          'show_option_none'    => __( 'No places found', 'ubik' ),
          'echo'                => 0
        )
      );

    }

    // Places index
    $places[] = array(
      'title' => __( 'Places index', 'ubik' ),
      'args' => array(
        'child_of'            => 0,
        'depth'               => 1,
        'show_count'          => 1,
        'taxonomy'            => $tax,
        'title_li'            => '',
        'show_option_none'    => __( 'No places found', 'ubik' ),
        'echo'                => 0
      )
    );

  }

  // Only output places widget markup if we have results; @TODO: turn this into a real widget
  if ( !empty( $places ) ) {

    ?><div id="wrap-sidebar">
      <div id="secondary" class="widget-area" role="complementary">
        <aside id="places" class="widget widget_places">
          <?php if ( !empty( $places ) ) {
            foreach ( $places as $place ) {
              ?><h3 class="widget-title"><?php echo $place['title']; ?></a></h3>
              <ul class="place-list"><?php echo wp_list_categories( $place['args'] ); ?></ul><?php
            }
          } ?>
        </aside>
      </div>
    </div><?php
  }
}
