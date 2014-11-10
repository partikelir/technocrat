<?php // ==== FALLBACK ==== //

// Disabling Ubik Imagery? Not sure what to do with all those image shortcodes? Add this code to your theme!

// Image shortcode fallback markup
if ( !function_exists( 'ubik_imagery_fallback_markup' ) ) : function ubik_imagery_fallback_markup( $html, $id, $caption, $title = '', $align = 'none', $url = '', $size = 'medium', $alt = '', $rel = '', $class = '' ) {

  // Feeds won't validate with fancy HTML5 tags; let's keep things simply
  if ( is_feed() ) {
    $content = $html;
    if ( !empty( $caption ) )
      $content .= '<br/><small>' . $caption . '</small> ';

  // Produce some simple HTML5 markup for images and captions
  } else {
    $content = '<figure id="' . $id . '" class="wp-caption wp-caption-' . $id . ' ' . esc_attr( $align ) . '" itemscope itemtype="http://schema.org/ImageObject">' . $html;
    if ( !empty( $caption ) )
      $content .= '<figcaption id="figcaption-' . $id . '" class="wp-caption-text" itemprop="caption">' . $caption . '</figcaption>';
    $content .= '</figure>' . "\n";
  }
  return $content;
} endif;



// Image shortcode fallback; basic functionality paired with the markup pattern above
if ( !function_exists( 'ubik_imagery_image_shortcode_fallback' ) ) : function ubik_imagery_image_shortcode_fallback( $atts, $caption = null ) {
  $args = shortcode_atts( array(
    'id'            => '',
    'title'         => '',
    'align'         => 'none',
    'url'           => '',
    'size'          => 'medium',
    'alt'           => ''
  ), $atts );

  $id = $args['id'];
  $title = $args['title'];
  $align = str_replace( 'align', '', $args['align'] ); // The get_image_tag function requires a simple alignment e.g. "none", "left", etc.
  $url = $args['url'];
  $size = $args['size'];
  $alt = $args['alt'];

  // Default img element generator
  $html = get_image_tag( $id, $alt, $title, $align, $size );

  return apply_filters( 'ubik_imagery_image_shortcode_fallback', ubik_imagery_fallback_markup( $html, $id, $caption, $title, $align, $url, $size, $alt ) );
} endif;



// Simply return the contents of the group shortcode
if ( !function_exists( 'ubik_imagery_group_shortcode_fallback' ) ) : function ubik_imagery_group_shortcode_fallback( $atts, $caption = null ) {
  return $content;
} endif;



// Add image and group shortcodes if they don't appear to be active
if ( !function_exists( 'ubik_imagery_image_shortcode' ) )
  add_shortcode( 'image', 'ubik_imagery_image_shortcode_fallback' );

if ( !function_exists( 'ubik_imagery_group_shortcode' ) )
  add_shortcode( 'group', 'ubik_imagery_group_shortcode_fallback' );
