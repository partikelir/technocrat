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
      </div>
      <div id="wrap-footer" class="wrap-footer">
      	<footer id="colophon" class="site-footer" role="contentinfo">
          <nav id="site-footer-navigation" role="navigation">
            <?php wp_nav_menu( array( 'theme_location' => 'footer', 'menu_class' => 'menu-footer inline-menu' ) ); ?>
          </nav>
          <div class="site-footer-buttons">
            <a href="#top" class="button" rel="nofollow"><?php _e( 'Back to top', 'pendrell' ); ?></a>
          </div>
          <?php do_action( 'pendrell_footer' ); ?>
      	</footer>
      </div>
    </div>
  <?php wp_footer(); ?>
  </body>
</html>