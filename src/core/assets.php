<?php // ==== ASSETS ==== //

// These functions handle asset loading: scripts, styles, and fonts

// == SCRIPTS & STYLES == //

// Enqueue front-end scripts and styles; additional ideas to consider: https://github.com/roots/roots/blob/master/lib/scripts.php
function pendrell_scripts_enqueue() {

  $script_name = '';                // Empty by default, may be populated by conditionals below
  $script_vars = array();           // An empty array that can be filled with variables to send to front-end scripts
  $script_handle = 'pendrell-core'; // A generic script handle used by WordPress



  // == JQUERY == //

  // Optionally move jQuery into the footer
  if ( PENDRELL_JQUERY_FOOTER ) {
    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', '/wp-includes/js/jquery/jquery.js', false, null, true ); // This should load version 1.11.3+
    wp_enqueue_script( 'jquery' );
  }



  // == CORE SCRIPTS == //

  // Figure out which script bundle to load based on various options set in `src/functions-config-defaults.php`
  // Note: bundles require less HTTP requests at the expense of addition caching hits when different scripts are requested
  // Be wary of adding needless conditionals as this will increase the diversity of scripts the user might encounter

  // AJAX page loading w/WP AJAX Page Loader (pg8)
  $script_vars_pg8 = '';
  if ( PENDRELL_AJAX_PAGE_LOADER && ( is_archive() || is_home() || is_search() ) ) {
    $script_name .= '-pg8';

    global $wp_query;

    // What page are we on? And what is the page limit?
    $max = $wp_query->max_num_pages;
    $paged = ( get_query_var( 'paged' ) > 1 ) ? get_query_var( 'paged' ) : 1;

    // Prepare script variables; note that these are separate from the rest of the script variables
    $script_vars_pg8 = array(
      'startPage'   => $paged,
      'maxPages'    => $max,
      'nextLink'    => next_posts( $max, false )
    );
  } // end PG8

  // Responsive images w/Picturefill (pf)
  if ( PENDRELL_RESPONSIVE_IMAGES )
    $script_name .= '-pf';

  // Syntax highlighting w/Prism (prism)
  if ( PENDRELL_SYNTAX_HIGHLIGHT )
    $script_name .= '-prism';

  // Default script name
  if ( empty( $script_name ) )
    $script_name = '-core';

  // Load theme-specific JavaScript bundles with versioning based on last modified time; http://www.ericmmartin.com/5-tips-for-using-jquery-with-wordpress/
  // These bundles are created with Gulp and each of these has the same script handle
  wp_enqueue_script( $script_handle, get_stylesheet_directory_uri() . '/js/p' . $script_name . '.js', array( 'jquery' ), filemtime( get_template_directory() . '/js/p' . $script_name . '.js' ), true );

  // Contact form (CF1) setup
  if ( is_page_template( 'page-templates/contact-form.php' ) )
    wp_enqueue_script( 'pendrell-contact-form', get_stylesheet_directory_uri() . '/js/p-contact.js', array( 'jquery' ), filemtime( get_template_directory() . '/js/p-contact.js' ), true );

  // Magnific popup
  if ( PENDRELL_MAGNIFIC && is_singular() && !is_attachment() && !has_post_format( 'image' ) )
    wp_enqueue_script( 'pendrell-magnific', get_stylesheet_directory_uri() . '/js/p-magnific.js', array( 'jquery' ), filemtime( get_template_directory() . '/js/p-magnific.js' ), true );

  // Adds JavaScript to pages with the comment form to support sites with threaded comments
  if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
    wp_enqueue_script( 'comment-reply' );



  // == SCRIPT VARIABLES == //

  // Pass variables to JavaScript at runtime; see: http://codex.wordpress.org/Function_Reference/wp_localize_script
  // @filter: pendrell_script_vars; see `modules/contact-form.php` for an example of usage
  $script_vars = apply_filters( 'pendrell_script_vars', $script_vars );
  if ( !empty( $script_vars ) )
    wp_localize_script( $script_handle, 'pendrellVars', $script_vars );

  // Script variables for WP AJAX Page Loader (these are separate from the main theme script variables due to the naming requirement)
  if ( !empty( $script_vars_pg8 ) )
    wp_localize_script( $script_handle, 'PG8Data', $script_vars_pg8 );

  // Provision svg.icon.js
  if ( UBIK_SVG_ICONS_URL )
    wp_localize_script( $script_handle, 'svgIconsUrl', UBIK_SVG_ICONS_URL );



  // == STYLES == //

  // Register and enqueue our main stylesheet with versioning based on last modified time
  wp_register_style( 'pendrell-style', get_stylesheet_uri(), $dependencies = array(), filemtime( get_template_directory() . '/style.css' ) );
  wp_enqueue_style( 'pendrell-style' );

} // pendrell_enqueue_scripts()
add_action( 'wp_enqueue_scripts', 'pendrell_scripts_enqueue' );



// Asynchronous script loading filter
function pendrell_scripts_async( $tag, $handle ) {
  if ( 'jquery' !== $handle )
    $tag = str_replace( ' src', ' defer="defer" src', $tag ); // Defer absolutely everything until after the page has loaded
  return $tag;
}
add_filter( 'script_loader_tag', 'pendrell_scripts_async', 10, 2 );



// Load additional stylesheets for the admin panel
function pendrell_admin_enqueue_scripts() {
  wp_register_style( 'pendrell-style-admin', get_template_directory_uri() . '/style-admin.css', $dependencies = array(), filemtime( get_template_directory() . '/style-admin.css' ) );
  wp_enqueue_style( 'pendrell-style-admin' );
}
add_action( 'admin_enqueue_scripts', 'pendrell_admin_enqueue_scripts' );



// Load an extra stylesheet for use with the visual editor
function pendrell_admin_editor_style() {
  add_editor_style( get_template_directory_uri() . '/style-editor.css?version=' . filemtime( get_template_directory() . '/style-editor.css' ) );
}
add_action( 'after_setup_theme', 'pendrell_admin_editor_style' );
