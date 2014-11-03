<?php
/**
 * Template Name: Full-width
 *
 * Description: Use this page template to remove the sidebar from any page.
 *
 * @package WordPress
 * @subpackage Pendrell
 * @since Pendrell 0.4
 */

get_header(); ?>

  <section id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
      <?php while ( have_posts() ) : the_post();
        pendrell_template_part();
      endwhile; ?>
    </main>
    <?php pendrell_nav_page( 'nav-below' ); ?>
  </section>

<?php get_footer(); ?>