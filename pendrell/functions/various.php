<?php

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



// Removes the ".recentcomments" style added to the header for no good reason
// http://www.narga.net/how-to-remove-or-disable-comment-reply-js-and-recentcomments-from-wordpress-header
function pendrell_remove_recent_comments_style() {
  global $wp_widget_factory;
  remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
}
add_action( 'widgets_init', 'pendrell_remove_recent_comments_style' );



// Allow HTML in author descriptions on single user blogs
remove_filter( 'pre_user_description', 'wp_filter_kses' );



// Allow HTML in author descriptions on single user blogs
if ( !is_multi_author() ) {
	remove_filter( 'pre_user_description', 'wp_filter_kses' );
}



// Ditch the default gallery styling, yuck
add_filter( 'use_default_gallery_style', '__return_false' );
