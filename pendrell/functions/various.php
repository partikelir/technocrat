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
  if ( pendrell_is_full_width() ) {
    $classes[] = 'full-width';
  }

  if ( !is_multi_author() )
    $classes[] = 'single-author';

	return $classes;
}
add_filter( 'body_class', 'pendrell_body_class' );



// Abstracted function to test whether the current view is full-width
function pendrell_is_full_width() {
  // Allow other functions to pass the test
  $full_width_test = apply_filters( 'pendrell_full_width', $full_width_test = false );

  // If we're on a full-width page, attachment, or there is no sidebar active let's expand the viewing window
  if (
    is_page_template( 'page-templates/full-width.php' )
    || is_attachment()
    || !is_active_sidebar( 'sidebar-main' )
    || $full_width_test === true
  ) {
    return true;
  } else {
    return false;
  }
}
