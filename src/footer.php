<?php get_sidebar(); ?>
      </div>
      <div id="wrap-footer" class="wrap-footer">
        <div class="site-footer">
        	<nav id="site-footer-navigation">
            <?php do_action( 'pendrell_footer_navigation' ); ?>
          </nav>
          <footer id="colophon" class="site-colophon">
            <?php do_action( 'pendrell_footer_colophon' ); ?>
        	</footer>
        </div>
      </div>
    </div>
  <?php wp_footer(); ?>
  </body>
</html>