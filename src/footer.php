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
        <div class="site-footer">
        	<nav id="site-footer-navigation" role="navigation">
            <?php wp_nav_menu( array( 'theme_location' => 'footer', 'menu_class' => 'menu-footer inline-menu' ) ); ?>
          </nav>
          <div class="site-footer-interface">
            <a href="#top" class="button" rel="nofollow"><?php echo pendrell_icon( 'top-link', __( 'Top', 'pendrell' ) ); ?></a>
          </div>
          <footer id="colophon" class="site-colophon" role="contentinfo">
            <?php do_action( 'pendrell_footer' ); ?>
        	</footer>
        </div>
      </div>
    </div>
  <?php wp_footer(); ?>
  </body>
</html>