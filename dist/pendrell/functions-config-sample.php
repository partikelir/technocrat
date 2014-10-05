<?php // ==== CONFIGURATION ==== //

// Switch for author info boxes on single posts; true/false
define( 'PENDRELL_AUTHOR_META', false );

// Baseline for the vertical rhythm; should match whatever is set in _base_config.scss; integer
define( 'PENDRELL_BASELINE', 30 );

// Display a copyright statement in the footer; true/false
define( 'PENDRELL_COPYRIGHT', true );

// Author to give credit to in copyright statement; false to disable
define( 'PENDRELL_COPYRIGHT_AUTHOR', get_the_author_meta( 'display_name', 1 ) );

// Author URL for the copyright statement; false to disable
define( 'PENDRELL_COPYRIGHT_AUTHOR_URL', get_the_author_meta( 'user_url', 1 ) );

// Copyright statement; fill this with a string to display in the footer instead of the default; false to disable
define( 'PENDRELL_COPYRIGHT_STRING', false );

// Display a copyright yearEarliest year for the copyright; easier than making a database call; integer or false to disable
define( 'PENDRELL_COPYRIGHT_YEAR', 2014 );

// Google web fonts to load; string false will load Open Sans as a fallback
define( 'PENDRELL_GOOGLE_FONTS', 'Raleway:200,300,600' );

// Image licensing specific to my projects; edit `lib/image-metadata.php` to customize; true/false
define( 'PENDRELL_IMAGE_LICENSE', false );

// Terms of use for images; this should be a full URL to your terms of use; string or false to disable
define( 'PENDRELL_IMAGE_LICENSE_TERMS', false );

// Master switch for full-width styling
define( 'PENDRELL_MODULE_FULL_WIDTH', true );

// Master switch for post formats
define( 'PENDRELL_MODULE_POST_FORMATS', true );

// Master switch for full-width styling
define( 'PENDRELL_MODULE_VIEWS', true );

// Master switch for Ajaxify page loading script
define( 'PENDRELL_SCRIPTS_AJAXIFY', false );

// Master switch for Prism code-highlighting script
define( 'PENDRELL_SCRIPTS_PRISM', false );

// Force full-width categories and tags; just enter an array of IDs, names, or slugs below; matches in_category() and has_tag()
if ( PENDRELL_MODULE_FULL_WIDTH ) {
  $pendrell_full_width_cats = array();
  $pendrell_full_width_tags = array();
}

// Set default views for different categories and tag archives
if ( PENDRELL_MODULE_VIEWS ) {
  $pendrell_default_view_cats = array();
  $pendrell_default_view_tags = array();
}
