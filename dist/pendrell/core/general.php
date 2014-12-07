<?php // ==== GENERAL ==== //

// == SCRIPTS & STYLESHEET LOADING == //

// Enqueue scripts; additional ideas to consider: https://github.com/roots/roots/blob/master/lib/scripts.php
if ( !function_exists( 'pendrell_enqueue_scripts' ) ) : function pendrell_enqueue_scripts() {

  // Front-end scripts
  if ( !is_admin() ) {

    $handle = 'pendrell-core';  // A generic script handle
    $script_name = '';          // To be filled later
    $script_vars = array();     // An empty array that can be filled with variables to send to front-end scripts

    // Load minified scripts if debug mode is off
    if ( WP_DEBUG === true ) {
      $suffix = '';
    } else {
      $suffix = '.min';
    }

    // Figure out which script to load based on various options set in `src/functions-config-defaults.php`
    // Note: Ajaxinate and Page Loader are mutually exclusive; @TODO: decide whether to keep or remove Ajaxinate altogether
    if ( PENDRELL_SCRIPTS_AJAXINATE )
      $script_name .= '-xn8';
    if ( PENDRELL_SCRIPTS_AJAXINATE === false && PENDRELL_SCRIPTS_PAGELOAD && pendrell_load_pg8() )
      $script_name .= '-pg8';
    if ( PENDRELL_SCRIPTS_AJAXINATE === false && PENDRELL_MODULE_RESPONSIVE && pendrell_load_pf() )
      $script_name .= '-pf';
    if ( PENDRELL_SCRIPTS_PRISM )
      $script_name .= '-prism';
    if ( empty( $script_name ) )
      $script_name .= '-core';

    // Load theme-specific JavaScript bundles with versioning based on last modified time; http://www.ericmmartin.com/5-tips-for-using-jquery-with-wordpress/
    // These bundles are created with Gulp; see `/gulpfile.js`
    // The handle is the same for each bundle since we're only loading one script; if you load others be sure to provide a new handle
    wp_enqueue_script( $handle, get_stylesheet_directory_uri() . '/js/p' . $script_name . $suffix . '.js', array( 'jquery' ), filemtime( get_template_directory() . '/js/p' . $script_name . $suffix . '.js' ), true );

    // Contact form (CF1) variable setup
    if ( is_page_template( 'page-templates/contact-form.php' ) ) {
      wp_enqueue_script( 'pendrell-contact-form', get_stylesheet_directory_uri() . '/js/contact-form' . $suffix . '.js', array( 'jquery' ), filemtime( get_template_directory() . '/js/contact-form' . $suffix . '.js' ), true );

      // Non-destructively merge array and namespace custom variables
      $script_vars = array_merge( $script_vars, array(
        'CF1' => array(
          'from'          => __( 'Please enter your name.', 'pendrell' ),
          'email'         => __( 'Enter your email.', 'pendrell' ),
          'invalidEmail'  => __( 'Enter a valid email address.', 'pendrell' ),
          'message'       => __( 'Please enter a message.', 'pendrell' ),
          'messageLength' => __( 'This message is too short.', 'pendrell' ),
          'formSent'      => __( 'Your request has been received.', 'pendrell' ),
          'formError'     => __( 'There was an error submitting your request: ', 'pendrell' )
      ) ) );
    }

    // Page load (PG8) variable setup
    if ( PENDRELL_SCRIPTS_PAGELOAD && pendrell_load_pg8() ) {

      global $wp_query;

      // What page are we on? And what is the pages limit?
      $max = $wp_query->max_num_pages;
      $paged = ( get_query_var( 'paged' ) > 1 ) ? get_query_var( 'paged' ) : 1;

      // Non-destructively merge array and namespace custom variables
      $script_vars = array_merge( $script_vars, array(
        'PG8' => array(
          'startPage' => $paged,
          'maxPages'  => $max,
          'nextLink'  => next_posts($max, false)
      ) ) );
    }

    // Currently we don't need any front-end variables without $script_vars being populated
    if ( !empty( $script_vars ) ) {

      // Pass variables to JavaScript at runtime; see: http://codex.wordpress.org/Function_Reference/wp_localize_script
      wp_localize_script( $handle, 'pendrellVars', array_merge( array(
          'ajaxUrl' => admin_url( 'admin-ajax.php' )
        ), $script_vars )
      );
    }
  } // end is_admin()

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



// == CONDITIONAL SCRIPT LOADING == //

// Test whether the current request will work with the Page Loader script (PG8)
if ( !function_exists( 'pendrell_load_pg8' ) ) : function pendrell_load_pg8() {
  if ( is_archive() || is_home() || is_search() )
    return true;
  return false;
} endif;

// Test whether the current request will work with the Picturefill script (PF)
if ( !function_exists( 'pendrell_load_pf' ) ) : function pendrell_load_pf() {
  if ( is_404() || ( is_attachment() && !wp_is_attachment_image() ) ) // Could also return false on certain post formats
    return false;
  return true;
} endif;



// == GOOGLE FONTS == //

// Hack: simplify and customize Google font loading; reference Twenty Twelve for more advanced options
if ( !function_exists( 'pendrell_get_font_url' ) ) : function pendrell_get_font_url( $fonts = '' ) {
  $font_url = '';

  // Allows us to pass a Google web font declaration as needed
  if ( empty( $fonts ) )
    $fonts = PENDRELL_GOOGLE_FONTS ? PENDRELL_GOOGLE_FONTS : 'Open+Sans:400italic,700italic,400,700'; // Default back to Open Sans

  // Encode commas and pipe characters; explanation: http://www.designfordigital.com/2014/04/07/google-fonts-bad-value-css-validate/
  $fonts = str_replace( ',', '%2C', $fonts );
  $fonts = str_replace( '|', '%7C', $fonts );

  $protocol = is_ssl() ? 'https' : 'http';

  $font_url = "$protocol://fonts.googleapis.com/css?family=" . $fonts;

  return $font_url;
} endif;
