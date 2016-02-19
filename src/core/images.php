<?php // ==== IMAGES ==== //

// Generates HTML5 markup for image attachments and image format posts; called in Pendrell's templates
function pendrell_image_wrapper( $content = '' ) {

  // Fail early if needed
  if ( ( has_post_format( 'image' ) && has_post_thumbnail() ) === false && !wp_attachment_is_image() )
    return $content;

  global $post;

  // Hooks into existing post_thumbnail_size filter; modified for full-width display; @TODO: customize this
  $size = apply_filters( 'post_thumbnail_size', 'medium' );

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



// Image overlay contents; displays comment count and date by default (but you can filter this to insert your own stuff)
function pendrell_image_overlay_contents( $id = '' ) {
  if ( empty( $id ) )
    $id = get_the_ID(); // Try to guess the ID
  return apply_filters( 'pendrell_image_overlay_top_left', '', $id ) . apply_filters( 'pendrell_image_overlay_top_right', '', $id );
}



// Standard image overlay: comments
function pendrell_image_overlay_comments( $data = '', $id = '' ) {
  $comments_count = get_comments_number( $id );
  if ( $comments_count > 0 ) {
    $comments_meta = $comments_count . ' ' . pendrell_icon( 'overlay-comments', __( 'Comments', 'pendrell' ) );
    $data = pendrell_image_overlay_wrapper( $comments_meta, 'top-right', 'comments small' );
  }
  return $data;
}
add_filter( 'pendrell_image_overlay_top_right', 'pendrell_image_overlay_comments', '', 2 );



// Standard image overlay: date
function pendrell_image_overlay_date( $data = '', $id = '' ) {
  return pendrell_image_overlay_wrapper( get_the_date( 'M Y', $id ), 'top-left', 'date smaller' );
}
add_filter( 'pendrell_image_overlay_top_left', 'pendrell_image_overlay_date', '', 2 );



// Clear out WordPress default markup for image attachments
function pendrell_image_prepend( $content = '' ) {
  if ( wp_attachment_is_image() )
    $content = ''; // Pendrell handles image attachments internally; jettison WordPress default markup
  return $content;
}
add_filter( 'prepend_attachment', 'pendrell_image_prepend' );



// Full-width image size filter; assumes 'large' size images fill the window, which they should
function pendrell_image_full_width_resize( $size ) {
  if ( pendrell_full_width() && $size === 'medium' )
    $size = 'large';
  return $size;
}
if ( UBIK_IMAGERY_SRCSET === false )
  add_filter( 'ubik_imagery_size', 'pendrell_image_full_width_resize' );



// Thumbnail ID fallback
function pendrell_thumbnail_id( $post_id = null, $fallback_id = null ) {
  if ( function_exists( 'ubik_imagery_thumbnail_id' ) )
    return ubik_imagery_thumbnail_id( $post_id, $fallback_id );
  return get_post_thumbnail_id( $post_id );
}
