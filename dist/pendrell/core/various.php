<?php // ==== VARIOUS ==== //

// == MISC == //

// Body class filter
if ( !function_exists( 'pendrell_body_class' ) ) : function pendrell_body_class( $classes ) {
  if ( is_multi_author() ) {
    $classes[] = 'group-blog';
  } else {
    $classes[] = 'single-author';
  }
  return $classes;
} endif;
add_filter( 'body_class', 'pendrell_body_class' );



// == SHORTCODES == //

// Shortcode fallback; in case Ubik or other plugins are disabled
if ( !function_exists( 'pendrell_shortcode_fallback' ) ) : function pendrell_shortcode_fallback( $atts, $content = null ) {
  return $content;
} endif;

// Conditional list of shortcodes to check
if ( !function_exists( 'pendrell_shortcode_init' ) ) : function pendrell_shortcode_init() {

  // Places shortcode from Ubik
  if ( !function_exists( 'ubik_places_init' ) )
    add_shortcode( 'place', 'pendrell_shortcode_fallback' );

  // Group shortcode from Ubik
  if ( !function_exists( 'ubik_image_group_shortcode' ) )
    add_shortcode( 'group', 'pendrell_shortcode_fallback' );

  // Discog shortcode from Ubik
  if ( !function_exists( 'ubik_discography_shortcode' ) )
    add_shortcode( 'discog', 'pendrell_shortcode_fallback' );

} endif;
add_action( 'init', 'pendrell_shortcode_init' );



// == THUMBNAILS == //

// Thumbnail ID fallback
if ( !function_exists( 'pendrell_thumbnail_id' ) ) : function pendrell_thumbnail_id( $post_id = null, $fallback_id = null ) {
  if ( function_exists( 'ubik_thumbnail_id' ) )
    return ubik_thumbnail_id( $post_id, $fallback_id );
  return get_post_thumbnail_id( $post_id );
} endif;



// == PAGE LOADER == //

// Test whether the current request will work with the page loader script (PG8)
if ( !function_exists( 'pendrell_load_pg8' ) ) : function pendrell_load_pg8() {
  if ( is_archive() || is_home() || is_search() )
    return true;
  return false;
} endif;

