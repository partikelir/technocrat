<?php
/**
 * @package WordPress
 * @subpackage Pendrell
 * @since Pendrell 0.4
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php pendrell_content_template( 'none' ); ?>
		</main><!-- #content -->
	</section><!-- #primary -->

<?php get_footer(); ?>