<?php // ==== VIEW POSTS SHORTCODE ==== //

// View posts shortcode; outputs content from within posts and pages by using the same templates used by Ubik Views (to be clear this component is not required for this kludge to work)
// Use cases: list children of current page; show artist discographies; etc.
// Example: [view-posts view="gallery" mode="children" title="Profiles"]Here are some profiles related to this page...[/view-posts]
// Example: [view-posts mode="tagged" title="Releases"]The full discography of artist X.[/view-posts]
// @TODO: this function needs a lot of work; this prototype is sufficient for my needs as of now but it should be expanded...
// For some ideas, consider: http://plugins.svn.wordpress.org/display-posts-shortcode/tags/2.4/display-posts-shortcode.php
if ( !function_exists( 'pendrell_view_posts_shortcode' ) ) : function pendrell_view_posts_shortcode( $atts, $content = null ) {
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

  $posts = pendrell_view_posts( $id, $view, $mode, $taxonomy, $title, $content );

  return $posts;
} endif;
add_shortcode( 'view-posts', 'pendrell_view_posts_shortcode' );



// Generate HTML for the view-posts shortcode; @TODO: make this more customizable; currently it's very limited to just a few modes
if ( !function_exists( 'pendrell_view_posts' ) ) : function pendrell_view_posts( $id = '', $view = 'list', $mode = '', $taxonomy = '', $title = '', $content ) {

  global $post; // Must be declared

  if ( empty( $id ) )
    $id = $post->ID;

  $html = '';
  $html_before = '';
  $html_after = '';

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

  $query = new WP_Query( wp_parse_args( $args, $defaults ) );

  if ( $query->have_posts() ) {
    while ( $query->have_posts() ) : $query->the_post();
      ob_start(); // Start output buffering; necessary because get_template_part echoes contents
      get_template_part( 'content', $view );
      $html .= ob_get_clean(); // Clean the output buffer after every cycle
    endwhile;
  }
  wp_reset_postdata();

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

} endif;
