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



// Minimally functional wp_title filter; use Ubik (or your plugin of choice) for more SEO-friendly page titles
function pendrell_wp_title( $title, $sep = '-' ) {
  if ( is_front_page() || is_home() ) {
    $title = get_bloginfo( 'name' );
    if ( get_bloginfo( 'description' ) )
      $title .= ' ' . $sep . ' ' . get_bloginfo( 'description' );
  } else {
    $title = $title . get_bloginfo( 'name' );
  }
  return $title;
}
add_filter( 'wp_title', 'pendrell_wp_title', 11, 3 );



// Sidebar handler; since WordPress hasn't really made things easy in this department
function pendrell_sidebar( $sidebar = true ) {

  // Filter the sidebar variable; this way we can set it to "false" in other locations
  $sidebar = apply_filters( 'pendrell_sidebar', $sidebar );

  // Don't display sidebar for certain post formats
  if ( is_singular() && has_post_format( array( 'aside', 'link', 'quote', 'status' ) ) ) {
    $sidebar = false;
  }

  // Include the regular sidebar template if $sidebar has not been set to "false"
  if ( $sidebar ) {
    get_sidebar();
  }
}
