<?php
/**
 * The sidebar containing the main widget area
 *
 * @package WordPress
 * @subpackage Pendrell
 * @since Pendrell 0.4
 */

if ( is_active_sidebar( 'sidebar-main' ) ) { ?>
	<div id="secondary" class="widget-area" role="complementary">
		<?php dynamic_sidebar( 'sidebar-main' ); ?>
	</div><!-- #secondary --><?php
} else {
  // Sidebar fallback functionality
}
