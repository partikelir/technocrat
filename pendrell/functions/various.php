<?php

// Body class filter
function pendrell_body_class( $classes ) {
	if ( pendrell_is_portfolio() ) {
		$classes[] = 'full-width portfolio';
	}

  if ( ! is_active_sidebar( 'sidebar-1' ) || is_page_template( 'page-templates/full-width.php' ) )
    $classes[] = 'full-width';

  if ( is_page_template( 'page-templates/front-page.php' ) ) {
    $classes[] = 'template-front-page';
    if ( has_post_thumbnail() )
      $classes[] = 'has-post-thumbnail';
    if ( is_active_sidebar( 'sidebar-2' ) && is_active_sidebar( 'sidebar-3' ) )
      $classes[] = 'two-sidebars';
  }

  if ( ! is_multi_author() )
    $classes[] = 'single-author';

	return $classes;
}
add_filter( 'body_class', 'pendrell_body_class' );



// Adjusts content_width value for full-width and single image attachment templates, and when there are no active widgets in the sidebar.
function pendrell_content_width() {
  if ( is_page_template( 'page-templates/full-width.php' ) || is_attachment() || ! is_active_sidebar( 'sidebar-1' ) | pendrell_is_portfolio() ) {
    global $content_width;
    $content_width = 960;
  }
}
add_action( 'template_redirect', 'pendrell_content_width' );



// Test to see whether we are viewing a portfolio post or category archive
function pendrell_is_portfolio() {
	global $pendrell_portfolio_cats;
	if ( is_category( $pendrell_portfolio_cats ) || ( is_singular() && in_category( $pendrell_portfolio_cats ) ) ) {
		return true;
	} else {
		return false;
	}
}



// Google Analytics code
function pendrell_analytics() {
	if ( PENDRELL_GOOGLE_ANALYTICS_CODE ) { ?>
				<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '<?php echo PENDRELL_GOOGLE_ANALYTICS_CODE; ?>']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script><?php
	}
}
add_action( 'wp_footer', 'pendrell_analytics' );



// Allow HTML in author descriptions on single user blogs
remove_filter( 'pre_user_description', 'wp_filter_kses' );

// Allow HTML in author descriptions on single user blogs
if ( !is_multi_author() ) {
	remove_filter( 'pre_user_description', 'wp_filter_kses' );
}

// Ditch the default gallery styling, yuck
add_filter( 'use_default_gallery_style', '__return_false' );
