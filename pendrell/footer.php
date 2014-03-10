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
	</div><!-- #main .wrapper -->
	<footer id="colophon" class="site-footer" role="contentinfo">
    <div class="footer-buttons">
      <span class="back-to-top-link button"><a href="#top"><?php _e( 'Back to top', 'pendrell' ); ?></a></span>
    </div>
    <nav class="footer-navigation" role="navigation">
      <?php wp_nav_menu( array( 'theme_location' => 'footer', 'menu_class' => 'nav-footer' ) ); ?>
    </nav>
		<div class="site-info">
			<?php printf( __( 'Powered by <a href="%1$s" title="%2$s" rel="generator">WordPress</a> and <a href="%3$s" title="%4$s">Pendrell %5$s</a>.', 'pendrell' ),
          esc_url( __( 'http://wordpress.org/', 'pendrell' ) ),
          esc_attr( __( 'Semantic Personal Publishing Platform', 'pendrell' ) ),
          esc_url( __( 'http://github.com/synapticism/pendrell', 'pendrell' ) ),
          esc_attr( __( 'Pendrell theme by Alexander Synaptic', 'pendrell' ) ),
          PENDRELL_VERSION
      ); ?> just testing what happens when I write a lot more text here!
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>