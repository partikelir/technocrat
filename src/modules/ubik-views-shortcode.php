<?php // ==== VIEWS SHORTCODE ==== //

// Views shortcode; outputs content from within posts and pages by using the same templates used by Ubik Views (to be clear this component is not required for this kludge to work)
// Use cases: list children of current page; show artist discographies; etc.
// Example: [view-posts view="gallery" mode="children" title="Profiles"]Here are some profiles related to this page...[/view-posts]
// Example: [view-posts mode="tagged" title="Releases"]The full discography of artist X.[/view-posts]
// @TODO: this function needs a lot of work; this prototype is sufficient for my needs as of now but it should be expanded...
// For some ideas, consider: http://plugins.svn.wordpress.org/display-posts-shortcode/tags/2.4/display-posts-shortcode.php
function pendrell_views_shortcode( $atts, $content = null ) {
  $args = shortcode_atts( array(
    'id'        => ''
  , 'mode'      => ''
  , 'taxonomy'  => ''
  , 'title'     => ''
  , 'view'      => 'list'
  ), $atts );

  $id       = (int) $args['id'];
  $mode     = (string) $args['mode'];
  $taxonomy = (string) $args['taxonomy'];
  $title    = (string) $args['title'];
  $view     = (string) $args['view'];

  if ( empty( $mode ) && is_page() )
    $mode = 'children';

  if ( !in_array( $view, array( 'excerpt', 'gallery', 'list' ) ) ) // No standard!
    $view = 'list';

  $posts = pendrell_views_shortcode_query( $id, $view, $mode, $taxonomy, $title, $content );

  return $posts;
}
add_shortcode( 'view-posts', 'pendrell_views_shortcode' );



// Generate HTML for the views shortcode; @TODO: make this more customizable; currently it's very limited to just a few modes
function pendrell_views_shortcode_query( $id = '', $view = 'list', $mode = '', $taxonomy = '', $title = '', $content ) {

  global $post, $wp_query;

  if ( empty( $id ) )
    $id = $post->ID;

  $html = $html_before = $html_after = '';

  $defaults = array(
    'has_password'        => false
  , 'ignore_sticky_posts' => 1
  );

  // Children of the current page
  if ( $mode == 'children' && is_page() ) {
    $args = array(
      'order'             => 'asc'
    , 'orderby'           => 'menu_order'
    , 'post_parent'       => $id
    , 'post_type'         => 'page'
    , 'posts_per_page'    => -1
    );
  }

  if ( $mode == 'tagged' ) {
    if ( empty( $taxonomy ) )
      $taxonomy = 'post_tag';

    $tax_query = array(
      array(
        'taxonomy'          => $taxonomy
      , 'field'             => 'slug'
      , 'terms'             => $post->post_name
      )
    );
    $args = array(
      'order'             => 'desc'
    , 'orderby'           => 'date'
    , 'tax_query'         => $tax_query
    );
  }

  if ( empty( $args ) )
    return;

  // Instatiate a new query; this should return a taxonomy archive of posts
  $query = new WP_Query( wp_parse_args( $args, $defaults ) );

  // WordPress conditionals like `is_archive()` rely on the main query object; let's swap it out for our custom query to ensure that everything works as it should
  if ( $query->have_posts() ) {
    $wp_query_copy = $wp_query; // Copy the main query
    $wp_query = $query; // Replace the main query with our custom query
    while ( $wp_query->have_posts() ) : $wp_query->the_post();
      ob_start(); // Start output buffering; necessary because `get_template_part` echoes contents
      get_template_part( 'content', $view );
      $html .= ob_get_clean(); // Clean the output buffer after every cycle
    endwhile;
    $wp_query = $wp_query_copy; // Swap the main query back in; we could use `wp_reset_query()` but this approach handles the query exactly as we received it
    wp_reset_postdata(); // Just in case anything went awry
  }

  // HTML scaffolding specific to each view
  if ( !empty( $html ) ) {
    if ( $view == 'gallery' ) {
      $html_before = '<section class="' . $view . '-view view-posts gallery gallery-flex">';
    } else {
      $html_before = '<section class="' . $view . '-view view-posts">';
    }
    if ( !empty( $title ) )
      $html_before .= '<h2>' . $title . '</h2>';
    if ( !empty( $content ) )
      $html_before .= '<p>' . wptexturize( do_shortcode( $content ) ) . '</p>'; // @TODO: sanitize content
    $html_after = '</section>';
  }

  $html = $html_before . $html . $html_after;

  return $html;
}
