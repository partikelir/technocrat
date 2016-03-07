<?php // ==== NAVIGATION ==== //

// Content navigation: used in archive, index, and search templates
// @filter: pendrell_nav_content
// @filter: pendrell_nav_content_switch
function pendrell_nav_content( $id = 'nav-below' ) {

  // Raw material
  global $paged, $wp_query;
  $max = $wp_query->max_num_pages;

  // Exit early under certain conditions (no pages to display; top navigation on page one of results)
  if ( is_single() || $max <= 1 || ( $id === 'nav-above' && !is_paged() ) || apply_filters( 'pendrell_nav_content_switch', true, $id ) === false )
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
    $prev_link = html_entity_decode( get_pagenum_link( $prev_page ) );
  if ( $next_page <= $max )
    $next_link = html_entity_decode( get_pagenum_link( $next_page ) );

  // Conditional output of previous and next links followed by the actual output
  if ( !empty( $prev_link ) )
    $output .= '<div class="nav-left"><a class="button button-left prev-page" href="' . esc_url( $prev_link ) . '" role="button">' . pendrell_icon_text( 'nav-previous', __( 'Previous', 'pendrell' ) ) . '</a></div>';
  if ( !empty( $next_link ) )
    $output .= '<div class="nav-right"><a class="button button-action next-page" href="' . esc_url( $next_link ) . '" role="button">' . pendrell_icon_text( 'nav-next', __( 'Next', 'pendrell' ) ) . '</a></div>';
  if ( !empty( $output ) )
    $output = '<nav id="' . $id . '" class="nav-content ' . $id . '"><h2 class="screen-reader-text">' . __( 'Content navigation', 'pendrell' ) . '</h2>' . $output  . '</nav>';

  // Output content navigation links
  echo apply_filters( 'pendrell_nav_content', $output );
}



// Navigation function selector for single entries
function pendrell_nav_singular() {

  // Initialize
  $output = $class = $help = '';

  // Singular navigation is always below
  $id = 'nav-below';

  // Handle three different content classes
  if ( is_attachment() ) {
    $output = pendrell_nav_attachment();
    $class = 'nav-attachment ' . $id;
    $help = __( 'Attachment navigation', 'pendrell' );
  } elseif ( is_page() ) {
    $output = pendrell_nav_page();
    $class = 'nav-page ' . $id;
    $help = __( 'Page navigation', 'pendrell' );
  } else {
    $output = pendrell_nav_post();
    $class = 'nav-post ' . $id;
    $help = __( 'Post navigation', 'pendrell' );
  }

  // Only output if we have something
  if ( !empty( $output ) )
    echo '<nav id="' . $id . '" class="nav-singular ' . $class . '"><h2 class="screen-reader-text">' . $help . '</h2>' . $output . '</nav>';
}
add_action( 'pendrell_singular_below', 'pendrell_nav_singular' );



// Post navigation: only to be used with the `single.php` template
// @filter: pendrell_nav_post
// @filter: pendrell_nav_post_same_term
function pendrell_nav_attachment() {

  // Previous post link handles the special case of attachments
  $output = get_previous_post_link( '<div class="nav-right">%link&nbsp;' . pendrell_nav_arrows( 'back' ) . '</div>', '%title' );

  return apply_filters( 'pendrell_nav_attachment', $output );
}



// Page navigation: only to be used with the `single.php` template
// @filter: pendrell_nav_page
function pendrell_nav_page() {

  global $post;

  // Initialize
  $output = '';

  // Get the immediate parent of the current page
  $ancestors = get_ancestors( $post->ID, 'page' );
  if ( !empty( $ancestors ) ) {
    $parent = $ancestors[0];
    if ( !empty( $parent ) ) {
      $output .= '<div class="nav-right"><a href="' . get_permalink( $parent ) . '">' . get_the_title( $parent ) . '</a>&nbsp;' . pendrell_nav_arrows( 'back' ) . '</div>';
    }
  }

  // Get the first child if no parent exists
  if ( empty( $output ) ) {
    $children = get_pages( 'child_of=' . $post->ID . '&sort_column=menu_order' );
    if ( !empty( $children ) ) {
      $child = $children[0]->ID;
      if ( !empty( $child ) ) {
        $output .= '<div class="nav-right"><a href="' . get_permalink( $child ) . '">' . get_the_title( $child ) . '</a>&nbsp;' . pendrell_nav_arrows( 'right' ) . '</div>';
      }
    }
  }

  return apply_filters( 'pendrell_nav_page', $output );
}



