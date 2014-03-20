<?php // === GENERAL === //

// Enqueue scripts
function pendrell_enqueue_scripts() {

  // Load theme-specific JavaScript
	if ( !is_admin() ) { // http://www.ericmmartin.com/5-tips-for-using-jquery-with-wordpress/
		wp_enqueue_script( 'pendrell', get_stylesheet_directory_uri() . '/pendrell.min.js', array( 'jquery' ), '0.1', true );
    wp_enqueue_script( 'pendrell-plugins', get_stylesheet_directory_uri() . '/pendrell-plugins.min.js', array( 'jquery' ), '0.1', true );
	}

  // Override Twenty Twelve font styles
  $font_url = pendrell_get_font_url();
  if ( ! empty( $font_url ) ) {
    wp_enqueue_style( 'pendrell-fonts', esc_url_raw( $font_url ), array(), null );
  }

  // Loads our main stylesheet.
  wp_enqueue_style( 'pendrell-style', get_stylesheet_uri() );
}
add_action( 'wp_enqueue_scripts', 'pendrell_enqueue_scripts' );



// Hack: simplify and customize Google font loading; reference Twenty Twelve for more advanced options
function pendrell_get_font_url() {
  $font_url = '';
  $protocol = is_ssl() ? 'https' : 'http';
  $fonts = PENDRELL_GOOGLE_FONTS ? PENDRELL_GOOGLE_FONTS : "Open+Sans:400italic,700italic,400,700"; // Default back to Open Sans
  $font_url = "$protocol://fonts.googleapis.com/css?family=" . $fonts;
  return $font_url;
}



// Main sidebar
function pendrell_widgets_init() {
  register_sidebar( array(
    'name' => __( 'Main sidebar', 'pendrell' ),
    'id' => 'sidebar-main',
    'description' => __( 'Appears on posts and most pages.', 'pendrell' ),
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget' => '</aside>',
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>',
  ) );
}
add_action( 'widgets_init', 'pendrell_widgets_init' );
