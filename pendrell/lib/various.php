<?php // ==== VARIOUS ==== //

// == MISC == //

// Body class filter
if ( !function_exists( 'pendrell_body_class' ) ) : function pendrell_body_class( $classes ) {

  // Full width page templates (as specified below)
  if ( pendrell_is_full_width() ) {
    $classes[] = 'full-width';
  }

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

if ( !function_exists( 'pendrell_shortcode_init' ) ) : function pendrell_shortcode_init() {
  // Conditional list of shortcodes to pass through
  if ( !function_exists( 'ubik_places_init' ) )
    add_shortcode( 'place', 'pendrell_shortcode_fallback' );
} endif;
add_action( 'init', 'pendrell_shortcode_init' );



// == JETPACK == //

// Add theme support for Infinite Scroll: http://jetpack.me/support/infinite-scroll/
if ( !function_exists( 'pendrell_jetpack_setup' ) ) : function pendrell_jetpack_setup() {
  add_theme_support( 'infinite-scroll', array(
    'container' => 'main',
    'footer'    => 'colophon'
  ) );
} endif;
//add_action( 'after_setup_theme', 'pendrell_jetpack_setup' );
