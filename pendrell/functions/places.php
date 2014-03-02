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
    'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'custom-fields', 'comments', 'revisions', 'page-attributes', 'wpcom-markdown' ),
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

  register_taxonomy( 'place_tag', 'place', array(
    'hierarchical' => false,
    'labels' => array(
      'name' => _x( 'Place Tags', 'taxonomy general name' ),
      'singular_name' => _x( 'Place Tag', 'taxonomy singular name' ),
      'search_items' =>  __( 'Search Place Tags' ),
      'all_items' => __( 'All Place Tags' ),
      'parent_item' => __( 'Parent Place Tags' ),
      'parent_item_colon' => __( 'Parent Place Tags:' ),
      'edit_item' => __( 'Edit Place Tag' ),
      'update_item' => __( 'Update Place Tag' ),
      'add_new_item' => __( 'Add New Place Tag' ),
      'new_item_name' => __( 'New Place Tag Name' ),
      'menu_name' => __( 'Place Tags' ),
    ),
    'rewrite' => array(
      'slug' => 'place-tag',
      'with_front' => false
    ),
  ));
}
add_action( 'init', 'pendrell_places_init' );



// Places widget
function pendrell_places_widgets_init() {
  register_sidebar( array(
    'name' => __( 'Places Sidebar', 'pendrell' ),
    'id' => 'sidebar-places',
    'description' => __( 'Appears on places', 'pendrell' ),
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget' => '</aside>',
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>',
  ) );
}
add_action( 'widgets_init', 'pendrell_places_widgets_init' );



// List places in the entry meta area
function pendrell_places_list() {
  global $post;

  if ( pendrell_is_place() ) {
    $children = get_pages('post_type=place&child_of=' . $post->ID);
    $siblings = get_pages('post_type=place&child_of=' . $post->post_parent);
    if ( $children ) {
      ?><div class="entry-meta-places-list">
        <h2><?php printf( 'Places in %s:', $post->post_title ); ?></h2>
        <ul class="place-list"><?php wp_list_pages(
        array(
          'child_of'      => $post->ID,
          'depth'         => 2,
          'post_type'     => 'place',
          'title_li'      => '',
          )
        ); ?></ul>
      </div><?php
    // If there aren't any children perhaps siblings will be useful
    } elseif ( count( $siblings ) >= 3 ) {
      ?><div class="entry-meta-places-list">
        <h2><?php printf( 'Places near %s:', $post->post_title ); ?></h2>
        <ul class="place-list"><?php wp_list_pages(
        array(
          'child_of'      => $post->post_parent,
          'depth'         => 1,
          'post_type'     => 'place',
          'title_li'      => '',
          'exclude'       => $post->ID,
          )
        ); ?></ul>
      </div><?php
    } else {
      // Show parent?
    }
  }
}
add_action( 'pre_entry_meta', 'pendrell_places_list', 7 );



// List posts tagged with the current place
function pendrell_places_posts() {
  global $post;
  $place_name = $post->post_name;
  $place_title = $post->post_title;
  $place_tag = term_exists( $place_name, 'post_tag' );

  // Only do the extra work if there is a matching post tag
  if ($place_tag !== 0 && $place_tag !== null) {
    $place_tag_link = get_tag_link( $place_tag['term_id'] );

    // Fetch posts tagged with the current place; only the slugs need to match
    $the_query = new WP_Query( 'tag=' . $place_name );

    if ( $the_query->have_posts() ) {
      ?>
        <div class="entry-meta-places-posts">
          <h2>Posts tagged <a href="<?php echo $place_tag_link; ?>"><?php echo $place_title; ?></a>:</h2>
          <ul><?php while ( $the_query->have_posts() ) {
            $the_query->the_post();
            ?><li><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'pendrell' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a></li><?php
          } ?></ul>
        </div>
    <?php } else {
      // no posts found
    }
    // Restore original post data
    wp_reset_postdata();
  }
}
add_action( 'pre_entry_meta', 'pendrell_places_posts', 5 );



// Places sidebar
function pendrell_places_sidebar() {
?><div id="secondary" class="widget-area" role="complementary">
    <aside id="recent-posts-4" class="widget widget_recent_entries">
      <h3 class="widget-title">Places</h3>
      <ul class="place-list"><?php wp_list_pages(
      array(
        'depth'         => 3,
        'post_type'     => 'place',
        'title_li'      => '',
        )
      ); ?></ul>
    </aside>
  </div><!-- #secondary --><?php
}

// Display post series in forward chronological order
function pendrell_place_tags_get_posts( $query ) {
  if ( is_tax ( 'place_tag' ) && $query->is_main_query() ) {
    $query->set( 'posts_per_page', 10 );
    $query->set( 'order', 'ASC' );
    $query->set( 'orderby', 'title' );
  }
  return $query;
}
add_filter( 'pre_get_posts', 'pendrell_place_tags_get_posts' );
