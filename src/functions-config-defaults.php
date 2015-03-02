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

// Master switch for post formats
defined( 'PENDRELL_POST_FORMATS' )        || define( 'PENDRELL_POST_FORMATS', false );

// Master switch for WP AJAX Page Load script
defined( 'PENDRELL_SCRIPTS_PAGELOAD' )    || define( 'PENDRELL_SCRIPTS_PAGELOAD', true );

// Master switch for Picturefill script
defined( 'PENDRELL_SCRIPTS_PICTUREFILL' ) || define( 'PENDRELL_SCRIPTS_PICTUREFILL', false );

// Master switch for Prism syntax highlighting script
defined( 'PENDRELL_SCRIPTS_PRISM' )       || define( 'PENDRELL_SCRIPTS_PRISM', false );
