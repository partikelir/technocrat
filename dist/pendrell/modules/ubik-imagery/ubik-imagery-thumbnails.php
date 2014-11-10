<?php // ==== THUMBNAILS ==== //

// Default thumbnail taken from first attached image; adapted from: http://wpengineer.com/1735/easier-better-solutions-to-get-pictures-on-your-posts/
function ubik_imagery_thumbnail( $html, $post_id, $post_thumbnail_id, $size, $attr ) {

  // If this post doesn't already have a thumbnail
  if ( empty( $html ) && !empty( $post_id ) )
    $post_thumbnail_id = ubik_imagery_thumbnail_id( $post_id );

  // Attempt to beautify thumbnail markup; note: this means that you shouldn't wrap post thumbnails in additional image markup
  if ( function_exists( 'ubik_imagery_markup' ) && !empty( $post_thumbnail_id ) && UBIK_IMAGERY_THUMBNAIL_MARKUP ) {
    $html = ubik_imagery_markup( '', $post_thumbnail_id, '', '', 'none', get_permalink( $post_id ), $size );
  } else {
    $html = wp_get_attachment_image( $post_thumbnail_id, $size, false, $attr );
  }

  return $html;
}
add_filter( 'post_thumbnail_html', 'ubik_imagery_thumbnail', 11, 5 );



// Return the ID of a post's thumbnail, the first attached image, or a fallback image specified in the configuration file
function ubik_imagery_thumbnail_id( $post_id = null, $fallback_id = null ) {

  // Try to get the current post ID if one was not passed
  if ( $post_id === null )
    $post_id = get_the_ID();

  // Return false if we have nothing to work with
  if ( empty( $post_id ) )
    return false;

  // Check for an existing featured image; this should take precedence over the other methods
  if ( has_post_thumbnail( $post_id ) )
    return get_post_thumbnail_id( $post_id );

  // Check for attachments and return the first of the lot
  $attachments = get_children( array(
    'numberposts'    => 1,
    'order'          => 'ASC',
    'orderby'        => 'menu_order ASC',
    'post_parent'    => $post_id,
    'post_mime_type' => 'image',
    'post_status'    => 'inherit',
    'post_type'      => 'attachment'
    )
  );

  // Fetch the first attachment if it exists
  if ( !empty( $attachments ) )
    return current( array_keys( $attachments ) );

  // Default image fallback; double check it is an existing image attachment first
  if ( $fallback_id === null && is_int( UBIK_IMAGERY_THUMBNAIL_DEFAULT ) )
    $fallback_id = UBIK_IMAGERY_THUMBNAIL_DEFAULT;

  if ( !empty( $fallback_id ) ) {
    $fallback_id = (int) $fallback_id;
    $post = get_post( $fallback_id );
    if ( !empty( $post ) ) {
      if ( wp_attachment_is_image( $fallback_id ) )
        return $fallback_id;
    }
  }

  // No thumbnail, attachment, or fallback image was found
  return false;
}



// Remove image width and height attributes from most images; via https://gist.github.com/miklb/2919525
function ubik_imagery_thumbnail_dimensions( $html ) {
  $html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );
  $html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );
  return $html;
}
if ( UBIK_IMAGERY_THUMBNAIL_NO_DIMENSIONS ) {
  add_filter( 'post_thumbnail_html', 'ubik_imagery_thumbnail_dimensions', 10 );
  add_filter( 'img_caption_shortcode', 'ubik_imagery_thumbnail_dimensions', 10 );
  add_filter( 'wp_caption', 'ubik_imagery_thumbnail_dimensions', 10 );
  add_filter( 'caption', 'ubik_imagery_thumbnail_dimensions', 10 );
  add_filter( 'ubik_imagery_shortcode', 'ubik_imagery_thumbnail_dimensions', 10);
}
