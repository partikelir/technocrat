<?php // ==== UBIK LINKS ==== //

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
      <div id="secondary" class="site-sidebar" role="complementary">
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
