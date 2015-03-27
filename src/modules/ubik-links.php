<?php // ==== UBIK LINKS ==== //

require_once( trailingslashit( get_stylesheet_directory() ) . 'modules/ubik-links/ubik-links.php' );

// Display the Ubik Links sidebar
function pendrell_links_sidebar( $sidebar ) {
  if ( is_page_template( UBIK_LINKS_PAGE_TEMPLATE ) ) {

    // Retrieve the list of all categories
    $cats = ubik_links_categories();

    // Add the links page template to the bottom of the list (relies on `get_permalink`)
    $cats[] = '<strong><a class="link-category" href="' . get_permalink() . '">' . __( 'All links', 'pendrell' ) . '</a></strong>';
    $cats = ubik_links_categories_list( $cats );

    // Output the links sidebar
    $sidebar = '<aside id="ubik-links-search-widget" class="widget widget-links-search"><h2>' . __( 'Search links', 'pendrell' ) . '</h2>' . ubik_links_search_form() . '</aside>';

    // A list of link categories
    if ( !empty( $cats ) )
      $sidebar .= '<aside id="ubik-links-categories-widget" class="widget widget-links-categories"><h2>' . __( 'Link categories', 'pendrell' ) . '</h2>' . $cats . '</aside>';
  }
  return $sidebar;
}
add_filter( 'pendrell_sidebar', 'pendrell_links_sidebar' );
