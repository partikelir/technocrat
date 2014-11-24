<?php // ==== ADMIN FUNCTIONS ==== //

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



// Styles the visual editor like the front-end; @TODO: complete this component, presently it is only sketched out
if ( !function_exists( 'pendrell_editor_style' ) ) : function pendrell_editor_style() {
  if ( is_admin() ) {
    add_editor_style( array(
      get_template_directory_uri() . '/editor-style.css?version=' . filemtime( get_template_directory() . '/editor-style.css' ), // Cache busting only works with absolute URLs
      pendrell_get_font_url() // Add Google Fonts to the visual editor
    ) );
  }
} endif;
add_action( 'after_setup_theme', 'pendrell_editor_style' );
