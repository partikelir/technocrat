<?php // ==== UBIK ==== //

// This file is an adapter for the Ubik suite of components; it contains all the little scraps of code to integrate Ubik into Pendrell

// For testing purposes only; switches everything on for development
if ( WP_LOCAL_DEV && 1==0 ) {
  defined( 'PENDRELL_UBIK_ADMIN' )          || define( 'PENDRELL_UBIK_ADMIN', true );
  defined( 'PENDRELL_UBIK_ANALYTICS' )      || define( 'PENDRELL_UBIK_ANALYTICS', true );
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
  defined( 'PENDRELL_UBIK_SEO' )            || define( 'PENDRELL_UBIK_SEO', true );
  defined( 'PENDRELL_UBIK_SERIES' )         || define( 'PENDRELL_UBIK_SERIES', true );
}

// Ubik is a collection of lightwight WordPress components; use these master switches to turn these optional components on or off
defined( 'PENDRELL_UBIK_ADMIN' )          || define( 'PENDRELL_UBIK_ADMIN', true );
defined( 'PENDRELL_UBIK_ANALYTICS' )      || define( 'PENDRELL_UBIK_ANALYTICS', true );
defined( 'PENDRELL_UBIK_EXCLUDER' )       || define( 'PENDRELL_UBIK_EXCLUDER', false );
defined( 'PENDRELL_UBIK_FEED' )           || define( 'PENDRELL_UBIK_FEED', true );
defined( 'PENDRELL_UBIK_LINGUAL' )        || define( 'PENDRELL_UBIK_LINGUAL', false );
defined( 'PENDRELL_UBIK_LINKS' )          || define( 'PENDRELL_UBIK_LINKS', true );
defined( 'PENDRELL_UBIK_MARKDOWN' )       || define( 'PENDRELL_UBIK_MARKDOWN', true );
defined( 'PENDRELL_UBIK_PHOTO_META' )     || define( 'PENDRELL_UBIK_PHOTO_META', false );
defined( 'PENDRELL_UBIK_PLACES' )         || define( 'PENDRELL_UBIK_PLACES', false );
defined( 'PENDRELL_UBIK_POST_FORMATS' )   || define( 'PENDRELL_UBIK_POST_FORMATS', false );
defined( 'PENDRELL_UBIK_QUICK_TERMS' )    || define( 'PENDRELL_UBIK_QUICK_TERMS', true );
defined( 'PENDRELL_UBIK_RECORDPRESS' )    || define( 'PENDRELL_UBIK_RECORDPRESS', false );
defined( 'PENDRELL_UBIK_RELATED' )        || define( 'PENDRELL_UBIK_RELATED', false );
defined( 'PENDRELL_UBIK_SEO' )            || define( 'PENDRELL_UBIK_SEO', true );
defined( 'PENDRELL_UBIK_SERIES' )         || define( 'PENDRELL_UBIK_SERIES', false );

// Modules path
$path_modules = trailingslashit( get_stylesheet_directory() ) . 'modules/';



// == ADMIN == //

if ( is_admin() ) {
  if ( PENDRELL_UBIK_ADMIN ) {
    define( 'UBIK_ADMIN_POST_LIST_THUMBS', true );
    define( 'UBIK_ADMIN_TAG_FILTER', true );
    define( 'UBIK_ADMIN_TERM_EDIT_STYLE', true );
    define( 'UBIK_ADMIN_USER_CONTACT_METHOD', true );
    define( 'UBIK_ADMIN_USER_ALLOW_HTML', true );
    if ( WP_DEBUG ) {
      define( 'UBIK_ADMIN_VIEW_ALL_SETTINGS', true );
      define( 'UBIK_ADMIN_VIEW_ALL_SHORTCODES', true );
    }
    require_once( $path_modules . 'ubik-admin/ubik-admin.php' );
  }
  if ( PENDRELL_UBIK_QUICK_TERMS )
    require_once( $path_modules . 'ubik-quick-terms/ubik-quick-terms.php' );
}



// == ANALYTICS == //

