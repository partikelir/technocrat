<?php // ==== UBIK CONFIGURATION ==== //

// Default number of columns for the [group] shortcode
defined( 'UBIK_IMAGERY_GROUP_COLUMNS' )             || define( 'UBIK_IMAGERY_GROUP_COLUMNS', 2 );

// Default thumbnail; must be an attachment ID (integer) or false to disable
defined( 'UBIK_IMAGERY_THUMBNAIL_DEFAULT' )         || define( 'UBIK_IMAGERY_THUMBNAIL_DEFAULT', false );

// Wrap post_thumbnail output in nicer image markup; use with caution; true/false
defined( 'UBIK_IMAGERY_THUMBNAIL_MARKUP' )          || define( 'UBIK_IMAGERY_THUMBNAIL_MARKUP', false );

// Image height and width attributes; true by default, false to remove dimensions for most images
defined( 'UBIK_IMAGERY_THUMBNAIL_NO_DIMENSIONS' )   || define( 'UBIK_IMAGERY_THUMBNAIL_NO_DIMENSIONS', false );
