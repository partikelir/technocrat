<?php
/**
 * @package WordPress
 * @subpackage Pendrell
 * @since Pendrell 0.4
 */

get_header(); ?>

	<section id="primary" class="site-content">
		<div id="content" role="main">
			<?php while ( have_posts() ) : the_post();
				get_template_part( 'content', 'image-attachment' );
			endwhile;
			pendrell_content_nav( 'nav-below' );
			comments_template( '', true ); ?>
		</div><!-- #content -->
	</section><!-- #primary -->

<?php get_footer(); ?>