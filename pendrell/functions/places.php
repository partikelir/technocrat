<?php // Quick and dirty places type
function pendrell_places_init() {
  $labels = array(
    'name' => _x( 'Places', 'place' ),
    'singular_name' => _x( 'Place', 'place' ),
    'add_new' => _x( 'Add New', 'place' ),
    'add_new_item' => _x( 'Add New Place', 'place' ),
    'edit_item' => _x( 'Edit Place', 'place' ),
    'new_item' => _x( 'New Place', 'place' ),
    'view_item' => _x( 'View Place', 'place' ),
    'search_items' => _x( 'Search Places', 'place' ),
    'not_found' => _x( 'No places found', 'place' ),
    'not_found_in_trash' => _x( 'No places found in Trash', 'place' ),
    'parent_item_colon' => _x( 'Parent Place:', 'place' ),
    'menu_name' => _x( 'Places', 'place' ),
  );
  $args = array(
    'labels' => $labels,
    'hierarchical' => true,
    'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'custom-fields', 'comments', 'revisions', 'page-attributes' ),
    'taxonomies' => array( 'place_type' ),
    'public' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'menu_position' => 20,
    'show_in_nav_menus' => true,
    'publicly_queryable' => true,
    'exclude_from_search' => false,
    'has_archive' => true,
    'query_var' => true,
    'can_export' => true,
    'rewrite' => true,
    'capability_type' => 'page'
  );
  register_post_type( 'place', $args );

  register_taxonomy( 'place_type', 'place', array(
    'hierarchical' => false,
    'labels' => array(
      'name' => _x( 'Place Types', 'taxonomy general name' ),
      'singular_name' => _x( 'Place Type', 'taxonomy singular name' ),
      'search_items' =>  __( 'Search Place Types' ),
      'all_items' => __( 'All Place Types' ),
      'parent_item' => __( 'Parent Place Types' ),
      'parent_item_colon' => __( 'Parent Place Types:' ),
      'edit_item' => __( 'Edit Place Type' ),
      'update_item' => __( 'Update Place Type' ),
      'add_new_item' => __( 'Add New Place Type' ),
      'new_item_name' => __( 'New Place Type Name' ),
      'menu_name' => __( 'Place Types' ),
    ),
    'rewrite' => array(
      'slug' => 'place-type',
      'with_front' => false
    ),
  ));
}
add_action( 'init', 'pendrell_places_init' );



// List places in the entry meta area
function pendrell_list_places() {
  global $post;
      ?>
      <div class="entry-meta-place-list">
        <ul class="place-list"><?php wp_list_pages(
        array(
          'child_of'      => $post->ID,
          'depth'         => 2,
          'post_type'     => 'place',
          'title_li'      => sprintf( 'More places under &lsquo;<mark>%s</mark>&rsquo;:', $post->post_title ),
        )
      ); ?></ul>
      </div>
<?php
}
add_action( 'pre_entry_meta', 'pendrell_list_places' );