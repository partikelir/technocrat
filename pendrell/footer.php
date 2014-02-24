<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content and the closing of the
 * #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>
	</div><!-- #main .wrapper -->
	<footer id="colophon" role="contentinfo">
		<div class="site-info">
			<?php printf( __( '<a href="%1$s" title="%2$s" rel="generator">Powered by WordPress</a> and themed with <a href="%3$s" title="%4$s">Pendrell %5$s</a>.', 'pendrell' ),
          esc_url( __( 'http://wordpress.org/', 'pendrell' ) ),
          esc_attr( __( 'Semantic Personal Publishing Platform', 'pendrell' ) ),
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