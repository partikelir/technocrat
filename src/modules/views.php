<?php // ==== VIEWS ==== //

// The views module allows for template parts to be controlled by URL rewrites
// For example, to view a category in gallery mode, navigate to <website.com/category/kittens/gallery>
// By default there are two defined views, gallery and list, as well as a default 'posts' view to display content in the usual way
// @TODO: transform this into an Ubik component; presently it is too entangled with the theme to make this a simple operation
// @TODO: views with different posts per page will cause trouble when switching between them


// == DEFAULTS == //

// A global array of defined views
$pendrell_views = array(
  'gallery' => array(
    'name'    => _x( 'Gallery', 'Gallery view', 'pendrell' )
  ),
  'list'    => array(
    'name'    => _x( 'List', 'List view', 'pendrell' )
  ),
  'posts'   => array(
    'name'    => _x( 'Posts', 'Posts view', 'pendrell' )
  )
);

// This should be set in `functions-config.php`; let's default back to categories and tags if it hasn't already
if ( empty( $pendrell_views_taxonomies ) || !is_array( $pendrell_views_taxonomies ) )
  $pendrell_views_taxonomies = array( 'category', 'post_tag' );



// == SETUP == //

// Setup views; add rewrite tags and rules
if ( !function_exists( 'pendrell_view_setup' ) ) : function pendrell_view_setup() {

  global $pendrell_views, $pendrell_views_taxonomies;

  // Cycle through each view to add rewrite tags and rules
  if ( !empty( $pendrell_views ) ) {

    foreach ( $pendrell_views as $view => $data ) {
      add_rewrite_tag( '%' . $view . '%', '' ); // Regex is currently blank; if we want to capture: '([^/]+)'

      // Root rewrite rules
      add_rewrite_rule( $view . '/page/?([0-9]{1,})/?$', 'index.php?&' . $view . '=&paged=$matches[1]', 'top' );
      add_rewrite_rule( $view . '/?$', 'index.php?&' . $view . '=', 'top' );

      // Cycle through each taxonomy to apply additional rules
      if ( !empty( $pendrell_views_taxonomies ) ) {
        foreach ( $pendrell_views_taxonomies as $taxonomy ) {

          // Clear existing variables from the last run through the loop
          unset( $tax );
          unset( $tax_slug );
          unset( $tax_var );

          // Fetch the taxonomy object
          $tax = get_taxonomy( $taxonomy );

          // Only proceed if we have a match
          if ( !empty( $tax ) ) {
            $tax_slug = $tax->rewrite['slug'];
            $tax_var = $tax->query_var;

            // Add rewrite rules for taxonomy archives
            if ( !empty( $tax_slug ) && !empty( $tax_var ) ) {
              add_rewrite_rule( $tax_slug . '/(.+?)/' . $view . '/page/?([0-9]{1,})/?$', 'index.php?&' . $tax_var . '=$matches[1]&' . $view . '=&paged=$matches[2]', 'top' );
              add_rewrite_rule( $tax_slug . '/(.+?)/' . $view . '/?$', 'index.php?&' . $tax_var . '=$matches[1]&' . $view . '=', 'top' );
            }
          }
        }
      }
    }
  }
} endif;
add_action( 'init', 'pendrell_view_setup' );



// == PLUMBING == //

// A simple function to get the current view
if ( !function_exists( 'pendrell_get_view' ) ) : function pendrell_get_view() {

  global $wp_query, $pendrell_views;

  // Cycle through the views array and test whether the requisite query variable exists
  if ( !empty( $pendrell_views ) ) {

    foreach ( $pendrell_views as $view => $data ) {
      if ( isset( $wp_query->query_vars[ $view ] ) )
         $the_view = $view;
    }
  }

  // Return the view slug if found; false if not
  if ( !empty( $the_view ) )
    return $the_view;
  return false;
} endif;



// Views conditional test; is this a view and, if so, does it match the specified type?
if ( !function_exists( 'pendrell_is_view' ) ) : function pendrell_is_view( $type = '' ) {

  global $wp_query;

  // Only proceed where relevant
  if ( $wp_query->is_main_query() && ( is_archive() || is_home() ) ) {

    $view = pendrell_get_view();

    // Now test the view
    if ( !empty( $view ) ) {
      if ( !empty( $type ) ) {
        if ( $view === $type ) {
          return true; // The current view is the specified type
        } else {
          return false; // The current view is not the specified type
        }
      }
      return true; // This is a view
    }
  }
  return false; // This is not a view
} endif;



// == TEMPLATES & CLASSES == //

// Template selector based on current view
if ( !function_exists( 'pendrell_view_template_part' ) ) : function pendrell_view_template_part( $name ) {

  // Get the current view
  $view = pendrell_get_view();

  // Assign a template name unless the view is empty or not the default 'posts' view
  if ( !empty( $view ) ) {
    if ( $view !== 'posts' )
      $name = $view;
  }

  return $name;
} endif;
add_filter( 'pendrell_template_part', 'pendrell_view_template_part' );



