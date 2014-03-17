<?php // === PENDRELL CONFIGURATION === //
define( 'PENDRELL_VERSION', 0.5 );
define( 'PENDRELL_NAME', get_bloginfo( 'name' ) );
define( 'PENDRELL_DESC', get_bloginfo( 'description' ) );
define( 'PENDRELL_HOME', get_bloginfo( 'url' ) );

// === CUSTOMIZE THESE VALUES TO SUIT YOUR NEEDS === //

// The author ID of the blog owner (for use with more highly secured blogs)
define( 'PENDRELL_AUTHOR_ID', 1 );

// Turn author box display on or off
define( 'PENDRELL_AUTHOR_BOX', true );

// Google web fonts to load; false will load Open Sans
define( 'PENDRELL_GOOGLE_FONTS', 'Varela+Round:400|Lato:400italic,400,700'); // |Open+Sans:400italic,700italic,400,700');

// Places functionality
define( 'PENDRELL_PLACES', false );

// Shadow categories: add category numbers to this string in the format '-1,-2' to hide them from the front page
define( 'PENDRELL_SHADOW_CATS', false );

// Post series functionality
define( 'PENDRELL_SERIES', false );

// Post formats; choose from array( 'aside', 'gallery', 'link', 'image', 'quote', 'status', 'audio', 'chat', 'video' );
$pendrell_post_formats = array( 'aside', 'link', 'quote', 'status' );

// Portfolio categories; add or remove any slug to this array to enable matching categories with portfolio functionality
$pendrell_portfolio_cats = array( 'creative', 'design', 'photography', 'portfolio' );
