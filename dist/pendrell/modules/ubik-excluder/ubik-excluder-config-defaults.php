<?php // ==== CONFIGURATION ==== //

// Categories, formats, and tags to exclude from the homepage; set these in your `functions-config.php`

// Categories should be listed by slug or ID
if ( empty( $ubik_exclude_cats ) )    { $ubik_exclude_cats    = array(); }

// Post formats should be complete post format names e.g. 'post-format-aside' or 'post-format-image' (*not* IDs)
if ( empty( $ubik_exclude_formats ) ) { $ubik_exclude_formats = array(); }

// Tags should also be listed by slug or ID
if ( empty( $ubik_exclude_tags ) )    { $ubik_exclude_tags    = array(); }

// Rewrite slug for an all-inclusive homepage alias; string or false to disable
defined( 'UBIK_EXCLUDER_INCLUDE_ALL' ) || define( 'UBIK_EXCLUDER_INCLUDE_ALL', false );
