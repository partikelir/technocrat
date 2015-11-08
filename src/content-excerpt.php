<?php // ==== CONTENT: EXCERPT ==== // ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <header class="entry-header">
    <?php do_action( 'pendrell_entry_header' ); ?>
  </header>
  <div class="entry-content">
    <?php the_excerpt(); ?>
  </div>
  <footer class="entry-footer">
    <?php do_action( 'pendrell_entry_footer' ); ?>
  </footer>
</article>
