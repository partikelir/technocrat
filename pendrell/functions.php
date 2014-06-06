<?php // ==== FUNCTIONS ==== //

// == CONFIGURATION == //

// Switch for author info boxes on single posts; true/false
define( 'PENDRELL_AUTHOR_META', false );

// Baseline for the vertical rhythm; should match whatever is set in _base_config.scss; integer
define( 'PENDRELL_BASELINE', 32 );

// Display a copyright statement in the footer; true/false
define( 'PENDRELL_COPYRIGHT', true );

// Author to give credit to in copyright statement; false to disable
define( 'PENDRELL_COPYRIGHT_AUTHOR', get_the_author_meta( 'display_name', 1 ) );

// Author URL for the copyright statement; false to disable
define( 'PENDRELL_COPYRIGHT_AUTHOR_URL', get_the_author_meta( 'user_url', 1 ) );

// Copyright statement; fill this with a string to display in the footer instead of the default; false to disable
define( 'PENDRELL_COPYRIGHT_STRING', false );

// Display a copyright yearEarliest year for the copyright; easier than making a database call; integer or false to disable
define( 'PENDRELL_COPYRIGHT_YEAR', 2011 );

// Google web fonts to load; string false will load Open Sans as a fallback
define( 'PENDRELL_GOOGLE_FONTS', 'Raleway:200,400,600|Source+Sans+Pro:300italic,300,400italic,400,700' );

// Google web fonts custom subset support; string or false to disable
// Designed with Pinyin support in mind using this string: '&amp;text=%C4%81%C3%A1%C7%8E%C3%A0%C5%8D%C3%B3%C7%92%C3%B2%C4%93%C3%A9%C4%9B%C3%A8%C4%AB%C3%AD%C7%90%C3%AC%C5%AB%C3%BA%C7%94%C3%B9%C7%96%C7%98%C7%9A%C7%9C%C3%BC%C4%80%C3%81%C7%8D%C3%80%C5%8C%C3%93%C7%91%C3%92%C4%92%C3%89%C4%9A%C3%88'
define( 'PENDRELL_GOOGLE_FONTS_SUBSET', 'Source+Sans+Pro:300,300italic,400,400italic,700&amp;text=%C4%81%C3%A1%C7%8E%C3%A0%C5%8D%C3%B3%C7%92%C3%B2%C4%93%C3%A9%C4%9B%C3%A8%C4%AB%C3%AD%C7%90%C3%AC%C5%AB%C3%BA%C7%94%C3%B9%C7%96%C7%98%C7%9A%C7%9C%C3%BC%C4%80%C3%81%C7%8D%C3%80%C5%8C%C3%93%C7%91%C3%92%C4%92%C3%89%C4%9A%C3%88' );

// Experimental gallery shortcode override; false to disable
define( 'PENDRELL_MEDIA_GALLERY', false );

// There should be no need to edit these
define( 'PENDRELL_NAME', get_bloginfo( 'name' ) );
define( 'PENDRELL_DESCRIPTION', get_bloginfo( 'description' ) );
define( 'PENDRELL_HOME', home_url() );

// Basic theme info; don't edit these either
define( 'PENDRELL_THEME_NAME', 'Pendrell' );
define( 'PENDRELL_THEME_URL', 'http://github.com/synapticism/pendrell' );
define( 'PENDRELL_THEME_VERSION', '0.9' );



// == MODULE LOADING == //

// Pendrell is abstracted into the `pendrell/lib` directory
include( get_stylesheet_directory() . '/lib/archive.php' );
include( get_stylesheet_directory() . '/lib/author.php' );
include( get_stylesheet_directory() . '/lib/content.php' );
include( get_stylesheet_directory() . '/lib/feed.php' );
include( get_stylesheet_directory() . '/lib/formats.php' );
include( get_stylesheet_directory() . '/lib/full-width.php' );
include( get_stylesheet_directory() . '/lib/general.php' );
include( get_stylesheet_directory() . '/lib/image-metadata.php' );
include( get_stylesheet_directory() . '/lib/media.php' );
include( get_stylesheet_directory() . '/lib/navigation.php' );
include( get_stylesheet_directory() . '/lib/search.php' );
include( get_stylesheet_directory() . '/lib/various.php' );

if ( is_admin() )
  include( get_stylesheet_directory() . '/lib/admin.php' );



// == SETUP == //

// Includes some image size definitions and other things that belong here in the config file
function pendrell_setup() {

  // Language loading
  load_theme_textdomain( 'pendrell', get_template_directory() . '/languages' );

  // Add post format support
  add_theme_support( 'post-formats', array( 'aside', 'audio', 'gallery', 'image', 'link', 'quote', 'status' ) );

  // HTML5 captions and gallery shortcodes; both still kind of ugly though
  add_theme_support( 'html5', array( 'comment-form', 'comment-list', 'gallery', 'search-form' ) ); // Disabled: 'caption'; the output causes feeds to invalidate

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

  // This theme uses a custom image size for featured images; it isn't really a "thumbnail"
  add_theme_support( 'post-thumbnails' );
  set_post_thumbnail_size( $main_width, 9999 );

  // Add a few additional image sizes for various other purposes
  add_image_size( 'medium-third', $content_width/3, 9999 );
  add_image_size( 'medium-third-cropped', $content_width/3, $content_width/3, true );
  add_image_size( 'medium-half', $content_width/2, 9999 );
  add_image_size( 'medium-half-cropped', $content_width/2, $content_width/2, true );
  add_image_size( 'medium-cropped', $main_width, $main_width, true );
  add_image_size( 'large-cropped', $content_width, $content_width, true );

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
