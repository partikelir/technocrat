<?php // ==== UBIK RELATED ==== //

// Load component
require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-related/ubik-related.php' );



// Add various custom taxonomies to the related posts feature
function pendrell_related_taxonomies( $taxonomies = array() ) {
  if ( PENDRELL_UBIK_PLACES )
    $taxonomies['places'] = 2; // This taxonomy is also extended (below)
  if ( PENDRELL_UBIK_RECORDPRESS ) {
    $taxonomies['artists'] = 3;
    $taxonomies['styles'] = 2;
  }
  if ( PENDRELL_UBIK_SERIES )
    $taxonomies['series'] = 2;
  return $taxonomies;
}
add_filter( 'pendrell_related_taxonomies', 'pendrell_related_taxonomies' );



// Extended taxonomies: a broader search for related posts using sibling terms
function pendrell_related_taxonomies_extended( $taxonomies = array() ) {
  if ( PENDRELL_UBIK_PLACES )
    $taxonomies[] = 'places';
  return $taxonomies;
}
add_filter( 'ubik_related_taxonomies_extended', 'pendrell_related_taxonomies_extended' );



// Related posts display switch
function pendrell_related_display( $switch = true ) {
  return $switch;
}
//add_filter( 'pendrell_related_display', 'pendrell_related_display' );



// Adjust scoring based on various factors
function pendrell_related_score( $related ) {
  foreach ( $related as $id => $count ) {
    $comments = wp_count_comments( $id );
    if ( $comments->approved >= 3 )
      $related[$id]++;
    if ( has_post_thumbnail( $id ) )
      $related[$id]++;
    if ( !has_post_thumbnail( $id ) )
      $related[$id]--;
    if ( PENDRELL_POST_FORMATS ) {
      if ( in_array( get_post_format( $id ), array( 'aside', 'chat', 'link', 'quote', 'status' ) ) )
        $related[$id] = 0;
    }
  }
  return $related;
}
add_filter( 'ubik_related_score', 'pendrell_related_score' );



// Display a list of related posts as thumbnails
// @filter: pendrell_related_display
// @filter: pendrell_related_taxonomies
function pendrell_related_posts() {

  // Check if this is singular content
  if ( !is_singular() )
    return;

  // Display related posts; true/false
  $display = (bool) apply_filters( 'pendrell_related_display', true );

  // Display mode; gallery or list (note that these do not have anything to do with Ubik Views)
  $mode = (string) apply_filters( 'pendrell_related_mode', 'list' );

  // Additional constraints on displaying related posts: not on pages, attachments, password-protected posts, or certain post formats
  if ( $display === false || post_password_required() || is_page() || is_attachment() || has_post_format( array( 'aside', 'link', 'quote', 'status' ) ) )
    return;

  // Retrieve a list of related post IDs; null allows the function to use the current post
  // Format: array( 'taxonomy' => (int) weight )
  $related_posts = ubik_related_posts( null, apply_filters( 'pendrell_related_taxonomies', array( 'post_tag' => 1 ) ) );

  // Only proceed if related posts were found
  if ( !empty( $related_posts ) ) {
    if ( $mode === 'gallery' )
      echo pendrell_related_posts_gallery( $related_posts );
    if ( $mode === 'list' )
      echo pendrell_related_posts_list( $related_posts );
  }
}
add_action( 'pendrell_comment_template_before', 'pendrell_related_posts' );



// Output related posts as a gallery
function pendrell_related_posts_gallery( $related_posts = '' ) {

  // Exit early if we don't have what we need
  if ( empty( $related_posts ) )
    return;

  // Show more related posts on full size posts
  $count = 3;
  $size = 'third-square';
  if ( pendrell_full_width() ) {
    $count = 4;
    $size = 'quarter-square';
  }

  // We only want the first three results for this theme
  $related_posts = array_slice( $related_posts, 0, $count );

  // Iterate through each related post and populate the gallery
  $gallery = '';
  foreach ( $related_posts as $related_post ) {

    // Long title handler
    $related_title = get_the_title( $related_post );
    if ( str_word_count( $related_title ) > 10 )
      $related_title = ubik_text_truncate( $related_title, 8, '&hellip;', '' );

    // Output the gallery item
    $gallery .= ubik_imagery(
      $html     = '',
      $id       = pendrell_thumbnail_id( $related_post ),
      $caption  = $related_title,
      $title    = '',
      $align    = '',
      $url      = get_permalink( $related_post ),
      $size     = $size,
      $alt      = '',
      $rel      = '',
      $class    = 'related-post overlay',
      $contents = pendrell_image_overlay_metadata( $related_post ),
      $context  = array( 'group', 'static' )
    );
  }

  // Generate markup
  $output = '<section class="entry-extras related-posts related-posts-gallery">';
  $output .= '<h3>' . __( 'Related posts', 'pendrell' ) . '</h3>';
  $output .= '<div class="gallery gallery-static gallery-columns-' . $count . '">' . $gallery . '</div></section>';

  return $output;
}



// Output related posts as a list
function pendrell_related_posts_list( $related_posts ) {

  // Exit early if we don't have what we need
  if ( empty( $related_posts ) )
    return;

  // How many results would you like? These should already be ordered in priority sequence
  $related_posts = array_slice( $related_posts, 0, apply_filters( 'pendrell_related_posts_list_count', 5 ) );

  // Iterate through each related post
  $list = '';
  foreach ( $related_posts as $related_post ) {
    $list .= '<li><a href="' . get_permalink( $related_post ) . '">' . get_the_title( $related_post ) . '</a></li>';
  }

  // Generate markup
  $output = '<section class="entry-extras related-posts related-posts-list">';
  $output .= '<h3>' . __( 'Related posts', 'pendrell' ) . '</h3>';
  $output .= '<ul>' . $list . '</ul></section>';

  return $output;
}
