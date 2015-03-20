<?php
/**
 * The sidebar containing the main widget area
 *
 * @package WordPress
 * @subpackage Pendrell
 * @since Pendrell 0.4
 */
?>
<div id="wrap-sidebar" class="wrap-sidebar">
  <?php if ( is_active_sidebar( 'sidebar-main' ) ) { ?>
  <div id="secondary" class="site-sidebar" role="complementary">
    <div class="social"><?php echo pendrell_author_social(); ?></div>
  	<?php dynamic_sidebar( 'sidebar-main' ); ?>
  </div>
  <?php } ?>
</div>
