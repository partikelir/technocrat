<?php // ==== CONFIGURATION (DEFAULT) ==== //

// Switch for author info boxes on single posts; true/false
defined( 'PENDRELL_AUTHOR_META' )         || define( 'PENDRELL_AUTHOR_META', false );

// Baseline for the vertical rhythm; should match whatever is set in _base_config.scss; integer, required
defined( 'PENDRELL_BASELINE' )            || define( 'PENDRELL_BASELINE', 30 );

// Google web fonts to load; string false will load Open Sans as a fallback
defined( 'PENDRELL_GOOGLE_FONTS' )        || define( 'PENDRELL_GOOGLE_FONTS', 'Raleway:200,300,600' );

// Master switch for page load module
defined( 'PENDRELL_SCRIPTS_PAGELOAD' )    || define( 'PENDRELL_SCRIPTS_PAGELOAD', true );

// Master switch for Prism syntax highlighting script
defined( 'PENDRELL_SCRIPTS_PRISM' )       || define( 'PENDRELL_SCRIPTS_PRISM', false );



// ==== MODULES ==== //

// This section outlines configuration options for each module
// So, for instance, if you want to activate the "footer info" module, place this in your `functions-config.php`:
// define( 'PENDRELL_MODULE_FOOTER_INFO', true );
// Next, create and popular a global array $pendrell_footer_info according to the specifications in the example below



// == FOOTER INFO == //

// No master switch needed; this module will output some default information if you don't set anything here

// There are so many different things you might want in your footer that I've made this super flexible; edit `src/modules/footer-info.php` as you see fit
// $pendrell_footer_info = array(
//   'author'      => '', // Author to give credit to in copyright statement e.g. get_the_author_meta( 'display_name', 1 )
//   'author_url'  => '', // Author URL for the copyright statement e.g. get_the_author_meta( 'user_url', 1 )
//   'string'      => '', // Arbitrary HTML string to display in place of all this processing
//   'year'        => ''  // Earliest year for the copyright; easier than making a database call
// );



// == FULL WIDTH == //

// Optional: force full-width categories and tags; just enter an array of IDs, names, or slugs below; matches in_category() and has_tag()
// $pendrell_full_width_cats = array();
// $pendrell_full_width_tags = array();



// == ICONS == //

// Master switch for icons functionality
defined( 'PENDRELL_MODULE_ICONS' )        || define( 'PENDRELL_MODULE_ICONS', true );

// Icons file path; this needs to be set if you are working with icons
defined( 'PENDRELL_MODULE_ICONS_PATH' )   || define( 'PENDRELL_MODULE_ICONS_PATH', get_template_directory() . '/img/icons.svg' );

// Icons file URL; false to default back to in-line SVG icons
defined( 'PENDRELL_MODULE_ICONS_URL' )    || define( 'PENDRELL_MODULE_ICONS_URL', get_template_directory_uri() . '/img/icons.svg' );



// == IMAGE METADATA == //

// Display image metadata (exposure, shutter speed, etc. plus optional license and terms); true/false
defined( 'PENDRELL_MODULE_IMAGE_META' )   || define( 'PENDRELL_MODULE_IMAGE_META', false );

// Set image licenses by category
// $pendrell_image_license_cats = array();

// Set image licenses by tag
// $pendrell_image_license_tags = array(
//   'design'      => 'cc-by-nc-nd'
// , 'photography' => 'cc-by-nc'
// );

// Terms of use statement; defaults to '' if it isn't defined in `functions-config.php`
// $pendrell_image_license_terms = sprintf(
//   __( 'see <a href="%s">terms of use</a> for more info', 'pendrell' ), trailingslashit( home_url() ) . 'terms-of-use'
// );



// == POST FORMATS == //

// Master switch for post formats
defined( 'PENDRELL_MODULE_POST_FORMATS' ) || define( 'PENDRELL_MODULE_POST_FORMATS', false );



// == RESPONSIVE IMAGES == //

// Master switch for responsive images with Ubik Imagery and Picturefill; see `src/modules/responsive-images.php` for more info
defined( 'PENDRELL_MODULE_RESPONSIVE' )   || define( 'PENDRELL_MODULE_RESPONSIVE', false );

// Enable `srcset` output only when Picturefill module is active
if ( PENDRELL_MODULE_RESPONSIVE )
  define( 'UBIK_IMAGERY_SRCSET', true );



// == VIEWS == //

// Master switch for views
defined( 'PENDRELL_MODULE_VIEWS' )        || define( 'PENDRELL_MODULE_VIEWS', false );

// Set default views for different taxonomies; format is 'taxonomy' => array( 'term' => 'view' )
// $pendrell_views_map = array(
//   'category'    => array(
//     'photography' => 'gallery'
//   ),
//   'post_format' => array(
//     'post-format-gallery' => 'gallery',
//     'post-format-image'   => 'gallery'
//   ),
//   'post_tag'    => array(
//   )
// );

// Taxonomies to apply rewrite rules to (this way <website.com/category/kittens/list> works as you would expect)
// $pendrell_views_taxonomies = array( 'category', 'post_tag', 'post_format' );



// == UBIK == //

// Ubik is a collection of lightwight WordPress components; use the switches to turn these components on or off
defined( 'PENDRELL_UBIK_ADMIN' )          || define( 'PENDRELL_UBIK_ADMIN', false );
defined( 'PENDRELL_UBIK_ANALYTICS' )      || define( 'PENDRELL_UBIK_ANALYTICS', false );
defined( 'PENDRELL_UBIK_CLEANER' )        || define( 'PENDRELL_UBIK_CLEANER', true ); // Active
defined( 'PENDRELL_UBIK_COMMENTS' )       || define( 'PENDRELL_UBIK_COMMENTS', true ); // Active
defined( 'PENDRELL_UBIK_EXCLUDER' )       || define( 'PENDRELL_UBIK_EXCLUDER', false );
defined( 'PENDRELL_UBIK_FEED' )           || define( 'PENDRELL_UBIK_FEED', true ); // Active
defined( 'PENDRELL_UBIK_LINGUAL' )        || define( 'PENDRELL_UBIK_LINGUAL', false );
defined( 'PENDRELL_UBIK_MARKDOWN' )       || define( 'PENDRELL_UBIK_MARKDOWN', false );
defined( 'PENDRELL_UBIK_PLACES' )         || define( 'PENDRELL_UBIK_PLACES', false );
defined( 'PENDRELL_UBIK_POST_FORMATS' )   || define( 'PENDRELL_UBIK_POST_FORMATS', true ); // Active
defined( 'PENDRELL_UBIK_QUICK_TERMS' )    || define( 'PENDRELL_UBIK_QUICK_TERMS', false );
defined( 'PENDRELL_UBIK_RECORDPRESS' )    || define( 'PENDRELL_UBIK_RECORDPRESS', false );
defined( 'PENDRELL_UBIK_RELATED' )        || define( 'PENDRELL_UBIK_RELATED', false );
defined( 'PENDRELL_UBIK_SERIES' )         || define( 'PENDRELL_UBIK_SERIES', false );
defined( 'PENDRELL_UBIK_SEO' )            || define( 'PENDRELL_UBIK_SEO', true ); // Active

// Default configuration options for core Ubik components...
define( 'UBIK_SEARCH_FORM_REVERSE', true ); // Reverses the order of search field and submit button; *required* for this theme
