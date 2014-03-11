<?php
// Pendrell configuration files
if ( is_readable( get_stylesheet_directory() . '/functions-config.php' ) ) {
	require_once( get_stylesheet_directory() . '/functions-config.php' );
} else {
	require_once( get_stylesheet_directory() . '/functions-config-sample.php' );
}

// Pendrell is abstracted into the /functions directory
include( get_stylesheet_directory() . '/functions/content.php' );
include( get_stylesheet_directory() . '/functions/general.php' );
//include( get_stylesheet_directory() . '/functions/feed.php' );
include( get_stylesheet_directory() . '/functions/images.php' );
include( get_stylesheet_directory() . '/functions/search.php' );
include( get_stylesheet_directory() . '/functions/various.php' );

if ( PENDRELL_PLACES )
  include( get_stylesheet_directory() . '/functions/places.php' );

if ( PENDRELL_SERIES )
  include( get_stylesheet_directory() . '/functions/series.php' );

if ( is_admin() )
  include( get_stylesheet_directory() . '/functions/admin.php' );

// If development mode is on...
include( get_stylesheet_directory() . '/functions/dev.php' );

// Theme setup; includes some image size definitions and other things that belong here in the config file
function pendrell_setup() {

  // Languages
  load_theme_textdomain( 'pendrell', get_template_directory() . '/languages' );

  // Add full post format support
  global $pendrell_post_formats;
  add_theme_support( 'post-formats', $pendrell_post_formats );

  // Adds RSS feed links to <head> for posts and comments.
  add_theme_support( 'automatic-feed-links' );

  // This theme uses a custom image size for featured images, displayed on "standard" posts.
  add_theme_support( 'post-thumbnails' );
  set_post_thumbnail_size( 624, 9999 ); // Unlimited height, soft crop

  // Add a few additional image sizes for various purposes
  add_image_size( 'thumbnail-150', 150, 150 );
  add_image_size( 'image-navigation', 150, 150, true );
  add_image_size( 'portfolio', 300, 150, true );
  add_image_size( 'third-width', 300, 9999 );
  add_image_size( 'third-width-cropped', 300, 300, true );
  add_image_size( 'half-width', 465, 9999 );
  add_image_size( 'half-width-cropped', 465, 465, true );
  add_image_size( 'full-width', 960, 9999 );
  add_image_size( 'full-width-cropped', 960, 960, true );

  // Set the medium and large size image sizes under media settings; default to our new full width image size in media uploader
  update_option( 'medium_size_w', 624 );
  update_option( 'medium_size_h', 9999 );
  update_option( 'large_size_w', 960 );
  update_option( 'large_size_h', 9999 );
  update_option( 'image_default_size', 'full-width' );

  // $content_width limits the size of the largest image size available via the media uploader
  global $content_width;
  $content_width = 624;

  // This theme styles the visual editor with editor-style.css to match the theme style
  //add_editor_style();

  // This theme uses wp_nav_menu() in two locations
  register_nav_menu( 'header', __( 'Header menu', 'pendrell' ) );
  register_nav_menu( 'footer', __( 'Footer menu', 'pendrell' ) );
}
add_action( 'after_setup_theme', 'pendrell_setup', 11 );