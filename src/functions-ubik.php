<?php // ==== UBIK ==== //

// This file is an adapter for the Ubik suite of components; it contains all the little scraps of code to integrate Ubik into Pendrell

// ==== CONFIGURATION ==== //

// Nothing by default...



// ==== COMPONENTS ==== //

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



// == EXCERPTS * == //

require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-excerpt/ubik-excerpt.php' );



// == EXCLUDER == //

if ( PENDRELL_UBIK_EXCLUDER )
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-excluder/ubik-excluder.php' );



// == FAVICONS * == //

require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-favicons/ubik-favicons.php' );



// == FEED == //

if ( PENDRELL_UBIK_FEED )
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-feed/ubik-feed.php' ); // @TODO: image size management in feeds



// == IMAGERY * == //

// Enable `srcset` output only when Picturefill module is active
if ( PENDRELL_MODULE_RESPONSIVE )
  define( 'UBIK_IMAGERY_SRCSET', true );

require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-imagery/ubik-imagery.php' );



// == LINGUAL == //

if ( PENDRELL_UBIK_LINGUAL ) {
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-lingual/ubik-lingual.php' );
  add_filter( 'ubik_title', 'ubik_lingual_unpinyin' );
  if ( PENDRELL_UBIK_PLACES )
    add_filter( 'ubik_places_title', 'ubik_lingual_unpinyin' ); // Small header in the faux widget
}



// == LINKS == //

if ( PENDRELL_UBIK_LINKS ) {
  define( 'UBIK_LINKS_PAGE_TEMPLATE', 'page-templates/links.php' );
  define( 'UBIK_LINKS_SEARCH_FORM_REVERSE', true );
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-links/ubik-links.php' );

  // Display the Ubik Links sidebar
  function pendrell_sidebar_links( $sidebar ) {
    if ( is_page_template( UBIK_LINKS_PAGE_TEMPLATE ) ) {

      // Retrieve the list of all categories
      $cats = ubik_links_categories();

      // Add the links page template to the bottom of the list (relies on `get_permalink`)
      $cats[] = '<strong><a class="link-category" href="' . get_permalink() . '">' . __( 'All links', 'pendrell' ) . '</a></strong>';
      $cats = ubik_links_categories_list( $cats );

      // Output the links sidebar
      ?><div id="wrap-sidebar" class="wrap-sidebar">
        <div id="secondary" class="widget-area" role="complementary">
          <aside id="ubik-links-search-widget" class="widget widget-links-search">
            <h2>Search links</h2>
            <?php echo ubik_links_search_form(); ?>
          </aside>
          <?php if ( !empty( $cats ) ) { ?>
          <aside id="ubik-links-categories-widget" class="widget widget-links-categories">
            <h2>Links categories</h2>
            <?php echo $cats; ?>
          </aside>
          <?php } ?>
        </div>
      </div><?php

      // Return false to prevent the display of the regular sidebar
      $sidebar = false;
    }
    return $sidebar;
  }
  add_filter( 'pendrell_sidebar', 'pendrell_sidebar_links' );
}



// == MARKDOWN == //

if ( PENDRELL_UBIK_MARKDOWN )
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-markdown/ubik-markdown.php' );



// == META * == //

require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-meta/ubik-meta.php' );



// == PLACES == //

