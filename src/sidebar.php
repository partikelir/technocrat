<?php
/**
 * The sidebar containing the main widget area
 *
 * @package WordPress
 * @subpackage Pendrell
 * @since Pendrell 0.4
 */
?>
<div id="wrap-sidebar">
  <?php if ( is_active_sidebar( 'sidebar-main' ) ) { ?>
  <div id="secondary" class="widget-area" role="complementary">
  	<?php dynamic_sidebar( 'sidebar-main' ); ?>
  </div>
  <?php } ?>
</div>
