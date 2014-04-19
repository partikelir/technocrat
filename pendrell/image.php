<?php
/**
 * @package WordPress
 * @subpackage Pendrell
 * @since Pendrell 0.4
 */

get_header(); ?>

  <section id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
      <?php while ( have_posts() ) : the_post();
        get_template_part( 'content', pendrell_content_template( 'image-attachment' ) );
      endwhile;
      pendrell_content_nav( 'nav-below' ); ?>
    </main><!-- #content -->
  </section><!-- #primary -->

<?php get_footer(); ?>