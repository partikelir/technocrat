<?php // === GENERAL === //

// Enqueue scripts
if ( !function_exists( 'pendrell_enqueue_scripts' ) ) : function pendrell_enqueue_scripts() {

  // Front-end scripts
	if ( !is_admin() ) {

    // Load minified scripts if debug mode is off
    if ( WP_DEBUG === true ) {
      $suffix = '';
    } else {
      $suffix = '.min';
    }

    // Load theme-specific JavaScript with versioning based on last modified time; http://www.ericmmartin.com/5-tips-for-using-jquery-with-wordpress/
		wp_enqueue_script( 'pendrell-core', get_stylesheet_directory_uri() . '/js/core' . $suffix . '.js', array( 'jquery' ), filemtime( get_template_directory() . '/js/core' . $suffix . '.js' ), true );
    if ( PENDRELL_SCRIPTS_AJAXINATE )
      wp_enqueue_script( 'pendrell-ajaxinate', get_stylesheet_directory_uri() . '/js/ajaxinate' . $suffix . '.js', array( 'jquery' ), filemtime( get_template_directory() . '/js/ajaxinate' . $suffix . '.js' ), true );
    if ( PENDRELL_SCRIPTS_PRISM )
      wp_enqueue_script( 'pendrell-prism', get_stylesheet_directory_uri() . '/js/prism' . $suffix . '.js', array( 'jquery' ), filemtime( get_template_directory() . '/js/prism' . $suffix . '.js' ), true );

    // Set some variables via PHP prior to loading our custom scripts
    wp_localize_script( 'pendrell-core', 'themeVars', array(
        'ajaxUrl'               => admin_url( 'admin-ajax.php' )
      , 'templateDirectory'     => get_template_directory_uri()
      )
    );
  }

  // Adds JavaScript to pages with the comment form to support sites with threaded comments
  if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
    wp_enqueue_script( 'comment-reply' );

  // Google web fonts
  $font_url = pendrell_get_font_url();
  if ( !empty( $font_url ) ) {
    wp_enqueue_style( 'pendrell-fonts', esc_url_raw( $font_url ), array(), null );
  }

  // Register and enqueue our main stylesheet with versioning based on last modified time
  wp_register_style( 'pendrell-style', get_stylesheet_uri(), $dependencies = array(), filemtime( get_template_directory() . '/style.css' ) );
  wp_enqueue_style( 'pendrell-style' );
} endif;
add_action( 'wp_enqueue_scripts', 'pendrell_enqueue_scripts' );



// Hack: simplify and customize Google font loading; reference Twenty Twelve for more advanced options
if ( !function_exists( 'pendrell_get_font_url' ) ) : function pendrell_get_font_url( $fonts = '' ) {
  $font_url = '';

  // Allows us to pass a Google web font declaration as needed
  if ( empty( $fonts ) )
    $fonts = PENDRELL_GOOGLE_FONTS ? PENDRELL_GOOGLE_FONTS : 'Open+Sans:400italic,700italic,400,700'; // Default back to Open Sans

  // Encode pipe character; explanation: http://www.designfordigital.com/2014/04/07/google-fonts-bad-value-css-validate/
  $fonts = str_replace( '|', '%7C', $fonts );

  $protocol = is_ssl() ? 'https' : 'http';

  $font_url = "$protocol://fonts.googleapis.com/css?family=" . $fonts;

  return $font_url;
} endif;



// Minimally functional wp_title filter; use Ubik (or your plugin of choice) for more SEO-friendly page titles
if ( !function_exists( 'pendrell_wp_title' ) ) : function pendrell_wp_title( $title, $sep = '-' ) {
  if ( is_front_page() || is_home() ) {
    $title = PENDRELL_NAME;
    if ( PENDRELL_DESCRIPTION )
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

  // Include the regular sidebar template if $sidebar has not been set to "false"
  if ( $sidebar )
    get_sidebar();
} endif;



// Footer info; relies on information in functions-config.php to generate appropriate content; it is't elegant but it gets the job done
if ( !function_exists( 'pendrell_footer_info' ) ) : function pendrell_footer_info() {

  // Copyright statement
  if ( PENDRELL_COPYRIGHT ) {

    // Output the copyright year or range of years
    if ( PENDRELL_COPYRIGHT_YEAR ) {
      echo '&copy;' . PENDRELL_COPYRIGHT_YEAR . '&#8211;' . date( "Y" ) . ' ';
    } else {
      echo '&copy;' . date( "Y" ) . ' ';
    }

    // If a custom copyright string has been set let's display that
    if ( PENDRELL_COPYRIGHT_STRING ) {
      echo PENDRELL_COPYRIGHT_STRING;
    } else {
      if ( PENDRELL_COPYRIGHT_AUTHOR && PENDRELL_COPYRIGHT_AUTHOR_URL ) {
  echo '<a href="' . PENDRELL_COPYRIGHT_AUTHOR_URL . '" rel="author">' . PENDRELL_COPYRIGHT_AUTHOR . '</a>';
      } elseif ( PENDRELL_COPYRIGHT_AUTHOR ) {
  echo PENDRELL_COPYRIGHT_AUTHOR;
      }
      echo '. ';
    }
  }

  // Powered by statement
  printf( __( '%1$s is powered by <a href="http://wordpress.org" rel="generator">WordPress</a> and <a href="%2$s">%3$s</a>.', 'pendrell' ),
    PENDRELL_NAME,
    PENDRELL_THEME_URL,
    PENDRELL_THEME_NAME . ' ' . PENDRELL_THEME_VERSION
  );

} endif;