if ( PENDRELL_UBIK_ANALYTICS ) {
  define( 'UBIK_GOOGLE_ANALYTICS', 'UA-31121442-5' );
  define( 'UBIK_GOOGLE_ANALYTICS_DISPLAYF', true );
  require_once( $path_modules . 'ubik-analytics/ubik-analytics.php' );
}



// == CLEANER * == //

define( 'UBIK_CLEANER_REMOVE_MIGRATE', true );
define( 'UBIK_CLEANER_REMOVE_OPEN_SANS', true );
require_once( $path_modules . 'ubik-cleaner/ubik-cleaner.php' );



// == COLOPHON * == //

require_once( $path_modules . 'ubik-colophon/ubik-colophon.php' );

function pendrell_colophon_copyright_from() {
  return '2012';
}
add_filter( 'ubik_colophon_copyright_from', 'pendrell_colophon_copyright_from' );

function pendrell_colophon_credit_author() {
  return 1;
}
add_filter( 'ubik_colophon_credit_author', 'pendrell_colophon_credit_author' );

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



// == COMMENTS * == //

define( 'UBIK_COMMENTS_ALLOWED_TAGS', true );
define( 'UBIK_COMMENTS_ATTACHMENTS_OFF', true );
//define( 'UBIK_COMMENTS_LINK_SHOW_NONE', true );
define( 'UBIK_COMMENTS_PINGBACKS_OFF', true );
require_once( $path_modules . 'ubik-comments/ubik-comments.php' );



// == EXCERPTS * == //

define( 'UBIK_EXCERPT_PAGES', true );
require_once( $path_modules . 'ubik-excerpt/ubik-excerpt.php' );



// == EXCLUDER == //

if ( PENDRELL_UBIK_EXCLUDER )
  require_once( $path_modules . 'ubik-excluder/ubik-excluder.php' );



// == FAVICONS * == //

// define( 'UBIK_FAVICONS_PATH', '/dev' ); // @TODO: activate
define( 'UBIK_FAVICONS_TILE_COLOUR', '#00aba9' );
require_once( $path_modules . 'ubik-favicons/ubik-favicons.php' );



// == FEED == //

if ( PENDRELL_UBIK_FEED )
  require_once( $path_modules . 'ubik-feed/ubik-feed.php' ); // @TODO: image size management in feeds



// == FONTS == * //

define( 'UBIK_FONTS_GOOGLE', 'Oxygen:300,300italic,400,400italic,700,700italic|Ubuntu:300,400,700' );
//define( 'UBIK_FONTS_GOOGLE_ADMIN', 'Noto+Serif:400' );
require_once( $path_modules . 'ubik-fonts/ubik-fonts.php' );



// == IMAGERY * == //

// Enable `srcset` output only when Picturefill module is active
if ( PENDRELL_SCRIPTS_PICTUREFILL )
  define( 'UBIK_IMAGERY_SRCSET', true );
define( 'UBIK_IMAGERY_DIMENSIONS', false );
define( 'UBIK_IMAGERY_URL_MORE_SHORTCUT', true );
require_once( $path_modules . 'ubik-imagery.php' );



// == LINGUAL == //

if ( PENDRELL_UBIK_LINGUAL )
  require_once( $path_modules . 'ubik-lingual/ubik-lingual.php' );



// == LINKS == //

if ( PENDRELL_UBIK_LINKS ) {
  define( 'UBIK_LINKS_PAGE_TEMPLATE', 'page-templates/links.php' );
  define( 'UBIK_LINKS_SEARCH_FORM_REVERSE', true );
  require_once( $path_modules . 'ubik-links.php' );
}



// == MARKDOWN == //

if ( PENDRELL_UBIK_MARKDOWN )
  require_once( $path_modules . 'ubik-markdown/ubik-markdown.php' );



// == META * == //

require_once( $path_modules . 'ubik-meta/ubik-meta.php' );



// == PHOTO META == //

if ( PENDRELL_UBIK_PHOTO_META )
  require_once( $path_modules . 'ubik-photo-meta.php' );



// == RELATED POSTS == //

if ( PENDRELL_UBIK_RELATED )
  require_once( $path_modules . 'ubik-related.php' );



// == PLACES == //

if ( PENDRELL_UBIK_PLACES )
  require_once( $path_modules . 'ubik-places.php' );



