<?php // ==== FUNCTIONS ==== //

// == CONFIGURATION == //

$path = trailingslashit( get_stylesheet_directory() );

// Load the configuration file for this theme; all options are set here
if ( is_readable( $path . 'functions-config.php' ) )
  require_once( $path . 'functions-config.php' );

// Load configuration defaults; anything not set in the custom configuration (above) will be set here
require_once( $path . 'functions-config-defaults.php' );

// Load the modules file; everything related to Ubik is set here
if ( is_readable( $path . 'functions-modules.php' ) )
  require_once( $path . 'functions-modules.php' );



// == LOADER == //

// Pendrell core functions are abstracted into the `pendrell/lib` directory
require_once( $path . 'lib/assets.php' );
require_once( $path . 'lib/author.php' );
require_once( $path . 'lib/comments.php' );
require_once( $path . 'lib/contact-form.php' );
require_once( $path . 'lib/content.php' );
require_once( $path . 'lib/icons.php' );
require_once( $path . 'lib/images.php' );
require_once( $path . 'lib/main.php' );
require_once( $path . 'lib/navigation.php' );
require_once( $path . 'lib/post-formats.php' );
require_once( $path . 'lib/site.php' );



// == SETUP == //

// Includes some image size definitions and other things that belong here in the config file
// @filter: pendrell_content_width
// @filter: pendrell_medium_width
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

  // Adds RSS feed links to <head> for posts and comments
  add_theme_support( 'automatic-feed-links' );



  // == LAYOUT == //

  // $content_width limits the size of the largest image size available via the media uploader
  // $medium_width is a handy shortcut, nothing more
  global $content_width, $medium_width;
  $content_width = (int) apply_filters( 'pendrell_content_width', 720 );
  $medium_width = (int) apply_filters( 'pendrell_medium_width', 540 );



  // == IMAGES == //

  // Post thumbnails"are actually featured images and don't have anything to do with the actual thumbnail size
  add_theme_support( 'post-thumbnails' );
  set_post_thumbnail_size( $medium_width, $medium_width );

  // Built-in image sizes
  update_option( 'image_default_size', 'medium' );
  update_option( 'thumbnail_size_w', 150 );
  update_option( 'thumbnail_size_h', 150 );
  update_option( 'thumbnail_crop', 1 );
  update_option( 'medium_size_w', $medium_width );
  update_option( 'medium_size_h', 9999 );
  update_option( 'large_size_w', $content_width );
  update_option( 'large_size_h', 9999 );
  //update_option( 'medium_large_size_w', 800 ); // New size with WP 4.4; not needed in this theme
  //update_option( 'medium_large_size_h', 9999 );

  // Additional hard-cropped square versions of both medium and large image sizes
  add_image_size( 'medium-square', $medium_width, $medium_width, true );
  add_image_size( 'large-square', $content_width, $content_width, true );

  // Add fractional image sizes; this used to be in Ubik but applies mostly to this theme
  $margin = PENDRELL_BASELINE;

  $quarter_width  = (int) ceil( ( $content_width - $margin * 3 ) / 4 );
  $third_width    = (int) ceil( ( $content_width - $margin * 2 ) / 3 );
  $half_width     = (int) ceil( ( $content_width - $margin * 1 ) / 2 );

  add_image_size( 'quarter', $quarter_width, 9999 );
  add_image_size( 'quarter-square', $quarter_width, $quarter_width, true );
  add_image_size( 'third', $third_width, 9999 );
  add_image_size( 'third-square', $third_width, $third_width, true );
  add_image_size( 'half', $half_width, 9999 );
  add_image_size( 'half-square', $half_width, $half_width, true );



  // == MENUS == //

  // Register header and footer menus
  register_nav_menu( 'header', __( 'Header menu', 'pendrell' ) );
  register_nav_menu( 'footer', __( 'Footer menu', 'pendrell' ) );
}
add_action( 'after_setup_theme', 'pendrell_setup', 11 );



// Sidebar declarations
function pendrell_widgets_init() {
  register_sidebar( array(
    'id'            => 'sidebar-1',
    'name'          => __( 'Sidebar 1', 'pendrell' ),
    'description'   => __( 'The first sidebar.', 'pendrell' ),
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h2>',
    'after_title'   => '</h2>'
  ) );
  register_sidebar( array(
    'id'            => 'sidebar-2',
    'name'          => __( 'Sidebar 2', 'pendrell' ),
    'description'   => __( 'The second sidebar.', 'pendrell' ),
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h2>',
    'after_title'   => '</h2>'
  ) );
}
add_action( 'widgets_init', 'pendrell_widgets_init' );
add_filter( 'widget_text', 'do_shortcode' ); // Add shortcodes to sidebar widgets



// A wrapper for `get_template_part`, a core WordPress function that ships without a filter
// @filter: pendrell_template_part
function pendrell_template_part( $slug = 'content', $name = null ) {
  if ( is_search() )
    $name = 'excerpt';
  return get_template_part( $slug, apply_filters( 'pendrell_template_part', $name ) );
}
