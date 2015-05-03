<?php // ==== IMAGES ==== //

// Generates HTML5 markup for image attachments and image format posts; called in Pendrell's templates
function pendrell_image_wrapper( $content = '' ) {

  // Fail early if needed
  if ( ( has_post_format( 'image' ) && has_post_thumbnail() ) === false && !wp_attachment_is_image() )
    return $content;

  global $post;

  // Hooks into existing post_thumbnail_size filter; modified for full-width display
  $size = apply_filters( 'post_thumbnail_size', 'large' );

  // Image post formats: load thumbnail and metadata from the attachment
  if ( has_post_format( 'image' ) && has_post_thumbnail() ) {
    $id = get_post_thumbnail_id();
    $caption = get_post( $id )->post_excerpt;
    $url = ''; // Let Ubik Imagery figure it out
    if ( !is_singular() )
      $url = get_permalink(); // Link to the post itself, not the attachment, in category archives and such

  // Attachments: load post data from the attachment itself
  } elseif ( wp_attachment_is_image() ) {
    $id = $post->ID;
    $caption = $post->post_excerpt;
    $url = get_permalink( $post->post_parent ); // Click on the attachment and return to the parent post
  }

  // Set blank variables
  $html = $title = $align = $alt = $rel = $class = $contents = '';

  // Generate image markup from ID, size, caption, and URL and append existing content
  return ubik_imagery( $html, $id, $caption, $title, $align, $url, $size, $alt, $rel, $class, $contents, $context = 'content' ) . $content;
}
add_filter( 'the_content', 'pendrell_image_wrapper' );



// Image overlay metadata wrapper
function pendrell_image_overlay_wrapper( $html = '', $position = 'top-right', $class = '' ) {
  if ( !empty( $class ) )
    $class = ' ' . $class;
  if ( $html !== '' && in_array( $position, array( 'top-right', 'top-left', 'bottom-right', 'bottom-left' ) ) )
    $html = '<footer class="' . esc_attr( $position . $class ) . '">' . (string) $html . '</footer>';
  return $html;
}



// Image overlay metadata; displays comment count and date by default
function pendrell_image_overlay_metadata( $id = '' ) {

  // Try to guess the ID
  if ( empty( $id ) )
    $id = get_the_ID();

  // Initialize
  $output = '';

  // Comments
  $comments_count = get_comments_number( $id );
  if ( $comments_count > 0 ) {
    $comments_meta = $comments_count . ' ' . pendrell_icon( 'overlay-comments', __( 'Comments', 'pendrell' ) );
    $output .= pendrell_image_overlay_wrapper( $comments_meta, 'top-right', 'comments' );
  }

  // Date
  $output .= pendrell_image_overlay_wrapper( get_the_date( 'M Y', $id ), 'top-left', 'date' );

  return $output;
}



// Clear out WordPress default markup for image attachments
function pendrell_image_prepend( $content = '' ) {
  if ( wp_attachment_is_image() )
    $content = ''; // Pendrell handles image attachments internally; jettison WordPress default markup
  return $content;
}
add_filter( 'prepend_attachment', 'pendrell_image_prepend' );



// Thumbnail ID fallback
function pendrell_thumbnail_id( $post_id = null, $fallback_id = null ) {
  if ( function_exists( 'ubik_imagery_thumbnail_id' ) )
    return ubik_imagery_thumbnail_id( $post_id, $fallback_id );
  return get_post_thumbnail_id( $post_id );
}
