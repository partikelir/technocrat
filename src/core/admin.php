<?php // ==== ADMIN FUNCTIONS ==== //

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
