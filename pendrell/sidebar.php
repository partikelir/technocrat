<?php
/**
 * The sidebar containing the main widget area
 *
 * If no active widgets are in the sidebar, hide it completely.
 *
 * @package WordPress
 * @subpackage Pendrell
 * @since Pendrell 0.4
 */

// Don't display the sidebar at all for certain post formats and custom post types
if (
  is_active_sidebar('sidebar-1')
  && !has_post_format('aside')
  && !has_post_format('link')
  && !has_post_format('quote')
  && !has_post_format('status')
  && !pendrell_is_place()
  && !pendrell_is_portfolio()
) { ?>
	<div id="secondary" class="widget-area" role="complementary">
		<?php dynamic_sidebar( 'sidebar-1' ); ?>
	</div><!-- #secondary --><?php
}

if ( pendrell_is_place() ) {
  pendrell_places_sidebar();
}
