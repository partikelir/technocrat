<?php // === ADMIN FUNCTIONS === //

// Add custom images sizes to the media uploader dropdown; use a plugin like Regenerate Thumbnails as needed: https://wordpress.org/plugins/regenerate-thumbnails/
if ( !function_exists( 'pendrell_image_sizes' ) ) : function pendrell_image_sizes( $sizes ) {

	// Cheap hack to keep "full size" at the bottom of the dropdown: unset and reset it after adding our custom sizes
	unset ( $sizes['medium'] );
	unset ( $sizes['large'] );
	unset ( $sizes['full'] );

	// The full list
  $sizes['third'] = __( 'Third-width', 'pendrell' );
  $sizes['third-square'] = __( 'Third-width square', 'pendrell' );
  $sizes['half'] = __( 'Half-width', 'pendrell' );
  $sizes['half-square'] = __( 'Half-width square', 'pendrell' );
  $sizes['medium'] = __( 'Medium', 'pendrell' );
  $sizes['medium-square'] = __( 'Medium square', 'pendrell' );
  $sizes['large'] = __( 'Large', 'pendrell' );
  $sizes['large-square'] = __( 'Large square', 'pendrell' );
  $sizes['full'] = __( 'Full', 'pendrell' );
  return $sizes;

} endif;
add_filter( 'image_size_names_choose', 'pendrell_image_sizes' );



// Filter TinyMCE CSS path to include Google Fonts; adapted from Twenty Twelve; @TODO: test this with the visual editor
if ( !function_exists( 'pendrell_mce_css' ) ) : function pendrell_mce_css( $mce_css ) {
  $font_url = pendrell_get_font_url();

  if ( empty( $font_url ) )
    return $mce_css;

  if ( !empty( $mce_css ) )
    $mce_css .= ',';

  $mce_css .= esc_url_raw( str_replace( ',', '%2C', $font_url ) );

  return $mce_css;
} endif;
add_filter( 'mce_css', 'pendrell_mce_css' );
