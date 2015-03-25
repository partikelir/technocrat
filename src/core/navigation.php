<?php // ==== NAVIGATION ==== //

// Site navigation; this outputs the header menu and search form
if ( !function_exists( 'pendrell_nav_site' ) ) : function pendrell_nav_site() {
  get_search_form(); // Responsive search bar; hidden except on small screens
  wp_nav_menu( array( 'theme_location' => 'header', 'menu_class' => 'menu-header inline-menu' ) );
} endif;
add_action( 'pendrell_site_navigation', 'pendrell_nav_site' );



// Content navigation: used in archive, index, and search templates
// @filter: pendrell_nav_content
if ( !function_exists( 'pendrell_nav_content' ) ) : function pendrell_nav_content( $id = 'nav-below' ) {

  // Raw material
  global $paged, $wp_query;
  $max = $wp_query->max_num_pages;

  // Exit early under certain conditions (no pages to display; top navigation on page one of results)
  if ( is_single() || $max <= 1 || ( $id === 'nav-above' && ( !is_paged() || ubik_is_view( 'gallery' ) ) ) || apply_filters( 'pendrell_nav_content_switch', true, $id ) === false )
    return;

  // Initialize
  $output = $prev_link = $next_link = '';

  // Paging
  if ( empty( $paged ) )
    $paged = 1;
  $next_page = intval( $paged ) + 1;
  $prev_page = intval( $paged ) - 1;

  // Get page links
  if ( $prev_page > 0 )
    $prev_link = get_pagenum_link( $prev_page );
  if ( $next_page <= $max )
    $next_link = get_pagenum_link( $next_page );

  // Conditional output of previous and next links followed by the actual output
  if ( !empty( $prev_link ) )
    $output .= '<div class="nav-previous"><a class="button prev-page" href="' . $prev_link . '" role="button">' . pendrell_icon( 'nav-previous', __( 'Previous', 'pendrell' ) ) . '</a></div>';
  if ( !empty( $next_link ) )
    $output .= '<div class="nav-next"><a class="button next-page" href="' . $next_link . '" role="button">' . pendrell_icon( 'nav-next', __( 'Next', 'pendrell' ) ) . '</a></div>';
  if ( !empty( $output ) )
    $output = '<nav id="' . $id . '" class="nav-content" role="navigation"><h2 class="screen-reader-text">' . __( 'Content navigation', 'pendrell' ) . '</h2>' . $output  . '</nav>';

  // Output content navigation links
  echo apply_filters( 'pendrell_nav_content', $output );
} endif;



// Page navigation: should only be used in page.php template
if ( !function_exists( 'pendrell_nav_page' ) ) : function pendrell_nav_page( $id = 'nav-below' ) {

  global $post;

  // Initialize
  $output = $child = $parent = '';

  // Search for ancestors; what we really want here is the immediate parent
  $ancestors = get_ancestors( $post->ID, 'page' );
  if ( !empty( $ancestors ) ) {
    $parent = $ancestors[0];
    if ( !empty( $parent ) ) {
      $parent = '<div class="nav-previous"><a href="' . get_permalink( $parent ) . '"><span class="nav-arrow">&larr;</span>&nbsp;' . get_the_title( $parent ) . '</a></div>';
    }
  }

  // Search for the first-born child
  $children = get_pages( 'child_of=' . $post->ID . '&sort_column=menu_order' );
  if ( !empty( $children ) ) {
    $child = $children[0]->ID;
    if ( !empty( $child ) ) {
      $child = '<div class="nav-next"><a href="' . get_permalink( $child ) . '">' . get_the_title( $child ) . '&nbsp;<span class="nav-arrow">&rarr;</span></a></div>';
    }
  }

  // Check if we have anything; if so, let's output what we've found
  if ( !empty( $parent ) || !empty( $child ) )
    $output = '<nav id="' . $id . '" class="nav-page ' . $id . '" role="navigation"><h2 class="screen-reader-text">' . __( 'Post navigation', 'pendrell' ) . '</h2>' . $parent . $child . '</nav>';

  echo apply_filters( 'pendrell_nav_page', $output );
} endif;



// Post navigation; only used in single.php temple
// @filter: pendrell_nav_post
if ( !function_exists( 'pendrell_nav_post' ) ) : function pendrell_nav_post( $id = 'nav-below' ) {

  // Initialize
  $output = $prev = $next = '';

  // Default taxonomy
  $post_tax = 'category';

  // If this blog isn't big on categories let's use tags instead; @DEPENDENCY: relies on the is_categorized conditional from Ubik core
  if ( !is_categorized() )
    $post_tax = 'post_tags';

  // @TODO: use $post_tax; this seems to be very buggy in the current version of WP as per https://core.trac.wordpress.org/ticket/26937
  $prev = get_previous_post_link( '<div class="nav-previous">%link</div>', _x( '<span class="nav-arrow">&larr;</span>&nbsp;%title', 'Previous post link', 'pendrell' ) );
  $next = get_next_post_link( '<div class="nav-next">%link</div>', _x( '%title&nbsp;<span class="nav-arrow">&rarr;</span>', 'Next post link', 'pendrell' ) );

  // Output markup if we've got a match
  if ( !empty( $next ) || !empty( $prev ) )
    $output = '<nav id="' . $id . '" class="nav-post ' . $id . '" role="navigation"><h2 class="screen-reader-text">' . __( 'Post navigation', 'pendrell' ) . '</h2>' . $prev . $next . '</nav>';

  echo apply_filters( 'pendrell_nav_post', $output );
} endif;



// Wrapper for wp_link_pages(); @TODO: separate by comma or something; built-in separator functionality is coded by crazy people
if ( !function_exists( 'pendrell_nav_link_pages' ) ) : function pendrell_nav_link_pages() {
  wp_link_pages( array(
    'before' => '<nav class="page-links">' . __( 'Pages:', 'pendrell' ),
    'after' => '</nav>'
  ) );
} endif;



// Comment navigation; @TODO: comment-loader.js script to pull in more comments via AJAX (low priority)
if ( !function_exists( 'pendrell_nav_comments' ) ) : function pendrell_nav_comments() {

  // Check to see whether comments are paged and if there is more than one page
  if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) {
    ?><nav id="nav-comments" class="nav-comments" role="navigation">
      <h1 class="screen-reader-text"><?php _e( 'Comment navigation', 'pendrell' ); ?></h1>
      <div class="nav-previous"><?php previous_comments_link( __( '<span class="nav-arrow">&larr; </span>Previous comments', 'pendrell' ) ); ?></div>
      <div class="nav-next"><?php next_comments_link( __( 'Next comments<span class="nav-arrow"> &rarr;</span>', 'pendrell' ) ); ?></div>
    </nav><?php
  }
} endif;

