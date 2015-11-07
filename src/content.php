<?php // ==== CONTENT: GENERAL ==== // ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="http://schema.org/Article">
  <header class="entry-header">
    <?php do_action( 'pendrell_entry_header' ); ?>
  </header>
  <div class="entry-content">
    <?php the_content(); pendrell_nav_page_links(); ?>
  </div>
  <footer class="entry-footer">
    <?php do_action( 'pendrell_entry_footer' ); ?>
  </footer>
  <?php pendrell_comments_template(); ?>
</article>
