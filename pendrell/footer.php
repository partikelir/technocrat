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
    <nav id="menu-footer" class="inline-menu" role="navigation">
      <?php wp_nav_menu( array( 'theme_location' => 'footer', 'menu_class' => 'menu-footer' ) ); ?>
    </nav>
		<div class="site-info">
			<?php printf( __( '&copy;2011&#8211;%1$s <a href="%2$s" title="%3$s" rel="author">%4$s</a>. Powered by <a href="%5$s" title="%6$s" rel="generator">WordPress</a> and <a href="%7$s" title="%8$s">Pendrell %9$s</a>.', 'pendrell' ),
          date( "Y" ),
          esc_url( __( 'http://alexandersynaptic.com/', 'pendrell' ) ),
          esc_attr( __( 'Homepage of Alexander Synaptic', 'pendrell' ) ),
          esc_attr( __( 'Alexander Synaptic', 'pendrell' ) ),
          esc_url( __( 'http://wordpress.org/', 'pendrell' ) ),
          esc_attr( __( 'WordPress publishing platform', 'pendrell' ) ),
          esc_url( __( 'http://github.com/synapticism/pendrell', 'pendrell' ) ),
          esc_attr( __( 'Pendrell theme by Alexander Synaptic', 'pendrell' ) ),
          PENDRELL_VERSION
      ); ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->
<?php wp_footer(); ?>
</body>
</html>