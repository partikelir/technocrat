<?php // ==== FEED ==== //

// Cleaner feed title
if ( !function_exists( 'pendrell_feed_title' ) ) : function pendrell_feed_title( $title, $sep ) {
  if ( !is_archive() )
    return '';
  return $title;
} endif;
if ( !function_exists( 'ubik_feed_title' ) )
  add_filter( 'get_wp_title_rss', 'pendrell_feed_title', 10, 2 );



// Add featured images to the feed
function pendrell_image_feed( $content ) {

  // We should only need this on image format posts
  if ( has_post_format( 'image' ) && has_post_thumbnail() )
    $content = pendrell_image_wrapper() . $content;

  return $content;
}
// This shouldn't be necessary; @TODO: verify that everything works as it should without this function
//add_filter( 'the_content_feed', 'pendrell_image_feed' );
