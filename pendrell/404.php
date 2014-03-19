<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
 * @subpackage Pendrell
 * @since Pendrell 0.4
 */

get_header(); ?>

	<section id="primary" class="site-content">
		<div id="content" role="main">
			<?php get_template_part( 'content', 'none' ); ?>
		</div><!-- #content -->
	</section><!-- #primary -->

<?php get_footer(); ?>