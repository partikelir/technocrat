<?php // ==== UTILS ==== //

// Get info about various images sizes, both standard and custom; adapted from http://codex.wordpress.org/Function_Reference/get_intermediate_image_sizes
function ubik_imagery_get_sizes( $size ) {

  global $_wp_additional_image_sizes;
  $sizes = array();
  $get_intermediate_image_sizes = get_intermediate_image_sizes();

  // Create the full array with sizes and crop info
  foreach( $get_intermediate_image_sizes as $_size ) {
    if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ) ) ) {
      $sizes[ $_size ]['width'] = get_option( $_size . '_size_w' );
      $sizes[ $_size ]['height'] = get_option( $_size . '_size_h' );
      $sizes[ $_size ]['crop'] = (bool) get_option( $_size . '_crop' );
    } elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
      $sizes[ $_size ] = array(
        'width' => $_wp_additional_image_sizes[ $_size ]['width'],
        'height' => $_wp_additional_image_sizes[ $_size ]['height'],
        'crop' =>  $_wp_additional_image_sizes[ $_size ]['crop']
      );
    }
  }

  // Get only one size if found
  if ( $size ) {
    if( isset( $sizes[ $size ] ) ) {
      return $sizes[ $size ];
    } else {
      return false;
    }
  }
  return $sizes;
}
