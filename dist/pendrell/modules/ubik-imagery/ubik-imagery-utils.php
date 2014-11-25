<?php // ==== UTILS ==== //

// Get info about various images sizes, both standard and custom; adapted from http://codex.wordpress.org/Function_Reference/get_intermediate_image_sizes
if ( !function_exists( 'ubik_imagery_get_sizes' ) ) : function ubik_imagery_get_sizes( $size ) {

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
} endif; // end ubik_imagery_get_sizes()



// == CONTENT FILTERS == //

// Strip paragraph tags from orphaned more tags; mainly a hack to address more tags placed next to image shortcodes
if ( !function_exists( 'ubik_imagery_strip_more_orphan' ) ) : function ubik_imagery_strip_more_orphan( $content ) {
  $content = preg_replace( '/<p><span id="more-[0-9]*?"><\/span><\/p>/', '', $content );
  $content = preg_replace( '/<p><span id="more-[0-9]*?"><\/span>(<(div|img|figure)[\s\S]*?)<\/p>/', '$1', $content );
  $content = preg_replace( '/<p>(<(div|img|figure)[\s\S]*?)<span id="more-[0-9]*?"><\/span><\/p>/', '$1', $content );
  return $content;
} endif;
if ( UBIK_IMAGERY_STRIP_MORE_ORPHAN )
  add_filter( 'the_content', 'ubik_imagery_strip_more_orphan', 99 );



// Playing around with a function to strip paragraph tags off of images and such
if ( !function_exists( 'ubik_imagery_strip_media_p' ) ) : function ubik_imagery_strip_media_p( $content ) {
  $content = preg_replace( '/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content );
  $content = preg_replace( '/<p>\s*(<iframe .*>*.<\/iframe>)\s*<\/p>/iU', '\1', $content );
  return $content;
} endif;
if ( UBIK_IMAGERY_STRIP_MEDIA_P )
  add_filter( 'the_content', 'ubik_imagery_strip_media_p' );
