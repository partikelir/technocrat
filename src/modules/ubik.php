<?php // ==== UBIK ==== //

// A small collection of functions to improve integration between Pendrell and Ubik components



// == REQUIRED == //

require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-imagery/ubik-imagery.php' );
require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-search/ubik-search.php' );
require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-terms/ubik-terms.php' );
add_filter( 'pendrell_archive_description', 'ubik_terms_edit_link' );
add_filter( 'pendrell_term_archive_description', 'ubik_terms_edit_description_prompt' );



// == OPTIONAL == //

if ( PENDRELL_UBIK_ANALYTICS )
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-analytics/ubik-analytics.php' );
if ( PENDRELL_UBIK_COMMENTS )
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-comments/ubik-comments.php' );
if ( PENDRELL_UBIK_EXCLUDER )
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-excluder/ubik-excluder.php' );
if ( PENDRELL_UBIK_FEED )
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-feed/ubik-feed.php' );
if ( PENDRELL_UBIK_LINGUAL ) {
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-lingual/ubik-lingual.php' );
  add_filter( 'pendrell_entry_title', 'ubik_lingual_unpinyin' );
  add_filter( 'pendrell_archive_title', 'ubik_lingual_unpinyin' );
  add_filter( 'ubik_content_title', 'ubik_lingual_unpinyin' ); // @TODO: verify this is still needed
  if ( PENDRELL_UBIK_PLACES )
    add_filter( 'ubik_places_title', 'ubik_lingual_unpinyin' );
}
if ( PENDRELL_UBIK_MARKDOWN )
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-markdown/ubik-markdown.php' );
if ( PENDRELL_UBIK_META )
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-meta/ubik-meta.php' );
if ( PENDRELL_UBIK_PLACES ) {
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-places/ubik-places.php' );
  add_action( 'pendrell_archive_description_before', 'ubik_places_breadcrumb' );
  add_filter( 'pendrell_sidebar', 'ubik_places_sidebar' );
}
if ( PENDRELL_UBIK_POST_FORMATS )
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-post-formats/ubik-post-formats.php' );
if ( PENDRELL_UBIK_RECORDPRESS )
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-recordpress/ubik-recordpress.php' );
if ( PENDRELL_UBIK_SERIES ) {
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-series/ubik-series.php' );
  add_action( 'pendrell_entry_meta_before', 'ubik_series_list' );
}



// == ADMIN == //

if ( is_admin() ) {
  if ( PENDRELL_UBIK_ADMIN )
    require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-admin/ubik-admin.php' );
  if ( PENDRELL_UBIK_QUICK_TERMS )
    require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-quick-terms/ubik-quick-terms.php' );
}



// == PLACES SIDEBAR == //

// Don't display regular sidebar on full-width items
function ubik_places_sidebar( $sidebar ) {
  if ( is_tax( 'places' ) && !pendrell_is_full_width() ) {
    ubik_places_widget(); // @TODO: turn this into a real widgetized sidebar
    $sidebar = false;
  }
  return $sidebar;
}
