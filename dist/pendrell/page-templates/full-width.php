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
          get_template_part( 'content' );
				pendrell_comments_template();
        endwhile;
      ?>
		</main>
	</section>

<?php get_footer(); ?>