<?php // ==== VIEWS ==== //

// == CONDITIONALS == //

// Views conditional test; is this a view and, if so, does it match the type supplied?
if ( !function_exists( 'pendrell_is_view' ) ) : function pendrell_is_view( $type = '' ) {
  $view = get_query_var( 'view' );
  if ( is_main_query() && ( is_archive() || is_front_page() || is_home() || is_search() ) ) {
    if ( !empty( $view ) ) {
      if ( !empty( $type ) ) {
        if ( $view === $type ) {
          return true; // This is the specified type
        } else {
          return false; // This is a view but it is not the specified type
        }
      }
      return true; // This is a view
    }
  }
  return false; // This is not a view
} endif;



if ( !function_exists( 'pendrell_is_view_default' ) ) : function pendrell_is_view_default() {

  // Test the categories and tags set in functions-config.php
  global $pendrell_default_view_cats, $pendrell_default_view_tags;
  if ( is_array( $pendrell_default_view_cats ) ) {
    foreach ( $pendrell_default_view_cats as $cats => $view ) {
      if ( is_category( $cats ) )
        return true;
    }
  }
  if ( is_array( $pendrell_default_view_tags ) ) {
    foreach ( $pendrell_default_view_tags as $tags => $view ) {
      if ( is_tag( $tags ) )
        return true;
    }
  }
  return false; // No default view has been set
} endif;



// == QUERY VARIABLES == //

// Add the view query var
if ( !function_exists( 'pendrell_view_query_var' ) ) : function pendrell_view_query_var( $vars ) {
  $vars[] = "view";
  return $vars;
} endif;
add_filter( 'query_vars', 'pendrell_view_query_var' );



// Set default views under different conditions
if ( !function_exists( 'pendrell_view_default' ) ) : function pendrell_view_default() {

  // Set search results
  if ( is_search() && !pendrell_is_view() ) {
    set_query_var( 'view', 'excerpt' );
  }

  // Test the categories and tags set in functions-config.php
  global $pendrell_default_view_cats, $pendrell_default_view_tags;
  if ( is_array( $pendrell_default_view_cats ) ) {
    foreach ( $pendrell_default_view_cats as $cats => $view ) {
      if ( is_category( $cats ) && !pendrell_is_view() )
        set_query_var( 'view', $view );
    }
  }
  if ( is_array( $pendrell_default_view_tags ) ) {
    foreach ( $pendrell_default_view_tags as $tags => $view ) {
      if ( is_tag( $tags ) && !pendrell_is_view() )
        set_query_var( 'view', $view );
    }
  }

} endif;
add_action( 'parse_query', 'pendrell_view_default' );



// Modify how many posts per page are displayed for different views; adapted from: http://wordpress.stackexchange.com/questions/21/show-a-different-number-of-posts-per-page-depending-on-context-e-g-homepage
if ( !function_exists( 'pendrell_view_pre_get_posts' ) ) : function pendrell_view_pre_get_posts( $query ) {
  if ( pendrell_is_view( 'gallery' ) ) {
    $query->set( 'ignore_sticky_posts', true );
    $query->set( 'posts_per_page', 18 ); // Best if this is a number divisible by both 2 and 3
  }
} endif;
add_action( 'pre_get_posts', 'pendrell_view_pre_get_posts' );



// == TEMPLATES & CLASSES == //

// Swap templates as needed based on query var
if ( !function_exists( 'pendrell_view_template' ) ) : function pendrell_view_template( $template ) {
  if ( pendrell_is_view( 'excerpt' ) )
    $template = 'excerpt';
  if ( pendrell_is_view( 'gallery' ) )
    $template = 'gallery';
  if ( pendrell_is_view( 'list' ) )
    $template = 'list';
  return $template;
} endif;
add_filter( 'pendrell_content_template', 'pendrell_view_template' );



// Add to content class array
if ( !function_exists( 'pendrell_view_body_class' ) ) : function pendrell_view_body_class( $classes ) {
  if ( pendrell_is_view( 'excerpt' ) )
    $classes[] = 'excerpt-view';
  if ( pendrell_is_view( 'gallery' ) )
    $classes[] = 'gallery-view';
  if ( pendrell_is_view( 'list' ) )
    $classes[] = 'list-view';
  return $classes;
} endif;
add_filter( 'body_class', 'pendrell_view_body_class' );



