<?php // ==== PORTFOLIO ==== //

// @TODO: this needs to be converted into a custom post type



// Portfolio categories; add or remove any slug to this array to enable matching categories with portfolio functionality
$pendrell_portfolio_cats = array( 'design', 'photography', 'creative' );



function pendrell_portfolio_init() {
  // Thumbnails on the portfolio screen
  add_image_size( 'portfolio-navigation', 300, 200, true );
}
//add_action( 'init', 'pendrell_portfolio_init' );



// Body class filter
function pendrell_portfolio_body_class( $classes ) {
  if ( pendrell_is_portfolio() ) {
    //$classes[] = 'portfolio';
  }
  return $classes;
}
add_filter( 'body_class', 'pendrell_portfolio_body_class' );



// This lets Pendrell know to make portfolio items full-width
function pendrell_portfolio_full_width() {
  if ( is_singular() && in_category( array( 'design', 'photography' ) ) ) {
    return true;
  } else {
    return false;
  }
}
add_filter( 'pendrell_full_width', 'pendrell_portfolio_full_width' );




// Test to see whether we are viewing a portfolio post or category archive
function pendrell_is_portfolio() {
  global $pendrell_portfolio_cats;
  if (
    is_category( $pendrell_portfolio_cats )
    || ( is_singular() && in_category( $pendrell_portfolio_cats ) )
  ) {
    return true;
  } else {
    return false;
  }
}



// Modify how many posts per page are displayed in different contexts (e.g. more portfolio items on category archives)
function pendrell_portfolio_pre_get_posts( $query ) {
  // Source: http://wordpress.stackexchange.com/questions/21/show-a-different-number-of-posts-per-page-depending-on-context-e-g-homepage
  if ( pendrell_is_portfolio() && $query->is_main_query() ) {
    $query->set( 'posts_per_page', 24 );
  }
}
add_action( 'pre_get_posts', 'pendrell_portfolio_pre_get_posts' );
