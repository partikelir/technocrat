<?php
/**
 * @package WordPress
 * @subpackage Pendrell
 * @since Pendrell 0.4
 */

get_header(); ?>

	<section id="primary" class="site-content">
		<div id="content" role="main">
			<?php get_template_part( 'content', pendrell_content_template( 'none' ) ); ?>
		</div><!-- #content -->
	</section><!-- #primary -->

<?php get_footer(); ?>