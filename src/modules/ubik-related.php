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

// Comment score
function pendrell_related_score_comments( $score = 1 ) {
  return $score;
}
//add_filter( 'ubik_related_score_comments', 'pendrell_related_score_comments' );

// Comment threshold
function pendrell_related_score_comments_threshold( $score = 1 ) {
  return $score;
}
//add_filter( 'ubik_related_score_comments_threshold', 'pendrell_related_score_comments_threshold' );

// Thumbnail score
function pendrell_related_score_thumbnail( $score = 1 ) {
  return $score;
}
//add_filter( 'ubik_related_score_thumbnail', 'pendrell_related_score_thumbnail' );

// Post formats exclusion; accepts an array of port format slugs e.g. link, quote, etc.
function pendrell_related_score_formats_exclude( $formats = array() ) {
  return array( 'aside', 'link', 'quote', 'status' );
}
if ( PENDRELL_POST_FORMATS )
  add_filter( 'ubik_related_score_formats_exclude', 'pendrell_related_score_formats_exclude' );



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
      $contents = pendrell_image_overlay_metadata( get_comments_number( $related_post ) . ' ' . ubik_svg_icon( pendrell_icon( 'related-comments' ), __( 'Comments', 'pendrell' ) ) ),
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
