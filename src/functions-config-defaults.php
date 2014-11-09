<?php // ==== CONFIGURATION DEFAULTS ==== //

// Switch for author info boxes on single posts; true/false
defined( 'PENDRELL_AUTHOR_META' )         || define( 'PENDRELL_AUTHOR_META', false );

// Baseline for the vertical rhythm; should match whatever is set in _base_config.scss; integer
defined( 'PENDRELL_BASELINE' )            || define( 'PENDRELL_BASELINE', 30 );

// Google web fonts to load; string false will load Open Sans as a fallback
defined( 'PENDRELL_GOOGLE_FONTS' )        || define( 'PENDRELL_GOOGLE_FONTS', 'Raleway:200,300,600' );

// Master switch for Ajaxinate page loading script
defined( 'PENDRELL_SCRIPTS_AJAXINATE' )   || define( 'PENDRELL_SCRIPTS_AJAXINATE', false );

// Master switch for page load module
defined( 'PENDRELL_SCRIPTS_PAGELOAD' )    || define( 'PENDRELL_SCRIPTS_PAGELOAD', true );

// Master switch for Prism syntax highlighting script
defined( 'PENDRELL_SCRIPTS_PRISM' )       || define( 'PENDRELL_SCRIPTS_PRISM', false );



// ==== MODULES ==== //

// Edit below to active and configure any of Pendrell's optional modules

// == FOOTER INFO == //

// Master switch for footer info
defined( 'PENDRELL_MODULE_FOOTER_INFO' )  || define( 'PENDRELL_MODULE_FOOTER_INFO', false );

// Set some variables used in the footer info module
if ( PENDRELL_MODULE_FOOTER_INFO ) {

  // There are so many different things you might want in your footer that I've made this super flexible; edit `src/modules/footer-info.php` as you see fit
  $pendrell_footer_info = array(
    'author' => '', // Author to give credit to in copyright statement e.g. get_the_author_meta( 'display_name', 1 )
    'author_url' => '', // Author URL for the copyright statement e.g. get_the_author_meta( 'user_url', 1 )
    'string' => '', // Arbitrary HTML string to display in place of all this processing
    'year' => '' // Earliest year for the copyright; easier than making a database call
  );
}



// == FULL WIDTH == //

// Master switch for full-width styling
defined( 'PENDRELL_MODULE_FULL_WIDTH' )   || define( 'PENDRELL_MODULE_FULL_WIDTH', false );

// Force full-width categories and tags; just enter an array of IDs, names, or slugs below; matches in_category() and has_tag()
if ( PENDRELL_MODULE_FULL_WIDTH ) {
  $pendrell_full_width_cats = array();
  $pendrell_full_width_tags = array( 'design', 'photography' );
}



// == IMAGE METADATA == //

// Display image metadata (exposure, shutter speed, etc. plus optional license and terms); true/false
defined( 'PENDRELL_MODULE_IMAGE_META' )   || define( 'PENDRELL_MODULE_IMAGE_META', false );

// Terms of use statement; appended to the license statement
if ( PENDRELL_MODULE_IMAGE_META ) {

  // Set image licenses by category
  $pendrell_image_license_cats = array();

  // Set image licenses by tag
  $pendrell_image_license_tags = array(
    'design'      => 'cc-by-nc-nd'
  , 'photography' => 'cc-by-nc'
  );

  // An optional terms of use statement
  $pendrell_image_license_terms = sprintf(
    __( 'see <a href="%s">terms of use</a> for more info', 'pendrell' ), trailingslashit( home_url() ) . 'terms-of-use'
  );
}



// == POST FORMATS == //

// Master switch for post formats
defined( 'PENDRELL_MODULE_POST_FORMATS' ) || define( 'PENDRELL_MODULE_POST_FORMATS', false );



// == VIEWS == //

// Master switch for views
defined( 'PENDRELL_MODULE_VIEWS' )        || define( 'PENDRELL_MODULE_VIEWS', false );

// Set default views for different categories and tag archives
if ( PENDRELL_MODULE_VIEWS ) {

  // Set default views for different taxonomies; format is 'taxonomy' => array( 'term' => 'view' )
  $pendrell_views_map = array(
    'category'    => array(
      'photography' => 'gallery'
    ),
    'post_format' => array(
      'post-format-gallery' => 'gallery',
      'post-format-image'   => 'gallery'
    ),
    'post_tag'    => array(
    )
  );

  // Taxonomies to apply rewrite rules to (this way <website.com/category/kittens/list> works as you would expect)
  $pendrell_views_taxonomies = array( 'category', 'post_tag', 'post_format' );
}