<?php // === PENDRELL FUNCTIONS === //
// There should be no need to edit anything in this file; check out functions-config-sample.php instead!

// Pendrell configuration files
if ( is_readable( get_stylesheet_directory() . '/functions-config.php' ) ) {
	require_once( get_stylesheet_directory() . '/functions-config.php' );
} else {
	require_once( get_stylesheet_directory() . '/functions-config-sample.php' );
}

// Definitions
define( 'PENDRELL_VERSION', "0.7" );
define( 'PENDRELL_NAME', get_bloginfo( 'name' ) );
define( 'PENDRELL_DESC', get_bloginfo( 'description' ) );
define( 'PENDRELL_HOME', get_bloginfo( 'url' ) );

// Pendrell is abstracted into the `pendrell/lib` directory
include( get_stylesheet_directory() . '/lib/archive.php' );
include( get_stylesheet_directory() . '/lib/author.php' );
include( get_stylesheet_directory() . '/lib/content.php' );
include( get_stylesheet_directory() . '/lib/general.php' );
include( get_stylesheet_directory() . '/lib/formats.php' );
include( get_stylesheet_directory() . '/lib/media.php' );
include( get_stylesheet_directory() . '/lib/navigation.php' );
include( get_stylesheet_directory() . '/lib/various.php' );

if ( is_admin() )
  include( get_stylesheet_directory() . '/lib/admin.php' );

// Still in development
include( get_stylesheet_directory() . '/lib/dev.php' );

// Theme setup; includes some image size definitions and other things that belong here in the config file
function pendrell_setup() {

  // Language loading
  load_theme_textdomain( 'pendrell', get_template_directory() . '/languages' );

  // Add post format support
  add_theme_support( 'post-formats', array( 'aside', 'audio', 'gallery', 'image', 'link', 'quote', 'status' ) );

  // Adds RSS feed links to <head> for posts and comments.
  add_theme_support( 'automatic-feed-links' );

  // $content_width limits the size of the largest image size available via the media uploader
  // It should be set once and left alone apart from that; don't do anything fancy with it
  global $content_width;
  $content_width = 960;

  // Width of the main content column; should correspond to equivalent values in the stylesheet
  // This variable is mainly used here in functions.php; it should match the variable defined in _base.scss
  $main_width = 624;

  // This theme uses a custom image size for featured images; it isn't really a "thumbnail"
  add_theme_support( 'post-thumbnails' );
  set_post_thumbnail_size( $main_width, 9999 );

  // Add a few additional image sizes for various other purposes
  add_image_size( 'medium-300', 300, 9999 );
  add_image_size( 'medium-300-cropped', 300, 300, true );
  add_image_size( 'medium-465', $content_width/2, 9999 );
  add_image_size( 'medium-465-cropped', $content_width/2, $content_width/2, true );
  add_image_size( 'medium-624-cropped', $main_width, $main_width, true );
  add_image_size( 'large-960-cropped', $content_width, $content_width, true );

  // Forcing medium and large sizes to match $content_width and $site_width
  update_option( 'medium_size_w', $main_width );
  update_option( 'medium_size_h', 9999 );
  update_option( 'large_size_w', $content_width );
  update_option( 'large_size_h', 9999 );

  // Set the medium and large size image sizes under media settings; default to our new full width image size in media uploader
  update_option( 'image_default_size', 'medium' );

  // This theme styles the visual editor with editor-style.css to match the theme style; @TODO: check this out sometime
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
