<?php // ==== VARIOUS ==== //

// == BODY CLASSES == //

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

  // Discog shortcode from Ubik RecordPress
  if ( !function_exists( 'ubik_discography_shortcode' ) )
    add_shortcode( 'discog', 'pendrell_shortcode_fallback' );

  // Places shortcode from Ubik Places
  if ( !function_exists( 'ubik_places_shortcode' ) )
    add_shortcode( 'place', 'pendrell_shortcode_fallback' );

} endif;
add_action( 'init', 'pendrell_shortcode_init' );
