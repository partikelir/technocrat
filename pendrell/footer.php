<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content and the closing of the
 * #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Pendrell
 * @since Pendrell 0.4
 */
?>
    </div><!-- .site-content -->
	</div><!-- .site-content-wrapper -->
  <div class="site-footer-wrapper">
  	<footer id="colophon" class="site-footer" role="contentinfo">
      <nav id="site-footer-navigation" role="navigation">
        <?php wp_nav_menu( array( 'theme_location' => 'footer', 'menu_class' => 'menu-footer inline-menu' ) ); ?>
      </nav><!-- #site-footer-navigation -->
      <div class="site-footer-buttons">
        <span class="button"><a href="#top"><?php _e( 'Back to top', 'pendrell' ); ?></a></span>
      </div>
      <div class="site-footer-info">
        <?php pendrell_footer_info(); ?>
      </div><!-- .site-footer-info -->
  	</footer><!-- .site-footer -->
  </div><!-- .site-footer-wrapper -->
</div><!-- #page -->
<?php wp_footer(); ?>
</body>
</html>