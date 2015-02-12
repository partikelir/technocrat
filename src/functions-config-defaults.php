<?php // ==== CONFIGURATION (DEFAULT) ==== //

// Development mode
if ( WP_LOCAL_DEV ) {
  defined( 'PENDRELL_POST_FORMATS' )        || define( 'PENDRELL_POST_FORMATS', true );
  defined( 'PENDRELL_SCRIPTS_PAGELOAD' )    || define( 'PENDRELL_SCRIPTS_PAGELOAD', true );
  defined( 'PENDRELL_SCRIPTS_PICTUREFILL' ) || define( 'PENDRELL_SCRIPTS_PICTUREFILL', true );
  defined( 'PENDRELL_SCRIPTS_PRISM' )       || define( 'PENDRELL_SCRIPTS_PRISM', true );
}

// Switch for author info boxes on single posts; true/false
defined( 'PENDRELL_AUTHOR_META' )         || define( 'PENDRELL_AUTHOR_META', false );

// Baseline for the vertical rhythm; should match whatever is set in _base_config.scss; integer, required
defined( 'PENDRELL_BASELINE' )            || define( 'PENDRELL_BASELINE', 30 );

// Google web fonts to load; string false will load Open Sans as a fallback
defined( 'PENDRELL_GOOGLE_FONTS' )        || define( 'PENDRELL_GOOGLE_FONTS', 'Raleway:200,300,600' );

// Master switch for post formats
defined( 'PENDRELL_POST_FORMATS' )        || define( 'PENDRELL_POST_FORMATS', false );

// Master switch for WP AJAX Page Load script
defined( 'PENDRELL_SCRIPTS_PAGELOAD' )    || define( 'PENDRELL_SCRIPTS_PAGELOAD', true );

// Master switch for Picturefill script
defined( 'PENDRELL_SCRIPTS_PICTUREFILL' ) || define( 'PENDRELL_SCRIPTS_PICTUREFILL', true );

// Master switch for Prism syntax highlighting script
defined( 'PENDRELL_SCRIPTS_PRISM' )       || define( 'PENDRELL_SCRIPTS_PRISM', false );



// ==== MODULES ==== //

// This section outlines configuration options for each module
// So, for instance, if you want to activate the "footer info" module, place this in your `functions-config.php`:
// define( 'PENDRELL_MODULE_FOOTER_INFO', true );
// Next, create and popular a global array $pendrell_footer_info according to the specifications in the example below



// == FULL WIDTH == //

// Optional: force full-width categories and tags; just enter an array of IDs, names, or slugs below; matches in_category() and has_tag()
// $pendrell_full_width_cats = array();
// $pendrell_full_width_tags = array();
