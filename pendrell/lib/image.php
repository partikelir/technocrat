<?php // ==== IMAGES ==== //

// Generates HTML5 markup for image attachments and image format posts; called in Pendrell's templates
if ( !function_exists( 'pendrell_image_wrapper' ) ) : function pendrell_image_wrapper( $content ) {

  if ( !has_post_format( 'image' ) && !is_attachment() )
    return $content;

  global $post;

  // Hooks into existing post_thumbnail_size filter; modified for full-width display
  $size = apply_filters( 'post_thumbnail_size', 'medium' );

  // Image post formats: load thumbnail and metadata from the attachment
  if ( has_post_format( 'image' ) && has_post_thumbnail() ) {
    $id = get_post_thumbnail_id();
    $caption = get_post( $id )->post_excerpt;
    $html = get_the_post_thumbnail( $post->ID, $size );
    if ( !is_singular() ) // Conditionally wrap the image in a link to the image post itself
      $html = '<a href="' . get_permalink() . '" rel="bookmark">' . $html . '</a>';

  // Attachments: load post data from the attachment itself
  } elseif ( is_attachment() && wp_attachment_is_image() ) {
    $id = $post->ID;
    $caption = get_the_excerpt();
    $html = wp_get_attachment_image( $post->ID, $size );
  }

  // Check to see if we have anything; image format posts without thumbnails will return nothing
  if ( !empty( $id ) )
    $content = pendrell_image_markup( $html, $id, $caption, $title = '', $align = 'none', $url = '', $size ) . $content;

  return $content;

} endif;
remove_filter( 'the_content', 'prepend_attachment' ); // Removes default WordPress functionality for all attachments, not just images
add_filter( 'the_content', 'pendrell_image_wrapper' );



// Thin wrapper for ubik_image_markup with graceful fallback
if ( !function_exists( 'pendrell_image_markup' ) ) : function pendrell_image_markup( $html, $id, $caption, $title = '', $align = 'none', $url = '', $size = 'medium' ) {

    // If Ubik is installed...
    if ( function_exists( 'ubik_image_markup' ) ) {
      $content = ubik_image_markup( $html, $id, $caption, $title = '', $align = 'none', $url = '', $size );

    // This stuff works but Ubik does things in a slightly more refined way
    } else {

      // Feeds won't validate with fancy HTML5 tags; let's keep things simply
      if ( is_feed() ) {
        $content = $html;
        if ( !empty( $caption ) )
          $content .= '<br/><small>' . $caption . '</small> ';

      // Produce some simple HTML5 markup for images and captions
      } else {
        $content = '<figure id="' . $id . '" class="wp-caption wp-caption-' . $id . ' ' . esc_attr( $align ) . '" itemscope itemtype="http://schema.org/ImageObject">' . $html;
        if ( !empty( $caption ) )
          $content .= '<figcaption id="figcaption-' . $id . '" class="wp-caption-text" itemprop="caption">' . $caption . '</figcaption>';
        $content .= '</figure>' . "\n";
      }
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



// Image shortcode fallback (in case Ubik is not active)
function pendrell_image_shortcode( $atts, $caption = null ) {
  extract( shortcode_atts( array(
    'id'            => '',
    'title'         => '',
    'align'         => 'none',
    'url'           => '',
    'size'          => 'medium',
    'alt'           => ''
  ), $atts ) );

  // The get_image_tag function requires a simple alignment e.g. "none", "left", etc.
  $align = str_replace( 'align', '', $align );

  // Default img element generator
  $html = get_image_tag( $id, $alt, $title, $align, $size );

  return apply_filters( 'pendrell_image_shortcode', pendrell_image_markup( $html, $id, $caption, $title, $align, $url, $size, $alt ) );
}
if ( !function_exists( 'ubik_image_shortcode' ) )
  add_shortcode( 'image', 'pendrell_image_shortcode' );