// Wrapper for wp_link_pages(); @TODO: separate by comma or something; built-in separator functionality is coded by crazy people
function pendrell_nav_page_links() {
  if ( is_page() ) {
    wp_link_pages( array(
      'before' => '<nav class="page-links">' . __( 'Pages:', 'pendrell' ),
      'after' => '</nav>'
    ) );
  }
}



// Post navigation: only to be used with the `single.php` template
// @filter: pendrell_nav_post
// @filter: pendrell_nav_post_same_term
function pendrell_nav_post() {

  // Fetch taxonomy
  $taxonomy = pendrell_nav_post_taxonomy();

  // Switch for same term functionality
  $same_term = apply_filters( 'pendrell_nav_post_same_term', true );

  // @TODO: use $post_tax; this seems to be very buggy in the current version of WP as per https://core.trac.wordpress.org/ticket/26937
  $output = get_previous_post_link( '<div class="nav-left">' . pendrell_nav_arrows( 'left' ) . '&nbsp;%link</div>', '%title', $same_term, '', $taxonomy );
  $output .= get_next_post_link( '<div class="nav-right">%link&nbsp;' . pendrell_nav_arrows( 'right' ) . '</div>', '%title', $same_term, '', $taxonomy );

  return apply_filters( 'pendrell_nav_post', $output );
}



// Post navigation taxonomy selector
// @filter: pendrell_nav_post_taxonomy
function pendrell_nav_post_taxonomy() {

  // Default taxonomy
  $taxonomy = 'category';

  // If this blog isn't big on categories let's use tags instead; @DEPENDENCY: relies on the ubik_terms_categorized conditional from Ubik Terms
  if ( !ubik_terms_categorized() )
    $taxonomy = 'post_tags';

  return apply_filters( 'pendrell_nav_post_taxonomy', $taxonomy );
}



// Comment navigation; @TODO: comment-loader.js script to pull in more comments via AJAX (low priority)
function pendrell_nav_comments() {

  // Check to see whether comments are paged and if there is more than one page
  if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) {
    ?><nav id="nav-comments" class="nav-comments">
      <h1 class="screen-reader-text"><?php _e( 'Comment navigation', 'pendrell' ); ?></h1>
      <div class="nav-left"><?php previous_comments_link( pendrell_nav_arrows( 'left' ) . '&nbsp;' . __( 'Previous comments', 'pendrell' ) ); ?></div>
      <div class="nav-right"><?php next_comments_link( __( 'Next comments', 'pendrell' ) . '%nbsp;' . pendrell_nav_arrows( 'right' ) ); ?></div>
    </nav><?php
  }
}



// Site navigation; this outputs the header menu and search form
function pendrell_nav_site() {

  // Responsive search bar; hidden except on small screens
  get_search_form();

  // Responsive menu wrapped in a container to handle the fact that `wp_nav_menu` defaults back to `wp_page_menu` when no menu is specified
  wp_nav_menu( array( 'theme_location' => 'header', 'menu_id' => 'menu-header', 'menu_class' => 'menu-inline' ) );
}
add_action( 'pendrell_site_navigation', 'pendrell_nav_site' );



// Navigation arrows; centralized in this utility function to allow for consistency across different forms of navigation
// @filter: pendrell_nav_arrows
function pendrell_nav_arrows( $name = '' ) {
  $arrows = apply_filters( 'pendrell_nav_arrows', array(
    'left'  => '<span class="nav-arrow">&larr;</span>'
  , 'right' => '<span class="nav-arrow">&rarr;</span>'
  , 'back'  => '<span class="nav-arrow">&larrhk;</span>'
  ) );
  if ( !empty( $name ) && array_key_exists( $name, $arrows ) )
    return $arrows[$name];
  return false;
}
