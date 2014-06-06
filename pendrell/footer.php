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
	</div><!-- #content .wrapper -->
	<footer id="colophon" class="site-footer" role="contentinfo">
    <nav id="menu-footer" class="inline-menu" role="navigation">
      <?php wp_nav_menu( array( 'theme_location' => 'footer', 'menu_class' => 'menu-footer' ) ); ?>
    </nav>
    <div class="site-footer-buttons">
      <span class="back-to-top-link button"><a href="#top"><?php _e( 'Back to top', 'pendrell' ); ?></a></span>
    </div>
    <div class="site-footer-info">
      <?php pendrell_footer_info(); ?>
    </div><!-- .site-footer-info -->
	</footer><!-- .site-footer -->
</div><!-- #page -->
<?php wp_footer(); ?>
</body>
</html>