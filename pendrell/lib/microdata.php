<?php // ==== MICRODATA ==== //

// Manage microdata scope
function pendrell_microdata_scope( $context = null ) {

  if ( empty( $context ) )
    return;

  switch ( $context ) {
    case 'image-template':
      if ( is_singular() && in_category( 'photography') )
        $scope = ' itemscope itemtype="http://schema.org/Photograph"';
    break;
  }

  $scope = apply_filters( 'pendrell_microdata_scope', $scope );

  echo $scope;
}

// Main wrapper function to generate item properties
function pendrell_microdata_wrapper( $content = null, $tag = null, $class = null, $itemprop = null ) {

  if ( empty( $content) )
    return;

  if ( !empty( $class ) )
    $class = ' class="' . $class . '"';

  // Just an example; @TODO: make this interoperable
  if ( is_tax( 'places' ) )
    $microdata = pendrell_microdata_itemprop( $itemprop );

  return '<' . $tag . $class . $microdata . '>' . $content . '</' . $tag . '>';
}

function pendrell_microdata_itemprop( $itemprop ) {
  if ( !empty( $itemprop ) )
    return ' itemprop="' . $itemprop . '"';
  return;
}

function pendrell_microdata_name( $content = null, $tag = 'span', $class = null ) {
  return pendrell_microdata_wrapper( $content, $tag, $class, 'name' );
}

function pendrell_microdata_description( $content = null, $tag = 'span', $class = null ) {
  return pendrell_microdata_wrapper( $content, $tag, $class, 'description' );
}
