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

// Google web fonts custom subset support; string or false to disable
define( 'PENDRELL_GOOGLE_FONTS_SUBSET', false );

// Master switch for full-width styling
define( 'PENDRELL_MODULE_FULL_WIDTH', true );

// Master switch for post formats
define( 'PENDRELL_MODULE_POST_FORMATS', true );
