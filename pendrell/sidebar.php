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

  if ( is_active_sidebar( 'sidebar-1' ) && !pendrell_is_place() ) : ?>
		<div id="secondary" class="widget-area" role="complementary">
			<?php dynamic_sidebar( 'sidebar-1' ); ?>
		</div><!-- #secondary --><?php
  endif;

  if ( pendrell_is_place() ) :
    pendrell_places_sidebar();
  endif;
