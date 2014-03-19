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

if (
  // Make sure the main sidebar is active
  is_active_sidebar('sidebar-main')

  // Don't display the sidebar for certain post formats
  && !is_tax( 'post_format', array( 'post-format-aside', 'post-format-link', 'post-format-quote', 'post-format-status' ) )

  // Don't display on portfolios
  && !pendrell_is_portfolio()

  // Don't display on places or place archives
  && !pendrell_is_place()
) { ?>
	<div id="secondary" class="widget-area" role="complementary">
		<?php dynamic_sidebar( 'sidebar-main' ); ?>
	</div><!-- #secondary --><?php
} elseif (
  pendrell_is_place()
) {
  ubik_places_widget();
}
