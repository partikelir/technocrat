<?php // ==== FEED ==== //

// Cleaner feed title
if ( !function_exists( 'pendrell_feed_title' ) ) : function pendrell_feed_title( $title, $sep ) {
  if ( !is_archive() )
    return '';
  return $title;
} endif;
if ( !function_exists( 'ubik_feed_title' ) )
  add_filter( 'get_wp_title_rss', 'pendrell_feed_title', 10, 2 );
