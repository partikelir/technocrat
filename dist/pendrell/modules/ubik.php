<?php // ==== UBIK ==== //

// Integrating Pendrell with the Ubik suite of WordPress components...



// == REQUIRED == //

require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik/ubik.php' ); // Must come first

require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-excerpt/ubik-excerpt.php' );

require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-favicons/ubik-favicons.php' );

require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-imagery/ubik-imagery.php' );

require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-meta/ubik-meta.php' );

require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-search/ubik-search.php' );

require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-terms/ubik-terms.php' );
add_filter( 'pendrell_archive_description', 'ubik_terms_edit_link' );
add_filter( 'pendrell_term_archive_description', 'ubik_terms_edit_description_prompt' );

require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-time/ubik-time.php' );
add_filter( 'ubik_meta_timestamp_published', 'ubik_time_human' ); // Humanize these times
add_filter( 'ubik_meta_timestamp_updated', 'ubik_time_human' );

require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-title/ubik-title.php' );



// == OPTIONAL == //

if ( PENDRELL_UBIK_ANALYTICS )
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-analytics/ubik-analytics.php' );
if ( PENDRELL_UBIK_CLEANER )
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-cleaner/ubik-cleaner.php' );
if ( PENDRELL_UBIK_COMMENTS )
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-comments/ubik-comments.php' );
if ( PENDRELL_UBIK_EXCLUDER )
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-excluder/ubik-excluder.php' );
if ( PENDRELL_UBIK_FEED )
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-feed/ubik-feed.php' );
// @TODO: manage image sizes in feeds
if ( PENDRELL_UBIK_LINGUAL ) {
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-lingual/ubik-lingual.php' );
  //add_filter( 'pendrell_entry_title', 'ubik_lingual_unpinyin' );
  add_filter( 'ubik_title', 'ubik_lingual_unpinyin' );
  if ( PENDRELL_UBIK_PLACES )
    add_filter( 'ubik_places_title', 'ubik_lingual_unpinyin' ); // Small header in the faux widget
}
if ( PENDRELL_UBIK_MARKDOWN )
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-markdown/ubik-markdown.php' );
if ( PENDRELL_UBIK_PLACES ) {
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-places/ubik-places.php' );
  add_action( 'pendrell_archive_description_before', 'ubik_places_breadcrumb' );
  add_filter( 'pendrell_sidebar', 'pendrell_sidebar_places' );
}
if ( PENDRELL_UBIK_POST_FORMATS )
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-post-formats/ubik-post-formats.php' );
if ( PENDRELL_UBIK_RECORDPRESS )
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-recordpress/ubik-recordpress.php' );
if ( PENDRELL_UBIK_RELATED ) {
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-related/ubik-related.php' );
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/related.php' );
}
if ( PENDRELL_UBIK_SEO )
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-seo/ubik-seo.php' );
if ( PENDRELL_UBIK_SERIES ) {
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-series/ubik-series.php' );
  add_action( 'pendrell_comment_template_before', 'ubik_series', 15 );
  add_filter( 'ubik_series_list_class', 'pendrell_series_list_class' );
}



// == ADMIN == //

if ( is_admin() ) {
  if ( PENDRELL_UBIK_ADMIN )
    require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-admin/ubik-admin.php' );
  if ( PENDRELL_UBIK_QUICK_TERMS )
    require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-quick-terms/ubik-quick-terms.php' );
}



// == PLACES SIDEBAR == //

// Don't display regular sidebar on full-width items; this function appears here as the sidebar-switching filter is specific to Pendrell
if ( !function_exists( 'pendrell_sidebar_places' ) ) : function pendrell_sidebar_places( $sidebar ) {
  if ( is_tax( 'places' ) && !pendrell_is_full_width() ) {
    ubik_places_widget(); // @TODO: turn this into a real widgetized sidebar
    $sidebar = false;
  }
  return $sidebar;
} endif;



// == RELATED POSTS == //

// Add various custom taxonomies to the related posts component; @TODO: relocate these filters to their respective components
if ( !function_exists( 'pendrell_related_taxonomies' ) ) : function pendrell_related_taxonomies( $taxonomies = array() ) {
  if ( PENDRELL_UBIK_PLACES )
    $taxonomies[] = 'places';
  if ( PENDRELL_UBIK_RECORDPRESS ) {
    $taxonomies[] = 'artists';
    $taxonomies[] = 'styles';
  }
  if ( PENDRELL_UBIK_SERIES )
    $taxonomies[] = 'series';
  return $taxonomies;
} endif;
add_filter( 'pendrell_related_taxonomies', 'pendrell_related_taxonomies' );



// == SERIES == //

// Add a custom class to series list output
if ( !function_exists( 'pendrell_series_list_class' ) ) : function pendrell_series_list_class( $class ) {
  return 'entry-after series';
} endif;
