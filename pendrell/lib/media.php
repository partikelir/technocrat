<?php // ==== MEDIA ==== //

// == IMAGES == //

// Generates HTML5 markup for image attachments and image format posts; called in Pendrell's templates
if ( !function_exists( 'pendrell_image_wrapper' ) ) : function pendrell_image_wrapper() {

  global $post;

  // Hooks into existing post_thumbnail_size filter; modified for full-width display in various.php
  $size = apply_filters( 'post_thumbnail_size', 'medium' );

  // Image post formats: load thumbnail and metadata from the attachment
  if ( has_post_format( 'image' ) && has_post_thumbnail() ) {
    $id = get_post_thumbnail_id();
    $html = get_the_post_thumbnail( $post->ID, $size );
    $caption = get_post( $id )->post_excerpt;
    $description = get_post( $id )->post_content;

  // Attachments: load post data from the attachment itself
  } elseif ( is_attachment() && wp_attachment_is_image() ) {
    $id = $post->ID;
    $html = wp_get_attachment_image( $post->ID, $size );
    $caption = get_the_excerpt();
    $description = $post->post_content;
  }

  // Check to see if we have anything; image format posts without thumbnails will return nothing
  if ( !empty( $id ) ) {

    $content = pendrell_image_markup( $html, $id, $caption, $title = '', $align = 'alignnone', $url = '', $size );

    // Raw description; let's pass it through the content filter
    if ( !empty( $description ) )
      $content .= apply_filters( 'the_content', $description );

    return $content;
  }
} endif;



// Thin wrapper for ubik_image_markup with graceful fallback
if ( !function_exists( 'pendrell_image_markup' ) ) : function pendrell_image_markup( $html, $id, $caption, $title = '', $align = 'alignnone', $url = '', $size ) {

    // If Ubik is installed...
    if ( function_exists( 'ubik_image_markup' ) ) {
      $content = ubik_image_markup( $html, $id, $caption, $title = '', $align = 'alignnone', $url = '', $size );

    // This is working, tested code but Ubik does things in a slightly more refined way
    } else {
      $content = '<figure id="' . $id . '" class="wp-caption" itemscope itemtype="http://schema.org/ImageObject">' . $html;
      if ( !empty( $caption ) )
        $content .= '<figcaption id="figcaption-' . $id . '" class="wp-caption-text" itemprop="caption">' . $caption . '</figcaption>';
      $content .= '</figure>' . "\n";
    }

  return $content;

} endif;



// Add featured images to the feed
function pendrell_image_feed( $content ) {

  // We should only need this on image format posts
  if ( has_post_format( 'image' ) && has_post_thumbnail() )
    $content = pendrell_image_wrapper() . $content;

  return $content;
}
add_filter( 'the_content_feed', 'pendrell_image_feed' );
