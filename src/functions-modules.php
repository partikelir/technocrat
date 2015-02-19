<?php // ==== UBIK ==== //

// This file is an adapter for the Ubik suite of components; it contains all the little scraps of code to integrate Ubik into Pendrell

// For testing purposes only; switches everything on for development
if ( WP_LOCAL_DEV ) {
  defined( 'PENDRELL_UBIK_ADMIN' )          || define( 'PENDRELL_UBIK_ADMIN', true );
  defined( 'PENDRELL_UBIK_ANALYTICS' )      || define( 'PENDRELL_UBIK_ANALYTICS', true );
  defined( 'PENDRELL_UBIK_CLEANER' )        || define( 'PENDRELL_UBIK_CLEANER', true );
  defined( 'PENDRELL_UBIK_COMMENTS' )       || define( 'PENDRELL_UBIK_COMMENTS', true );
  defined( 'PENDRELL_UBIK_EXCLUDER' )       || define( 'PENDRELL_UBIK_EXCLUDER', true );
  defined( 'PENDRELL_UBIK_FEED' )           || define( 'PENDRELL_UBIK_FEED', true );
  defined( 'PENDRELL_UBIK_LINGUAL' )        || define( 'PENDRELL_UBIK_LINGUAL', true );
  defined( 'PENDRELL_UBIK_LINKS' )          || define( 'PENDRELL_UBIK_LINKS', true );
  defined( 'PENDRELL_UBIK_MARKDOWN' )       || define( 'PENDRELL_UBIK_MARKDOWN', true );
  defined( 'PENDRELL_UBIK_PHOTO_META' )     || define( 'PENDRELL_UBIK_PHOTO_META', true );
  defined( 'PENDRELL_UBIK_PLACES' )         || define( 'PENDRELL_UBIK_PLACES', true );
  defined( 'PENDRELL_UBIK_POST_FORMATS' )   || define( 'PENDRELL_UBIK_POST_FORMATS', true );
  defined( 'PENDRELL_UBIK_QUICK_TERMS' )    || define( 'PENDRELL_UBIK_QUICK_TERMS', true );
  defined( 'PENDRELL_UBIK_RECORDPRESS' )    || define( 'PENDRELL_UBIK_RECORDPRESS', true );
  defined( 'PENDRELL_UBIK_RELATED' )        || define( 'PENDRELL_UBIK_RELATED', true );
  defined( 'PENDRELL_UBIK_SERIES' )         || define( 'PENDRELL_UBIK_SERIES', true );
  defined( 'PENDRELL_UBIK_SEO' )            || define( 'PENDRELL_UBIK_SEO', true );
  defined( 'PENDRELL_UBIK_VIEWS' )          || define( 'PENDRELL_UBIK_VIEWS', true );
}

// Ubik is a collection of lightwight WordPress components; use these master switches to turn these optional components on or off
defined( 'PENDRELL_UBIK_ADMIN' )          || define( 'PENDRELL_UBIK_ADMIN', false );
defined( 'PENDRELL_UBIK_ANALYTICS' )      || define( 'PENDRELL_UBIK_ANALYTICS', false );
defined( 'PENDRELL_UBIK_CLEANER' )        || define( 'PENDRELL_UBIK_CLEANER', true ); // Active
defined( 'PENDRELL_UBIK_COMMENTS' )       || define( 'PENDRELL_UBIK_COMMENTS', true ); // Active
defined( 'PENDRELL_UBIK_EXCLUDER' )       || define( 'PENDRELL_UBIK_EXCLUDER', false );
defined( 'PENDRELL_UBIK_FEED' )           || define( 'PENDRELL_UBIK_FEED', true ); // Active
defined( 'PENDRELL_UBIK_LINGUAL' )        || define( 'PENDRELL_UBIK_LINGUAL', false );
defined( 'PENDRELL_UBIK_LINKS' )          || define( 'PENDRELL_UBIK_LINKS', false );
defined( 'PENDRELL_UBIK_MARKDOWN' )       || define( 'PENDRELL_UBIK_MARKDOWN', false );
defined( 'PENDRELL_UBIK_PHOTO_META' )     || define( 'PENDRELL_UBIK_PHOTO_META', false );
defined( 'PENDRELL_UBIK_PLACES' )         || define( 'PENDRELL_UBIK_PLACES', false );
defined( 'PENDRELL_UBIK_POST_FORMATS' )   || define( 'PENDRELL_UBIK_POST_FORMATS', true ); // Active
defined( 'PENDRELL_UBIK_QUICK_TERMS' )    || define( 'PENDRELL_UBIK_QUICK_TERMS', false );
defined( 'PENDRELL_UBIK_RECORDPRESS' )    || define( 'PENDRELL_UBIK_RECORDPRESS', false );
defined( 'PENDRELL_UBIK_RELATED' )        || define( 'PENDRELL_UBIK_RELATED', false );
defined( 'PENDRELL_UBIK_SERIES' )         || define( 'PENDRELL_UBIK_SERIES', false );
defined( 'PENDRELL_UBIK_SEO' )            || define( 'PENDRELL_UBIK_SEO', true ); // Active
defined( 'PENDRELL_UBIK_VIEWS' )          || define( 'PENDRELL_UBIK_VIEWS', true ); // Active

