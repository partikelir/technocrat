<?php
/**
 * @package WordPress
 * @subpackage Pendrell
 * @since Pendrell 0.4
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="article">
  <header class="entry-header">
    <?php do_action( 'pendrell_entry_header' ); ?>
  </header>
  <div class="entry-content">
    <?php the_content(); pendrell_nav_link_pages(); ?>
  </div>
  <footer class="entry-meta">
    <?php do_action( 'pendrell_entry_footer' ); ?>
  </footer>
  <?php pendrell_comments_template(); ?>
</article>
