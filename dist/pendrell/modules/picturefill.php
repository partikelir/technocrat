<?php // ==== PICTUREFILL ==== //

// Setup a few media queries for the `sizes` attribute; must be an array
if ( !function_exists( 'pendrell_sizes_media_queries' ) ) : function pendrell_sizes_media_queries( $queries ) {

  global $main_width;

  // Breakpoints replicated from `src/scss/_base_config.scss` without pixel values
  $mq_small = $main_width/1.2;
  $mq_medium = $main_width + ( PENDRELL_BASELINE * 3);
  $mq_large = $main_width + ( PENDRELL_BASELINE * 8);

  //$queries[] = '(min-width: ' . $mq_large . 'px) 100%';

  return $queries;

} endif;
add_filter( 'ubik_imagery_sizes_media_queries', 'pendrell_sizes_media_queries' );



// Return $content_width as the default `sizes` fallback
if ( !function_exists( 'pendrell_sizes_default' ) ) : function pendrell_sizes_default( $default ) {

  global $content_width;

  if ( isset( $content_width ) )
    $default = $content_width . 'px';

  return $default;

} endif;
add_filter( 'ubik_imagery_sizes_default', 'pendrell_sizes_default' );
