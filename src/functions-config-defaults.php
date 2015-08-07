<?php // ==== CONFIGURATION (DEFAULT) ==== //

// Development mode
if ( WP_LOCAL_DEV === true ) {
  define( 'PENDRELL_POST_FORMATS', true );
  define( 'PENDRELL_RESPONSIVE_IMAGES', true );
  define( 'PENDRELL_SYNTAX_HIGHLIGHT', true );
}

// Master switch for WP AJAX Page Load script
defined( 'PENDRELL_AJAX_PAGE_LOADER' )    || define( 'PENDRELL_AJAX_PAGE_LOADER', true );

// Baseline for the vertical rhythm; should match whatever is set in _base_config.scss (integer; required)
defined( 'PENDRELL_BASELINE' )            || define( 'PENDRELL_BASELINE', 28 );

// Disabled in this theme
defined( 'PENDRELL_POST_FORMATS' )        || define( 'PENDRELL_POST_FORMATS', false );

// Master switch for Picturefill script
defined( 'PENDRELL_RESPONSIVE_IMAGES' )   || define( 'PENDRELL_RESPONSIVE_IMAGES', false );

// Master switch for Prism syntax highlighting script
defined( 'PENDRELL_SYNTAX_HIGHLIGHT' )    || define( 'PENDRELL_SYNTAX_HIGHLIGHT', false );
