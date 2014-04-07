<?php // === UBIK === //

// Shortcode fallback; in case Ubik or other plugins are disabled
function pendrell_shortcode_fallback( $atts, $content = null ) {
  return $content;
}

function pendrell_shortcode_init() {
  // Conditional list of shortcodes to pass through
  if ( !function_exists( 'ubik_places_init') )
    add_shortcode('place', 'pendrell_shortcode_fallback');
}
add_action( 'init', 'pendrell_shortcode_init' );