// Add to content class array
if ( !function_exists( 'pendrell_view_body_class' ) ) : function pendrell_view_body_class( $classes ) {

  // Get the current view
  $view = pendrell_get_view();

  // Assign classes unless the view is empty or not the default 'posts' view
  if ( !empty( $view ) ) {
    if ( $view !== 'posts' )
      $classes[] = $view . '-view';
  }

  return $classes;
} endif;
add_filter( 'body_class', 'pendrell_view_body_class' );



// View content class filter; adds classes to the main content element rather than body class (for compatibility with the full-width module)
if ( !function_exists( 'pendrell_view_content_class' ) ) : function pendrell_view_content_class( $classes ) {
  if ( pendrell_is_view( 'gallery' ) )
    $classes[] = 'gallery';
  return $classes;
} endif;
add_filter( 'pendrell_content_class', 'pendrell_view_content_class' );



// == FULL-WIDTH == //

// Force certain views to be full-width
if ( !function_exists( 'pendrell_view_full_width' ) ) : function pendrell_view_full_width( $full_width_test ) {
  if ( pendrell_is_view( 'gallery' ) )
    return true;
  return $full_width_test;
} endif;
add_filter( 'pendrell_full_width', 'pendrell_view_full_width' );



// == VIEW-SPECIFIC CUSTOMIZATIONS == //

// Modify how many posts per page are displayed for different views; adapted from: http://wordpress.stackexchange.com/questions/21/show-a-different-number-of-posts-per-page-depending-on-context-e-g-homepage
if ( !function_exists( 'pendrell_view_pre_get_posts' ) ) : function pendrell_view_pre_get_posts( $query ) {
  if ( pendrell_is_view( 'gallery' ) ) {
    $query->set( 'ignore_sticky_posts', true );
    $query->set( 'posts_per_page', 18 ); // Best if this is a number divisible by both 2 and 3
  }
} endif;
add_action( 'pre_get_posts', 'pendrell_view_pre_get_posts' );



// Modify entry meta format on list view
if ( !function_exists( 'pendrell_view_date_format' ) ) : function pendrell_view_date_format( $format ) {
  if ( pendrell_is_view( 'gallery' ) )
    $format = _x( 'M Y', 'gallery view date format', 'pendrell' );
  if ( pendrell_is_view( 'list' ) )
    $format = _x( 'F jS, Y', 'list view date format', 'pendrell' );
  return $format;
} endif;
add_filter( 'ubik_time_human_format', 'pendrell_view_date_format' );
add_filter( 'ubik_meta_full_format', 'pendrell_view_date_format' );



// Entry meta for list view, called directly from the template
if ( !function_exists( 'pendrell_entry_meta_list_view' ) ) : function pendrell_entry_meta_list_view() {
  $date = ubik_meta_date();
  echo strip_tags( $date[0], '<span><time>' ); // Publication date with any potential links stripped
} endif;



// == VIEW MAPPING == //

// Assign default views to different taxonomy archives in `functions-config.php`
if ( !function_exists( 'pendrell_view_mapping' ) ) : function pendrell_view_mapping() {

  global $pendrell_views_map;

  // Dual loops to cycle through the default assignments
  if ( !empty( $pendrell_views_map ) && is_array( $pendrell_views_map ) ) {
    foreach ( $pendrell_views_map as $taxonomy => $term_set ) {
      if ( !empty( $term_set ) ) {
        foreach ( $term_set as $term => $view ) {

          // This part is due to yet another WordPress idiosyncrasy: is_tax() doesn't work on categories or tags
          if ( $taxonomy === 'category' ) {
            if ( is_category( $term ) )
              set_query_var( $view, '' );
          } elseif ( $taxonomy === 'post_tag' ) {
            if ( is_tag( $term ) )
              set_query_var( $view, '' );
          } elseif ( is_tax( $taxonomy, $term ) ) {
            set_query_var( $view, '' );
          }
        }
      }
    }
  }
} endif;
add_action( 'parse_query', 'pendrell_view_mapping' );



// == RESPONSIVE IMAGES == //

