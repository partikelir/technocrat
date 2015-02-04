<?php // ==== IMAGES ==== //

// Generates HTML5 markup for image attachments and image format posts; called in Pendrell's templates
if ( !function_exists( 'pendrell_image_wrapper' ) ) : function pendrell_image_wrapper( $content = '' ) {

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
    if ( !is_singular() ) {
      $url = get_permalink(); // Link to the post itself, not the attachment, in category archives and such
    } else {
      $url = ''; // Let Ubik Imagery figure it out
    }

  // Attachments: load post data from the attachment itself
  } elseif ( wp_attachment_is_image() ) {
    $id = $post->ID;
    $caption = $post->post_excerpt;
    $url = get_permalink( $post->post_parent ); // Click on the attachment and return to the parent post
  }

  // Generate image markup from ID, size, caption, and URL and append existing content
  return ubik_imagery( $html = '', $id, $caption, $title = '', $align = '', $url, $size ) . $content;
} endif;
add_filter( 'the_content', 'pendrell_image_wrapper' );



// Clear out WordPress default markup for image attachments
if ( !function_exists( 'pendrell_image_prepend' ) ) : function pendrell_image_prepend( $content = '' ) {
  if ( wp_attachment_is_image() )
    $content = ''; // Pendrell handles image attachments internally; jettison WordPress default markup
  return $content;
} endif;
add_filter( 'prepend_attachment', 'pendrell_image_prepend' );



// Image overlay wrapper; for use with Ubik Imagery
if ( !function_exists( 'pendrell_image_overlay' ) ) : function pendrell_image_overlay( $html = '', $position = 'top-right' ) {
  if ( $html !== '' && in_array( $position, array( 'top-right', 'top-left', 'bottom-right', 'bottom-left' ) ) )
    $html = '<footer class="overlay overlay-' . esc_attr( $position ) . '">' . (string) $html . '</footer>';
  return $html;
} endif;



// Thumbnail ID fallback
if ( !function_exists( 'pendrell_thumbnail_id' ) ) : function pendrell_thumbnail_id( $post_id = null, $fallback_id = null ) {
  if ( function_exists( 'ubik_imagery_thumbnail_id' ) )
    return ubik_imagery_thumbnail_id( $post_id, $fallback_id );
  return get_post_thumbnail_id( $post_id );
} endif;
