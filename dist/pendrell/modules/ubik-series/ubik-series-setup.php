<?php // ==== SERIES ==== //

// Everything you need to implement a quick and dirty post series custom taxonomy
function ubik_series_init() {
  register_taxonomy( 'series', 'post', array(
    'hierarchical' => false,
    'labels' => array(
      'name'              => _x( 'Series', 'taxonomy general name' ),
      'singular_name'     => _x( 'Series', 'taxonomy singular name' ),
      'search_items'      =>  __( 'Search Series' ),
      'all_items'         => __( 'All Series' ),
      'parent_item'       => __( 'Parent Series' ),
      'parent_item_colon' => __( 'Parent Series:' ),
      'edit_item'         => __( 'Edit Series' ),
      'update_item'       => __( 'Update Series' ),
      'add_new_item'      => __( 'Add New Series' ),
      'new_item_name'     => __( 'New Series Name' ),
      'menu_name'         => __( 'Series' ),
    ),
    'rewrite'             => array(
      'slug'              => 'series',
      'with_front'        => false,
      'ep_mask'           => EP_TAGS
    ),
  ));
}
add_action( 'init', 'ubik_series_init' );



// Quick and dirty post series list; optionally set the class and introductory text
function ubik_series_list() {

  global $post;

  // Only display the post series list on the single post view
  if ( is_singular() ) {

    // Placeholder values; @TODO: make these variables user-configurable
    $class = 'entry-meta-series';
    $text = '';

    // Fetch a list of post series the current post is a part of
    $series_terms = wp_get_post_terms( $post->ID, 'series', array(
      'orderby' => 'name', // Defaults to alphabetical order; also: count, slug, or term_id
      'order' => 'ASC'
    ) );

    if ( !empty( $series_terms ) ) {
      foreach ( $series_terms as $series_term ) {

        // Fetch a list of posts in a given series in chronological order
        $series_query = new WP_Query( array(
          'order' => 'ASC',
          'nopaging' => true,
          'tax_query' => array(
            array(
              'taxonomy' => 'series',
              'field' => 'slug',
              'terms' => $series_term->slug
            )
          )
        ) );

        // Display the list of posts in the series only if there is more than one post in that series
        if ( $series_query->have_posts() && ( $series_query->found_posts > 1 ) ): ?>
        <div class="<?php echo $class; ?>">
          <p><?php if ( !empty( $text ) ) {
            echo $text;
          } else {
            printf( __( 'This is part of the <a href="%1$s">%2$s</a> series:', 'ubik' ),
              get_term_link( $series_term->slug, 'series' ),
              $series_term->name );
          } ?></p>
          <ol>
          <?php while ( $series_query->have_posts() ) : $series_query->the_post(); ?>
            <li><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></li>
          <?php endwhile; wp_reset_postdata(); ?>
          </ol>
        </div>
        <?php endif;
      }
    }
  }
}



// Display post series in order; caution: may require changing "newer" and "older" navigation cues to "next" and "previous"
function ubik_series_get_posts( $query ) {
  if (
    is_tax('series')
    && $query->is_main_query()
    && UBIK_SERIES_ORDER
  ) {
    $query->set( 'order', UBIK_SERIES_ORDER );
  }
  return $query;
}
add_filter( 'pre_get_posts', 'ubik_series_get_posts' );
