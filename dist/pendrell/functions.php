<?php // ==== FUNCTIONS ==== //

// == CONFIGURATION == //

// Load the configuration file for this theme; all options are set here
if ( is_readable( trailingslashit( get_stylesheet_directory() ) . 'functions-config.php' ) ) {
  require_once( trailingslashit( get_stylesheet_directory() ) . 'functions-config.php' );
} else {
  require_once( trailingslashit( get_stylesheet_directory() ) . 'functions-config-sample.php' );
}

// There should be no need to edit these
define( 'PENDRELL_NAME', get_bloginfo( 'name' ) );
define( 'PENDRELL_DESCRIPTION', get_bloginfo( 'description' ) );
define( 'PENDRELL_HOME', home_url() );

// Basic theme info; don't edit these either
define( 'PENDRELL_THEME_NAME', 'Pendrell' );
define( 'PENDRELL_THEME_URL', 'http://github.com/synapticism/pendrell' );
define( 'PENDRELL_THEME_VERSION', '0.11' );



// == MODULE LOADING == //

// Pendrell is abstracted into the `pendrell/lib` directory
if ( is_admin() )
  require_once( trailingslashit( get_stylesheet_directory() ) . 'lib/admin.php' );
require_once( trailingslashit( get_stylesheet_directory() ) . 'lib/ajax.php' );
require_once( trailingslashit( get_stylesheet_directory() ) . 'lib/archive.php' );
require_once( trailingslashit( get_stylesheet_directory() ) . 'lib/author.php' );
require_once( trailingslashit( get_stylesheet_directory() ) . 'lib/comments.php' );
require_once( trailingslashit( get_stylesheet_directory() ) . 'lib/content.php' );
require_once( trailingslashit( get_stylesheet_directory() ) . 'lib/feed.php' );
require_once( trailingslashit( get_stylesheet_directory() ) . 'lib/general.php' );
require_once( trailingslashit( get_stylesheet_directory() ) . 'lib/image.php' );
require_once( trailingslashit( get_stylesheet_directory() ) . 'lib/image-metadata.php' );
require_once( trailingslashit( get_stylesheet_directory() ) . 'lib/navigation.php' );
require_once( trailingslashit( get_stylesheet_directory() ) . 'lib/terms.php' );
require_once( trailingslashit( get_stylesheet_directory() ) . 'lib/various.php' );

// Optional modules set in `functions-config.php`
if ( PENDRELL_MODULE_FULL_WIDTH )
  require_once( trailingslashit( get_stylesheet_directory() ) . 'lib/full-width.php' );
if ( PENDRELL_MODULE_POST_FORMATS )
  require_once( trailingslashit( get_stylesheet_directory() ) . 'lib/post-formats.php' );
if ( PENDRELL_MODULE_VIEWS )
  require_once( trailingslashit( get_stylesheet_directory() ) . 'lib/views.php' );

// Local development mode; relies on WP-Config-X or some similar system
if ( WP_LOCAL_DEV )
  require_once( trailingslashit( get_stylesheet_directory() ) . 'lib/development.php' );


// == SETUP == //

// Includes some image size definitions and other things that belong here in the config file
function pendrell_setup() {

  // Language loading
  load_theme_textdomain( 'pendrell', trailingslashit( get_template_directory() ) . 'languages' );

  // Conditionally add post format support
  if ( PENDRELL_MODULE_POST_FORMATS )
    add_theme_support( 'post-formats', array( 'aside', 'gallery', 'image', 'link', 'quote', 'status' ) );

  // HTML5 theme options
  add_theme_support( 'html5', array(
    // 'caption', // Disabled; the output causes feeds to invalidate!
    'comment-form',
    'comment-list',
    'gallery',
    'search-form'
  ) );

  // Adds RSS feed links to <head> for posts and comments.
  add_theme_support( 'automatic-feed-links' );

  // $content_width limits the size of the largest image size available via the media uploader
  // It should be set once and left alone apart from that; don't do anything fancy with it; it is part of WordPress core
  global $content_width;
  if ( !isset( $content_width ) )
    $content_width = 960;

  // Width of the main content column; should correspond to equivalent values in the stylesheet; NOT a WordPress core thing
  // This variable is mainly used here in functions.php; it should match the variable defined in _base.scss
  $main_width = 624;

  // This theme uses a custom image size for featured images; it isn't really a "thumbnail" and it actually differs from the thumbnail image size
  add_theme_support( 'post-thumbnails' );
  set_post_thumbnail_size( $main_width, $main_width );

  // Add a few additional image sizes for various other purposes
  add_image_size( 'third', $content_width/3, 9999 );
  add_image_size( 'third-square', $content_width/3, $content_width/3, true );
  add_image_size( 'half', $content_width/2, 9999 );
  add_image_size( 'half-square', $content_width/2, $content_width/2, true );
  add_image_size( 'medium-square', $main_width, $main_width, true );
  add_image_size( 'large-square', $content_width, $content_width, true );

  // Forcing medium and large sizes to match $content_width and $site_width; explicitly setting thumbnail image size
  update_option( 'thumbnail_size_w', 150 );
  update_option( 'thumbnail_size_h', 150 );
  update_option( 'thumbnail_crop', 1 );
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



// Sidebar declarations
function pendrell_widgets_init() {
  register_sidebar( array(
    'name'          => __( 'Main sidebar', 'pendrell' ),
    'id'            => 'sidebar-main',
    'description'   => __( 'Appears to the right side of most posts and pages.', 'pendrell' ),
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget'  => '</aside>',
    'before_title'  => '<h2 class="widget-title">',
    'after_title'   => '</h2>'
  ) );
}
add_action( 'widgets_init', 'pendrell_widgets_init' );