// View content class filter; adds classes to the main content element rather than body class (for compatibility with the full-width module)
if ( !function_exists( 'pendrell_view_content_class' ) ) : function pendrell_view_content_class( $classes ) {
  if ( pendrell_is_view( 'gallery' ) )
    $classes[] = 'img-group';
  return $classes;
} endif;
add_filter( 'pendrell_content_class', 'pendrell_view_content_class' );



// == FULL-WIDTH == //

// Force certain views to be full-width
if ( !function_exists( 'pendrell_view_full_width' ) ) : function pendrell_view_full_width( $full_width_test ) {

  // Return immediately if the test has already been passed
  if ( $full_width_test === true )
    return $full_width_test;

  // Test the view
  if ( pendrell_is_view( 'gallery' ) )
    return true;

  // Otherwise return false
  return false;
} endif;
add_filter( 'pendrell_full_width', 'pendrell_view_full_width' );



// == INTERFACE == //

// View switch prototype
if ( !function_exists( 'pendrell_view_switch' ) ) : function pendrell_view_switch() {

  global $pendrell_default_view_cats, $pendrell_default_view_tags;

  // Do we want to display this function here?
  if (
    !is_category( array_keys( $pendrell_default_view_cats ) ) &&
    !is_tag( array_keys( $pendrell_default_view_tags ) )
  )
    return;

  // Check the current view
  $current = get_query_var( 'view' );

  // If view isn't set then we're looking at a standard view
  if ( empty( $current ) )
    $current = 'standard';

  // Has a default view been set?
  if ( pendrell_is_view_default() ) {
    $current_url = add_query_arg( 'view', 'standard' );
  } else {
    $current_url = remove_query_arg( 'view' );
  }

  // Setup views
  $views = array(
    array(
      'name' => 'standard',
      'text' => __( 'Standard', 'pendrell' ),
      'icon' => 'standard',
      'url'  =>  $current_url
    ),
    array(
      'name' => 'gallery',
      'text' => __( 'Gallery', 'pendrell' ),
      'icon' => 'gallery-view',
      'url'  => add_query_arg( 'view', 'gallery' )
    ),
    array(
      'name' => 'list',
      'text' => __( 'List', 'pendrell' ),
      'icon' => 'list-view',
      'url'  => add_query_arg( 'view', 'list' )
    ),
  );

  // Not used, kept for reference
  $views_more = array(
     array(
      'name' => 'excerpt',
      'text' => __( 'Excerpt', 'pendrell' ),
      'icon' => 'excerpt-view',
      'url'  => add_query_arg( 'view', 'excerpt' )
    )
  );

  $output = '';

  // Determine how many options we need to display
  $count = count( $views );
  foreach ( $views as $view ) {
    if ( $view['name'] == $current )
      $count--;
  }

  // Only one option? Make it a simple link button
  if ( $count === 1 ) {

    foreach ( $views as $view ) {
      if ( $view['name'] != $current ) {
        $output .= '<div class="button ' . $view['name'] . '-view-option"><a href="' . $view['url'] . '"><span class="' . $view['icon'] . '"></span> ' . $view['text'] . '</a></div>' . "\n";
      }
    }

    // Scaffolding for the link button
    $output = '<div class="view-options">' . $output . '</div>' . "\n";

  // More than one option? Iterate through view array to create a list of options for the current page
  } elseif ( $count > 1 ) {

    foreach ( $views as $view ) {
      if ( $view['name'] != $current ) {
        $output .= '<li class="' . $view['name'] . '-view-option"><a href="' . $view['url'] . '"><span class="' . $view['icon'] . '"></span> ' . $view['text'] . '</a></li>' . "\n";
      }
    }

    // Scaffolding for the dropdown button
    $output = '<div class="view-options"><button>View<ul class="button-dropdown">' . $output . '</ul></button></div>' . "\n";
  }

  echo $output;

} endif;
add_action( 'pendrell_archive_header_before', 'pendrell_view_switch' );