// == RELATED POSTS == //

if ( PENDRELL_UBIK_RELATED )
  require_once( $path_modules . 'ubik-related.php' );



// == SEARCH * == //

// Reverses the order of search field and submit button; *required* for this theme
define( 'UBIK_SEARCH_FORM_REVERSE', true );
define( 'UBIK_SEARCH_POSTS_PER_PAGE', 20 );
require_once( $path_modules . 'ubik-search/ubik-search.php' );

// Add an icon to the search button
function pendrell_search_button( $contents ) {
  return pendrell_icon( 'search', $contents );
}
add_filter( 'ubik_search_button', 'pendrell_search_button' );



// == SEO == //

if ( PENDRELL_UBIK_SEO ) {
  define( 'UBIK_SEO_YOAST_DEFAULT_DESC', true );
  define( 'UBIK_SEO_YOAST_IMAGE_EXTRAS', true );
  define( 'UBIK_SEO_YOAST_IMAGE_MORE', true );
  define( 'UBIK_SEO_YOAST_NO_ADMIN_BAR', true );
  define( 'UBIK_SEO_YOAST_NO_POST_FILTER', true );
  define( 'UBIK_SEO_YOAST_PINTEREST_AUTH', true );
  define( 'UBIK_SEO_YOAST_TWITTER_CARDS', true );
  require_once( $path_modules . 'ubik-seo/ubik-seo.php' );
}



// == SERIES == //

if ( PENDRELL_UBIK_SERIES )
  require_once( $path_modules . 'ubik-series.php' );



// == SVG ICONS * == //

define( 'UBIK_SVG_ICONS_PATH', get_template_directory() . '/img/icons.svg' );
define( 'UBIK_SVG_ICONS_URL', get_template_directory_uri() . '/img/icons.svg' );
require_once( $path_modules . 'ubik-svg-icons/ubik-svg-icons.php' );

// If we have a path and no URL we probably mean to inject the SVG icon file
if ( UBIK_SVG_ICONS_PATH && UBIK_SVG_ICONS_URL === false )
  add_action( 'pendrell_body_before', 'ubik_icon_sheet_inline' );



// == TERMS * == //

define( 'UBIK_TERMS_CATEGORIZED', true ); // This blog has categories
define( 'UBIK_TERMS_TAG_SHORTCODE', true );
require_once( $path_modules . 'ubik-terms/ubik-terms.php' );

function pendrell_terms_edit_description_prompt( $content ) {
  if ( empty( $content ) )
    return '<span class="warning">' . ubik_terms_edit_description_prompt( __( 'This term description is empty.', 'pendrell' ) ) . '</span>';
  return $content;
}
add_filter( 'pendrell_archive_description_term', 'pendrell_terms_edit_description_prompt' );

function pendrell_terms_edit_link() {
  $edit_link = ubik_terms_edit_link( pendrell_icon( 'term-edit', __( 'Edit', 'pendrell' ) ), 'button edit-link' );
  if ( !empty( $edit_link ) )
    echo '<div class="entry-meta-buttons">' . $edit_link . '</div>';
}
add_action( 'pendrell_archive_header_before', 'pendrell_terms_edit_link' );

// An alias for ubik_terms_categorized()
function is_categorized() {
  return ubik_terms_categorized();
}



// == TEXT * == //

require_once( $path_modules . 'ubik-text/ubik-text.php' );
add_filter( 'the_content', 'ubik_text_replacement', 99 );
add_filter( 'the_excerpt', 'ubik_text_replacement', 99 );
add_filter( 'comment_text', 'ubik_text_replacement', 99 );



// == TIME * == //

require_once( $path_modules . 'ubik-time/ubik-time.php' );
add_filter( 'ubik_meta_timestamp_published', 'ubik_time_human' ); // Humanize these times
add_filter( 'ubik_meta_timestamp_updated', 'ubik_time_human' );



// == TITLE * == //

define( 'UBIK_TITLE_STRIP_ARCHIVES', true );
require_once( $path_modules . 'ubik-title/ubik-title.php' );



// == VIEWS * == //

//require_once( $path_modules . 'ubik-views.php' );
