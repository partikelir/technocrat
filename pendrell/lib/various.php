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



// Name wrapper; a stand-in for future microdata use
if ( !function_exists( 'pendrell_name_wrapper' ) ) : function pendrell_name_wrapper( $content ) {
  return '<span>' . $content . '</span>';
} endif;



// == UBIK == //

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
  if ( !function_exists( 'ubik_group_shortcode' ) )
    add_shortcode( 'group', 'pendrell_shortcode_fallback' );

} endif;
add_action( 'init', 'pendrell_shortcode_init' );