// == VIEW POSTS SHORTCODE == //

// View posts shortcode; outputs content from within posts and pages using view template functionality
// Use cases: list children of current page; show artist discographies; etc.
// Example: [view-posts view="gallery" mode="children" title="Profiles"]Here are some profiles related to this page...[/view-posts]
// Example: [view-posts mode="tagged" title="Releases"]The full discography of artist X.[/view-posts]
// @TODO: this function needs a lot of work; this prototype is sufficient for my needs as of now but it should be expanded...
// For some ideas, consider: http://plugins.svn.wordpress.org/display-posts-shortcode/tags/2.4/display-posts-shortcode.php
if ( !function_exists( 'pendrell_view_posts_shortcode' ) ) : function pendrell_view_posts_shortcode( $atts, $content = null ) {
  $args = shortcode_atts( array(
    'id'        => ''
  , 'mode'      => ''
  , 'taxonomy'  => ''
  , 'title'     => ''
  , 'view'      => 'list'
  ), $atts );

  $id       = (int) $args['id'];
  $mode     = (string) $args['mode'];
  $taxonomy = (string) $args['taxonomy'];
  $title    = (string) $args['title'];
  $view     = (string) $args['view'];

  if ( empty( $mode ) && is_page() )
    $mode = 'children';

  if ( !in_array( $view, array( 'excerpt', 'gallery', 'list' ) ) ) // No standard!
    $view = 'list';

  $posts = pendrell_view_posts( $id, $view, $mode, $taxonomy, $title, $content );

  return $posts;
} endif;
add_shortcode( 'view-posts', 'pendrell_view_posts_shortcode' );



// Generate HTML for the view-posts shortcode; @TODO: make this more customizable; currently it's very limited to just a few modes
if ( !function_exists( 'pendrell_view_posts' ) ) : function pendrell_view_posts( $id = '', $view = 'list', $mode = '', $taxonomy = '', $title = '', $content ) {

  global $post; // Must be declared

  if ( empty( $id ) )
    $id = $post->ID;

  $html = '';
  $html_before = '';
  $html_after = '';

  $defaults = array(
    'has_password'        => false
  , 'ignore_sticky_posts' => 1
  );

  // Children of the current page
  if ( $mode == 'children' && is_page() ) {
    $args = array(
      'order'             => 'asc'
    , 'orderby'           => 'menu_order'
    , 'post_parent'       => $id
    , 'post_type'         => 'page'
    , 'posts_per_page'    => -1
    );
  }

  if ( $mode == 'tagged' ) {
    if ( empty( $taxonomy ) )
      $taxonomy = 'post_tag';

    $tax_query = array(
      array(
        'taxonomy'          => $taxonomy
      , 'field'             => 'slug'
      , 'terms'             => $post->post_name
      )
    );
    $args = array(
      'order'             => 'desc'
    , 'orderby'           => 'date'
    , 'tax_query'         => $tax_query
    );
  }

  if ( empty( $args ) )
    return;

  $query = new WP_Query( wp_parse_args( $args, $defaults ) );

  if ( $query->have_posts() ) {
    while ( $query->have_posts() ) : $query->the_post();
      ob_start(); // Start output buffering; necessary because get_template_part echoes contents
      get_template_part( 'content', $view );
      $html .= ob_get_clean(); // Clean the output buffer after every cycle
    endwhile;
  }
  wp_reset_postdata();

  // HTML scaffolding specific to each view
  if ( !empty( $html ) ) {
    if ( $view == 'gallery' ) {
      $html_before = '<section class="' . $view . '-view view-posts img-group img-group-3">';
    } else {
      $html_before = '<section class="' . $view . '-view view-posts">';
    }
    if ( !empty( $title ) )
      $html_before .= '<h2>' . $title . '</h2>';
    if ( !empty( $content ) )
      $html_before .= '<p>' . wptexturize( do_shortcode( $content ) ) . '</p>'; // @TODO: sanitize content
    $html_after = '</section>';
  }

  $html = $html_before . $html . $html_after;

  return $html;

} endif;
