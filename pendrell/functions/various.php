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
	if ( pendrell_is_portfolio() ) {
		$classes[] = 'full-width portfolio';
	}

  if (
    !is_active_sidebar( 'sidebar-1' )
    || is_page_template( 'page-templates/full-width.php' )
  ) {
    $classes[] = 'full-width';
  }

  if ( ! is_multi_author() )
    $classes[] = 'single-author';

	return $classes;
}
add_filter( 'body_class', 'pendrell_body_class' );



// Adjusts content_width value for full-width and single image attachment templates, and when there are no active widgets in the sidebar.
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



// Test whether the current item is a place
function pendrell_is_place() {
  if ( post_type_exists( 'place') ) {
    if ( get_post_type() === 'place' ) {
      return true;
    }
  }
  return false;
}
