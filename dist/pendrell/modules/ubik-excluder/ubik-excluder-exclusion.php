<?php // ==== EXCLUDER ==== //

// Control what sort of content appears on your homepage... set all applicable variables in `ubik-excluder-config.php`

// Exclude specific categories from the homepage
function ubik_exclude_cats( $query ) {
  if ( !is_admin() && $query->is_home() && $query->is_main_query() && $query->get( 'ubik_include_all' ) !== true ) {
    global $ubik_exclude_cats;

    $terms = ubik_exclude_terms( $ubik_exclude_cats, 'category' );

    // One final test and then we're good to go
    if ( !empty( $terms ) && $terms === array_filter( $terms, 'is_int' ) )
      $query->set( 'category__not_in', $terms );
  }
}
if ( !empty( $ubik_exclude_cats ) )
  add_action( 'pre_get_posts', 'ubik_exclude_cats' );



// Exclude specific post formats from the homepage
function ubik_exclude_formats( $query ) {
  if ( !is_admin() && $query->is_home() && $query->is_main_query() && $query->get( 'ubik_include_all' ) !== true ) {
    global $ubik_exclude_formats;
    $args = array(
      array(
        'taxonomy' => 'post_format',
        'field'    => 'slug',
        'terms'    => $ubik_exclude_formats,
        'operator' => 'NOT IN'
      ),
    );
    $query->set( 'tax_query', $args );
  }
}
if ( !empty( $ubik_exclude_formats ) )
  add_action( 'pre_get_posts', 'ubik_exclude_formats' );



// Exclude specific tags from the homepage
function ubik_exclude_tags( $query ) {
  if ( !is_admin() && $query->is_home() && $query->is_main_query() && $query->get( 'ubik_include_all' ) !== true ) {
    global $ubik_exclude_tags;

    $terms = ubik_exclude_terms( $ubik_exclude_tags, 'post_tag' );

    // One final test and then we're good to go
    if ( !empty( $terms ) && $terms === array_filter( $terms, 'is_int' ) )
      $query->set( 'tag__not_in', $terms );
  }
}
if ( !empty( $ubik_exclude_tags ) )
  add_action( 'pre_get_posts', 'ubik_exclude_tags' );



// Convert a potentially messy array of terms into a clean array of IDs to throw back at the query
function ubik_exclude_terms( $terms, $taxonomy = 'category' ) {

  // Exit early if this isn't an array
  if ( !is_array( $terms ) )
    return;

  // Return the terms if the array already contains nothing but integers; presumably these are IDs
  if ( $terms === array_filter( $terms, 'is_int' ) )
    return $terms;

  // Cycle through each item in the array and attempt to retrieve a term ID
  foreach ( $terms as $term ) {
    $new_term = get_term_by( 'slug', $term, $taxonomy );
    if ( !empty( $new_term ) )
      $new_terms[] = (int) $new_term->term_id;
    unset( $new_term );
  }

  // Reset the array if we have something, return if not
  if ( !empty( $new_terms ) ) {
    return $new_terms;
  } else {
    return;
  }
}
