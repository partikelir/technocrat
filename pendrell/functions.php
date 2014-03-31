<?php // === PENDRELL FUNCTIONS === //
// There should be no need to edit anything in this file; check out functions-config-sample.php instead!

// Pendrell configuration files
if ( is_readable( get_stylesheet_directory() . '/functions-config.php' ) ) {
	require_once( get_stylesheet_directory() . '/functions-config.php' );
} else {
	require_once( get_stylesheet_directory() . '/functions-config-sample.php' );
}

// Definitions
define( 'PENDRELL_VERSION', 0.6 );
define( 'PENDRELL_NAME', get_bloginfo( 'name' ) );
define( 'PENDRELL_DESC', get_bloginfo( 'description' ) );
define( 'PENDRELL_HOME', get_bloginfo( 'url' ) );

// Pendrell is abstracted into the /functions directory
include( get_stylesheet_directory() . '/functions/archive.php' );
include( get_stylesheet_directory() . '/functions/author.php' );
include( get_stylesheet_directory() . '/functions/content.php' );
include( get_stylesheet_directory() . '/functions/general.php' );
include( get_stylesheet_directory() . '/functions/formats.php' );
include( get_stylesheet_directory() . '/functions/media.php' );
include( get_stylesheet_directory() . '/functions/navigation.php' );
include( get_stylesheet_directory() . '/functions/various.php' );

// A simple adapter for the Ubik toolkit
include( get_stylesheet_directory() . '/functions/ubik.php' );

if ( is_admin() )
  include( get_stylesheet_directory() . '/functions/admin.php' );

// Still in development
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

  // $content_width limits the size of the largest image size available via the media uploader
  global $content_width, $site_width;
  $content_width = 624;
  $site_width = 960;

  // This theme uses a custom image size for featured images; it isn't really a "thumbnail"
  add_theme_support( 'post-thumbnails' );
  set_post_thumbnail_size( $content_width, 9999 );

  // Add a few additional image sizes for various other purposes
  add_image_size( 'medium-300', 300, 9999 );
  add_image_size( 'medium-300-cropped', 300, 300, true );
  add_image_size( 'medium-465', $site_width/2, 9999 );
  add_image_size( 'medium-465-cropped', $site_width/2, $site_width/2, true );
  add_image_size( 'medium-624-cropped', $content_width, $content_width, true );
  add_image_size( 'large-960-cropped', $site_width, $site_width, true );

  // Forcing medium and large sizes to match $content_width and $site_width
  update_option( 'medium_size_w', $content_width );
  update_option( 'medium_size_h', 9999 );
  update_option( 'large_size_w', $site_width );
  update_option( 'large_size_h', 9999 );

  // Old Pendrell sizes
  add_image_size( 'third-width', 300, 9999 );
  add_image_size( 'third-width-cropped', 300, 300, true );
  add_image_size( 'half-width', 465, 9999 );
  add_image_size( 'half-width-cropped', 465, 465, true );
  add_image_size( 'full-width', 960, 9999 );
  add_image_size( 'full-width-cropped', 960, 960, true );

  // Set the medium and large size image sizes under media settings; default to our new full width image size in media uploader
  update_option( 'image_default_size', 'medium' );

  // This theme styles the visual editor with editor-style.css to match the theme style
  //add_editor_style();

  // Register header and footer menus
  register_nav_menu( 'header', __( 'Header menu', 'pendrell' ) );
  register_nav_menu( 'footer', __( 'Footer menu', 'pendrell' ) );
}
add_action( 'after_setup_theme', 'pendrell_setup', 11 );



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
