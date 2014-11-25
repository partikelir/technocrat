<?php // ==== SHORTCODE ==== //

// Places shortcode; simply wrap places in [place]Place name[/place]; alternately [place slug="place_slug"]Place name[/place]
if ( !function_exists( 'ubik_places_shortcode' ) ) : function ubik_places_shortcode( $atts, $content = null ) {

  // Extract attributes
  $args = shortcode_atts( array(
    'slug' => ''
  ), $atts );

  $slug = (string) $args['slug'];

  // Guess the slug if we have content but no slug attribute
  if ( empty( $slug ) && !empty( $content ) ) {
    $slug_query = sanitize_title( $content );

  // Keep the slug if we have one
  } elseif ( !empty( $slug ) ) {
    $slug_query = $slug;
  }

  // Don't bother if there's no slug
  if ( $slug_query ) {

    // Now test to see if the term exists in the places taxonomy
    $term = get_term_by( 'slug', $slug_query, 'places' );

    if ( !empty( $term ) ) {

      if ( empty( $slug ) || empty( $content ) ) {
        $title = $term->name;
      } else {
        $title = $content;
      }

      $content = sprintf( '<a href="%1$s">%2$s</a>',
        get_term_link( $term->term_id, 'places' ),
        $title
      );
    }
  }

  // If the preceding loop didn't find a match we still return the original content as-is; graceful fallback
  return $content;
} endif; // ubik_places_shortcode()
