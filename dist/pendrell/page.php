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
          pendrell_content_template();
        endwhile;
        pendrell_comments_template();
      ?>
		</main><!-- #content -->
	</section><!-- #primary -->

<?php pendrell_sidebar(); ?>
<?php get_footer(); ?>