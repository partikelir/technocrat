<?php
/**
 * @package WordPress
 * @subpackage Pendrell
 * @since Pendrell 0.4
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php get_template_part( 'content', 'none' ); ?>
		</main>
	</section>

<?php get_footer(); ?>