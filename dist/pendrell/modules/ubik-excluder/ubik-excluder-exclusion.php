<?php // ==== EXCLUDER ==== //

// Control what sort of content appears on your homepage... set all applicable variables in `ubik-excluder-config.php` or define locally

// Exclude specific categories from the homepage
if ( !function_exists( 'ubik_exclude_cats' ) ) : function ubik_exclude_cats( $query ) {
  if ( !is_admin() && $query->is_home() && $query->is_main_query() && $query->get( 'ubik_include_all' ) !== true ) {

    global $ubik_exclude_cats;

    if ( empty( $ubik_exclude_cats ) )
      return;

    $terms = ubik_exclude_terms_transform( $ubik_exclude_cats, 'category' );

    // One final test and then we're good to go
    if ( !empty( $terms ) && $terms === array_filter( $terms, 'is_int' ) )
      $query->set( 'category__not_in', $terms );
  }
} endif;
if ( !empty( $ubik_exclude_cats ) )
  add_action( 'pre_get_posts', 'ubik_exclude_cats' );



// Exclude specific post formats from the homepage
if ( !function_exists( 'ubik_exclude_formats' ) ) : function ubik_exclude_formats( $query ) {
  if ( !is_admin() && $query->is_home() && $query->is_main_query() && $query->get( 'ubik_include_all' ) !== true ) {

    global $ubik_exclude_formats;

    if ( empty( $ubik_exclude_formats ) )
      return;

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
} endif;
if ( !empty( $ubik_exclude_formats ) )
  add_action( 'pre_get_posts', 'ubik_exclude_formats' );



// Exclude specific tags from the homepage
if ( !function_exists( 'ubik_exclude_tags' ) ) : function ubik_exclude_tags( $query ) {
  if ( !is_admin() && $query->is_home() && $query->is_main_query() && $query->get( 'ubik_include_all' ) !== true ) {

    global $ubik_exclude_tags;

    if ( empty( $ubik_exclude_tags ) )
      return;

    $terms = ubik_exclude_terms_transform( $ubik_exclude_tags, 'post_tag' );

    // One final test and then we're good to go
    if ( !empty( $terms ) && $terms === array_filter( $terms, 'is_int' ) )
      $query->set( 'tag__not_in', $terms );
  }
} endif;
if ( !empty( $ubik_exclude_tags ) )
  add_action( 'pre_get_posts', 'ubik_exclude_tags' );



// Convert a potentially messy array of terms into a clean array of IDs to throw back at the query
if ( !function_exists( 'ubik_exclude_terms_transform' ) ) : function ubik_exclude_terms_transform( $terms, $taxonomy = 'category' ) {

  // Exit early if this isn't an array
  if ( !is_array( $terms ) || empty( $terms ) )
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
} endif;
