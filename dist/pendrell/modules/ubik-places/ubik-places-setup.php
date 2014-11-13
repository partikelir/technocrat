<?php // ==== SETUP ==== //

// == TAXONOMY == //

// Quick and dirty places taxonomy
function ubik_places_init() {

  // Places taxonomy
  register_taxonomy( 'places', 'post', array(
    'labels' => array(
      'name' => _x( 'Places', 'taxonomy general name' ),
      'singular_name'     => _x( 'Places', 'taxonomy singular name' ),
      'menu_name'         => __( 'Places' ),
      'all_items'         => __( 'All places' ),
      'edit_item'         => __( 'Edit place' ),
      'view_item'         => __( 'View place' ),
      'update_item'       => __( 'Update places' ),
      'add_new_item'      => __( 'Add new place' ),
      'new_item_name'     => __( 'New place name' ),
      'parent_item'       => __( 'Parent place' ),
      'parent_item_colon' => __( 'Parent place:' ),
      'search_items'      => __( 'Search places' ),
    ),
    'show_admin_column'   => true,
    'hierarchical'        => true,
    'rewrite'             => array(
      'slug'              => 'places',
      'with_front'        => true,
      'hierarchical'      => true,
      'ep_mask'           => EP_TAGS
    )
  ));

  // Places shortcode
  add_shortcode( 'place', 'ubik_places_shortcode' );
}
add_action( 'init', 'ubik_places_init' );



// == PLACES QUICK EDIT == //

// Adds place descriptions to the quick edit box
function ubik_places_quick_edit( $taxonomies ) {
  $taxonomies[] = 'places';
  return $taxonomies;
}
add_filter( 'ubik_term_description_taxonomies', 'ubik_places_quick_edit' );



// == PLACES ENTRY META == //

// Adds places to entry metadata right after other taxonomies
function ubik_places_entry_meta( $meta ) {
  global $post;
  if ( has_term( '', 'places' ) && function_exists( 'ubik_get_the_popular_term_list' ) )
    $meta .= ubik_get_the_popular_term_list( $post->ID, 'places', 'Places: ', ', ', '. ' );
  return $meta;
}
add_filter( 'ubik_entry_meta_taxonomies', 'ubik_places_entry_meta' );
