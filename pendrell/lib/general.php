<?php // === GENERAL === //

// Enqueue scripts
if ( !function_exists( 'pendrell_enqueue_scripts' ) ) : function pendrell_enqueue_scripts() {

  // Load theme-specific JavaScript with versioning based on last modified time
	if ( !is_admin() ) { // http://www.ericmmartin.com/5-tips-for-using-jquery-with-wordpress/
		wp_enqueue_script( 'pendrell', get_stylesheet_directory_uri() . '/pendrell.min.js', array( 'jquery' ), filemtime( get_template_directory() . '/pendrell.min.js' ), true );
    wp_enqueue_script( 'pendrell-plugins', get_stylesheet_directory_uri() . '/pendrell-plugins.min.js', array( 'jquery' ), filemtime( get_template_directory() . '/pendrell-plugins.min.js' ), true );
	}

  // Google web fonts
  $font_url = pendrell_get_font_url();
  if ( !empty( $font_url ) ) {
    wp_enqueue_style( 'pendrell-fonts', esc_url_raw( $font_url ), array(), null );
  }

  // Google web fonts custom subset support
  if ( PENDRELL_GOOGLE_FONTS_SUBSET ) {
    $font_url_subset = pendrell_get_font_url( PENDRELL_GOOGLE_FONTS_SUBSET );
    wp_enqueue_style( 'pendrell-fonts-subset', esc_url_raw( $font_url_subset ), array(), null );
  }

  // Deregister bulky mediaelement.js stylesheets; via https://github.com/justintadlock/theme-mediaelement
  //wp_deregister_style( 'mediaelement' );
  //wp_deregister_style( 'wp-mediaelement' );

  // Register and enqueue our main stylesheet with versioning based on last modified time
  wp_register_style( 'pendrell-style', get_stylesheet_uri(), array(), filemtime( get_template_directory() . '/style.css' ) );
  wp_enqueue_style( 'pendrell-style' );
} endif;
add_action( 'wp_enqueue_scripts', 'pendrell_enqueue_scripts' );



// Hack: simplify and customize Google font loading; reference Twenty Twelve for more advanced options
if ( !function_exists( 'pendrell_get_font_url' ) ) : function pendrell_get_font_url( $fonts = '' ) {
  $font_url = '';
  if ( empty( $fonts ) )
    $fonts = PENDRELL_GOOGLE_FONTS ? PENDRELL_GOOGLE_FONTS : 'Open+Sans:400italic,700italic,400,700'; // Default back to Open Sans
  $fonts = str_replace( '|', '%7C', $fonts );
  $protocol = is_ssl() ? 'https' : 'http';
  $font_url = "$protocol://fonts.googleapis.com/css?family=" . $fonts;
  return $font_url;
} endif;



// Minimally functional wp_title filter; use Ubik (or your plugin of choice) for more SEO-friendly page titles
if ( !function_exists( 'pendrell_wp_title' ) ) : function pendrell_wp_title( $title, $sep = '-' ) {
  if ( is_front_page() || is_home() ) {
    $title = get_bloginfo( 'name' );
    if ( get_bloginfo( 'description' ) )
      $title .= ' ' . $sep . ' ' . get_bloginfo( 'description' );
  } else {
    $title = $title . get_bloginfo( 'name' );
  }
  return $title;
} endif;
add_filter( 'wp_title', 'pendrell_wp_title', 11, 3 );



// Sidebar handler; since WordPress hasn't really made things easy in this department
if ( !function_exists( 'pendrell_sidebar' ) ) : function pendrell_sidebar( $sidebar = true ) {

  // Filter the $sidebar variable; this way we can set it to "false" by hooking into this function elsewhere
  // This way the regular sidebar can be disabled and you can output whatever you want
  $sidebar = apply_filters( 'pendrell_sidebar', $sidebar );

  // Don't display sidebar for certain post formats
  if (
    ( is_singular() && has_post_format( array( 'aside', 'image', 'link', 'quote', 'status' ) ) )
    || pendrell_is_full_width()
  ) {
    $sidebar = false;
  }

  // Include the regular sidebar template if $sidebar has not been set to "false"
  if ( $sidebar ) {
    get_sidebar();
  }
} endif;
