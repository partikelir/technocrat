<?php // ==== FOOTER ==== // ?>

      </div>
      <div id="wrap-footer" class="wrap-footer">
        <div class="site-footer">
        	<nav id="site-footer-navigation">
            <?php wp_nav_menu( array( 'theme_location' => 'footer', 'menu_id' => 'menu-footer', 'menu_class' => 'menu-inline' ) ); ?>
          </nav>
          <div class="buttons">
            <a href="#top" class="button" rel="nofollow"><?php echo pendrell_icon_text( 'top-link', __( 'Top', 'pendrell' ) ); ?></a>
          </div>
          <footer id="colophon" class="site-colophon">
            <?php do_action( 'pendrell_footer' ); ?>
        	</footer>
        </div>
      </div>
    </div>
  <?php wp_footer(); ?>
  </body>
</html>