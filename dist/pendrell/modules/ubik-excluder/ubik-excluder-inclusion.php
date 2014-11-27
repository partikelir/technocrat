<?php // ==== INCLUDER ==== //

// These functions allow for the creation of a virtual alias of the WordPress homepage not subject to any rules set above
// Be sure to flush your permalinks after activating this feature in your configuration file
// Some caveats: UBIK_EXCLUDER_INCLUDE_ALL takes precedence over any post or page with the same slug

// Add rewrite rules for our virtual page to the top of the rewrite rules
// @constant: UBIK_EXCLUDER_INCLUDE_ALL
if ( !function_exists( 'ubik_include_all_rewrite' ) ) : function ubik_include_all_rewrite() {
  add_rewrite_rule( UBIK_EXCLUDER_INCLUDE_ALL . '/page/?([0-9]{1,})/?$', 'index.php?&paged=$matches[1]', 'top' );
  add_rewrite_rule( UBIK_EXCLUDER_INCLUDE_ALL . '/?$', 'index.php?', 'top' );
} endif;

// Parse the query and conditionally add the 'ubik_include_all' variable to the query; this in turn will disable any exclusions
// @constant: UBIK_EXCLUDER_INCLUDE_ALL
if ( !function_exists( 'ubik_include_all_parse_query' ) ) : function ubik_include_all_parse_query( $wp_query ) {
  global $wp;

  // Check the matched rule to see if it begins with UBIK_EXCLUDER_INCLUDE_ALL; the backslash is intended to prevent inexact matches
  if ( strpos( $wp->matched_rule, UBIK_EXCLUDER_INCLUDE_ALL . '/' ) === 0 )
    $wp_query->set( 'ubik_include_all', true );
} endif;

// Only activate these functions when an 'include all' page slug is set in the configuration
if ( UBIK_EXCLUDER_INCLUDE_ALL ) {
  add_action( 'init', 'ubik_include_all_rewrite' );
  add_action( 'parse_query', 'ubik_include_all_parse_query' );
}
