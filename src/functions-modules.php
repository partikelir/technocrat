<?php // ==== UBIK ==== //

// This file is an adapter for the Ubik suite of components; it contains all the little scraps of code to integrate Ubik into Pendrell

// For testing purposes only; switches everything on for development
if ( WP_LOCAL_DEV ) {
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
defined( 'PENDRELL_UBIK_ADMIN' )          || define( 'PENDRELL_UBIK_ADMIN', false );
defined( 'PENDRELL_UBIK_ANALYTICS' )      || define( 'PENDRELL_UBIK_ANALYTICS', false );
defined( 'PENDRELL_UBIK_EXCLUDER' )       || define( 'PENDRELL_UBIK_EXCLUDER', false );
defined( 'PENDRELL_UBIK_FEED' )           || define( 'PENDRELL_UBIK_FEED', false );
defined( 'PENDRELL_UBIK_LINGUAL' )        || define( 'PENDRELL_UBIK_LINGUAL', false );
defined( 'PENDRELL_UBIK_LINKS' )          || define( 'PENDRELL_UBIK_LINKS', false );
defined( 'PENDRELL_UBIK_MARKDOWN' )       || define( 'PENDRELL_UBIK_MARKDOWN', false );
defined( 'PENDRELL_UBIK_PHOTO_META' )     || define( 'PENDRELL_UBIK_PHOTO_META', false );
defined( 'PENDRELL_UBIK_PLACES' )         || define( 'PENDRELL_UBIK_PLACES', false );
defined( 'PENDRELL_UBIK_QUICK_TERMS' )    || define( 'PENDRELL_UBIK_QUICK_TERMS', false );
defined( 'PENDRELL_UBIK_RECORDPRESS' )    || define( 'PENDRELL_UBIK_RECORDPRESS', false );
defined( 'PENDRELL_UBIK_RELATED' )        || define( 'PENDRELL_UBIK_RELATED', false );
defined( 'PENDRELL_UBIK_SEO' )            || define( 'PENDRELL_UBIK_SEO', false );
defined( 'PENDRELL_UBIK_SERIES' )         || define( 'PENDRELL_UBIK_SERIES', false );

// Dependent on the Pendrell core post formats switch
if ( PENDRELL_POST_FORMATS )
  defined( 'PENDRELL_UBIK_POST_FORMATS' )   || define( 'PENDRELL_UBIK_POST_FORMATS', true );

// Modules path; a shortcut for use below
$path_modules = trailingslashit( get_stylesheet_directory() ) . 'modules/';



// == ADMIN == //

if ( is_admin() ) {
  if ( PENDRELL_UBIK_ADMIN )
    require_once( $path_modules . 'ubik-admin/ubik-admin.php' );
  if ( PENDRELL_UBIK_QUICK_TERMS )
    require_once( $path_modules . 'ubik-quick-terms/ubik-quick-terms.php' );
}



// == ANALYTICS == //

if ( PENDRELL_UBIK_ANALYTICS )
  require_once( $path_modules . 'ubik-analytics/ubik-analytics.php' );



// == CLEANER * == //

define( 'UBIK_CLEANER_REMOVE_EMOJI', true );
define( 'UBIK_CLEANER_REMOVE_MIGRATE', true );
define( 'UBIK_CLEANER_REMOVE_OPEN_SANS', true );
define( 'UBIK_CLEANER_STYLE_TEMPLATES', true );
require_once( $path_modules . 'ubik-cleaner/ubik-cleaner.php' );



// == COLOPHON * == //

require_once( $path_modules . 'ubik-colophon/ubik-colophon.php' );

// Output the colophon
function pendrell_colophon() {
  $colophon = ubik_colophon();
  if ( !empty( $colophon ) )
    echo '<div class="site-footer-info">' . $colophon . '</div>';
}
add_action( 'pendrell_footer', 'pendrell_colophon' );



// == COMMENTS * == //

define( 'UBIK_COMMENTS_ALLOWED_TAGS', true );
define( 'UBIK_COMMENTS_ATTACHMENTS_OFF', true );
//define( 'UBIK_COMMENTS_LINK_SHOW_NONE', true );
define( 'UBIK_COMMENTS_PINGBACKS_OFF', true );
require_once( $path_modules . 'ubik-comments/ubik-comments.php' );



// == EXCERPTS * == //

define( 'UBIK_EXCERPT_MORE_LINK', true );
define( 'UBIK_EXCERPT_PAGES', true );
require_once( $path_modules . 'ubik-excerpt/ubik-excerpt.php' );



// == EXCLUDER == //

if ( PENDRELL_UBIK_EXCLUDER )
  require_once( $path_modules . 'ubik-excluder/ubik-excluder.php' );



// == FEED == //

if ( PENDRELL_UBIK_FEED ) {
  if ( PENDRELL_POST_FORMATS )
    define( 'UBIK_FEED_EXCLUDE_FORMATS', true );
  require_once( $path_modules . 'ubik-feed/ubik-feed.php' );
}



// == FONTS == * //

define( 'UBIK_FONTS_GOOGLE', 'Raleway:200,300,600|Oxygen:300,300italic,400,400italic,600,600italic' );
require_once( $path_modules . 'ubik-fonts/ubik-fonts.php' );



// == FULL-WIDTH * == //

// Force full-width categories and/or tags (or anything else you can dream up, really)
function pendrell_full_width_content( $full_width ) {
  if ( $full_width === true )
    return $full_width;

  // Insert conditional checks here! Test for category archives, the presence of specific tags, etc.

  return $full_width;
}
add_filter( 'pendrell_full_width', 'pendrell_full_width_content' );



// == IMAGERY * == //

// Enable `srcset` output only when Picturefill module is active
if ( PENDRELL_RESPONSIVE_IMAGES )
  define( 'UBIK_IMAGERY_SRCSET', true );
define( 'UBIK_IMAGERY_DIMENSIONS', false );
require_once( $path_modules . 'ubik-imagery.php' );



// == LINGUAL == //

if ( PENDRELL_UBIK_LINGUAL ) {
  define( 'UBIK_LINGUAL_SANITIZE_SLUG', true );
  require_once( $path_modules . 'ubik-lingual/ubik-lingual.php' );
  add_filter( 'the_title', 'ubik_lingual_pinyin_strip_marks' ); // Content titles
  add_filter( 'ubik_title', 'ubik_lingual_pinyin_strip_marks' ); // Page titles
  if ( PENDRELL_UBIK_PLACES )
    add_filter( 'ubik_places_title', 'ubik_lingual_pinyin_strip_marks' ); // Small header in the faux widget
}



// == LINKS == //

if ( PENDRELL_UBIK_LINKS ) {
  define( 'UBIK_LINKS_PAGE_TEMPLATE', 'page-templates/links.php' );
  define( 'UBIK_LINKS_SEARCH_FORM_REVERSE', true );
  require_once( $path_modules . 'ubik-links.php' );
  add_filter( 'ubik_links_search_button', 'pendrell_icon_search_button' );
}



// == MARKDOWN == //

if ( PENDRELL_UBIK_MARKDOWN ) {
  define( 'UBIK_MARKDOWN_TERM_DESCRIPTION', true );
  define( 'UBIK_MARKDOWN_WIDGET_TEXT', true );
  require_once( $path_modules . 'ubik-markdown/ubik-markdown.php' );
  add_filter( 'get_the_author_description', 'ubik_markdown_transform' ); // See also: ./core/author.php
  add_filter( 'ubik_imagery_caption_pre', 'ubik_markdown_transform' );
  add_filter( 'get_the_excerpt', 'ubik_markdown_transform' ); // This comes up when generated meta descriptions from attachment captions
}



// == META * == //

define('UBIK_META_DATE_HUMANIZE', true);
require_once( $path_modules . 'ubik-meta/ubik-meta.php' );
add_filter( 'ubik_meta_date_grace_period', '__return_true' );
add_filter( 'ubik_meta_microformats', '__return_true' );



// == PHOTO META == //

if ( PENDRELL_UBIK_PHOTO_META )
  require_once( $path_modules . 'ubik-photo-meta.php' );



// == PLACES == //

if ( PENDRELL_UBIK_PLACES )
  require_once( $path_modules . 'ubik-places.php' );



// == POST FORMATS == //

if ( PENDRELL_UBIK_POST_FORMATS && PENDRELL_POST_FORMATS )
  require_once( $path_modules . 'ubik-post-formats/ubik-post-formats.php' );



// == RECORDPRESS == //

if ( PENDRELL_UBIK_RECORDPRESS )
  require_once( $path_modules . 'ubik-recordpress/ubik-recordpress.php' );



// == RELATED POSTS == //

if ( PENDRELL_UBIK_RELATED )
  require_once( $path_modules . 'ubik-related.php' );



// == SEARCH * == //

// Reverses the order of search field and submit button; *required* for this theme
define( 'UBIK_SEARCH_FORM_REVERSE', true );
define( 'UBIK_SEARCH_POSTS_PER_PAGE', 20 );
require_once( $path_modules . 'ubik-search/ubik-search.php' );
add_filter( 'ubik_search_button', 'pendrell_icon_search_button' );



// == SEO == //

if ( PENDRELL_UBIK_SEO ) {
  define( 'UBIK_SEO_YOAST_DEFAULT_DESC', true );
  define( 'UBIK_SEO_YOAST_OG', true );
  define( 'UBIK_SEO_YOAST_NO_ADMIN_BAR', true );
  define( 'UBIK_SEO_YOAST_NO_POST_FILTER', true );
  define( 'UBIK_SEO_YOAST_PINTEREST_AUTH', true );
  define( 'UBIK_SEO_YOAST_TITLE_CLEANER', true );
  require_once( $path_modules . 'ubik-seo/ubik-seo.php' );
}



// == SERIES == //

if ( PENDRELL_UBIK_SERIES )
  require_once( $path_modules . 'ubik-series.php' );



// == SVG ICONS * == //

$icons_path = '/img/icons.svg';
$icons_url = $icons_path . '?v=' . filemtime( get_template_directory() . $icons_path ); // Cache busting icons!
define( 'UBIK_SVG_ICONS_PATH', get_template_directory() . $icons_path );
define( 'UBIK_SVG_ICONS_URL', get_template_directory_uri() . $icons_url );
require_once( $path_modules . 'ubik-svg-icons/ubik-svg-icons.php' );

// If we have a path and no URL we probably mean to inject the SVG icon file
if ( UBIK_SVG_ICONS_PATH && UBIK_SVG_ICONS_URL === false )
  add_action( 'pendrell_body_before', 'ubik_svg_icon_sheet_inline' );



// == TERMS * == //

//define( 'UBIK_TERMS_CATEGORIZED', true ); // This blog has categories
define( 'UBIK_TERMS_TAG_SHORTCODE', true );
require_once( $path_modules . 'ubik-terms/ubik-terms.php' );

function pendrell_terms_edit_description_prompt( $content ) {
  if ( empty( $content ) )
    return '<span class="warning">' . ubik_terms_edit_description_prompt( __( 'This term description is empty.', 'pendrell' ) ) . '</span>';
  return $content;
}
add_filter( 'get_the_archive_description', 'pendrell_terms_edit_description_prompt' );

function pendrell_terms_edit_link( $buttons ) {
  $edit_link = ubik_terms_edit_link( pendrell_icon_text( 'term-edit', __( 'Edit', 'pendrell' ) ), 'button edit-link' );
  if ( !empty( $edit_link ) )
    $buttons .= $edit_link;
  return $buttons;
}
add_action( 'pendrell_archive_buttons', 'pendrell_terms_edit_link' );

// Don't display categories if the blog isn't categorized
if ( !ubik_terms_categorized() )
  add_filter( 'ubik_meta_categories', '__return_empty_string' );



// == TEXT * == //

require_once( $path_modules . 'ubik-text/ubik-text.php' );
//add_filter( 'the_content', 'ubik_text_replace', 99 ); // Commented out as simple search and replace doesn't always yield desireable results
//add_filter( 'the_excerpt', 'ubik_text_replace', 99 );
//add_filter( 'comment_text', 'ubik_text_replace', 99 );
add_filter( 'the_content_feed', 'ubik_text_strip_asides' );
add_filter( 'the_excerpt_rss', 'ubik_text_strip_asides' );
add_filter( 'the_content', 'ubik_text_strip_more_orphan', 99 ); // Strip paragraph tags from orphaned more tags

// An example showing how to filter the text replacement array
function pendrell_text_replace( $array ) {
  $array['dividers']['<p>*</p>'] = '<p class="divider floral-heart">&#x2766;</p>'; // Floral hearts are rad
  return $array;
}
add_filter( 'ubik_text_replace_simple', 'pendrell_text_replace' );



// == TIME * == //

require_once( $path_modules . 'ubik-time/ubik-time.php' );
add_filter( 'ubik_time_human_diff_case', 'ubik_time_human_diff_case_lower' );



// == TITLE * == //

define( 'UBIK_TITLE_DOCUMENT', true );
define( 'UBIK_TITLE_STRIP_ARCHIVES', true );
require_once( $path_modules . 'ubik-title/ubik-title.php' );



// == VIEWS * == //

require_once( $path_modules . 'ubik-views.php' );
