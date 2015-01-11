<?php // ==== UBIK ==== //

// This file is an adapter for the Ubik suite of components; it contains all the little scraps of code to integrate Ubik into Pendrell

// Require Ubik core first...
require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik/ubik.php' );



// == ADMIN == //

if ( is_admin() ) {
  if ( PENDRELL_UBIK_ADMIN )
    require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-admin/ubik-admin.php' );
  if ( PENDRELL_UBIK_QUICK_TERMS )
    require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-quick-terms/ubik-quick-terms.php' );
}



// == ANALYTICS == //

if ( PENDRELL_UBIK_ANALYTICS )
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-analytics/ubik-analytics.php' );



// == CLEANER == //

if ( PENDRELL_UBIK_CLEANER )
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-cleaner/ubik-cleaner.php' );



// == COMMENTS == //

if ( PENDRELL_UBIK_COMMENTS )
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-comments/ubik-comments.php' );



// == EXCERPTS == //

require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-excerpt/ubik-excerpt.php' );



// == EXCLUDER == //

if ( PENDRELL_UBIK_EXCLUDER )
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-excluder/ubik-excluder.php' );



// == FAVICONS == //

require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-favicons/ubik-favicons.php' );



// == FEED == //

if ( PENDRELL_UBIK_FEED )
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-feed/ubik-feed.php' ); // @TODO: image size management in feeds



// == IMAGERY == //

require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-imagery/ubik-imagery.php' );



// == LINGUAL == //

if ( PENDRELL_UBIK_LINGUAL ) {
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-lingual/ubik-lingual.php' );
  add_filter( 'ubik_title', 'ubik_lingual_unpinyin' );
  if ( PENDRELL_UBIK_PLACES )
    add_filter( 'ubik_places_title', 'ubik_lingual_unpinyin' ); // Small header in the faux widget
}



// == MARKDOWN == //

if ( PENDRELL_UBIK_MARKDOWN )
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-markdown/ubik-markdown.php' );



// == META == //

require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-meta/ubik-meta.php' );



// == PLACES == //

if ( PENDRELL_UBIK_PLACES ) {
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-places/ubik-places.php' );
  add_action( 'pendrell_archive_description_before', 'ubik_places_breadcrumb' );

  // Don't display regular sidebar on full-width items; this function appears here as the sidebar-switching filter is specific to Pendrell
  function pendrell_sidebar_places( $sidebar ) {
    if ( is_tax( 'places' ) && !pendrell_is_full_width() ) {
      ubik_places_widget(); // @TODO: turn this into a real widgetized sidebar
      $sidebar = false;
    }
    return $sidebar;
  }
  add_filter( 'pendrell_sidebar', 'pendrell_sidebar_places' );
}



// == POST FORMATS == //

if ( PENDRELL_UBIK_POST_FORMATS )
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-post-formats/ubik-post-formats.php' );



// == RECORDPRESS == //

if ( PENDRELL_UBIK_RECORDPRESS )
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-recordpress/ubik-recordpress.php' );



// == RELATED POSTS == //

if ( PENDRELL_UBIK_RELATED ) {
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-related/ubik-related.php' );
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/related.php' );

  // Add various custom taxonomies to the related posts feature
  function pendrell_related_taxonomies( $taxonomies = array() ) {
    if ( PENDRELL_UBIK_PLACES )
      $taxonomies['places'] = 2; // This taxonomy is also extended (below)
    if ( PENDRELL_UBIK_RECORDPRESS ) {
      $taxonomies['artists'] = 3;
      $taxonomies['styles'] = 2;
    }
    if ( PENDRELL_UBIK_SERIES )
      $taxonomies['series'] = 2;
    return $taxonomies;
  }
  add_filter( 'pendrell_related_taxonomies', 'pendrell_related_taxonomies' );

  // Extended taxonomies: a broader search for related posts using sibling terms
  function pendrell_related_taxonomies_extended( $taxonomies = array() ) {
    if ( PENDRELL_UBIK_PLACES )
      $taxonomies[] = 'places';
    return $taxonomies;
  }
  add_filter( 'ubik_related_taxonomies_extended', 'pendrell_related_taxonomies_extended' );
}



// == SEO == //

if ( PENDRELL_UBIK_SEO )
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-seo/ubik-seo.php' );



// == SEARCH == //

// Reverses the order of search field and submit button; *required* for this theme
define( 'UBIK_SEARCH_FORM_REVERSE', true );

require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-search/ubik-search.php' );

// Add an icon to the search button
if ( !function_exists( 'pendrell_search_button' ) ) : function pendrell_search_button( $contents ) {
  return pendrell_icon( 'ion-search' ) . $contents;
} endif;
add_filter( 'ubik_search_button', 'pendrell_search_button' );



// == SERIES == //

if ( PENDRELL_UBIK_SERIES ) {
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-series/ubik-series.php' );
  add_action( 'pendrell_comment_template_before', 'ubik_series', 15 );

  // Add a custom class to series list output
  function pendrell_series_list_class( $class ) {
    return 'entry-after series';
  }
  add_filter( 'ubik_series_list_class', 'pendrell_series_list_class' );
}



// == TERMS == //

require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-terms/ubik-terms.php' );
add_filter( 'pendrell_archive_description', 'ubik_terms_edit_link' );
add_filter( 'pendrell_term_archive_description', 'ubik_terms_edit_description_prompt' );



// == TIME == //

require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-time/ubik-time.php' );
add_filter( 'ubik_meta_timestamp_published', 'ubik_time_human' ); // Humanize these times
add_filter( 'ubik_meta_timestamp_updated', 'ubik_time_human' );



// == TITLE == //

require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-title/ubik-title.php' );