// Modules path
$pendrell_modules = trailingslashit( get_stylesheet_directory() ) . 'modules/';

// Require Ubik core first...
require_once( $pendrell_modules . 'ubik/ubik.php' );



// == ADMIN == //

if ( is_admin() ) {
  if ( PENDRELL_UBIK_ADMIN )
    require_once( $pendrell_modules . 'ubik-admin/ubik-admin.php' );
  if ( PENDRELL_UBIK_QUICK_TERMS )
    require_once( $pendrell_modules . 'ubik-quick-terms/ubik-quick-terms.php' );
}



// == ANALYTICS == //

if ( PENDRELL_UBIK_ANALYTICS )
  require_once( $pendrell_modules . 'ubik-analytics/ubik-analytics.php' );



// == CLEANER == //

if ( PENDRELL_UBIK_CLEANER )
  require_once( $pendrell_modules . 'ubik-cleaner/ubik-cleaner.php' );



// == COLOPHON * == //

require_once( $pendrell_modules . 'ubik-colophon/ubik-colophon.php' );

// Output the colophon
function pendrell_colophon() {
  $colophon = ubik_colophon();
  if ( !empty( $colophon ) ) {
    ?><div class="site-footer-info">
      <?php echo $colophon; ?>
    </div><?php
  }
}
add_action( 'pendrell_footer', 'pendrell_colophon' );



// == COMMENTS == //

if ( PENDRELL_UBIK_COMMENTS )
  require_once( $pendrell_modules . 'ubik-comments/ubik-comments.php' );



// == EXCERPTS * == //

require_once( $pendrell_modules . 'ubik-excerpt/ubik-excerpt.php' );



// == EXCLUDER == //

if ( PENDRELL_UBIK_EXCLUDER )
  require_once( $pendrell_modules . 'ubik-excluder/ubik-excluder.php' );



// == FAVICONS * == //

require_once( $pendrell_modules . 'ubik-favicons/ubik-favicons.php' );



// == FEED == //

if ( PENDRELL_UBIK_FEED )
  require_once( $pendrell_modules . 'ubik-feed/ubik-feed.php' ); // @TODO: image size management in feeds



// == IMAGERY * == //

// Enable `srcset` output only when Picturefill module is active
if ( PENDRELL_SCRIPTS_PICTUREFILL )
  define( 'UBIK_IMAGERY_SRCSET', true );
define( 'UBIK_IMAGERY_DIMENSIONS', false );
require_once( $pendrell_modules . 'ubik-imagery.php' );



// == LINGUAL == //

if ( PENDRELL_UBIK_LINGUAL ) {
  require_once( $pendrell_modules . 'ubik-lingual/ubik-lingual.php' );
  add_filter( 'ubik_title', 'ubik_lingual_unpinyin' );
  if ( PENDRELL_UBIK_PLACES )
    add_filter( 'ubik_places_title', 'ubik_lingual_unpinyin' ); // Small header in the faux widget
}



