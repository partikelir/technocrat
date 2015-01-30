<?php // ==== FUNCTIONS ==== //

// == CONFIGURATION == //

// Load the configuration file for this theme; all options are set here
if ( is_readable( trailingslashit( get_stylesheet_directory() ) . 'functions-config.php' ) )
  require_once( trailingslashit( get_stylesheet_directory() ) . 'functions-config.php' );

// Load configuration defaults; anything not set in the custom configuration (above) will be set here
require_once( trailingslashit( get_stylesheet_directory() ) . 'functions-config-defaults.php' );

// Load the Ubik adapter file; everything related to Ubik is set here
if ( is_readable( trailingslashit( get_stylesheet_directory() ) . 'functions-ubik.php' ) )
  require_once( trailingslashit( get_stylesheet_directory() ) . 'functions-ubik.php' );



// == CONSTANTS == //

// There should be no need to edit any of these
define( 'PENDRELL_NAME', get_bloginfo( 'name' ) );
define( 'PENDRELL_DESCRIPTION', get_bloginfo( 'description' ) );
define( 'PENDRELL_HOME', home_url() );
define( 'PENDRELL_THEME_NAME', 'Pendrell' );
define( 'PENDRELL_THEME_URL', 'http://github.com/synapticism/pendrell' );
define( 'PENDRELL_THEME_VERSION', '0.18.0' );



// == CORE == //

// Pendrell is abstracted into the `pendrell/core` directory
if ( is_admin() )
  require_once( trailingslashit( get_stylesheet_directory() ) . 'core/admin.php' );
require_once( trailingslashit( get_stylesheet_directory() ) . 'core/archive.php' );
require_once( trailingslashit( get_stylesheet_directory() ) . 'core/assets.php' );
require_once( trailingslashit( get_stylesheet_directory() ) . 'core/author.php' );
require_once( trailingslashit( get_stylesheet_directory() ) . 'core/comments.php' );
require_once( trailingslashit( get_stylesheet_directory() ) . 'core/content.php' );
require_once( trailingslashit( get_stylesheet_directory() ) . 'core/full-width.php' );
require_once( trailingslashit( get_stylesheet_directory() ) . 'core/image.php' );
require_once( trailingslashit( get_stylesheet_directory() ) . 'core/navigation.php' );
require_once( trailingslashit( get_stylesheet_directory() ) . 'core/templates.php' );



// == MODULES == //

// Modules provide non-standard features that don't generally ship with WordPress

// Required *
require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/contact-form.php' );
require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/footer-info.php' );

// Optional
if ( PENDRELL_MODULE_ICONS )
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/icons.php' );
if ( PENDRELL_MODULE_IMAGE_META )
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/image-metadata.php' );
if ( PENDRELL_MODULE_POST_FORMATS )
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/post-formats.php' );
if ( PENDRELL_MODULE_RESPONSIVE )
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/responsive-images.php' );
if ( PENDRELL_MODULE_VIEWS ) {
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/views.php' );
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/view-posts-shortcode.php' );
}

// Fallback functionality is loaded last
require_once( trailingslashit( get_stylesheet_directory() ) . 'core/fallback.php' );



// == SETUP == //

// Includes some image size definitions and other things that belong here in the config file
function pendrell_setup() {

  // == THEME SUPPORT == //

  // Language loading
  load_theme_textdomain( 'pendrell', trailingslashit( get_template_directory() ) . 'languages' );

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

  // No need for this as long as we're managing our own titles: http://codex.wordpress.org/Function_Reference/add_theme_support#Title_Tag
  // In WP 4.1 this doesn't actually do anything; it merely enables forward-compatibility with future title generating functions in core
  // Additional reference: https://make.wordpress.org/core/2014/10/29/title-tags-in-4-1/
  //add_theme_support( 'title-tag' );

  // Conditionally add post format support
  if ( PENDRELL_MODULE_POST_FORMATS )
    add_theme_support( 'post-formats', array( 'aside', 'gallery', 'image', 'link', 'quote', 'status' ) );



  // == LAYOUT == //

  // $content_width limits the size of the largest image size available via the media uploader
  // It should be set once and left alone apart from that; don't do anything fancy with it; it is part of WordPress core
  global $content_width, $main_width;
  if ( !isset( $content_width ) || !is_int( $content_width ) )
    $content_width = (int) 960;

  // Width of the main content column; should correspond to equivalent values in the stylesheet; NOT a WordPress core thing
  // This variable is mainly used here in functions.php; it should match the variable defined in _base.scss
  $main_width = round( $content_width * 0.65 ); // = 624



  // == IMAGES == //

  // This theme uses a custom image size for featured images; it isn't really a "thumbnail" and it actually differs from the thumbnail image size
  add_theme_support( 'post-thumbnails' );
  set_post_thumbnail_size( $main_width, $main_width );

  // Add a few additional image sizes for various other purposes
  if ( function_exists( 'ubik_imagery_add_fractional_sizes' ) )
    ubik_imagery_add_fractional_sizes( $margin = PENDRELL_BASELINE );

  // Forcing medium and large sizes to match $content_width and $main_width; explicitly setting thumbnail image size
  update_option( 'thumbnail_size_w', 150 );
  update_option( 'thumbnail_size_h', 150 );
  update_option( 'thumbnail_crop', 1 );
  update_option( 'medium_size_w', $main_width );
  update_option( 'medium_size_h', 9999 );
  update_option( 'large_size_w', $content_width );
  update_option( 'large_size_h', 9999 );

  // Additional hard-cropped square versions of both medium and large image sizes
  add_image_size( 'medium-square', $main_width, $main_width, true );
  add_image_size( 'large-square', $content_width, $content_width, true );

  // Set the medium and large size image sizes under media settings; default to our new full width image size in media uploader
  update_option( 'image_default_size', 'medium' );



  // == MENUS == //

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
    'before_title'  => '<h2>',
    'after_title'   => '</h2>'
  ) );
}
add_action( 'widgets_init', 'pendrell_widgets_init' );