// The views module introduces a special case for the responsive images module
// Refer to `src/modules/responsive-images.php` for a much more detailed explanation of what is going on here
if ( !function_exists( 'pendrell_views_sizes_media_queries' ) ) : function pendrell_views_sizes_media_queries( $queries = array(), $size = '', $width = '' ) {

  // Exit early if we don't have the required size and width data
  if ( empty( $size ) || !is_string( $size ) || empty( $width ) || !is_int( $width ) || !pendrell_is_view( 'gallery' ) )
    return;

  // Reset queries array
  $queries = array();

  global $content_width, $main_width;

  // The margins can be filtered; this is mostly in case the inner margin (the space between grouped images) is not the same as the page margins
  // Example: your outer page margins are 30px on each side but the spacing between images is 20px
  $margin       = (int) apply_filters( 'pendrell_sizes_margin', PENDRELL_BASELINE );
  $margin_inner = (int) apply_filters( 'pendrell_sizes_margin_inner', PENDRELL_BASELINE );

  // Breakpoints replicated from `src/scss/_base_config.scss`
  $b_small = ceil( $main_width/1.2 ); // 520px
  $b_medium = $main_width + ( $margin * 3 ); // 714px

  // The topmost media query specifies the minimum viewport width at which an image is *fixed* in size (not fluid)
  $queries[] = '(min-width: ' . ( $content_width + ( $margin * 3 ) ) . 'px) ' . $width . 'px';

  // Above $b_medium there is a step up from 2x to 3x page margins
  $queries[] = '(min-width: ' . $b_medium . 'px) calc(' . round( ( 1 / 3 - ( ( ( $margin_inner * 2 ) / $content_width ) ) / 3 ) * 100, 5 ) . 'vw - ' . round( ( $margin * 3 ) / 3, 5 ) . 'px)';

  // Above here it's a three column layout but page margins are still 2x
  $queries[] = '(min-width: ' . ( 600 + $margin_inner + ( $margin * 2 ) ) . 'px) calc(' . round( ( 1 / 3 - ( ( ( $margin_inner * 2 ) / $content_width ) ) / 3 ) * 100, 5 ) . 'vw - ' . round( ( $margin * 2 ) / 3, 5 ) . 'px)';

  // Above $b_small it's a two column layout and the page margins are 2x
  $queries[] = '(min-width: ' . $b_small . 'px) calc(' . round( ( 1 / 2 - ( ( $margin_inner / $content_width ) ) / 2 ) * 100, 5 ) . 'vw - ' . round( ( $margin * 2 ) / 2, 5 ) . 'px)';

  // Above here it's a two column layout and the page margins are 1x
  $queries[] = '(min-width: ' . ( 300 + $margin ) . 'px) calc(' . round( ( 1 / 2 - ( ( $margin_inner / $content_width ) ) / 2 ) * 100, 5 ) . 'vw - ' . round( $margin / 2, 5 ) . 'px)';

  return array( $queries );

} endif;
if ( PENDRELL_MODULE_RESPONSIVE )
  add_filter( 'ubik_imagery_sizes_media_queries', 'pendrell_views_sizes_media_queries', 11, 3 );



// Handle default `sizes` attribute for gallery view
if ( !function_exists( 'pendrell_views_sizes_default' ) ) : function pendrell_views_sizes_default( $default = '', $size = '', $width = '' ) {

  // Gallery view has a responsive layout that slims down to a single column at the smallest breakpoint
  if ( pendrell_is_view( 'gallery' ) )
    $default = 'calc(100vw - ' . PENDRELL_BASELINE . 'px)';

  return $default;
} endif;
if ( PENDRELL_MODULE_RESPONSIVE )
  add_filter( 'ubik_imagery_sizes_default', 'pendrell_views_sizes_default', 11, 3 );



// == VIEW SWITCHER == //

// A way to switch between different views
// @filter: pendrell_view_switcher_display
if ( !function_exists( 'pendrell_view_switcher' ) ) : function pendrell_view_switcher() {

  // Allow this function to be filtered
  $display = apply_filters( 'pendrell_view_switcher_display', true );

  // Exit early if there is no need to display these options
  if ( $display === false || ( !is_home() && !is_category() && !is_tag() && !is_tax( $pendrell_views_taxonomies ) ) )
    return;

  global $pendrell_views, $pendrell_views_taxonomies, $wp;

  // Fetch the current view
  $this_view = pendrell_get_view();

  // If view isn't set then we're looking at a standard 'posts' view
  if ( empty( $this_view ) )
    $this_view = 'posts';

  // Get current URL
  $link = trailingslashit( home_url( $wp->request ) );

  // Create a patterns array containing all views and the page parameter (which we'll restore later on)
  $patterns = array_merge( array_keys( $pendrell_views ), array( 'page' ) );

  // Cycle through the views and trim the URL as needed
  foreach ( $patterns as $pattern ) {
    if ( strpos( $link, $pattern ) !== false ) {
      $link = substr( $link, 0, strpos( $link, $pattern ) );
    }
  }

  // Get current page, if any
  $paged = ( get_query_var( 'paged' ) > 1 ) ? get_query_var( 'paged' ) : 1;
  if ( $paged > 1 ) {
    $page = 'page/' . $paged . '/';
  } else {
    $page = '';
  }

  // Initialize output
  $output = '';

  // Loop through the views and construct the list
  foreach ( $pendrell_views as $view => $data ) {
    if ( $view !== $this_view ) {
      $output .= '<li><a href="' . $link . $view . '/' . $page . '">' . $data['name'] . '</a></li>';
    }
  }

  // Output wrapper
  $output = "\n" . '<nav class="view-switcher">' . "\n" . '<ul>' . "\n" . $output . '</ul>' . "\n" . '</nav>' . "\n";

  echo $output;
} endif;
add_action( 'pendrell_archive_description_before', 'pendrell_view_switcher', 9 );