if ( PENDRELL_UBIK_PLACES ) {
  define( 'PENDRELL_PLACES_TEMPLATE_ID', 5951 );
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-places/ubik-places.php' );
  add_action( 'pendrell_archive_description_before', 'ubik_places_breadcrumb' );

  // Hack to manually set thumbnails for the places page template
  global $pendrell_places_thumbs;
  $pendrell_places_thumbs = array(
    '239' => '365', // Canada
    '245' => '1670', // Hong Kong
    '337' => '1983', // Japan
    '243' => '3210', // Malaysia
    '470' => '3591', // Singapore
    '246' => '554', // South Korea
    '180' => '5077', // Taiwan
    '244' => '748', // Thailand
    '561' => '4713' // U.S.A.
  );

  // Display the Ubik Places sidebar
  function pendrell_sidebar_places( $sidebar ) {
    if ( is_tax( 'places' ) && !pendrell_is_full_width() ) {

      // Retrieve data from Ubik Places
      $places = ubik_places_list();

      // Only output places widget markup if we have results; @TODO: turn this into a real widget
      if ( !empty( $places ) ) {
        ?><div id="wrap-sidebar" class="wrap-sidebar">
          <div id="secondary" class="widget-area" role="complementary">
            <aside id="ubik-places" class="widget">
              <?php if ( !empty( $places ) ) {
                foreach ( $places as $key => $place ) {
                  $places_index = ''; // A simple hack to insert a link to the places index page
                  if ( $key === ( count( $places ) - 1 ) && PENDRELL_PLACES_TEMPLATE_ID )
                    $places_index = '<li class="cat-item"><strong><a href="' . get_permalink( PENDRELL_PLACES_TEMPLATE_ID ) . '">' . __( 'All places', 'pendrell' ) . '</a></strong></li>';
                  ?><h2><?php echo $place['title']; ?></h2>
                  <ul class="place-list">
                    <?php echo $places_index; echo wp_list_categories( $place['args'] ); ?>
                  </ul><?php
                }
              } ?>
            </aside>
          </div>
        </div><?php
      }

      // Return false to prevent the regular sidebar from displaying
      $sidebar = false;
    }
    return $sidebar;
  }
  add_filter( 'pendrell_sidebar', 'pendrell_sidebar_places' );

  // Adds places to entry metadata right after other taxonomies; @DEPENDENCY: relies on popular terms function in Ubik core
  function pendrell_places_meta( $meta ) {
    global $post;
    if ( has_term( '', 'places' ) )
      $meta .= ubik_popular_terms_list( $post->ID, 'places', 'Places: ', ', ', '. ' );
    return $meta;
  }
  add_filter( 'ubik_meta_taxonomies', 'pendrell_places_meta' );

  // Body class filter
  function pendrell_places_body_class( $classes ) {
    if ( is_page_template( 'page-templates/places.php' ) )
      $classes[] = 'gallery-view';
    return $classes;
  }
  add_filter( 'body_class', 'pendrell_places_body_class' );

  // Force places base template to be full-width
  function pendrell_places_full_width( $test ) {
    if ( is_page_template( 'page-templates/places.php' ) )
      return true;
    return $test;
  }
  add_filter( 'pendrell_full_width', 'pendrell_places_full_width' );

  // Adds place descriptions to the quick edit box
  if ( PENDRELL_UBIK_QUICK_TERMS ) {
    function pendrell_places_quick_terms( $taxonomies ) {
      $taxonomies[] = 'places';
      return $taxonomies;
    }
    add_filter( 'ubik_quick_terms_taxonomies', 'pendrell_places_quick_terms' );
  }
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

  // Related posts display switch
  function pendrell_related_display( $switch = true ) {
    //if ( has_tag( array( 'this', 'that', 'the-other-thing' ) ) )
      //return false;
    return (bool) $switch;
  }
  add_filter( 'pendrell_related_display', 'pendrell_related_display' );
}



// == SEO == //

if ( PENDRELL_UBIK_SEO )
  require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-seo/ubik-seo.php' );



// == SEARCH * == //

// Reverses the order of search field and submit button; *required* for this theme
define( 'UBIK_SEARCH_FORM_REVERSE', true );
define( 'UBIK_SEARCH_POSTS_PER_PAGE', 20 );
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



// == TERMS * == //

define( 'UBIK_TERMS_TAG_SHORTCODE', true );
require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-terms/ubik-terms.php' );
add_filter( 'pendrell_archive_description_term', 'ubik_terms_edit_description_prompt' );
add_filter( 'pendrell_archive_description_term', 'ubik_terms_edit_link' );



// == TIME * == //

require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-time/ubik-time.php' );
add_filter( 'ubik_meta_timestamp_published', 'ubik_time_human' ); // Humanize these times
add_filter( 'ubik_meta_timestamp_updated', 'ubik_time_human' );



// == TITLE * == //

require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-title/ubik-title.php' );