// == LINKS == //

if ( PENDRELL_UBIK_LINKS ) {
  define( 'UBIK_LINKS_PAGE_TEMPLATE', 'page-templates/links.php' );
  define( 'UBIK_LINKS_SEARCH_FORM_REVERSE', true );
  require_once( $pendrell_modules . 'ubik-links.php' );
}



// == MARKDOWN == //

if ( PENDRELL_UBIK_MARKDOWN )
  require_once( $pendrell_modules . 'ubik-markdown/ubik-markdown.php' );



// == META * == //

require_once( $pendrell_modules . 'ubik-meta/ubik-meta.php' );



// == PHOTO META == //

if ( PENDRELL_UBIK_PHOTO_META )
  require_once( $pendrell_modules . 'ubik-photo-meta.php' );



// == PLACES == //

if ( PENDRELL_UBIK_PLACES )
  require_once( $pendrell_modules . 'ubik-places.php' );



// == POST FORMATS == //

if ( PENDRELL_UBIK_POST_FORMATS )
  require_once( $pendrell_modules . 'ubik-post-formats/ubik-post-formats.php' );



// == RECORDPRESS == //

if ( PENDRELL_UBIK_RECORDPRESS )
  require_once( $pendrell_modules . 'ubik-recordpress/ubik-recordpress.php' );



// == RELATED POSTS == //

if ( PENDRELL_UBIK_RELATED )
  require_once( $pendrell_modules . 'ubik-related.php' );



// == SEO == //

if ( PENDRELL_UBIK_SEO )
  require_once( $pendrell_modules . 'ubik-seo/ubik-seo.php' );



// == SEARCH * == //

// Reverses the order of search field and submit button; *required* for this theme
define( 'UBIK_SEARCH_FORM_REVERSE', true );
define( 'UBIK_SEARCH_POSTS_PER_PAGE', 20 );
require_once( $pendrell_modules . 'ubik-search/ubik-search.php' );

// Add an icon to the search button
function pendrell_search_button( $contents ) {
  return ubik_svg_icon( 'ion-search' ) . $contents;
}
add_filter( 'ubik_search_button', 'pendrell_search_button' );



// == SERIES == //

if ( PENDRELL_UBIK_SERIES ) {
  require_once( $pendrell_modules . 'ubik-series/ubik-series.php' );
  add_action( 'pendrell_comment_template_before', 'ubik_series', 15 );

  // Add a custom class to series list output
  function pendrell_series_list_class( $class ) {
    return 'entry-after series';
  }
  add_filter( 'ubik_series_list_class', 'pendrell_series_list_class' );
}



// == SVG ICONS * == //

define( 'UBIK_SVG_ICONS_PATH', get_template_directory() . '/img/icons.svg' );
define( 'UBIK_SVG_ICONS_URL', get_template_directory_uri() . '/img/icons.svg' );
require_once( $pendrell_modules . 'ubik-svg-icons/ubik-svg-icons.php' );

// If we have a path and no URL we probably mean to inject the SVG icon file
if ( UBIK_SVG_ICONS_PATH && UBIK_SVG_ICONS_URL === false )
  add_action( 'pendrell_body_before', 'ubik_icon_sheet_inline' );



// == TERMS * == //

define( 'UBIK_TERMS_TAG_SHORTCODE', true );
require_once( $pendrell_modules . 'ubik-terms/ubik-terms.php' );
add_filter( 'pendrell_archive_description_term', 'ubik_terms_edit_description_prompt' );
add_filter( 'pendrell_archive_description_term', 'ubik_terms_edit_link' );



// == TIME * == //

require_once( $pendrell_modules . 'ubik-time/ubik-time.php' );
add_filter( 'ubik_meta_timestamp_published', 'ubik_time_human' ); // Humanize these times
add_filter( 'ubik_meta_timestamp_updated', 'ubik_time_human' );



// == TITLE * == //

require_once( $pendrell_modules . 'ubik-title/ubik-title.php' );



// == VIEWS == //

if ( PENDRELL_UBIK_VIEWS )
  require_once( $pendrell_modules . 'ubik-views.php' );
