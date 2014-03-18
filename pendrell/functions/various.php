<?php // === VARIOUS === //

// Capture search query for jQuery highlighter
function pendrell_search_highlighter() {
  $query = get_search_query();
  if ( strlen($query) > 0 ) {
    ?><script type="text/javascript">var pendrellSearchQuery  = "<?php echo $query; ?>";</script><?php
  }
}
add_action( 'wp_print_scripts', 'pendrell_search_highlighter' );



// Body class filter
function pendrell_body_class( $classes ) {

  // Full width page template
  if (
    !is_active_sidebar( 'sidebar-main' )
    || is_page_template( 'page-templates/full-width.php' )
  ) {
    $classes[] = 'full-width';
  }

  if ( ! is_multi_author() )
    $classes[] = 'single-author';

	return $classes;
}
add_filter( 'body_class', 'pendrell_body_class' );



// Adjusts content_width value for full-width and single image attachment templates, and when there are no active widgets in the sidebar
function pendrell_content_width() {
  if (
    is_page_template( 'page-templates/full-width.php' )
    || is_attachment()
    || !is_active_sidebar( 'sidebar-1' )
    || pendrell_is_portfolio()
  ) {
    global $content_width;
    $content_width = 960;
  }
}
add_action( 'template_redirect', 'pendrell_content_width' );
