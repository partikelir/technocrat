<?php
/**
 * Template Name: Full-width
 *
 * Description: Use this page template to remove the sidebar from any page. Post thumbnail will be displayed in the top right corner.
 *
 * @package WordPress
 * @subpackage Pendrell
 * @since Pendrell 0.4
 */

get_header(); ?>

	<div id="primary" class="site-content">
		<div id="content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content', 'page' ); ?>
				<?php comments_template( '', true ); ?>
			<?php endwhile; ?>

		</div><!-- #content -->
	</div><!-- #primary -->

  <div id="secondary" class="widget-area" role="complementary">
    <?php the_post_thumbnail(); ?>
  </div><!-- #secondary -->

<?php get_footer(); ?>